<?php
/**
 * Project: F4N70M
 * Version: 0.1
 * Date: 04.02.2020
 */

namespace Fw\Components\Services\Assets;

use Exception;

class Assets
{
	private $list = [];
	private $prepare = [];
	private $ready;


	/**
	 * Auth constructor.
	 * @throws Exception
	 */
	public function __construct()
	{
		$this->setStyle('base.css','base.css',-1);
		$this->setStyle('main.css','main.css',1);
		$this->setScript('base.js','base.js',0,['jquery']);
		$this->setScript('main.js','main.js',1,['jquery']);
		$this->setScript('jquery','jquery.js',-1);
	}


	/**
	 * @param string $type
	 * @param string $id
	 * @param string $url
	 * @param int $priority -1|0|1
	 * @param array $dependencies
	 * @throws Exception
	 */
	public function set(string $type, string $id, string $url, $priority = 0, array $dependencies = [])
	{
		if ($this->has($type,$id))
			throw new Exception("\"$id\" $type already exists");

		$this->list[$id] = [
			'type'          => $type,
			'url'         => $url,
			'dependencies'  => $dependencies,
			'priority'       => $priority,
		];
	}


	/**
	 * @param string $id
	 * @param string $url
	 * @param int $priority -1|0|1
	 * @param array $dependencies
	 * @throws Exception
	 */
	public function setStyle(string $id, string $url, $priority = 0, array $dependencies = [])
	{
		$this->set('style', $id, $url, $priority, $dependencies);
	}


	/**
	 * @param string $id
	 * @param string $url
	 * @param int $priority -1|0|1
	 * @param array $dependencies
	 * @throws Exception
	 */
	public function setScript(string $id, string $url, $priority = 0, array $dependencies = [])
	{
		$this->set('script', $id, $url, $priority, $dependencies);
	}


	/**
	 *
	 */
	public function get()
	{
		$result = [[],[]];
		foreach ($this->prepare() as $itemId => $priority)
		{
			$item = $this->list[$itemId];
			unset($item['dependencies']);
			unset($item['preload']);
			$result[($priority >= 0 ? 1 : 0)][] = $item;
		}
		ksort($result);
		return $result;
	}

	/**
	 * @return array
	 */
	private function prepare()
	{
		$this->prepare = [];
		foreach ($this->list as $id => $item)
		{
			$this->prepareItem($id, $item['dependencies'], $item['priority']);
		}
		asort($this->prepare);

		return $this->prepare;
	}

	/**
	 * @param string $id
	 * @param array $dependencies
	 * @param int $priority
	 */
	private function prepareItem(string $id, array $dependencies, int $priority = 0)
	{
		if (is_array($dependencies) && !empty($dependencies))
		{
			foreach ($dependencies as $dependencyId)
			{
				$dependency = $this->list[$dependencyId];

//				$dependencyPreload = $preload
//					|| $dependency['preload']
//					|| (isset($this->prepare[$dependencyId]) ? $this->prepare[$dependencyId] : false);
				$dependencyPriority = min(
					$priority,
					$dependency['priority'],
					(isset($this->prepare[$dependencyId]) ? $this->prepare[$dependencyId] : 1)
				);
				$this->prepareItem($dependencyId, $dependency['dependencies'], $dependencyPriority);
			}
		}
		$itemPriority = min(
			$priority,
			$this->list[$id]['priority'],
			(isset($this->prepare[$id]) ? $this->prepare[$id] : 1)
		);
		$this->prepare[$id] = $itemPriority;
	}

	/**
	 * @param string $type
	 * @param string $id
	 * @return bool
	 */
	private function has(string $type, string $id)
	{
		return isset($this->list[$type][$id]);
	}
}