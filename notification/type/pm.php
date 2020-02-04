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
		return 'primehalo.primenotify.notification.type.pm';
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
		$template_vars = parent::get_email_template_variables();
		$msg = utf8_decode_ncr(censor_text($this->get_data('prime_notify_text')));
		$template_vars['MESSAGE'] = htmlspecialchars_decode($msg);
		return $template_vars;
	}

	/**
	* {@inheritdoc}
	*/
	public function create_insert_array($pm, $pre_create_data = array())
	{
		$user_data = $this->user_loader->get_user($this->user_id);
		$this->set_data('prime_notify_text', $this->prime_notify->get_processed_text($pm, $user_data));

		parent::create_insert_array($pm, $pre_create_data);
	}
}
