<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 03.06.2020
 */

namespace Fw\Components\Classes;

use Fw\Components\Di\Container;

class Provider
{
	protected $container;

	/**
	 * Provider constructor.
	 * @param Container $container
	 */
	public function __construct(Container $container)
	{
		$this->container = $container;
	}

	/**
	 * @param $class
	 * @param string|null $alias
	 * @param bool $singleton
	 * @param array $parameters
	 */
	protected function init($class, string $alias = null, bool $singleton = false, array $parameters = [])
	{
		$this->container->set(
			$class,
			function(Container $container, string $class, array $parameters=[]) {
				$instance = $container->getInstance($class, $parameters);
				return $instance;
			},
			$singleton
		);

		if (!is_null($alias))
			$this->container->setAlias($alias, $class);

		if (!empty($parameters))
		{
			foreach ($parameters as $key => $value)
			{
				if(empty($key) || is_numeric($key)) continue;
				$this->container->setParameter($class, $key, $value);
			}

		}
	}
}