<?php
/**
 * Treat: F4N70M
 * Version: 0.1
 * Date: 13.01.2020
 */

namespace Apps\Lk\Model;


use Exception;
use Fw\Core;

class Lk_Model
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
	 * @throws Exception
	 */
	public function request(string $action)
	{
		$method = $action.'Request';

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
			else
			{
				header("Location: {$_SERVER['REDIRECT_URL']}");
				exit();
			}

			return $result;
		}
		else
		{
			throw new Exception("Request action \"{$action}\" does not exist");
		}
	}


	/**
	 * @return bool
	 */
	private function loginRequest()
	{
		if (isset($_POST['login']) && !empty($_POST['login']) && isset($_POST['password']) && !empty($_POST['password']))
		{
			return $this->Fw->Account->login($_POST['login'], $_POST['password']);
		}
		return false;
	}


	/**
	 * @return bool
	 */
	private function signUpRequest()
	{
		if (
			isset($_POST['login']) &&
			!empty($_POST['login']) &&
			isset($_POST['password']) &&
			!empty($_POST['password']))
		{
			return $this->Fw->Account->signup($_POST['login'], $_POST['password'],$_POST);
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
	 * @throws Exception
	 */
	public function getAccess()
	{
		$user = $this->Fw->Account->getCurrent();
		//TODO: Написать нормальную проверку доступа
		return (bool) $user;
	}


	/**
	 * @return bool
	 * @throws Exception
	 */
	private function clientCreateRequest()
	{
		if (
			isset($_POST['login']) &&
			!empty($_POST['login']) &&
			isset($_POST['name']) &&
			!empty($_POST['name']) &&
			isset($_POST['password']) &&
			!empty($_POST['password']) &&
			$this->getAccess()
		)
		{
			$data = [
				'login' =>  $_POST['login'],
				'name'  =>  $_POST['name'],
				'password'  =>  $_POST['password']
			];
			return $this->Fw->UserManager->new($data);
		}
		return false;
	}


	/**
	 * @return bool
	 * @throws Exception
	 */
	private function projectCreateRequest()
	{
		if (
			isset($_POST['title']) &&
			!empty($_POST['title']) &&
			isset($_POST['user']) &&
			!empty($_POST['user']) &&
			$this->getAccess()
		)
		{
			$data = [
				'title' =>  $_POST['title'],
				'link'  =>  $_POST['link'],
				'description'   =>  $_POST['description'],
				'user'  =>  $_POST['user']
			];
			return $this->Fw->ProjectManager->new($data);
		}
		return false;
	}


	/**
	 * @return bool
	 * @throws Exception
	 */
	private function projectDeleteRequest()
	{
		if (
			isset($_POST['id']) && !empty($_POST['id']) &&
			$this->getAccess()
		)
		{
			$project = $this->Fw->Project($_POST['id']);
			return $project->delete();
//			return $this->Fw->Projects->deleteProject($_POST['id']);
		}
		return false;
	}


	/**
	 * @return bool
	 * @throws Exception
	 */
	private function ticketCreateRequest()
	{
		if (
			isset($_POST['project']) && !empty($_POST['project']) &&
			isset($_POST['user']) && !empty($_POST['user']) &&
			isset($_POST['title']) && !empty($_POST['title']) &&
			isset($_POST['content']) && !empty($_POST['content']) &&
			$this->getAccess()
		)
		{
			$TicketManager = $this->Fw->TicketManager();
			$data = [
				'title'=>$_POST['title'],
				'content'=>$_POST['content'],
				'project'=>$_POST['project'],
				'user'=>$_POST['user']
			];
			return $TicketManager->new($data);
		}
		return false;
	}


	/**
	 * @return bool
	 * @throws Exception
	 */
	private function ticketDeleteRequest()
	{
		if (
			isset($_POST['id']) && !empty($_POST['id']) &&
			$this->getAccess()
		)
		{
			$ticket = $this->Fw->Ticket($_POST['id']);
			return $ticket->delete();
//			return $this->Fw->Projects->deleteProject($_POST['id']);
		}
		return false;
	}


	/**
	 * @return bool
	 * @throws Exception
	 */
	private function ticketMessageCreateRequest()
	{
		if (
			isset($_POST['ticket']) && !empty($_POST['ticket']) &&
			isset($_POST['user']) && !empty($_POST['user']) &&
			isset($_POST['content']) && !empty($_POST['content']) &&
			$this->getAccess()
		)
		{
			$data = [
				'user'      =>  $_POST['user'],
				'content'   =>  $_POST['content'],
				'ticket'     =>  $_POST['ticket']
			];
			$MessageManager = $this->Fw->MessageManager();
			return $MessageManager->new($data);
		}
		return false;
	}


	/**
	 * @return bool
	 * @throws Exception
	 */
	private function ticketMessageDeleteRequest()
	{
		if (
			isset($_POST['message']) && !empty($_POST['message']) &&
			$this->getAccess()
		)
		{
			return $this->Fw->Message($_POST['message'])->delete();
		}
		return false;
	}


	/**
	 * @return bool
	 * @throws Exception
	 */
	public function accessCreateRequest()
	{
		if (
			isset($_POST['type']) && !empty($_POST['type']) &&
			isset($_POST['user']) && !empty($_POST['user']) &&
			isset($_POST['project']) && !empty($_POST['project']) &&
			$this->getAccess()
		)
		{
			$data = $_POST;
			$data['accessType'] = $data['type'];
			unset($data['type']);
			unset($data['redirect']);
			unset($data['request']);
			$AccessManager = $this->Fw->AccessManager();
			$result = $AccessManager->new($data);
//			debug($data, $project);
			return $result;
		}
		return false;
	}


	/**
	 * @return bool
	 * @throws Exception
	 */
	public function serviceNewRequest()
	{
		debug($_POST);
		if (
			isset($_POST['type']) && !empty($_POST['type']) &&
			isset($_POST['user']) && !empty($_POST['user']) &&
			isset($_POST['project']) && !empty($_POST['project']) &&
			$this->getAccess()
		)
		{
			$data = $_POST;
			$data['accessType'] = $data['type'];
			unset($data['type']);
			unset($data['redirect']);
			unset($data['request']);
			$AccessManager = $this->Fw->AccessManager();
			$result = $AccessManager->new($data);
//			debug($data, $project);
			return $result;
		}
		return false;
	}
}