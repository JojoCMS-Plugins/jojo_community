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


$_provides['pluginClasses'] = array(
        'Jojo_Plugin_Jojo_Community_Register'     => 'Community - User Registration',
        'Jojo_Plugin_Jojo_Community_Edit_Profile' => 'Community - Edit User Profile',
        'Jojo_Plugin_Jojo_Community_Profile'      => 'Community - User Profile'
        );
        

Jojo::registerURI("register/approve/[groupid:string]/[approvecode:[a-zA-Z0-9]{16}]", 'Jojo_Plugin_Jojo_Community_Register'); // "register/approve/groupname/8gw0vywrbs0vbsoivb0wvb/" for approving registrations
Jojo::registerURI("register/delete/[deletecode:[a-zA-Z0-9]{16}]",                    'Jojo_Plugin_Jojo_Community_Register'); // "register/delete/8gw0vywrbs0vbsoivb0wvb/" for deleting new registrations
Jojo::registerURI("register/[redirect:(.*)]",                                        'Jojo_Plugin_Jojo_Community_Register'); // "register/page-to-redirect-to-on-success/" for register page

$profileprefix = Jojo_Plugin_Jojo_Community_Profile::_getPrefix('', Jojo::getOption('multilanguage-default', 'en') );
Jojo::registerURI("[action:$profileprefix]/[id:integer]/",                         'Jojo_Plugin_Jojo_Community_Profile');  // "profiles/563/"
Jojo::registerURI("[action:$profileprefix]/[id:integer]/[string]",                         'Jojo_Plugin_Jojo_Community_Profile');  // "profiles/563/name-of-user/"
Jojo::registerURI("[action:$profileprefix]/[profilename:string]",                          'Jojo_Plugin_Jojo_Community_Profile');  // "profiles/name-of-user/"

$_options[] = array(
    'id'          => 'jojo_community_register_captcha',
    'category'    => 'Security',
    'label'       => 'Registration form CAPTCHA',
    'description' => 'Adds a CAPTCHA to the registration form',
    'type'        => 'radio',
    'default'     => 'no',
    'options'     => 'yes,no',
    'plugin'      => 'jojo_community'
);

$_options[] = array(
    'id'          => 'jojo_community_public_profile',
    'category'    => 'Security',
    'label'       => 'Use public profiles',
    'description' => 'Registered users have a public profile page',
    'type'        => 'radio',
    'default'     => 'yes',
    'options'     => 'yes,no',
    'plugin'      => 'jojo_community'
);