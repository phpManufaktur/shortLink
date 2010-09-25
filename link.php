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
  
  $Id: link.php 32 2010-04-07 04:14:15Z ralf $
  
**/

// Include config file
$config_path = dirname(__FILE__).'/config.php';
if (!file_exists($config_path)) {
	// Vermutung: link.php befindet sich im /modules/shortlink Verzeichnis
	$config_path = '../../config.php';
	if (!file_exists($config_path)) {
		die('Missing Configuration File...'); 
	}
}
require_once($config_path);

$redirect = true;
// ShortLink
if (file_exists(WB_PATH.'/modules/shortlink/class.shortlink.php')) {
  require_once(WB_PATH.'/modules/shortlink/initialize.php');
  $redirect = shortLink();
}
if ($redirect) {
	// index.php aufrufen
	header('Location: index.php');
}

?>