<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 10.01.2020
 */

namespace Fw\Components\Modules\Users;

use Exception;
use Fw\Components\Services\Database\Db;

class Users
{
	private $Db;

	/**
	 * Users constructor.
	 * @param Db $db
	 */
	public function __construct(Db $db)
	{
		$this->Db = $db;
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

		return $this->Db
			->select()
			->from('users')
			->where([$column => $id])
			->one();
	}

	/**
	 * @param int $id
	 * @param array $parameters
	 * @return mixed
	 */
	public function set(int $id, array $parameters = [])
	{
		return $this->Db
			->update('users')
			->s
			->where([$column => $id])
			->one();
	}
}