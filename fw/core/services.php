<?php

namespace Fw\Core;

use \Fw\Di\Di;
	
use \Exception;

/**
 * 
 */
class Services
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
	// function __construct(Di $di, array $array,int $int, string $string, bool $bool, $null=null, $false=false, $true=true, $char = 'char')
	function __construct(Di $di, array $services)
	{
		$this->di = $di;

		$this->list = $services;

		$this->initServices();

		// debug($this->container);
	}




	/*
	 *
	 */
	function initServices()
	{
		$this->setServices();
		$this->getServices();
	}




	/*
	 *
	 */
	private function setServices()
	{
		foreach ($this->list as $service)
		{
			$this->initService($service);
		}
	}




	/*
	 *
	 */
	private function getServices()
	{

		foreach ($this->list as $service)
		{
			$this->container[$service] = $this->di->get($service);
		}
	}




	/*
	 *
	 */
	public function initService($service)
	{
		try
		{
			
			$providerClass = '\\Fw\\Core\\Providers\\'.$service.'Provider';
			if (!class_exists($providerClass))
				throw new Exception("Service provider «{$service}» not found");

			$provider = new $providerClass();
			
			$config = $provider->getConnectionParams();

			$this->di->set(
				$service,
				$config['class'],
				$config['singleton'],
				$config['args']
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