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

namespace phpbb\efw\sorter;

/**
 * User sorter.
 */
class user extends abstract_sorter
{
	/**
	 * Get default.
	 *
	 * @return array
	 */
	public function get_default(): array
	{
		return ['u'];
	}

	/**
	 * Get options.
	 *
	 * @return array
	 */
	public function get_options(): array
	{
		return [
			'u'	=> ['title' => 'USERNAME', 'column' => 'username_clean', 'direction' => 'ASC'],
			'p'	=> ['title' => 'POSTS', 'column' => 'user_posts', 'direction' => 'DESC'],
			'r'	=> ['title' => 'JOINED', 'column' => 'user_regdate', 'direction' => 'ASC'],
			'v'	=> ['title' => 'LAST_ACTIVE', 'column' => 'user_lastvisit', 'direction' => 'DESC'],
		];
	}
}
