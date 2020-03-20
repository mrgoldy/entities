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

use phpbb\efw\data;

/**
 * Base entity.
 */
class entity
{
	/**
	 * @var string Id column
	 * @static
	 */
	static public $id_column;

	/**
	 * @var string Slug column
	 * @static
	 */
	static public $slug_column;

	/**
	 * @var string Parent column
	 * @static
	 */
	static public $parent_column;

	/** @var \phpbb\efw\data */
	protected $data;

	/**
	 * Create a new entity.
	 *
	 * @return static
	 */
	final static public function create() /** : static	If static was an allowed type */
	{
		return new static;
	}

	/**
	 * Set entity column map.
	 *
	 * @param array $map
	 * @return entity
	 */
	public function set_map(array $map): entity
	{
		$this->data = new data($map['columns']);

		return $this;
	}

	/**
	 * Get id column.
	 *
	 * @return string
	 * @static
	 */
	static public function get_id_column(): string
	{
		return static::$id_column;
	}

	/**
	 * Get slug column.
	 *
	 * @return string
	 * @static
	 */
	static public function get_slug_column(): string
	{
		return static::$slug_column;
	}

	/**
	 * Get parent column.
	 *
	 * @return string|null
	 * @static
	 */
	static public function get_parent_column()
	{
		return static::$parent_column;
	}

	/**
	 * Get identifier.
	 *
	 * @return int
	 */
	public function get_id(): int
	{
		return $this->get(static::$id_column) ?? 0;
	}

	/**
	 * Get slug.
	 *
	 * @return string
	 */
	public function get_slug(): string
	{
		return $this->get(static::$slug_column) ?? '';
	}

	/**
	 * Get parent.
	 *
	 * @return int
	 */
	public function get_parent(): int
	{
		return $this->get(static::$parent_column) ?? 0;
	}

	/**
	 * Has data entry.
	 *
	 * @param string $key
	 * @return bool
	 */
	public function has(string $key): bool
	{
		return $this->data->offsetExists($key);
	}

	/**
	 * Get data entry.
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function get(string $key)
	{
		return $this->data->offsetGet($key);
	}

	/**
	 * Set data entry.
	 *
	 * @param string $key
	 * @param mixed $value
	 * @return entity
	 */
	public function set(string $key, $value): entity
	{
		$this->data->offsetSet($key, $value);

		return $this;
	}

	/**
	 * Increment data entry.
	 *
	 * @param string $key
	 * @param int    $value
	 * @return entity
	 */
	public function increment(string $key, int $value): entity
	{
		$this->data->offsetIncrement($key, $value);

		return $this;
	}

	/**
	 * Join data entry.
	 *
	 * @param string $key
	 * @param string $value
	 * @return entity
	 */
	public function join(string $key, string $value): entity
	{
		$this->data->offsetJoin($key, $value);

		return $this;
	}

	/**
	 * Merge data entry.
	 *
	 * @param string $key
	 * @param array  $value
	 * @return entity
	 */
	public function merge(string $key, array $value): entity
	{
		$this->data->offsetMerge($key, $value);

		return $this;
	}

	/**
	 * Import data entry.
	 *
	 * @param array $data
	 * @return entity
	 */
	public function import(array $data): entity
	{
		foreach ($data as $key => $value)
		{
			$this->set($key, $value);
		}

		return $this;
	}

	/**
	 * Get data.
	 *
	 * @return array
	 */
	public function get_data(): array
	{
		return $this->data->getArrayCopy();
	}

	/**
	 * Get map data.
	 *
	 * @return array
	 */
	public function get_map_data(): array
	{
		return $this->data->getMapData();
	}

	/**
	 * Get SQL data.
	 *
	 * @return array
	 */
	public function get_sql_data(): array
	{
		return array_map(function($value) {
			return is_array($value) ? serialize($value) : $value;
		}, array_diff_key($this->get_map_data(), [
			static::$id_column => null,
		]));
	}
}
