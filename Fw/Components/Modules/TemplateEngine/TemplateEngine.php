<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 03.06.2020
 */

namespace Fw\Components\Modules\TemplateEngine;


use Fw\Components\Services\Cache\Cache;

class TemplateEngine
{
	protected $cache;
	protected $templatesDir;

	/**
	 * TemplateEngine constructor.
	 * @param Cache $cache
	 * @param string $templatesDir
	 */
	public function __construct(Cache $cache, string $templatesDir)
	{
		$this->cache = $cache;
		$this->templatesDir = $templatesDir;

	}

	/**
	 * @param $family
	 * @param string $name
	 * @return bool
	 */
	public function getBlock($family, $name = 'default', array $data=[])
	{
		$class = __NAMESPACE__
			. '\\Block\\' . ucfirst($family) . '\\'
			. ucfirst($family) . '_' . ucfirst($name);

		if (class_exists($class))
		{
			$block = new $class($data);

			return $block;
		}
		return false;
	}

	/**
	 * @param array $tpl
	 * @param array $pageData
	 * @param array $appData
	 */
	public function render(array $tpl, array $pageData=[], array $appData=[])
	{
		$template = $this->convertArrayConfigToObject($tpl);

		$html = $this->prepare($template, $pageData, $appData);
//		$this->execute($template);

		echo $html;
	}

	/**
	 * @param Template $obj
	 */
	private function execute(Block $obj)
	{
	}

	/**
	 * @param Block $t
	 * @param array $pageData
	 * @param array $appData
	 * @return mixed
	 */
	private function prepare(Block $t, array $pageData = [], array $appData = [])
	{
		$assets = ['styles'=>$t->getStyles(),'scripts'=>$t->getScripts()];
//		debug($assets);
		$appData['assets'] = $assets;

		$result = $this->prepareBlock($t, $pageData, $appData);
		return $result['html'];
	}

	/**
	 * @param Block $b
	 * @param array $appData
	 * @param array $pageData
	 * @return array
	 */
	private function prepareBlock(Block $b, array $pageData, array $appData)
	{
		$tpl = $b->getTemplate();
		$data = $b->getData();
		$data['blocks'] = '';
		foreach ($b->getBlocks() as $block)
		{
			$blockResult = $this->prepareBlock($block, $pageData, $appData);
			$data['blocks'] .= $blockResult['html'];
		}

//		$tpl = $this->substitution('#{% (data\:\:)?([A-z.]+) %}#', $b, $data, $pageData, $appData, $tpl);
		// подстановка констант
		$tpl = $this->substitution('#{% (const)\:\:([A-z_]+) %}#', $b, $data, $pageData, $appData, $tpl);
		// подстановка значений блока
		$tpl = $this->substitution('#{% (data\:\:)?([A-z.]+) %}#', $b, $data, $pageData, $appData, $tpl);
		// подстановка результатов исполнения экшенов
		$tpl = $this->substitution('#{% (action)\:\:([A-z]+) %}#', $b, $data, $pageData, $appData, $tpl);
		// подстановка значений страницы
		$tpl = $this->substitution('#{% (page)\:\:([A-z.]+) %}#', $b, $data, $pageData, $appData, $tpl);
		// подстановка значений приложения
		$tpl = $this->substitution('#{% (app)\:\:([A-z.]+) %}#', $b, $data, $pageData, $appData, $tpl);

		return [
			'html' => $tpl
		];
	}

	/**
	 * @param $pattern
	 * @param $block
	 * @param $pageData
	 * @param $appData
	 * @param string $string
	 * @return mixed|string
	 */
	private function substitution(string $pattern, Block $block, array $data, array $pageData, array $appData, string $string)
	{
		preg_match_all($pattern, $string, $matches);

		foreach ($matches[2] as $matchKey => $key)
		{

			if ($matches[1][$matchKey] == "foreach")
			{}
			elseif ($matches[1][$matchKey] == "const")
			{
				$constants = get_defined_constants();
				$string = str_replace(
					$matches[0][$matchKey],
					array_key_exists($key, $constants)
						? $constants[$key]
						: null
					, $string
				);
			}
			// Проверка методов
			elseif ($matches[1][$matchKey] == "action")
			{
				$string = str_replace($matches[0][$matchKey], method_exists($block,$key)
					? $block->$key($pageData, $appData)
					: null, $string);
			}
			// Проверка значений
			elseif($matches[1][$matchKey] =="")
			{
				$string = str_replace($matches[0][$matchKey], isset($data[$key]) ? $data[$key] : null, $string);
			}
			// Проверка значений
			elseif($matches[1][$matchKey] =="app")
			{
				$string = str_replace($matches[0][$matchKey], isset($appData[$key]) ? $appData[$key] : null, $string);
			}
			// Проверка значений
			elseif($matches[1][$matchKey] =="page")
			{
				$string = str_replace($matches[0][$matchKey], isset($pageData[$key]) ? $pageData[$key] : null, $string);
			}
			// Иначе
			else
				$string = str_replace($matches[0][$matchKey], null, $string);
		}
		return $string;
	}

	private function convertArrayConfigToObject(array $tpl)
	{
		$data = isset($tpl['data']) && is_array($tpl['data']) ? $tpl['data'] : [];

		$block = $this->getBlock($tpl['family'], $tpl['name'], $data);

		if ($block && isset($tpl['blocks']) && is_array($tpl['blocks']))
		{
			foreach ($tpl['blocks'] as $subTpl)
			{
				$subBlock = $this->convertArrayConfigToObject($subTpl);
				if (is_object($subBlock) && $subBlock instanceof Block)
					$block->addBlock($subBlock);
			}
		}
		return $block;
	}
}