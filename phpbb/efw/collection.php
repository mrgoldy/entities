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

namespace phpbb\efw;

use phpbb\efw\entity\entity;

/**
 * Entity collection.
 */
final class collection extends \ArrayIterator
{
	/** @var array Slug map */
	protected $map;

	/**
	 * Offset not exists.
	 *
	 * @param $key
	 * @return bool
	 */
	public function offsetNotExists($key): bool
	{
		return !parent::offsetExists(self::verifyKey($key));
	}

	/**
	 * Offset exists.
	 *
	 * @param string $key
	 * @return bool
	 */
	public function offsetExists($key): bool
	{
		return parent::offsetExists(self::verifyKey($key));
	}

	/**
	 * Offset get.
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function offsetGet($key)
	{
		return parent::offsetGet(self::verifyKey($key));
	}

	/**
	 * Offset set.
	 *
	 * @param string $key
	 * @param entity $entity
	 * @return void
	 */
	public function offsetSet($key, $entity)
	{
		if ($entity instanceof entity)
		{
			parent::offsetSet($entity->get_id(), $entity);

			if ($entity->get_slug())
			{
				$this->map[strtolower($entity->get_slug())] = $entity->get_id();
			}
		}
	}

	/**
	 * Offset unset.
	 *
	 * @param string $key
	 * @return void
	 */
	public function offsetUnset($key)
	{
		$slugs = array_flip($this->map);

		if (isset($slugs[$key]))
		{
			if (self::offsetNotExists($key))
			{
				$key = $this->map[$slugs[$key]];
			}

			unset($this->map[$slugs[$key]]);
		}

		parent::offsetUnset($key);
	}

	/**
	 * Verify key.
	 *
	 * @param $key
	 * @return mixed
	 */
	public function verifyKey($key)
	{
		if (is_string($key) && isset($this->map[strtolower($key)]))
		{
			$key = $this->map[strtolower($key)];
		}

		return $key;
	}

	/**
	 * Get first entity.
	 *
	 * @return mixed
	 */
	public function first()
	{
		if (self::count() === 0)
		{
			return null;
		}

		return reset($this);
	}

	/**
	 * Get last entity.
	 *
	 * @return mixed
	 */
	public function last()
	{
		if (self::count() === 0)
		{
			return null;
		}

		return end($this);
	}
}
