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
  
  $Id: class.shortlink.php 29 2009-07-18 05:56:23Z ralf $
  
**/

// prevent this file from being accesses directly
if(defined('WB_PATH') == false) {
  exit("Cannot access this file directly");
}

define('request_link', 						'sl');
define('request_check',						'chk');
define('request_name',						'name');
define('request_download',				'dl');
define('request_download_name',		'dl_name');



/**
 * Wird von der index.php aufgerufen und setzt den Shortlink in den
 * urspruenglichen Link um... *
 */
function shortLink() {
	// SHORTLINK 
	if (((isset($_REQUEST[request_link])) && (!empty($_REQUEST[request_link]))) || 
			((isset($_REQUEST[request_name])) && (!empty($_REQUEST[request_name])))) {
		// es soll ein ShortLink ausgefuehrt werden
		$dbShortLink = new dbShortLink();
		if ((isset($_REQUEST[request_link])) && (!empty($_REQUEST[request_link]))) {
			$where = array();
			$where[dbShortLink::field_param] = $_REQUEST[request_link];
			$where[dbShortLink::field_status] = dbShortLink::status_active;
		}
		else {
			$where = array();
			$where[dbShortLink::field_name] = strtolower($_REQUEST[request_name]);
			$where[dbShortLink::field_status] = dbShortLink::status_active;
		}
		$result = array();
		if (($dbShortLink->sqlSelectRecord($where, $result)) && (sizeof($result) > 0)) {
			// ShortLink existiert, Datenbank aktualisieren und ausfuehren
			$result = $result[0];
			$link = $result[dbShortLink::field_url_long];
			$exec = true;
			$data = array();
			// Pruefen ob der ShortLink zeitlich begrenzt ist
			$dt = strtotime($result[dbShortLink::field_valid_until]);
			$now = time();
			if (($dt > 0) && ($dt < $now)) {
				// Gueltigkeit ist abgelaufen
				$exec = false;
				$data[dbShortLink::field_status] = dbShortLink::status_locked;
			}
			// Wegwerf Link?
			if ($result[dbShortLink::field_use_once] == 1) {
				// Datensatz sperren
				$data[dbShortLink::field_status] = dbShortLink::status_locked;
			}
			$data[dbShortLink::field_last_exec] = date('Y-m-d H:i:s');
			if ($exec) {
				// Zaehler nur erhoehen, wenn der Link auch ausgefuehrt wird
				$data[dbShortLink::field_count] = $result[dbShortLink::field_count]+1;	
			}
			$where = array();
			$where[dbShortLink::field_id] = $result[dbShortLink::field_id];
			$dbShortLink->sqlUpdateRecord($data, $where);
			if ($exec) {
				header('Location: '.$link);
				return false;
			}
		}
	}
	// DOWNLOAD LINK
	if (((isset($_REQUEST[request_download])) && (!empty($_REQUEST[request_download]))) ||
			((isset($_REQUEST[request_download_name])) && (!empty($_REQUEST[request_download_name])))) {
		// es soll ein Download Link ausgefuehrt werden
		$dbShortLink = new dbShortLink;
		if (isset($_REQUEST[request_download]) && (!empty($_REQUEST[request_download]))) {
			$where = array();
			$where[dbShortLink::field_param] = $_REQUEST[request_download];
			$where[dbShortLink::field_status] = dbShortLink::status_active;
		}
		else {
			$where = array();
			$where[dbShortLink::field_name] = strtolower($_REQUEST[request_download_name]);
			$where[dbShortLink::field_status] = dbShortLink::status_active;
		}
		$result = array();
		if ($dbShortLink->sqlSelectRecord($where, $result)) {
			$result = $result[0];
			$exec = true;
			$unlink = false;
			$data = array();
			// Pruefen ob der ShortLink zeitlich begrenzt ist
			$dt = strtotime($result[dbShortLink::field_valid_until]);
			$now = time();
			if ((!$dt === false) && ($dt < $now)) {
				// Gueltigkeit ist abgelaufen
				$exec = false;
				if ($result[dbShortLink::field_dl_unlink] == 1) {
					// Datensatz loeschen und Datei loeschen
					$data[dbShortLink::field_status] = dbShortLink::status_deleted;
					unlink($result[dbShortLink::field_dl_path.$result[dbShortLink::field_dl_fn_target]]);
				}
				else {
					$data[dbShortLink::field_status] = dbShortLink::status_locked;
				}
			}
			// Wegwerf Link?
			if ($result[dbShortLink::field_use_once] == 1) {
				if ($result[dbShortLink::field_dl_unlink] == 1) {
					// Datensatz loeschen
					$data[dbShortLink::field_status] = dbShortLink::status_deleted;
					$unlink = true;
				}
				else {
					// Datensatz sperren
					$data[dbShortLink::field_status] = dbShortLink::status_locked;
				}
			}
			$data[dbShortLink::field_last_exec] = date('Y-m-d H:i:s');
			if ($exec) {
				// Zaehler nur erhoehen, wenn der Download auch ausgefuehrt wird
				$data[dbShortLink::field_count] = $result[dbShortLink::field_count]+1;	
			}
			// Datensatz aktualisieren
			$where = array();
			$where[dbShortLink::field_id] = $result[dbShortLink::field_id];
			$dbShortLink->sqlUpdateRecord($data, $where);			
			// Prufen ob Datei existiert
			if ($exec && (file_exists($result[dbShortLink::field_dl_path].$result[dbShortLink::field_dl_fn_target]))) {
				// Datei existiert, Download starten
				header('Content-type: application/force-download');
				header('Content-Transfer-Encoding: Binary');
				header('Content-length: '.$result[dbShortLink::field_dl_size]);
				header('Content-disposition: attachment;filename='.$result[dbShortLink::field_dl_fn_origin]);
				readfile($result[dbShortLink::field_dl_path].$result[dbShortLink::field_dl_fn_target]);
				if ($unlink) {
					unlink($result[dbShortLink::field_dl_path].$result[dbShortLink::field_dl_fn_target]);
				}
				return false;
			}
		}
	}
	return true;
} // shortLink()


