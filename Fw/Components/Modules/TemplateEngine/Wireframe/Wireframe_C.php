<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 04.06.2020
 */

namespace Fw\Components\Modules\TemplateEngine\Wireframe;


use Fw\Components\Modules\TemplateEngine\Wireframe;
use Fw\Components\Modules\TemplateEngine\Template;

class Wireframe_C extends Wireframe
{
	public function __construct(Template $content)
	{
		parent::__construct($content, null, null, null);
	}
}