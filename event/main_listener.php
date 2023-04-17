<?php
/**
*
* Prime Notify extension for the phpBB Forum Software package.
*
* @copyright (c) 2018 Ken F. Innes IV <https://www.absoluteanime.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace primehalo\primenotify\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use primehalo\primenotify\core\prime_notify;

/**
* Event listener
*/
class main_listener implements EventSubscriberInterface
{
	/**
	* Service Containers
	*/
	protected $config;
	protected $db;
	protected $language;
	protected $request;
	protected $template;
	protected $user;
	protected $user_loader;
	protected $notification_manager;

	/**
	* Constructor
	*
	* @param \phpbb\config\config				$config		Config object
	* @param \phpbb\db\driver\driver_interface	$db			Database connection
	* @param \phpbb\language\language 			$language
	* @param \phpbb\request\request_interface	$request 	phpBB request
	* @param \phpbb\template\template 			$template	Template object
	* @param \phpbb\user						$user		User object
	* @param \phpbb\user_loader					$user_loader
	* @param \phpbb\notification\manager		$notification_manager
	* @access public
	*/
	public function __construct(
		\phpbb\config\config $config,
		\phpbb\db\driver\driver_interface $db,
		\phpbb\language\language $language,
		\phpbb\request\request $request,
		\phpbb\template\template $template,
		\phpbb\user $user,
		\phpbb\user_loader $user_loader,
		\phpbb\notification\manager $notification_manager)
	{
		$this->config		= $config;
		$this->db			= $db;
		$this->language		= $language;
		$this->request		= $request;
		$this->template		= $template;
		$this->user			= $user;
		$this->user_loader	= $user_loader;
		$this->notification_manager = $notification_manager;
	}

	/**
	* Assign functions defined in this class to event listeners in the core
	*
	* @static
	* @access public
	*/
	static public function getSubscribedEvents()
	{
		return array(
			'core.acp_users_prefs_modify_data'				=> 'user_prefs',		//3.1.0-b3
			'core.acp_users_prefs_modify_sql'				=> 'user_prefs_sql',	//3.1.0-b3
			'core.ucp_prefs_personal_data'					=> 'user_prefs',		//3.1.0-a1
			'core.ucp_prefs_personal_update_data'			=> 'user_prefs_sql',	//3.1.0-a1
			'core.notification_manager_add_notifications'	=> 'add_notifications',	//3.1.3-RC1
			'core.markread_before'							=> 'markread',			//3.1.4-RC1
			'core.ucp_pm_view_message'						=> 'markread_pm',		//3.2.2-RC1
			'core.user_add_modify_notifications_data'		=> 'user_add_modify_notifications_data', //3.2.2-RC1
		);
	}

