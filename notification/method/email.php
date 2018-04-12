<?php
/**
*
* Prime Notify extension for the phpBB Forum Software package.
*
* @copyright (c) 2018 Ken F. Innes IV <https://www.absoluteanime.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace primehalo\primenotify\notification\method;

use phpbb\notification\type\type_interface;

/**
* Email notification method class
* This class handles sending emails for notifications
*/

class email extends \phpbb\notification\method\email
{

//-- mod: Prime Notify ------------------------------------------------------//
// The code below comes from the notify_using_messenger() function in messenger_base.php
//-- end: Prime Notify ------------------------------------------------------//
	/**
	* Notify using phpBB messenger
	*
	* @param int $notify_method				Notify method for messenger (e.g. NOTIFY_IM)
	* @param string $template_dir_prefix	Base directory to prepend to the email template name
	*
	* @return null
	*/
	protected function notify_using_messenger($notify_method, $template_dir_prefix = '')
	{
		if (empty($this->queue))
		{
			return;
		}
//-- mod: Prime Notify ------------------------------------------------------//
		$prime_notify = \primehalo\primenotify\core\prime_notify::Instance();
//-- end: Prime Notify ------------------------------------------------------//

		// Load all users we want to notify (we need their email address)
		$user_ids = $users = array();
		foreach ($this->queue as $notification)
		{
			$user_ids[] = $notification->user_id;
		}

		// We do not send emails to banned users
		if (!function_exists('phpbb_get_banned_user_ids'))
		{
			include($this->phpbb_root_path . 'includes/functions_user.' . $this->php_ext);
		}
		$banned_users = phpbb_get_banned_user_ids($user_ids);

		// Load all the users we need
		$this->user_loader->load_users($user_ids);

		// Load the messenger
		if (!class_exists('messenger'))
		{
			include($this->phpbb_root_path . 'includes/functions_messenger.' . $this->php_ext);
		}
		$messenger = new \messenger();

		// Time to go through the queue and send emails
		/** @var \phpbb\notification\type\type_interface $notification */
		foreach ($this->queue as $notification)
		{
			if ($notification->get_email_template() === false)
			{
				continue;
			}

			$user = $this->user_loader->get_user($notification->user_id);

			if ($user['user_type'] == USER_IGNORE || ($user['user_type'] == USER_INACTIVE && $user['user_inactive_reason'] == INACTIVE_MANUAL) || in_array($notification->user_id, $banned_users))
			{
				continue;
			}

//-- mod: Prime Notify ------------------------------------------------------//
			$prime_notify->template($messenger, $notification, $user, $template_dir_prefix);
//-- end: Prime Notify ------------------------------------------------------//
//-- rem:			$messenger->template($notification->get_email_template(), $user['user_lang'], '', $template_dir_prefix);

			$messenger->set_addresses($user);

			$messenger->assign_vars(array_merge(array(
				'USERNAME'						=> $user['username'],

				'U_NOTIFICATION_SETTINGS'		=> generate_board_url() . '/ucp.' . $this->php_ext . '?i=ucp_notifications&mode=notification_options',
			), $notification->get_email_template_variables()));

			$messenger->send($notify_method);
		}

		// Save the queue in the messenger class (has to be called or these emails could be lost?)
		$messenger->save_queue();

		// We're done, empty the queue
		$this->empty_queue();
	}
}
