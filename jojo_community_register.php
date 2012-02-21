<?php
/**
 *                    Jojo CMS
 *                ================
 *
 * Copyright 2007-2009 Harvey Kane <code@ragepank.com>
 * Copyright 2007-2009 Michael Holt <code@gardyneholt.co.nz>
 *
 * See the enclosed file license.txt for license information (LGPL). If you
 * did not receive this file, see http://www.fsf.org/copyleft/lgpl.html.
 *
 * @author  Harvey Kane <code@ragepank.com>
 * @license http://www.fsf.org/copyleft/lgpl.html GNU Lesser General Public License
 * @link    http://www.jojocms.org JojoCMS
 */

class Jojo_Plugin_Jojo_Community_Register extends Jojo_Plugin
{

    function _getContent()
    {
        global $smarty;
        $content = array('javascript' => '');

        $approvecode = Jojo::getFormData('approvecode', false);
        $deletecode  = Jojo::getFormData('deletecode',  false);

        /* handle usergroup approvals */
        if ($approvecode) {
            $groupid = Jojo::getFormData('groupid', false);
            $user = Jojo::selectRow("SELECT userid, us_firstname, us_lastname FROM {user} WHERE us_approvecode=?", $approvecode);
            if (!count($user)) {
                $content['content'] = 'This approval link is invalid.';
                return $content;
            }
            /* add user to group */
            Jojo::insertQuery("REPLACE INTO {usergroup_membership} SET userid=?, groupid=?", array($user['userid'], $groupid));

            $content['content'] = $user['us_firstname'] . ' ' . $user['us_lastname'] . ' added to ' . $groupid . ' group.';
            return $content;
        }

        /* handle user deletions */
        if ($deletecode) {
            $groupid = Jojo::getFormData('groupid', false);
            $user = Jojo::selectRow("SELECT userid, us_firstname, us_lastname FROM {user} WHERE us_deletecode=?", $deletecode);
            if (!count($user)) {
                $content['content'] = 'This delete link is invalid, or the user has already been deleted.';
                return $content;
            }
            Jojo::deleteQuery("DELETE FROM {user} WHERE userid=?", $user['userid']);
            Jojo::deleteQuery("DELETE FROM {usergroup_membership} WHERE userid=?", $user['userid']);

            $content['content'] = $user['us_firstname'] . ' ' . $user['us_lastname'] . ' deleted.';
            return $content;
        }

        $redirect = Jojo::getFormData('redirect', false);
        $errors  = array();

        /* register button pressed */
        $message = '';
        if (isset($_POST['submit'])) {
            
            $table = &Jojo_Table::singleton('user');
            
            /* Retrieve all values from form and set the field values */
            foreach ($table->getFieldNames() as $fieldname) {
                if (Jojo::getFormData('fm_' . $fieldname, false) !== false) {
                    $table->setFieldValue($fieldname, Jojo::getFormData('fm_' . $fieldname));
                }
            }            
            /* Check for errors */
            $errors = $table->fieldErrors();
            if (is_array($errors) && count($errors)) {
                
            } else {
            
                /* additional error checking */
                $errors = array();
                /* Check user does not already exist */
                $user = Jojo::selectRow("SELECT userid FROM {user} WHERE us_login = ? AND us_login != ''", array($table->getFieldValue('us_login')));
                if (count($user)) {
                    $errors[] = 'The username "' . $table->getFieldValue('us_login') . '" is already taken';
                }
                /* Check email address is not already in the system unless the options allow duplicate emails */
                if (Jojo::getOption('users_require_unique_email', 'yes') == 'yes') {
                    $user = Jojo::selectRow("SELECT userid FROM {user} WHERE us_email = ? AND us_email != ''", array($table->getFieldValue('us_email')));
                    if (count($user)) {
                        $errors[] = 'The email "' . $table->getFieldValue('us_email') . '" is already in use by another user';
                    }
                }
                /* validate CAPTCHA */
                if (Jojo::getOption('jojo_community_register_captcha') == 'yes') {
                    $captchacode = Jojo::getFormData('CAPTCHA','');
                    if (!PhpCaptcha::Validate($captchacode)) {
                        $errors[] = 'Incorrect Spam Prevention Code entered';
                    }
                }
            }
           
            if (!count($errors)) {
                $approvecode = Jojo::randomString(16);
                $deletecode  = Jojo::randomString(16);
                
                $table->setFieldValue('us_approvecode', $approvecode);
                $table->setFieldValue('us_deletecode',  $deletecode);
                
                /* save the record */
                $res = $table->saveRecord();
                
                if ($res === false) {
                    /* save failed */
                } else {
                    /* save successful */
                    $userid = $table->getRecordID();
                    
                    /* add the new user to the default group specified in Options */
                    $defaultgroup = Jojo::getOption('defaultgroup');
                    if ($defaultgroup != '') {
                        Jojo::insertQuery("INSERT INTO {usergroup_membership} (userid, groupid) VALUES (?, ?)", array($userid, $defaultgroup));
                    }
                    $message = 'Your registration was successful.';
                    $smarty->assign('success', true);
                    
                    /* log them in */
                    $_USERID = $userid;
                    $_SESSION['userid'] = $_USERID;

                    $_USERGROUPS = array('everyone');
                    $groups = Jojo::selectQuery("SELECT * FROM {usergroup_membership} WHERE userid = ?", $_USERID);
                    foreach ($groups as $group) {
                        $_USERGROUPS[] = $group['groupid'];
                    }
                    $username = $table->getFieldValue('us_login');
                    $_SESSION['username'] = $username;
                    $emailaddress = $table->getFieldValue('us_email');
                    $firstname = $table->getFieldValue('us_firstname');
                    $lastname = $table->getFieldValue('us_lastname');
                    
                    /* allow plugins to add code here */
                    Jojo::runHook('register_complete');
                    
                    /* email the admin some links for adding the new user into additional groups */
                    $email  = "A new User has registered on " . _SITEURL . "\n\n";
                    $email .= "Username: " . $username . ($firstname ? ' - ' . $firstname : '') . ($lastname ? " " . $lastname : '') . "\n";
                    $email .= $emailaddress != '' ? "Email: " . $emailaddress . "\n" : '';
    
                    /* provide links for adding the user into each group */
                    $allgroups = Jojo::selectQuery("SELECT * FROM {usergroups} WHERE groupid!='notloggedin' ORDER BY groupid");
                    foreach ($allgroups as $g) {
                        if ($g['groupid'] != $defaultgroup) { //no need to include a link for adding them to the default group
                            $email .= "\nTo add the user to the '" . $g['gr_name'] . "' group\n";
                            $email .= _SITEURL . '/register/approve/' . $g['groupid'] . '/' . $approvecode . "/\n";
                        }
                    }
                    /* and a link for deleting the user */
                    $email .= "\nTo DELETE this User, click the following link\n";
                    $email .= _SITEURL . '/register/delete/' . $deletecode . "/\n";    
                    $email .= Jojo::emailFooter();
    
                    /* Email notification to webmaster + admin person*/
                    Jojo::simplemail(_WEBMASTERNAME, _WEBMASTERADDRESS, 'User Registration - ' . _SITETITLE, $email);
                    Jojo::simplemail(_FROMNAME, _CONTACTADDRESS, 'User Registration - ' . _SITETITLE, $email);
    
                    if ($redirect) {
                        Jojo::redirect(_SITEURL . '/' . $redirect, 302);
                    }
                }
            }
        }
        
        $smarty->assign('errors', $errors);
        
        if (!isset($table)) $table = &Jojo_Table::singleton('user');
        
        $fieldsHTML = $table->getHTML('edit');
        
        foreach ($fieldsHTML as $f) {
            if ($f['type'] == 'bbeditor') {
                $smarty->assign('includebbeditor', true);
            } elseif ($f['type'] == 'wysiwygeditor') {
                $smarty->assign('includewysiwygeditor', true);
            } elseif ($f['type'] == 'texteditor') {
                $smarty->assign('includewysiwygeditor', true);
            }
            if (!empty($f['js'])) $content['javascript'] .= "\n".$f['js'];
        }

        $content['head']= $smarty->fetch('external/date_input_head.tpl');
        
        /* Fetch list of tabs from fields */
        $data = Jojo::selectQuery("SELECT fd_tabname AS tabname FROM {fielddata} WHERE fd_table='user' ORDER BY fd_tabname");
        
        /* Build array of tabs from all the fields */
        $tabnames = array();
        foreach ($data as $i => $v) {
            $tabname = $data[$i]['tabname'];
            $tabnames[$tabname]['tabname'] = $tabname;
        }
    
        /* Sort the tabs */
        ksort($tabnames);
        
        /* Let smarty know about the tab names */
        $_tabnames = array_values($tabnames);
        $smarty->assign('tabnames', $_tabnames);
        $smarty->assign('numtabs', count($tabnames));
        
        $smarty->assign('fields', $fieldsHTML);

        $smarty->assign('redirect', $redirect);
        $smarty->assign('message',  $message);
        $smarty->assign('error',    implode("<br />\n", $errors));
        $content['content'] = $smarty->fetch('jojo_community_register.tpl');
        return $content;
    }

    function getCorrectUrl()
    {
        $approvecode = Jojo::getFormData('approvecode', false);
        $deletecode  = Jojo::getFormData('deletecode',  false);
        $redirect    = Jojo::getGet('redirect', false);

        if ($approvecode || $deletecode) {
            return _PROTOCOL . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        }

        if ($redirect) {
            return parent::getCorrectUrl() . $redirect . '/';
        }
        return parent::getCorrectUrl();
    }

}