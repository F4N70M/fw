<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 17.03.2020
 */

namespace Fw\Components\Modules\Treats;


use Fw\Components\Services\Entity\Entity;
use Fw\Components\Modules\Objects\Obj;

class Treat extends Obj
{
	public function __construct(Entity $Entity, int $id)
	{
		parent ::__construct($Entity, $id);
		$this->where['type'] = 'treat';
	}
}