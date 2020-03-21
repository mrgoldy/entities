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

/**
 * Abstract sorter.
 */
abstract class abstract_sorter implements sorter_interface
{
	const KEY	= 'sk';
	const DIR	= 'sd';

	const ASC	= 'ASC';
	const DESC	= 'DESC';

	const GET		= \phpbb\request\request_interface::GET;
	const POST		= \phpbb\request\request_interface::POST;
	const REQUEST	= \phpbb\request\request_interface::REQUEST;

	/** @var \phpbb\controller\helper */
	protected $helper;

	/** @var \phpbb\language\language */
	protected $language;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\template\template */
	protected $template;

	/**
	 * Constructor.
	 *
	 * @param \phpbb\controller\helper $helper
	 * @param \phpbb\language\language $language
	 * @param \phpbb\request\request   $request
	 * @param \phpbb\template\template $template
	 */
	public function __construct(
		\phpbb\controller\helper $helper,
		\phpbb\language\language $language,
		\phpbb\request\request $request,
		\phpbb\template\template $template
	)
	{
		$this->helper	= $helper;
		$this->language	= $language;
		$this->request	= $request;
		$this->template	= $template;
	}

	/**
	 * Get default.
	 *
	 * @return array
	 */
	abstract public function get_default(): array;

	/**
	 * Get options.
	 *
	 * @return array
	 */
	abstract public function get_options(): array;

	/**
	 * Get order by.
	 *
	 * @param int $super_global
	 * @return array
	 */
	public function get_order_by($super_global = self::REQUEST): array
	{
		list($key, $dir) = $this->request_sorter($super_global);

		$options = $this->get_options();

		$col = $options[$key]['column'];

		$order_by = [$col => $dir];

		foreach ($this->get_default() as $default)
		{
			if ($default !== $key)
			{
				$order_by[$options[$default]['column']] = strtoupper($options[$default]['direction']);
			}
		}

		return $order_by;
	}

	/**
	 * Assign form.
	 *
	 * @param string $prefix
	 * @param int    $super_global
	 * @return void
	 */
	public function assign_form(string $prefix = '', $super_global = self::REQUEST): void
	{
		list($key, $dir) = $this->request_sorter($super_global);

		$options = [];

		foreach ($this->get_options() as $sk => $option)
		{
			$options[$option['column']] = [
				'KEY'			=> $sk,
				'TITLE'			=> $this->language->lang($option['title']),
				'DEFAULT_DIR'	=> strtoupper($option['direction']),
				'S_DEFAULT'		=> in_array($sk, $this->get_default()),
				'S_SELECTED'	=> $sk === $key,
			];
		}

		$directions = [
			self::ASC	=> [
				'TITLE'			=> $this->language->lang('ASCENDING'),
				'S_SELECTED'	=> self::ASC === $dir,
			],
			self::DESC	=> [
				'TITLE'			=> $this->language->lang('DESCENDING'),
				'S_SELECTED'	=> self::DESC === $dir,
			],
		];

		$this->template->assign_vars([
			"{$prefix}SORT_KEY"		=> $key,
			"{$prefix}SORT_DIR"		=> $dir,
			"{$prefix}SORT_DIRS"	=> $directions,
			"{$prefix}SORT_OPTIONS"	=> $options,
		]);
	}

	/**
	 * Assign links.
	 *
	 * @param string $route
	 * @param array  $params
	 * @param string $prefix
	 * @param int    $super_global
	 * @return void
	 */
	public function assign_links(string $route, array $params = [], string $prefix = '', $super_global = self::REQUEST): void
	{
		list($key, $dir) = $this->request_sorter($super_global);

		$links = [];

		foreach ($this->get_options() as $sk => $option)
		{
			$links[$option['column']] = [
				'KEY'			=> $sk,
				'TITLE'			=> $this->language->lang($option['title']),
				'DEFAULT_DIR'	=> strtoupper($option['direction']),

				'S_ASC'			=> $sk === $key && $dir === self::ASC,
				'S_DESC'		=> $sk === $key && $dir === self::DESC,
				'S_DEFAULT'		=> in_array($sk, $this->get_default()),
				'S_SELECTED'	=> $sk === $key,

				'U_ASC'			=> $this->helper->route($route, array_merge($params, [self::KEY => $sk, self::DIR => self::ASC])),
				'U_DESC'		=> $this->helper->route($route, array_merge($params, [self::KEY => $sk, self::DIR => self::DESC])),
				'U_TOGGLE'		=> $this->helper->route($route, array_merge($params, [self::KEY => $sk, self::DIR => $sk === $key ? ($dir === self::ASC ? self::DESC : self::ASC) : strtoupper($option['direction'])])),
			];
		}

		$this->template->assign_vars([
			"{$prefix}SORT_KEY"		=> $key,
			"{$prefix}SORT_DIR"		=> $dir,
			"{$prefix}SORT_LINKS"	=> $links,
		]);
	}

	/**
	 * Request sorter variables.
	 *
	 * @param int $super_global
	 * @return array
	 */
	public function request_sorter($super_global = self::REQUEST): array
	{
		$default = $this->get_default();
		$options = $this->get_options();

		$key = $this->request->variable(self::KEY, '', true, $super_global);
		$dir = $this->request->variable(self::DIR, '', true, $super_global);

		$key = strtolower(trim($key));
		$dir = strtoupper(trim($dir));

		$key = isset($options[$key]) ? $key : reset($default);
		$dir = in_array($dir, [self::ASC, self::DESC]) ? $dir : strtoupper($options[$key]['direction']);

		return [$key, $dir];
	}
}
