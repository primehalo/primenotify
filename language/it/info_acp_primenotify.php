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
	'ACP_PRIMENOTIFY_SETTINGS'				=> 'Impostazioni di Prime Notify',
	'ACP_PRIMENOTIFY_BASIC_SETTINGS'		=> 'Impostazioni Generali',
	'ACP_PRIMENOTIFY_SETTINGS_SAVED'		=> 'Le impostazioni di Prime Notify sono state salvate con successo!',
	'ACP_PRIMENOTIFY_SETTINGS_LOG'			=> '<strong>Alterate le impostazioni di Prime Notify</strong>',
	'ACP_PRIMENOTIFY_USER_CHOICE'			=> 'Scelta dell\'Utente',
	'ACP_PRIMENOTIFY_USER_CHOICE_MSG'		=> 'Le opzioni di Scelta dell\'Utente verranno mostrate nella sezione <strong>Preferenze Globali</strong> del tab <strong>Preferenze</strong> nel <strong>Pannello di Controllo Utente</strong>, se abilitate.',

	// Settings
	'ACP_PRIMENOTIFY_ENABLE_POST'			=> 'Abilita per i Messaggi',
	'ACP_PRIMENOTIFY_ENABLE_POST_EXPLAIN'	=> 'Includi il contenuto di un post nella mail di notifica inviata agli utenti.',
	'ACP_PRIMENOTIFY_ENABLE_PM'				=> 'Abilita per i Messaggi Privati',
	'ACP_PRIMENOTIFY_ENABLE_PM_EXPLAIN'		=> 'Includi il contenuto di un messaggio privato nella mail di notifica inviata agli utenti.',
	'ACP_PRIMENOTIFY_KEEP_BBCODES'			=> 'Mantieni i BBCode',
	'ACP_PRIMENOTIFY_KEEP_BBCODES_EXPLAIN'	=> 'Le mail sono inviate come testo normale quindi i BBCode non vengono convertiti alla formattazione. Mantenerli puo\' aiutare per mostrare la formattazione voluta ma al contempo rimuoverli puo\' far sembrare piu\' pulito il messaggio.',
	'ACP_PRIMENOTIFY_ALWAYS_SEND'			=> 'Mail ad ogni nuovo post',
	'ACP_PRIMENOTIFY_ALWAYS_SEND_EXPLAIN'	=> 'Manda una mail di notifica per ogni nuovo post fatto nel topic o forum sottoscritto. Normalmente una mail di notifica viene inviata solo per il primo post dalla tua ultima visita al topic.',
	'ACP_PRIMENOTIFY_TRUNCATE'				=> 'Limita la Lunghezza del Messaggio',
	'ACP_PRIMENOTIFY_TRUNCATE_EXPLAIN'		=> 'Tronca i messaggi piu\' lunghi del limite di caratteri specificato. Imposta a 0 per nessun limite.',
));
