<?php
/**
*
* Prime Notify extension for the phpBB Forum Software package.
*
* @copyright (c) 2018 Ken F. Innes IV <https://www.absoluteanime.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace primehalo\primenotify\core;

/**
* Class declaration
*/
class prime_notify
{
	/**
	* Constants
	*/
	const DISABLED		= 0;
	const ENABLED		= 1;
	const USER_CHOICE	= 2;

	/**
	* Service Containers
	*/
	private $config;
	private $db;
	private $user;
	private $language;
	private $text_formatter_utils;
	private $user_loader;

	/**
	* Variables
	*/
	private $myconfig	= array();	// Our config settings
	private $raw_msg	= '';		// Message content as it is in the database
	private $orig_msg	= '';		// Message content as the user submitted it
	private $clean_msg	= '';		// Message content removed of all BBCodes

	/**
	* Constructor
	*/
	public function __construct(\phpbb\config\config $config, \phpbb\db\driver\driver_interface $db, \phpbb\language\language $language, \phpbb\textformatter\s9e\utils $text_formatter_utils, \phpbb\user $user, \phpbb\user_loader $user_loader)
	{
		$this->config				= $config;
		$this->db					= $db;
		$this->language				= $language;
		$this->text_formatter_utils	= $text_formatter_utils;
		$this->user					= $user;
		$this->user_loader			= $user_loader;

		$this->myconfig = array(
			'enable_post'	=> $this->config['primenotify_enable_post'],
			'enable_pm'		=> $this->config['primenotify_enable_pm'],
			'keep_bbcodes'	=> $this->config['primenotify_keep_bbcodes'],
			'always_send'	=> $this->config['primenotify_always_send'],
			'truncate'		=> $this->config['primenotify_truncate'],
		);
	}

	/**
	* Format the message for display in a plain-text e-mail
	*
	* @param object $text Text from a post or private message that needs to be formatted for inserting into a plain-text email
	* @param boolean $keep_bbcodes
	* @return string The text formatted for insertion into a plain-text email
	* @access private
	*/
	private function format_for_email($text, $keep_bbcodes = true)
	{
		$this->language->add_lang('common', 'primehalo/primenotify');

		if (!$keep_bbcodes)
		{
			// Remove nested blocks which could make the message look very messy
			$text = $this->text_formatter_utils->remove_bbcode($text, 'spoiler', 2);
			$text = $this->text_formatter_utils->remove_bbcode($text, 'quote', 2);
			$text = $this->text_formatter_utils->remove_bbcode($text, 'code', 2);
		}

		// Return the text from its XML form to its original plain text form
		$text = $this->text_formatter_utils->unparse($text);

		// If there is a spoiler, remove the spoiler content.
		$re = '@\[spoiler(?:=[^]]*)?\]((?!.*\[spoiler\]).*?)\[/spoiler]@s';
		$spoiler_replacement = $this->language->lang('PRIMENOTIFY_SPOILER_REMOVED');
		if (stripos($spoiler_replacement, '[spoiler') === false)	// Ensure replacement text doesn't have a [spoiler] tag or we could get an infinite loop
		{
			while (preg_match($re, $text))
			{
				$text = preg_replace('@\[spoiler(?:=[^]]*)?\]((?!.*\[spoiler\]).*?)\[/spoiler]@s', $this->language->lang('PRIMENOTIFY_SPOILER_REMOVED'), $text);
			}
		}

		if ($keep_bbcodes)
		{
			// Add spaces in BBCode URL tags so email programs won't think they're part of the URL.
			$text = preg_replace('@](http(?::|&#58;)//.*?)\[@', '] $1 [', $text);
		}
		else
		{
			// Change list items bullets (quick & dirty, no checking if we're actually in a list, much less if it's ordered or unordered)
			$text = str_replace('[*]',  $this->language->lang('PRIMENOTIFY_LIST_ITEM'), $text);

			// Change [url=http://www.example.com]Example[/url] to Example (http://www.example.com)
			$text = preg_replace('@\[url=([^]]*)\]([^[]*)\[/url\]@', '$2 ($1)', $text);

			// Change [quote=username ...][/quote] to "Quote from username: "
			$text = preg_replace('@\[quote=([a-zA-Z0-9._-]+)\s*[^]]*\]\s*((?!.*\[quote\]).*?)\[/quote\]@', $this->language->lang('PRIMENOTIFY_QUOTE_FROM'), $text);

			// Change [quote][/quote] to "Quote: "
			$text = preg_replace('@\[quote\]\s*((?!.*\[quote\]).*?)\[/quote\]@', $this->language->lang('PRIMENOTIFY_QUOTE'), $text);

			// Change [code][/code] to "Code: "
			$text = preg_replace('@\[code\]\s*((?!.*\[code\]).*?)\[/code\]@', $this->language->lang('PRIMENOTIFY_CODE'), $text);

			// Change [img][/img] to "Image: "
			$text = preg_replace('@\[img\]\s*(.*[^*]+)\[/img\]@', $this->language->lang('PRIMENOTIFY_IMAGE'), $text);

			// Change [b]text[/b] to *text*
			$text = preg_replace('@\[b\]((?!.*\[b\]).*?)\[/b\]@', '*$1*', $text);

			// Change [i]text[/i] to /text/
			$text = preg_replace('@\[i\]((?!.*\[i\]).*?)\[/i\]@', '/$1/', $text);

			// Change [u]text[/u] to _text_
			$text = preg_replace('@\[u\]((?!.*\[u\]).*?)\[/u\]@', '_$1_', $text);

			// Remove all remaining BBCodes
			//strip_bbcode($text, $uid_param); // This function replaces BBCodes with spaces, which we don't want
			$text = preg_replace("#\[\/?[a-z0-9\*\+\-]+(?:=(?:&quot;.*&quot;|[^\]]*))?(?::[a-z])?\]#", '', $text);
			// In the future we should loop through existing BBCodes to remove them instead of using a blanket RegEx which removes everything that looks like a BBCode
		}
		return $text;
	}

