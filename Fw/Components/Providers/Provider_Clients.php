<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 19.03.2020
 */

namespace Fw\Components\Providers;


use Fw\Di\Container;

class Provider_Clients
{
	protected $container;

	public function __construct(Container $container)
	{
		$this->container = $container;

		/**
		 * ClientManager
		 */
		$class = \Fw\Components\Modules\Clients\ClientManager::class;
		$container->setAlias('ClientManager', $class);

		$this->container->set(
			$class,
			function(Container $container, $parameters=[]) {
				$instance = $container->getInstance(\Fw\Components\Modules\Clients\ClientManager::class, $parameters);
				return $instance;
			},
			true
		);

		/**
		 * Client
		 */
		$class = \Fw\Components\Modules\Clients\Client::class;
		$container->setAlias('Client', $class);

		$this->container->set(
			$class,
			function(Container $container, $parameters=[]) {
				$instance = $container->getInstance(\Fw\Components\Modules\Clients\Client::class, $parameters);
				return $instance;
			},
			false
		);
	}
}