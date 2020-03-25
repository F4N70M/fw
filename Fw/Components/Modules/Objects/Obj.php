<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 17.03.2020
 */

namespace Fw\Components\Modules\Objects;

use Fw\Components\Services\Items\Item;
use Fw\Components\Services\Entity\Entity;

class Obj extends Item
{
	/**
	 * Obj constructor.
	 * @param Entity $Entity
	 * @param int $id
	 */
	public function __construct(Entity $Entity, int $id)
	{
		$entityName = 'objects';
		parent ::__construct($Entity, $entityName, $id);
	}
}