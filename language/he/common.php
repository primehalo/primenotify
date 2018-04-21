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
	'PRIMENOTIFY_QUOTE_FROM'				=> "ציטוט מאת $1:\n$2",			// $1, $2: Regular expression replacement variables
	'PRIMENOTIFY_QUOTE'						=> "ציטוט:\n$1",				// $1: Regular expression replacement variable
	'PRIMENOTIFY_CODE'						=> "קוד:\n$1",				// $1: Regular expression replacement variable
	'PRIMENOTIFY_IMAGE'						=> 'תמונה: $1',				// $1: Regular expression replacement variable
	'PRIMENOTIFY_SPOILER_REMOVED'			=> '-- Spoiler Removed --',	// Replaces the content of a spoiler tag

	// User Settings
	'UCP_PRIMENOTIFY_TITLE'					=> 'התראות באימייל',
	'UCP_PRIMENOTIFY_ENABLE_POST'			=> 'כלול הודעה באימייל',
	'UCP_PRIMENOTIFY_ENABLE_POST_EXPLAIN'	=> 'כלול את תוכן ההודעה בהתראה באימייל.',
	'UCP_PRIMENOTIFY_ENABLE_PM'				=> 'כלול הודעה פרטית באימייל',
	'UCP_PRIMENOTIFY_ENABLE_PM_EXPLAIN'		=> 'כלול את התוכן של ההודעה הפרטית בהתראה באימייל.',
	'UCP_PRIMENOTIFY_KEEP_BBCODES'			=> 'שמור על תגי BBCodes',
	'UCP_PRIMENOTIFY_KEEP_BBCODES_EXPLAIN'	=> 'אימיילים נשלחים כטקסט כך שתגי ה BBCode לא מפורמטים. לשמור עליהם יכול לעזור להראות את תוכן ההודעה אך יגרום להודעה לראות מבולגנת.',
	'UCP_PRIMENOTIFY_ALWAYS_SEND'			=> 'התראה לכל הודעה חדשה',
	'UCP_PRIMENOTIFY_ALWAYS_SEND_EXPLAIN'	=> 'שלח התראה באימייל לכל הודעה חדשה בנושא או בפורום שאליו נרשמת. בדרך-כלל נשלחת רק התראה על ההודעה הראשונה לאחר הביקור שלך בנושא.',
));
