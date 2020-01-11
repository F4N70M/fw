<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 10.01.2020
 */

namespace Fw\Components\Providers;


use Fw\Di\Container;
use Fw\Components\Providers\Components_Provider;

class Provider_Cookie extends Components_Provider
{
	/**
	 * Provider_Account constructor.
	 * @param Container $container
	 */
	public function __construct(Container $container)
	{
		parent ::__construct($container);

		$class = \Fw\Components\Services\Cookie\Cookie::class;
		$container -> setAlias('Cookie', $class);

		$this -> container -> set(
			$class,
			function (\Fw\Di\Container $container) {
				$obj = new \Fw\Components\Services\Cookie\Cookie();
				return $obj;
			},
			true
		);
	}
}