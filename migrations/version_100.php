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

use \primehalo\primenotify\core\prime_notify;

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
					'user_primenotify_enable_post'	=> array('BOOL', true),
					'user_primenotify_enable_pm'	=> array('BOOL', true),
					'user_primenotify_keep_bbcodes'	=> array('BOOL', true),
					'user_primenotify_always_send'	=> array('BOOL', true),
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

			// Change notification types to our types
			#array('custom', array(array($this, 'update_notification_types'))),
		);
	}

	/**
	*
	*/
	/*
	public function revert_data()
	{
    	return array(
			array('custom', array(array($this, 'revert_notification_types'))),
    	);
	}
	*/

	/**
	*
	*/
	/*
	public function update_notification_types()
	{
		// Notification Types
		$data = array('notification_type_name' => 'primehalo.primenotify.notification.type.post');
		$sql = 'UPDATE ' . NOTIFICATION_TYPES_TABLE . ' SET ' . $this->db->sql_build_array('UPDATE', $data) .
				 " WHERE notification_type_name = 'notification.type.post'";
		$this->sql_query($sql);

		$data = array('notification_type_name' => 'primehalo.primenotify.notification.type.topic');
		$sql = 'UPDATE ' . NOTIFICATION_TYPES_TABLE . ' SET ' . $this->db->sql_build_array('UPDATE', $data) .
				 " WHERE notification_type_name = 'notification.type.topic'";
		$this->sql_query($sql);

		$data = array('notification_type_name' => 'primehalo.primenotify.notification.type.pm');
		$sql = 'UPDATE ' . NOTIFICATION_TYPES_TABLE . ' SET ' . $this->db->sql_build_array('UPDATE', $data) .
				 " WHERE notification_type_name = 'notification.type.pm'";
		$this->sql_query($sql);

		// User Notification Types
		$data = array('item_type' => 'primehalo.primenotify.notification.type.post');
		$sql = 'UPDATE ' . USER_NOTIFICATIONS_TABLE . ' SET ' . $this->db->sql_build_array('UPDATE', $data) .
				 " WHERE item_type = 'notification.type.post' AND method = 'notification.method.email'";
		$this->sql_query($sql);

		$data = array('item_type' => 'primehalo.primenotify.notification.type.topic');
		$sql = 'UPDATE ' . USER_NOTIFICATIONS_TABLE . ' SET ' . $this->db->sql_build_array('UPDATE', $data) .
				 " WHERE item_type = 'notification.type.topic' AND method = 'notification.method.email'";
		$this->sql_query($sql);

		$data = array('item_type' => 'primehalo.primenotify.notification.type.pm');
		$sql = 'UPDATE ' . USER_NOTIFICATIONS_TABLE . ' SET ' . $this->db->sql_build_array('UPDATE', $data) .
				 " WHERE item_type = 'notification.type.pm' AND method = 'notification.method.email'";
		$this->sql_query($sql);
	}
	*/

	/**
	*
	*/
	/*
	public function revert_notification_types()
	{
		// Notification Types
		$data = array('notification_type_name' => 'notification.type.post');
		$sql = 'UPDATE ' . NOTIFICATION_TYPES_TABLE . ' SET ' . $this->db->sql_build_array('UPDATE', $data) .
				 " WHERE notification_type_name = 'primehalo.primenotify.notification.type.post'";
		$this->sql_query($sql);

		$data = array('notification_type_name' => 'notification.type.topic');
		$sql = 'UPDATE ' . NOTIFICATION_TYPES_TABLE . ' SET ' . $this->db->sql_build_array('UPDATE', $data) .
				 " WHERE notification_type_name = 'primehalo.primenotify.notification.type.topic'";
		$this->sql_query($sql);

		$data = array('notification_type_name' => 'notification.type.pm');
		$sql = 'UPDATE ' . NOTIFICATION_TYPES_TABLE . ' SET ' . $this->db->sql_build_array('UPDATE', $data) .
				 " WHERE notification_type_name = 'primehalo.primenotify.notification.type.pm'";
		$this->sql_query($sql);

		// User Notification Types
		$data = array('item_type' => 'notification.type.post');
		$sql = 'UPDATE ' . USER_NOTIFICATIONS_TABLE . ' SET ' . $this->db->sql_build_array('UPDATE', $data) .
				 " WHERE item_type = 'primehalo.primenotify.notification.type.post' AND method = 'notification.method.email'";
		$this->sql_query($sql);

		$data = array('item_type' => 'notification.type.topic');
		$sql = 'UPDATE ' . USER_NOTIFICATIONS_TABLE . ' SET ' . $this->db->sql_build_array('UPDATE', $data) .
				 " WHERE item_type = 'primehalo.primenotify.notification.type.topic' AND method = 'notification.method.email'";
		$this->sql_query($sql);

		$data = array('item_type' => 'notification.type.pm');
		$sql = 'UPDATE ' . USER_NOTIFICATIONS_TABLE . ' SET ' . $this->db->sql_build_array('UPDATE', $data) .
				 " WHERE item_type = 'primehalo.primenotify.notification.type.pm' AND method = 'notification.method.email'";
		$this->sql_query($sql);
	}
	*/
}
