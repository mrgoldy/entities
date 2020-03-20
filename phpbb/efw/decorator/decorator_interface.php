<?php
/**
 *
 * This file is part of the phpBB Forum Software package.
 *
 * @copyright (c) phpBB Limited <https://www.phpbb.com>
 * @license       GNU General Public License, version 2 (GPL-2.0)
 *
 * For full copyright and license information, please see
 * the docs/CREDITS.txt file.
 *
 */

namespace phpbb\efw\decorator;

/**
 * Base decorator.
 */
interface decorator_interface
{
	/**
	 * Get variables.
	 *
	 * @param \phpbb\efw\entity\entity $entity
	 * @return array
	 */
	public function get_variables($entity): array;

	/**
	 * Get const variables.
	 *
	 * @param string $variable
	 * @param        $constant
	 * @param array  $constants
	 * @param        $class
	 * @return array
	 */
	public function get_const_variables(string $variable, $constant, array $constants, $class): array;

	/**
	 * Get date variables.
	 *
	 * @param string $variable
	 * @param int    $unix
	 * @param bool   $format
	 * @return array
	 */
	public function get_date_variables(string $variable, int $unix, $format = false): array;

	/**
	 * Get link variables.
	 *
	 * @param string $variable
	 * @param string $route
	 * @param array  $params
	 * @return array
	 */
	public function get_link_variables(string $variable, string $route, array $params = []): array;

	/**
	 * Get rank variables.
	 *
	 * @param string $variable
	 * @param array  $rank
	 * @param int    $rank_id
	 * @return array
	 */
	public function get_rank_variables(string $variable, array $rank, int $rank_id): array;

	/**
	 * Get text variables.
	 *
	 * @param string $variable
	 * @param string $xml
	 * @return array
	 */
	public function get_text_variables(string $variable, string $xml): array;
}
