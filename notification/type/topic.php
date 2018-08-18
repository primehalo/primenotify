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
	* Get email template
	*
	* @return string|bool
	*/
	public function get_email_template()
	{
		return '@primehalo_primenotify/newtopic_notify';
	}

	/**
	* Get email template variables
	*
	* @return array
	*/
	public function get_email_template_variables()
	{
		$prime_notify = \primehalo\primenotify\core\prime_notify::Instance();
		$template_vars = parent::get_email_template_variables();
		$template_vars['MESSAGE'] = htmlspecialchars_decode(censor_text($this->get_data('prime_notify_text')));
		$template_vars['S_VISIT_MSG'] = !$prime_notify->is_enabled('always_send', $this->user_loader->get_user($this->user_id));
		return $template_vars;
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
	}
}
