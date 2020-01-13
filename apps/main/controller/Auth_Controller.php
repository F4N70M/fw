<?php
/**
 * Created by PhpStorm.
 * User: edkon
 * Date: 03.11.2019
 * Time: 6:21
 */

namespace Apps\Main\Controller;


use Apps\Main\Model\Main_Model;
use Fw\Components\Interfaces\iAppController;
use Fw\Core;

class Auth_Controller implements iAppController
{
	private $Fw;
	private $action;
	private $model;
	private $request;

	public function __construct(Core $Fw)
	{
		$this->Fw = $Fw;
		$this->model = new Main_Model($this->Fw);

		// Обработка запросов
		$this->request = $this->request();
	}

	public function signup()
	{
		$this->action = 'signup';
	}
	public function login()
	{
		$this->action = 'login';
	}
	public function logout()
	{
		$this->action = 'logout';
	}
	public function recovery()
	{
		$this->action = 'recovery';
	}


	/**
	 * @return bool
	 */
	public function request()
	{
		return isset($_POST['request']) ? $this->model->request($_POST['request']) : false;
	}


	public function render()
	{
		debug($this->request);
		require __DIR__ . "/../template/default/auth/" . $this->action . ".tpl";
	}
}