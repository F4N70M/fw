<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 12.06.2020
 */

namespace Fw\Components\Modules\TemplateEngine\Block\Structure;

class Structure_Head extends Structure
{
	protected $name   = 'Head';

	public function meta()
	{
		return '<!--meta-->';
	}

	public function metric()
	{
		return '<!--metric-->';
	}
}