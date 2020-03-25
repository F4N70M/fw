<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 17.03.2020
 */

namespace Fw\Components\Providers;


use Fw\Di\Container;

class Provider_Objects
{
	protected $container;

	public function __construct(Container $container)
	{
		$this->container = $container;

		/**
		 * Treat Manager : TreatManager
		 */
		$class = \Fw\Components\Modules\Objects\ObjectManager::class;
		$container->setAlias('ObjectManager', $class);

		$this->container->set(
			$class,
			function(Container $container, $parameters=[]) {
				$instance = $container->getInstance(\Fw\Components\Modules\Objects\ObjectManager::class, $parameters);
				return $instance;
			},
			true
		);

		/**
		 * Treat Manager : Treat
		 */
		$class = \Fw\Components\Modules\Objects\Obj::class;
		$container->setAlias('Obj', $class);

		$this->container->set(
			$class,
			function(Container $container, $parameters=[]) {
				$instance = $container->getInstance(\Fw\Components\Modules\Objects\Obj::class, $parameters);
				return $instance;
			},
			false
		);
	}
}