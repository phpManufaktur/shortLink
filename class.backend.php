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
  
  $Id: class.backend.php 30 2010-04-06 12:21:51Z ralf $
  
**/

// prevent this file from being accesses directly
if(defined('WB_PATH') == false) {
  exit("Cannot access this file directly");
}

/**
 * Backend fuer ShortLink
 *
 */
class backendShortLink {
	
	const request_action 						= 'act';
	const request_items							= 'its';
	const request_csv_export				= 'csvex';
	const request_file							= 'rfile';
	const request_by								= 'by';
	
	const action_about							= 'info';
	const action_cfg								= 'cfg';
	const action_cfg_check					= 'cfgc';
	const action_default						= 'def';
	const action_download						= 'dl';
	const action_download_edit			= 'dle';
	const action_download_insert		= 'dli';
	const action_list								= 'list';
	const action_mail								= 'mail';
	const action_mail_check					= 'mc';
	const action_shortlink					= 'sl';
	const action_shortlink_insert		= 'sli';
	const action_shortlink_edit			= 'sle';
	const action_shortlink_update		= 'slu';
	
	private $tab_navigation_array = array(
		self::action_shortlink		=> sl_tab_shortlink,
		self::action_download			=> sl_tab_download,
		self::action_list					=> sl_tab_list,
		self::action_cfg					=> sl_tab_cfg,
		self::action_about				=> sl_tab_about
	);
	
	private $page_link 					= '';
	private $template_path			= '';
	private $error							= '';
	private $message						= '';
	private $swNavDefault				= self::action_default;
	private $swNavHide					= array();
	
	public function __construct() {
		$this->page_link = ADMIN_URL.'/admintools/tool.php?tool=shortlink';
		$this->template_path = WB_PATH . '/modules/' . basename(dirname(__FILE__)) . '/htt/' ;
		// Schalter einlesen
  	$config = new dbShortLinkConfig();
  	$switches = $config->getValue(dbShortLinkConfig::cfgSwitches);
  	foreach ($switches as $item) {
  		if (!empty($item)) {
	  		list($key, $value) = explode("=", $item);
	  		switch ($key):
	  		case dbShortLinkConfig::swNavDefault:
	  			// dieser TAB soll in der Voreinstellung angezeigt werden
	  			$this->swNavDefault = $value;
	  			break;
	  		case dbShortLinkConfig::swNavHide:
	  			// diese TABs sollen nicht angezeigt werden
	  			$this->swNavHide = explode(";", $value);
	  			break;
	  		endswitch;
  		}
  	}
	} // __construct
		
	/**
    * Set $this->error to $error
    * 
    * @param STR $error
    */
  public function setError($error) {
    $this->error = $error;
  } // setError()

  /**
    * Get Error from $this->error;
    * 
    * @return STR $this->error
    */
  public function getError() {
    return $this->error;
  } // getError()

  /**
    * Check if $this->error is empty
    * 
    * @return BOOL
    */
  public function isError() {
    return (bool) !empty($this->error);
  } // isError

  /**
   * Reset Error to empty String
   */
  public function clearError() {
  	$this->error = '';
  }

