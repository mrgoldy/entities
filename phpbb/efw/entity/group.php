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

namespace phpbb\efw\entity;

/**
 * Group entity.
 */
class group extends entity
{
	/**
	 * @var string Id column
	 * @static
	 */
	static public $id_column = 'group_id';

	/**
	 * @var string Slug column
	 * @static
	 */
	static public $slug_column = 'group_name';
}
