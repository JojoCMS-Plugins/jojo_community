<?php
/**
 *                    Jojo CMS
 *                ================
 *
 * Copyright 2008 Harvey Kane <code@ragepank.com>
 * Copyright 2008 Michael Holt <code@gardyneholt.co.nz>
 *
 * See the enclosed file license.txt for license information (LGPL). If you
 * did not receive this file, see http://www.fsf.org/copyleft/lgpl.html.
 *
 * @author  Harvey Kane <code@ragepank.com>
 * @license http://www.fsf.org/copyleft/lgpl.html GNU Lesser General Public License
 * @link    http://www.jojocms.org JojoCMS
 */

/* uninstall jojo_community_legacy plugin */
$legacy_plugins = Jojo::selectQuery("SELECT * FROM {plugin} WHERE name = 'jojo_community_legacy'");
if (count($legacy_plugins)) {
    Jojo::deleteQuery("DELETE FROM {plugin} WHERE name='jojo_community_legacy'");
    echo 'jojo_community_legacy plugin installed. Please run setup again.<br />';
}

/* if a registration page already exists, convert it from core to community plugin */
Jojo::updateQuery("UPDATE {page} SET pg_link='Jojo_Plugin_Jojo_Community_Register' WHERE pg_link='Jojo_Plugin_Register'");

/* Register */
$data = Jojo::selectQuery("SELECT pageid FROM {page} WHERE pg_link = 'Jojo_Plugin_Jojo_Community_Register'");
if (!count($data)) {
    echo "Adding <b>register</b> Page to menu<br />";
    Jojo::insertQuery("INSERT INTO {page} SET pg_title = 'Register', pg_link = 'Jojo_Plugin_Jojo_Community_Register', pg_url = 'register', pg_parent = ?, pg_order=0, pg_mainnav='no', pg_footernav='no', pg_sitemapnav='no', pg_xmlsitemapnav='no', pg_index='no', pg_body = ''", array($_NOT_ON_MENU_ID));
}


/* if an edit profile page already exists, convert it from core to community plugin */
Jojo::updateQuery("UPDATE {page} SET pg_link='Jojo_Plugin_Jojo_Community_Edit_profile' WHERE pg_link='Jojo_Plugin_User_profile'");

/* Edit profile */

$data = Jojo::selectQuery("SELECT pageid FROM {page} WHERE pg_link = 'Jojo_Plugin_Jojo_Community_Edit_profile'");
if (!count($data)) {
    echo "Adding <b>edit profile</b> Page to menu<br />";
    Jojo::insertQuery("INSERT INTO {page} SET pg_title = 'User Profile', pg_link = 'Jojo_Plugin_Jojo_Community_Edit_profile', pg_url = 'user-profile', pg_parent = ?, pg_order=0, pg_mainnav='no', pg_footernav='no', pg_sitemapnav='no', pg_xmlsitemapnav='no', pg_index='no', pg_body = ''", array($_NOT_ON_MENU_ID));
}


/* View profile */

$data = Jojo::selectQuery("SELECT pageid FROM {page} WHERE pg_link = 'Jojo_Plugin_Jojo_Community_profile'");
if (!count($data)) {
    echo "Adding <b>user-profiles</b> Page to menu<br />";
    Jojo::insertQuery("INSERT INTO {page} SET pg_title = 'User Profiles', pg_link = 'Jojo_Plugin_Jojo_Community_profile', pg_url = 'profiles', pg_parent = ?, pg_order=0, pg_mainnav='no', pg_footernav='no', pg_sitemapnav='no', pg_xmlsitemapnav='no', pg_index='no', pg_body = ''", array($_NOT_ON_MENU_ID));
}