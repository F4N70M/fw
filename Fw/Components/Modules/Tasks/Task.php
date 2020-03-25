<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 23.03.2020
 */

namespace Fw\Components\Modules\Tasks;

use Fw\Components\Services\Entity\Entity;
use Fw\Components\Modules\Objects\Obj;

class Task extends Obj
{
	/**
	 * Task constructor.
	 * @param Entity $Entity
	 * @param int $id
	 */
	public function __construct(Entity $Entity, int $id)
	{
		parent ::__construct($Entity, $id);
		$this->where['type'] = 'task';
	}
}