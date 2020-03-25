<?php
/**
 * Treat: F4N70M
 * Version: 0.1
 * Date: 10.01.2020
 */

namespace Fw\Components\Providers;

use Fw\Di\Container;

class Provider_Accesses
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
		 * AccessManager
		 */
		$class = \Fw\Components\Modules\Accesses\AccessManager::class;
		$container->setAlias('AccessManager', $class);

		$this->container->set(
			$class,
			function(Container $container, $parameters=[]) {
				$instance = $container->getInstance(\Fw\Components\Modules\Accesses\AccessManager::class, $parameters);
				return $instance;
			},
			true
		);

		/**
		 * Access
		 */
		$class = \Fw\Components\Modules\Accesses\Access::class;
		$container->setAlias('Access', $class);

		$this->container->set(
			$class,
			function(Container $container, $parameters=[]) {
				$instance = $container->getInstance(\Fw\Components\Modules\Accesses\Access::class, $parameters);
				return $instance;
			},
			false
		);
	}
}