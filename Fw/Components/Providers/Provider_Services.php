<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 17.03.2020
 */

namespace Fw\Components\Providers;


use Fw\Di\Container;

class Provider_Services
{
	protected $container;

	public function __construct(Container $container)
	{
		$this->container = $container;

		/**
		 * ServiceManager
		 */
		$class = \Fw\Components\Modules\Services\ServiceManager::class;
		$container->setAlias('ServiceManager', $class);

		$this->container->set(
			$class,
			function(Container $container, $parameters=[]) {
				$instance = $container->getInstance(\Fw\Components\Modules\Services\ServiceManager::class, $parameters);
				return $instance;
			},
			true
		);

		/**
		 * Service
		 */
		$class = \Fw\Components\Modules\Services\Service::class;
		$container->setAlias('Service', $class);

		$this->container->set(
			$class,
			function(Container $container, $parameters=[]) {
				$instance = $container->getInstance(\Fw\Components\Modules\Services\Service::class, $parameters);
				return $instance;
			},
			false
		);
	}
}