	/**
	* Obtain the text from a post or private message and format it for display inside an email.
	*
	* @param object $data The post or private message data that holds the content
	* @param object $user Recipient user data
	* @return string The message formatted to work inside a plain text email
	* @access public
	*/
	public function get_processed_text($data, $user = array())
	{
		$raw_msg = '';								// The message as it exists in the database
		if (isset($data['post_text']))				// For posts
		{
			$raw_msg = $data['post_text'];
		}
		else if (isset($data['message']))			// For private messages
		{
			$raw_msg = $data['message'];
		}
		if ($raw_msg !== $this->raw_msg)
		{
			$this->raw_msg = $raw_msg;
			if (!empty($this->raw_msg))
			{
				// If this post doesn't have BBCodes enabled then we keep them because they do not represent formatting
				$bbcodes_disabled	= isset($data['enable_bbcode']) && empty($data['enable_bbcode']);
				$this->orig_msg		= $this->format_for_email($this->raw_msg, true);
				$this->clean_msg	= $bbcodes_disabled ? $this->orig_msg : $this->format_for_email($this->raw_msg, false);

				if (!empty($this->myconfig['truncate']) && $this->myconfig['truncate'] > 0)
				{
					$this->orig_msg		= truncate_string($this->orig_msg, $this->myconfig['truncate'], $this->myconfig['truncate'], false, $this->language->lang('ELLIPSIS'));
					$this->clean_msg	= truncate_string($this->clean_msg, $this->myconfig['truncate'], $this->myconfig['truncate'], false, $this->language->lang('ELLIPSIS'));
				}
			}
		}
		$msg = $this->is_enabled('keep_bbcodes', $user) ? $this->orig_msg : $this->clean_msg;

		// Replace Emojis and other 4bit UTF-8 chars not allowed by utf8_bin in MySql to NCR. (code snippet taken from includes/functions_display.php)
		if (function_exists('utf8_encode_ucr')) // Available in phpBB 3.2.9
		{
			$msg = utf8_encode_ucr($msg);
		}
		else if (preg_match_all('/[\x{10000}-\x{10FFFF}]/u', $msg, $matches))
		{
			foreach ($matches as $key => $emoji)
			{
				$msg = str_replace($emoji, utf8_encode_ncr($emoji), $msg);
			}
		}

		// Fallback: convert all out-of-bounds characters that are currently not supported by utf8_bin in MySQL
		$msg = (string) preg_replace('/[\x{10000}-\x{10FFFF}]/u', "\xef\xbf\xbd", $msg); // "\xef\xbf\xbd": UTF-8 REPLACEMENT CHARACTER

		return $msg;
	}

