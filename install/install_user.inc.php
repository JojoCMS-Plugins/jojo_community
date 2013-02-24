<?php
/**
 *                    Jojo CMS
 *                ================
 *
 * Copyright 2007-2008 Harvey Kane <code@ragepank.com>
 * Copyright 2007-2008 Michael Holt <code@gardyneholt.co.nz>
 * Copyright 2007 Melanie Schulz <mel@gardyneholt.co.nz>
 *
 * See the enclosed file license.txt for license information (LGPL). If you
 * did not receive this file, see http://www.fsf.org/copyleft/lgpl.html.
 *
 * @author  Harvey Kane <code@ragepank.com>
 * @author  Michael Cochrane <mikec@jojocms.org>
 * @author  Melanie Schulz <mel@gardyneholt.co.nz>
 * @license http://www.fsf.org/copyleft/lgpl.html GNU Lesser General Public License
 * @link    http://www.jojocms.org JojoCMS
 * @package jojo_core
 */

$table = 'user';
$query = "
    CREATE TABLE {user} (
      `us_status` enum('active','inactive') NOT NULL default 'active',
      `us_address1` varchar(255) NOT NULL default '',
      `us_address2` varchar(255) NOT NULL default '',
      `us_suburb` varchar(255) NOT NULL default '',
      `us_city` varchar(255) NOT NULL default '',
      `us_state` varchar(255) NOT NULL default '',
      `us_postcode` varchar(255) NOT NULL default '',
      `us_country` varchar(255) NOT NULL default '',
      `us_signature` varchar(255) NOT NULL default '',
      `us_permissions` text NULL,
      `us_website` varchar(255) NOT NULL default '',
      `us_avatar` varchar(255) NOT NULL default '',
      `us_tagline` varchar(255) NOT NULL default '',
      `us_location` varchar(255) NOT NULL default '',
      `us_timezone` int(11) NOT NULL default '12',
      `us_approvecode` varchar(40) NOT NULL default '',
      `us_deletecode` varchar(40) NOT NULL default '',
      `us_groups` varchar(255) NOT NULL default '',
      `us_privacy` text NOT NULL,
      PRIMARY KEY  (`userid`)
    ) TYPE=MyISAM ;";

/* Check table structure */
$result = Jojo::checkTable($table, $query);

/* Output result */
if (isset($result['created'])) {
    echo sprintf("Table <b>%s</b> Does not exist - created empty table.<br />", $table);
}

if (isset($result['added'])) {
    foreach ($result['added'] as $col => $v) {
        echo sprintf("Table <b>%s</b> column <b>%s</b> Does not exist - added.<br />", $table, $col);
    }
}

if (isset($result['different'])) Jojo::printTableDifference($table,$result['different']);
