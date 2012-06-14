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
  
  $Id: tool.php 24 2009-07-06 05:06:28Z ralf $
  
**/

require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/initialize.php');
require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.backend.php');
require_once(WB_PATH."/framework/initialize.php");
require_once(WB_PATH."/include/phpmailer/class.phpmailer.php");
require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.mail.php');

$backendShortLink = new backendShortLink();
$backendShortLink->action();

?>