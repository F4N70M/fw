<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 10.01.2020
 */

namespace Fw\Components\Providers;


use Fw\Di\Container;
use Fw\Components\Providers\Components_Provider;

class Provider_Account extends Components_Provider
{
	/**
	 * Provider_Account constructor.
	 * @param Container $container
	 */
	public function __construct(Container $container)
	{
		parent::__construct($container);

		$class = \Fw\Components\Modules\Account\Account::class;
		$container->setAlias('Account', \Fw\Components\Modules\Account\Account::class);

		$this->container->set(
			$class,
			function(\Fw\Di\Container $container,$parameters=[]) {
				$parameters['users'] = $container->get('Users');
				$instance = parent::getInstance(\Fw\Components\Modules\Account\Account::class, $parameters);
				return $instance;
			},
			true
		);
	}
}