/**
 * Datenbank Klasse fuer ShortLink
 *
 */
class dbShortLink extends dbConnect {
	
	const field_id						= 'sl_id';
	const field_type					= 'sl_type';					// 0.11
	const field_name					= 'sl_name';					// 0.11
	const field_count					= 'sl_count';
	const field_last_exec			= 'sl_last_exec';
	const field_param					= 'sl_param';
	const field_url_long			= 'sl_url_long';
	const field_url_short			= 'sl_url_short';
	const field_dl_path				= 'sl_dl_path';				// 0.11
	const field_dl_size 			= 'sl_dl_size';				// 0.11
	const field_dl_fn_origin 	= 'sl_dl_fn_origin';	// 0.11
	const field_dl_fn_target	= 'sl_dl_fn_target';	// 0.11
	const field_dl_checksum		= 'sl_dl_checksum';		// 0.11
	const field_dl_unlink			= 'sl_dl_unlink';			// 0.13
	const field_checksum			= 'sl_checksum';
	const field_created_when	= 'sl_created_when';
	const field_created_by		= 'sl_created_by';
	const field_valid_until		= 'sl_valid_until';
	const field_use_once			= 'sl_use_once';			// 0.11
	const field_status				= 'sl_status';
	const field_update_by			= 'sl_update_by';
	const field_update_when		= 'sl_update_when';
	
	const status_active				= 1;
	const status_locked				= 2;
	const status_deleted			= 0;
	
	const type_undefined			= 0;
	const type_shortlink			= 1;
	const type_download				= 2;
	
	var $status_array = array(
		self::status_active		=> sl_status_active,
		self::status_locked		=> sl_status_locked,
		self::status_deleted	=> sl_status_deleted
	);
	
	var $type_array = array(
		self::type_undefined	=> sl_type_undefined,
		self::type_shortlink	=> sl_type_shortlink,
		self::type_download		=> sl_type_download
	);
	
	const base_from						= 10;
	const base_to							= 29;
	
	private $create_tables 		= false;
	
