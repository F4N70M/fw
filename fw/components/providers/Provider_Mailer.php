<?php
/**
 * Project: F4N70M
 * Version: 0.1
 * Date: 10.01.2020
 */

namespace Fw\Components\Providers;

use Fw\Di\Container;

class Provider_Mailer
{
	protected $container;

	/**
	 * Provider_Mailer constructor.
	 * @param Container $container
	 */
	public function __construct(Container $container)
	{
		$this->container = $container;

		$class = \Fw\Components\Services\Mailer\Mailer::class;
		$container->setAlias('Mailer', $class);

		$this->container->set(
			$class,
			function(\Fw\Di\Container $container)
			{
				$config = $container['config']['mail'];

				$parameters = [
					'config' => $config
				];
				$instance = $container->getInstance(\Fw\Components\Services\Mailer\Mailer::class,$parameters);
				return $instance;
			},
			true
		);
	}
}