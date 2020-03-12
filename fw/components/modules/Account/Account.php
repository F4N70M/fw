<?php
/**
 * Project: F4N70M
 * Version: 0.1
 * Date: 10.01.2020
 */

namespace Fw\Components\Modules\Account;


use Fw\Components\Modules\Users\Users;
use Fw\Components\Services\Auth\Auth;
use Exception;

/**
 * Class Account
 * @package Fw\Modules\Account
 */
class Account
{
	private $auth;
	private $users;

	private $list = [];
	private $current;


	/**
	 * Account constructor.
	 * @param Auth $auth
	 * @param Users $users
	 */
	public function __construct(Auth $auth, Users $users)
	{
		$this->auth = $auth;
		$this->users = $users;
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
		$currentID = $this->auth->getCurrent();

		$currentUser = false;
		if ($currentID)
			$currentUser = $this->users->get($currentID);
		return $currentUser;
	}


	/**
	 * @param $login
	 * @param $password
	 * @return bool
	 * @throws Exception
	 */
	public function login($login, $password)
	{
		if ($this->users->verifyPassword($login, $password))
		{
			$user = $this->users->get($login);
//			debug($user);
			$result = $this->auth->in($user['id']);
			return $result;
		}
		return false;
	}
}