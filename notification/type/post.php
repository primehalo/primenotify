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
	/**
	* Get notification type name
	*
	* @return string
	*/
	public function get_type()
	{
//-- mod: Prime Notify ------------------------------------------------------//
		return 'primehalo.primenotify.notification.type.post';
//-- mod: Prime Notify ------------------------------------------------------//
//-- rem:		return 'notification.type.post';
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
//-- mod: Prime Notify ------------------------------------------------------//
		$prime_notify = \primehalo\primenotify\core\prime_notify::Instance();
		$prime_notify->alter_post_sql($sql, $post);
//-- end: Prime Notify ------------------------------------------------------//
		$result = $this->db->sql_query($sql);
		while ($row = $this->db->sql_fetchrow($result))
		{
			$users[] = (int) $row['user_id'];
		}
		$this->db->sql_freeresult($result);

		$sql = 'SELECT user_id
			FROM ' . FORUMS_WATCH_TABLE . '
			WHERE forum_id = ' . (int) $post['forum_id'] . '
				AND notify_status = ' . NOTIFY_YES . '
				AND user_id <> ' . (int) $post['poster_id'];
//-- mod: Prime Notify ------------------------------------------------------//
		$prime_notify->alter_post_sql($sql, $post);
//-- end: Prime Notify ------------------------------------------------------//
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
//-- mod: Prime Notify ------------------------------------------------------//
//		$prime_notify->keep_users_to_notify($notified_users);
		$this->user_loader->load_users(array_keys($notified_users)); // Load user data so should_always_send() knows their user_primenotify_always_send setting
//-- end: Prime Notify ------------------------------------------------------//

		foreach ($notified_users as $user => $notification_data)
		{
//-- mod: Prime Notify ------------------------------------------------------//
			if (!$prime_notify->should_always_send($user))	// Note: $user is actually just a user_id... very misleading
			{
				unset($notify_users[$user]);
			}
//-- end: Prime Notify ------------------------------------------------------//
//-- rem:			unset($notify_users[$user]);

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
	* Get email template variables
	*
	* @return array
	*/
	public function get_email_template_variables()
	{
		$template_vars = parent::get_email_template_variables();
		$template_vars['MESSAGE'] = htmlspecialchars_decode(censor_text($this->get_data('prime_notify_text')));
		return $template_vars;

		/*
		if ($this->get_data('post_username'))
		{
			$username = $this->get_data('post_username');
		}
		else
		{
			$username = $this->user_loader->get_username($this->get_data('poster_id'), 'username');
		}

		return array(
			'AUTHOR_NAME'				=> htmlspecialchars_decode($username),
			'POST_SUBJECT'				=> htmlspecialchars_decode(censor_text($this->get_data('post_subject'))),
			'TOPIC_TITLE'				=> htmlspecialchars_decode(censor_text($this->get_data('topic_title'))),
//-- mod: Prime Notify ------------------------------------------------------//
			'MESSAGE'					=> htmlspecialchars_decode(censor_text($this->get_data('prime_notify_text'))),
//-- end: Prime Notify ------------------------------------------------------//

			'U_VIEW_POST'				=> generate_board_url() . "/viewtopic.{$this->php_ext}?p={$this->item_id}#p{$this->item_id}",
			'U_NEWEST_POST'				=> generate_board_url() . "/viewtopic.{$this->php_ext}?f={$this->get_data('forum_id')}&t={$this->item_parent_id}&e=1&view=unread#unread",
			'U_TOPIC'					=> generate_board_url() . "/viewtopic.{$this->php_ext}?f={$this->get_data('forum_id')}&t={$this->item_parent_id}",
			'U_VIEW_TOPIC'				=> generate_board_url() . "/viewtopic.{$this->php_ext}?f={$this->get_data('forum_id')}&t={$this->item_parent_id}",
			'U_FORUM'					=> generate_board_url() . "/viewforum.{$this->php_ext}?f={$this->get_data('forum_id')}",
			'U_STOP_WATCHING_TOPIC'		=> generate_board_url() . "/viewtopic.{$this->php_ext}?uid={$this->user_id}&f={$this->get_data('forum_id')}&t={$this->item_parent_id}&unwatch=topic",
		);
		*/
	}

	/**
	* {@inheritdoc}
	*/
	public function create_insert_array($post, $pre_create_data = array())
	{
		$prime_notify = \primehalo\primenotify\core\prime_notify::Instance();
		$user_data = $this->user_loader->get_user($this->user_id);	// Could also probably get user_id from $this->__get('user_id')
		$this->set_data('prime_notify_text', $prime_notify->get_processed_text($post, $user_data)); // So it can be retrieved via get_data() in get_email_template_variables()
		parent::create_insert_array($post, $pre_create_data);

		/*
		$this->set_data('poster_id', $post['poster_id']);

		$this->set_data('topic_title', $post['topic_title']);

		$this->set_data('post_subject', $post['post_subject']);

		$this->set_data('post_username', (($post['poster_id'] == ANONYMOUS) ? $post['post_username'] : ''));

		$this->set_data('forum_id', $post['forum_id']);

		$this->set_data('forum_name', $post['forum_name']);

//-- mod: Prime Notify ------------------------------------------------------//
		$prime_notify = \primehalo\primenotify\core\prime_notify::Instance();
		$user_data = $this->user_loader->get_user($this->user_id);	// Could also probably get user_id from $this->__get('user_id')
		$this->set_data('prime_notify_text', $prime_notify->get_processed_text($post, $user_data)); // So it can be retrieved via get_data() in get_email_template_variables()
//-- end: Prime Notify ------------------------------------------------------//

		$this->notification_time = $post['post_time'];

		// Topics can be "read" before they are public (while awaiting approval).
		// Make sure that if the user has read the topic, it's marked as read in the notification
		if ($this->inherit_read_status && isset($pre_create_data[$this->user_id]) && $pre_create_data[$this->user_id] >= $this->notification_time)
		{
			$this->notification_read = true;
		}
		parent::create_insert_array($post, $pre_create_data);
		*/
	}
}
