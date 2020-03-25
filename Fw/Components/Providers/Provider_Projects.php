<?php
/**
 * Treat: F4N70M
 * Version: 0.1
 * Date: 10.01.2020
 */

namespace Fw\Components\Providers;

use Fw\Di\Container;

class Provider_Projects
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
		 * Project Manager : ProjectManager
		 */
		$class = \Fw\Components\Modules\Projects\ProjectManager::class;
		$container->setAlias('ProjectManager', $class);

		$this->container->set(
			$class,
			function(Container $container, $parameters=[]) {
				$instance = $container->getInstance(\Fw\Components\Modules\Projects\ProjectManager::class, $parameters);
				return $instance;
			},
			true
		);

		/**
		 * Project Manager : Project
		 */
		$class = \Fw\Components\Modules\Projects\Project::class;
		$container->setAlias('Project', $class);

		$this->container->set(
			$class,
			function(Container $container, $parameters=[]) {
				$instance = $container->getInstance(\Fw\Components\Modules\Projects\Project::class, $parameters);
				return $instance;
			},
			false
		);
	}
}