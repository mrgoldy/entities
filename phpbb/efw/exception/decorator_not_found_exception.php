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
 * Decorator not found exception.
 */
class decorator_not_found_exception extends http_exception implements exception_interface
{
	/**
	 * Constructor.
	 *
	 * @param int             $status_code
	 * @param string          $message
	 * @param array           $parameters
	 * @param \Exception|null $previous
	 * @param array           $headers
	 * @param int             $code
	 */
	public function __construct(int $status_code = 404, string $message = "", array $parameters = [], \Exception $previous = null, array $headers = [], int $code = 0)
	{
		parent::__construct($status_code, $message, $parameters, $previous, $headers, $code);
	}
}
