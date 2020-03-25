<?php
/**
 * Treat: F4N70M
 * Version: 0.1
 * Date: 10.01.2020
 */

namespace Fw\Components\Providers;


use Fw\Di\Container;

class Provider_Auth
{
	protected $container;


	/**
	 * Provider_Auth constructor.
	 * @param Container $container
	 */
	public function __construct(Container $container)
	{
		$this->container = $container;

		$class = \Fw\Components\Services\Auth\Auth::class;
		$container->setAlias('Auth', $class);

		$this->container->set(
			$class,
			function(\Fw\Di\Container $container)
			{
				$instance = $container->getInstance(\Fw\Components\Services\Auth\Auth::class);
				return $instance;
			},
			true
		);
	}
}