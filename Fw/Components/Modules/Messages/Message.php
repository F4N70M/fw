<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 18.03.2020
 */

namespace Fw\Components\Modules\Messages;


use Fw\Components\Services\Entity\Entity;
use Fw\Components\Modules\Objects\Obj;

class Message extends Obj
{
	public function __construct(Entity $Entity, int $id)
	{
		parent ::__construct($Entity, $id);
		$this->where['type'] = 'message';
	}
}