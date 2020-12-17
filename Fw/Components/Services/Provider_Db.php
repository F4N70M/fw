<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 10.01.2020
 */

namespace Fw\Components\Services;

use Fw\Components\Classes\Provider;
use Fw\Components\Di\Container;
use Exception;

class Provider_Db extends Provider
{
	protected $container;

	/**
	 * Provider_Db constructor.
	 * @param Container $container
	 * @throws Exception
	 */
	public function __construct(Container $container)
	{
		parent::__construct($container);

		$ip = $_SERVER['REMOTE_ADDR'];
		$connections = $this->container->get('config')['db'];
		$connection = array_key_exists($ip, $connections) ? $connections[$ip] : $connections['default'];
		$parameters = [
			'host'     => $connection['HOST'],
			'user'     => $connection['USER'],
			'password' => $connection['PASS'],
			'base'     => $connection['BASE']
		];

		$this->init(
			\Fw\Components\Services\Database\Connection::class,
			'Connection',
			true,
			$parameters
		);
//        $this->init(
//            \Fw\Components\Services\Database\EntityQueryBuilder::class,
//            'Db',
//            true
//        );
        $this->init(
            \Fw\Components\Services\Database\QueryBuilder::class,
            'Db',
            true
        );
		/*$this->init(
			\Fw\Components\Services\Database\EntityQueryBuilder::class,
			'EntityQueryBuilder',
			true
		);*/
	}
}