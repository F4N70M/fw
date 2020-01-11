<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 10.01.2020
 */

namespace Fw\Components\Providers;


use Fw\Di\Container;
use Fw\Components\Providers\Components_Provider;

class Provider_Auth extends Components_Provider
{
	/**
	 * Provider_Account constructor.
	 * @param Container $container
	 */
	public function __construct(Container $container)
	{
		parent ::__construct($container);

		$class = \Fw\Components\Services\Auth\Auth::class;
		$container -> setAlias('Auth', $class);

		$this->container->set(
			$class,
			function(\Fw\Di\Container $container)
			{
//				$obj = new \Fw\Components\Services\Auth\Auth(
//					$container->get('Db'),
//					$container->get('Cookie')
//				);
//				return $obj;
				$instance = parent::getInstance(\Fw\Components\Services\Auth\Auth::class);
				return $instance;
			},
			true
		);
	}
}