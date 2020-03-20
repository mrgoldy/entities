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

namespace phpbb\efw\sorter;

abstract class base
{
	const SORT_KEY = 'sk';
	const SORT_DIR = 'sd';

	protected $language;
	protected $request;
	protected $template;

	protected $default;
	protected $options = [
		'column'	=> [
			'title',
			'direction',
		],
	];

	public function __construct(
		\phpbb\language\language $language,
		\phpbb\request\request $request,
		\phpbb\template\template $template
	)
	{
		$this->language	= $language;
		$this->request	= $request;
		$this->template	= $template;
	}

	abstract public function get_orders();
	abstract public function get_order_by($super_global = \phpbb\request\request_interface::REQUEST);

	abstract public function assign_form();
	abstract public function assign_links();
}
