<?php
/**
 * Project: F4N70M
 * Version: 0.1
 * Date: 10.01.2020
 */

namespace Fw\Components\Providers;

use Fw\Di\Container;

class Provider_Router
{
	protected $container;

	/**
	 * Provider_Router constructor.
	 * @param Container $container
	 */
	public function __construct(Container $container)
	{
		$this->container = $container;

		$class = \Fw\Components\Services\Router\Router::class;
		$container->setAlias('Router', $class);

		$this->container->set(
			$class,
			function(\Fw\Di\Container $container) {
				$instance = $container->getInstance(\Fw\Components\Services\Router\Router::class);
				return $instance;
			},
			true
		);
	}
}