	/**
	* Set and display user preferences
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function user_prefs($event)
	{
		$this->language->add_lang('common', 'primehalo/primenotify');

		if (defined('IN_ADMIN'))
		{
			$user_id = $event['user_row']['user_id'];
			$user_primenotify_enable_post	= $event['user_row']['user_primenotify_enable_post'];
			$user_primenotify_enable_pm		= $event['user_row']['user_primenotify_enable_pm'];
			$user_primenotify_keep_bbcodes	= $event['user_row']['user_primenotify_keep_bbcodes'];
			$user_primenotify_always_send	= $event['user_row']['user_primenotify_always_send'];
		}
		else
		{
			$user_id = $this->user->data['user_id'];
			$user_primenotify_enable_post	= $this->user->data['user_primenotify_enable_post'];
			$user_primenotify_enable_pm		= $this->user->data['user_primenotify_enable_pm'];
			$user_primenotify_keep_bbcodes	= $this->user->data['user_primenotify_keep_bbcodes'];
			$user_primenotify_always_send	= $this->user->data['user_primenotify_always_send'];
		}

		// Add either new form data or user's current settings to the event's $data array
		$event['data'] = array_merge($event['data'], array(
			'user_primenotify_enable_post'	=> $this->request->variable('user_primenotify_enable_post', (bool) $user_primenotify_enable_post),
			'user_primenotify_enable_pm'	=> $this->request->variable('user_primenotify_enable_pm', (bool) $user_primenotify_enable_pm),
			'user_primenotify_keep_bbcodes'	=> $this->request->variable('user_primenotify_keep_bbcodes', (bool) $user_primenotify_keep_bbcodes),
			'user_primenotify_always_send'	=> $this->request->variable('user_primenotify_always_send', (bool) $user_primenotify_always_send),
		));

		// Get the user's current email notification settings as our settings are useless if they're not enabled
		$user_notifications = $this->get_user_notifications($user_id);
		$user_post	= !empty($user_notifications['primehalo.primenotify.notification.type.post'][0]['notify'])
						|| !empty($user_notifications['primehalo.primenotify.notification.type.topic'][0]['notify'])
						|| !empty($user_notifications['primehalo.primenotify.notification.type.forum'][0]['notify']);
		$user_pm	= !empty($user_notifications['primehalo.primenotify.notification.type.pm'][0]['notify']);

		$show_enable_post	= ($this->config['primenotify_enable_post']	== prime_notify::USER_CHOICE) && $user_post;
		$show_enable_pm		= ($this->config['primenotify_enable_pm']	== prime_notify::USER_CHOICE) && $user_pm;
		$show_keep_bbcodes	= $this->config['primenotify_keep_bbcodes']	== prime_notify::USER_CHOICE;
		$show_always_send	= ($this->config['primenotify_always_send']	== prime_notify::USER_CHOICE) && $user_post;

		$this->template->assign_vars(array(
			'S_SHOW_OPTIONS'					=> $show_enable_post || $show_enable_pm || $show_always_send,	// $show_keep_bbcodes left out intentionally
			'S_SHOW_PRIMENOTIFY_ENABLE_POST'	=> $show_enable_post,
			'S_SHOW_PRIMENOTIFY_ENABLE_PM'		=> $show_enable_pm,
			'S_SHOW_PRIMENOTIFY_KEEP_BBCODES'	=> $show_keep_bbcodes,
			'S_SHOW_PRIMENOTIFY_ALWAYS_SEND'	=> $show_always_send,

			'S_USER_PRIMENOTIFY_ENABLE_POST'	=> !empty($event['data']['user_primenotify_enable_post']),
			'S_USER_PRIMENOTIFY_ENABLE_PM'		=> !empty($event['data']['user_primenotify_enable_pm']),
			'S_USER_PRIMENOTIFY_KEEP_BBCODES'	=> !empty($event['data']['user_primenotify_keep_bbcodes']),
			'S_USER_PRIMENOTIFY_ALWAYS_SEND'	=> !empty($event['data']['user_primenotify_always_send']),
		));
	}

	/**
	* Update the SQL statement for updating the user's settings in the database
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function user_prefs_sql($event)
	{
		$data = array();
		if ($this->config['primenotify_enable_post'] == prime_notify::USER_CHOICE)
		{
			$data['user_primenotify_enable_post'] = $event['data']['user_primenotify_enable_post'];
		}
		if ($this->config['primenotify_enable_pm'] == prime_notify::USER_CHOICE)
		{
			$data['user_primenotify_enable_pm'] = $event['data']['user_primenotify_enable_pm'];
		}
		if ($this->config['primenotify_keep_bbcodes'] == prime_notify::USER_CHOICE)
		{
			$data['user_primenotify_keep_bbcodes'] = $event['data']['user_primenotify_keep_bbcodes'];
		}
		if ($this->config['primenotify_always_send'] == prime_notify::USER_CHOICE)
		{
			$data['user_primenotify_always_send'] = $event['data']['user_primenotify_always_send'];
		}

		if (!empty($data))
		{
			$event['sql_ary'] = array_merge($event['sql_ary'], $data);
		}
	}

	/**
	* Load user data for potential notification recipients
	*
	* In manager.php's add_notifications_for_users(), load_users() is done after
	* create_insert_array() but we need user information while in create_insert_array()
	* so we know which version of the message content to store. Calling this function
	* from the core.notification_manager_add_notifications event accomplishes this.
	*
	* @param array $event The event object
	*        array $event['notify_users'] Array indexed by user_id with value being a numerically
	*                                     indexed array of user_notifications_table methods
	* @return none
	*/
	public function add_notifications($event)
	{
		switch ($event['notification_type_name'])
		{
			case 'notification.type.forum':
				$event['notification_type_name'] = 'primehalo.primenotify.notification.type.forum';
			break;
			case 'notification.type.topic':
				$event['notification_type_name'] = 'primehalo.primenotify.notification.type.topic';
			break;
			case 'notification.type.post':
				$event['notification_type_name'] = 'primehalo.primenotify.notification.type.post';
			break;
			case 'notification.type.pm':
				$event['notification_type_name'] = 'primehalo.primenotify.notification.type.pm';
			break;
		}

		// Load Users
		$notify_users = $event['notify_users'];
		unset($notify_users[ANONYMOUS]);
		$user_ids = array_keys($notify_users);
		if (!empty($user_ids))
		{
			$this->user_loader->load_users($user_ids);
		}
	}



	/**
	* Include our notifications as defaults when a new user is added
	*
	* @param array $event The event object
	* @return none
	*/
	public function user_add_modify_notifications_data($event)
	{
		$notifications_data = $event['notifications_data'];
		$notifications_data[] = array('item_type'	=> 'primehalo.primenotify.notification.type.post', 'method' => 'notification.method.email');
		$notifications_data[] = array('item_type'	=> 'primehalo.primenotify.notification.type.topic', 'method' => 'notification.method.email');
		$notifications_data[] = array('item_type'	=> 'primehalo.primenotify.notification.type.forum', 'method' => 'notification.method.email');
		$event['notifications_data'] = $notifications_data;
	}

