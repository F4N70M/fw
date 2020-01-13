<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 10.01.2020
 */

namespace Fw\Components\Providers;


use Fw\Di\Container;

class Provider_Entity
{
	protected $container;

	/**
	 * Provider_Entity constructor.
	 * @param Container $container
	 */
	public function __construct(Container $container)
	{
		$this->container = $container;

		$class = \Fw\Components\Services\Entity\Entity::class;
		$container->setAlias('Entity', $class);

		$this->container->set(
			$class,
			function(\Fw\Di\Container $container) {
				$instance = $container->getInstance(\Fw\Components\Services\Entity\Entity::class);
				return $instance;
			},
			true
		);
	}
}