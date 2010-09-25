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
  
  $Id: DE.php 28 2009-07-13 04:05:12Z ralf $
  
**/

define('sl_btn_abort',									'Abbrechen');
define('sl_btn_change',									'ShortLink &auml;ndern');
define('sl_btn_copy',										'Kopieren');
define('sl_btn_dl_new',									'Neuer DownloadLink');
define('sl_btn_dl_change',							'DownloadLink &auml;ndern');
define('sl_btn_mail',										'E-Mail senden');
define('sl_btn_new',										'Neuer ShortLink');
define('sl_btn_ok',											'&Uuml;bernehmen');
define('sl_btn_ready',									'Fertig');

define('sl_desc_cfg_developer_mode',		'Erm&ouml;glicht dem Programmierer das Hinzuf&uuml;gen von Konfigurationsparametern.');
define('sl_desc_cfg_dl_unlink_auto',		'Voreinstellung: DownloadLink nach Ablauf der G&uuml;ltigkeit automatisch l&ouml;schen (1 = JA, 0 = NEIN).');
define('sl_desc_cfg_dl_use_once',				'Voreinstellung: DownloadLink nur ein einziges Mal ausf&uuml;hren (1 = JA, 0 = NEIN).');
define('sl_desc_cfg_download_path',			'Der Pfad ausgehend von <b>WB_PATH</b>, den ShortLink f&uuml;r die Bereitstellung von Download Dateien verwenden soll.');
define('sl_desc_cfg_linkfile_use',			'Legen Sie fest, ob ShortLink im Wurzelverzeichnis Ihrer Domain &uuml;ber die Link-Datei <b>link.php</b> oder eine angepasste <b>index.php</b> aufgerufen wird.');
define('sl_desc_cfg_linkfile_name',			'Sie k&ouml;nnen die Link-Datei <b>link.php</b> im Wurzelverzeichnis Ihrer Domain umbenennen.');
define('sl_desc_cfg_mail_send_html',		'Voreinstellung: E-Mails im HTML Format versenden (1 = JA, 0 = NEIN).');
define('sl_desc_cfg_use_interface',			'Legen Sie fest, welche zus&auml;tzlichen Interfaces ShortLink verwenden soll.');
define('sl_desc_cfg_sl_use_once',				'Voreinstellung: ShortLink nur ein einziges Mal ausf&uuml;hren (1 = JA, 0 = NEIN).');
define('sl_desc_cfg_switches',					'Spezielle Schalter, mit denen sich das Verhalten von ShortLink beeinflussen l&auml;sst (<i>siehe Dokumentation</i>).');
define('sl_desc_mail_new_address',			'Geben Sie eine <b>neue</b> E-Mail Adresse ein, die sich noch nicht in der Auswahlliste befindet.<br />Wenn Sie eine E-Mail Adresse und einen Namen angeben, verwenden Sie die Form<br /><b>NAME &lt;E-MAIL ADRESSE&gt;</b>');
define('sl_desc_mail_select_address',		'W&auml;hlen Sie die gew&uuml;nschte(n) E-Mail Adresse(n) aus.<br />In dieser Liste werden E-Mail Adressen angezeigt, die Sie bereits einmal verwendet haben oder auf die <i>ShortLink</i> &uuml;ber eine Schnittstelle Zugriff hat.');
define('sl_desc_name',									'Sie k&ouml;nnen Link\'s, die Sie regelm&auml;&szlig;ig verwenden m&ouml;chten, einen festen Bezeichner zuordnen, &uuml;ber die sie aufgerufen werden.<br>Verwenden Sie hierzu ein einzelnes Wort ohne Sonderzeichen und/oder Umlaute.<br>Der Aufruf erfolgt mit:<br><b>%s=&lt;BEZEICHNER&gt;</b>.');
define('sl_desc_status',								'&Auml;ndern Sie den Status um den Link zu sperren oder zu l&ouml;schen.');
define('sl_desc_unlink_auto',						'Die Datei wird automatisch gel&ouml;scht, wenn der DownloadLink nicht mehr g&uuml;ltig ist.');
define('sl_desc_use_once',							'Der Link kann nur ein einziges Mal aufgerufen werden.');
define('sl_desc_valid_until',						'Sie k&ouml;nnen ein Datum festlegen, bis zu dem der Link einschlie&szlig;lich g&uuml;ltig ist.');

