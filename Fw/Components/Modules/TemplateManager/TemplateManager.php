<?php
/**
 * Project: F4N70M
 * Version: 0.1
 * Date: 10.02.2020
 */

namespace Fw\Components\Modules\TemplateManager;

use Exception;

class TemplateManager
{
	private $templates = [];

	public function __construct()
	{
		$this->init();
//		debug($this->getTemplatesForApp('main'));
	}

	/**
	 * @param string $app
	 * @return mixed
	 * @throws Exception
	 */
	public function getTemplatesForApp(string $app)
	{
		if (!$this->hasTemplatesForApp($app))
			throw new \Exception("Not found templates for app \"{$app}\"");
		return $this->templates[$app];
	}

	private function hasTemplatesForApp(string $app)
	{
		return (isset($this->templates[$app]) && is_array($this->templates[$app]) && !empty($this->templates[$app]));
	}

	/**
	 * return void
	 */
	private function init()
	{
		$glob = APPS_DIR . "/*/template/*/";
		$pattern = "#".APPS_DIR."/([^/]+)/template/([^/]+)/#iu";
		foreach (glob($glob) as $path)
		{
			preg_match($pattern,$path,$matches);
			$this->templates[$matches[1]][] = $matches[2];
		}
	}
}