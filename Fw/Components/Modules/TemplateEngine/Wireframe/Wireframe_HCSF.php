<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 04.06.2020
 */

namespace Fw\Components\Modules\TemplateEngine\Wireframe;

use Fw\Components\Modules\TemplateEngine\Template;

class Wireframe_HCSF extends Wireframe
{
	public function __construct(Template $content, Template $header, Template $sidebar, Template $footer)
	{
		parent::__construct($content, $header, $sidebar, $footer);
		$this->sidebarPosition = self::SIDEBAR_RIGHT;
	}
}