define('sl_error_addon_version',     		'<p>Fataler Fehler: <b>ShortLink</b> benoetigt das Website Baker Addon <b>%s</b> ab der Version <b>%01.2f</b> - installiert ist die Version <b>%01.2f</b>.</p><p>Bitte aktualisieren Sie zunaechst dieses Addon.</p>');
define('sl_error_cfg_id',								'<p>Der Konfigurationsdatensatz mit der <b>ID #%05d</b> konnte nicht ausgelesen werden!</p>');
define('sl_error_cfg_name',							'<p>Zu dem Bezeichner <b>%s</b> wurde kein Konfigurationsdatensatz gefunden!</p>');
define('sl_error_missing_addon',    		'<p>Fataler Fehler: <b>ShortLink</b> benoetigt das Website Baker Addon <b>%s</b>, die Programmausfuehrung wurde gestoppt.</p>');
define('sl_error_missing_id',						'<p>Es wurde keine <b>ID</b> f&uuml;r den ShortLink &uuml;bergeben, der bearbeitet werden soll!</p>');
define('sl_error_sl_id',								'<p>Der ShortLink mit der <b>ID #%05d</b> wurde nicht gefunden!</p>');

define('sl_header_config',							'Einstellungen');
define('sl_header_copy_btn',						'');
define('sl_header_count',								'Aufrufe');
define('sl_header_description',					'Beschreibung');
define('sl_header_dl_edit',							'<i>Download</i>Link bearbeiten');
define('sl_header_download',						'<i>Download</i>Link');
define('sl_header_id',									'ID');
define('sl_header_identifier',					'Bezeichner');
define('sl_header_label',								'Label');
define('sl_header_last_exec',						'Letzter Aufruf');
define('sl_header_mail',								'E-Mail versenden');
define('sl_header_name',								'Bezeichner');
define('sl_header_param',								'Kennung');
define('sl_header_prompt_error',    		'[ShortLink] Fehlermeldung');
define('sl_header_shortlink',						'<i>Short</i>Link');
define('sl_header_shortlink_edit',			'<i>Short</i>Link bearbeiten');
define('sl_header_sl_list',							'<i>Short</i>Link Liste');
define('sl_header_typ',									'Typ');
define('sl_header_use_once',						'Einweg');
define('sl_header_valid_until',					'G&uuml;ltig bis');
define('sl_header_value',								'Wert');
define('sl_header_update_when',					'Letzte &Auml;nderung');

define('sl_intro_cfg_add_item',					'<p>Das Hinzuf&uuml;gen von Eintr&auml;gen zur Konfiguration ist nur sinnvoll, wenn die angegebenen Werte mit dem Programm korrespondieren.</p>');
define('sl_intro_cfg',									'<p>Bearbeiten Sie die Einstellungen f&uuml;r ShortLink.</p>');
define('sl_intro_downloadlink',					'<p>&Uuml;bertragen Sie die gew&uuml;nschte Datei auf den Server und erstellen Sie einen DownloadLink.</p>');
define('sl_intro_link_copy',						'<p>Kopieren Sie sich den verk&uuml;rzten Link in die Zwischenablage.</p>');
define('sl_intro_link_edit',						'<p>Mit diesem Dialog k&ouml;nnen Sie den Link bearbeiten und erg&auml;nzen.</p>');
define('sl_intro_shortlink',						'<p>F&uuml;gen Sie den Link, den Sie verk&uuml;rzen m&ouml;chten, in das Eingabefeld ein und klicken Sie auf die Eingabetaste.</p>');
define('sl_intro_sl_list',							'<p>W&auml;hlen Sie den Link aus, den Sie bearbeiten m&ouml;chten.</p>');
define('sl_intro_mail',									'<p>Versenden Sie den Link per E-Mail...</p>');

