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

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Query\QueryBuilder;
use phpbb\efw\collection;
use phpbb\efw\entity\entity;
use phpbb\efw\exception\entity_exists_exception;
use phpbb\exception\http_exception;

/**
 * Abstract base repository.
 */
abstract class abstract_repository implements repository_interface
{
	/** @var Connection */
	protected $conn;

	/** @var array Entity maps */
	protected $maps;

	/** @var array phpBB tables */
	protected $tables;

	/** @var entity */
	protected $entity;

	/** @var collection */
	protected $entities;

	/** @var collection|null */
	protected $loaded;

	/** @var QueryBuilder|null */
	protected $builder;

	public function __construct(Connection $conn, array $maps, array $tables)
	{
		$this->conn   = $conn;
		$this->maps   = $maps;
		$this->tables = $tables;

		/** @var entity entity */
		$this->entity   = $this->get_entity_map_data('entity');
		$this->entities = new collection();
	}

	/**
	 * Get entity map.
	 *
	 * @return array
	 */
	abstract public function get_entity_map(): array;

	/**
	 * Get connection.
	 *
	 * @return Connection
	 */
	public function get_connection(): Connection
	{
		return $this->conn;
	}

	/**
	 * Get builder.
	 *
	 * @return QueryBuilder
	 */
	public function get_builder(): QueryBuilder
	{
		return $this->conn
			->createQueryBuilder()
			->select($this->get_table_column('*'))
			->from($this->get_table(), $this->get_table_alias());
	}

	/**
	 * Get last builder.
	 *
	 * @return QueryBuilder|null
	 */
	public function get_last_builder()
	{
		return $this->builder;
	}

	/**
	 * Find.
	 *
	 * @param QueryBuilder $builder
	 * @return collection|null
	 */
	public function find(QueryBuilder $builder)
	{
		return $this->load($builder)->get_last();
	}

	/**
	 * Find one.
	 *
	 * @param QueryBuilder $builder
	 * @return entity
	 */
	public function find_one(QueryBuilder $builder)
	{
		return $this->find($builder->setMaxResults(1))->first();
	}

	/**
	 * Build.
	 *
	 * @param QueryBuilder $builder
	 * @param array        $orderBy
	 * @param int          $limit
	 * @param int          $offset
	 * @return QueryBuilder
	 */
	public function build(QueryBuilder $builder, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): QueryBuilder
	{
		if ($orderBy)
		{
			foreach ($orderBy as $column => $direction)
			{
				$builder->addOrderBy($column, strtoupper(trim($direction)));
			}
		}

		if ($limit)
		{
			$builder->setMaxResults($limit);
		}

		if ($offset)
		{
			$builder->setFirstResult($offset);
		}

		return $this->builder = $builder;
	}

	/**
	 * Build criteria.
	 *
	 * @param QueryBuilder $builder
	 * @param array        $criteria
	 * @return QueryBuilder
	 */
	public function build_criteria(QueryBuilder $builder, array $criteria): QueryBuilder
	{
		foreach ($criteria as $field => $value)
		{
			if (is_int($field))
			{
				$builder->andWhere($value);

				continue;
			}

			switch ($type = gettype($value))
			{
				case 'integer':
				case 'boolean':
				case 'string':
				case 'NULL':
					$builder->andWhere($field . ' = ' . $builder->createPositionalParameter($value, constant(ParameterType::class . '::' . strtoupper($type))));
				break;

				case 'array':
					if (count($value) === 1)
					{
						$builder->andWhere($field . ' = ' . $builder->createPositionalParameter(reset($value)));
					}
					else
					{
						$builder->andWhere($builder->expr()->in($field, $value));
					}
				break;
			}
		}

		return $this->builder = $builder;
	}

	/**
	 * Build by.
	 *
	 * @param array $criteria
	 * @param array $orderBy
	 * @param int   $limit
	 * @param int   $offset
	 * @return QueryBuilder
	 */
	public function build_by(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): QueryBuilder
	{
		return $this->builder = $this->build($this->build_criteria($this->get_builder(), $criteria), $orderBy, $limit, $offset);
	}

