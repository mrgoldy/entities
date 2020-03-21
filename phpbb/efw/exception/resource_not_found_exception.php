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

namespace phpbb\efw\exception;

use phpbb\exception\exception_interface;
use phpbb\exception\http_exception;

/**
 * Resource not found exception.
 */
class resource_not_found_exception extends http_exception implements exception_interface
{
	static public function decorator(string $decorator): resource_not_found_exception
	{
		return new self(404, 'Decorator not found', [$decorator]);
	}

	static public function repository(string $repository): resource_not_found_exception
	{
		return new self(404, 'Repository not found', [$repository]);
	}

	static public function sorter(string $sorter): resource_not_found_exception
	{
		return new self(404, 'Sorter not found', [$sorter]);
	}
}
