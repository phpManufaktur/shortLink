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
  
  $Id: class.address.interface.php 25 2009-07-07 02:00:37Z ralf $
  
**/

/**
	This INTERFACE enable ShortLink to access to external address databases, i.e. PrintShop,
	dbKontakte, Addresses (Aldus), Addressbook (Hubert Winkel) and others. 
 
	To create an INTERFACE, i.e. "TEST" write a new file, named:

	--> class.address.TEST.php

	and store it within the ShortLink module directory.

	You may use an additional language file for your interface, use the 

	--> /languages/interface/TEST/<LANGUAGE>.php

	directory to place them.

	Please look at class.address.printshop.php as example for an interface to the 
  dbKundenAdressen of the module PrintShop.

  Create your interface class:

	class TESTInterface extends addressInterface {

    public function __construct() {
      $this->setInterfaceName('TEST Interface');
    } // __construct()

		public function getEMailAddresses(&$address_array = array()) {
      [..]
      Access to your database and return the address_array for 
      ShortLink. Each entry in the manner:
      --> DISPLAY NAME <E-MAIL>

			On error, use $this->setError('ERROR MESSAGE') and return FALSE
      [..]
    } // getEMailAddresses()

  } // class TESTInterface
  
	Finally, add the name of your interface in the CONFIGURATION Tab of ShortLink:

  --> cfgUseInterface = TEST

  that's all!
  
**/

abstract class addressInterface {

	private $error = '';
	private $interface_name = '- UNDEFINED -';
	private $interface_version = '0.0';
			
	/**
	 * ABSTRACT __construct()
	 * Initialize the interface and set interface_name
	 */
	abstract function __construct();
	
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
   * Set the name of the interface
   */
  public function setInterfaceName($interface) {
  	$this->interface_name = $interface;
  } // setInterfaceName()
  
  /**
   * Return the name of the interface
   * 
   * @return STR interface
   */
  public function getInterfaceName() {
  	return $this->interface_name;
  } // getInterfaceName()
  
  /**
   * Set the version of the interface
   */
  public function setInterfaceVersion($version) {
  	$this->interface_version = $version;
  } // setInterfaceVersion()
	
  /**
   * Get the version of the interface
   */
  public function getInterfaceVersion() {
  	return $this->interface_version;
  } // getInterfaceVersion()
  
	/**
	 * ABSTRACT
	 * This function should return all E-Mail entries of the
	 * specified address database as array(), each entry as
	 * DISPLAY_NAME <E-MAIL> or only the E_MAIL address.
	 * 
	 * On error the function should set an ERROR_MESSAGE by
	 * $this->setError(ERROR_MESSAGE) and return FALSE
	 * 
	 * @param REFERENCE &$address_array ARRAY
	 * @return BOOL
	 */
	abstract public function getEMailAddresses(&$address_array = array());
	
} // class addressInterface

?>