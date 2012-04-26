<?php
/**
 *                    Jojo CMS
 *                ================
 *
 * Copyright 2009 Harvey Kane <code@ragepank.com>
 * Copyright 2009 Michael Holt <code@gardyneholt.co.nz>
 *
 * See the enclosed file license.txt for license information (LGPL). If you
 * did not receive this file, see http://www.fsf.org/copyleft/lgpl.html.
 *
 * @author  Harvey Kane <code@ragepank.com>
 * @license http://www.fsf.org/copyleft/lgpl.html GNU Lesser General Public License
 * @link    http://www.jojocms.org JojoCMS
 * @package jojo_core
 */

class Jojo_Plugin_Jojo_community_edit_profile extends Jojo_Plugin
{

    function _getContent()
    {
        global $smarty, $_USERID;
        $errors = array();
        $content = array('javascript' => '');
        
        if (empty($_USERID)) {
            echo "There was an error accessing your profile. Please check you are logged in and try again.";
            exit();
        }

        if (isset($_POST['update'])) {
            
            $table = &Jojo_Table::singleton('user');
            $table->getRecord($_USERID);
            
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
            }
            
            $public_uri = Jojo_Plugin_Jojo_Community_Profile::_getPrefix() . '/' . $_USERID . '/' . Jojo::cleanUrl($table->getFieldValue('us_login')).'/';

            /* no errors */
            if (!count($errors)) {
                /* save the record */
                $res = $table->saveRecord();
                
                if ($res === false) {
                    /* save failed */
                    $errors[] = 'An error occured. Please contact the webmaster if this error continues.';
                    Jojo::runHook('edit_profile_save_error');
                } else {
                    /* success */
                    $message = 'Your user profile has been updated.';
                    if (Jojo::getOption('jojo_community_public_profile', 'no') == 'yes') $message .= ' View your <a href="' . $public_uri . '">public profile</a>.';
                    Jojo::runHook('edit_profile_save_success');
                }
                
            } else {
                /* Errors - return to form */
            }

        } else {
            
        }
        
        if (!isset($table)) $table = &Jojo_Table::singleton('user');
        $table->getRecord($_USERID);
        $fieldsHTML = $table->getHTML('edit');
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
        
        foreach ($fieldsHTML as $f) {
            if ($f['type'] == 'bbeditor') {
                $smarty->assign('includebbeditor', true);
            } elseif ($f['type'] == 'wysiwygeditor') {
                $smarty->assign('includewysiwygeditor', true);
            } elseif ($f['type'] == 'texteditor') {
                $smarty->assign('includewysiwygeditor', true);
            }
            if (!empty($f['js'])) $content['javascript'] .= "\n" . $f['js'];
        }

        $content['head']= $smarty->fetch('external/date_input_head.tpl');
        
        $public_uri = Jojo_Plugin_Jojo_Community_Profile::_getPrefix() . '/' . $_USERID . '/' . Jojo::cleanUrl($table->getFieldValue('us_login')) . '/';
        $smarty->assign('public_uri', $public_uri);
        
        $smarty->assign('message', (isset($message) ? $message : ''));
        $smarty->assign('error', implode("<br />\n", $errors));
        $content['content'] = $smarty->fetch('jojo_community_edit_profile.tpl');

        return $content;
    }

}