define('sl_label_cfg_developer_mode',		'Programmierer Modus');
define('sl_label_cfg_dl_unlink_auto',		'Automatisch L&ouml;schen');
define('sl_label_cfg_dl_use_once',			'Einweg DownloadLink');
define('sl_label_cfg_download_path',		'Download Pfad');
define('sl_label_cfg_linkfile_name',		'Linkfile Dateiname');
define('sl_label_cfg_linkfile_use',			'Linkfile verwenden');
define('sl_label_cfg_mail_send_html',		'E-Mails HTML Format');
define('sl_label_cfg_sl_use_once',			'Einweg ShortLink');
define('sl_label_cfg_switches',					'Spezielle Schalter');
define('sl_label_cfg_use_interface',		'Interface verwenden');
define('sl_label_checksum',							'Pr&uuml;fsumme');
define('sl_label_csv_export',						'Konfigurationsdaten als CSV-Datei im /MEDIA Verzeichnis sichern');
define('sl_label_count',								'Aufrufe insgesamt');
define('sl_label_created_by',						'Link angelegt von');
define('sl_label_created_when',					'Link angelegt am');
define('sl_label_dl_file_name',					'Download Datei');
define('sl_label_dl_filesize',					'Dateigr&ouml;&szlig;e');
define('sl_label_dl_fn_target',					'Datei gesichert als');
define('sl_label_download_link',				'DownloadLink');
define('sl_label_id',										'ID');
define('sl_label_last_exec',						'Letzter Aufruf');
define('sl_label_mail_from',						'Absender');
define('sl_label_mail_new_address',			'Empf&auml;nger (neue Adresse)');
define('sl_label_mail_send_html',				'E-Mail im HTML Format versenden');
define('sl_label_mail_subject',					'Betreff');
define('sl_label_mail_text',						'Mitteilung');
define('sl_label_mail_to_select',				'Empf&auml;nger (Auswahl)');
define('sl_label_name',									'Bezeichner');
define('sl_label_param',								'Kennung');
define('sl_label_shortlink',						'ShortLink');
define('sl_label_status',								'Status');
define('sl_label_type',									'Typ');
define('sl_label_unlink_auto',					'Automatisch l&ouml;schen');
define('sl_label_update_by',						'Zuletzt aktualisiert von');
define('sl_label_update_when',					'Zuletzt aktualisiert am');
define('sl_label_upload_file',					'Datei &uuml;bertragen');
define('sl_label_url_long',							'Ziel URL');
define('sl_label_url_short',						'ShortLink');
define('sl_label_use_once',							'Einweg Link');
define('sl_label_valid_until',					'G&uuml;ltig bis');

