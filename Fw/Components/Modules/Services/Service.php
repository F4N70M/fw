<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 19.03.2020
 */

namespace Fw\Components\Modules\Services;


use Fw\Components\Modules\Objects\Obj;
use Fw\Components\Services\Entity\Entity;

class Service extends Obj
{
	public function __construct(Entity $Entity, int $id)
	{
		parent ::__construct($Entity, $id);
		$this->where['type'] = 'service';
	}
}