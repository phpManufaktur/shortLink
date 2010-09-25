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
  
  $Id: install.php 21 2009-07-05 05:49:57Z ralf $
  
**/

require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/initialize.php');

$dbShortLink = new dbShortLink(true);
if ($dbShortLink->isError()) {
	$admin->print_error(sprintf('[Installation] %s', $dbShortLink->getError()));
}

$dbShortLinkConfig = new dbShortLinkConfig(true);
if ($dbShortLinkConfig->isError()) {
	$admin->print_error(sprintf('[Installation] %s', $dbShortLinkConfig->getError()));
}

$dbShortLinkAddresses = new dbShortLinkAddresses(true);
if ($dbShortLinkAddresses->isError()) {
	$admin->print_error(sprintf('[Installation] %s', $dbShortLinkAddresses->getError()));
}

?>