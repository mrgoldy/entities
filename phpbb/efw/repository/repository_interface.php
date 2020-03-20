<?php
/**
 *
 * This file is part of the phpBB Forum Software package.
 *
 * @copyright (c) phpBB Limited <https://www.phpbb.com>
 * @license       GNU General Public License, version 2 (GPL-2.0)
 *
 * For full copyright and license information, please see
 * the docs/CREDITS.txt file.
 *
 */

namespace phpbb\efw\repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use phpbb\efw\collection;
use phpbb\efw\entity\entity;

/**
 * Repository interface.
 */
interface repository_interface
{
	/**
	 * Get entity map.
	 *
	 * @return array
	 */
	public function get_entity_map(): array;

	/**
	 * Get connection.
	 *
	 * @return Connection
	 */
	public function get_connection(): Connection;

	/**
	 * Get builder.
	 *
	 * @return QueryBuilder
	 */
	public function get_builder(): QueryBuilder;

	/**
	 * Get last builder.
	 *
	 * @return QueryBuilder|null
	 */
	public function get_last_builder();

	/**
	 * Find.
	 *
	 * @param QueryBuilder $builder
	 * @return collection|null
	 */
	public function find(QueryBuilder $builder);

	/**
	 * Find one.
	 *
	 * @param QueryBuilder $builder
	 * @return entity
	 */
	public function find_one(QueryBuilder $builder);

	/**
	 * Build.
	 *
	 * @param QueryBuilder $builder
	 * @param array        $orderBy
	 * @param int          $limit
	 * @param int          $offset
	 * @return QueryBuilder
	 */
	public function build(QueryBuilder $builder, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): QueryBuilder;

	/**
	 * Build criteria.
	 *
	 * @param QueryBuilder $builder
	 * @param array        $criteria
	 * @return QueryBuilder
	 */
	public function build_criteria(QueryBuilder $builder, array $criteria): QueryBuilder;

	/**
	 * Build by.
	 *
	 * @param array $criteria
	 * @param array $orderBy
	 * @param int   $limit
	 * @param int   $offset
	 * @return QueryBuilder
	 */
	public function build_by(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): QueryBuilder;

	/**
	 * Build all.
	 *
	 * @param array $orderBy
	 * @return QueryBuilder
	 */
	public function build_all(?array $orderBy = null): QueryBuilder;

	/**
	 * Build by id.
	 *
	 * @param array $ids
	 * @param array $orderBy
	 * @param int   $limit
	 * @param int   $offset
	 * @return QueryBuilder
	 */
	public function build_by_id(array $ids, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): QueryBuilder;

	/**
	 * Build by slug.
	 *
	 * @param array $slugs
	 * @param array $orderBy
	 * @param int   $limit
	 * @param int   $offset
	 * @return QueryBuilder
	 */
	public function build_by_slug(array $slugs, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): QueryBuilder;

	/**
	 * Build by parent.
	 *
	 * @param array $parents
	 * @param array $orderBy
	 * @param int   $limit
	 * @param int   $offset
	 * @return QueryBuilder
	 */
	public function build_by_parent(array $parents, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): QueryBuilder;

	/**
	 * Build one by.
	 *
	 * @param array $criteria
	 * @return QueryBuilder
	 */
	public function build_one_by(array $criteria): QueryBuilder;

	/**
	 * Build one by key.
	 *
	 * @param $key
	 * @return QueryBuilder
	 */
	public function build_one_by_key($key): QueryBuilder;

	/**
	 * Build one by id.
	 *
	 * @param int $id
	 * @return QueryBuilder
	 */
	public function build_one_by_id(int $id): QueryBuilder;

	/**
	 * Build one by slug.
	 *
	 * @param string $slug
	 * @return QueryBuilder
	 */
	public function build_one_by_slug(string $slug): QueryBuilder;

