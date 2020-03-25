<?php
/**
 * Treat: F4N70M
 * Version: 0.1
 * Date: 10.01.2020
 */

namespace Fw\Components\Providers;

use Fw\Di\Container;

class Provider_Tasks
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
		 * TaskManager
		 */
		$class = \Fw\Components\Modules\Tasks\TaskManager::class;
		$container->setAlias('TaskManager', $class);

		$this->container->set(
			$class,
			function(Container $container, $parameters=[]) {
				$instance = $container->getInstance(\Fw\Components\Modules\Tasks\TaskManager::class, $parameters);
				return $instance;
			},
			true
		);

		/**
		 * Task
		 */
		$class = \Fw\Components\Modules\Tasks\Task::class;
		$container->setAlias('Task', $class);

		$this->container->set(
			$class,
			function(Container $container, $parameters=[]) {
				$instance = $container->getInstance(\Fw\Components\Modules\Tasks\Task::class, $parameters);
				return $instance;
			},
			false
		);
	}
}