define('sl_msg_cfg_add_exists',					'<p>Der Konfigurationsdatensatz mit dem Bezeichner <b>%s</b> existiert bereits und kann nicht noch einmal hinzugef&uuml;gt werden!</p>');
define('sl_msg_cfg_add_incomplete',			'<p>Der neu hinzuzuf&uuml;gende Konfigurationsdatensatz ist unvollst&auml;ndig! Bitte pr&uuml;fen Sie Ihre Angaben!</p>');
define('sl_msg_cfg_add_success',				'<p>Der Konfigurationsdatensatz mit der <b>ID #%05d</b> und dem Bezeichner <b>%s</b> wurde hinzugef&uuml;gt.</p>');
define('sl_msg_cfg_csv_export',					'<p>Die Konfigurationsdaten wurden als <b>%s</b> im /MEDIA Verzeichnis gesichert.</p>');
define('sl_msg_cfg_id_updated',					'<p>Der Konfigurationsdatensatz mit der <b>ID #%05d</b> und dem Bezeichner <b>%s</b> wurde aktualisiert.</p>');
define('sl_msg_cfg_link_index_fail',		'<p>ShortLink soll die <b>index.php</b> im Wurzelverzeichnis Ihrer Domain verwenden, in der <b>index.php</b> fehlt jedoch der Aufruf f&uuml;r <b>shortLink();</b>.<br>Bitte pr&uuml;fen Sie die Datei.</p>');
define('sl_msg_copy_linkfile_fail',			'<p>Die Linkdatei konnte nicht nach <b>%s</b> kopiert werden.<br>Vermutlich sind die Rechte nicht ausreichend, bitte kopieren Sie die Datei <b>%s</b> selbst in das Wurzelverzeichnis ihrer Domain.</p>');
define('sl_msg_dl_db_insert',						'<p>Der Datensatz f&uuml;r den Download der Datei <b>%s</b> wurde angelegt.</p>');
define('sl_msg_dl_exists',							'<p>F&uuml;r die Datei <b>%s</b> existiert bereits ein DownloadLink.<br>Der DownloadLink <b>%s</b> wurde aktualisiert.</p>');
define('sl_msg_dl_exists_file_missing',	'<p>F&uuml;r die Datei <b>%s</b> existiert bereits ein DownloadLink, die Datei selbst existiert jedoch nicht mehr.<br>Der Datensatz wurde deshalb gel&ouml;scht und ein neuer Datensatz angelegt.</p>');
define('sl_msg_interface_not_exists',		'<p>Das Interface <b>%s</b> wurde nicht gefunden und deshalb nicht eingebunden.</p>');
define('sl_msg_invalid_date',						'<p>Die Datumsangabe <b>%s</b> ist ung&uuml;ltig, bitte pr&uuml;fen Sie Ihre Eingabe!</p>');
define('sl_msg_invalid_email',					'<p>Die E-Mail Adresse <b>%s</b> ist nicht g&uuml;ltig, bitte pr&uuml;fen Sie Ihre Eingabe.</p>');
define('sl_msg_mail_address_added',			'<p>Die E-Mail Adresse <b>%s</b> wurde mit der <b>ID %05d</b> der Datenbank hinzugef&uuml;gt.</p>');
define('sl_msg_mail_address_exists',		'<p>Die E-Mail Adresse <b>%s</b> existiert bereits in der Datenbank und wurde nicht hinzugef&uuml;gt.</p>');
define('sl_msg_mail_from_empty',				'<p>Sie haben vergessen, die E-Mail Adresse des <b>Absenders</b> anzugeben!</p>');
define('sl_msg_mail_no_id',							'<p>Es wurde keine ShortLink <b>ID</b> &uuml;bergeben!</p>');
define('sl_msg_mail_success',						'<p>Die E-Mail an <b>%s</b> wurde versendet.</p>');
define('sl_msg_mail_send_fail',					'<p>Beim Versenden der E-Mail an <b>%s</b> ist ein Fehler aufgetreten:<br><b>%s</b>.</p>');
define('sl_msg_mail_subject_empty',			'<p>Sie haben vergessen, einen <b>Betreff</b> anzugeben!</p>');
define('sl_msg_mail_text_empty',				'<p>Sie haben vergessen, eine <b>Mitteilung</b> zu schreiben!</p>');
define('sl_msg_mail_to_empty',					'<p>Sie haben vergessen, die E-Mail Adresse des <b>Empf&auml;ngers</b> anzugeben!</p>');
define('sl_msg_md5_create_error',				'<p>Die Pr&uuml;fsumme f&uuml;r die Datei <b>%s</b> konnte nicht erstellt werden.</p>');
define('sl_msg_mkdir_error',						'<p>Das Verzeichnis<br>--> <b>%s</b><br>konnte nicht erstellt werden!</p>');
define('sl_msg_mkdir_success',					'<p>Das Verzeichnis<br>--> <b>%s</b><br>wurde angelegt.</p>');
define('sl_msg_no_changes',							'<p>Es wurden keine &Auml;nderungen vorgenommen.</p>');
define('sl_msg_no_longlink',						'<p>Sie haben keinen Link zum Verk&uuml;rzen angegeben!</p>');
define('sl_msg_sl_date_reset',					'<p>Das Datum wurde zur&uuml;ckgesetzt.</p>');
define('sl_msg_sl_exists_locked',				'<p>F&uuml;r die <b>Ziel URL</b> existiert bereits ein <b>gesperrter</b> ShortLink.<br>Die Sperre wurde aufgehoben und der ShortLink <b>%s</b> wieder aktiviert sowie aktualisiert.</p>');
define('sl_msg_sl_exists',							'<p>F&uuml;r die <b>Ziel URL</b> existiert bereits ein ShortLink.<br>Der ShortLink <b>%s</b> wurde aktualisiert.</p>');
define('sl_msg_sl_name_exists',					'<p>Der Bezeichner <b>%s</b> wird bereits von dem ShortLink mit der <b> #%05d</b> verwendet, der Datensatz wurde nicht aktualisiert.</p>');
define('sl_msg_sl_name_changed',				'<p>Der Bezeichner <b>%s</b> f&uuml;r den ShortLink wurde neu hinzugef&uuml;gt bzw. ge&auml;ndert und der Aufruf des ShortLink aktualisiert.</p>');
define('sl_msg_sl_restore_link_exists',	'<p>Der Datensatz kann nicht wiederhergestellt werden, da f&uuml;r die <b>Ziel URL</b> bereits der ShortLink mit der <b>ID #%05d</b> existiert.</p>');
define('sl_msg_sl_restore_name_exists',	'<p>Der Bezeichner <b>%s</b> des wiederhergestellten Datensatz musste gel&ouml;scht werden, da der geleiche Bezeichner von dem ShortLink mit der <b>ID #%05d</b> verwendet wird.');
define('sl_msg_sl_status_changed',			'<p>Der <b>Status</b> f&uuml;r den ShortLink wurde ge&auml;ndert.</b>');
define('sl_msg_sl_updated',							'<p>Der Datensatz f&uuml;r den ShortLink wurde erfolgreich aktualisiert.</p>');
define('sl_msg_sl_use_once',						'<p>Die Einstellung <b>Einweg ShortLink</b> wurde ge&auml;ndert.</p>');
define('sl_msg_sl_valid_until_changed',	'<p>Die Einstellung <b>G&uuml;ltig bis</b> wurde in <b>%s</b> ge&auml;ndert.</p>');
define('sl_msg_upload_error_move_file',	'<p>Die &uuml;bertragene Datei <b>%s</b> konnte nicht nach<br>--> <b>%s</b><br>verschoben werden.</p>');
define('sl_msg_upload_no_file',					'<p>Es wurde keine Datei f&uuml;r die &Uuml;bertragung ausgew&auml;hlt.</p>');
define('sl_msg_unlink_auto',						'<p>Die Einstellung <b>Automatisch l&ouml;schen</b> wurde ge&auml;ndert.</p>');

