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

namespace phpbb\efw\sorter;

/**
 * Abstract sorter.
 */
interface sorter_interface
{
	/**
	 * Get default.
	 *
	 * @return array
	 */
	public function get_default(): array;

	/**
	 * Get options.
	 *
	 * @return array
	 */
	public function get_options(): array;

	/**
	 * Get order by.
	 *
	 * @param int $super_global
	 * @return array
	 */
	public function get_order_by($super_global = abstract_sorter::REQUEST): array;

	/**
	 * Assign form.
	 *
	 * @param string $prefix
	 * @param int    $super_global
	 * @return void
	 */
	public function assign_form(string $prefix = '', $super_global = abstract_sorter::REQUEST): void;

	/**
	 * Assign links.
	 *
	 * @param string $route
	 * @param array  $params
	 * @param string $prefix
	 * @param int    $super_global
	 * @return void
	 */
	public function assign_links(string $route, array $params = [], string $prefix = '', $super_global = abstract_sorter::REQUEST): void;

	/**
	 * Request sorter.
	 *
	 * @param int $super_global
	 * @return array
	 */
	public function request_sorter($super_global = abstract_sorter::REQUEST): array;
}
