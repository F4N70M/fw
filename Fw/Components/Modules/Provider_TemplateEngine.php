<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 03.06.2020
 */

namespace Fw\Components\Modules;

use Fw\Components\Classes\Provider;
use Fw\Components\Di\Container;

class Provider_TemplateEngine extends Provider
{
	protected $container;

	/**
	 * Provider_TemplateEngine constructor.
	 * @param Container $container
	 */
	public function __construct(Container $container)
	{
		parent::__construct($container);

		$this->init(
			\Fw\Components\Modules\TemplateEngine\TemplateEngine::class,
			'TemplateEngine',
			true,
			[
				'templatesDir' => THEMES_DIR
			]
		);
		$this->init(
			\Fw\Components\Modules\TemplateEngine\Template::class,
			'Template',
			false
		);
		$this->init(
			\Fw\Components\Modules\TemplateEngine\Palette::class,
			'Palette',
			false
		);
	}
}