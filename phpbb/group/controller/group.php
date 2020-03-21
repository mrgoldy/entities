<?php

namespace phpbb\group\controller;

use phpbb\exception\http_exception;

class group
{
	protected $auth;
	protected $config;
	protected $ep;
	protected $group_helper;
	protected $helper;
	protected $language;
	protected $pagination;
	protected $template;

	public function __construct(
		\phpbb\auth\auth $auth,
		\phpbb\config\config $config,
		\phpbb\efw\provider $ep,
		\phpbb\group\helper $group_helper,
		\phpbb\controller\helper $helper,
		\phpbb\language\language $language,
		\phpbb\pagination $pagination,
		\phpbb\template\template $template
	)
	{
		$this->auth			= $auth;
		$this->config		= $config;
		$this->ep			= $ep;
		$this->group_helper	= $group_helper;
		$this->helper		= $helper;
		$this->language		= $language;
		$this->pagination	= $pagination;
		$this->template		= $template;
	}

	public function list()
	{
		# IF(group_type = ' . GROUP_SPECIAL . ', 1, 0) DESC, group_name ASC
		return $this->helper->render('', '');
	}

	public function view($key, int $page)
	{
		$this->language->add_lang(['memberlist', 'groups']);

		/** @var \phpbb\efw\repository\group $group_repo */
		$group_repo	= $this->ep->get_repository('group');
		$group_deco	= $this->ep->get_decorator('group');
		$group_qb	= $group_repo->build_one_by_key($key);
		$group		= $group_repo->find_one($group_qb);

		if ($group === null)
		{
			throw new http_exception(404, 'Group not found');
		}

		$limit = $this->config['topics_per_page'];
		$start = ($page - 1) * $limit;

		/** @var \phpbb\efw\repository\user $user_repo */
		$user_repo	= $this->ep->get_repository('user');
		$user_deco	= $this->ep->get_decorator('user');
		$user_sort	= $this->ep->get_sorter('user_group');
		$user_qb	= $user_repo->build_by_group($group->get_id(), $user_sort->get_order_by(), $limit, $start);

		$users = $user_repo->find($user_qb);
		$total = $user_repo->count($user_qb);

		foreach ($users as $user)
		{
			$this->template->assign_block_vars('users', array_merge($user_deco->get_variables($user), [
				'S_GROUP_LEADER'	=> $user->get('group_leader'),
			]));
		}

		$this->pagination->generate_template_pagination([
			'routes' => 'phpbb_group_view',
			'params' => ['key' => $key],
		], 'pagination', 'page', $total, $limit, $start);

		$this->template->assign_vars(array_merge($group_deco->get_variables($group), [
			'TOTAL_USERS'		=> $this->language->lang('LIST_USERS', $total),

			'S_MANAGE_GROUP'	=> $group->get('group_leader'),
			'U_MANAGE_GROUP'	=> '',
		]));

		return $this->helper->render('group_view.twig', $this->group_helper->get_name($group->get('group_name')));
	}
}
