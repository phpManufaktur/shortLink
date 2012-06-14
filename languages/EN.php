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
  
  $Id: EN.php 28 2009-07-13 04:05:12Z ralf $
  
**/

define('sl_btn_abort',									'Abort');
define('sl_btn_change',									'Change ShortLink');
define('sl_btn_copy',										'Copy');
define('sl_btn_dl_new',									'New DownloadLink');
define('sl_btn_dl_change',							'Change DownloadLink');
define('sl_btn_mail',										'Send E-Mail');
define('sl_btn_new',										'New ShortLink');
define('sl_btn_ok',											'Accept');
define('sl_btn_ready',									'Ready');

define('sl_desc_cfg_developer_mode',		'Allows the programmer to add configuration params.');
define('sl_desc_cfg_dl_unlink_auto',		'Default: Automaticly delete DownloadLink if it is no longer valid (1 = YES, 0 = NO).');
define('sl_desc_cfg_dl_use_once',				'Default: Exec DownloadLink only one time (1 = YES, 0 = NO).');
define('sl_desc_cfg_download_path',			'The path starting at <b>WB_PATH</b>, which will ShortLink use for download files.');
define('sl_desc_cfg_linkfile_use',			'Determine, if ShortLink will be called by the link-file <b>link.php</p> or a adapted <b>index.php</b> in the root of your domain..');
define('sl_desc_cfg_linkfile_name',			'You may rename the link-file <b>link.php</b> in the root of your domain.');
define('sl_desc_cfg_mail_send_html',		'Default: Send E-Mails in HTML format (1 = YES, 0 = NO).');
define('sl_desc_cfg_use_interface',			'Determine, which additional interfaces should be used by ShortLink.');
define('sl_desc_cfg_sl_use_once',				'Default: Exec ShortLink only one time (1 = YES, 0 = NO).');
define('sl_desc_cfg_switches',					'Special switches to change the behaviour of ShortLink (<i>see documentation</i>).');
define('sl_desc_mail_new_address',			'Type in a <b>new</b> E-Mail address, which is not in the selection list.<br />If you type in an E-Mail address and a name, please use the format<br /><b>NAME &lt;E-MAIL ADDRESS&gt;</b>');
define('sl_desc_mail_select_address',		'Select the E-Mail Address(es) you want to use.<br />In this list you will see E-Mail addresses you have already used or addresses to which <i>ShortLink</i> has access by interface.');
define('sl_desc_name',									'You may give link\'s, you are using regular, an identifier by which they are called.<br>Please use a single word without special chars.<br>The link will be:<br><b>%s=&lt;IDENTIFIER&gt;</b>.');
define('sl_desc_status',								'Change the state to lock or delete the link.');
define('sl_desc_unlink_auto',						'The file will be automaticly deleted, if the DownloadLink is no longer valid.');
define('sl_desc_use_once',							'The link may be executed only one time.');
define('sl_desc_valid_until',						'You may determine an expiration date for the link.');

define('sl_error_addon_version',     		'<p>Fatal error: <b>ShortLink</b> needs the Website Baker Addon <b>%s</b> at minimum with version <b>%01.2f</b> - you have installed the version <b>%01.2f</b>.</p><p>Please upgrade and try again.</p>');
define('sl_error_cfg_id',								'<p>Can\'t read the configuration entry with the<b>ID #%05d</b>!</p>');
define('sl_error_cfg_name',							'<p>For the identifier <b>%s</b> does not exists a configuration entry!</p>');
define('sl_error_missing_addon',    		'<p>Fatal error: <b>ShortLink</b> needs the Website Baker Addon <b>%s</b>, the programm execution is stopped!</p>');
define('sl_error_missing_id',						'<p>Missing the <b>ID</b> for the ShortLink you will edit!</p>');
define('sl_error_sl_id',								'<p>The ShortLink with the <b>ID #%05d</b> was not found!</p>');

