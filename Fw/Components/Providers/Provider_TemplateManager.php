<?php
/**
 * Treat: F4N70M
 * Version: 0.1
 * Date: 13.01.2020
 */

namespace Fw\Components\Providers;


use Fw\Di\Container;

class Provider_TemplateManager
{

	protected $container;


	/**
	 * Provider_TemplateManager constructor.
	 * @param Container $container
	 */
	public function __construct(Container $container)
	{
		$this->container = $container;

		$class = \Fw\Components\Modules\TemplateManager\TemplateManager::class;
		$container->setAlias('TemplateManager', $class);

		$this->container->set(
			$class,
			function(\Fw\Di\Container $container)
			{
				$instance = $container->getInstance(\Fw\Components\Modules\TemplateManager\TemplateManager::class);
				return $instance;
			},
			true
		);
	}
}