	/**
	 * Build all.
	 *
	 * @param array $orderBy
	 * @return QueryBuilder
	 */
	public function build_all(?array $orderBy = null): QueryBuilder
	{
		return $this->build_by([], $orderBy);
	}

	/**
	 * Build by id.
	 *
	 * @param array $ids
	 * @param array $orderBy
	 * @param int   $limit
	 * @param int   $offset
	 * @return QueryBuilder
	 */
	public function build_by_id(array $ids, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): QueryBuilder
	{
		return $this->build_by([
			$this->get_table_column($this->entity::get_id_column()) => array_map('intval', $ids),
		], $orderBy, $limit, $offset);
	}

	/**
	 * Build by slug.
	 *
	 * @param array $slugs
	 * @param array $orderBy
	 * @param int   $limit
	 * @param int   $offset
	 * @return QueryBuilder
	 */
	public function build_by_slug(array $slugs, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): QueryBuilder
	{
		try
		{
			$column = $this->conn->getDatabasePlatform()->getLowerExpression($this->get_table_column($this->entity::get_slug_column()));
		}
		catch (DBALException $e)
		{
			$column = $this->get_table_column($this->entity::get_slug_column());
		}

		return $this->build_by([
			$column => array_map(function($slug) {
				return (string) strtolower($slug);
			}, $slugs),
		], $orderBy, $limit, $offset);
	}

	/**
	 * Build by parent.
	 *
	 * @param array $parents
	 * @param array $orderBy
	 * @param int   $limit
	 * @param int   $offset
	 * @return QueryBuilder
	 */
	public function build_by_parent(array $parents, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): QueryBuilder
	{
		if (!$this->entity::get_parent_column())
		{
			throw new http_exception(0, '');
		}

		return $this->build_by([
			$this->get_table_column($this->entity::get_parent_column()) => array_map('intval', $parents),
		], $orderBy, $limit, $offset);
	}

	/**
	 * Build one by.
	 *
	 * @param array $criteria
	 * @return QueryBuilder
	 */
	public function build_one_by(array $criteria): QueryBuilder
	{
		return $this->build_by($criteria, null, 1);
	}

	/**
	 * Build one by key.
	 *
	 * @param $key
	 * @return QueryBuilder
	 */
	public function build_one_by_key($key): QueryBuilder
	{
		if (is_numeric($key) && ctype_digit((string) $key) && $key == (int) $key)
		{
			return $this->build_one_by_id((int) $key);
		}

		return $this->build_one_by_slug((string) $key);
	}

	/**
	 * Build one by id.
	 *
	 * @param int $id
	 * @return QueryBuilder
	 */
	public function build_one_by_id(int $id): QueryBuilder
	{
		return $this->build_one_by([$this->get_table_column($this->entity::get_id_column()) => $id]);
	}

	/**
	 * Build one by slug.
	 *
	 * @param string $slug
	 * @return QueryBuilder
	 */
	public function build_one_by_slug(string $slug): QueryBuilder
	{
		try
		{
			$column = $this->conn->getDatabasePlatform()->getLowerExpression($this->get_table_column($this->entity::get_slug_column()));
		}
		catch (DBALException $e)
		{
			$column = $this->get_table_column($this->entity::get_slug_column());
		}

		return $this->build_one_by([$column => strtolower($slug)]);
	}

	/**
	 * Build one by parent.
	 *
	 * @param int   $parent
	 * @param array $orderBy
	 * @return QueryBuilder
	 */
	public function build_one_by_parent(int $parent, ?array $orderBy = null): QueryBuilder
	{
		if (!$this->entity::get_parent_column())
		{
			throw new http_exception(0, '');
		}

		return $this->build_by([$this->get_table_column($this->entity::get_parent_column()) => $parent], $orderBy, 1);
	}

	/**
	 * Get all.
	 *
	 * @return collection
	 */
	public function get_all(): collection
	{
		return $this->entities;
	}

	/**
	 * Get last.
	 *
	 * @return collection|null
	 */
	public function get_last()
	{
		return $this->loaded;
	}

