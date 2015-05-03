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

class Jojo_Plugin_Jojo_Community_Profile extends Jojo_Plugin
{

    function _getContent()
    {
        global $smarty, $_USERID;
        $content = array();

        $userid = Jojo::getFormData('id', false);

        if (!isset($table)) $table = &Jojo_Table::singleton('user');
        $table->getRecord($userid);
        $fieldsHTML = $table->getHTML('view');

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
        //print_r($fieldsHTML);
        $smarty->assign('fields', $fieldsHTML);
        $smarty->assign('profileprefix', self::_getPrefix());
        $smarty->assign('editprofileprefix', self::_getPrefix('editprofile'));
        $smarty->assign('thisuser', ($userid==$_USERID ? true : false));

        $content['title']     = 'User profile: ' . $table->getFieldValue('us_login');
        $content['seotitle']  = 'User profile: ' . $table->getFieldValue('us_login');
        $content['content']   = $smarty->fetch('jojo_community_profile.tpl');

        return $content;
    }

    /**
     * Get the url prefix for a particular part of this plugin
     */
    static function _getPrefix($for='userprofiles', $language=false) {
        $cacheKey = $for;
        $cacheKey .= ($language) ? $language : 'false';

        /* Have we got a cached result? */
        static $_cache;
        if (isset($_cache[$cacheKey])) {
            return $_cache[$cacheKey];
        }
        $plugin =  ($for = 'editprofiles') ? 'Jojo_Plugin_Jojo_Community_Edit_profile' : 'jojo_plugin_jojo_community_profile';
        $language = !empty($language) ? $language : Jojo::getOption('multilanguage-default', 'en');
        $query = "SELECT pageid, pg_title, pg_url FROM {page} WHERE pg_link = ?";
        $query .= (_MULTILANGUAGE) ? " AND pg_language = '$language'" : '';
        $res = Jojo::selectRow($query, array($plugin));

        if ($res) {
            $_cache[$cacheKey] = !empty($res['pg_url']) ? $res['pg_url'] : $res['pageid'] . '/' . strtolower($res['pg_title']);
        } else {
            $_cache[$cacheKey] = '';
        }
        return $_cache[$cacheKey];
    }


    function getCorrectUrl()
    {
        $userid = Jojo::getFormData('id', false);
        if (!$userid || !$user = Jojo::selectRow('SELECT userid, us_login FROM {user} WHERE userid = ?', $userid)) {
            include(_BASEPLUGINDIR . '/jojo_core/404.php');
            exit;
        }
        return _SITEURL . '/' . Jojo::rewrite('user-profile', $user['userid'], $user['us_login'], '');
    }

}