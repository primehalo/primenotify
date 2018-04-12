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
	private static $conversion_table = array(
				'primehalo.primenotify.notification.type.pm'				=> 'notification.type.pm',
				'primehalo.primenotify.notification.type.post'				=> 'notification.type.post',
				'primehalo.primenotify.notification.type.topic'				=> 'notification.type.topic',
			);
	private $db = null;
	private $phpbb_notifications = null;

    public function is_enableable()
    {
        $config = $this->container->get('config');
        return phpbb_version_compare($config['version'], '3.2.1', '>=');
    }

	/**
	* Enable our notifications, disable the original notifications that we are
	* replacing, and change user notification types to ours.
	*
	* @param mixed $old_state State returned by previous call of this method
	* @return mixed Returns false after last step, otherwise temporary state
	* @access public
	*/
    public function enable_step($old_state)
	{
        if ($old_state === false)
        {
			foreach (self::$conversion_table as $new => $old)
			{
				$this->update_notification_type($new, $old);
			}

			// Purge the cache to make sure our notification types show up on the UCP instead of the original types
			$cache = $this->container->get('cache');
			$cache->purge();

			return 'cache_purged';
        }

        return parent::enable_step($old_state);
	}

	/**
	 * Disable our notifications, enable the original notifications that we
	 * replaced, and change user notification types back to the originals.
	 *
	 * @param mixed $old_state State returned by previous call of this method
	 * @return mixed Returns false after last step, otherwise temporary state
	 * @access public
	 */
	public function disable_step($old_state)
	{
        if ($old_state === false)
        {
			foreach (self::$conversion_table as $new => $old)
			{
				$this->update_notification_type($old, $new);
			}

			$cache = $this->container->get('cache');
			$cache->purge();

			return 'cache_purged';
        }

        return parent::disable_step($old_state);
	}

	/**
	* Enables and disables corresponding notification types.
	*
	* @param string $enable		Name of the notification to enable
	* @param string $disable	Name of the notification to disable
	* @return nothing
	* @access private
	*/
	private function update_notification_type($enable, $disable)
	{
		if (!$this->phpbb_notifications)
		{
			$this->phpbb_notifications = $this->container->get('notification_manager');
		}
		if (!$this->db)
		{
			$this->db = $this->container->get('dbal.conn');
		}

		// Enable our notifications
		$this->phpbb_notifications->enable_notifications($enable);

		// Disable original notifications
		$this->phpbb_notifications->disable_notifications($disable);

		// Change user's original notifications to ours
		$data = array('item_type' => $enable);
		$sql = 'UPDATE ' . USER_NOTIFICATIONS_TABLE . ' SET ' . $this->db->sql_build_array('UPDATE', $data) .
				 " WHERE item_type = '{$disable}' AND method = 'notification.method.email'";
		$this->db->sql_query($sql);
	}
}
