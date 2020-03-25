<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 25.03.2020
 */

namespace Fw\Components\Providers;


use Fw\Di\Container;

class Provider_Relations
{
	protected $container;

	/**
	 * Provider_Relations constructor.
	 * @param Container $container
	 */
	public function __construct(Container $container)
	{
		$this->container = $container;

		$class = \Fw\Components\Services\Relations\Relations::class;
		$container->setAlias('Relations', $class);

		$this->container->set(
			$class,
			function(\Fw\Di\Container $container, $parameters=[]) {
				$instance = $container->getInstance(\Fw\Components\Services\Relations\Relations::class, $parameters);
				return $instance;
			},
			true
		);
	}
}