	/**
	* Mark our notifications as read so they will be cleared.
	*
	* @param array $event The event object:
	* 	@var	string	mode				Variable containing marking mode value
	* 	@var	mixed	forum_id			Variable containing forum id, or false
	* 	@var	mixed	topic_id			Variable containing topic id, or false
	* 	@var	int		post_time			Variable containing post time
	* 	@var	int		user_id				Variable containing the user id
	* 	@var	bool	should_markread		Flag indicating if the markread should be done or not.
	* @return none
	*/
	public function markread($event)
	{
		$user		= $this->user;
		$mode		= $event['mode'];
		$forum_id	= $event['forum_id'];
		$topic_id	= $event['topic_id'];
		$post_time	= $event['post_time'];
		$should_markread = $event['should_markread'];

		// The below code is from the markread() function in includes/functions.php
		// and is for marking our notifications as read.
		if (!$should_markread)
		{
			return;
		}

		if ($mode == 'all')
		{
			if (empty($forum_id))
			{
				// Mark all topic notifications read for this user
				$this->notification_manager->mark_notifications(array(
					'primehalo.primenotify.notification.type.topic',
					#'notification.type.quote',
					#'notification.type.bookmark',
					'primehalo.primenotify.notification.type.post',
					#'notification.type.approve_topic',
					#'notification.type.approve_post',
					'primehalo.primenotify.notification.type.forum',
				), false, $user->data['user_id'], $post_time);

			}
		}
		else if ($mode == 'topics')
		{
			// Mark all topics in forums read
			if (!is_array($forum_id))
			{
				$forum_id = array($forum_id);
			}
			else
			{
				$forum_id = array_unique($forum_id);
			}

			$this->notification_manager->mark_notifications_by_parent(array(
				'primehalo.primenotify.notification.type.topic',
				#'notification.type.approve_topic',
			), $forum_id, $user->data['user_id'], $post_time);

			$this->notification_manager->mark_notifications_by_parent(array(
				#'notification.type.quote',
				#'notification.type.bookmark',
				'primehalo.primenotify.notification.type.post',
				#'notification.type.approve_post',
				'primehalo.primenotify.notification.type.forum',
			), $topic_id, $user->data['user_id'], $post_time);
		}
		else if ($mode == 'topic')
		{
			if ($topic_id === false || $forum_id === false)
			{
				return;
			}

			// Mark post notifications read for this user in this topic
			$this->notification_manager->mark_notifications(array(
				'primehalo.primenotify.notification.type.topic',
				#'notification.type.approve_topic',
			), $topic_id, $user->data['user_id'], $post_time);

			$this->notification_manager->mark_notifications_by_parent(array(
				#'notification.type.quote',
				#'notification.type.bookmark',
				'primehalo.primenotify.notification.type.post',
				#'notification.type.approve_post',
				'primehalo.primenotify.notification.type.forum',
			), $topic_id, $user->data['user_id'], $post_time);
		}
	}

	/**
	* Mark our PM notification as read so it will be cleared.
	*
	* @param array $event The event object:
	* 	@var	mixed	id			Active module category (can be int or string)
	* 	@var	string	mode		Active module
	* 	@var	int		folder_id	ID of the folder the message is in
	* 	@var	int		msg_id		ID of the private message
	* 	@var	array	folder		Array with data of user's message folders
	* 	@var	array	message_row	Array with message data
	* 	@var	array	cp_row		Array with senders custom profile field data
	* 	@var	array	msg_data	Template array with message data
	* 	@var	array	user_info	User data of the sender
	* @return none
	*/
	function markread_pm($event)
	{
		$msg_id		= $event['msg_id'];
		$user_id	= $this->user->data['user_id']; // The $event variables don't seem to have the recipient's user ID so use $user

		// The below code to mark the PM notification as read was taken from
		// functions_privmsgs.php's update_unread_status() function.
		$this->notification_manager->mark_notifications('primehalo.primenotify.notification.type.pm', $msg_id, $user_id);
	}

	/**
	* Get user's notification data
	*
	* This is the same function as the one in phpbb/notification/manager.php.
	* That one is protected and thus inaccessible, so I copied it here.
	*
	* @param int $user_id The user_id of the user to get the notifications for
	*
	* @return array User's notification
	*/
	protected function get_user_notifications($user_id)
	{
		$sql = 'SELECT method, notify, item_type
				FROM ' . USER_NOTIFICATIONS_TABLE . '
				WHERE user_id = ' . (int) $user_id . '
					AND item_id = 0';

		$result = $this->db->sql_query($sql);
		$user_notifications = array();

		while ($row = $this->db->sql_fetchrow($result))
		{
			$user_notifications[$row['item_type']][] = $row;
		}

		$this->db->sql_freeresult($result);

		return $user_notifications;
	}
}
