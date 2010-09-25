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
  
  $Id: initialize.php 4 2009-06-24 13:04:15Z ralf $
  
**/

// prevent this file from being accesses directly
if(defined('WB_PATH') == false) {
	exit("Cannot access this file directly"); 
}

// DIE if php Version is wrong
if (floor(phpversion()) < 5) die("Sorry, but this module needs at minimum PHP 5. Program execution stopped.");

$debug = true;
if ($debug) {
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
}
else {
	ini_set('display_errors', 0);
	error_reporting(E_ERROR);
}

// WB Konfigurationsdatei einbinden
require_once(WB_PATH.'/config.php');

// Sprachdateien einbinden
if(!file_exists(WB_PATH .'/modules/'.basename(dirname(__FILE__)).'/languages/' .LANGUAGE .'.php')) {
	require_once(WB_PATH .'/modules/'.basename(dirname(__FILE__)).'/languages/DE.php'); 
}
else {
	require_once(WB_PATH .'/modules/'.basename(dirname(__FILE__)).'/languages/' .LANGUAGE .'.php'); 
}


// benoetigt dbConnect
if (!class_exists('dbConnect')) {
	if (file_exists(WB_PATH.'/modules/dbconnect/include.php')) {
		require_once(WB_PATH.'/modules/dbconnect/include.php');
	}
	else {
		die(sprintf(sl_template_error, sl_header_prompt_error, sprintf(sl_error_missing_addon, 'dbConnect')));	
	} 
}
// check dbConnect version
$version = dbConnect::getVersion();
if ($version < 0.33) {
  die(sprintf(sl_template_error, sl_header_prompt_error, sprintf(sl_error_addon_version, 'dbConnect', 0.33, $version)));
} 
		
// benoetigt rhTools
if (!class_exists('rhTools')) {
	if (file_exists(WB_PATH.'/modules/rhtools/include.php')) {
		require_once(WB_PATH.'/modules/rhtools/include.php'); 
	}
	else {
		die(sprintf(sl_template_error, sl_header_prompt_error, sprintf(sl_error_missing_addon, 'rhTools')));	
	} 
}
// check rhTools version
$version = rhTools::getVersion();
if ($version < 0.43) {
  die(sprintf(sl_template_error, sl_header_prompt_error, sprintf(sl_error_addon_version, 'rhTools', 0.43, $version)));
} 

require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.shortlink.php');

?>