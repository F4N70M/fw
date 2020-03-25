<?php
/**
 * Treat: F4N70M
 * Version: 0.1
 * Date: 13.01.2020
 */

namespace Apps\Main\Model;


use Exception;
use Fw\Core;

class Main_Model
{
	/**
	 * @var Core
	 */
	private $Fw;


	/**
	 * Lk_Model constructor.
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
			if (isset($_POST['redirect']))
			{
				header("Location: {$_POST['redirect']}");
				exit();
			}

			return $this->$method();
		}
		return false;
	}

	public function getNameByUri(string $uri)
	{
		$arr = explode('/', $uri);

		if (empty($arr))
			throw new Exception("Empty URI");

		$name = end($arr);
		return $name;
	}

	public function getIndex()
	{

	}

	public function getInfo($name)
	{
		$uniq = ($name == 'index' ? $this->Fw->Pages->getIndexID() : $name);
		$result = $this->Fw->Pages->get($uniq);

		if (!$result)
			return $this->getInfo404();
		return $result;
	}

	private function getInfo404()
	{
		return [
		    'type' => 'error',
			'name' => '404',
			'title' => '404',
			'content' => 'Страница не найдена'
		];
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