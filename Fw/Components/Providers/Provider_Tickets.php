<?php
/**
 * Treat: F4N70M
 * Version: 0.1
 * Date: 10.01.2020
 */

namespace Fw\Components\Providers;

use Fw\Di\Container;

class Provider_Tickets
{
	protected $container;

	/**
	 * Provider_Users constructor.
	 * @param Container $container
	 */
	public function __construct(Container $container)
	{
		$this->container = $container;

		/**
		 * Ticket Manager : TicketManager
		 */
		$class = \Fw\Components\Modules\Tickets\TicketManager::class;
		$container->setAlias('TicketManager', $class);

		$this->container->set(
			$class,
			function(Container $container, $parameters=[]) {
				$instance = $container->getInstance(\Fw\Components\Modules\Tickets\TicketManager::class, $parameters);
				return $instance;
			},
			true
		);

		/**
		 * Ticket Manager : Ticket
		 */
		$class = \Fw\Components\Modules\Tickets\Ticket::class;
		$container->setAlias('Ticket', $class);

		$this->container->set(
			$class,
			function(Container $container, $parameters=[]) {
				$instance = $container->getInstance(\Fw\Components\Modules\Tickets\Ticket::class, $parameters);
				return $instance;
			},
			false
		);
	}
}