define('sl_status_active',							'Aktiv');
define('sl_status_deleted',							'Gel&ouml;scht');
define('sl_status_locked',							'Gesperrt');

define('sl_tab_about',									'?');
define('sl_tab_cfg',										'Einstellungen');
define('sl_tab_download',								'<i>Download</i>Link');
define('sl_tab_list',										'Liste');
define('sl_tab_shortlink',							'<i>Short</i>Link');

define('sl_template_error',        		 	'<div style="margin:15px;padding:15px;border:1px solid #cc0000;color: #cc0000; background-color:#ffffdd;"><h1>%s</h1>%s</div>');

define('sl_text_anleitung',							'<p>Damit Sie <b>ShortLink</b> nutzen k&ouml;nnen, ist es erforderlich, die Datei <b>link.php</b> aus dem Modulverzeichnis von ShortLink in das Wurzelverzeichnis Ihrer Domain zu kopieren.</p>'.
																				'<p>Sie k&ouml;nnen die Datei <b>link.php</b> umbenennen, in diesem Fall &auml;ndern Sie unter den <i>Einstellungen</i> den Wert f&uuml;r den Bezeichner <i>cfgLinkFileName</i> entsprechend, damit ShortLink die Datei finden und verwenden kann.</p>'.
																				'<p><b>Alternativ</b> k&ouml;nnen Sie die <b>index.php</b> im Wurzelverzeichnis ihrer Websitebaker Installation mit einem Aufruf f&uuml;r ShortLink erg&auml;nzen.</p>'.
																				'<p>&ouml;ffnen Sie hierzu die Datei <b>index.php</b> mit einem Texteditor und f&uuml;gen Sie nach dem Abschnitt <i>// Check if the config file has been set-up</i> den Abschnitt f&uuml;r <b>ShortLink</b> ein, wie im unten stehenden Beispiel gezeigt.</p>'.
																				'<p>Damit ShortLink die <b>index.php</b> anstatt dem Linkfile verwendet, &auml;ndern Sie unter den <i>Einstellungen</i> den Wert f&uuml;r den Bezeichner <i>cfgLinkFileUse</i> auf <b>0</b>.</p>');
define('sl_text_anleitung_2',						'<i>F&uuml;r weitere Informationen zu Konfigurationsm&ouml;glichkeiten etc. finden Sie in der <a href="http://phpmanufaktur.de/pages/shortlink.php">online Dokumentation</a>.');
define('sl_text_changed_by',						'%s, am %s Uhr');
define('sl_text_copyright',							'ShortLink v%s - &copy 2009 by Ralf Hertsch, Berlin (Germany)');
define('sl_text_error',									'- FEHLER -');
define('sl_text_mail_text_sl',					'Die Adresse lautet:<br /><br />--> <a href="%s">%s</a><br /><br />Mit freundlichen Gr&uuml;&szlig;en<br />%s');
define('sl_text_mail_text_dl',					'Bitte laden Sie sich die Datei <b>%s</b> durch Aufruf der Adresse:<br /><br />--> <a href="%s">%s</a><br /><br />herunter.<br /><br />Dateigr&ouml;&szlig;e: %s<br />Pr&uuml;fsumme (MD5): %s<br /><br />Mit freundlichen Gr&uuml;&szlig;en<br />%s');
define('sl_text_not_established',				'- nicht festgelegt -');
define('sl_text_select',								'- bitte ausw&auml;hlen -');
define('sl_text_undefined',							'- nicht definiert -');
define('sl_text_yes_lower',							'ja');

define('sl_type_download',							'Download');
define('sl_type_shortlink',							'ShortLink');
define('sl_type_undefined',							'- nicht definiert -');

?>