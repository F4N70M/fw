<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 17.03.2020
 */

namespace Fw\Components\Modules\Projects;


use Fw\Components\Modules\Tickets\TicketManager;
use Fw\Components\Services\Entity\Entity;
use Fw\Components\Modules\Objects\Obj;

class Project extends Obj
{
	protected $TicketManager;

	public function __construct(Entity $Entity, TicketManager $TicketManager, int $id)
	{
		parent ::__construct($Entity, $id);
		$this->where['type'] = 'project';
		$this->TicketManager = $TicketManager;
	}


	public function getTickets()
	{
		$data = $this->info();
//		debug($data['id']);
		$where = ['project' => $data['id']];
		return $this->TicketManager->get($where);

	}
}