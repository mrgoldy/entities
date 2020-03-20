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

namespace phpbb\efw\repository;

use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Group repository.
 */
class group extends abstract_repository
{
	/** @var \phpbb\user */
	protected $user;

	/**
	 * Set user.
	 *
	 * @param \phpbb\user $user
	 * @return void
	 */
	public function set_user(\phpbb\user $user)
	{
		$this->user = $user;
	}

	/**
	 * Get entity map.
	 *
	 * @return array
	 */
	public function get_entity_map(): array
	{
		return $this->maps['group'];
	}

	/**
	 * Get builder.
	 *
	 * @return QueryBuilder
	 */
	public function get_builder(): QueryBuilder
	{
		return parent::get_builder()
			->select($this->get_table_column('*'), 'ug.group_leader', 'ug.user_pending')
			->leftJoin(
				$this->get_table_alias(),
				$this->tables['user_group'],
				'ug',
				'g.group_id = ug.group_id AND ug.user_id = ' . (int) $this->user->data['user_id']
			);
	}
}
