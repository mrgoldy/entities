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

use phpbb\efw\entity\entity;
use phpbb\exception\exception_interface;
use phpbb\exception\http_exception;

/**
 * Entity exists exception.
 */
class entity_exists_exception extends http_exception implements exception_interface
{
	static public function already_exists(entity $entity): entity_exists_exception
	{
		return new self(409, 'Entity already exists', [$entity->get_id()]);
	}

	static public function not_exists(entity $entity): entity_exists_exception
	{
		return new self(409, 'Entity does not exist', [$entity->get_slug(), get_class($entity)]);
	}
}