	/**
	 * Get by.
	 *
	 * @param array $criteria
	 * @return collection
	 */
	public function get_by(array $criteria): collection
	{
		$entities = new collection();

		foreach ($this->entities as $entity)
		{
			foreach ($criteria as $field => $value)
			{
				if (is_array($value) ? in_array($entity->get($field), $value) : $entity->get($field) === $value)
				{
					continue 2;
				}
			}

			$entities->offsetSet(null, $entity);
		}

		return $entities;
	}

	/**
	 * Get by key.
	 *
	 * @param $key
	 * @return entity
	 */
	public function get_by_key($key): entity
	{
		return $this->entities->offsetGet($key);
	}

	/**
	 * Get by id.
	 *
	 * @param array $ids
	 * @return collection
	 */
	public function get_by_id(array $ids): collection
	{
		return $this->get_by([$this->entity::get_id_column() => array_map('intval', $ids)]);
	}

	/**
	 * Get by slug.
	 *
	 * @param array $slugs
	 * @return collection
	 */
	public function get_by_slug(array $slugs): collection
	{
		return $this->get_by([$this->entity::get_id_column() => array_map(function($slug) {
			return (string) strtolower($slug);
		}, $slugs)]);
	}

	/**
	 * Get by parents.
	 *
	 * @param array $parents
	 * @return collection[]
	 */
	public function get_by_parents(array $parents): array
	{
		if (!$this->entity::get_parent_column())
		{
			throw new http_exception(0, '');
		}

		$column = $this->entity::get_parent_column();

		$parents = array_flip($parents);

		array_walk($parents, function(&$collection, $parent) use ($column) {
			$collection = $this->get_by([$column => (int) $parent]);
		});

		return $parents;
	}

	/**
	 * Load.
	 *
	 * @param QueryBuilder $builder
	 * @return repository_interface
	 */
	public function load(QueryBuilder $builder): repository_interface
	{
		$this->builder	= $builder;
		$this->loaded	= new collection();

		$result = $builder->execute();
		$rowset = $result->fetchAll(FetchMode::ASSOCIATIVE);

		foreach ($rowset as $row)
		{
			/** @var entity $entity */
			$entity = $this->create()->import($row);

			$this->set_one($entity);
			$this->loaded->offsetSet(null, $entity);
		}

		return $this;
	}

	/**
	 * Load one.
	 *
	 * @param QueryBuilder $builder
	 * @return repository_interface
	 */
	public function load_one(QueryBuilder $builder): repository_interface
	{
		return $this->load($builder->setMaxResults(1));
	}

	/**
	 * Count entities.
	 *
	 * @param QueryBuilder $builder
	 * @return int
	 */
	public function count(QueryBuilder $builder = null): int
	{
		$builder = $builder ?? $this->builder;

		$builder
			->resetQueryParts(array_diff(array_keys($this->builder->getQueryParts()), ['from', 'join', 'where']))
			->select('COUNT(*)');

		return (int) $builder->execute()->fetchColumn();
	}

	/**
	 * Clear entities.
	 *
	 * @return repository_interface
	 */
	public function clear(): repository_interface
	{
		$this->entities	= new collection();
		$this->loaded	= new collection();

		return $this;
	}

	/**
	 * Has entity.
	 *
	 * @param $key
	 * @return bool
	 */
	public function has($key): bool
	{
		return $this->entities->offsetExists($key);
	}

	/**
	 * Import.
	 *
	 * @param array $rowset
	 * @return repository_interface
	 */
	public function import(array $rowset): repository_interface
	{
		foreach ($rowset as $row)
		{
			$this->import_one($row);
		}

		return $this;
	}

	/**
	 * Import one.
	 *
	 * @param array $row
	 * @return repository_interface
	 */
	public function import_one(array $row): repository_interface
	{
		return $this->set_one($this->create()->import($row));
	}

	/**
	 * Set entities.
	 *
	 * @param entity[] $entities
	 * @return repository_interface
	 */
	public function set(array $entities): repository_interface
	{
		foreach ($entities as $entity)
		{
			if ($entity instanceof entity)
			{
				$this->set_one($entity);
			}
		}

		return $this;
	}

