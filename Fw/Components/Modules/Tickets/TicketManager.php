<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 17.03.2020
 */

namespace Fw\Components\Modules\Tickets;


use Exception;
use Fw\Components\Modules\Messages\MessageManager;
use Fw\Components\Services\Entity\Entity;
use Fw\Components\Modules\Objects\ObjectManager;

class TicketManager extends ObjectManager
{
	protected $entityName;
	protected $entityType = 'ticket';
	protected $MessageManager;


	public function __construct(Entity $Entity, MessageManager $MessageManager)
	{
		$this->MessageManager = $MessageManager;
		parent ::__construct($Entity);
	}


	/**
	 * @param array $data
	 * @return int
	 * @throws Exception
	 */
	public function new(array $data)
	{
		$dataTicket = [
			'title' => $data['title'],
			'project' => $data['project']
		];
		$ticketId =  parent ::new($dataTicket);

		return $this->MessageManager->new(['ticket'=>$ticketId,'content'=>$data['content'],'user'=>$data['user']]);

	}
}