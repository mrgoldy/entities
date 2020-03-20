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

/**
 * Entity data.
 */
final class data extends \ArrayObject
{
	/** @var array */
	static protected $map;

	/**
	 * Constructor.
	 *
	 * @param array $map
	 */
	public function __construct(array $map = [])
	{
		self::$map = array_map(function($value) {
			return [
				'type'		=> gettype($value),
				'default'	=> $value,
			];
		}, $map);

		parent::__construct();
	}

	/**
	 * Get map data.
	 *
	 * @return array
	 */
	public function getMapData(): array
	{
		return array_intersect_key(self::getArrayCopy(), self::$map);
	}

	/**
	 * Offset set.
	 *
	 * @param mixed $key
	 * @param mixed $value
	 * @return void
	 */
	public function offsetSet($key, $value)
	{
		if (isset(self::$map[$key]))
		{
			if (self::$map[$key]['type'] === 'array' && !is_array($value))
			{
				$value = @unserialize($value);
			}

			settype($value, self::$map[$key]['type']);
		}

		parent::offsetSet($key, $value);
	}

	/**
	 * Offset join.
	 *
	 * @param string $key
	 * @param string $value
	 * @return void
	 */
	public function offsetJoin(string $key, string $value)
	{
		parent::offsetSet($key, (string) ((self::offsetGet($key) ?? self::$map[$key]['default'] ?? '') . $value));
	}

	/**
	 * Offset merge.
	 *
	 * @param string $key
	 * @param array  $value
	 * @return void
	 */
	public function offsetMerge(string $key, array $value)
	{
		parent::offsetSet($key, (array) array_merge((self::offsetGet($key) ?? self::$map[$key]['default'] ?? []), $value));
	}

	/**
	 * Offset increment.
	 *
	 * @param string $key
	 * @param int    $value
	 * @return void
	 */
	public function offsetIncrement(string $key, int $value)
	{
		parent::offsetSet($key, (int) ((self::offsetGet($key) ?? self::$map[$key]['default'] ?? 0) + $value));
	}
}
