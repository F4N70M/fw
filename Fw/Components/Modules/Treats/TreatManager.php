<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 17.03.2020
 */

namespace Fw\Components\Modules\Treats;


use Exception;
use Fw\Components\Services\Entity\Entity;
use Fw\Components\Modules\Objects\ObjectManager;

class TreatManager extends ObjectManager
{
	protected $entityType = 'treat';
}