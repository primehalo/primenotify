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
	// Plain-text for e-mail
	'PRIMENOTIFY_LIST_ITEM'					=> '* ',					// Bullet for a list item when BBCodes are removed
	'PRIMENOTIFY_QUOTE_FROM'				=> "Citazione da $1:\n$2",	// $1, $2: Regular expression replacement variables
	'PRIMENOTIFY_QUOTE'						=> "Citazione:\n$1",			// $1: Regular expression replacement variable
	'PRIMENOTIFY_CODE'						=> "Codice:\n$1",				// $1: Regular expression replacement variable
	'PRIMENOTIFY_IMAGE'						=> 'Immagine: $1',				// $1: Regular expression replacement variable
	'PRIMENOTIFY_SPOILER_REMOVED'			=> '-- Spoiler Rimosso --',	// Replaces the content of a spoiler tag

	// User Settings
	'UCP_PRIMENOTIFY_TITLE'					=> 'Mail di Notifica',
	'UCP_PRIMENOTIFY_ENABLE_POST'			=> 'Includi il messaggio nella mail',
	'UCP_PRIMENOTIFY_ENABLE_POST_EXPLAIN'	=> 'Includi il contenuto del post nella mail di notifica.',
	'UCP_PRIMENOTIFY_ENABLE_PM'				=> 'Includi messaggio privato nella mail',
	'UCP_PRIMENOTIFY_ENABLE_PM_EXPLAIN'		=> 'Includi il contenuto del messaggio privato nella mail di notifica.',
	'UCP_PRIMENOTIFY_KEEP_BBCODES'			=> 'Mantieni i BBCode',
	'UCP_PRIMENOTIFY_KEEP_BBCODES_EXPLAIN'	=> 'Le mail sono inviate come testo normale quindi i BBCode non vengono convertiti alla formattazione. Mantenerli puo\' aiutare per mostrare la formattazione voluta ma al contempo rimuoverli puo\' far sembrare piu\' pulito il messaggio.',
	'UCP_PRIMENOTIFY_ALWAYS_SEND'			=> 'Notifica sempre',
	'UCP_PRIMENOTIFY_ALWAYS_SEND_EXPLAIN'	=> 'Manda una mail di notifica per ogni nuovo post fatto nel topic o forum sottoscritto. Normalmente una mail di notifica viene inviata solo per il primo post dalla tua ultima visita al topic.',
));