define('sl_header_config',							'Settings');
define('sl_header_copy_btn',						'');
define('sl_header_count',								'Calls');
define('sl_header_description',					'Description');
define('sl_header_dl_edit',							'Edit <i>Download</i>Link');
define('sl_header_download',						'<i>Download</i>Link');
define('sl_header_id',									'ID');
define('sl_header_identifier',					'Identifier');
define('sl_header_label',								'Label');
define('sl_header_last_exec',						'Last call');
define('sl_header_mail',								'Send E-Mail');
define('sl_header_name',								'Identifier');
define('sl_header_param',								'Param');
define('sl_header_prompt_error',    		'[ShortLink] Error');
define('sl_header_shortlink',						'<i>Short</i>Link');
define('sl_header_shortlink_edit',			'Edit <i>Short</i>Link');
define('sl_header_sl_list',							'<i>Short</i>Link List');
define('sl_header_typ',									'Type');
define('sl_header_use_once',						'Single-Use');
define('sl_header_valid_until',					'Valid until');
define('sl_header_value',								'Value');
define('sl_header_update_when',					'Last change');

define('sl_intro_cfg_add_item',					'<p>Adding of entries to the configuration is only useful, if they are corresponding with the program.</p>');
define('sl_intro_cfg',									'<p>Change the settings of ShortLink.</p>');
define('sl_intro_downloadlink',					'<p>Upload the file you want to use and create a DownloadLink.</p>');
define('sl_intro_link_copy',						'<p>Copy the shortend Link to the clipboard.</p>');
define('sl_intro_link_edit',						'<p>With this dialog you may edit the link.</p>');
define('sl_intro_shortlink',						'<p>Insert the link, you want to shorten into the input field for the target URL and click to accept.</p>');
define('sl_intro_sl_list',							'<p>Select the link you want to edit.</p>');
define('sl_intro_mail',									'<p>Send the link by E-Mail...</p>');

define('sl_label_cfg_developer_mode',		'Developer Mode');
define('sl_label_cfg_dl_unlink_auto',		'Delete automaticly');
define('sl_label_cfg_dl_use_once',			'Single-Use DownloadLink');
define('sl_label_cfg_download_path',		'Download Path');
define('sl_label_cfg_linkfile_name',		'Linkfile filename');
define('sl_label_cfg_linkfile_use',			'Use Linkfile');
define('sl_label_cfg_mail_send_html',		'E-Mails in HTML format');
define('sl_label_cfg_sl_use_once',			'Single-Use ShortLink');
define('sl_label_cfg_switches',					'Special Switches');
define('sl_label_cfg_use_interface',		'Use Interface');
define('sl_label_checksum',							'Checksum');
define('sl_label_csv_export',						'Save settings as CSV-file in the /MEDIA directory');
define('sl_label_count',								'Calls total');
define('sl_label_created_by',						'Link created by');
define('sl_label_created_when',					'Link created at');
define('sl_label_dl_file_name',					'Download file');
define('sl_label_dl_filesize',					'Filesize');
define('sl_label_dl_fn_target',					'File saved as');
define('sl_label_download_link',				'DownloadLink');
define('sl_label_id',										'ID');
define('sl_label_last_exec',						'Last call');
define('sl_label_mail_from',						'From');
define('sl_label_mail_new_address',			'To (new address)');
define('sl_label_mail_send_html',				'Send E-Mail in HTML format');
define('sl_label_mail_subject',					'Subject');
define('sl_label_mail_text',						'Message');
define('sl_label_mail_to_select',				'To (Selection)');
define('sl_label_name',									'Identifier');
define('sl_label_param',								'Param');
define('sl_label_shortlink',						'ShortLink');
define('sl_label_status',								'State');
define('sl_label_type',									'Type');
define('sl_label_unlink_auto',					'Delete automaticly');
define('sl_label_update_by',						'Last updated by');
define('sl_label_update_when',					'Last updated at');
define('sl_label_upload_file',					'Upload File');
define('sl_label_url_long',							'Target URL');
define('sl_label_url_short',						'ShortLink');
define('sl_label_use_once',							'Single-Use Link');
define('sl_label_valid_until',					'Valid until');

