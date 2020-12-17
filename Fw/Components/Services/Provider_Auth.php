<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 10.01.2020
 */

namespace Fw\Components\Services;

use Fw\Components\Classes\Provider;
use Fw\Components\Di\Container;

class Provider_Auth extends Provider
{
	protected $container;

	/**
	 * Provider_Auth constructor.
	 * @param Container $container
	 */
	public function __construct(Container $container)
	{
		parent::__construct($container);

		$this->init(
			\Fw\Components\Services\Auth\Auth::class,
			'Auth',
			true
		);
	}
}