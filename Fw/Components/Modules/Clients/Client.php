<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 19.03.2020
 */

namespace Fw\Components\Modules\Clients;


use Fw\Components\Modules\Users\User;
use Fw\Components\Services\Entity\Entity;
use Fw\Components\Modules\Objects\Obj;

class Client extends User
{
	public function __construct(Entity $Entity, int $id)
	{
		parent ::__construct($Entity, $id);
//		$this->where['type'] = 'access';
	}
}