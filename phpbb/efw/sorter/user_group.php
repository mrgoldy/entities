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
 * User group sorter.
 */
class user_group extends user
{
	/**
	 * Get default.
	 *
	 * @return array
	 */
	public function get_default(): array
	{
		return array_merge(['gl'], parent::get_default());
	}

	/**
	 * Get options.
	 *
	 * @return array
	 */
	public function get_options(): array
	{
		return array_merge([
			'gl' => ['title' => 'GROUP_LEADER', 'column' => 'group_leader', 'direction' => 'DESC'],
		], parent::get_options());
	}
}
