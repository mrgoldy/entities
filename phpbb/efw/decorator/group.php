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

class group extends abstract_decorator
{
	/** @var \phpbb\group\helper */
	protected $group_helper;

	/**
	 * Constructor.
	 *
	 * @param \phpbb\group\helper               $group_helper
	 * @param \phpbb\controller\helper          $helper
	 * @param \phpbb\textformatter\s9e\renderer $renderer
	 * @param \phpbb\textformatter\s9e\utils    $utils
	 * @param \phpbb\user                       $user
	 */
	public function __construct(
		\phpbb\group\helper $group_helper,
		\phpbb\controller\helper $helper,
		\phpbb\textformatter\s9e\renderer $renderer,
		\phpbb\textformatter\s9e\utils $utils,
		\phpbb\user $user
	)
	{
		$this->group_helper	= $group_helper;

		parent::__construct($helper, $renderer, $utils, $user);
	}

	/**
	 * Get group variables
	 *
	 * @param \phpbb\efw\entity\group $entity
	 * @return array
	 */
	public function get_variables($entity): array
	{
		$data = $entity->get_data();

		$common = [
			'GROUP_ID'				=> $entity->get_id(),
			'GROUP_TYPE'			=> $entity->get('group_type'),

			'GROUP_NAME'			=> $entity->get('group_name'),
			'GROUP_NAME_FULL'		=> $this->group_helper->get_name_string('full', $entity->get_id(), $entity->get('group_name'), $entity->get('group_colour')),
			'GROUP_NAME_NO_PROFILE'	=> $this->group_helper->get_name_string('no_profile', $entity->get_id(), $entity->get('group_name'), $entity->get('group_colour')),
			'GROUP_NAME_PROFILE'	=> $this->group_helper->get_name_string('profile', $entity->get_id(), $entity->get('group_name'), $entity->get('group_colour')),
			'GROUP_COLOUR'			=> $this->group_helper->get_name_string('colour', $entity->get_id(), $entity->get('group_name'), $entity->get('group_colour')),

			'GROUP_AVATAR'			=> $this->group_helper->get_avatar($data),

			'GROUP_DISPLAY'			=> $entity->get('group_display'),
			'GROUP_FOUNDER_MANAGE'	=> $entity->get('group_founder_manage'),
			'GROUP_SIG_CHARS'		=> $entity->get('group_sig_chars'),
			'GROUP_RECEIVE_PM'		=> $entity->get('group_receive_pm'),
			'GROUP_MESSAGE_LIMIT'	=> $entity->get('group_message_limit'),
			'GROUP_LEGEND'			=> $entity->get('group_legend'),
			'GROUP_MAX_RECIPIENTS'	=> $entity->get('group_max_recipients'),
		];

		$variables = array_merge(
			$common,
			$this->get_text_variables('GROUP_DESC', $entity->get('group_desc')),
			$this->get_rank_variables('GROUP_RANK', $this->group_helper->get_rank($data), $entity->get('group_rank')),
			$this->get_const_variables('S_GROUP', $entity->get('group_type'), ['OPEN', 'CLOSED', 'HIDDEN', 'SPECIAL', 'FREE'], $entity),
			$this->get_link_variables('GROUP', 'phpbb_group_view', ['key' => $entity->get_id()])
		);

		return $variables;
	}
}
