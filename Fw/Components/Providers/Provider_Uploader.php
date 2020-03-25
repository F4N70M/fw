<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 25.03.2020
 */

namespace Fw\Components\Providers;


use Fw\Di\Container;

class Provider_Uploader
{
	protected $container;

	/**
	 * Provider_Users constructor.
	 * @param Container $container
	 */
	public function __construct(Container $container)
	{
		$this->container = $container;

		/**
		 * Uploader
		 */
		$class = \Fw\Components\Services\Uploader\Uploader::class;
		$container->setAlias('Uploader', $class);

		$this->container->set(
			$class,
			function(Container $container, $parameters=[]) {
				$instance = $container->getInstance(\Fw\Components\Services\Uploader\Uploader::class, $parameters);
				return $instance;
			},
			true
		);
	}
}