	/**
	 * Set one entity.
	 *
	 * @param entity $entity
	 * @return repository_interface
	 */
	public function set_one(entity $entity): repository_interface
	{
		$this->entities->offsetSet(null, $entity);

		return $this;
	}

	/**
	 * Unset entities.
	 *
	 * @param entity[] $entities
	 * @return repository_interface
	 */
	public function unset(array $entities): repository_interface
	{
		foreach ($entities as $entity)
		{
			if ($entity instanceof entity)
			{
				$this->unset_one($entity);
			}
		}

		return $this;
	}

	/**
	 * Unset one entity.
	 *
	 * @param entity $entity
	 * @return repository_interface
	 */
	public function unset_one(entity $entity): repository_interface
	{
		$this->entities->offsetUnset($entity->get_id());
		$this->entities->offsetUnset($entity->get_slug());

		return $this;
	}

	/**
	 * Create entity.
	 *
	 * @return entity
	 */
	public function create(): entity
	{
		return $this->entity::create()->set_map($this->get_entity_map());
	}

	/**
	 * Delete entity.
	 *
	 * @param entity $entity
	 * @return bool
	 */
	public function delete(entity $entity): bool
	{
		if (empty($entity->get_id()))
		{
			throw entity_exists_exception::not_exists($entity);
		}

		try
		{
			$deleted = (bool) $this->conn->delete($this->get_table(), [$entity::$id_column => $entity->get_id()]);
		}
		catch (DBALException $e)
		{
			return false;
		}

		if ($deleted)
		{
			$this->unset_one($entity);
		}

		return $deleted;
	}

	/**
	 * Insert entity.
	 *
	 * @param entity $entity
	 * @return entity
	 */
	public function insert(entity $entity): entity
	{
		if (!empty($entity->get_id()))
		{
			throw entity_exists_exception::already_exists($entity);
		}

		$this->conn->createQueryBuilder()
			->insert($this->get_table())
			->values($entity->get_sql_data())
			->execute();

		$entity->set('id', (int) $this->conn->lastInsertId());

		if ($entity->get_id())
		{
			$this->set_one($entity);
		}

		return $entity;
	}

	/**
	 * Update entity.
	 *
	 * @param entity $entity
	 * @return entity
	 */
	public function update(entity $entity): entity
	{
		if (empty($entity->get_id()))
		{
			throw entity_exists_exception::not_exists($entity);
		}

		$builder = $this->conn->createQueryBuilder();
		$builder->update($this->get_table())
			->where($entity::$id_column . ' = :entity_id')
			->setParameter(':entity_id', $entity->get_id());

		foreach ($entity->get_sql_data() as $key => $value)
		{
			$builder->set($key, $value);
		}

		$builder->execute();

		$this->set_one($entity);

		return $entity;
	}

	/**
	 * Get table.
	 *
	 * @return string
	 */
	public function get_table(): string
	{
		return $this->tables[$this->get_table_name()];
	}

	/**
	 * Get table alias.
	 *
	 * @return string
	 */
	public function get_table_alias(): string
	{
		if ($alias = $this->get_entity_map_data('alias'))
		{
			return $alias;
		}

		return substr($this->get_table_name(), 0, 1);
	}

	/**
	 * Get table column.
	 *
	 * @param string $column
	 * @param string $alias
	 * @return string
	 */
	public function get_table_column(string $column, ?string $alias = null): string
	{
		if (strpos($column, '.') === false)
		{
			$alias = $alias ?? $this->get_table_alias();

			return "{$alias}.{$column}";
		}

		return $column;
	}

	/**
	 * Get table name.
	 *
	 * @return string
	 */
	public function get_table_name(): string
	{
		return $this->get_entity_map_data('table');
	}

	/**
	 * Get entity map data.
	 *
	 * @param string $key
	 * @return string|null
	 */
	public function get_entity_map_data(string $key)
	{
		return $this->get_entity_map()[$key] ?? null;
	}
}
