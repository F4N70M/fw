<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 04.06.2020
 */

namespace Fw\Components\Modules\TemplateEngine\Wireframe;

use Fw\Components\Modules\TemplateEngine\Template;

class Wireframe_HSCF extends Wireframe
{
	public function __construct(Template $content, Template $header=null, Template $sidebar=null, Template $footer=null)
	{
		parent::__construct($content, $header, $sidebar, $footer);
		$this->sidebarPosition = self::SIDEBAR_LEFT;
	}
}