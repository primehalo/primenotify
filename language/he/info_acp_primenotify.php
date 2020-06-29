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
	'ACP_PRIMENOTIFY_TITLE'					=> 'התראות פריים',
	'ACP_PRIMENOTIFY_SETTINGS'				=> 'הגדרות של התראות פריים',
	'ACP_PRIMENOTIFY_BASIC_SETTINGS'		=> 'הגדרות כלליות',
	'ACP_PRIMENOTIFY_SETTINGS_SAVED'		=> 'ההגדרות של התראות פריים נשמרו בהצלחה!',
	'ACP_PRIMENOTIFY_SETTINGS_LOG'			=> '<strong>הגדרות של התראות פריים שונו</strong>',
	'ACP_PRIMENOTIFY_USER_CHOICE'			=> 'בחירת המשתמש',
	'ACP_PRIMENOTIFY_USER_CHOICE_MSG'		=> 'User’s Choice options will be shown in the <strong>Edit global settings</strong> section of the <strong>Board preferences</strong> tab on the <strong>User Control Panel</strong>, when enabled.',

	// Settings
	'ACP_PRIMENOTIFY_ENABLE_POST'			=> 'הפעל להודעות',
	'ACP_PRIMENOTIFY_ENABLE_POST_EXPLAIN'	=> 'כלול את תוכן ההודעה באימייל ההתראה שנשלח למשתמשים',
	'ACP_PRIMENOTIFY_ENABLE_PM'				=> 'הפעל להודעות פרטיות',
	'ACP_PRIMENOTIFY_ENABLE_PM_EXPLAIN'		=> 'כלול את תוכן ההודעה הפרטית באימייל ההתראה שנשלח למשתמשים',
	'ACP_PRIMENOTIFY_KEEP_BBCODES'			=> 'שמור על BBCodes',
	'ACP_PRIMENOTIFY_KEEP_BBCODES_EXPLAIN'	=> 'מיילים נשלח כטקסט פשוט כך שלא ניתן להמיר את תגי ה BBCodes. לשמור עליהם יכול לעזור להבין את ההודעה בועד הסרתם תגרום לאימייל להראות נקי יותר.',
	'ACP_PRIMENOTIFY_ALWAYS_SEND'			=> 'שלח התראה בכל הודעה חדשה',
	'ACP_PRIMENOTIFY_ALWAYS_SEND_EXPLAIN'	=> 'שולח אימייל התראה בכל פעם שיש הודעה חדשה בנושא או הפורום שאליו נרשמו. בדרך-כלל מייל התראה נשלח רק עבור ההודעה הראשונה שמפורסמת מאז הביקור האחרון של המשתמש בנושא.',
	'ACP_PRIMENOTIFY_TRUNCATE'				=> 'הגבל אורך הודעה',
	'ACP_PRIMENOTIFY_TRUNCATE_EXPLAIN'		=> 'הגבל הודעות לאורך מקסימלי של תווים לפי הגדרה, אם ההודעה ארוכה יותר מהמוגדר, אזי היא תחתך מנקודה זו. קבע כ-0 כדי לא להגביל.',
));