	/**
	* Determine if one of our options is enabled.
	*
	* @param string $option
	* @param object $user
	* @return boolean
	* @access public
	*/
	public function is_enabled($option, $user = array())
	{
		switch ($option)
		{
			case 'enable_post':
				return $this->myconfig['enable_post'] == self::ENABLED
						|| ($this->myconfig['enable_post'] == self::USER_CHOICE && !empty($user['user_primenotify_enable_post']));

			case 'enable_pm':
				return $this->myconfig['enable_pm'] == self::ENABLED
						|| ($this->myconfig['enable_pm'] == self::USER_CHOICE && !empty($user['user_primenotify_enable_pm']));

			case 'keep_bbcodes':
				return $this->myconfig['keep_bbcodes'] == self::ENABLED
						|| ($this->myconfig['keep_bbcodes'] == self::USER_CHOICE && !empty($user['user_primenotify_keep_bbcodes']));

			case 'always_send':
				return $this->myconfig['always_send'] == self::ENABLED
						|| ($this->myconfig['always_send'] == self::USER_CHOICE && !empty($user['user_primenotify_always_send']));
		}
		return false;
	}

	/**
	* Determine if the user should be notified even if they've already
	* received a previous notification and have not yet visited the topic.
	*
	* @param int $user_id Optional user ID
	* @return boolean
	* @access public
	*/
	public function should_always_send($user_id = 0)
	{
		$user = $this->user_loader->get_user($user_id);	// Users must be loaded already for this to work, otherwise it just returns the anonymous user
		return $this->is_enabled('always_send', $user);
	}

	/**
	* Alter the SQL statement to fit our needs
	*
	* @param string $sql	SQL SELECT string
	* @param object $post	post data
	* @return null
	* @access public
	*/
	public function alter_post_sql(&$sql, $post = array())
	{
		if ($this->myconfig['always_send'] == self::USER_CHOICE)
		{
			if (strpos($sql, 'FROM ' . FORUMS_WATCH_TABLE))
			{
				$sql = 'SELECT w.user_id as user_id
					FROM ' . FORUMS_WATCH_TABLE . ' w INNER JOIN ' . USERS_TABLE . ' u ON u.user_id = w.user_id
					WHERE w.forum_id = ' . (int) $post['forum_id'] . '
						AND (w.notify_status = ' . NOTIFY_YES . ' OR u.user_primenotify_always_send = ' . self::ENABLED . ')
						AND w.user_id <> ' . (int) $post['poster_id'];
			}
			else
			{
				$sql = 'SELECT w.user_id as user_id
					FROM ' . TOPICS_WATCH_TABLE . ' w INNER JOIN ' . USERS_TABLE . ' u ON u.user_id = w.user_id
					WHERE w.topic_id = ' . (int) $post['topic_id'] . '
						AND (w.notify_status = ' . NOTIFY_YES . ' OR u.user_primenotify_always_send = ' . self::ENABLED . ')
						AND w.user_id <> ' . (int) $post['poster_id'];
			}
		}
		else if ($this->should_always_send())
		{
			// Always notify, so don't check if a notification was already sent
			$sql = str_replace('AND notify_status = ' . NOTIFY_YES, '', $sql);
		}
	}

}
