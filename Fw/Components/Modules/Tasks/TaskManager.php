<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 23.03.2020
 */

namespace Fw\Components\Modules\Tasks;


use Exception;
use Fw\Components\Modules\Messages\MessageManager;
use Fw\Components\Services\Entity\Entity;
use Fw\Components\Modules\Objects\ObjectManager;

class TaskManager extends ObjectManager
{
	protected $entityName;
	protected $entityType = 'task';
	protected $MessageManager;


	public function __construct(Entity $Entity, MessageManager $MessageManager)
	{
		$this->MessageManager = $MessageManager;
		parent ::__construct($Entity);
	}
}