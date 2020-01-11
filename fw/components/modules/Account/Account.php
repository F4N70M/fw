<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 10.01.2020
 */

namespace Fw\Components\Modules\Account;


use Fw\Components\Modules\Users\Users;
use Fw\Components\Services\Auth\Auth;

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
	 * @param $login
	 * @param $password
	 * @throws \Exception
	 */
	public function login($login, $password)
	{
		$user = $this->users->get($login);

		if (password_verify($password, $user['password']))
		{
//			$this->users->rehashPassword($password);
			$this->auth->in($user['id']);
			debug($this->auth->check($user['id']));
			debug('VERIFY!');
		}
	}
}