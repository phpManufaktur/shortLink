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
  
  $Id: class.mail.php 31 2010-04-07 04:11:46Z ralf $
  
**/

// prevent this file from being accesses directly
if(defined('WB_PATH') == false) {
  exit("Cannot access this file directly");
}

class xMailer extends PHPMailer {
	
	const mail_from 				= 'mail_from';
	const mail_to						= 'mail_to';
	const mail_new_address	= 'mail_new_address'; // neue E-Mail Adresse
	const mail_subject			= 'mail_subject';
	const mail_text					= 'mail_text';
	const mail_is_html			= 'mail_is_html';
	
	public function __construct() {
    $tools = new rhTools();
    $tools->getUserEMail();
    $this->IsMail(); // use php Mailer
		if(defined("LANGUAGE")) {
      $this->SetLanguage(strtolower(LANGUAGE), WB_PATH.'/include/phpmailer/language/'); 
    }
    $this->CharSet = 'utf-8';
    $this->FromName = $tools->getDisplayName();
    $this->From = $tools->getUserEMail();
    $this->IsHTML(true);
    $this->WordWrap = 80;
    $this->Timeout = 30;
	} // __construct()
  		
  /**
    * Get Error from $this->ErrorInfo;
    * 
    * @return STR $this->ErrorInfo
    */
  public function getError() {
    return $this->ErrorInfo;
  } // getError()

  /**
    * Check if $this->ErrorInfo is empty
    * 
    * @return BOOL
    */
  public function isError() {
    return (bool) !empty($this->ErrorInfo);
  } // isError
  
  /**
   * Send Mail, using WB phpMailer
   * 
   * @param $from STR - E-Mail Sender name <email@domain.tld>
   * @param $to_array ARRAY - Recipient E-Mail Array
   * @param $subject STR
   * @param $message STR
   * @param $isHTML BOOL - Send Mail in HTML Format?
   * 
   * @return BOOL
   */
  public function mail($from, $to_array, $subject, $message, $isHTML=false) {
  	$tools = new rhTools();
  	// FROM
  	if (!empty($from)) {
  		if (strpos($from, "<") > 0) {
  			$mail = strtolower(trim(substr($from, strpos($from, "<")+1, -1)));
  			$name = trim(substr($from, 0, strpos($from, "<")-1));
  			$this->FromName = $name;
  			$this->From = $mail; 
  			$this->AddReplyTo($mail, $name);  			
  		}
  		else {
  			$mail = strtolower(trim($from));
  			$this->From = $mail;
  			$this->AddReplyTo($mail);
  		}
  	}
  	// TO
  	foreach ($to_array as $to) {
	  	if (!empty($to)) {
	  		if (strpos($to, "<") > 0) {
	  			$mail = strtolower(trim(substr($to, strpos($to, "<")+1, -1)));
	  			$name = trim(substr($to, 0, strpos($to, "<")-1));
	  			$this->AddAddress($mail, $name);
	  		}
	  		else {
	  			$mail = strtolower(trim($to));
	  			$this->AddAddress($mail);
	  		}
	  	}
  	}
  	// SUBJECT
  	$this->Subject = $subject;
  	// HTML?
  	$this->IsHTML($isHTML);
  	// MESSAGE
  	if ($this->ContentType == 'text/html') {
    	// HTML Format
    	$this->Body = $message;
    	$this->AltBody = utf8_encode($tools->htmlEntities2char(strip_tags($message)));
    }
    else {
    	// TEXT Format
    	$this->Body = utf8_encode($tools->htmlEntities2char(strip_tags($message)));
    }
    // SEND MAIL
  	if (!$this->Send()) {
      return false;
    } 
    else {
      return true;
    }
  } // mail()
  
} // class xMailer



?>