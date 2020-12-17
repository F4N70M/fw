<?php
/**
 * Treat: F4N70M
 * Version: 0.1
 * Date: 10.01.2020
 */

namespace Fw\Components\Services;

use Fw\Components\Classes\Provider;
use Fw\Components\Di\Container;

class Provider_Account extends Provider
{
	protected $container;

	/**
	 * Provider_Account constructor.
	 * @param Container $container
	 */
	public function __construct(Container $container)
	{
		parent::__construct($container);

		$this->init(
			\Fw\Components\Services\Account\Account::class,
			'Account',
			true
		);
	}
}