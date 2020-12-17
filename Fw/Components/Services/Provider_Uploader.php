<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 25.03.2020
 */

namespace Fw\Components\Services;

use Fw\Components\Classes\Provider;
use Fw\Components\Di\Container;

class Provider_Uploader extends Provider
{
	protected $container;

	/**
	 * Provider_Uploader constructor.
	 * @param Container $container
	 */
	public function __construct(Container $container)
	{
		parent::__construct($container);

		$this->init(
			\Fw\Components\Services\Uploader\Uploader::class,
			'Uploader',
			true
		);
	}
}