<?php
/**
 * Project: F4N70M
 * Version: 0.1
 * Date: 10.01.2020
 */

namespace Fw\Components\Providers;

use Fw\Di\Container;

class Provider_Db
{
	protected $container;

	/**
	 * Provider_Db constructor.
	 * @param Container $container
	 */
	public function __construct(Container $container)
	{
	$this->container = $container;

		$class = \Fw\Components\Services\Database\Connection::class;
		$container->setAlias('Connection', $class);

		$this->container->set(
			$class,
			function (Container $container) {

				//  $ip = $_SERVER['SERVER_ADDR'];
				$ip = $_SERVER['REMOTE_ADDR'];
				$connections = $container->get('config')['db'];
				$connection = array_key_exists($ip, $connections) ? $connections[$ip] : $connections['default'];

				$parameters = [
					'connection' => $connection
				];

				$instance = $container->getInstance(\Fw\Components\Services\Database\Connection::class,$parameters);
				return $instance;
			},
			true
		);

		$class = \Fw\Components\Services\Database\QueryBuilder::class;
		$container->setAlias('QueryBuilder', $class);

		$this->container->set(
			$class,
			function(Container $container) {
				$instance = $container->getInstance(\Fw\Components\Services\Database\QueryBuilder::class);
				return $instance;
			},
			true
		);

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