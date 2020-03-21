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

use phpbb\efw\exception\resource_not_found_exception;
use phpbb\efw\decorator\decorator_interface;
use phpbb\efw\repository\repository_interface;
use phpbb\efw\sorter\sorter_interface;

/**
 * Entity framework provider.
 */
class provider
{
	/** @var string */
	static protected $decorator_suffix = '.decorator';

	/** @var string */
	static protected $repository_suffix = '.repository';

	/** @var string */
	static protected $sorter_suffix = '.sorter';

	/** @var \phpbb\di\service_collection */
	protected $decorators;

	/** @var \phpbb\di\service_collection */
	protected $repositories;

	/** @var \phpbb\di\service_collection */
	protected $sorters;

	/**
	 * Constructor.
	 *
	 * @param \phpbb\di\service_collection $decorators
	 * @param \phpbb\di\service_collection $repositories
	 * @param \phpbb\di\service_collection $sorters
	 */
	public function __construct(
		\phpbb\di\service_collection $decorators,
		\phpbb\di\service_collection $repositories,
		\phpbb\di\service_collection $sorters
	)
	{
		$this->decorators	= $decorators;
		$this->repositories = $repositories;
		$this->sorters		= $sorters;
	}

	/**
	 * Get decorator.
	 *
	 * @param string $decorator
	 * @param bool   $suffix
	 * @return decorator_interface
	 */
	public function get_decorator(string $decorator, bool $suffix = true): decorator_interface
	{
		if ($suffix && substr($decorator, - strlen(self::$decorator_suffix)) !== self::$decorator_suffix)
		{
			$decorator .= self::$decorator_suffix;
		}

		if (!$this->decorators->offsetExists($decorator))
		{
			throw resource_not_found_exception::decorator($decorator);
		}

		return $this->decorators->offsetGet($decorator);
	}

	/**
	 * Get repository.
	 *
	 * @param string $repository
	 * @param bool   $suffix
	 * @return repository_interface
	 */
	public function get_repository(string $repository, bool $suffix = true): repository_interface
	{
		if ($suffix && substr($repository, - strlen(self::$repository_suffix)) !== self::$repository_suffix)
		{
			$repository .= self::$repository_suffix;
		}

		if (!$this->repositories->offsetExists($repository))
		{
			throw resource_not_found_exception::repository($repository);
		}

		return $this->repositories->offsetGet($repository);
	}

	/**
	 * Get sorter.
	 *
	 * @param string $sorter
	 * @param bool   $suffix
	 * @return sorter_interface
	 */
	public function get_sorter(string $sorter, bool $suffix = true): sorter_interface
	{
		if ($suffix && substr($sorter, - strlen(self::$sorter_suffix)) !== self::$sorter_suffix)
		{
			$sorter .= self::$sorter_suffix;
		}

		if (!$this->sorters->offsetExists($sorter))
		{
			throw resource_not_found_exception::sorter($sorter);
		}

		return $this->sorters->offsetGet($sorter);
	}
}
