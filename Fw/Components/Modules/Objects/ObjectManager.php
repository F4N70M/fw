<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 17.03.2020
 */

namespace Fw\Components\Modules\Objects;

use Fw\Components\Services\Items\ItemManager;
use Fw\Components\Services\Entity\Entity;

class ObjectManager extends ItemManager
{
	public function __construct(Entity $Entity)
	{
		$entityName = 'objects';
		parent ::__construct($Entity, $entityName);
	}
}