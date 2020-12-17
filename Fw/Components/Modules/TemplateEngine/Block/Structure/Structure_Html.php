<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 17.07.2020
 */

namespace Fw\Components\Modules\TemplateEngine\Block\Structure;


class Structure_Html extends Structure
{
	protected $name   = 'Html';

	public function scripts($pageData, $appData)
	{
		$result = '';
		foreach ($appData["assets"]["scripts"] as $script)
		{
			$result .= "<script src=\"{$script}\"></script>\n";
		}
		return $result;
	}

	public function styles($pageData, $appData)
	{
		$result = '';
		foreach ($appData["assets"]["styles"] as $style)
		{
			$result .= "<link href=\"{$style}\" rel=\"stylesheet\" type=\"text/css\">\n";
		}
		return $result;
	}
}