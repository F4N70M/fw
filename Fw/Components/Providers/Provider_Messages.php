<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 18.03.2020
 */

namespace Fw\Components\Providers;

use Fw\Di\Container;

class Provider_Messages
{
	protected $container;

	/**
	 * Provider_Messages constructor.
	 * @param Container $container
	 */
	public function __construct(Container $container)
	{
		$this->container = $container;

		/**
		 * MessageManager
		 */
		$class = \Fw\Components\Modules\Messages\MessageManager::class;
		$container->setAlias('MessageManager', $class);

		$this->container->set(
			$class,
			function(Container $container, $parameters=[]) {
				$instance = $container->getInstance(\Fw\Components\Modules\Messages\MessageManager::class, $parameters);
				return $instance;
			},
			true
		);

		/**
		 * Message
		 */
		$class = \Fw\Components\Modules\Messages\Message::class;
		$container->setAlias('Message', $class);

		$this->container->set(
			$class,
			function(Container $container, $parameters=[]) {
				$instance = $container->getInstance(\Fw\Components\Modules\Messages\Message::class, $parameters);
				return $instance;
			},
			false
		);
	}
}