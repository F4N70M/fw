<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 10.01.2020
 */

namespace Fw\Components\Services;

use Fw\Components\Classes\Provider;
use Fw\Components\Di\Container;

class Provider_Mailer extends Provider
{
	protected $container;

	/**
	 * Provider_Cookie constructor.
	 * @param Container $container
	 */
	public function __construct(Container $container)
	{
		parent::__construct($container);

		$this->init(
			\Fw\Components\Services\Mailer\Mailer::class,
			'Mailer',
			true
		);
	}
}