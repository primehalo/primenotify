<?php
/**
*
* Prime Notify extension for the phpBB Forum Software package.
*
* @copyright (c) 2018 Ken F. Innes IV <https://www.absoluteanime.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace primehalo\primenotify;

class ext extends \phpbb\extension\base
{
	private static $notification_types = array(
				'primehalo.primenotify.notification.type.pm'	=> 'notification.type.pm',
				'primehalo.primenotify.notification.type.post'	=> 'notification.type.post',
				'primehalo.primenotify.notification.type.topic'	=> 'notification.type.topic',
				'primehalo.primenotify.notification.type.forum'	=> 'notification.type.forum',
			);
	private $db = null;

	public function is_enableable()
	{
		$config = $this->container->get('config');
		return phpbb_version_compare($config['version'], '3.3.2', '>=');
	}

	/**
	* When enabling this extension we want to make a primenotify copy of each
	* equivalent notification type so that it will be enabled or disabled
	* according to the user's current notification preferences.
	* If we did not do this, the user's notification settings would not be
	* enabled until they specifically enabled them from the UCP.
	*
	* @param mixed $old_state State returned by previous call of this method
	* @return mixed Returns false after last step, otherwise temporary state
	* @access public
	*/
	public function enable_step($old_state)
	{
		if ($old_state === false)
		{
			$this->db = !$this->db ? $this->container->get('dbal.conn') : $this->db;

			// Grab all of our existing custom notifications (there shouldn't be any but we have to make sure otherwise we'll get an SQL error in the next step)
			$sql = 'SELECT item_type, user_id FROM ' . USER_NOTIFICATIONS_TABLE . ' WHERE item_type ' . $this->db->sql_like_expression('primehalo.primenotify.notification.type.' . $this->db->get_any_char());
			$result = $this->db->sql_query($sql);
			while ($row = $this->db->sql_fetchrow($result))
			{
				$user_ids_per_type[$row['item_type']][] = $row['user_id'];
			}
			$this->db->sql_freeresult($result);

			// Now loop through each notification type that has an equivalent primenotify type so that we can make a primenotify copy of it
			$this->db->sql_transaction('begin');
			foreach (self::$notification_types as $our_type => $orig_type)
			{
				// Create our notification type entries for each equivalent default notification type that already exists, copying over the notify status setting
				$sql = 'SELECT user_id, notify FROM ' . USER_NOTIFICATIONS_TABLE . " WHERE item_type = '" . $this->db->sql_escape($orig_type) . "' AND method = 'notification.method.email'";
				$result = $this->db->sql_query($sql);
				if ($result)
				{
					$sql_ary = array();
					while ($row = $this->db->sql_fetchrow($result))
					{
						if (!isset($user_ids_per_type[$our_type][$row['user_id']]))
						{
							$sql_ary[] = array('item_type' => $our_type, 'user_id' => $row['user_id'], 'method' => 'notification.method.email', 'notify' => $row['notify']);
						}
					}
					$this->db->sql_freeresult($result);
					$this->db->sql_multi_insert(USER_NOTIFICATIONS_TABLE, $sql_ary);
				}

			}
			$this->db->sql_transaction('commit');

			// Purge the cache to make sure our notification types show up on the UCP instead of the original types
			$cache = $this->container->get('cache');
			$cache->purge();

			return 'cache_purged';
		}

		return parent::enable_step($old_state);
	}

	/**
	 * When disabling this extension we need to convert existing primenotify notifications
	 * to their phpBB equivalents and delete all primenotify notification types.
	 *
	 * @param mixed $old_state State returned by previous call of this method
	 * @return mixed Returns false after last step, otherwise temporary state
	 * @access public
	 */
	public function disable_step($old_state)
	{
		if ($old_state === false)
		{
			$this->db = !$this->db ? $this->container->get('dbal.conn') : $this->db;

			// Convert all existing custom notifications into default notifications
			// Part 1/4: Get the notification type IDs so we know which notification IDs to find and what to convert them to
			$sql_in = array_merge(array_values(self::$notification_types), array_keys(self::$notification_types));
			$sql = 'SELECT notification_type_id, notification_type_name FROM ' . NOTIFICATION_TYPES_TABLE . ' WHERE ' . $this->db->sql_in_set('notification_type_name', $sql_in);
			$result = $this->db->sql_query($sql);

			// Part 2/4: Organize the notification IDs for easy access, using the type_name as the key and the type_id as the value
			$notification_type_ids = array();
			while ($row = $this->db->sql_fetchrow($result))
			{
				$notification_type_ids[$row['notification_type_name']] = $row['notification_type_id'];
			}
			$this->db->sql_freeresult($result);

			// Part 3/4: Change notification_type_id from our custom one to the equivalent default one
			$this->db->sql_transaction('begin');
			foreach (self::$notification_types as $from => $to)
			{
				if (isset($notification_type_ids[$to]) && isset($notification_type_ids[$from]))
				{
					$sql = 'UPDATE ' . NOTIFICATIONS_TABLE . " SET notification_type_id = " . $this->db->sql_escape($notification_type_ids[$to]) . " WHERE notification_type_id = " . $this->db->sql_escape($notification_type_ids[$from]);
					$this->db->sql_query($sql);
				}
				unset($notification_type_ids[$to]);	// Remove default notification IDs so in the end we'll only be left with our custom notification IDs
			}
			$this->db->sql_transaction('commit');

			// Part 4/4: All notifications should have been converted, but just in case lets try deleting all notifications still containing our custom notification IDs
			if (!empty($notification_type_ids))
			{
				$sql_in = array_values($notification_type_ids); // At this point $notification_type_ids contains only our custom notification IDs
				$sql = 'DELETE FROM ' . NOTIFICATIONS_TABLE .  ' WHERE ' . $this->db->sql_in_set('notification_type_id', $sql_in);
				$this->db->sql_query($sql);
			}

			// Delete our custom notification types from the Notification Types Table
			$sql = 'DELETE FROM ' . NOTIFICATION_TYPES_TABLE . ' WHERE notification_type_name ' . $this->db->sql_like_expression('primehalo.primenotify.notification.type.' . $this->db->get_any_char());
			$this->db->sql_query($sql);

			// Delete our custom notification types from the User Notifications Table
			$sql = 'DELETE FROM ' . USER_NOTIFICATIONS_TABLE . ' WHERE item_type ' . $this->db->sql_like_expression('primehalo.primenotify.notification.type.' . $this->db->get_any_char());
			$this->db->sql_query($sql);

			// Clear the cache
			$cache = $this->container->get('cache');
			$cache->purge();

			return 'cache_purged';
		}

		return parent::disable_step($old_state);
	}
}
