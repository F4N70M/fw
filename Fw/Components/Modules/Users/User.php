<?php
/**
 * Treat: F4N70M
 * Version: 0.1
 * Date: 10.01.2020
 */

namespace Fw\Components\Modules\Users;

use Fw\Components\Services\Items\Item;
use Fw\Components\Services\Entity\Entity;

class User extends Item
{
	public function __construct(Entity $Entity, $id)
	{
		$entityName = 'users';

		$col = ( (string) $id == (string) (int) $id ) ? 'id' : 'login';
		$result = $Entity->selectFirst($entityName,[$col=>$id]);
		$id = $result['id'];
		parent ::__construct($Entity, $entityName, $id);
	}

}