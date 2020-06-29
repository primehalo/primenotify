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
	'ACP_PRIMENOTIFY_TITLE'					=> 'Prime уведомления',
	'ACP_PRIMENOTIFY_SETTINGS'				=> 'Настройки уведомлений',
	'ACP_PRIMENOTIFY_BASIC_SETTINGS'		=> 'Основные настройки',
	'ACP_PRIMENOTIFY_SETTINGS_SAVED'		=> 'Настройки Prime уведомлений успешно обновлены!',
	'ACP_PRIMENOTIFY_SETTINGS_LOG'			=> '<strong>Изменены настройки Prime уведомлений</strong>',
	'ACP_PRIMENOTIFY_USER_CHOICE'			=> 'Выбор пользователя',
	'ACP_PRIMENOTIFY_USER_CHOICE_MSG'		=> 'User’s Choice options will be shown in the <strong>Edit global settings</strong> section of the <strong>Board preferences</strong> tab on the <strong>User Control Panel</strong>, when enabled.',

	// Settings
	'ACP_PRIMENOTIFY_ENABLE_POST'			=> 'Разрешить для сообщений',
	'ACP_PRIMENOTIFY_ENABLE_POST_EXPLAIN'	=> 'Включать текст сообщения в email-уведомление.',
	'ACP_PRIMENOTIFY_ENABLE_PM'				=> 'Разрешить для личных сообщений',
	'ACP_PRIMENOTIFY_ENABLE_PM_EXPLAIN'		=> 'Включать текст личного сообщения в email-уведомление.',
	'ACP_PRIMENOTIFY_KEEP_BBCODES'			=> 'Сохранять BBCode',
	'ACP_PRIMENOTIFY_KEEP_BBCODES_EXPLAIN'	=> 'Email-сообщения отправляются как простой текст, и BBCode не могут их отформатировать. Сохраненный BBCode поможет понять оригинальное форматирование сообщения, удаленный облегчит чтение сообщения.',
	'ACP_PRIMENOTIFY_ALWAYS_SEND'			=> 'Email-уведомление для каждого нового сообщения',
	'ACP_PRIMENOTIFY_ALWAYS_SEND_EXPLAIN'	=> 'Без данной настройки email-уведомление отправляется только для первого сообщения с момента последнего посещения пользователем темы.',
	'ACP_PRIMENOTIFY_TRUNCATE'				=> 'Максимальное количество символов в сообщениях',
	'ACP_PRIMENOTIFY_TRUNCATE_EXPLAIN'		=> 'Укорачивает сообщения до указанной длины. Введите 0 для снятия ограничений.',
));
