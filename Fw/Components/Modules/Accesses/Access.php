<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 17.03.2020
 */

namespace Fw\Components\Modules\Accesses;

//TODO: Переименовать в entrance

use Fw\Components\Services\Entity\Entity;
use Fw\Components\Modules\Objects\Obj;

class Access extends Obj
{
	public function __construct(Entity $Entity, int $id)
	{
		parent ::__construct($Entity, $id);
		$this->where['type'] = 'access';
	}
}