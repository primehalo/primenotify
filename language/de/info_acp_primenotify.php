<?php
/**
*
* Prime Notify extension for the phpBB Forum Software package.
*
* @copyright (c) 2018 Ken F. Innes IV <https://www.absoluteanime.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ » “ ” …
//

$lang = array_merge($lang, array(
	'ACP_PRIMENOTIFY_TITLE'					=> 'Prime Notify',
	'ACP_PRIMENOTIFY_SETTINGS'				=> 'Prime Notify Einstellungen',
	'ACP_PRIMENOTIFY_BASIC_SETTINGS'		=> 'Generelle Einstellungen',
	'ACP_PRIMENOTIFY_SETTINGS_SAVED'		=> 'Einstellungen wurden erfolgreich gespeichert!',
	'ACP_PRIMENOTIFY_SETTINGS_LOG'			=> '<strong>Änderungen der Prime Notify Einstllungen</strong>',
	'ACP_PRIMENOTIFY_USER_CHOICE'			=> 'Auswahl durch Benutzer',
	'ACP_PRIMENOTIFY_USER_CHOICE_MSG'		=> 'User’s Choice options will be shown in the <strong>Edit global settings</strong> section of the <strong>Board preferences</strong> tab on the <strong>User Control Panel</strong>, when enabled.',

	// Settings
	'ACP_PRIMENOTIFY_ENABLE_POST'			=> 'Für Beiträge aktivieren',
	'ACP_PRIMENOTIFY_ENABLE_POST_EXPLAIN'	=> 'Fügt den Inhalt der Beiträge in die Emailbenachrichtigung ein.',
	'ACP_PRIMENOTIFY_ENABLE_PM'				=> 'Für Private Nachrichten aktivieren',
	'ACP_PRIMENOTIFY_ENABLE_PM_EXPLAIN'		=> 'Fügt den Inhalt des der Privaten Nachrichten in die Emailbenachrichtigung ein.',
	'ACP_PRIMENOTIFY_KEEP_BBCODES'			=> 'Behalte die BBCodes bei',
	'ACP_PRIMENOTIFY_KEEP_BBCODES_EXPLAIN'	=> 'Emails werden als "nur Text" versendet. Somit werden die BBCode-Formatierungen nicht berücksichtigt um den Email-Text zu formatieren (Bsp.: Ein, im Beitrag, fett dargestelltes <b>Wort</b> wird in der Email als [b]Wort[/b] dargestellt). <br>
                                                Die Einstellung "Ja" zeigt also die BBCodes in der Mail an, "Nein" löscht die BBCodes aus dem Text heraus.',
	'ACP_PRIMENOTIFY_ALWAYS_SEND'			=> 'Immer Benachrichtigen',
	'ACP_PRIMENOTIFY_ALWAYS_SEND_EXPLAIN'	=> '"Ja" sendet bei jedem neuen Beitrag in abonnierten Thema eine Benachrichtigung, unabhängig davon ob Du das Thema zwischenzeitlich besucht hast oder nicht. <br>
                                                "Nein" sendet eine neue Benachrichtigung in dem Thema nur dann, wenn Du das Thema besucht hast.',
	'ACP_PRIMENOTIFY_TRUNCATE'				=> 'Min. Länge des Beitrages/der Nachricht',
	'ACP_PRIMENOTIFY_TRUNCATE_EXPLAIN'		=> 'Berücksichtigt nur Beiträge/Nachrichten mit mehr Zeichen als der genannten Anzahl. Setze auf "0" für kein Limit.',
));
