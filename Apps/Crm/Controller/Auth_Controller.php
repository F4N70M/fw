<?php
/**
 * Created by PhpStorm.
 * Treat: edkon
 * Date: 03.11.2019
 * Time: 6:21
 */

namespace Apps\Crm\Controller;


use Apps\Crm\Model\Lk_Model;
use Apps\Crm\View\Lk_View;
use Exception;
use Fw\Components\Interfaces\iAppController;
use Fw\Core;

class Auth_Controller implements iAppController
{
	private $Fw;

	private $model;
	private $view;

	private $action;
	private $request;

	public function __construct(Core $Fw)
	{
		$this->Fw = $Fw;
		$this->model = new Lk_Model($this->Fw);
		$this->view = new Lk_View($this->Fw, $this->model);

		// Обработка запросов
		$this->request = $this->request();
	}

	public function signup()
	{
		$this->action = 'signup';
	}
	public function login()
	{
		if ($this->model->getAccess())
		{
			header('Location: '.APP_PREFIX);
		}
		$this->action = 'login';
	}
	public function logout()
	{
		$this->Fw->Auth->out();
//		debug($this->model->getAccess());
		header("Location: ".APP_PREFIX."/login");
	}
	public function recovery()
	{
		$this->action = 'recovery';
	}


	/**
	 * @return bool
	 * @throws Exception
	 */
	public function request()
	{
		return isset($_POST['request']) ? $this->model->request($_POST['request']) : false;
	}


	public function render()
	{
		$tpl = "auth/" . $this->action;
		$this->view->render($tpl);

//		$content = file_get_contents(__DIR__ . "/../template/default/auth/" . $this->action . ".tpl");
//		$this->Fw->TemplateBuilder->render($content);
	}
}