  /** Set $this->message to $message
    * 
    * @param STR $message
    */
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
   * Return Version of Module
   *
   * @return FLOAT
   */
  public function getVersion() {
    // read info.php into array
    $info_text = file(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/info.php');
    if ($info_text == false) {
      return -1; 
    }
    // walk through array
    foreach ($info_text as $item) {
      if (strpos($item, '$module_version') !== false) {
        // split string $module_version
        $value = split('=', $item);
        // return floatval
        return floatval(ereg_replace('([\'";,\(\)[:space:][:alpha:]])', '', $value[1])); 
      } 
    }
    return -1;
  } // getVersion()
  
  
  /**
   * Verhindert XSS Cross Site Scripting
   * 
   * @param REFERENCE $_REQUEST Array
   * @return $request
   */
	public function xssPrevent(&$request) {
  	if (is_string($request)) {
	    $request = html_entity_decode($request);
	    $request = strip_tags($request);
	    $request = trim($request);
	    $request = stripslashes($request);
  	}
	  return $request;
  } // xssPrevent()
	
  public function action() {
  	$html_allowed = array(xMailer::mail_text, xMailer::mail_from, xMailer::mail_to, xmailer::mail_new_address);
  	foreach ($_REQUEST as $key => $value) {
  		if (!in_array($key, $html_allowed)) {
    		$_REQUEST[$key] = $this->xssPrevent($value);
  		} 
  	}
    isset($_REQUEST[self::request_action]) ? $action = $_REQUEST[self::request_action] : $action = $this->swNavDefault;
  	switch ($action):
  	case self::action_about:
  		$this->show(self::action_about, $this->dlgInfo());
  		break;
  	case self::action_shortlink_insert:
  		$this->show(self::action_shortlink, $this->checkShortLink());
  		break;
  	case self::action_shortlink_edit:
  		$this->show(self::action_shortlink, $this->editShortLink());
  		break;
  	case self::action_shortlink_update:
  		$this->show(self::action_shortlink, $this->updateShortLink());
  		break;
  	case self::action_cfg:
  		$this->show(self::action_cfg, $this->editConfig());
  		break;
  	case self::action_cfg_check:
  		$this->show(self::action_cfg, $this->checkConfig());
  		break;
  	case self::action_list:
  		$this->show(self::action_list, $this->showList());
  		break;
  	case self::action_download:
  		$this->show(self::action_download, $this->createDownloadLink());
  		break;
  	case self::action_download_insert:
  		$this->show(self::action_download, $this->checkDownloadLink());
  		break;
  	case self::action_download_edit:
  		$this->show(self::action_download, $this->editShortLink());
  		break;
  	case self::action_mail:
  		((isset($_REQUEST[self::request_by])) && (!empty($_REQUEST[self::request_by]))) ? $by = $_REQUEST[self::request_by] : $by = $this->swNavDefault;
  		$this->show($by, $this->createMail());
  		break;
  	case self::action_mail_check:
  		((isset($_REQUEST[self::request_by])) && (!empty($_REQUEST[self::request_by]))) ? $by = $_REQUEST[self::request_by] : $by = $this->swNavDefault;
  		$this->show($by, $this->checkMail());
  		break;
  	case self::action_default:
  	default:
  		$this->show(self::action_shortlink, $this->createShortLink());
  	endswitch;
  } // action
	
  	
  /**
   * Erstellt eine Navigationsleiste
   * 
   * @param $action - aktives Navigationselement
   * @return STR Navigationsleiste
   */
  public function getNavigation($action) {
  	$result = '';
  	foreach ($this->tab_navigation_array as $key => $value) {
  		if (!in_array($key, $this->swNavHide)) {
	  		($key == $action) ? $selected = ' class="selected"' : $selected = ''; 
	  		$result .= sprintf(	'<li%s><a href="%s">%s</a></li>', 
	  												$selected,
	  												sprintf('%s&%s=%s', $this->page_link, self::request_action, $key),
	  												$value
	  												);
  		}
  	}
  	$result = sprintf('<ul class="nav_tab">%s</ul>', $result);
  	return $result;
  } // getNavigation()
  
  /**
   * Ausgabe des formatieren Ergebnis mit Navigationsleiste
   * 
   * @param $action - aktives Navigationselement
   * @param $content - Inhalt
   * 
   * @return ECHO RESULT
   */
  public function show($action, $content) {
  	if ($this->isError()) {
  		$content = $this->getError();
  		$class = ' class="error"';
  	}
  	else {
  		$class = '';
  	}
  	$parser = new tParser();
  	$parser->add('navigation', $this->getNavigation($action));
  	$parser->add('class', $class);
  	$parser->add('content', $content);
  	$parser->parseTemplateFile($this->template_path.'backend.body.htt');
  	$parser->echoHTML();
  } 
  
  /**
   * Dialog zum Erstellen von ShortLinks
   * 
   * @return STR DIALOG
   */
  public function createShortLink() {
  	$form_name = 'sl_create';
  	// LONG Link
  	$row = '<tr><td class="sl_label">%s</td><td colspan="2" class="%s">%s</td></tr>';
  	$row_3 = '<tr><td class="sl_label">%s</td><td class="%s">%s</td><td class="%s">%s</td></tr>';
  	$items = sprintf(	$row,
  										sl_label_url_long,
  										'sl_url_long',
  										sprintf('<input type="text" name="%s" value="%s" />',
  														dbShortLink::field_url_long,
  														''));
  	// Bezeichner
  	$linkFile = '';
  	$this->checkLinkFile($linkFile);
  	$items .= sprintf($row_3,
  										sl_label_name,
  										'sl_name',
  										sprintf('<input type="text" name="%s" value="%s">',
  														dbShortLink::field_name,
  														''),
  										'sl_explain',
  										sprintf(sl_desc_name,
  														$linkFile ));
  	// Einweglink
  	$config = new dbShortLinkConfig();
  	$cfgSLUseOnce = $config->getValue(dbShortLinkConfig::cfgSLUseOnce);
  	($cfgSLUseOnce) ? $checked = ' checked="checked"' : $checked = '';
  	$items .= sprintf($row_3,
  										sl_label_use_once,
  										'sl_use_once',
  										sprintf('<input type="checkbox" name="%s" value="1"%s />',
  														dbShortLink::field_use_once,
  														$checked),
  										'sl_explain',
  										sl_desc_use_once);
  	// Gueltig bis
		$items .= sprintf('<tr><td class="sl_label">%s</td><td><span class="date_picker">'.
    									'<input type="text" name="%s" value="%s" />'.
    									'<script language="JavaScript">'.
    									'new tcal ({ \'formname\': \'%s\', \'controlname\': \'%s\'	}, \'%s\');'.
    									'</script></span></td><td class="%s">%s</td></tr>',
											sl_label_valid_until,
											dbShortLink::field_valid_until,
											'',
											$form_name,
											dbShortLink::field_valid_until,
											WB_URL. '/modules/'.basename(dirname(__FILE__)).'/images/calendar/',
											'sl_explain',
											sl_desc_valid_until);  														
  	// Mitteilungen anzeigen
		if ($this->isMessage()) {
			$intro = sprintf('<div class="message">%s</div>', $this->getMessage());
		}
		else {
			$intro = sprintf('<div class="intro">%s</div>', sl_intro_shortlink);
		}
		
  	$parser = new tParser();
  	$parseArray = array(
  		'header'					=> sl_header_shortlink,
  		'intro'						=> $intro,
  		'form_name'				=> $form_name,
  		'form_action'			=> $this->page_link,
  		'action_name'			=> self::request_action,
  		'action_value'		=> self::action_shortlink_insert,
  		'items'						=> $items,
  		'btn_ok'					=> sl_btn_ok,
  		'btn_abort'				=> sl_btn_abort,
  		'abort_location'	=> $this->page_link
  	);
  	foreach ($parseArray as $key => $value) {
  		$parser->add($key, $value);
  	}
  	$parser->parseTemplateFile($this->template_path.'backend.shortlink.htt');
  	return $parser->getHTML();
  } // createShortLink()
  
  /**
   * Funktion ueberprueft ob die LINK Datei existiert, 
   * kopiert sie ggf. in das Wurzelverzeichnis
   * 
   * @result BOOL
   */
  public function checkLinkFile(&$linkFile_URL) {
  	$linkFile_URL = '';
  	$dbConfig = new dbShortLinkConfig();
  	$linkFileUse = $dbConfig->getValue(dbShortLinkConfig::cfgLinkFileUse); 
  	if ($linkFileUse) {
  		// LINK File verwenden
  		$linkFileName = $dbConfig->getValue(dbShortLinkConfig::cfgLinkFileName); 
  		$linkFile_URL = WB_URL.'/'.$linkFileName;
  		if (file_exists(WB_PATH.'/'.$linkFileName)) {
  			// LINK Datei existiert
  			return true;
  		}
  		else {
  			if (!@copy(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/link.php', WB_PATH.'/'.$linkFileName)) {
  			// Fehler beim kopieren der Datei
  			$this->setMessage(sprintf(sl_msg_copy_linkfile_fail, $linkFile_URL, WB_URL.'/modules/'.basename(dirname(__FILE__)).'/link.php'));
  			return false;
  			}
  		}
			return true;
  	}
  	else {
  		// index.php verwenden
  		$linkFile_URL = WB_URL.'/index.php';
  		$check = file_get_contents(WB_PATH.'/index.php');
  		if (strpos($check, 'shortLink();') === false) {
  			// index.php soll verwendet werden, es fehlt jedoch der Aufruf fuer shortLink()
  			$this->setMessage(sl_msg_cfg_link_index_fail);
  			return false;
  		}
  		return true;
  	}
  } // checkLinkFile()
  
  public function checkShortLink() {
  	$tools = new rhTools();
  	if ((isset($_REQUEST[dbShortLink::field_url_long])) && (!empty($_REQUEST[dbShortLink::field_url_long]))) {
  		// URL verkuerzen
  		$tools = new rhTools();
  		$dbShortLink = new dbShortLink();
  		// LINK Datei pruefen
  		$linkFile = '';
  		if ($this->checkLinkFile($linkFile)) {
  			// Die LINK Datei ist ok, pruefen ob der LONG Link bereits existiert...
  			$SQL = sprintf(	"SELECT * FROM %s WHERE %s='%s' AND %s!='%s'",
  											$dbShortLink->getTableName(),
  											dbShortLink::field_url_long,
  											$_REQUEST[dbShortLink::field_url_long],
  											dbShortLink::field_status,
  											dbShortLink::status_deleted);
				$result = array();  											
  			if (!$dbShortLink->sqlExec($SQL, $result)) {
  				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbShortLink->getError()));
	  			return false;
  			}
  			if (sizeof($result) > 0) {
  				// der LONG Link existiert bereits, Datensatz aktualisieren
  				$result = $result[0];
  				$param = $result[dbShortLink::field_param];
  				$id = $result[dbShortLink::field_id];
  				$name = $result[dbShortLink::field_name];
  				if ($result[dbShortLink::field_valid_until] == '0000-00-00 00:00:00') {
  					$valid_until = '';
  				}
  				else {
  					$valid_until = $dbShortLink->mySQLdate2datum($result[dbShortLink::field_valid_until]);
  				}
  				$where = array();
  				$where[dbShortLink::field_id] = $result[dbShortLink::field_id];
  				$data = array();
  				$data[dbShortLink::field_status] = dbShortLink::status_active;
		  		$data[dbShortLink::field_update_by] = $tools->getDisplayName();
		  		$data[dbShortLink::field_update_when] = date('Y-m-d H:i:s');
		  		$data[dbShortLink::field_valid_until] = '0000-00-00 00:00:00';
		  		// use_once
		  		((isset($_REQUEST[dbShortLink::field_use_once])) && ($_REQUEST[dbShortLink::field_use_once] == 1)) ? $use_once = 1 : $use_once = 0; 
		  		$data[dbShortLink::field_use_once] = $use_once;
		  		
		  		// Datensatz aktualisieren
	  			if (!$dbShortLink->sqlUpdateRecord($data, $where)) {
	  				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbShortLink->getError()));
	  				return false;	
	  			}
	  			// $data Felder ergaenzen
	  			$data[dbShortLink::field_url_short] = $result[dbShortLink::field_url_short];
	  			$data[dbShortLink::field_url_long] = $result[dbShortLink::field_url_long];
  				if ($result[dbShortLink::field_status] == dbShortLink::status_locked) {
  					// Datensatz war gesperrt und wurde entsperrt
  					$this->setMessage(sprintf(sl_msg_sl_exists_locked, $data[dbShortLink::field_url_short]));
  				}
  				else {
  					// Daten wurden aktualisiert
  					$this->setMessage(sprintf(sl_msg_sl_exists, $data[dbShortLink::field_url_short]));
  				}
  			}
  			else {
  				// Neuer SHORT Link, Anzahl der Datensaetze ermitteln
		  		$count_str = sprintf('COUNT(%s)', dbShortLink::field_id);
		  		$SQL = sprintf("SELECT %s FROM %s", $count_str, $dbShortLink->getTableName());
		  		if (!$dbShortLink->sqlExec($SQL, $result)) {
		  			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbShortLink->getError()));
		  			return false;
		  		}
		  		(sizeof($result) == 0) ? $count = 0 : $count = $result[0][$count_str];
		  		// Zaehler erhoehen
		  		$param = base_convert($count+1, dbShortLink::base_from, dbShortLink::base_to);
		  		((isset($_REQUEST[dbShortLink::field_name])) && (!empty($_REQUEST[dbShortLink::field_name]))) ? 
		  			$name = strtolower($tools->cleanFileName($_REQUEST[dbShortLink::field_name])) : $name = '';
				  $data = array();
				  $data[dbShortLink::field_type] = dbShortLink::type_shortlink;
				  if (empty($name)) {
		  			$data[dbShortLink::field_url_short] = $linkFile.'?'.request_link.'='.$param;
				  }
				  else {
				  	$data[dbShortLink::field_url_short] = $linkFile.'?'.request_name.'='.$name;
				  }
				  if ((isset($_REQUEST[dbShortLink::field_valid_until])) && (!empty($_REQUEST[dbShortLink::field_valid_until]))) {
				  	if (($dt = strtotime($_REQUEST[dbShortLink::field_valid_until])) === false) {
				  		$valid_until = '';				  		
				  	}
				  	else {
				  		$valid_until = $_REQUEST[dbShortLink::field_valid_until];
				  	}
				  }
				  else {
				  	$dt = false;
				  	$valid_until = '';
				  }
		  		$data[dbShortLink::field_url_long] = $_REQUEST[dbShortLink::field_url_long];
		  		$data[dbShortLink::field_param] = $param;
		  		$data[dbShortLink::field_name] = $name;
		  		((isset($_REQUEST[dbShortLink::field_use_once])) && ($_REQUEST[dbShortLink::field_use_once] == 1)) ? $use_once = 1 : $use_once = 0; 
		  		$data[dbShortLink::field_use_once] = $use_once;
		  		$data[dbShortLink::field_checksum] = md5($_REQUEST[dbShortLink::field_url_long]);
		  		$data[dbShortLink::field_created_by] = $tools->getDisplayName();
		  		$data[dbShortLink::field_created_when] = date('Y-m-d H:i:s');
		  		$data[dbShortLink::field_status] = dbShortLink::status_active;
		  		$data[dbShortLink::field_update_by] = $tools->getDisplayName();
		  		$data[dbShortLink::field_update_when] = date('Y-m-d H:i:s');
		  		if ($dt === false) {
		  			$data[dbShortLink::field_valid_until] = '0000-00-00 00:00:00';	
		  		}
		  		else {
		  			$data[dbShortLink::field_valid_until] = date('Y-m-d H:i:s', mktime(23, 59, 59, date('m', $dt), date('d', $dt), date('Y', $dt)));
		  		}
		  		if (!$dbShortLink->sqlInsertRecord($data, $id)) {
		  			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbShortLink->getError()));
		  			return false;
		  		}
  			}
  		}	
  		else {
  			$data[dbShortLink::field_url_short] = sl_text_error;
	  		$data[dbShortLink::field_url_long] = $_REQUEST[dbShortLink::field_url_long];
  		}
  		// Shortlink anzeigen
  		unset($_REQUEST[dbShortLink::field_url_long]);
  		$form_name = 'sl_copy';
  		$items = '';
  		$row = '<tr><td class="sl_label">%s</td><td colspan="2" class="%s">%s</td></tr>';
  		$items .= sprintf($row, sl_label_id, 'sl_id', sprintf('#%05d', $id));
  		// LONG Link
  		$items .= sprintf($row, sl_label_url_long, 'sl_url_long', $data[dbShortLink::field_url_long]);
  		$row_3 = '<tr><td class="sl_label">%s</td><td class="%s">%s</td><td class="%s">%s</td></tr>';
  		// ShortLink
  		$items .= sprintf($row_3, 
  											sl_label_url_short,
  											'sl_url_short',
  											sprintf('<input type="text" id="sl_url_short" class="sl_url_short" name="%s" value="%s" readonly="readonly" />',
  															dbShortLink::field_url_short,
  															$data[dbShortLink::field_url_short]),
  											'sl_explain',
  											sprintf('<input type="button" value="%s" onclick="javascript:TextKopieren(\'%s\');" />',
  															sl_btn_copy, 'sl_url_short'));
  		// Bezeichner
  		(empty($name)) ? $name = sprintf('<i>%s</i>', sl_text_not_established) : $name = sprintf('<b>%s</b>', $name);
  		$items .= sprintf($row, sl_label_name,	'sl_name', $name);
  		// Einweg Link
  		($use_once == 1) ? $checked = ' checked="checked"' : $checked = '';
  		$items .= sprintf($row_3, sl_label_use_once,
  											'sl_use_once',
  											sprintf('<input type="checkbox" name="%s" value="1"%s disabled="disabled" />',
  															dbShortLink::field_use_once,
  															$checked),
  											'sl_explain',
  											'');
  		// Gueltig bis
  		(empty($valid_until)) ? $valid_until = sprintf('<i>%s</i>', sl_text_not_established) : $valid_until = sprintf('<b>%s</b>', $valid_until);
  		$items .= sprintf($row, sl_label_valid_until,	'sl_valid_until', $valid_until);
  		
  		// Mitteilungen anzeigen
			if ($this->isMessage()) {
				$intro = sprintf('<div class="message">%s</div>', $this->getMessage());
			}
			else {
				$intro = sprintf('<div class="intro">%s</div>', sl_intro_link_copy);
			}
			$parser = new tParser();
	  	$parseArray = array(
  			'header'					=> sl_header_shortlink,
  			'intro'						=> $intro,
  			'form_name'				=> $form_name,
  			'form_action'			=> $this->page_link,
  			'action_name'			=> self::request_action,
  			'action_value'		=> self::action_shortlink_edit,
	  		'id_name'					=> dbShortLink::field_id,
	  		'id_value'				=> $id,
  			'items'						=> $items,
  			'btn_edit'				=> sl_btn_change,
	  		'btn_new'					=> sl_btn_new,
	  		'new_location'		=> sprintf('%s&%s=%s', $this->page_link, self::request_action, self::action_shortlink),
	  		'btn_mail'				=> sl_btn_mail,
	  		'mail_location'		=> sprintf(	'%s&%s=%s&%s=%s&%s=%s',
	  																	$this->page_link,
	  																	self::request_action,
	  																	self::action_mail,
	  																	self::request_by,
	  																	self::action_shortlink,
	  																	dbShortLink::field_id,
	  																	$id
	  																	),
  			'btn_abort'				=> sl_btn_abort,
  			'abort_location'	=> $this->page_link
  		);
  		foreach ($parseArray as $key => $value) {
  			$parser->add($key, $value);
  		}
  		$parser->parseTemplateFile($this->template_path.'backend.shortlink.check.htt');
  		return $parser->getHTML();
  	}
  	else {
  		// kein Link uebergeben...
  		$this->setMessage(sl_msg_no_longlink);
  		return $this->createShortLink();
  	}
  } // checkShortLink()
  
  /**
   * Dialog zum Bearbeiten von ShortLinks und DownloadLinks
   * 
   * @return STR DIALOG
   */
  public function editShortLink() {
  	if ((isset($_REQUEST[dbShortLink::field_id])) && ($_REQUEST[dbShortLink::field_id] > 0)) {
  		$tools = new rhTools();
  		$config = new dbShortLinkConfig();
  		// ShortLink bearbeiten
  		$form_name = 'sl_edit';
  		$id = $_REQUEST[dbShortLink::field_id];
  		$dbShortLink = new dbShortLink();
  		$where = array();
  		$where[dbShortLink::field_id] = $id;
  		$link = array();
  		if (!$dbShortLink->sqlSelectRecord($where, $link)) {
  			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbShortLink->getError()));
  			return false;
  		}
  		if (sizeof($link) < 1) {
  			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, sprintf(sl_error_sl_id, $id)));
  			return false;
  		}
  		$link = $link[0];
  		// Handelt es sich um einen ShortLink?
  		($link[dbShortLink::field_type] == dbShortLink::type_shortlink) ? $isShortLink = true : $isShortLink = false; 
  		$items = '';
  		$row = '<tr><td class="sl_label">%s</td><td colspan="2" class="%s">%s</td></tr>';
  		$row_3 = '<tr><td class="sl_label">%s</td class="%s"><td>%s</td><td class="%s">%s</td></tr>';
  		// ID
  		$items .= sprintf($row, sl_label_id, "sl_id", sprintf('#%05d', $id));
  		// Typ
  		$items .= sprintf($row, sl_label_type, "sl_type", $dbShortLink->type_array[$link[dbShortLink::field_type]]);
  		// Param
  		$items .= sprintf($row, sl_label_param, "sl_param", $link[dbShortLink::field_param]);
  		// Aufrufe
  		$items .= sprintf($row, sl_label_count, "sl_count", sprintf('%05d', $link[dbShortLink::field_count]));
  		// letzter Aufruf
  		$items .= sprintf($row, sl_label_last_exec, "sl_last_exec", $dbShortLink->mySQLdate2datum($link[dbShortLink::field_last_exec]));
  		
  		if ($isShortLink) {
  			// spezifische Angaben fuer reine ShortLinks
  			$items .= sprintf($row, sl_label_url_long, "sl_url_long", $link[dbShortLink::field_url_long]);
  			// ShortLink
  			$items .= sprintf($row_3, sl_label_url_short,
  												"sl_url_short",
  												sprintf('<input type="text" id="sl_url_short" class="sl_url_short" name="%s" value="%s" readonly="readonly" />',
  																dbShortLink::field_url_short,
  																$link[dbShortLink::field_url_short]),
  																"sl_explain",
  																sprintf('<input type="button" value="%s" onclick="javascript:TextKopieren(\'%s\');" />',
  																sl_btn_copy, 'sl_url_short'));
  			// Pruefsumme
  			$items .= sprintf($row, sl_label_checksum, "sl_checksum", $link[dbShortLink::field_checksum]);
  		}
  		else {
  			// spezifische Angaben fuer Downloads
  			$items .= sprintf($row, sl_label_dl_file_name, 'sl_dl_fn_origin', $link[dbShortLink::field_dl_fn_origin]);
  			// Dateigroesse
  			$items .= sprintf($row, sl_label_dl_filesize, 'sl_dl_size', $tools->bytes2Str($link[dbShortLink::field_dl_size]));
  			// Checksum
  			$items .= sprintf($row, sl_label_checksum, 'sl_dl_checksum', $link[dbShortLink::field_dl_checksum]);
  			// Speicherort
  			$cfgDownloadPath = $config->getValue(dbShortLinkConfig::cfgDownloadPath);
  			$target = $tools->addSlash(WB_URL).$tools->addSlash($cfgDownloadPath).$link[dbShortLink::field_dl_fn_target];
  			$items .= sprintf($row, sl_label_dl_fn_target, 'sl_dl_fn_target', $target);
  			// DownloadLink
  			$items .= sprintf($row_3, sl_label_url_short,
  												"sl_url_short",
  												sprintf('<input type="text" id="sl_url_short" class="sl_url_short" name="%s" value="%s" readonly="readonly" />',
  																dbShortLink::field_url_short,
  																$link[dbShortLink::field_url_short]),
  																"sl_explain",
  																sprintf('<input type="button" value="%s" onclick="javascript:TextKopieren(\'%s\');" />',
  																sl_btn_copy, 'sl_url_short'));
  		}
  		
  		// Bezeichner
	  	$dbConfig = new dbShortLinkConfig();
	  	$useLinkFile = $dbConfig->getValue(dbShortLinkConfig::cfgLinkFileUse);
	  	$linkFileName = $dbConfig->getValue(dbShortLinkConfig::cfgLinkFileName);
	  	if ($useLinkFile) {
	  		$linkFile = WB_URL.'/'.$linkFileName.'?'.request_name;
	  	}
	  	else {
	  		$linkFile = WB_URL.'/index.php?'.request_name;
	  	}
  		$items .= sprintf($row_3, sl_label_name,
  											"sl_name", 
  											sprintf('<input type="text" name="%s" value="%s" />',
  															dbShortLink::field_name,
  															$link[dbShortLink::field_name]),
  											"sl_explain",
  											sprintf(sl_desc_name, $linkFile));
  		// Einweg Link
  		($link[dbShortLink::field_use_once] == 1) ? $checked = ' checked="checked"' : $checked = '';
  		$items .= sprintf($row_3, 
  											sl_label_use_once,
  											"sl_use_once",
  											sprintf('<input type="checkbox" value="1" name="%s"%s />',
  															dbShortLink::field_use_once,
  															$checked),
  											"sl_explain",
  											sl_desc_use_once);
  		// Gueltig bis
  		if ($link[dbShortLink::field_valid_until] == '0000-00-00 00:00:00') {
  			$valid_until = '';
  		}
  		else {
  			$valid_until = date('d.m.Y', strtotime($link[dbShortLink::field_valid_until]));
  		}
  		$items .= sprintf('<tr><td class="sl_label">%s</td><td><span class="date_picker">'.
	    									'<input type="text" name="%s" value="%s" />'.
	    									'<script language="JavaScript">'.
	    									'new tcal ({ \'formname\': \'%s\', \'controlname\': \'%s\'	}, \'%s\');'.
	    									'</script></span></td><td class="%s">%s</td></tr>',
												sl_label_valid_until,
												dbShortLink::field_valid_until,
												$valid_until,
												$form_name,
												dbShortLink::field_valid_until,
												WB_URL. '/modules/'.basename(dirname(__FILE__)).'/images/calendar/',
												"sl_explain",
												sl_desc_valid_until); 
			if (!$isShortLink) {
				// Datei automatisch l�schen
				($link[dbShortLink::field_dl_unlink] == 1) ? $checked = ' checked="checked"' : $checked = '';
  			$items .= sprintf($row_3, 
													sl_label_unlink_auto,
													"sl_use_once",
  												sprintf('<input type="checkbox" value="1" name="%s"%s />',
  																dbShortLink::field_dl_unlink,
  																$checked),
  												"sl_explain",
  												sl_desc_unlink_auto);
			}
			// Status
			$select = '';
  		foreach ($dbShortLink->status_array as $key => $value) {
  			($key == $link[dbShortLink::field_status]) ? $selected = ' selected="selected"' : $selected = '';
  			$select .= sprintf('<option value="%s"%s>%s</option>', $key, $selected, $value); 
  		}
  		$select = sprintf('<select name="%s">%s</select>', dbShortLink::field_status, $select);
			$items .= sprintf($row_3, 
												sl_label_status,
												"sl_status",
												$select,
												"sl_explain", 
												sl_desc_status);
  		$items .= sprintf($row, sl_label_created_by, 'sl_label_created_by', 
  											sprintf(sl_text_changed_by, 
  															utf8_decode($link[dbShortLink::field_created_by]),
  															$dbShortLink->mySQLdate2datum($link[dbShortLink::field_created_when])));
  		$items .= sprintf($row, sl_label_update_by, 'sl_label_update_by', 
  											sprintf(sl_text_changed_by,
  															utf8_decode($link[dbShortLink::field_update_by]),
  															$dbShortLink->mySQLdate2datum($link[dbShortLink::field_update_when])));
  		
  		// Mitteilungen anzeigen
			if ($this->isMessage()) {
				$intro = sprintf('<div class="message">%s</div>', $this->getMessage());
			}
			else {
				$intro = sprintf('<div class="intro">%s</div>', sl_intro_link_edit);
			}  											
  		$parser = new tParser();
  		($isShortLink) ? $header = sl_header_shortlink_edit : $header = sl_header_dl_edit; 
  		(isset($_REQUEST[self::action_list])) ? $list = 1 : $list = 0;
  		$parseArray = array(
	  		'header'					=> $header,
	  		'intro'						=> $intro,
	  		'form_name'				=> $form_name,
	  		'form_action'			=> $this->page_link,
	  		'action_name'			=> self::request_action,
	  		'action_value'		=> self::action_shortlink_update,
  			'id_name'					=> dbShortLink::field_id,
  			'id_value'				=> $id,
  			'list_name'				=> self::action_list,
  			'list_value'			=> $list,
	  		'items'						=> $items,
	  		'btn_ok'					=> sl_btn_ok,
	  		'btn_abort'				=> sl_btn_abort,
	  		'abort_location'	=> $this->page_link,
  			'btn_mail'				=> sl_btn_mail,
  			'mail_location'		=> sprintf(	'%s&%s=%s&%s=%s&%s=%s',
	  																	$this->page_link,
	  																	self::request_action,
	  																	self::action_mail,
	  																	self::request_by,
	  																	self::action_shortlink,
	  																	dbShortLink::field_id,
	  																	$id
	  																	)
  		);
  		foreach ($parseArray as $key => $value) {
  			$parser->add($key, $value);
  		}
  		$parser->parseTemplateFile($this->template_path.'backend.shortlink.edit.htt');
  		return $parser->getHTML();
  	} // ShortLink bearbeiten
  	else {
  		// keine ID uebergeben
  		$this->setError(sl_error_missing_id);
  		return false;
  	}
  } // editShortLink()
  
  /**
   * Prueft Aenderungen am ShortLink, aktualisiert den Datensatz und zeigt 
   * editShortLink() erneut an.
   */
  public function updateShortLink() {
  	$statusChanged = false;
  	if ((isset($_REQUEST[dbShortLink::field_id])) && ($_REQUEST[dbShortLink::field_id] > 0)) {
  		// Daten pruefen, zunaechst Datensatz einlesen
  		$id = $_REQUEST[dbShortLink::field_id];
  		$dbShortLink = new dbShortLink();
  		$tools = new rhTools();
  		$where = array();
  		$where[dbShortLink::field_id] = $id;
  		$oldShortLink = array();
  		if (!$dbShortLink->sqlSelectRecord($where, $oldShortLink)) {
  			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbShortLink->getError()));
  			return false;
  		}
  		if (sizeof($oldShortLink) < 1) {
  			// Datensatz nicht gefunden
  			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, sprintf(sl_error_sl_id, $id)));
  			return false;
  		}
  		$oldShortLink = $oldShortLink[0];
  		($oldShortLink[dbShortLink::field_type] == dbShortLink::type_shortlink) ? $isShortLink = true : $isShortLink = false;
  		$updateShortLink = array();
  		$changed = false;
  		$message = '';
  		$linkFile = '';
  		$this->checkLinkFile($linkFile);
  		// Bezeichner geaendert?
  		((isset($_REQUEST[dbShortLink::field_name])) && (!empty($_REQUEST[dbShortLink::field_name]))) ? 
  			$name = strtolower($tools->cleanFileName($_REQUEST[dbShortLink::field_name])) : $name = '';
  		if ($oldShortLink[dbShortLink::field_name] != $name) {
  			// Bezeichner wurde geaendert
  			if (!$this->checkLinkFile($linkFile)) {
  				// Fehler beim Pruefen der Linkdatei
  				return $this->editShortLink();
  			}
  			// neuen ShortLink erzeugen
  			if (empty($name)) {
  				// Aufruf mit Kennung
  				if ($isShortLink) {
  					$updateShortLink[dbShortLink::field_url_short] = sprintf('%s?%s=%s', $linkFile, request_link, $oldShortLink[dbShortLink::field_param]);
  				}
  				else {
  					// DownloadLink
  					$updateShortLink[dbShortLink::field_url_short] = sprintf('%s?%s=%s', $linkFile, request_download, $oldShortLink[dbShortLink::field_param]);
  				}
  			}
  			else {
  				// Bezeichner wurde geaendert oder neu erstellt, pruefen ob er verwendet werden kann
  				$SQL = sprintf(	"SELECT * FROM %s WHERE %s='%s' AND %s!='%s'",
  												$dbShortLink->getTableName(),
  												dbShortLink::field_name,
  												$name,
  												dbShortLink::field_status,
  												dbShortLink::status_deleted);
  				$check = array();
					if (!$dbShortLink->sqlExec($SQL, $check)) {
						$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbShortLink->getError()));
  					return false;  												
					}
					if (sizeof($check) > 0) {
						// Bezeichner wird bereits verwendet
						$this->setMessage(sprintf(sl_msg_sl_name_exists, $name, $check[0][dbShortLink::field_id]));
						return $this->editShortLink();
					}
					// Aufruf mit Bezeichner
					if ($isShortLink) {
						$updateShortLink[dbShortLink::field_url_short] = sprintf('%s?%s=%s', $linkFile, request_name, $name);
					}
					else {
						$updateShortLink[dbShortLink::field_url_short] = sprintf('%s?%s=%s', $linkFile, request_download_name, $name);
					}
  			}
  			// Daten wurden geaendert
  			$changed = true;
  			$updateShortLink[dbShortLink::field_name] = $name;
  			$message .= sprintf(sl_msg_sl_name_changed, $name);  			
  		}
  		// Einweglink?
  		((isset($_REQUEST[dbShortLink::field_use_once])) && ($_REQUEST[dbShortLink::field_use_once] == 1)) ? $use_once = 1 : $use_once = 0;
  		if ($oldShortLink[dbShortLink::field_use_once] != $use_once) {
  			// Einstellung wurde geaendert
  			$changed = true;
  			$updateShortLink[dbShortLink::field_use_once] = $use_once;
  			$message .= sl_msg_sl_use_once;
  		}  		
  		// Gueltig bis?
  		((isset($_REQUEST[dbShortLink::field_valid_until])) && (!empty($_REQUEST[dbShortLink::field_valid_until]))) ? 
  			$strDate = $_REQUEST[dbShortLink::field_valid_until] : $strDate = '';
  		if (empty($strDate)) {
  			$date = '0000-00-00 00:00:00';
				$time = -1;
  		}
  		else {
  			// Datum auf Gueltigkeit pruefen
  			if (($time = strtotime($strDate)) === false) {
  				$this->setMessage(sprintf(sl_msg_invalid_date, $strDate));
	  			return $this->editShortLink();
  			}
  			$time = mktime(23, 59, 59, date('m', $time), date('d', $time), date('Y', $time));
  			$date = date('Y-m-d H:i:s', $time);
  		}
  		if ($oldShortLink[dbShortLink::field_valid_until] != $date) {
  			// Datum wurde geaendert
  			$changed = true;
  			$updateShortLink[dbShortLink::field_valid_until] = $date;
  			if ($time == -1) {
  				$message .= sl_msg_sl_date_reset;
  			}
  			else      {
  				$message .= sprintf(sl_msg_sl_valid_until_changed, date('d.m.Y - H:i:s', $time));
  			}	
  		} 
  		// Datei automatisch loeschen?
  		((isset($_REQUEST[dbShortLink::field_dl_unlink])) && ($_REQUEST[dbShortLink::field_dl_unlink] == 1)) ? $unlink_auto = 1 : $unlink_auto = 0;
  		if ($oldShortLink[dbShortLink::field_dl_unlink] != $unlink_auto) {
  			// Einstellung wurde geaendert
  			$changed = true;
  			$updateShortLink[dbShortLink::field_dl_unlink] = $unlink_auto;
  			$message .= sl_msg_unlink_auto;
  		} 			
  		// Status?
  		(isset($_REQUEST[dbShortLink::field_status])) ? $status = $_REQUEST[dbShortLink::field_status] : $status = -1;
  		if (($status != -1) && ($oldShortLink[dbShortLink::field_status] != $status)) { 
  			// Status wurde geaendert
  			$SQL = sprintf(	"SELECT * FROM %s WHERE (%s='%s' AND %s!='%s' AND %s!='%s')",
  											$dbShortLink->getTableName(),
  											dbShortLink::field_url_long,
  											$oldShortLink[dbShortLink::field_url_long],
  											dbShortLink::field_id,
  											$id,
  											dbShortLink::field_status,
  											dbShortLink::status_deleted);
  			$check = array();
  			if (!$dbShortLink->sqlExec($SQL, $check)) {
  				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbShortLink->getError()));
  					return false;
  			}
  			if (sizeof($check) > 0) {
  				// die ZIEL URL wird bereits verwendet, Datensatz kann nicht wiederhergestellt werden
  				$message .= sprintf(sl_msg_sl_restore_link_exists, $check[0][dbShortLink::field_id]);
  			}
  			else {
	  			if (($oldShortLink[dbShortLink::field_status] == dbShortLink::status_deleted) && 
	  					(!empty($oldShortLink[dbShortLink::field_name]))) {
	  				// Eine geloeschte Datei, die einen Bezeichner verwendet hat, soll wieder aktivert werden
	  				$SQL = sprintf(	"SELECT * FROM %s WHERE (%s='%s' AND %s!='%s' AND %s!='%s')",
	  												$dbShortLink->getTableName(),
	  												dbShortLink::field_name,
	  												$oldShortLink[dbShortLink::field_name],
	  												dbShortLink::field_id,
	  												$id,
	  												dbShortLink::field_status,
	  												dbShortLink::status_deleted); 
	  				$check = array();
	  				if (!$dbShortLink->sqlExec($SQL, $check)) {
	  					$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbShortLink->getError()));
	  					return false;
	  				}
	  				if (sizeof($check) > 0) {
	  					// Treffer. Der Bezeicher wird inzwischen von einer anderen Datei verwendet
	  					if (!$this->checkLinkFile($linkFile)) {
	  						// Fehler beim Pruefen der Linkdatei
	  						return $this->editShortLink();
	  					}
	  					// Bezeichner zuruecksetzen und neuen ShortLink setzen
	  					$updateShortLink[dbShortLink::field_name] = '';
	  					$updateShortLink[dbShortLink::field_url_short] = sprintf('%s?%s=%s', $linkFile, request_link, $oldShortLink[dbShortLink::field_param]);
	  					$message .= sprintf(sl_msg_sl_restore_name_exists, $oldShortLink[dbShortLink::field_name], $check[0][dbShortLink::field_id]);
	  				}
	  			}
		  		// Status wurde geaendert
		  		$changed = true;
		  		$statusChanged = true;
		  		$updateShortLink[dbShortLink::field_status] = $status;
		  		$message .= sl_msg_sl_status_changed;
  			}
  		}
  		
  		if ($changed) {
  			// Datensatz wurde geaendert
  			$where = array();
  			$where[dbShortLink::field_id] = $id;
  			$updateShortLink[dbShortLink::field_update_by] = $tools->getDisplayName();
  			$updateShortLink[dbShortLink::field_update_when] = date('Y-m-d H:i:s');
  			if (!$dbShortLink->sqlUpdateRecord($updateShortLink, $where)) {
  				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbShortLink->getError()));
	  			return false;
  			}
  			$message .= sl_msg_sl_updated;
  		}
  		// Fertig...
  		(empty($message)) ? $this->setMessage(sl_msg_no_changes) : $this->setMessage($message);
  		//if (($statusChanged == true) && ((isset($_REQUEST[self::request_by])) && ($_REQUEST[self::request_by] == self::action_list))) {
  		if (($statusChanged) && ((isset($_REQUEST[self::action_list])) && ($_REQUEST[self::action_list] == 1))) {
  			// Link wurde von der Liste aus aufgerufen und der Status geaendert - zurueck zur Liste...
  			return $this->showList();
  		}
  		else {
  			return $this->editShortLink();
  		}
  	}
  	else {
  		// keine ID uebergeben
  		$this->setError(sl_error_missing_id);
  		return false;
  	}
  } // updateShortLink()
  
  
  /**
   * Zeigt den INFO Dialog an
   * 
   * @return STR DIALOG
   */
  public function dlgInfo() {
  	$parser = new tParser();
  	$parseArray = array(
  		'header'			=> sl_header_shortlink,
  		'copyright'		=> sprintf(sl_text_copyright, $this->getVersion()),
  		'anleitung'		=> sl_text_anleitung,
  		'anleitung_2'	=> sl_text_anleitung_2
  	);
  	foreach ($parseArray as $key => $value) {
  		$parser->add($key, $value);
  	}
  	$parser->parseTemplateFile($this->template_path.'backend.info.htt');
  	return $parser->getHTML();
  } // dlgInfo()
  
  /**
	 * Dialog zum Bearbeiten der Konfigurationseinstellungen
	 * 
	 * @return STR Dialog
	 */
	public function editConfig() {
		$dbShortLinkConfig = new dbShortLinkConfig();
		$SQL = sprintf(	"SELECT * FROM %s WHERE NOT %s='%s' ORDER BY %s",
										$dbShortLinkConfig->getTableName(),
										dbShortLinkConfig::field_status,
										dbShortLinkConfig::status_deleted,
										dbShortLinkConfig::field_name);
		$config = array();
		if (!$dbShortLinkConfig->sqlExec($SQL, $config)) {
			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbShortLinkConfig->getError()));
			return false;
		}
		$count = array();
		$items = sprintf(	'<tr><th>%s</th><th>%s</th><th>%s</th><th>%s</th><th>%s</th></tr>',
											'&nbsp;',
											sl_header_identifier,
											sl_header_typ,
											sl_header_value,
											sl_header_description );
		$row = '<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>';
		// bestehende Eintraege auflisten
		foreach ($config as $entry) {
			$id = $entry[dbShortLinkConfig::field_id];
			$count[] = $id;
			$label = constant($entry[dbShortLinkConfig::field_label]);
			$bezeichner = $entry[dbShortLinkConfig::field_name];
			$typ = $dbShortLinkConfig->type_array[$entry[dbShortLinkConfig::field_type]];
			(isset($_REQUEST[dbShortLinkConfig::field_value.'_'.$id])) ? 
				$val = $_REQUEST[dbShortLinkConfig::field_value.'_'.$id] : 
				$val = $entry[dbShortLinkConfig::field_value];
			$value = sprintf(	'<input type="text" name="%s_%s" value="%s" />', dbShortLinkConfig::field_value, $id,	$val);
			$desc = constant($entry[dbShortLinkConfig::field_description]);
			$items .= sprintf($row, $label, $bezeichner, $typ, $value, $desc);
		}
		$items_value = implode(",", $count);
		
		// Checkbox fuer CSV Export
		$items .= sprintf('<tr><td>&nbsp;</td><td colspan="4"><input type="checkbox" name="%s" value="1">&nbsp;%s</td></tr>',
											self::request_csv_export,
											sl_label_csv_export);
		
		// Konfiguration auslesen
		$dbConfig = new dbShortLinkConfig();
		$developerMode = $dbConfig->getValue(dbShortLinkConfig::cfgDeveloperMode);
		
		if ($developerMode) {
		// neue Eintraege hinzufuegen
			$add = sprintf(	'<tr><td colspan="5">&nbsp;</td></tr><tr><td colspan="5"><div class="intro">%s</div></td></tr><tr><td colspan="5">&nbsp;</td></tr>',
											sl_intro_cfg_add_item);
			$add .= sprintf('<tr><th>%s</th><th>%s</th><th>%s</th><th>%s</th><th>%s</th></tr>',
											sl_header_label,
											sl_header_identifier,
											sl_header_typ,
											sl_header_value,
											sl_header_description);
			$row = '<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>';
			(isset($_REQUEST[dbShortLinkConfig::field_name])) ? $name = utf8_decode($_REQUEST[dbShortLinkConfig::field_name]) : $name = '';
			(isset($_REQUEST[dbShortLinkConfig::field_type])) ? $type_val = $_REQUEST[dbShortLinkConfig::field_type] : $type_val = dbShortLinkConfig::type_undefined;
			$typ = '';
			foreach ($dbShortLinkConfig->type_array as $key => $value) {
				($key == $type_val) ? $selected = ' selected="selected"' : $selected = '';			
				$typ .= sprintf('<option value="%s"%s>%s</option>', $key, $selected, $value);
			}
			$typ = sprintf('<select name="%s" size="1">%s</select>', dbShortLinkConfig::field_type, $typ);
			(isset($_REQUEST[dbShortLinkConfig::field_value])) ? $val = utf8_decode($_REQUEST[dbShortLinkConfig::field_value]) : $val = ''; 
			(isset($_REQUEST[dbShortLinkConfig::field_label])) ? $label = utf8_decode($_REQUEST[dbShortLinkConfig::field_label]) : $label = ''; 
			(isset($_REQUEST[dbShortLinkConfig::field_description])) ? $description = utf8_decode($_REQUEST[dbShortLinkConfig::field_description]) : $description = ''; 
			$add .= sprintf($row,
											sprintf('<input type="text" name="%s" value="%s">', dbShortLinkConfig::field_label, $label),
											sprintf('<input type="text" name="%s" value="%s">', dbShortLinkConfig::field_name, $name),
											$typ,
											sprintf('<input type="text" name="%s" value="%s">', dbShortLinkConfig::field_value, $val),
											sprintf('<input type="text" name="%s" value="%s">', dbShortLinkConfig::field_description, $description)										
											);
			$add .= '<tr><td colspan="5">&nbsp;</td></tr>';
		}
		else {
			$add = '';
		}
		// Mitteilungen anzeigen
		if ($this->isMessage()) {
			$intro = sprintf('<div class="message">%s</div>', $this->getMessage());
		}
		else {
			$intro = sprintf('<div class="intro">%s</div>', sl_intro_cfg);
		}		
		$parser = new tParser();
		$parseArray = array(
			'form_name'						=> 'konfiguration',
			'form_action'					=> $this->page_link,
			'action_name'					=> self::request_action,
			'action_value'				=> self::action_cfg_check,
			'items_name'					=> self::request_items,
			'items_value'					=> $items_value,
			'header'							=> sl_header_config,
			'intro'								=> $intro,
			'items'								=> $items,
			'add'									=> $add,
			'btn_ok'							=> sl_btn_ok,
			'btn_abort'						=> sl_btn_abort,
			'abort_location'			=> $this->page_link
		);
		foreach ($parseArray as $key => $value) {
			$parser->add($key, $value);
		}
		$parser->parseTemplateFile($this->template_path.'backend.cfg.htt');
		return $parser->getHTML();
	} // editConfig()
	
	/**
	 * Ueberprueft Aenderungen die im Dialog editKonfiguration() vorgenommen wurden
	 * und aktualisiert die entsprechenden Datensaetze.
	 * Fuegt neue Datensaetze ein.
	 * 
	 * @return STR DIALOG editConfig()
	 */
	public function checkConfig() {
		$message = '';
		$dbShortLinkConfig = new dbShortLinkConfig();
		$tools = new rhTools();
		// ueberpruefen, ob ein Eintrag geaendert wurde
		if ((isset($_REQUEST[self::request_items])) && (!empty($_REQUEST[self::request_items]))) {
			$ids = explode(",", $_REQUEST[self::request_items]);
			foreach ($ids as $id) {
				if (isset($_REQUEST[dbShortLinkConfig::field_value.'_'.$id])) {
					$value = utf8_decode($_REQUEST[dbShortLinkConfig::field_value.'_'.$id]);
					$where = array();
					$where[dbShortLinkConfig::field_id] = $id; 
					$config = array();
					if (!$dbShortLinkConfig->sqlSelectRecord($where, $config)) {
						$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbShortLinkConfig->getError()));
						return false;
					}
					if (sizeof($config) < 1) {
						$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, sprintf(sl_error_cfg_id, $id)));
						return false;
					}
					$config = $config[0];
					if ($config[dbShortLinkConfig::field_value] != $value) {
						// Wert wurde geaendert
						if (!$dbShortLinkConfig->setValue($value, $id) && $dbShortLinkConfig->isError()) {
							$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbShortLinkConfig->getError()));
							return false;
						}
						elseif ($dbShortLinkConfig->isMessage()) {
							$message .= $dbShortLinkConfig->getMessage();
						}
						else {
							// Datensatz wurde aktualisiert
							$message .= sprintf(sl_msg_cfg_id_updated, $id, $config[dbShortLinkConfig::field_name]);
						}
					}
				}
			}		
		}		
		// ueberpruefen, ob ein neuer Eintrag hinzugefuegt wurde
		if ((isset($_REQUEST[dbShortLinkConfig::field_name])) && (!empty($_REQUEST[dbShortLinkConfig::field_name]))) {
			// pruefen ob dieser Konfigurationseintrag bereits existiert
			$where = array();
			$where[dbShortLinkConfig::field_name] = utf8_decode($_REQUEST[dbShortLinkConfig::field_name]);
			$where[dbShortLinkConfig::field_status] = dbShortLinkConfig::status_active;
			$result = array();
			if (!$dbShortLinkConfig->sqlSelectRecord($where, $result)) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbShortLinkConfig->getError()));
				return false;
			}
			if (sizeof($result) > 0) {
				// Eintrag existiert bereits
				$message .= sprintf(sl_msg_cfg_add_exists, $where[dbShortLinkConfig::field_name]);
			}
			else {
				// Eintrag kann hinzugefuegt werden
				$data = array();
				$data[dbShortLinkConfig::field_name] = utf8_decode($_REQUEST[dbShortLinkConfig::field_name]);
				if (((isset($_REQUEST[dbShortLinkConfig::field_type])) && ($_REQUEST[dbShortLinkConfig::field_type] != dbShortLinkConfig::type_undefined)) &&
						((isset($_REQUEST[dbShortLinkConfig::field_value])) && (!empty($_REQUEST[dbShortLinkConfig::field_value]))) &&
						((isset($_REQUEST[dbShortLinkConfig::field_label])) && (!empty($_REQUEST[dbShortLinkConfig::field_label]))) &&
						((isset($_REQUEST[dbShortLinkConfig::field_description])) && (!empty($_REQUEST[dbShortLinkConfig::field_description])))) {
					// Alle Daten vorhanden
					unset($_REQUEST[dbShortLinkConfig::field_name]);
					$data[dbShortLinkConfig::field_type] = $_REQUEST[dbShortLinkConfig::field_type];
					unset($_REQUEST[dbShortLinkConfig::field_type]);
					$data[dbShortLinkConfig::field_value] = utf8_decode($_REQUEST[dbShortLinkConfig::field_value]);
					unset($_REQUEST[dbShortLinkConfig::field_value]);
					$data[dbShortLinkConfig::field_label] = utf8_decode($_REQUEST[dbShortLinkConfig::field_label]);
					unset($_REQUEST[dbShortLinkConfig::field_label]);
					$data[dbShortLinkConfig::field_description] = utf8_decode($_REQUEST[dbShortLinkConfig::field_description]);
					unset($_REQUEST[dbShortLinkConfig::field_description]);
					$data[dbShortLinkConfig::field_status] = dbShortLinkConfig::status_active;
					$data[dbShortLinkConfig::field_update_by] = $tools->getDisplayName();
					$data[dbShortLinkConfig::field_update_when] = date('Y-m-d H:i:s');
					$id = -1;
					if (!$dbShortLinkConfig->sqlInsertRecord($data, $id)) {
						$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbShortLinkConfig->getError()));
						return false; 
					}
					$message .= sprintf(sl_msg_cfg_add_success, $id, $data[dbShortLinkConfig::field_name]);		
				}
				else {
					// Daten unvollstaendig
					$message .= sl_msg_cfg_add_incomplete;
				}
			}
		}
		// Sollen Daten als CSV gesichert werden?
		if ((isset($_REQUEST[self::request_csv_export])) && ($_REQUEST[self::request_csv_export] == 1)) {
			// Daten sichern
			$where = array();
			$where[dbShortLinkConfig::field_status] = dbShortLinkConfig::status_active;
			$csv = array();
			$csvFile = WB_PATH.MEDIA_DIRECTORY.'/'.date('ymd-His').'-shortlink-cfg.csv';
			if (!$dbShortLinkConfig->csvExport($where, $csv, $csvFile)) {
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbShortLinkConfig->getError()));
				return false; 
			}
			$message .= sprintf(sl_msg_cfg_csv_export, basename($csvFile));
		}
		
		if (!empty($message)) $this->setMessage($message);
		return $this->editConfig();
	} // checkConfig()
  
  public function showList() {
  	$dbShortLink = new dbShortLink();
  	$SQL = sprintf(	"SELECT * FROM %s WHERE NOT %s='%s'",
  									$dbShortLink->getTableName(),
  									dbShortLink::field_status,
  									dbShortLink::status_deleted);
  	$result = array();
  	if (!$dbShortLink->sqlExec($SQL, $result)) {
  		$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbShortLink->getError()));
			return false;
  	}
  	// Symbol fuer DownloadLinks vorbereiten
  	$img_path = WB_PATH .'/modules/'.basename(dirname(__FILE__)).'/images/disk.png';
  	$img_url = WB_URL .'/modules/'.basename(dirname(__FILE__)).'/images/disk.png';
  	if (file_exists($img_path)) { 
  		$image_size = getimagesize($img_path);
      if ($image_size !== false) {
      	// split image_size to width, height and type...
      	list($width, $height, $type) = $image_size;
				$disk_img =  sprintf(	'<img src="%s" width="%s" height="%s" title="%s" alt="%s" />',
  														$img_url,
  														$width,
  														$height,
  														sl_label_download_link,
  														sl_label_download_link);
  		}
  		else {
  			$disk_img = '';
  		}
  	}
  	else {
  		$disk_img = '';
  	}
  	$items = '';
  	$items = sprintf('<tr><th>%s</th><th>%s</th><th>%s</th><th>%s</th><th>%s</th><th>%s</th><th>%s</th><th>%s</th><th>%s</th><th>%s</th></tr>',
  										sl_header_id,
  										'',
  										sl_header_shortlink,
  										sl_header_param,
  										sl_header_name,
  										sl_header_use_once,
  										sl_header_valid_until,
  										sl_header_count,
  										sl_header_last_exec,
  										sl_header_update_when	);
  	$flipFlop = true;
  	foreach ($result as $link) {
  		if ($flipFlop) {
  		  $flipFlop = false; $flip = 'flip';
  		}
  		else {
  		  $flipFlop = true; $flip = 'flop';
  		}
  		($link[dbShortLink::field_use_once] == 1) ? $use_once = sl_text_yes_lower : $use_once = '';
  		($link[dbShortLink::field_valid_until] == '0000-00-00 00:00:00') ? $valid_until = '' : $valid_until = date('d.m.Y', strtotime($link[dbShortLink::field_valid_until])); 
  		($link[dbShortLink::field_last_exec] == '0000-00-00 00:00:00') ? $last_exec = '' : $last_exec = date('d.m.Y H:i:s', strtotime($link[dbShortLink::field_last_exec])); 
  		($link[dbShortLink::field_update_when] == '0000-00-00 00:00:00') ? $update_when = '' : $update_when = date('d.m.Y H:i:s', strtotime($link[dbShortLink::field_update_when]));
  		if ($link[dbShortLink::field_type] == dbShortLink::type_download) {
  			$disk = $disk_img;
  			$target = $link[dbShortLink::field_dl_fn_origin]; 
  		}
  		else {
  			$disk = '';
  			$target = $link[dbShortLink::field_url_long];
  		}
  		
  		$items .= sprintf('<tr class="%s"><td class="sl_id">%s</td><td class="sl_disc">%s</td><td class="sl_shortlink">%s</td><td class="sl_param">%s</td>'.
  											'<td class="sl_name">%s</td><td class="sl_use_once">%s</td><td class="sl_valid_until_r">%s</td><td class="sl_count_r">%s</td>'.
  											'<td class="sl_last_exec_r">%s</td><td class="sl_update_when_r">%s</td></tr>',
  											$flip,
  											sprintf('#%05d', $link[dbShortLink::field_id]),
  											$disk,
  											sprintf('<a href="%s" title="%s" alt=""%s">%s</a>',
  															$this->page_link.'&'.self::request_action.'='.self::action_shortlink_edit.'&'.
  															dbShortLink::field_id.'='.$link[dbShortLink::field_id].'&'.self::action_list.'=1',
  															$target,
  															$target,
  															$link[dbShortLink::field_url_short]),
  											$link[dbShortLink::field_param],
  											$link[dbShortLink::field_name],
  											$use_once,
  											$valid_until,
  											sprintf('%05d', $link[dbShortLink::field_count]),
  											$last_exec,
  											$update_when				
  											);
  	}
  	$parser = new tParser();
  	$parseArray = array(
  		'header'				=> sl_header_sl_list,
  		'intro'					=> sprintf('<div class="intro">%s</div>', sl_intro_sl_list),
  		'items'					=> $items
  	);
  	foreach ($parseArray as $key => $value) {
  		$parser->add($key, $value);
  	}
  	$parser->parseTemplateFile($this->template_path.'backend.shortlink.list.htt');
  	return $parser->getHTML();
  } // showList()
  
  public function createDownloadLink() {
  	$config = new dbShortLinkConfig();
  	$cfgDLUnlinkAuto = $config->getValue(dbShortLinkConfig::cfgDLUnlinkAuto);
  	$cfgDLUseOnce = $config->getValue(dbShortLinkConfig::cfgDLUseOnce);
  	$form_name = 'dl_create';
  	$row = '<tr><td class="sl_label">%s</td><td colspan="2" class="%s">%s</td></tr>';
  	$row_3 = '<tr><td class="sl_label">%s</td><td class="%s">%s</td><td class="%s">%s</td></tr>';
  	$items = '';
  	// Datei uebertragen
  	$items .= sprintf($row, 
  										sl_label_upload_file,
  										'sl_upload_file',
  										sprintf('<input type="file" name="%s" />', self::request_file));
  	// Bezeichner
  	$linkFile = '';
  	$this->checkLinkFile($linkFile);
  	$items .= sprintf($row_3,
  										sl_label_name,
  										'sl_name',
  										sprintf('<input type="text" name="%s" value="%s">',
  														dbShortLink::field_name,
  														''),
  										'sl_explain',
  										sprintf(sl_desc_name,
  														$linkFile ));
  	// Einweglink
  	($cfgDLUseOnce) ? $checked = ' checked="checked"' : $checked = '';
  	$items .= sprintf($row_3,
  										sl_label_use_once,
  										'sl_use_once',
  										sprintf('<input type="checkbox" name="%s" value="1"%s />',
  														dbShortLink::field_use_once,
  														$checked),
  										'sl_explain',
  										sl_desc_use_once);
  	// Gueltig bis
		$items .= sprintf('<tr><td class="sl_label">%s</td><td><span class="date_picker">'.
    									'<input type="text" name="%s" value="%s" />'.
    									'<script language="JavaScript">'.
    									'new tcal ({ \'formname\': \'%s\', \'controlname\': \'%s\'	}, \'%s\');'.
    									'</script></span></td><td class="%s">%s</td></tr>',
											sl_label_valid_until,
											dbShortLink::field_valid_until,
											'',
											$form_name,
											dbShortLink::field_valid_until,
											WB_URL. '/modules/'.basename(dirname(__FILE__)).'/images/calendar/',
											'sl_explain',
											sl_desc_valid_until);  														
  	// Datei loeschen
  	($cfgDLUnlinkAuto) ? $checked = ' checked="checked"' : $checked = '';
  	$items .= sprintf($row_3,
  										sl_label_unlink_auto,
  										'sl_unlink_auto',
  										sprintf('<input type="checkbox" name="%s" value="1"%s />',
  														dbShortLink::field_dl_unlink,
  														$checked),
  										'sl_explain',
  										sl_desc_unlink_auto);
  	// Mitteilungen anzeigen
		if ($this->isMessage()) {
			$intro = sprintf('<div class="message">%s</div>', $this->getMessage());
		}
		else {
			$intro = sprintf('<div class="intro">%s</div>', sl_intro_downloadlink);
		}		
		$parser = new tParser();
  	$parseArray = array(
  		'form_name'					=> $form_name,
  		'form_action'				=> $this->page_link,
  		'action_name'				=> self::request_action,
  		'action_value'			=> self::action_download_insert,
  		'header'						=> sl_header_download,
  		'intro'							=> $intro,
  		'items'							=> $items,
  		'btn_ok'						=> sl_btn_ok,
  		'btn_abort'					=> sl_btn_abort,
  		'abort_location'		=> $this->page_link
  	);
  	foreach ($parseArray as $key => $value) {
  		$parser->add($key, $value);
  	}
  	$parser->parseTemplateFile($this->template_path.'backend.download.htt');
  	return $parser->getHTML();
  } // createDownloadLink()
  
  public function checkDownloadLink() {
  	if (is_uploaded_file($_FILES[self::request_file]['tmp_name'])) {
  		// Es wurde eine Datei uebertragen
  		$message = '';
  		$new_upload = true;
  		$tools = new rhTools();
  		$config = new dbShortLinkConfig();
  		$download_path = $tools->addSlash(WB_PATH).$tools->addSlash($config->getValue(dbShortLinkConfig::cfgDownloadPath));
  		// Pruefen, ob das Verzeichnis existiert
  		if (!file_exists($download_path)) {
  			if (!mkdir($download_path, 0777, true)) {
  				// Verzeichnis konnte nicht erstellt werden
  				$this->setMessage(sprintf(sl_msg_mkdir_error, $download_path));
  				return $this->createDownloadLink();
  			}
  			else {
  				$message .= sprintf(sl_msg_mkdir_success, $download_path);
  			}
  		}
  		// Bezeichner
  		((isset($_REQUEST[dbShortLink::field_name])) && (!empty($_REQUEST[dbShortLink::field_name]))) ? $name = $_REQUEST[dbShortLink::field_name] : $name = '';
  		$linkFile = '';
  		if (!$this->checkLinkFile($linkFile)) {
  			return $this->createDownloadLink();
  		}
  		// valid_until
  		if ((isset($_REQUEST[dbShortLink::field_valid_until])) && (!empty($_REQUEST[dbShortLink::field_valid_until]))) {
			  if (($dt = strtotime($_REQUEST[dbShortLink::field_valid_until])) === false) {
			  	$valid_until = '';				  		
			  }
			  else {
			  	$valid_until = $_REQUEST[dbShortLink::field_valid_until];
			  }
			}
			else {
				$dt = false;
			 	$valid_until = '';
			}
			// use_once
	  	((isset($_REQUEST[dbShortLink::field_use_once])) && ($_REQUEST[dbShortLink::field_use_once] == 1)) ? $use_once = 1 : $use_once = 0; 
	  	// dl_unlink
			((isset($_REQUEST[dbShortLink::field_dl_unlink])) && ($_REQUEST[dbShortLink::field_dl_unlink] == 1)) ? $dl_unlink = 1 : $dl_unlink = 0; 
  		$dbShortLink = new dbShortLink();
  		// Pruefen, ob fuer diese Datei bereits ein aktiver DownloadLink existiert
  		$SQL = sprintf(	"SELECT * FROM %s WHERE %s='%s' AND %s!='%s'",
  										$dbShortLink->getTableName(),
  										dbShortLink::field_dl_fn_origin,
  										$_FILES[self::request_file]['name'],
  										dbShortLink::field_status,
  										dbShortLink::status_deleted);
  		$result = array();
  		if (!$dbShortLink->sqlExec($SQL, $result)) {
  			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbShortLink->getError()));
  			return false;
  		}
  		if (sizeof($result) > 0) {
  			// Dieser Download existiert bereits
  			$result = $result[0];
  			if (!file_exists($download_path.$result[dbShortLink::field_dl_fn_target])) {
  				// PROBLEM: Download Datei existiert nicht mehr, Datensatz loeschen und NEUEN Datensatz anlegen
  				$data = array();
  				$data[dbShortLink::field_status] = dbShortLink::status_deleted;
  				$data[dbShortLink::field_update_by] = 'SYSTEM';
  				$data[dbShortLink::field_update_when] = date('Y-m-d H:i:s');
  				$where = array();
  				$where[dbShortLink::field_id] = $result[dbShortLink::field_id];
  				if (!$dbShortLink->sqlUpdateRecord($data, $where)) {
  					$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbShortLink->getError()));
  					return false; 
  				}
  				$message .= sprintf(sl_msg_dl_exists_file_missing, $result[dbShortLink::field_dl_fn_origin]);
  			}
  			else {
  				// Datensatz aktualisieren und verwenden
  				$new_upload = false;
  				// Download Link
  				if (!empty($name)) {
  					$download_link = sprintf('%s?%s=%s', $linkFile, request_download_name, $name);
  				}
  				else {
  					$download_link = sprintf('%s?%s=%s', $linkFile, request_download, $result[dbShortLink::field_param]);
  				}
  				$id = $result[dbShortLink::field_id];
  				$where = array();
  				$where[dbShortLink::field_id] = $id;
  				$data = array();
  				$data[dbShortLink::field_name] = $name;
					$data[dbShortLink::field_url_short] = $download_link;
					$data[dbShortLink::field_dl_fn_origin] = $result[dbShortLink::field_dl_fn_origin];
					$data[dbShortLink::field_dl_unlink] = $dl_unlink;
					if ($dt === false) {
			  		$data[dbShortLink::field_valid_until] = '0000-00-00 00:00:00';	
			  	}
			  	else {
			  		$data[dbShortLink::field_valid_until] = date('Y-m-d H:i:s', mktime(23, 59, 59, date('m', $dt), date('d', $dt), date('Y', $dt)));
			  	}
			  	$data[dbShortLink::field_use_once] = $use_once;
			  	$data[dbShortLink::field_status] = dbShortLink::status_active;
			  	$data[dbShortLink::field_update_by] = $tools->getDisplayName();
			  	$data[dbShortLink::field_update_when] = date('Y-m-d H:i:s');
			  	if (!$dbShortLink->sqlUpdateRecord($data, $where)) {
			  		$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbShortLink->getError()));
			  		return false;
			  	}
			  	$message .= sprintf(sl_msg_dl_exists, $data[dbShortLink::field_dl_fn_origin], $data[dbShortLink::field_url_short]);
  			}
  		}
  		if ($new_upload) {
  			// Neuer Datensatz, Datei verschieben etc.
	  		$ext = explode(".", basename($_FILES[self::request_file]['name']));
	  		$new_filename = $tools->createGUID().'.'.$ext[count($ext)-1];
	  		if (move_uploaded_file($_FILES[self::request_file]['tmp_name'], $download_path.$new_filename)) {
	  			// Datei erfolgreich in das Download Verzeichnis verschoben 
	  			if (($md5 = md5_file($download_path.$new_filename)) == false) {
	  				// Fehler beim Erstellen der Pruefsumme
	  				$message .= sprintf(sl_msg_md5_create_error, $new_filename);
	  			}
	  			// Anzahl der bereits vorhanden Datensaetze ermitteln
	  			$count_str = sprintf('COUNT(%s)', dbShortLink::field_id);
			  	$SQL = sprintf("SELECT %s FROM %s", $count_str, $dbShortLink->getTableName());
			  	if (!$dbShortLink->sqlExec($SQL, $result)) {
			  		$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbShortLink->getError()));
			  		return false;
			  	}
			  	(sizeof($result) == 0) ? $count = 0 : $count = $result[0][$count_str];
			  	// Zaehler erhoehen
			  	$param = base_convert($count+1, dbShortLink::base_from, dbShortLink::base_to);	  	
	  			// Download Link
  				if (!empty($name)) {
  					$download_link = sprintf('%s?%s=%s', $linkFile, request_download_name, $name);
  				}
  				else {
  					$download_link = sprintf('%s?%s=%s', $linkFile, request_download, $param);
  				}
			  	// Datenbank aktualisieren
	  			$data = array();
	  			$data[dbShortLink::field_type] = dbShortLink::type_download;
	  			$data[dbShortLink::field_name] = $name;
					$data[dbShortLink::field_param] = $param;
					$data[dbShortLink::field_url_short] = $download_link;
					$data[dbShortLink::field_dl_path] = $download_path;
					$data[dbShortLink::field_dl_size] = $_FILES[self::request_file]['size'];
					$data[dbShortLink::field_dl_fn_origin] = $_FILES[self::request_file]['name'];
					$data[dbShortLink::field_dl_fn_target] = $new_filename;
					$data[dbShortLink::field_dl_checksum] = $md5;
					$data[dbShortLink::field_dl_unlink] = $dl_unlink;
					$data[dbShortLink::field_created_when] = date('Y-m-d H:i:s');
					$data[dbShortLink::field_created_by] = $tools->getDisplayName();
					if ($dt === false) {
			  		$data[dbShortLink::field_valid_until] = '0000-00-00 00:00:00';	
			  	}
			  	else {
			  		$data[dbShortLink::field_valid_until] = date('Y-m-d H:i:s', mktime(23, 59, 59, date('m', $dt), date('d', $dt), date('Y', $dt)));
			  	}
			  	$data[dbShortLink::field_use_once] = $use_once;
			  	$data[dbShortLink::field_status] = dbShortLink::status_active;
			  	$data[dbShortLink::field_update_by] = $tools->getDisplayName();
			  	$data[dbShortLink::field_update_when] = date('Y-m-d H:i:s');
			  	$id = -1;
			  	if (!$dbShortLink->sqlInsertRecord($data, $id)) {
			  		$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbShortLink->getError()));
			  		return false;	  			
			  	}
	  			$message .= sprintf(sl_msg_dl_db_insert, $_FILES[self::request_file]['name']);
	  		} // move_uploaded_file
  			else {
  				// Fehler beim Verschieben der Datei
  				$this->setMessage(sprintf(sl_msg_upload_error_move_file, $_FILES[self::request_file]['name'], $download_path.$new_filename));
  				return $this->createDownloadLink();
  			}	
  		} // $new_upload
  			
  		// Ausgabe vorbereiten
  		$items = '';
  		$row = '<tr><td class="sl_label">%s</td><td colspan="2" class="%s">%s</td></tr>';
  		$items .= sprintf($row, sl_label_id, 'sl_id', sprintf('#%05d', $id));
  		// Download Datei
  		$items .= sprintf($row, sl_label_dl_file_name, 'sl_dl_file_name', $data[dbShortLink::field_dl_fn_origin]);
  		$row_3 = '<tr><td class="sl_label">%s</td><td class="%s">%s</td><td class="%s">%s</td></tr>';
  		// ShortLink
  		$items .= sprintf($row_3, 
  											sl_label_download_link,
  											'sl_url_short',
  											sprintf('<input type="text" id="sl_url_short" class="sl_url_short" name="%s" value="%s" readonly="readonly" />',
  															dbShortLink::field_url_short,
  															$data[dbShortLink::field_url_short]),
  											'sl_explain',
  											sprintf('<input type="button" value="%s" onclick="javascript:TextKopieren(\'%s\');" />',
  															sl_btn_copy, 'sl_url_short'));
  		// Bezeichner
  		(empty($name)) ? $name = sprintf('<i>%s</i>', sl_text_not_established) : $name = sprintf('<b>%s</b>', $name);
  		$items .= sprintf($row, sl_label_name,	'sl_name', $name);
  		// Einweg Link
  		($use_once == 1) ? $checked = ' checked="checked"' : $checked = '';
  		$items .= sprintf($row, 
  											sl_label_use_once,
  											'sl_use_once',
  											sprintf('<input type="checkbox" name="%s" value="1"%s disabled="disabled" />',
  															dbShortLink::field_use_once,
  															$checked));
  		// Gueltig bis
  		(empty($valid_until)) ? $valid_until = sprintf('<i>%s</i>', sl_text_not_established) : $valid_until = sprintf('<b>%s</b>', $valid_until);
  		$items .= sprintf($row, sl_label_valid_until,	'sl_valid_until', $valid_until);
  		// Automatisch loeschen
  		($dl_unlink == 1) ? $checked = ' checked="checked"' : $checked = '';
  		$items .= sprintf($row, 
  											sl_label_unlink_auto,
  											'sl_dl_unlink',
  											sprintf('<input type="checkbox" name="%s" value="1"%s disabled="disabled" />',
  															dbShortLink::field_dl_unlink,
  															$checked));  		
  		// AUSGABE
  		$parser = new tParser();
  		$parseArray = array(
  			'header'					=> sl_header_download,
  			'intro'						=> sprintf('<div class="intro">%s</div>', $message),
  			'form_name'				=> 'dl_check',
  			'form_action'			=> $this->page_link,
  			'action_name'			=> self::request_action,
  			'action_value'		=> self::action_download_edit,
  			'id_name'					=> dbShortLink::field_id,
  			'id_value'				=> $id,
  			'items'						=> $items,
  			'btn_new'					=> sl_btn_dl_new,
  			'new_location'		=> sprintf('%s&%s=%s', $this->page_link, self::request_action, self::action_download),
  			'btn_edit'				=> sl_btn_dl_change,
  			'btn_mail'				=> sl_btn_mail,
  			'mail_location'		=> sprintf(	'%s&%s=%s&%s=%s&%s=%s',
	  																	$this->page_link,
	  																	self::request_action,
	  																	self::action_mail,
	  																	self::request_by,
	  																	self::action_download,
	  																	dbShortLink::field_id,
	  																	$id
	  																	),
  			'btn_abort'				=> sl_btn_abort,
  			'abort_location'	=> $this->page_link
  		);
  		foreach ($parseArray as $key => $value) {
  			$parser->add($key, $value);
  		}
  		$parser->parseTemplateFile($this->template_path.'backend.shortlink.check.htt');
  		return $parser->getHTML();
  	}
  	else {
  		$this->setMessage(sl_msg_upload_no_file);
  		return $this->createDownloadLink();
  	}
  } // checkDownloadLink()
  
  public function createMail() {
  	if ((isset($_REQUEST[dbShortLink::field_id])) && (!empty($_REQUEST[dbShortLink::field_id]))) {
  		// Datensatz auslesen
  		$id = $_REQUEST[dbShortLink::field_id];
  		$dbShortLink = new dbShortLink();
  		$where = array();
  		$where[dbShortLink::field_id] = $id;
  		$link = array();
  		if (!$dbShortLink->sqlSelectRecord($where, $link)) {
  			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbShortLink->getError()));
  			return false;
  		}
  		if (sizeof($link) < 1) {
  			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, sprintf(sl_error_sl_id, $id)));
  			return false;
  		}
  		$link = $link[0];
  	}
  	else {
  		// kein ShortLink angegeben
  		$this->setMessage(sl_msg_mail_no_id);
  		$id = -1;
  	}
  	$tools = new rhTools();
  	$config = new dbShortLinkConfig();
  	$items = '';
  	$row_2 = '<tr><td class="sl_label">%s</td><td class="%s" colspan="3">%s</td></tr>';
  	$row_3 = '<tr><td class="sl_label">%s</td><td class="%s" colspan="2">%s</td><td class="%s">%s</td></tr>';
  	((isset($_REQUEST[xMailer::mail_from])) && (!empty($_REQUEST[xMailer::mail_from]))) ? 
  		$mail_from = $_REQUEST[xMailer::mail_from] : $mail_from = sprintf('%s <%s>', $tools->getDisplayName(),	$tools->getUserEMail());
  	$items .= sprintf($row_2, 
  										sl_label_mail_from,
  										"sl_mail_from",
  										sprintf('<input type="text" name="%s" value="%s" />',
  														xMailer::mail_from,
  														$mail_from));
		// mail_to EINGABE  														
  	((isset($_REQUEST[xMailer::mail_new_address])) && (!empty($_REQUEST[xMailer::mail_new_address]))) ?	$mail_new_address = $_REQUEST[xMailer::mail_new_address] : $mail_new_address = '';
  	
  	$items .= sprintf($row_3, 
  										sl_label_mail_new_address,
  										"sl_mail_new_address",
  										sprintf('<input type="text" name="%s" value="%s" />',
  														xMailer::mail_new_address,
  														$mail_new_address),
  										"sl_explain",
  										sl_desc_mail_new_address);

  	// mail_to AUSWAHLLISTE  
  	((isset($_REQUEST[xMailer::mail_to])) && (!empty($_REQUEST[xMailer::mail_to]))) ? $mail_to = $_REQUEST[xMailer::mail_to] : $mail_to = array();										
  	$dbShortLinkAddresses = new dbShortLinkAddresses();
  	$where = array();
  	$where[dbShortLinkAddresses::field_status] = dbShortLinkAddresses::status_active;
  	$addresses = array();
  	$order_by = array(dbShortLinkAddresses::field_name, dbShortLinkAddresses::field_address);
  	if (!$dbShortLinkAddresses->sqlSelectRecordOrderBy($where, $addresses, $order_by)) {
  		$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbShortLinkAddresses->getError()));
  		return false;
  	}
  	$address_array = array();
  	if (sizeof($addresses) > 0) {
  		// Eintraege vorhanden
  		foreach ($addresses as $address) {
  			if (!empty($address[dbShortLinkAddresses::field_name])) {
  				$address_array[] = sprintf('%s <%s>', $address[dbShortLinkAddresses::field_name], $address[dbShortLinkAddresses::field_address]);
  			}
  			else {
  				$address_array[] = $address[dbShortLinkAddresses::field_address];
  			}
  		}
  	}

  	// Zusaetzliche Interfaces verwenden?
  	$config = new dbShortLinkConfig();
  	$cfgInterfaces = $config->getValue(dbShortLinkConfig::cfgUseInterface);
  	foreach ($cfgInterfaces as $interface) {
  		if (!empty($interface)) {
	  		if (file_exists(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.address.'.$interface.'.php')) {
	  			// Interface gefunden, einbinden...
	  			require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.address.'.$interface.'.php');
	  			// und initialisieren
	  			$class = $interface.'Interface';
	  			$interface_class = new $class();
	  			$interface_addresses = array();
	  			if ($interface_class->getEMailAddresses($interface_addresses)) {
	  				foreach ($interface_addresses as $i_addresses) {
	  					$address_array[] = $i_addresses;
	  				}
	  			}
	  			else {
	  				// Interface ERROR
	  				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $interface_class->getError()));
	  				return false;
	  			}
	  		}
	  		else { 
	  			// Interface existiert nicht
	  			$this->setMessage(sprintf(sl_msg_interface_not_exists, $interface));
	  		}
  		}
  	}
 	  // sort array
  	sort($address_array);
  	((empty($address_array)) || (empty($mail_to))) ? $selected = 'selected="selected"' : $selected = '';
  	$option = sprintf('<option value=""%s>%s</option>', $selected, sl_text_select);
  	foreach ($address_array as $address) { 
  		(in_array($address, $mail_to)) ? $selected = ' selected="selected"' : $selected = '';
  		$item = str_replace('<', '&lt;', $address);
  		$item = str_replace('>', '&gt;', $item);
  		$option .= sprintf('<option value="%s"%s>%s</option>', $address, $selected, $item);
  	}
  	$items .= sprintf($row_3,
  										sl_label_mail_to_select,
  										"sl_mail_to_select",
  										sprintf('<select name="%s[]" size="3" multiple="multiple">%s</select>', xMailer::mail_to, $option),
  										"sl_explain",
  										sl_desc_mail_select_address);
  	
		($link[dbShortLink::field_type] == dbShortLink::type_shortlink) ? $sub_def = sl_label_shortlink : $sub_def = sl_label_download_link;  													
		((isset($_REQUEST[xMailer::mail_subject])) && (!empty($_REQUEST[xMailer::mail_subject]))) ?	$mail_subject = $_REQUEST[xMailer::mail_subject] : $mail_subject = $sub_def;
  	$items .= sprintf($row_2, 
  										sl_label_mail_subject,
  										"sl_mail_subject",
  										sprintf('<input type="text" name="%s" value="%s" />',
  														xMailer::mail_subject,
  														$mail_subject));
		if ($link[dbShortLink::field_type] == dbShortLink::type_shortlink) {
			// es handelt sich um einen ShortLink
			$text_def = sprintf(sl_text_mail_text_sl, 
													$link[dbShortLink::field_url_short],
													$link[dbShortLink::field_url_short],
													$tools->getDisplayName());   														  
		}
		else {
			// es handelt sich um einen DownloadLink
			$text_def = sprintf(sl_text_mail_text_dl,
													$link[dbShortLink::field_dl_fn_origin],
													$link[dbShortLink::field_url_short],
													$link[dbShortLink::field_url_short],
													$tools->bytes2Str($link[dbShortLink::field_dl_size]),
													$link[dbShortLink::field_dl_checksum],
													$tools->getDisplayName());
		}
		((isset($_REQUEST[xMailer::mail_text])) && (!empty($_REQUEST[xMailer::mail_text]))) ?	$mail_text = $_REQUEST[xMailer::mail_text] : $mail_text = $text_def;
  	// WYSIWYG Editor einbinden
		if (!file_exists(WB_PATH.'/modules/fckeditor/include.php')) {
			// kein Editor definiert oder nicht gefunden...
			$editor = sprintf('<textarea name="%s">%s</textarea>', xMailer::mail_text, $mail_text);
		}
		else {
			// Editor einbinden und als Variable uebergeben
			require_once(WB_PATH.'/modules/fckeditor/include.php');
			ob_start();
				show_wysiwyg_editor(xMailer::mail_text, xMailer::mail_text, $mail_text, '98%', 300);
				$editor = ob_get_contents();
			ob_clean();
		}													
	  $items .= sprintf($row_2, sl_label_mail_text,	"sl_mail_text",	$editor);
	  if (isset($_REQUEST[xMailer::mail_is_html])) {
	  	($_REQUEST[xMailer::mail_is_html] == 1) ? $checked = ' checked="checked"' : $checked = '';  
	  }
	  else {
	  	$sendHTML = $config->getValue(dbShortLinkConfig::cfgMailSendHTML);
	  	($sendHTML) ? $checked = ' checked="checked"' : $checked = ''; 
	  }
		$items .= sprintf($row_2, '', "sl_mail_is_html", sprintf('<input type="checkbox" name="%s" value="1"%s />&nbsp;%s',
																				xMailer::mail_is_html, $checked, sl_label_mail_send_html));
		((isset($_REQUEST[self::request_by])) && (!empty($_REQUEST[self::request_by]))) ? $by = $_REQUEST[self::request_by]	: $by = '';																																							
		// Mitteilungen anzeigen
		if ($this->isMessage()) {
			$intro = sprintf('<div class="message">%s</div>', $this->getMessage());
		}
		else {
			$intro = sprintf('<div class="intro">%s</div>', sl_intro_mail);
		}			
  	$parser = new tParser();
  	$parseArray = array(
  		'header'					=> sl_header_mail,
  		'intro'						=> $intro,
  		'form_name'				=> 'sl_mail',	
  		'form_action'			=> $this->page_link,
  		'by_name'					=> self::request_by,
  		'by_value'				=> $by,
  		'action_name'			=> self::request_action,
  		'action_value'		=> self::action_mail_check,
  		'sl_name'					=> dbShortLink::field_id,
  		'sl_value'				=> $id,
  		'items'						=> $items,
  		'btn_ok'					=> sl_btn_mail,
  		'btn_abort'				=> sl_btn_abort,
  		'abort_location'	=> $this->page_link
  	);
  	foreach ($parseArray as $key => $value) {
  		$parser->add($key, $value);
  	}
  	$parser->parseTemplateFile($this->template_path.'backend.mail.htt');
  	return $parser->getHTML();
  } // createMail()
  
  public function checkMail() {
  	$tools = new rhTools();
  	$from = '';
  	$to_array = array();
  	$checked = true;
  	$message = '';
  	
  	((isset($_REQUEST[xMailer::mail_from])) && (!empty($_REQUEST[xMailer::mail_from]))) ? $mail_from = trim($_REQUEST[xMailer::mail_from]) : $mail_from = '';
  	// Absender
  	if (!empty($mail_from)) {
  		if (strpos($mail_from, "<") > 0) {
  			$mail = strtolower(trim(substr($mail_from, strpos($mail_from, "<")+1, -1)));
  			$name = trim(substr($mail_from, 0, strpos($mail_from, "<")-1));
  		}
  		else {
  			$mail = strtolower(trim($mail_from));
  			$name = '';
  		}
  		if (!$tools->validateEMail($mail)) {
  			$checked = false;
  			$message .= sprintf(sl_msg_invalid_email, $mail);				
  		}
  		else {
  			// $from Adresse 
	  		(!empty($name)) ? $from = sprintf('%s <%s>', $name, $mail) : $from = $mail;
  		}  		
  	}
  	else {
  		// Absender fehlt
  		$message .= sl_msg_mail_from_empty;
			$checked = false;
  	}
  	
  	((isset($_REQUEST[xMailer::mail_new_address])) && (!empty($_REQUEST[xMailer::mail_new_address]))) ? $new_address = $_REQUEST[xMailer::mail_new_address] : $new_address = '';
  	// neue Adresse?
  	if (!empty($new_address)) {
  		if (strpos($new_address, "<") > 0) {
  			$mail = strtolower(trim(substr($new_address, strpos($new_address, "<")+1, -1)));
  			$name = trim(substr($new_address, 0, strpos($new_address, "<")-1));
  		}
  		else {
  			$mail = strtolower(trim($new_address));
  			$name = '';
  		}
  		if (!$tools->validateEMail($mail)) {
  			$checked = false; 
  			$message .= sprintf(sl_msg_invalid_email, $mail);				
  		}
  		else {
  			// pruefen, ob die Adresse bereits in der Datenbank existiert
  			$dbShortLinkAddresses = new dbShortLinkAddresses();
	  		$where = array();
	  		$where[dbShortLinkAddresses::field_address] = $mail;
	  		$where[dbShortLinkAddresses::field_status] = dbShortLinkAddresses::status_active;
	  		$result = array();
	  		if (!$dbShortLinkAddresses->sqlSelectRecord($where, $result)) {
	  			$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbShortLinkAddresses->getError()));
	  			return false;
	  		}
	  		if (sizeof($result) > 0) {
	  			// Datensatz existiert bereits, nur Mitteilung
	  			$message .= sprintf(sl_msg_mail_address_exists, $mail);
	  		}
	  		else {
	  			// Datensatz hinzufuegen
	  			$data = array();
	  			$data[dbShortLinkAddresses::field_address] = $mail;
	  			$data[dbShortLinkAddresses::field_name] = $name;
	  			$data[dbShortLinkAddresses::field_status] = dbShortLinkAddresses::status_active;
	  			$data[dbShortLinkAddresses::field_update_by] = $tools->getDisplayName();
	  			$data[dbShortLinkAddresses::field_update_when] = date('Y-m-d H:i:s');
	  			$aid = -1;
	  			if (!$dbShortLinkAddresses->sqlInsertRecord($data, $aid)) {
	  				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbShortLinkAddresses->getError()));
	  				return false;
	  			}
	  			$message .= sprintf(sl_msg_mail_address_added, $mail, $aid);
	  			// $_REQUEST zuruecksetzen, wird nicht mehr benoetigt
	  			unset($_REQUEST[xMailer::mail_new_address]);
	  		}
	  		// Adresse dem $to_array hinzufuegen
	  		(!empty($name)) ? $to_array[] = sprintf('%s <%s>', $name, $mail) : $to_array[] = $mail; 
	  	}
  	}
  	
  	((isset($_REQUEST[xMailer::mail_to])) && (!empty($_REQUEST[xMailer::mail_to]))) ? $mail_to = $_REQUEST[xMailer::mail_to] : $mail_to = array();
  	// Adresse(n) aus Liste ausgewaehlt?
  	foreach ($mail_to as $address) {
  		if (!empty($address)) {
  			// Adresse pruefen
  			if (strpos($address, "<") > 0) {
  				$mail = strtolower(trim(substr($address, strpos($address, "<")+1, -1)));
  				$name = trim(substr($address, 0, strpos($address, "<")-1));
  			}
  			else {
  				$mail = strtolower(trim($address));
  				$name = '';
  			}
  			if (!$tools->validateEMail($mail)) {
  				$checked = false; 
  				$message .= sprintf(sl_msg_invalid_email, $mail);				
  			}
  			else {
  				(!empty($name)) ? $to_array[] = sprintf('%s <%s>', $name, $mail) : $to_array[] = $mail;
  			}
  		}
  	}
  	
  	((isset($_REQUEST[xMailer::mail_subject])) && (!empty($_REQUEST[xMailer::mail_subject]))) ? $mail_subject = trim($_REQUEST[xMailer::mail_subject]) : $mail_subject = '';
  	((isset($_REQUEST[xMailer::mail_text])) && (!empty($_REQUEST[xMailer::mail_text]))) ? $mail_text = trim($_REQUEST[xMailer::mail_text]) : $mail_text = '';
  	((isset($_REQUEST[xMailer::mail_is_html])) && ($_REQUEST[xMailer::mail_is_html] == 1)) ? $mail_is_html = true : $mail_is_html = false;
  	((isset($_REQUEST[self::request_by])) && (!empty($_REQUEST[self::request_by]))) ? $by = $_REQUEST[self::request_by] : $by = '';
  	
  	// $mail_subject
  	if (empty($mail_subject)) {
  		$message .= sl_msg_mail_subject_empty;
  		$checked = false;
  	}
  	// $mail_text
  	if (empty($mail_text)) {
  		$message .= sl_msg_mail_text_empty;
  		$checked = false;
  	}
  	if (!$checked) {
  		// Fehler, E-MAIL Dialog wieder aufrufen
  		$this->setMessage($message);
  		return $this->createMail();
  	}
  	// E-Mail versenden
  	$to_array_mail = array();
  	foreach ($to_array as $address) {
  		if (strpos($address, "<") > 0) {
  			$to_array_mail[] = substr($address, strpos($address, "<")+1, -1);
  		}
  		else {
  			$to_array_mail[] = $address;
  		}
  	}	
  	$xmailer = new xMailer();
  	if ($xmailer->mail($from, $to_array, $mail_subject, $mail_text, $mail_is_html)) {
  		$message .= sprintf(sl_msg_mail_success, implode(", ", $to_array_mail));
  		$this->setMessage($message);
  		if ($by == self::action_shortlink) {
  			return $this->createShortLink();
  		}
  		else {
  			return $this->createDownloadLink();
  		}
  	}
  	else {
  		$message .= sprintf(sl_msg_mail_send_fail, implode(", ", $to_array_mail), $xmailer->getError());
  		$this->setMessage($message);
  		return $this->createMail();
  	}
  } // checkMail()
  
} // class backendShortLink


?>