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
  
  $Id: class.address.users.php 26 2009-07-07 14:58:24Z ralf $
  
**/

// require the interface class
require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.address.interface.php');

/**
 * class for access to the WB USERS table
 *
 */
class dbUsers extends dbConnect {

	const field_user_id					= 'user_id';
	const field_group_id				= 'group_id';
	const field_groups_id				= 'groups_id';
	const field_active					= 'active';
	const field_username				= 'username';
	const field_password				= 'password';
	const field_remember_key		= 'remember_key';
	const field_last_reset			= 'last_reset';
	const field_display_name		= 'display_name';
	const field_email						= 'email';
	const field_timezone				= 'timezone';
	const field_date_format			= 'date_format';
	const field_time_format			= 'time_format';
	const field_language				= 'language';
	const field_home_folder			= 'home_folder';
	const field_login_when			= 'login_when';
	const field_login_ip				= 'login_ip';

	public function __construct() {
		parent::__construct();
		$this->setTableName('users');
		$this->addFieldDefinition(self::field_user_id,				"INT(11) NOT NULL AUTO_INCREMENT", true);
		$this->addFieldDefinition(self::field_group_id,				"INT(11) NOT NULL DEFAULT '0'");
		$this->addFieldDefinition(self::field_groups_id ,			"VARCHAR(255) NOT NULL DEFAULT '0'");
		$this->addFieldDefinition(self::field_active ,				"INT(11) NOT NULL DEFAULT '0'");
		$this->addFieldDefinition(self::field_username ,			"VARCHAR(255) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_password ,			"VARCHAR(255) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_remember_key ,	"VARCHAR(255) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_last_reset ,		"INT(11) NOT NULL DEFAULT '0'");
		$this->addFieldDefinition(self::field_display_name ,	"VARCHAR(255) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_email ,					"TEXT NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_timezone ,			"INT(11) NOT NULL DEFAULT '0'");
		$this->addFieldDefinition(self::field_date_format ,		"VARCHAR(255) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_time_format ,		"VARCHAR(255) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_language ,			"VARCHAR(5) NOT NULL DEFAULT 'DE'");
		$this->addFieldDefinition(self::field_home_folder ,		"TEXT NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_login_when ,		"INT(11) NOT NULL DEFAULT '0'");
		$this->addFieldDefinition(self::field_login_ip ,			"VARCHAR(15) NOT NULL DEFAULT ''");
		$this->checkFieldDefinitions();
	} // __construct()
	
} // class dbUsers

/**
 * Interface to WB USERS
 *
 */
class usersInterface extends addressInterface {
	
	public function __construct() {
		$this->setInterfaceName('WB Users Interface');
		$this->setInterfaceVersion(0.1);
	} // __construct()
	
	public function getEMailAddresses(&$address_array = array()) {
		$dbUsers = new dbUsers();
		$where = array();
		$where[dbUsers::field_active] = 1;
		$order_by = array(dbUsers::field_display_name);
		$addresses = array();
		if (!$dbUsers->sqlSelectRecordOrderBy($where, $addresses, $order_by)) {
			$this->setError(sprintf('[%s - s] %s', __METHOD__, __LINE__, $dbUsers->getError()));
			return false;
		}
		// reset $address_array
		$address_array = array();
		// step through result
		foreach ($addresses as $address) {
			$address_array[] = sprintf(	'%s <%s>', 
																	$address[dbUsers::field_display_name],
																	$address[dbUsers::field_email]);
		} // foreach
		return true;
	} // getEMailAddresses()
	
} // class usersInterface

?>