<?php
/**
 * Treat: F4N70M
 * Version: 0.1
 * Date: 10.01.2020
 */

namespace Fw\Components\Services\Account;


use Fw\Components\Modules\Users\User;
use Fw\Components\Modules\Users\Users;
use Fw\Components\Services\Auth\Auth;
use Fw\Components\Services\Database\EntityQueryBuilder;
use Fw\Components\Services\Database\QueryBuilder;
//use Fw\Components\Services\Entity\Entity;
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
	private $db;


	/**
	 * Account constructor.
	 * @param Auth $auth
	 * @param QueryBuilder $db
	 */
	public function __construct(Auth $auth, QueryBuilder $db)
	{
		$this->auth = $auth;
		$this->db = $db;
	}


	/**
	 * @param $login
	 * @param $password
	 * @return bool
	 * @throws Exception
	 */
	public function signin($login, $password)
	{
	    /*if ($response = $this->db->select()->from('users')->where(['id'=>$login, ['or', 'login'=>$login]])->result())
        {
            $user = $response[0];
            $id = $user['id'];
            $result = $this->auth->in($id);
            $this->users[$id] = $user;
            return $result;
        }*/
		$user = $this->verifyPassword($login, $password);
		if ($user)
		{
//		    debug($user);
			$id = $user['id'];
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
		$result = (bool) $this->db->insert()->into('users')->values($data)->result();
		return $result;
	}


	/**
	 * @return array|false
	 * @throws Exception
	 */
	public function getCurrentId()
	{
		return $this->auth->getCurrent();
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
	 * @return array
	 */
	public function getList()
	{
		return $this->auth->getlist();
	}


	private function getId($unique)
	{
		if (isset($this->users[$unique]))
			return $unique;
		elseif (isset($this->logins[$unique]))
			return $this->logins[$unique];
		else
			return false;
	}


	private function getUser($unique)
	{
		$id = $this->getId($unique);
		if (!$id)
		{
			$user = ($users = $this->db
                ->select()
                ->from('users')
                ->where(['id'=>$unique, ['or', 'login'=>$unique]])
                ->result()
            )
                ? $users[0]
                : false;

			if (!$user) return false;

			$id = $user['id'];

			$this->users[$id] = $user;
			$this->logins[$user['login']] = $id;
		}

		return $this->users[$id];
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

		if (password_verify($password, $user['password']))
		{
			$rehash = $this->hashPassword($password);
//			$user->set('password', $rehash);
            $users = $this->db
                ->update('users')
                ->set(['password' => $rehash])
                ->where(['id'=>$unique, ['or', 'login'=>$unique]])
                ->result();
			return $user;
		}
		return false;
	}


	public function hashPassword($password)
	{
		return password_hash($password, PASSWORD_DEFAULT);
	}
}