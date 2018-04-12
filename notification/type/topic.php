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
* Topic notifications class
* This class handles notifications for new topics
*/

class topic extends \phpbb\notification\type\topic
{
	/**
	* Get notification type name
	*
	* @return string
	*/
	public function get_type()
	{
//-- mod: Prime Notify ------------------------------------------------------//
		return 'primehalo.primenotify.notification.type.topic';
//-- end: Prime Notify ------------------------------------------------------//
//-- rem:		return 'notification.type.topic';
	}

	/**
	* Find the users who want to receive notifications
	*
	* @param array $topic Data from the topic
	* @param array $options Options for finding users for notification
	*
	* @return array
	*/
	public function find_users_for_notification($topic, $options = array())
	{
		$options = array_merge(array(
			'ignore_users'			=> array(),
		), $options);

		$users = array();

		$sql = 'SELECT user_id
			FROM ' . FORUMS_WATCH_TABLE . '
			WHERE forum_id = ' . (int) $topic['forum_id'] . '
				AND notify_status = ' . NOTIFY_YES . '
				AND user_id <> ' . (int) $topic['poster_id'];
//-- mod: Prime Notify ------------------------------------------------------//
		$prime_notify = \primehalo\primenotify\core\prime_notify::Instance();
		$prime_notify->alter_post_sql($sql, $topic);
//-- end: Prime Notify ------------------------------------------------------//
		$result = $this->db->sql_query($sql);
		while ($row = $this->db->sql_fetchrow($result))
		{
			$users[] = (int) $row['user_id'];
		}
		$this->db->sql_freeresult($result);

		return $this->get_authorised_recipients($users, $topic['forum_id'], $options);
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
		$board_url = generate_board_url();

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
			'FORUM_NAME'				=> htmlspecialchars_decode($this->get_data('forum_name')),
			'TOPIC_TITLE'				=> htmlspecialchars_decode(censor_text($this->get_data('topic_title'))),
//-- mod: Prime Notify ------------------------------------------------------//
			'MESSAGE'					=> htmlspecialchars_decode(censor_text($this->get_data('prime_notify_text'))),
//-- end: Prime Notify ------------------------------------------------------//

			'U_TOPIC'					=> "{$board_url}/viewtopic.{$this->php_ext}?f={$this->item_parent_id}&t={$this->item_id}",
			'U_VIEW_TOPIC'				=> "{$board_url}/viewtopic.{$this->php_ext}?f={$this->item_parent_id}&t={$this->item_id}",
			'U_FORUM'					=> "{$board_url}/viewforum.{$this->php_ext}?f={$this->item_parent_id}",
			'U_STOP_WATCHING_FORUM'		=> "{$board_url}/viewforum.{$this->php_ext}?uid={$this->user_id}&f={$this->item_parent_id}&unwatch=forum",
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

		$this->set_data('post_username', (($post['poster_id'] == ANONYMOUS) ? $post['post_username'] : ''));

		$this->set_data('forum_name', $post['forum_name']);

//-- mod: Prime Notify ------------------------------------------------------//
		$prime_notify = \primehalo\primenotify\core\prime_notify::Instance();
		$this->set_data('prime_notify_text', $prime_notify->get_processed_text($post, $this->user_loader->get_user($this->user_id))); // So it can be retrieved via get_data() in get_email_template_variables()
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
