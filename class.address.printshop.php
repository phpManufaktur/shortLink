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
  
  $Id: class.address.printshop.php 25 2009-07-07 02:00:37Z ralf $
  
**/

// require the interface class
require_once(WB_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.address.interface.php');

// load the language file for the interface
if(!file_exists(WB_PATH .'/modules/'.basename(dirname(__FILE__)).'/languages/interface/printshop/' .LANGUAGE .'.php')) {
	require_once(WB_PATH .'/modules/'.basename(dirname(__FILE__)).'/languages/interface/printshop/DE.php'); 
}
else {
	require_once(WB_PATH .'/modules/'.basename(dirname(__FILE__)).'/languages/interface/printshop/' .LANGUAGE .'.php'); 
}

/**
 * Interface to dbKundenAdressen of module PrintShop
 *
 */
class printshopInterface extends addressInterface {

	/**
	 * CONSTRUCTOR
	 * in this case only needed to define the interface name...
	 */
	public function __construct() {
		$this->setInterfaceName('PrintShop Interface');
		$this->setInterfaceVersion(0.1);
	} // __construct()
	
	/**
	 * Overwrite the ABSTRACT function getEMailAddresses()
	 * 
	 * @param REFERENCE ARRAY with the addresses
	 * @return BOOL
	 */
	public function getEMailAddresses(&$address_array = array()) {
		// check if the module/class exists...
		if (file_exists(WB_PATH.'/modules/printshop/class.kunden.php'))	{
			// load required modules for the interface
			require_once(WB_PATH .'/modules/printshop/languages/DE.php');
			require_once(WB_PATH .'/modules/printshop/class.kunden.php');
			// initialize dbKundenAdressen
			$dbKundenAdressen = new dbKundenAdressen();
			// get all active addresses
			$where = array();
			$where[dbKundenAdressen::field_status] = dbKundenAdressen::status_aktiv;
			$adressen = array();
			$order_by = array(dbKundenAdressen::field_name_1);
			if (!$dbKundenAdressen->sqlSelectRecordOrderBy($where, $adressen, $order_by)) {
				// ERROR while accessing database, set ERROR Message and RETURN FALSE
				$this->setError(sprintf('[%s - %s] %s', __METHOD__, __LINE__, $dbKundenAdressen->getError()));
				return false;
			}
			// reset $address_array
			$address_array = array();
			// step through result
			foreach ($adressen as $adresse) {
				if (!empty($adresse[dbKundenAdressen::field_email_1])) {
					if (!empty($adresse[dbKundenAdressen::field_name_2])) {
						// add address in form NAME, FIRSTNAME <E-MAIL>
						$address_array[] = sprintf(	'%s, %s <%s>', 
																				utf8_decode($adresse[dbKundenAdressen::field_name_1]),
																				utf8_decode($adresse[dbKundenAdressen::field_name_2]),
																				$adresse[dbKundenAdressen::field_email_1]);
					}
					else {
						// add address in form NAME <E-MAIL>
						$address_array[] = sprintf( '%s <%s>',
																				utf8_decode($adresse[dbKundenAdressen::field_name_1]),
																				$adresse[dbKundenAdressen::field_email_1]);
					}
				}
			} // foreach
			return true;
		}
		else {
			// PrintShop module does not exist, set error and exit...
			$this->setError(psi_error_missing_printshop);
			return false;
		}
	} // getEMailAddresses()
	
} // class addressPrintShop

?>