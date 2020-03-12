<?php
/**
 * Project: F4N70M
 * Version: 0.1
 * Date: 10.01.2020
 */

namespace Fw\Components\Modules\Users;

class User
{
	private $users;
	private $id;
	private $data;

	public function __construct(Projects $users, int $id)
	{
		$this->users = $users;
		$this->id = $id;

		$this->data = $this->users->get($id);
	}


}