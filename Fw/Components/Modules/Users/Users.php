<?php
/**
 * Project: F4N70M
 * Version: 0.1
 * Date: 10.01.2020
 */

namespace Fw\Components\Modules\Users;

use Exception;
use Fw\Components\Services\Entity\Entity;

class Users
{
	private $Entity;

	/**
	 * Projects constructor.
	 * @param Entity $Entity
	 */
	public function __construct(Entity $Entity)
	{
		$this->Entity = $Entity;
	}


	/**
	 * @param $id
	 * @return array|false
	 * @throws Exception
	 */
	public function get($id)
	{
		if (is_int($id))
			$column = 'id';
		elseif (is_string($id))
			$column = 'login';
		else
			throw new Exception("Invalid argument type \"{$id}\"");

		return $this->Entity->selectFirst('users',[$column=>$id]);
	}

	/**
	 * @param int $id
	 * @param array $parameters
	 * @return mixed
	 */
	public function set(int $id, array $parameters = [])
	{
//		return $this->Entity->
	}

	/**
	 * @param array $parameters
	 * @return mixed
	 */
	public function new(array $parameters = [])
	{
	}

	/**
	 * @param $id
	 * @param string $password
	 * @return bool
	 * @throws Exception
	 */
	public function verifyPassword($id, string $password)
	{
		$user = $this->get($id);

		if (password_verify($password, $user['password']))
		{
			$rehash = password_hash($password, PASSWORD_DEFAULT);
			$result = $this->Entity->update('users', ['password'=>$rehash], ['id'=>$user['id']]);
			return $result;
		}
		return false;
	}
}