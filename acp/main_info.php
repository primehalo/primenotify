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

/**
* Prime Notify ACP module info.
*/
class main_info
{
	public function module()
	{
		return array(
			'filename'	=> '\primehalo\primenotify\acp\main_module',
			'title'		=> 'ACP_PRIMENOTIFY_TITLE',
			'modes'		=> array(
				'settings'	=> array(
					'title'	=> 'ACP_PRIMENOTIFY_SETTINGS',
					'auth'	=> 'ext_primehalo/primenotify && acl_a_board',
					'cat'	=> array('ACP_PRIMENOTIFY_TITLE')
				),
			),
		);
	}
}
