<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 04.06.2020
 */

namespace Fw\Components\Modules\TemplateEngine\Wireframe;

use Fw\Components\Modules\TemplateEngine\Template;

class Wireframe_SC extends Wireframe
{
	public function __construct(Template $content, Template $sidebar)
	{
		parent::__construct($content, null, $sidebar, null);
		$this->sidebarPosition = self::SIDEBAR_LEFT;
	}
}