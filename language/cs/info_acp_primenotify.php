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
	'ACP_PRIMENOTIFY_SETTINGS'				=> 'Prime Notify Settings',
	'ACP_PRIMENOTIFY_BASIC_SETTINGS'		=> 'General Settings',
	'ACP_PRIMENOTIFY_SETTINGS_SAVED'		=> 'Prime Notify settings have been saved successfully!',
	'ACP_PRIMENOTIFY_SETTINGS_LOG'			=> '<strong>Altered Prime Notify settings</strong>',
	'ACP_PRIMENOTIFY_USER_CHOICE'			=> 'User’s Choice',
	'ACP_PRIMENOTIFY_USER_CHOICE_MSG'		=> 'User’s Choice options will be shown in the <strong>Edit global settings</strong> section of the <strong>Board preferences</strong> tab on the <strong>User Control Panel</strong>, when enabled.',

	// Settings
	'ACP_PRIMENOTIFY_ENABLE_POST'			=> 'Enable for Posts',
	'ACP_PRIMENOTIFY_ENABLE_POST_EXPLAIN'	=> 'Include the content of a post in the notification email sent to users.',
	'ACP_PRIMENOTIFY_ENABLE_PM'				=> 'Enable for Private Messages',
	'ACP_PRIMENOTIFY_ENABLE_PM_EXPLAIN'		=> 'Include the content of a private message in the notification email sent to users.',
	'ACP_PRIMENOTIFY_KEEP_BBCODES'			=> 'Keep BBCodes',
	'ACP_PRIMENOTIFY_KEEP_BBCODES_EXPLAIN'	=> 'Emails are sent as plain text so BBCodes don’t get converted to formatting. Keeping them may help to show the intended formatting while removing them may make the message look cleaner.',
	'ACP_PRIMENOTIFY_ALWAYS_SEND'			=> 'Email on each new post',
	'ACP_PRIMENOTIFY_ALWAYS_SEND_EXPLAIN'	=> 'Send a notification email for each new post made in the subscribed topic or forum. Normally a notification email is sent only for the first new post since the user’s last visit to the topic.',
	'ACP_PRIMENOTIFY_TRUNCATE'				=> 'Limit Message Length',
	'ACP_PRIMENOTIFY_TRUNCATE_EXPLAIN'		=> 'Truncates messages longer than the specified character limit. Set to 0 for no limit.',
));
