<?php
/**
*
* Prime Notify extension for the phpBB Forum Software package.
*
* @copyright (c) 2018 Ken F. Innes IV <https://www.absoluteanime.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace primehalo\primenotify\acp;

use \primehalo\primenotify\core\prime_notify;

/**
* Prime Notify ACP module.
*/
class main_module
{
	public $page_title;
	public $tpl_name;
	public $u_action;

	public function main($id, $mode)
	{
		global $phpbb_container;

		$config		= $phpbb_container->get('config');		// @var \phpbb\config\config
		$db			= $phpbb_container->get('dbal.conn');	// @var \phpbb\db\driver\driver_interface
		$request	= $phpbb_container->get('request');		// @var \phpbb\request\request
		$user		= $phpbb_container->get('user');		// @var \phpbb\user
		$template	= $phpbb_container->get('template');	// @var \phpbb\template\template

		$this->tpl_name = 'acp_primenotify_body';
		$this->page_title = $user->lang('ACP_PRIMENOTIFY_TITLE');
		add_form_key('primenotify_settings');

		// The form was submitted
		if ($request->is_set_post('submit'))
		{
			// Ensure the form submission is valid
			if (!check_form_key('primenotify_settings'))
			{
				 trigger_error('FORM_INVALID');
			}

			// Set the options
			$config->set('primenotify_enable_post', $request->variable('primenotify_enable_post', prime_notify::ENABLED));
			$config->set('primenotify_enable_pm', $request->variable('primenotify_enable_pm', prime_notify::ENABLED));
			$config->set('primenotify_keep_bbcodes', $request->variable('primenotify_keep_bbcodes', prime_notify::ENABLED));
			$config->set('primenotify_always_send', $request->variable('primenotify_always_send', prime_notify::ENABLED));

			$log = $phpbb_container->get('log');
			$log->add('admin', $user->data['user_id'], $user->ip, 'ACP_PRIMENOTIFY_SETTINGS_LOG');

			trigger_error($user->lang('ACP_PRIMENOTIFY_SETTINGS_SAVED') . adm_back_link($this->u_action));
		}

		$template->assign_vars(array(
			'PRIMENOTIFY_ENABLED'		=> prime_notify::ENABLED,
			'PRIMENOTIFY_DISABLED'		=> prime_notify::DISABLED,
			'PRIMENOTIFY_USER_CHOICE'	=> prime_notify::USER_CHOICE,
			'PRIMENOTIFY_ENABLE_POST'	=> $config['primenotify_enable_post'],
			'PRIMENOTIFY_ENABLE_PM'		=> $config['primenotify_enable_pm'],
			'PRIMENOTIFY_KEEP_BBCODES'	=> $config['primenotify_keep_bbcodes'],
			'PRIMENOTIFY_ALWAYS_SEND'	=> $config['primenotify_always_send'],
			'U_ACTION'					=> $this->u_action,
		));
	}
}