define('sl_msg_cfg_add_exists',					'<p>The configuration entry with the identifier <b>%s</b> already exist and can\'t added again!</p>');
define('sl_msg_cfg_add_incomplete',			'<p>The new configuration entry not complete! Please check the entry!</p>');
define('sl_msg_cfg_add_success',				'<p>The configuration entty with the <b>ID #%05d</b> and the identifier <b>%s</b> was successfully added.</p>');
define('sl_msg_cfg_csv_export',					'<p>The configuration was saved as <b>%s</b> in the /MEDIA directory.</p>');
define('sl_msg_cfg_id_updated',					'<p>The configuration entry with the <b>ID #%05d</b> and the identifier <b>%s</b> was updated.</p>');
define('sl_msg_cfg_link_index_fail',		'<p>ShortLink should use the <b>index.php</b> in the root of your domain, but within the <b>index.php</b> there is no execution command for <b>shortLink();</b>.<br>Please check the file and insert the execution command!.</p>');
define('sl_msg_copy_linkfile_fail',			'<p>Cant\'t copy the link-file to <b>%s</b>.<br>Probably the chmod rights of <i>ShortLink</i> are insufficient.<br>Please copy the file <b>%s</b> by yourself to the root of your domain!</p>');
define('sl_msg_dl_db_insert',						'<p>The entry for the download of the file <b>%s</b> was created.</p>');
define('sl_msg_dl_exists',							'<p>For the file <b>%s</b> already exists a DownloadLink.<br>The DownloadLink <b>%s</b> was updated.</p>');
define('sl_msg_dl_exists_file_missing',	'<p>For the file <b>%s</b> already exists a DownloadLink, the file does no longer exists, so the entry was deleted and a new entry created.</p>');
define('sl_msg_interface_not_exists',		'<p>The interface <b>%s</b> was not found and not implemented.</p>');
define('sl_msg_invalid_date',						'<p>The date/time format <b>%s</b> is invalid, please check your input!</p>');
define('sl_msg_invalid_email',					'<p>The E-Mail address <b>%s</b> is not valid, please check your input!</p>');
define('sl_msg_mail_address_added',			'<p>The E-Mail address <b>%s</b> was added to the database with the <b>ID %05d</b>.</p>');
define('sl_msg_mail_address_exists',		'<p>The E-Mail address <b>%s</b> already exists in database and was not added.</p>');
define('sl_msg_mail_from_empty',				'<p>You have forgotten the E-Mail address of the <b>Sender</b>!</p>');
define('sl_msg_mail_no_id',							'<p>Missing ShortLink <b>ID</b>!</p>');
define('sl_msg_mail_success',						'<p>Your E-Mail to <b>%s</b> was successfully send.</p>');
define('sl_msg_mail_send_fail',					'<p>Error while sending the E-Mail to <b>%s</b>:<br><b>%s</b>.</p>');
define('sl_msg_mail_subject_empty',			'<p>You have forgotten a <b>Subject</b>!</p>');
define('sl_msg_mail_text_empty',				'<p>You have forgotten a <b>Message</b>!</p>');
define('sl_msg_mail_to_empty',					'<p>You have not selected a <b>Recipient</b>!</p>');
define('sl_msg_md5_create_error',				'<p>The checksum for the file <b>%s</b> could not be created!</p>');
define('sl_msg_mkdir_error',						'<p>Cant\'t create the directory<br>--> <b>%s</b></p>');
define('sl_msg_mkdir_success',					'<p>The directory<br>--> <b>%s</b><br>was created.</p>');
define('sl_msg_no_changes',							'<p>Nothing changed.</p>');
define('sl_msg_no_longlink',						'<p>There is no link to shorten!</p>');
define('sl_msg_sl_date_reset',					'<p>The date was reset.</p>');
define('sl_msg_sl_exists_locked',				'<p>For the <b>target URL</b> already exists a <b>locked</b> ShortLink.<br>The lock was removed and the ShortLink <b>%s</b> was updated.</p>');
define('sl_msg_sl_exists',							'<p>For the <b>target URL</b> already exists a ShortLink.<br>Der ShortLink <b>%s</b> was updated.</p>');
define('sl_msg_sl_name_exists',					'<p>The identifier <b>%s</b> is already used by the ShortLink with the <b>ID %05d</b>, the entry was <b>not</b> updated!</p>');
define('sl_msg_sl_name_changed',				'<p>The identifier <b>%s</b> for the ShortLink was added rsp. changed and the call of the ShortLink updated.</p>');
define('sl_msg_sl_restore_link_exists',	'<p>The entry could not be recoverd, because for the <b>target URL</b> already exists the ShortLink with the <b>ID #%05d</b>!</p>');
define('sl_msg_sl_restore_name_exists',	'<p>The identifier <b>%s</b> of the recoverd entry must be removed, because this identifier is used by the ShortLink with the <b>ID #%05d</b>.');
define('sl_msg_sl_status_changed',			'<p>The <b>Status</b> for the ShortLink was changed.</b>');
define('sl_msg_sl_updated',							'<p>The entry for the ShortLink was updated.</p>');
define('sl_msg_sl_use_once',						'<p>The setting <b>Single-Use ShortLink</b> was changed.</p>');
define('sl_msg_sl_valid_until_changed',	'<p>The setting <b>Valid until</b> was changed to <b>%s</b>.</p>');
define('sl_msg_upload_error_move_file',	'<p>Can\'t move the transmitted file <b>%s</b> to<br>--> <b>%s</b></p>');
define('sl_msg_upload_no_file',					'<p>No file selected for transmission!</p>');
define('sl_msg_unlink_auto',						'<p>The setting <b>Delete automaticly</b> was changed.</p>');

