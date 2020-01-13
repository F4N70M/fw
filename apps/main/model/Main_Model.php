<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 13.01.2020
 */

namespace Apps\Main\Model;


use Fw\Core;

class Main_Model
{
	/**
	 * @var Core
	 */
	private $Fw;


	/**
	 * Main_Model constructor.
	 * @param Core $Fw
	 */
	public function __construct(Core $Fw)
	{
		$this->Fw = $Fw;
	}


	/**
	 * @param string $action
	 * @return bool
	 */
	public function request(string $action)
	{
		$method = ucfirst($action).'Request';
		if (method_exists($this,$method))
		{
			return $this->$method();
		}
		return false;
	}


	/**
	 * @return bool
	 */
	private function LoginRequest()
	{
		if (isset($_POST['login']) && !empty($_POST['login']) && isset($_POST['password']) && !empty($_POST['password']))
		{
			return $this->Fw->Account->login($_POST['login'], $_POST['password']);
		}
		return false;
	}
}