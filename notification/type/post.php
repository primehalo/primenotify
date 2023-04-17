<?php
/**
*
* Prime Notify extension for the phpBB Forum Software package.
*
* @copyright (c) 2018 Ken F. Innes IV <https://www.absoluteanime.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace primehalo\primenotify\notification\type;

/**
* Post notifications class
* This class handles notifications for replies to a topic
*/

class post extends \phpbb\notification\type\post
{
	/** @var \primehalo\primenotify\core\prime_notify */
	protected $prime_notify;
	public function set_prime_notify(\primehalo\primenotify\core\prime_notify $prime_notify)
	{
		$this->prime_notify = $prime_notify;
	}

	/**
	* Get notification type name
	*
	* @return string
	*/
	public function get_type()
	{
		return 'primehalo.primenotify.notification.type.post';
	}

	/**
	* Find the users who want to receive notifications
	*
	* @param array $post Data from submit_post
	* @param array $options Options for finding users for notification
	*
	* @return array
	*/
	public function find_users_for_notification($post, $options = array())
	{
		$options = array_merge(array(
			'ignore_users'		=> array(),
		), $options);

		$users = array();

		$sql = 'SELECT user_id
			FROM ' . TOPICS_WATCH_TABLE . '
			WHERE topic_id = ' . (int) $post['topic_id'] . '
				AND notify_status = ' . NOTIFY_YES . '
				AND user_id <> ' . (int) $post['poster_id'];

		// Alter query to check for Always-Send preference, almost everything else is the same as the original function.
		$this->prime_notify->alter_post_sql($sql, $post);

		$result = $this->db->sql_query($sql);
		while ($row = $this->db->sql_fetchrow($result))
		{
			$users[] = (int) $row['user_id'];
		}
		$this->db->sql_freeresult($result);

		$notify_users = $this->get_authorised_recipients($users, $post['forum_id'], $options, true);

		if (empty($notify_users))
		{
			return array();
		}

		// Try to find the users who already have been notified about replies and have not read the topic since and just update their notifications
		$notified_users = $this->notification_manager->get_notified_users($this->get_type(), array(
			'item_parent_id'	=> static::get_item_parent_id($post),
			'read'				=> 0,
		));

		$this->user_loader->load_users(array_keys($notified_users)); // Load user data so should_always_send() knows their user_primenotify_always_send setting

		foreach ($notified_users as $user => $notification_data)
		{
			if (!$this->prime_notify->should_always_send($user))	// Note: $user is actually just a user_id... very misleading
			{
				unset($notify_users[$user]);
			}

			/** @var post $notification */
			$notification = $this->notification_manager->get_item_type_class($this->get_type(), $notification_data);
			$update_responders = $notification->add_responders($post);
			if (!empty($update_responders))
			{
				$this->notification_manager->update_notification($notification, $update_responders, array(
					'item_parent_id'	=> self::get_item_parent_id($post),
					'read'				=> 0,
					'user_id'			=> $user,
				));
			}
		}
		return $notify_users;
	}

	/**
	* Get email template
	*
	* @return string|bool
	*/
	public function get_email_template()
	{
		return '@primehalo_primenotify/topic_notify';
	}

	/**
	* Get email template variables
	*
	* @return array
	*/
	public function get_email_template_variables()
	{
		$template_vars = parent::get_email_template_variables();
		$msg = utf8_decode_ncr(censor_text($this->get_data('prime_notify_text')));
		$template_vars['MESSAGE'] = htmlspecialchars_decode($msg);
		$template_vars['S_VISIT_MSG'] = !$this->prime_notify->is_enabled('always_send', $this->user_loader->get_user($this->user_id));
		return $template_vars;
	}

	/**
	* {@inheritdoc}
	*/
	public function create_insert_array($post, $pre_create_data = array())
	{
		$user_data = $this->user_loader->get_user($this->user_id);	// Could also probably get user_id from $this->__get('user_id')
		$this->set_data('prime_notify_text', $this->prime_notify->get_processed_text($post, $user_data)); // So it can be retrieved via get_data() in get_email_template_variables()

		parent::create_insert_array($post, $pre_create_data);
	}
}