	/**
	 * Build one by parent.
	 *
	 * @param int   $parent
	 * @param array $orderBy
	 * @return QueryBuilder
	 */
	public function build_one_by_parent(int $parent, ?array $orderBy = null): QueryBuilder;

	/**
	 * Get all.
	 *
	 * @return collection
	 */
	public function get_all(): collection;

	/**
	 * Get last.
	 *
	 * @return collection|null
	 */
	public function get_last();

	/**
	 * Get by.
	 *
	 * @param array $criteria
	 * @return collection
	 */
	public function get_by(array $criteria): collection;

	/**
	 * Get by key.
	 *
	 * @param $key
	 * @return entity
	 */
	public function get_by_key($key): entity;

	/**
	 * Get by id.
	 *
	 * @param array $ids
	 * @return collection
	 */
	public function get_by_id(array $ids): collection;

	/**
	 * Get by slug.
	 *
	 * @param array $slugs
	 * @return collection
	 */
	public function get_by_slug(array $slugs): collection;

	/**
	 * Get by parents.
	 *
	 * @param array $parents
	 * @return collection[]
	 */
	public function get_by_parents(array $parents): array;

	/**
	 * Load.
	 *
	 * @param QueryBuilder $builder
	 * @return repository_interface
	 */
	public function load(QueryBuilder $builder): repository_interface;

	/**
	 * Load one.
	 *
	 * @param QueryBuilder $builder
	 * @return repository_interface
	 */
	public function load_one(QueryBuilder $builder);

	/**
	 * Count.
	 *
	 * @param QueryBuilder|null $builder
	 * @return int
	 */
	public function count(QueryBuilder $builder = null): int;

	/**
	 * Clear.
	 *
	 * @return repository_interface
	 */
	public function clear(): repository_interface;

	/**
	 * Has.
	 *
	 * @param $key
	 * @return bool
	 */
	public function has($key): bool;

	/**
	 * Import.
	 *
	 * @param array $rowset
	 * @return repository_interface
	 */
	public function import(array $rowset): repository_interface;

	/**
	 * Import one.
	 *
	 * @param array $row
	 * @return repository_interface
	 */
	public function import_one(array $row): repository_interface;

	/**
	 * Set.
	 *
	 * @param array $entities
	 * @return repository_interface
	 */
	public function set(array $entities);

	/**
	 * Set one.
	 *
	 * @param entity $entity
	 * @return repository_interface
	 */
	public function set_one(entity $entity): repository_interface;

	/**
	 * Unset.
	 *
	 * @param array $entities
	 * @return repository_interface
	 */
	public function unset(array $entities): repository_interface;

	/**
	 * Unset one.
	 *
	 * @param entity $entity
	 * @return repository_interface
	 */
	public function unset_one(entity $entity): repository_interface;

	/**
	 * Create.
	 *
	 * @return entity
	 */
	public function create(): entity;

	/**
	 * Delete.
	 *
	 * @param entity $entity
	 * @return bool
	 */
	public function delete(entity $entity): bool;

	/**
	 * Insert.
	 *
	 * @param entity $entity
	 * @return entity
	 */
	public function insert(entity $entity): entity;

	/**
	 * Update.
	 *
	 * @param entity $entity
	 * @return entity
	 */
	public function update(entity $entity): entity;

	/**
	 * Get table.
	 *
	 * @return string
	 */
	public function get_table(): string;

	/**
	 * Get table alias.
	 *
	 * @return string
	 */
	public function get_table_alias(): string;

	/**
	 * Get table column.
	 *
	 * @param string $column
	 * @param string $alias
	 * @return string
	 */
	public function get_table_column(string $column, ?string $alias = null): string;

	/**
	 * Get table name.
	 *
	 * @return string
	 */
	public function get_table_name(): string;

	/**
	 * Get entity map data.
	 *
	 * @param string $key
	 * @return string|null
	 */
	public function get_entity_map_data(string $key);
}
