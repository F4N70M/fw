<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 19.03.2020
 */

namespace Fw\Components\Modules\Users;


use Fw\Components\Services\Items\ItemManager;
use Fw\Components\Services\Entity\Entity;

class UserManager extends ItemManager
{
	public function __construct(Entity $Entity)
	{
		$entityName = 'users';
		parent ::__construct($Entity, $entityName);
	}
	
	
	public function new(array $data)
	{
//		if (isset())
			$data['password'] = $this->hashPassword($data['password']);
		return parent ::new($data); // TODO: Change the autogenerated stub
	}

	public function generatePassword(
		int $length = 8,
		string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
	)
	{
		if ($length < 1) {
			throw new \RangeException("Length must be a positive integer");
		}
		$pieces = [];
		$max = mb_strlen($keyspace, '8bit') - 1;
		for ($i = 0; $i < $length; ++$i) {
			$pieces []= $keyspace[random_int(0, $max)];
		}
		return implode('', $pieces);
	}

	private function hashPassword($password)
	{
		return password_hash($password, PASSWORD_DEFAULT);
	}
}