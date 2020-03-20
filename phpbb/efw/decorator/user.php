<?php
/**
 *
 * This file is part of the phpBB Forum Software package.
 *
 * @copyright (c) phpBB Limited <https://www.phpbb.com>
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * For full copyright and license information, please see
 * the docs/CREDITS.txt file.
 *
 */

namespace phpbb\efw\decorator;

class user extends abstract_decorator
{
	/**
	 * Get user template variables.
	 *
	 * @param \phpbb\efw\entity\user $entity
	 * @return array
	 */
	public function get_variables($entity): array
	{
		$data = $entity->get_map_data();

		$common = [
			'USER_ID'				=> $entity->get_id(),
			'USER_IP'				=> $entity->get('user_ip'),
			'USER_TYPE'				=> $entity->get('user_type'),

			'USERNAME'				=> $entity->get('username'),
			'USERNAME_CLEAN'		=> $entity->get('username_clean'),
			'USERNAME_FULL'			=> get_username_string('no_profile', $entity->get_id(), $entity->get('username'), $entity->get('user_colour')),
			'USERNAME_PROFILE'		=> get_username_string('no_profile', $entity->get_id(), $entity->get('username'), $entity->get('user_colour')),
			'USERNAME_NO_PROFILE'	=> get_username_string('no_profile', $entity->get_id(), $entity->get('username'), $entity->get('user_colour')),
			'USER_COLOUR'			=> get_username_string('colour', $entity->get_id(), $entity->get('username'), $entity->get('user_colour')),

			'USER_POSTS'			=> $entity->get('user_posts'),
			'USER_WARNINGS'			=> $entity->get('user_warnings'),
			'USER_EMAIL'			=> $entity->get('user_email'),

			'USER_AVATAR'			=> phpbb_get_user_avatar($data),
			'USER_BIRTHDAY'			=> $entity->get('user_birthday'), // STRING

			'USER_LAST_PAGE'		=> $entity->get('user_lastpage'),

			'USER_LANG'				=> $entity->get('user_lang'),
			'USER_STYLE'			=> $entity->get('user_style'),
			'USER_TIMEZONE'			=> $entity->get('user_timezone'),
			'USER_DATEFORMAT'		=> $entity->get('user_dateformat'),

			'USER_INACTIVE_REASON'	=> $entity->get('user_inactive_reason'), // CONST

			'GROUP_ID'				=> $entity->get('group_id'),

			'S_USER_NEW'				=> $entity->get('user_new'),

			'S_USER_ALLOW_PM'			=> $entity->get('user_allow_pm'),
			'S_USER_ALLOW_MASS_EMAIL'	=> $entity->get('user_allow_massemail'),
			'S_USER_ALLOW_VIEW_EMAIL'	=> $entity->get('user_allow_viewemail'),
			'S_USER_ALLOW_VIEW_ONLINE'	=> $entity->get('user_allow_viewonline'),
		];

		$variables = array_merge(
			$common,
			//	$this->get_rank_variables('USER_RANK', phpbb_get_user_rank($data, $entity->get('user_posts')), $entity->get('user_rank')),
			$this->get_text_variables('USER_SIG', $entity->get('user_sig')),
			$this->get_date_variables('USER_REG_DATE', $entity->get('user_regdate')),
			$this->get_date_variables('USER_LAST_MARK', $entity->get('user_lastmark')),
			$this->get_date_variables('USER_LAST_POST_TIME', $entity->get('user_lastpost_time')),
			$this->get_date_variables('USER_LAST_SEARCH', $entity->get('user_last_search')),
			$this->get_date_variables('USER_LAST_VISIT', $entity->get('user_lastvisit')),
			$this->get_date_variables('USER_LAST_WARNING', $entity->get('user_last_warning')),
			$this->get_date_variables('USER_INACTIVE_TIME', $entity->get('user_inactive_time')),
			$this->get_const_variables('S_USER', $entity->get('user_inactive_reason'), ['INACTIVE_REGISTER', 'INACTIVE_PROFILE', 'INACTIVE_MANUAL', 'INACTIVE_REMIND'], $entity),
			$this->get_const_variables('S_USER', $entity->get('user_type'), ['FOUNDER', 'IGNORE', 'INACTIVE', 'NORMAL'], $entity)
		);

		return $variables;
	}
}

/**
 *
user_new_privmsg: 0
user_unread_privmsg: 0
user_last_privmsg: 0
user_message_rules: 0
user_full_folder: !php/const FULL_FOLDER_NONE

user_notify: false
user_notify_pm: true
user_notify_type: 0

user_options: 230271

user_reminded: false
user_reminded_time: 0
 */
