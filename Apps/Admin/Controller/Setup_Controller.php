<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 07.07.2020
 */

namespace Apps\Admin\Controller;


use Apps\Admin\Model\Admin_Model;
use Apps\Admin\View\Admin_View;
use Apps\Admin\Request\Admin_Request;
use Fw\Core;

class Setup_Controller
{
	protected $theme = 'private/setup';
	protected $template;
	protected $info = [];
	protected $data = [];

	public function __construct(Core $Fw, $config=[])
	{
		$this->Fw = $Fw;

		$this->info = $config['info'];

		// Model
		$this->model = new Admin_Model($this->Fw);
		// View
		$this->view = new Admin_View($this->Fw, $this->model);
		// Request
		$this->request = new Admin_Request($this->Fw, $this->model);
	}


	public function start()
	{
		//TODO: SETUP
		$this->template = 'page/start';
	}

	public function render()
	{
		$this->view->render($this->theme, $this->template, $this->data, $this->info);
	}

}