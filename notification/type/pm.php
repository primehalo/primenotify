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
* Private message notifications class
* This class handles notifications for private messages
*/

class pm extends \phpbb\notification\type\pm
{
	/**
	* Get notification type name
	*
	* @return string
	*/
	public function get_type()
	{
//-- mod: Prime Notify ------------------------------------------------------//
		return 'primehalo.primenotify.notification.type.pm';
//-- end: Prime Notify ------------------------------------------------------//
//-- rem:		return '.notification.type.pm';
	}

	/**
	* Get email template
	*
	* @return string|bool
	*/
	public function get_email_template()
	{
		return '@primehalo_primenotify/privmsg_notify';
	}

	/**
	* Get email template variables
	*
	* @return array
	*/
	public function get_email_template_variables()
	{
//-- mod: Prime Notify ------------------------------------------------------//
		$template_vars = parent::get_email_template_variables();
		$template_vars['MESSAGE'] = htmlspecialchars_decode(censor_text($this->get_data('prime_notify_text')));
		return $template_vars;
//-- end: Prime Notify ------------------------------------------------------//
	}

	/**
	* {@inheritdoc}
	*/
	public function create_insert_array($pm, $pre_create_data = array())
	{
//-- mod: Prime Notify ------------------------------------------------------//
		$prime_notify = \primehalo\primenotify\core\prime_notify::Instance();
		$user_data = $this->user_loader->get_user($this->user_id);
		$this->set_data('prime_notify_text', $prime_notify->get_processed_text($pm, $user_data));
//-- end: Prime Notify ------------------------------------------------------//
		parent::create_insert_array($pm, $pre_create_data);
	}
}
