<?php

namespace phpbb\user\controller;

class user
{
	protected $auth;
	protected $config;
	protected $ep;
	protected $helper;
	protected $language;
	protected $pagination;
	protected $template;

	public function __construct(
		\phpbb\auth\auth $auth,
		\phpbb\config\config $config,
		\phpbb\efw\provider $ep,
		\phpbb\controller\helper $helper,
		\phpbb\language\language $language,
		\phpbb\pagination $pagination,
		\phpbb\template\template $template
	)
	{
		$this->auth			= $auth;
		$this->config		= $config;
		$this->ep			= $ep;
		$this->helper		= $helper;
		$this->language		= $language;
		$this->pagination	= $pagination;
		$this->template		= $template;
	}

	public function view(int $user_id)
	{
		return $this->helper->render('memberlist_body.html', 'Group');
	}
}
