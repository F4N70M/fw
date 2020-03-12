<?php
/**
 * Project: F4N70M
 * Version: 0.1
 * Date: 13.01.2020
 */

namespace Apps\Lk\Model;


use Exception;
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
	 * @throws Exception
	 */
	public function request(string $action)
	{
		$method = ucfirst($action).'Request';

		if (method_exists($this,$method))
		{
			$result = $this->$method();
			if (!$result)
				throw new Exception("Action \"{$action}\" failed");

			if (isset($_POST['redirect']))
			{
				header("Location: {$_POST['redirect']}");
				exit();
			}

			return $result;
		}
		else
		{
			throw new Exception("Request action \"{$action}\" does not exist");
		}
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
	 * @throws Exception
	 */
	public function getAccess()
	{
		$user = $this->Fw->Account->getCurrent();
		//TODO: Написать нормальною проверку доступа
		return (bool) $user;
	}


	/**
	 * @return bool
	 * @throws Exception
	 */
	private function ProjectCreateRequest()
	{
		if (
			isset($_POST['name']) &&
			!empty($_POST['name']) &&
			isset($_POST['user']) &&
			!empty($_POST['user']) &&
			$this->getAccess()
		)
		{
			return $this->Fw->Projects->createProject($_POST['name'],$_POST['user']);
		}
		return false;
	}


	/**
	 * @return bool
	 * @throws Exception
	 */
	private function ProjectDeleteRequest()
	{
		if (
			isset($_POST['id']) &&
			!empty($_POST['id']) &&
			$this->getAccess()
		)
		{
			return $this->Fw->Projects->deleteProject($_POST['id']);
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