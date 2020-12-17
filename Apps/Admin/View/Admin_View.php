<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 13.03.2020
 */

namespace Apps\Admin\View;

use Fw\Core;
use Exception;

class Admin_View
{
	private $Fw;
	private $model;


	/**
	 * Admin_View constructor.
	 * @param Core $Fw
	 * @param $model
	 */
	public function __construct(Core $Fw, $model)
	{
		$this->Fw = $Fw;
		$this->model = $model;
	}

	public function render($theme, $template, $data=[], $info=[])
	{
		$pathTheme = THEMES_DIR . '/' . $theme;
		$pathTemplate = $pathTheme . '/' . $template;
//		$path = THEMES_DIR . '/' . $theme . '/' . $template;

		if (file_exists($pathTemplate.'.php'))
		{
			require $pathTemplate.'.php';
		}
		elseif (file_exists($pathTemplate.'.json'))
		{
			$dirTemplate = THEMES_DIR . '/' . $theme;
			$te = $this->Fw->TemplateEngine;
			$config = $this->getConfigTemplate($dirTemplate, $template);
//			debug($config);
			$te->render($config, $data, $info);
		}
		else
		{
			throw new Exception("template file does not exist");
		}



//		debug(THEMES_DIR.'/'.$tpl);
//		if (file_exists(TEPLATE_DIR))
//		$t = $this->Fw->TemplateEngine;
//		$t->render($tpl, $data, $info);
	}

	private function getConfigTemplate($tplDir,$tplName)
	{
		// Получаем массив из файла json
		$tpl = $this->getArrayTemplateConfig($tplDir.'/'.$tplName);

		// Если это блок
		if (
			isset(
				$tpl['family'],
				$tpl['name']
			)/* or isset(
				$tpl[0]['family'],
				$tpl[0]['name']
			)*/
		)
		{
			$result = $tpl;
		}
		// Если это набор
		if (isset(
			$tpl['header'],
			$tpl['content'],
			$tpl['footer']
		))
		{
			$result = [
				'family' => 'structure',
				'name'   => 'html',
				'blocks' => [
					[
						'family' => 'structure',
						'name'   => 'head'
					],
					[
						'family' => 'structure',
						'name'   => 'body',
						'blocks' => [
							[
								'family' => 'structure',
								'name'   => 'header',
								'blocks' => is_string($tpl['header'])
									? $this->getArrayTemplateConfig($tplDir.'/'.$tpl['header'])
									: (
										is_array($tpl['header'])
											? $tpl['header']
											: []
									)
							],
							[
								'family' => 'structure',
								'name'   => 'main',
								'blocks' => is_string($tpl['content'])
									? $this->getArrayTemplateConfig($tplDir.'/'.$tpl['content'])
									: (
										is_array($tpl['content'])
											? $tpl['content']
											: []
									)
							],
							[
								'family' => 'structure',
								'name'   => 'footer',
								'blocks' => is_string($tpl['footer'])
									? $this->getArrayTemplateConfig($tplDir.'/'.$tpl['footer'])
									: (
										is_array($tpl['footer'])
											? $tpl['footer']
											: []
									)
							]
						]
					]
				]
			];
		}
		return $result;
	}

	private function getArrayTemplateConfig($path)
	{
		$file = $path.'.json';
		if ( file_exists($file))
		{
			$fileContent = file_get_contents($file);
			if (is_json($fileContent))
				return json($fileContent, false);
		}
		return [];
	}
}