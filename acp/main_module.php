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

use primehalo\primenotify\core\prime_notify;

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
		$request	= $phpbb_container->get('request');		// @var \phpbb\request\request
		$user		= $phpbb_container->get('user');		// @var \phpbb\user
		$template	= $phpbb_container->get('template');	// @var \phpbb\template\template
		$defaults	= array(
			'enable_post'	=> prime_notify::ENABLED,
			'enable_pm'		=> prime_notify::ENABLED,
			'keep_bbcodes'	=> prime_notify::ENABLED,
			'always_send'	=> prime_notify::ENABLED,
			'truncate'		=> 0,
		);

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

			$validated = array(
				'enable_post'	=> $request->variable('primenotify_enable_post', $defaults['enable_post']),
				'enable_pm'		=> $request->variable('primenotify_enable_pm', $defaults['enable_pm']),
				'keep_bbcodes'	=> $request->variable('primenotify_keep_bbcodes', $defaults['keep_bbcodes']),
				'always_send'	=> $request->variable('primenotify_always_send', $defaults['always_send']),
				'truncate'		=> $request->variable('primenotify_truncate', $defaults['truncate']),
			);

			// Validate and correct
			foreach ($validated as $k => $v)
			{
				$invalid = false;
				if ($k === 'enable_post' || $k === 'enable_pm' || $k === 'keep_bbcodes' || $k === 'always_send')
				{
					$invalid = !in_array($validated[$k], array(prime_notify::ENABLED, prime_notify::DISABLED, prime_notify::USER_CHOICE));
				}
				else if ($k === 'truncate')
				{
					$invalid = !($validated[$k] >= 0);
				}
				$validated[$k] = $invalid ? $defaults[$k] : $validated[$k];
			}

			// Set the options
			$config->set('primenotify_enable_post', $validated['enable_post']);
			$config->set('primenotify_enable_pm', $validated['enable_pm']);
			$config->set('primenotify_keep_bbcodes', $validated['keep_bbcodes']);
			$config->set('primenotify_always_send', $validated['always_send']);
			$config->set('primenotify_truncate', $validated['truncate']);

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
			'PRIMENOTIFY_TRUNCATE'		=> $config['primenotify_truncate'],
			'U_ACTION'					=> $this->u_action,
		));
	}
}
