<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 17.03.2020
 */

namespace Fw\Components\Modules\Projects;


use Fw\Components\Services\Entity\Entity;
use Fw\Components\Modules\Objects\ObjectManager;

class ProjectManager extends ObjectManager
{
	protected $entityType = 'project';
}