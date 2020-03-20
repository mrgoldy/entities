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

use phpbb\efw\decorator\decorator_interface;
use phpbb\efw\exception\decorator_not_found_exception;
use phpbb\efw\exception\repository_not_found_exception;
use phpbb\efw\repository\repository_interface;

/**
 * Entity provider.
 */
class provider
{
	/** @var string */
	static protected $deco_suffix = '.decorator';

	/** @var string */
	static protected $repo_suffix = '.repository';

	/** @var \phpbb\di\service_collection */
	protected $decorators;

	/** @var \phpbb\di\service_collection */
	protected $repositories;

	/**
	 * Constructor.
	 *
	 * @param \phpbb\di\service_collection $decorators
	 * @param \phpbb\di\service_collection $repositories
	 */
	public function __construct(
		\phpbb\di\service_collection $decorators,
		\phpbb\di\service_collection $repositories
	)
	{
		$this->decorators	= $decorators;
		$this->repositories = $repositories;
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
		if ($suffix && substr($decorator, - strlen(self::$deco_suffix)) !== self::$deco_suffix)
		{
			$decorator .= self::$deco_suffix;
		}

		if (!$this->decorators->offsetExists($decorator))
		{
			throw new decorator_not_found_exception();
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
		if ($suffix && substr($repository, - strlen(self::$repo_suffix)) !== self::$repo_suffix)
		{
			$repository .= self::$repo_suffix;
		}

		if (!$this->repositories->offsetExists($repository))
		{
			throw new repository_not_found_exception();
		}

		return $this->repositories->offsetGet($repository);
	}
}
