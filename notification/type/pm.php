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
		/*
		$user_data = $this->user_loader->get_user($this->get_data('from_user_id'));

		return array(
			'AUTHOR_NAME'				=> htmlspecialchars_decode($user_data['username']),
			'SUBJECT'					=> htmlspecialchars_decode(censor_text($this->get_data('message_subject'))),
//-- mod: Prime Notify ------------------------------------------------------//
			'MESSAGE'					=> htmlspecialchars_decode(censor_text($this->get_data('prime_notify_text'))),
//-- end: Prime Notify ------------------------------------------------------//

			'U_VIEW_MESSAGE'			=> generate_board_url() . '/ucp.' . $this->php_ext . "?i=pm&mode=view&p={$this->item_id}",
		);
		*/
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
		/*
		$this->set_data('from_user_id', $pm['from_user_id']);

		$this->set_data('message_subject', $pm['message_subject']);

//-- mod: Prime Notify ------------------------------------------------------//
		$prime_notify = \primehalo\primenotify\core\prime_notify::Instance();
		$this->set_data('prime_notify_text', $prime_notify->get_processed_text($pm, $this->user_loader->get_user($this->user_id)));
//-- end: Prime Notify ------------------------------------------------------//
		*/
		parent::create_insert_array($pm, $pre_create_data);
	}
}
