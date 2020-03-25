<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 19.03.2020
 */

namespace Fw\Components\Modules\Clients;

use Fw\Components\Modules\Users\UserManager;
use Fw\Components\Services\Entity\Entity;

class ClientManager extends UserManager
{
	public function __construct(Entity $Entity)
	{
		parent ::__construct($Entity);
	}
}