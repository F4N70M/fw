<?php

namespace Fw\Core;

use \Fw\Di\Di;

use \Exception;

/**
 * 
 */
class Modules
{




	/*
	 *
	 */
	public	$container;

	private	$list;
	private	$di;




	/*
	 *
	 */
	function __construct(Di $di, array $modules)
	{
		$this->di = $di;

		$this->list = $modules;

		$this->initModules();

		// debug($this->container);
	}




	/*
	 *
	 */
	function initModules()
	{
		$this->setModules();
		$this->getModules();
	}




	/*
	 *
	 */
	private function setModules()
	{
		foreach ($this->list as $module)
		{
			$this->initModule($module);
		}
	}




	/*
	 *
	 */
	private function getModules()
	{

		foreach ($this->list as $module)
		{
			$this->container[$module] = $this->di->get($module);
		}
	}




	/*
	 *
	 */
	public function initModule($module)
	{
		try
		{
			
			$providerClass = '\\Fw\\Core\\Providers\\'.$module.'Provider';
			if (!class_exists($providerClass))
				throw new Exception("Module provider «{$module}» not found");

			$provider = new $providerClass();
			
			$config = $provider->getConnectionParams();

			$this->di->set(
				$module,
				$config['class'],
				$config['args'],
				$config['singleton'],
				$config['preload']
			);
			
		}
		catch (Exception $e)
		{
			debug('Error: '.$e->getMessage());
			exit ();
		}
	}




	/*
	 *
	 */
	public function get_config($name)
	{
		return isset($this->container[$name]);
	}




	/*
	 *
	 */
	public function has($name)
	{
		return isset($this->container[$name]);
	}




	/*
	 *
	 */
	public function get($name)
	{
		return $this->has($name) ? $this->container[$name] : null;
	}




	/*
	 *
	 */
	public function __get($name)
	{
		return $this->get($name);
	}
}