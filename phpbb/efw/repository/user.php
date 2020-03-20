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

/**
 * User.
 */
class user extends abstract_repository
{
	/**
	 * Get entity class.
	 *
	 * @return string
	 */
	public function get_entity_class(): string
	{
		return '\\phpbb\\efw\\entity\\user';
	}

	/**
	 * Get entity map.
	 *
	 * @return array
	 */
	public function get_entity_map(): array
	{
		return $this->maps['user'];
	}

	/**
	 * Get table name.
	 *
	 * @return string
	 */
	public function get_table_name(): string
	{
		return 'users';
	}

	/**
	 * Build by group.
	 *
	 * @param int        $group_id
	 * @param array|null $orderBy
	 * @param int|null   $limit
	 * @param int|null   $offset
	 * @return \Doctrine\DBAL\Query\QueryBuilder
	 */
	public function build_by_group(int $group_id, ?array $orderBy = null, ?int $limit = null, ?int $offset = null)
	{
		$builder = $this->get_builder()
			->select($this->get_table_column('*'), 'ug.group_leader', 'ug.user_pending')
			->join(
				$this->get_table_alias(),
				$this->tables['user_group'],
				'ug',
				'u.user_id = ug.user_id'
			);

		$builder = $this->build_criteria($builder, [
			'ug.user_pending'	=> 0,
			'ug.group_id'		=> $group_id,
		]);

		return $this->build($builder, $orderBy, $limit, $offset);
	}
}
