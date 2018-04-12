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
	'PRIMENOTIFY_QUOTE_FROM'				=> "Quote from $1:\n$2",	// $1, $2: Regular expression replacement variables
	'PRIMENOTIFY_QUOTE'						=> "Quote:\n$1",			// $1: Regular expression replacement variable
	'PRIMENOTIFY_CODE'						=> "Code:\n$1",				// $1: Regular expression replacement variable
	'PRIMENOTIFY_IMAGE'						=> 'Image: $1',				// $1: Regular expression replacement variable
	'PRIMENOTIFY_SPOILER_REMOVED'			=> '-- Spoiler Removed --',	// Replaces the content of a spoiler tag

	// User Settings
	'UCP_PRIMENOTIFY_TITLE'					=> 'Notification Emails',
	'UCP_PRIMENOTIFY_ENABLE_POST'			=> 'Include post in email',
	'UCP_PRIMENOTIFY_ENABLE_POST_EXPLAIN'	=> 'Include the post’s content in the notification email.',
	'UCP_PRIMENOTIFY_ENABLE_PM'				=> 'Include private message in email',
	'UCP_PRIMENOTIFY_ENABLE_PM_EXPLAIN'		=> 'Include the private message’s content in the notification email.',
	'UCP_PRIMENOTIFY_KEEP_BBCODES'			=> 'Keep BBCodes',
	'UCP_PRIMENOTIFY_KEEP_BBCODES_EXPLAIN'	=> 'Emails are sent as plain text so BBCodes don’t get converted to formatting. Keeping them may help to show the intended formatting while removing them may make the message look cleaner.',
	'UCP_PRIMENOTIFY_ALWAYS_SEND'			=> 'Always notify',
	'UCP_PRIMENOTIFY_ALWAYS_SEND_EXPLAIN'	=> 'Send a notification email for each new post made in the subscribed topic or forum. Normally a notification email is sent only for the first new post since your last visit to the topic.',
));
