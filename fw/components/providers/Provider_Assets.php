<?php
/**
 * Project: F4N70M
 * Version: 0.1
 * Date: 10.01.2020
 */

namespace Fw\Components\Providers;


use Fw\Di\Container;

class Provider_Assets
{
	protected $container;

	/**
	 * Provider_Assets constructor.
	 * @param Container $container
	 */
	public function __construct(Container $container)
	{
	$this->container = $container;

		$class = \Fw\Components\Services\Assets\Assets::class;
		$container->setAlias('Assets', $class);

		$this->container->set(
			$class,
			function (\Fw\Di\Container $container) {
				$obj = new \Fw\Components\Services\Assets\Assets();
				return $obj;
			},
			true
		);
	}
}