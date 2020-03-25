<?php
/**
 * Treat: F4N70M
 * Version: 0.1
 * Date: 10.01.2020
 */

namespace Fw\Components\Modules\Account;


use Fw\Components\Modules\Users\User;
use Fw\Components\Modules\Users\Users;
use Fw\Components\Services\Auth\Auth;
use Fw\Components\Services\Entity\Entity;
use Exception;

/**
 * Class Account
 * @package Fw\Modules\Account
 */
class Account
{
	private $auth;
//	private $users = [];
	private $logins = [];

	private $list = [];
	private $current;
	/**
	 * @var Entity
	 */
	private $Entity;


	/**
	 * Account constructor.
	 * @param Auth $auth
	 * @param Entity $entity
	 */
	public function __construct(Auth $auth, Entity $entity)
	{
		$this->auth = $auth;
		$this->Entity = $entity;
	}


	/**
	 * @return array
	 */
	public function getList()
	{
		return $this->auth->getlist();
	}


	/**
	 * @return array|false
	 * @throws Exception
	 */
	public function getCurrent()
	{
		$currentID = $this->getCurrentId();

		if ($currentID)
			return $this->getUser($currentID);
		return false;
	}


	/**
	 * @return array|false
	 * @throws Exception
	 */
	public function getCurrentId()
	{
		return $this->auth->getCurrent();
	}


	public function getId($unique)
	{
		if (isset($this->users[$unique]))
		{
			return $unique;
		}
		elseif (isset($this->logins[$unique]))
		{
			return $this->logins[$unique];
		}

		return false;
	}


	private function getUser($unique)
	{
		$id = $this->getId($unique);
		if (!$id)
		{
			$user = new User($this->Entity, $unique);

			$id = $user->get('id');

			$this->users[$id] = $user;
			$this->logins[$user->get('login')] = $id;
		}

		return $this->users[$id];
	}


	/**
	 * @param $login
	 * @param $password
	 * @return bool
	 * @throws Exception
	 */
	public function login($login, $password)
	{
		$user = $this->verifyPassword($login, $password);
		if ($user)
		{
			$id = $user->get('id');
			$result = $this->auth->in($id);
			$this->users[$id] = $user;
			return $result;
		}
		return false;
	}


	/**
	 * @param string $login
	 * @param string $password
	 * @param array $data
	 * @return bool
	 * @throws Exception
	 */
	public function signup(string $login, string $password, array $data = [])
	{
		$data['login'] = $login;
		$data['password'] = $this->hashPassword($password);
//		debug($data);
		$result = (bool) $this->Entity->insert('users', $data);
//		debug($result);
		return $result;
	}

	/**
	 * @param $unique
	 * @param string $password
	 * @return bool|User
	 * @throws Exception
	 */
	public function verifyPassword($unique, string $password)
	{
		$user = $this->getUser($unique);

		if (password_verify($password, $user->get('password')))
		{
			$rehash = $this->hashPassword($password);
			$user->set('password', $rehash);
			return $user;
		}
		return false;
	}


	private function hashPassword($password)
	{
		return password_hash($password, PASSWORD_DEFAULT);
	}
}