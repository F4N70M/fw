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
		$this->defineAssetsUri();
		$this->setStyle('base',ASSETS_SRC_URI.'/style/base.css',-1);

		$this->setScript('base',ASSETS_SRC_URI.'/script/base.js',0,['jquery']);
		$this->setScript('jquery',ASSETS_SRC_URI.'/script/jquery-3.4.1.min.js',-1);
	}


	private function defineAssetsUri()
	{
		$assetsSrcUri = str_replace('\\', '/', str_replace(getcwd(),'',__DIR__)).'/src';
		define('ASSETS_SRC_URI',$assetsSrcUri);
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

		$this->list[$type][$id] = [
			'url'           => $url,
			'dependencies'  => $dependencies,
			'priority'      => $priority,
		];
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
//		debug($this->list);
		$result = [[],[]];
		$prepare = $this->prepare();
		foreach ($prepare as $type => $items)
		{
			foreach ($items as $id => $priority)
			{
				$item = $this->list[$type][$id];
				$item['type'] = $type;
				unset($item['dependencies']);
				unset($item['preload']);
				$result[($priority >= 0 ? 1 : 0)][] = $item;
			}
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
		foreach ($this->list as $type => $items)
		{
			foreach ($items as $id => $item)
			{
				$this->prepareItem($type, $id, $item['dependencies'], $item['priority']);
			}
		}

		return $this->prepare;
	}

	/**
	 * @param string $type
	 * @param string $id
	 * @param array $dependencies
	 * @param int $priority
	 */
	private function prepareItem(string $type, string $id, array $dependencies, int $priority = 0)
	{
		if (is_array($dependencies) && !empty($dependencies))
		{
			foreach ($dependencies as $dependencyId)
			{
				$dependency = $this->list[$type][$dependencyId];

//				$dependencyPreload = $preload
//					|| $dependency['preload']
//					|| (isset($this->prepare[$dependencyId]) ? $this->prepare[$dependencyId] : false);
				$dependencyPriority = min(
					$priority,
					$dependency['priority'],
					(isset($this->prepare[$type][$dependencyId]) ? $this->prepare[$type][$dependencyId] : 1)
				);
				$this->prepareItem($type, $dependencyId, $dependency['dependencies'], $dependencyPriority);
			}
		}
		$itemPriority = min(
			$priority,
			$this->list[$type][$id]['priority'],
			(isset($this->prepare[$type][$id]) ? $this->prepare[$type][$id] : 1)
		);
		$this->prepare[$type][$id] = $itemPriority;
	}
}