define('sl_status_active',							'Active');
define('sl_status_deleted',							'Deleted');
define('sl_status_locked',							'Locked');

define('sl_tab_about',									'?');
define('sl_tab_cfg',										'Settings');
define('sl_tab_download',								'<i>Download</i>Link');
define('sl_tab_list',										'List');
define('sl_tab_shortlink',							'<i>Short</i>Link');

define('sl_template_error',        		 	'<div style="margin:15px;padding:15px;border:1px solid #cc0000;color: #cc0000; background-color:#ffffdd;"><h1>%s</h1>%s</div>');

define('sl_text_anleitung',							'<p>Before you can using <b>ShortLink</b>, you must copy the file <b>link.php</b> from the module directory of ShortLink to the root directory of your domain.</p>'.
																				'<p>You can rename the file <b>link.php</b>. In this case please change in the <i>Settings</i> the value for the identifier <i>cfgLinkFileName</i>, to tell ShortLink the renamed file.</p>'.
																				'<p><b>Alternate</b> you may add a ShortLink execution command to the <b>index.php</b> in the root directory of your Website Baker installation.</p>'.
																				'<p>Edit the <b>index.php</b> with a texteditor and insert the ShortLink execution command after <i>// Check if the config file has been set-up</i> as described below.</p>'.
																				'<p>To tell ShortLink to use the <b>index.php</b> instead of the link-file, please change in the <i>Settings</i> the value for the identifier <i>cfgLinkFileUse</i> to <b>0</b>.</p>');
define('sl_text_anleitung_2',						'<i>For further informations please look at the <a href="http://phpmanufaktur.de/pages/shortlink.php">online documentation</a> (German only).');
define('sl_text_changed_by',						'%s, at %s h');
define('sl_text_copyright',							'ShortLink v%s - &copy 2009 by Ralf Hertsch, Berlin (Germany)');
define('sl_text_error',									'- ERROR -');
define('sl_text_mail_text_sl',					'Please use the link:<br /><br />--> <a href="%s">%s</a><br /><br />Regards<br />%s');
define('sl_text_mail_text_dl',					'Please download the file <b>%s</b> by using the link:<br /><br />--> <a href="%s">%s</a><br /><br />Filesize: %s<br />Checksum (MD5): %s<br /><br />Regards<br />%s');
define('sl_text_not_established',				'- not established -');
define('sl_text_select',								'- please select -');
define('sl_text_undefined',							'- not defined -');
define('sl_text_yes_lower',							'yes');

define('sl_type_download',							'Download');
define('sl_type_shortlink',							'ShortLink');
define('sl_type_undefined',							'- not defined -');

?>