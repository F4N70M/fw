<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 10.01.2020
 */

namespace Fw\Components\Providers;


use Fw\Di\Container;
use Fw\Components\Providers\Components_Provider;

class Provider_Db extends Components_Provider
{
	/**
	 * Provider_Account constructor.
	 * @param Container $container
	 */
	public function __construct(Container $container)
	{
		parent ::__construct($container);

		$class = \Fw\Components\Services\Database\Connection::class;
		$container -> setAlias('Connection', $class);

		$this -> container -> set(
			$class,
			function (\Fw\Di\Container $container) {

				$ip = $_SERVER['SERVER_ADDR'];
				$connections = $container->get('config')['db'];
				$connection = array_key_exists($ip, $connections) ? $connections[$ip] : $connections['default'];

				$obj = new \Fw\Components\Services\Database\Connection($connection);
				return $obj;
			},
			true
		);

		$class = \Fw\Components\Services\Database\Db::class;
		$container -> setAlias('Db', $class);

		$this -> container -> set(
			$class,
			function(\Fw\Di\Container $container) {
				$obj = new \Fw\Components\Services\Database\Db($container->get('Connection'));
				return $obj;
			},
			true
		);
	}
}