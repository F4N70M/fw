<?php
/**
 * Treat: F4N70M
 * Version: 0.1
 * Date: 10.01.2020
 */

namespace Fw\Components\Providers;


use Fw\Di\Container;

class Provider_Cookie
{
	protected $container;

	/**
	 * Provider_Cookie constructor.
	 * @param Container $container
	 */
	public function __construct(Container $container)
	{
	$this->container = $container;

		$class = \Fw\Components\Services\Cookie\Cookie::class;
		$container->setAlias('Cookie', $class);

		$this->container->set(
			$class,
			function (\Fw\Di\Container $container) {
				$obj = new \Fw\Components\Services\Cookie\Cookie();
				return $obj;
			},
			true
		);
	}
}