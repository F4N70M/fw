<?php
/**
 * Project: F4N70M
 * Version: 0.1
 * Date: 13.01.2020
 */

namespace Fw\Components\Providers;


use Fw\Di\Container;

class Provider_Tpl
{

	protected $container;


	/**
	 * Provider_Auth constructor.
	 * @param Container $container
	 */
	public function __construct(Container $container)
	{
		$this->container = $container;

		$class = \Fw\Components\Modules\Tpl\Tpl::class;
		$container->setAlias('Tpl', $class);

		$this->container->set(
			$class,
			function(\Fw\Di\Container $container)
			{
				$instance = $container->getInstance(\Fw\Components\Modules\Tpl\Tpl::class);
				return $instance;
			},
			true
		);
	}
}