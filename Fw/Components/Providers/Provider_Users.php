<?php
/**
 * Project: F4N70M
 * Version: 0.1
 * Date: 10.01.2020
 */

namespace Fw\Components\Providers;

use Fw\Di\Container;

class Provider_Users
{
	protected $container;

	/**
	 * Provider_Users constructor.
	 * @param Container $container
	 */
	public function __construct(Container $container)
	{
		$this->container = $container;

		$class = \Fw\Components\Modules\Users\Users::class;
		$container->setAlias('Users', $class);
		$container->set(
			$class,
			function(\Fw\Di\Container $container) {
				$instance = $container->getInstance(\Fw\Components\Modules\Users\Users::class);
				return $instance;
			},
			true
		);
	}
}