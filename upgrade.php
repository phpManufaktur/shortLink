<?php

/**
  Module developed for the Open Source Content Management System Website Baker (http://websitebaker.org)
  Copyright (c) 2009, Ralf Hertsch
  Contact me: hertsch(at)berlin.de, http://phpManufaktur.de

  This module is free software. You can redistribute it and/or modify it
  under the terms of the GNU General Public License  - version 2 or later,
  as published by the Free Software Foundation: http://www.gnu.org/licenses/gpl.html.

  This module is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.
  
  $Id: upgrade.php 21 2009-07-05 05:49:57Z ralf $
  
**/

require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/initialize.php');

/**
 *  Version 0.10 --> 0.11
 */

// dbShortLinkConfig added

$dbShortLinkConfig = new dbShortLinkConfig(true);
if ($dbShortLinkConfig->isError()) {
	$admin->print_error(sprintf('[Upgrade] %s', $dbShortLinkConfig->getError()));
}

// geaenderte Bezeichner korrigieren
$where = array();
$where[dbShortLinkConfig::field_label] = 'sl_label_cfg_developer';
$result = array();
if (!$dbShortLinkConfig->sqlSelectRecord($where, $result)) {
	$admin->print_error(sprintf('[Upgrade] %s', $dbShortLinkConfig->getError()));
}
if (sizeof($result) > 0) {
	$where = array();
	$where[dbShortLinkConfig::field_id] = $result[0][dbShortLinkConfig::field_id];
	$data = array();
	$data[dbShortLinkConfig::field_label] = 'sl_label_cfg_developer_mode';
	$data[dbShortLinkConfig::field_update_by] = 'SYSTEM';
	$data[dbShortLinkConfig::field_update_when] = date('Y-m-d H:i:s');
	if (!$dbShortLinkConfig->sqlUpdateRecord($data, $where)) {
		$admin->print_error(sprintf('[Upgrade] %s', $dbShortLinkConfig->getError()));
	}
}

// dbShortLink, new fields

$dbShortLink = new dbShortLink(true);
if ($dbShortLink->isError()) {
	$admin->print_error(sprintf('[Upgrade] %s', $dbShortLink->getError()));
}
// field sl_type
if (!$dbShortLink->sqlFieldExists(dbShortLink::field_type)) {
	// add field
	if (!$dbShortLink->sqlAlterTableAddField(dbShortLink::field_type, "TINYINT UNSIGNED NOT NULL DEFAULT '".dbShortLink::type_shortlink."'", dbShortLink::field_id)) {
		$admin->print_error(sprintf('[Upgrade] %s', $dbShortLink->getError()));
	}
}
// field sl_name
if (!$dbShortLink->sqlFieldExists(dbShortLink::field_name)) {
	// add field
	if (!$dbShortLink->sqlAlterTableAddField(dbShortLink::field_name, "VARCHAR(64) NOT NULL DEFAULT ''", dbShortLink::field_type)) {
		$admin->print_error(sprintf('[Upgrade] %s', $dbShortLink->getError()));
	}
}
// field sl_count
if (!$dbShortLink->sqlFieldExists(dbShortLink::field_count)) {
	// add field
	if (!$dbShortLink->sqlAlterTableAddField(dbShortLink::field_count, "INT(11) NOT NULL DEFAULT '0'", dbShortLink::field_name)) {
		$admin->print_error(sprintf('[Upgrade] %s', $dbShortLink->getError()));
	}
}
// field sl_dl_path
if (!$dbShortLink->sqlFieldExists(dbShortLink::field_dl_path)) {
	// add field
	if (!$dbShortLink->sqlAlterTableAddField(dbShortLink::field_dl_path, "TEXT NOT NULL DEFAULT ''", dbShortLink::field_url_short)) {
		$admin->print_error(sprintf('[Upgrade] %s', $dbShortLink->getError()));
	}
}
// field sl_dl_size
if (!$dbShortLink->sqlFieldExists(dbShortLink::field_dl_size)) {
	// add field
	if (!$dbShortLink->sqlAlterTableAddField(dbShortLink::field_dl_size, "INT(11) NOT NULL DEFAULT '0'", dbShortLink::field_dl_path)) {
		$admin->print_error(sprintf('[Upgrade] %s', $dbShortLink->getError()));
	}
}
// field sl_dl_fn_origin
if (!$dbShortLink->sqlFieldExists(dbShortLink::field_dl_fn_origin)) {
	// add field
	if (!$dbShortLink->sqlAlterTableAddField(dbShortLink::field_dl_fn_origin, "VARCHAR(255) NOT NULL DEFAULT ''", dbShortLink::field_dl_size)) {
		$admin->print_error(sprintf('[Upgrade] %s', $dbShortLink->getError()));
	}
}
// field sl_dl_fn_target
if (!$dbShortLink->sqlFieldExists(dbShortLink::field_dl_fn_target)) {
	// add field
	if (!$dbShortLink->sqlAlterTableAddField(dbShortLink::field_dl_fn_target, "VARCHAR(255) NOT NULL DEFAULT ''", dbShortLink::field_dl_fn_origin)) {
		$admin->print_error(sprintf('[Upgrade] %s', $dbShortLink->getError()));
	}
}
// field sl_dl_checksum
if (!$dbShortLink->sqlFieldExists(dbShortLink::field_dl_checksum)) {
	// add field
	if (!$dbShortLink->sqlAlterTableAddField(dbShortLink::field_dl_checksum, "VARCHAR(64) NOT NULL DEFAULT ''", dbShortLink::field_dl_fn_target)) {
		$admin->print_error(sprintf('[Upgrade] %s', $dbShortLink->getError()));
	}
}
// field sl_last_exec
if (!$dbShortLink->sqlFieldExists(dbShortLink::field_last_exec)) {
	// add field
	if (!$dbShortLink->sqlAlterTableAddField(dbShortLink::field_last_exec, "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'", dbShortLink::field_count)) {
		$admin->print_error(sprintf('[Upgrade] %s', $dbShortLink->getError()));
	}
}
// field sl_use_once
if (!$dbShortLink->sqlFieldExists(dbShortLink::field_use_once)) {
	// add field
	if (!$dbShortLink->sqlAlterTableAddField(dbShortLink::field_use_once, "TINYINT UNSIGNED NOT NULL DEFAULT '0'", dbShortLink::field_valid_until)) {
		$admin->print_error(sprintf('[Upgrade] %s', $dbShortLink->getError()));
	}
}

/**
 * 0.12 --> 0.13
 */

// field sl_dl_unlink
if (!$dbShortLink->sqlFieldExists(dbShortLink::field_dl_unlink)) {
	// add field
	if (!$dbShortLink->sqlAlterTableAddField(dbShortLink::field_dl_unlink, "TINYINT UNSIGNED NOT NULL DEFAULT '0'", dbShortLink::field_dl_checksum)) {
		$admin->print_error(sprintf('[Upgrade] %s', $dbShortLink->getError()));
	}
}

/**
 * 0.15
 */
// NEW TABLE dbShortLinkAddresses
$dbShortLinkAddresses = new dbShortLinkAddresses(true);
if ($dbShortLinkAddresses->isError()) {
	$admin->print_error(sprintf('[Upgrade] %s', $dbShortLinkAddresses->getError()));
}


?>