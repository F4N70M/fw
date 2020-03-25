<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 13.03.2020
 */

namespace Fw\Components\Providers;


use Fw\Di\Container;

class Provider_Treats
{
	protected $container;

	/**
	 * Provider_Users constructor.
	 * @param Container $container
	 */
	public function __construct(Container $container)
	{
		$this->container = $container;

		$class = \Fw\Components\Modules\Treats\Treats::class;
		$container->setAlias('Treats', $class);
		$container->set(
			$class,
			function(\Fw\Di\Container $container) {
				$instance = $container->getInstance(\Fw\Components\Modules\Treats\Treats::class);
				return $instance;
			},
			true
		);
	}
}