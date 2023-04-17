<?php
/**
*
* Prime Notify extension for the phpBB Forum Software package.
*
* @copyright (c) 2018 Ken F. Innes IV <https://www.absoluteanime.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace primehalo\primenotify\migrations;

use primehalo\primenotify\core\prime_notify;

class version_100 extends \phpbb\db\migration\migration
{
	/**
	*
	*/
	public function effectively_installed()
	{
		return isset($this->config['primenotify_enable_post']);
	}

	/**
	* Assign migration file dependencies for this migration
	*
	* @return array Array of migration files
	* @static
	* @access public
	*/
	static public function depends_on()
	{
		return array('\phpbb\db\migration\data\v32x\v321');
	}

	/**
	* Add the Prime Notify columns to the users table:
	*    USERS_TABLE:
	*        user_primenotify_enable_post
	*        user_primenotify_enable_pm
	*        user_primenotify_keep_bbcodes
	*        user_primenotify_always_send
	*
	* @return array Array of table schema
	* @access public
	*/
	public function update_schema()
	{
		return array(
			'add_columns'	=> array(
				USERS_TABLE	=> array(
					'user_primenotify_enable_post'	=> array('BOOL', 1),
					'user_primenotify_enable_pm'	=> array('BOOL', 1),
					'user_primenotify_keep_bbcodes'	=> array('BOOL', 1),
					'user_primenotify_always_send'	=> array('BOOL', 1),
				),
			),
		);
	}

	/**
	* Drop the Prime Notify columns from the users table
	*
	* @return array Array of table schema
	* @access public
	*/
	public function revert_schema()
	{
		return array(
			'drop_columns'	=> array(
				USERS_TABLE	=> array(
					'user_primenotify_enable_post',
					'user_primenotify_enable_pm',
					'user_primenotify_keep_bbcodes',
					'user_primenotify_always_send',
				),
			),
		);
	}

	/**
	* Add or update data in the database
	*
	* @return array Array of table data
	* @access public
	*/
	public function update_data()
	{
		return array(
			// ACP Settings
			array('config.add', array('primenotify_enable_post', prime_notify::ENABLED)),
			array('config.add', array('primenotify_enable_pm', prime_notify::ENABLED)),
			array('config.add', array('primenotify_keep_bbcodes', prime_notify::ENABLED)),
			array('config.add', array('primenotify_always_send', prime_notify::ENABLED)),
			array('config.add', array('primenotify_truncate', 0)),

			// Add a parent module (ACP_PRIMENOTIFY_TITLE) to the Extensions tab (ACP_CAT_DOT_MODS)
			array('module.add', array(
				'acp',
				'ACP_CAT_DOT_MODS',
				'ACP_PRIMENOTIFY_TITLE'
			)),

			// Add our main_module to the parent module (ACP_PRIMENOTIFY_TITLE)
			array('module.add', array(
				'acp',
				'ACP_PRIMENOTIFY_TITLE',
				array(
					'module_basename'	=> '\primehalo\primenotify\acp\main_module',
					'modes' 			=> array('settings'),
				),
			)),
		);
	}
}
