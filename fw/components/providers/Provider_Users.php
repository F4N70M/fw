<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 10.01.2020
 */

namespace Fw\Components\Providers;


use Fw\Di\Container;
use Fw\Components\Providers\Components_Provider;

class Provider_Users extends Components_Provider
{
	/**
	 * Provider_Users constructor.
	 * @param Container $container
	 */
	public function __construct(Container $container)
	{

		$class = \Fw\Components\Modules\Users\Users::class;
		$container->setAlias('Users', $class);
		$container->set(
			$class,
			function(\Fw\Di\Container $container) {
				return new \Fw\Components\Modules\Users\Users($container->get('Db'));
			},
			true
		);

		/*
		$class = \Fw\Components\Modules\Users\User::class;
		$container->setAlias('User', $class);
		$container->set(
			$class,
			function(\Fw\Di\Container $container,$parameters=[]) {
				$class = \Fw\Components\Modules\Users\User::class;
				$instance = $this->getInstance($class, $parameters);
				return $instance;
			},
			false
		);
		*/
	}
}