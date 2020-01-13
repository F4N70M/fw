<?php
/**
 * User: F4N70M
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
	 * Users constructor.
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

		return $this->Entity->getOne('users',[$column=>$id]);
	}

	/**
	 * @param int $id
	 * @param array $parameters
	 * @return mixed
	 */
	public function set(int $id, array $parameters = [])
	{
	}
}