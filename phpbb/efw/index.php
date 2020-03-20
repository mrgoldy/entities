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

class index
{
	protected $helper;

	public function __construct(\phpbb\controller\helper $helper)
	{
		$this->helper = $helper;
	}

	public function index()
	{
		return $this->helper->render('entities.twig', 'Entities');
	}
}
