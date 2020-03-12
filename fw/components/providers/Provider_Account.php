<?php
/**
 * Project: F4N70M
 * Version: 0.1
 * Date: 10.01.2020
 */

namespace Fw\Components\Providers;

use Fw\Di\Container;

class Provider_Account
{
	protected $container;

	/**
	 * Provider_Account constructor.
	 * @param Container $container
	 */
	public function __construct(Container $container)
	{
		$this->container = $container;

		$class = \Fw\Components\Modules\Account\Account::class;
		$container->setAlias('Account', \Fw\Components\Modules\Account\Account::class);

		$this->container->set(
			$class,
			function(\Fw\Di\Container $container,$parameters=[]) {
				$parameters['users'] = $container->get('Users');
				$instance = $container->getInstance(\Fw\Components\Modules\Account\Account::class, $parameters);
				return $instance;
			},
			true
		);
	}
}