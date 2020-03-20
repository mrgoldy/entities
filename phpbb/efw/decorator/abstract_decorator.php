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

namespace phpbb\efw\decorator;

/**
 * Base decorator.
 */
abstract class abstract_decorator implements decorator_interface
{
	/** @var \phpbb\controller\helper */
	protected $helper;

	/** @var \phpbb\textformatter\s9e\renderer */
	protected $renderer;

	/** @var \phpbb\textformatter\s9e\utils */
	protected $utils;

	/** @var \phpbb\user */
	protected $user;

	/**
	 * Constructor.
	 *
	 * @param \phpbb\controller\helper          $helper
	 * @param \phpbb\textformatter\s9e\renderer $renderer
	 * @param \phpbb\textformatter\s9e\utils    $utils
	 * @param \phpbb\user                       $user
	 */
	public function __construct(
		\phpbb\controller\helper $helper,
		\phpbb\textformatter\s9e\renderer $renderer,
		\phpbb\textformatter\s9e\utils $utils,
		\phpbb\user $user
	)
	{
		$this->helper	= $helper;
		$this->renderer	= $renderer;
		$this->utils	= $utils;
		$this->user		= $user;
	}

	/**
	 * Get variables.
	 *
	 * @param \phpbb\efw\entity\entity $entity
	 * @return array
	 */
	abstract public function get_variables($entity): array;

	/**
	 * Get const variables.
	 *
	 * @param string $variable
	 * @param        $constant
	 * @param array  $constants
	 * @param        $class
	 * @return array
	 */
	public function get_const_variables(string $variable, $constant, array $constants, $class = ''): array
	{
		$variables = [];

		if (!empty($class) && substr($class, -2) !== '::')
		{
			$class .= '::';
		}

		foreach ($constants as $const)
		{
			$variables["{$variable}_{$const}"] = constant($class . $const) === $constant;
		}

		return $variables;
	}

	/**
	 * Get date variables.
	 *
	 * @param string $variable
	 * @param int    $unix
	 * @param bool   $format
	 * @return array
	 */
	public function get_date_variables(string $variable, int $unix, $format = false): array
	{
		return [
			$variable			=> $unix ? $this->user->format_date($unix, $format) : '',
			"{$variable}_UNIX"	=> $unix,
		];
	}

	/**
	 * Get link variables.
	 *
	 * @param string $variable
	 * @param string $route
	 * @param array  $params
	 * @return array
	 */
	public function get_link_variables(string $variable, string $route, array $params = []): array
	{
		return [
			"U_VIEW_{$variable}"		=> $this->helper->route($route, $params),
			"U_VIEW_{$variable}_FULL"	=> generate_board_url(false) . $this->helper->route($route, $params, false),
			"U_VIEW_{$variable}_SLUG"	=> $this->helper->route($route, $params)
		];
	}

	/**
	 * Get rank variables.
	 *
	 * @param string $variable
	 * @param array  $rank
	 * @param int    $rank_id
	 * @return array
	 */
	public function get_rank_variables(string $variable, array $rank, int $rank_id): array
	{
		return [
			$variable				=> $rank['img'],
			"{$variable}_ID"		=> $rank_id,
			"{$variable}_TITLE"		=> $rank['title'],
			"{$variable}_SOURCE"	=> $rank['img_src'],
		];
	}

	/**
	 * Get text variables.
	 *
	 * @param string $variable
	 * @param string $xml
	 * @return array
	 */
	public function get_text_variables(string $variable, string $xml): array
	{
		return [
			$variable				=> !$this->utils->is_empty($xml) ? $this->renderer->render($xml) : '',
			"{$variable}_BBCODE"	=> !$this->utils->is_empty($xml) ? $this->utils->unparse($xml) : '',
			"{$variable}_XML"		=> $xml,
		];
	}
}
