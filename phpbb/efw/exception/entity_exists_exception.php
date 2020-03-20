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
 * Entity exists exception.
 */
class entity_exists_exception extends http_exception implements exception_interface
{
	/**
	 * Entity already exists.
	 *
	 * @param int             $status_code
	 * @param string          $message
	 * @param array           $parameters
	 * @param \Exception|null $previous
	 * @param array           $headers
	 * @param int             $code
	 * @return entity_exists_exception
	 * @static
	 */
	static public function already_exists(int $status_code = 409, string $message = '', array $parameters = [], \Exception $previous = null, array $headers = [], int $code = 0)
	{
		return new self($status_code, $message, $parameters, $previous, $headers, $code);
	}

	/**
	 * Entity does not exist.
	 *
	 * @param int             $status_code
	 * @param string          $message
	 * @param array           $parameters
	 * @param \Exception|null $previous
	 * @param array           $headers
	 * @param int             $code
	 * @return entity_exists_exception
	 * @static
	 */
	static public function not_exists(int $status_code = 409, string $message = '', array $parameters = [], \Exception $previous = null, array $headers = [], int $code = 0)
	{
		return new self($status_code, $message, $parameters, $previous, $headers, $code);
	}
}
