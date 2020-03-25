<?php
/**
 * Treat: F4N70M
 * Version: 0.1
 * Date: 13.01.2020
 */

namespace Fw\Components\Modules\Template;


use Exception;

class TemplateBuilder
{
	public function render($layouts)
	{
		$result = $this->parse($layouts);

		return $result;
	}

	private function parse(array $layouts)
	{
		$result = '';
		foreach ($layouts as $layout) {
			if (isset($layout['tpl']))
			{
				if (!isset($layout['name'])) $layout['name'] = 'default';
				if (!isset($layout['data'])) $layout['data'] = [];

				foreach ($layout['data'] as $key => $value)
				{
					if (is_array($value) && isset($value[0]['tpl']))
					{
						$layout['data'][$key] = $this->parse($value);
					}
				}

				$className = __NAMESPACE__."\\Block\\".ucfirst($layout['tpl'])."\\".ucfirst($layout['tpl']).ucfirst($layout['name'])."_Tpl";
				if (!class_exists($className))
				{
					$className = __NAMESPACE__."\\Block\\Tag\\TagDefault_Tpl";
					$layout['data']['tag'] = $layout['tpl'];
				}
				$tpl = new $className($layout['data']);

				$result .= $tpl->get();
			}
		}
		return $result;
	}
}