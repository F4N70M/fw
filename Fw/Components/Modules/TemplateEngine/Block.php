<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 04.06.2020
 */

namespace Fw\Components\Modules\TemplateEngine;


class Block
{
	protected $family;
	protected $name;
	protected $assets;
	protected $params = [];
	protected $data = [];
	protected $blocks = [];

	protected $script = [];
	protected $style  = [];

	/**
	 * Block constructor.
	 * @param array $data
	 * @param array $blocks
	 */
	public function __construct(array $data = [], array $blocks = [])
	{
		$this->data = $data;
		$this->blocks = $this->validateBlocks($blocks) ? $blocks : [];
	}

	protected function getClass()
	{
		return get_class($this);
	}
	protected function getClassName()
	{
		$exp = explode('\\', $this->getClass());
		return end($exp);
	}
	protected function getClassDir()
	{
		return dirname(ROOT_DIR . '/' . str_replace('\\', '/', $this->getClass()));
	}
	protected function getClassUri()
	{
		return dirname( '/' . str_replace('\\', '/', $this->getClass()));
	}
	protected function getTplDir()
	{
		return $this->getClassDir() . '/tpl';
	}
	protected function getStyleUri()
	{
		return $this->getClassUri() . '/style';
	}
	protected function getScriptUri()
	{
		return $this->getClassUri() . '/script';
	}

	/**
	 * @return array
	 */
	public function getScripts()
	{
		$result = [];
		foreach ($this->script as $name)
		{
			$path = $this->getScriptUri().'/'.$name;
			$result[] = $path;
		}
		foreach ($this->blocks as $block)
		{
			$result = array_unique(array_merge($result, $block->getScripts()));
		}
		return $result;
	}

	/**
	 * @return array
	 */
	public function getStyles()
	{
		$result = [];
		foreach ($this->style as $name)
		{
			$path = $this->getStyleUri().'/'.$name;
			$result[] = $path;
		}
		foreach ($this->blocks as $block)
		{
			$result = array_unique(array_merge($result, $block->getStyles()));
		}
		return $result;
	}

	/**
	 * @return array
	 */
	public function getTemplate()
	{
		$tpl = $this->getClassDir().'/tpl/'.$this->getClassName().'.tpl';
		if (file_exists($tpl))
			$content = file_get_contents($tpl);
		else
		{
			debug('File  '.$tpl.' does not exist');
			$content = null;
		}
		return  $content;
	}

	/**
	 * @param array $blocks
	 * @return bool
	 */
	protected function validateBlocks(array $blocks) {
		foreach ($blocks as $block)
		{
			if (!($block instanceof Block)) return false;
		}
		return true;
	}

	/**
	 * @param Block $block
	 */
	public function addBlock(Block $block)
	{
		$this->blocks[] = $block;
	}

	/**
	 * @return array
	 */
	public function getFamily()
	{
		return $this->family;
	}

	/**
	 * @return array
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return array
	 */
	public function getParams()
	{
		return $this->params;
	}

	/**
	 * @return array
	 */
	public function getData()
	{
		return $this->data;
	}

	/**
	 * @return array
	 */
	public function getBlocks()
	{
		return $this->blocks;
	}
}