	public function __construct($create_tables=false) {
		parent::__construct();
		$this->create_tables = $create_tables;
		$this->setTableName('mod_shortlink');
		$this->addFieldDefinition(self::field_id, "INT(11) NOT NULL AUTO_INCREMENT", true);
		$this->addFieldDefinition(self::field_type, "TINYINT UNSIGNED NOT NULL DEFAULT '".self::type_shortlink."'");
		$this->addFieldDefinition(self::field_name, "VARCHAR(64) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_count, "INT(11) NOT NULL DEFAULT '0'");
		$this->addFieldDefinition(self::field_last_exec, "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
		$this->addFieldDefinition(self::field_param, "VARCHAR(64) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_url_long, "TEXT NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_url_short, "VARCHAR(255) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_dl_path, "TEXT NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_dl_size, "INT(11) NOT NULL DEFAULT '0'");
		$this->addFieldDefinition(self::field_dl_fn_origin, "VARCHAR(255) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_dl_fn_target, "VARCHAR(255) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_dl_checksum, "VARCHAR(64) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_dl_unlink, "TINYINT UNSIGNED NOT NULL DEFAULT '0'");
		$this->addFieldDefinition(self::field_checksum, "VARCHAR(64) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_created_by, "VARCHAR(64) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_created_when, "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
		$this->addFieldDefinition(self::field_valid_until, "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
		$this->addFieldDefinition(self::field_use_once, "TINYINT UNSIGNED NOT NULL DEFAULT '0'");
		$this->addFieldDefinition(self::field_status, "TINYINT UNSIGNED NOT NULL DEFAULT '".self::status_active."'");
		$this->addFieldDefinition(self::field_update_by, "VARCHAR(64) NOT NULL DEFAULT 'SYSTEM'");
		$this->addFieldDefinition(self::field_update_when, "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
		$this->setIndexFields(array(self::field_url_short));
		$this->checkFieldDefinitions();
		if (($this->create_tables) && (!$this->sqlTableExists())) {
			if (!$this->sqlCreateTable()) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
			}
		}
	} // __construct()
	
} // class dbShortLink


/**
 * Konfiguration fuer ShortLink
 *
 */
class dbShortLinkConfig extends dbConnect {
	
	const field_id						= 'cfg_id';
	const field_name					= 'cfg_name';
	const field_type					= 'cfg_type';
	const field_value					= 'cfg_value';
	const field_label					= 'cfg_label';
	const field_description		= 'cfg_desc';
	const field_status				= 'cfg_status';
	const field_update_by			= 'cfg_update_by';
	const field_update_when		= 'cfg_update_when';
	
	const status_active				= 1;
	const status_deleted			= 0;
	
	const type_undefined			= 0;
	const type_array					= 7;
  const type_boolean				= 1;
  const type_email					= 2;
  const type_float					= 3;
  const type_integer				= 4;
  const type_path						= 5;
  const type_string					= 6;
  const type_url						= 8;
  
  public $type_array = array(
  	self::type_undefined		=> '-UNDEFINED-',
  	self::type_array				=> 'ARRAY',
  	self::type_boolean			=> 'BOOLEAN',
  	self::type_email				=> 'E-MAIL',
  	self::type_float				=> 'FLOAT',
  	self::type_integer			=> 'INTEGER',
  	self::type_path					=> 'PATH',
  	self::type_string				=> 'STRING',
  	self::type_url					=> 'URL'
  );
  
  private $createTables 		= false;
  private $message					= '';
  
  const cfgDeveloperMode				= 'cfgDeveloperMode';
  const cfgLinkFileUse					= 'cfgLinkFileUse';
  const cfgLinkFileName					= 'cfgLinkFileName';
  const cfgDownloadPath					= 'cfgDownloadPath';
  const cfgDLUnlinkAuto					= 'cfgDLUnlinkAuto';
  const cfgDLUseOnce						= 'cfgDLUseOnce';
  const cfgSLUseOnce						= 'cfgSLUseOnce';
  const cfgSwitches							= 'cfgSwitches';
  const cfgMailSendHTML					= 'cfgMailSendHTML';
  const cfgUseInterface					= 'cfgUseInterface';
  
  public $config_array = array(
  	array('sl_label_cfg_developer_mode', self::cfgDeveloperMode, self::type_boolean, 0, 'sl_desc_cfg_developer_mode'),
  	array('sl_label_cfg_linkfile_use', self::cfgLinkFileUse, self::type_boolean, 1, 'sl_desc_cfg_linkfile_use'),
  	array('sl_label_cfg_linkfile_name', self::cfgLinkFileName, self::type_string, 'link.php', 'sl_desc_cfg_linkfile_name'),
  	array('sl_label_cfg_download_path', self::cfgDownloadPath, self::type_path, 'media/shortlink/', 'sl_desc_cfg_download_path'),
  	array('sl_label_cfg_dl_unlink_auto', self::cfgDLUnlinkAuto, self::type_boolean, 1, 'sl_desc_cfg_dl_unlink_auto'),
  	array('sl_label_cfg_dl_use_once', self::cfgDLUseOnce, self::type_boolean, 0, 'sl_desc_cfg_dl_use_once'),
  	array('sl_label_cfg_sl_use_once', self::cfgSLUseOnce, self::type_boolean, 0, 'sl_desc_cfg_sl_use_once'),
  	array('sl_label_cfg_switches', self::cfgSwitches, self::type_array, 'navDefault=sl', 'sl_desc_cfg_switches'),
  	array('sl_label_cfg_mail_send_html', self::cfgMailSendHTML, self::type_boolean, 1, 'sl_desc_cfg_mail_send_html'),
  	array('sl_label_cfg_use_interface', self::cfgUseInterface, self::type_array, '', 'sl_desc_cfg_use_interface')
  );
  
  const swNavDefault						= 'navDefault';	// Navigation TAB, Voreinstellung
  const swNavHide								= 'navHide';		// Navigation TAB, nicht anzeigen
  
  public function __construct($createTables = false) {
  	$this->createTables = $createTables;
  	parent::__construct();
  	$this->setTableName('mod_shortlink_cfg');
  	$this->addFieldDefinition(self::field_id, "INT(11) NOT NULL AUTO_INCREMENT", true);
  	$this->addFieldDefinition(self::field_name, "VARCHAR(32) NOT NULL DEFAULT ''");
  	$this->addFieldDefinition(self::field_type, "TINYINT UNSIGNED NOT NULL DEFAULT '".self::type_undefined."'");
  	$this->addFieldDefinition(self::field_value, "VARCHAR(255) NOT NULL DEFAULT ''");
  	$this->addFieldDefinition(self::field_label, "VARCHAR(64) NOT NULL DEFAULT 'sl_text_undefined'");
  	$this->addFieldDefinition(self::field_description, "VARCHAR(255) NOT NULL DEFAULT 'sl_text_undefined'");
  	$this->addFieldDefinition(self::field_status, "TINYINT UNSIGNED NOT NULL DEFAULT '".self::status_active."'");
  	$this->addFieldDefinition(self::field_update_by, "VARCHAR(32) NOT NULL DEFAULT 'SYSTEM'");
  	$this->addFieldDefinition(self::field_update_when, "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
  	$this->setIndexFields(array(self::field_name));
  	$this->checkFieldDefinitions();
  	// Tabelle erstellen
  	if ($this->createTables) {
  		if (!$this->sqlTableExists()) {
  			if (!$this->sqlCreateTable()) {
  				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
  			}
  		}
  	}
  	// Default Werte garantieren
  	if ($this->sqlTableExists()) {
  		$this->checkConfig();
  	}
  } // __construct()
  
  public function setMessage($message) {
    $this->message = $message;
  } // setMessage()

  /**
    * Get Message from $this->message;
    * 
    * @return STR $this->message
    */
  public function getMessage() {
    return $this->message;
  } // getMessage()

  /**
    * Check if $this->message is empty
    * 
    * @return BOOL
    */
  public function isMessage() {
    return (bool) !empty($this->message);
  } // isMessage
  
  
  /**
   * Fuegt den Wert $new_value in die dbShortLinkConfig ein
   * 
   * @param $new_value STR - Wert, der uebernommen werden soll
   * @param $id INT - ID des Datensatz, dessen Wert aktualisiert werden soll
   * 
   * @return BOOL Ergebnis
   */
  public function setValue($new_value, $id) {
  	$tools = new rhTools();
  	$value = '';
  	$where = array();
  	$where[self::field_id] = $id;
  	$config = array();
  	if (!$this->sqlSelectRecord($where, $config)) {
  		$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
  		return false;
  	}
  	if (sizeof($config) < 1) {
  		$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, sprintf(sl_error_cfg_id, $id)));
  		return false;
  	}
  	$config = $config[0];
  	switch ($config[self::field_type]):
  	case self::type_array:
  		// Funktion geht davon aus, dass $value als STR uebergeben wird!!!
  		$worker = explode(",", $new_value);
  		$data = array();
  		foreach ($worker as $item) {
  			$data[] = trim($item);
  		};
  		$value = implode(",", $data);  			
  		break;
  	case self::type_boolean:
  		$value = (bool) $new_value;
  		$value = (int) $value;
  		break;
  	case self::type_email:
  		if ($tools->validateEMail($new_value)) {
  			$value = trim($new_value);
  		}
  		else {
  			$this->setMessage(sprintf(sl_msg_invalid_email, $new_value));
  			return false;			
  		}
  		break;
  	case self::type_float:
  		$value = $tools->str2float($new_value);
  		break;
  	case self::type_integer:
  		$value = $tools->str2int($new_value);
  		break;
  	case self::type_url:
  	case self::type_path:
  		$value = $tools->addSlash(trim($new_value));
  		break;
  	case self::type_string:
  		$value = (string) trim($new_value);
  		break;
  	endswitch;
  	unset($config[self::field_id]);
  	$config[self::field_value] = (string) $value;
  	$config[self::field_update_by] = $tools->getDisplayName();
  	$config[self::field_update_when] = date('Y-m-d H:i:s');
  	if (!$this->sqlUpdateRecord($config, $where)) {
  		$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
  		return false;
  	}
  	return true;
  } // setValue()
  
  /**
   * Gibt den angeforderten Wert zurueck
   * 
   * @param $name - Bezeichner 
   * 
   * @return WERT entsprechend des TYP
   */
  public function getValue($name) {
  	$result = '';
  	$where = array();
  	$where[self::field_name] = $name;
  	$config = array();
  	if (!$this->sqlSelectRecord($where, $config)) {
  		$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
  		return false;
  	}
  	if (sizeof($config) < 1) {
  		$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, sprintf(sl_error_cfg_name, $name)));
  		return false;
  	}
  	$config = $config[0];
  	switch ($config[self::field_type]):
  	case self::type_array:
  		$result = explode(",", $config[self::field_value]);
  		break;
  	case self::type_boolean:
  		$result = (bool) $config[self::field_value];
  		break;
  	case self::type_email:
  	case self::type_path:
  	case self::type_string:
  	case self::type_url:
  		$result = (string) utf8_decode($config[self::field_value]);
  		break;
  	case self::type_float:
  		$result = (float) $config[self::field_value];
  		break;
  	case self::type_integer:
  		$result = (integer) $config[self::field_value];
  		break;
  	default:
  		$result = utf8_decode($config[self::field_value]);
  		break;
  	endswitch;
  	return $result;
  } // getValue()
  
  public function checkConfig() {
  	foreach ($this->config_array as $item) {
  		$where = array();
  		$where[self::field_name] = $item[1];
  		$check = array();
  		if (!$this->sqlSelectRecord($where, $check)) {
  			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
  			return false;
  		}
  		if (sizeof($check) < 1) {
  			// Eintrag existiert nicht
  			$data = array();
  			$data[self::field_label] = $item[0];
  			$data[self::field_name] = $item[1];
  			$data[self::field_type] = $item[2];
  			$data[self::field_value] = $item[3];
  			$data[self::field_description] = $item[4];
  			$data[self::field_update_when] = date('Y-m-d H:i:s');
  			$data[self::field_update_by] = 'SYSTEM';
  			if (!$this->sqlInsertRecord($data)) {
  				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
  				return false;
  			}
  		}
  	}
  	return true;
  }
  
} // class dbShortLinkConfig

class dbShortLinkAddresses extends dbConnect {
	
	const field_id						= 'adr_id';
	const field_address				= 'adr_address';
	const field_name					= 'adr_name';
	const field_status				= 'adr_status';
	const field_update_by			= 'adr_update_by';
	const field_update_when		= 'adr_update_when';
	
	const status_active				= 1;
	const status_locked				= 2;
	const status_deleted			= 0;
	
	private $create_tables		= false;
	
	public function __construct($create_tables=false) {
		parent::__construct();
		$this->create_tables = $create_tables;
		$this->setTableName('mod_shortlink_addresses');
		$this->addFieldDefinition(self::field_id, "INT(11) NOT NULL AUTO_INCREMENT", true);
		$this->addFieldDefinition(self::field_address, "VARCHAR(255) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_name, "VARCHAR(255) NOT NULL DEFAULT ''");
		$this->addFieldDefinition(self::field_status, "TINYINT UNSIGNED NOT NULL DEFAULT '".self::status_active."'");
		$this->addFieldDefinition(self::field_update_by, "VARCHAR(64) NOT NULL DEFAULT 'SYSTEM'");
		$this->addFieldDefinition(self::field_update_when, "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
		$this->checkFieldDefinitions();
		if (($this->create_tables) && (!$this->sqlTableExists())) {
			if (!$this->sqlCreateTable()) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $this->getError()));
			}
		}	
	} // __construct()
	
} // class dbShortLinkAddresses

?>