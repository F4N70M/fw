<?php

namespace Fw\Di;

use \ReflectionClass;
use \ReflectionMethod;
use \Exception;

/**
 * 
 */
class Di
{




	/*
	 *
	 */
	private $aliases	= [];
	private $container	= [];
	private $singletons	= [];
	private $parameters	= [];
	private $arguments	= [];




	/*
	 *
	 */
	public function __construct()
	{
		// $this->container[get_class($this)] = $this;
		$this->set('Di',get_class($this));
	}




	/*
	 *
	 */
	public function set(string $alias, string $class, bool $singleton = false, array $arguments = [])
	{
		try
		{
			$this->container[$class]		= [
				'alias'		=> $alias,
				'singleton'	=> $singleton
			];
			
			$params = $this->getClassParameters($class);

			$this->aliases[$alias]		= $class;
			$this->parameters[$class]	= $params;
			//$this->arguments[$class]		= [];
			$this->setArguments($class, $arguments);

			// debug($class, $this->parameters[$class],$this->arguments[$class]);
		}
		catch (Exception $e)
		{
			debug('Error: '.$e->getMessage());
		}
	}




	/*
	 *
	 */
	public function get(string $alias, array $arguments = [])
	{
		try
		{
			$class = $this->aliases[$alias];

			$object = $this->resolveDependencies($class);

			return $object;
		}
		catch (Exception $e)
		{
			debug('Error: '.$e);
		}
	}




	/*
	 *
	 */
	// public function __get($name)
	// {
	// 	return $this->get($name);
	// }




	/*
	 *
	 */
	private function has(string $name)
	{
		return isset($this->container[$name]) || isset($this->aliases[$name]);
	}




	/*
	 *
	 */
	public function setArguments(string $class, array $arguments)
	{
		$parameters = $this->getParameters($class);
		// debug($parameters);
		foreach ($parameters as $key => $values)
		{
			if (isset($arguments[$key]))
			{
				$this->setArgument($class, $key, $arguments[$key]);
				unset($arguments[$key]);
				unset($params[$key]);
			}
			else
			{
				$this->setArgument($class, $key, null);
			}
		}
		foreach ($parameters as $key => $values)
		{
			if (empty($arguments)) break;

			$this->setArgument($class, $key, array_shift($arguments));
		}
	}




	/*
	 *
	 */
	public function setArgument(string $class, string $key, $value)
	{
		$this->arguments[$class][$key] = $value;
	}




	/*
	 *
	 */
	private function getArguments(string $class)
	{
		$args = [];

		foreach ($this->getParameters($class) as $key => $param)
		{
			// Проверка наличия аргумента
			if (isset($this->arguments[$class][$key]))
			{
				$arg = $this->arguments[$class][$key];
			}
			// Проверка наличия класса
			elseif (class_exists($param['type']))
			{
				$arg = $this->resolveDependencies($param['type']);
			}
			// Проверка обязательного подключения
			elseif (!$param['required'])
			{
				$arg = $param['default'];
			}
			else
			{
				debug($class,$param,$this);
				throw new Exception("Argument «{$key}» of class «{$class}» is missing");
				return false;	
			}

			/*$this->arguments[$class][$key] = */$args[$key] = $arg;
		}
		return $args;
	}




	/*
	 *
	 */
	private function getParameters(string $class)
	{
		return isset($this->parameters[$class]) ? $this->parameters[$class] : $this->getClassParameters($class);
	}




	/*
	 *
	 */
	private function getClassParameters(string $class)
	{
		if (!class_exists($class)) throw new Exception("Class «{$class}» does not exists");

		$reflection = new ReflectionMethod($class, '__construct');

		$params = [];
		foreach ($reflection->getParameters() as $param)
		{
			$type		= $param->getType() ? (string) $param->getType() : null;
			// $required	= !$param->allowsNull();
			$required	= !$param->isDefaultValueAvailable();
			// $null	= $param->allowsNull();							//	Проверяет, допустимо ли значение null для параметра
			// debug($class,$param->getName(),$param->allowsNull(),$param->isDefaultValueAvailable()/*,$param->getDefaultValue()*/);
			$default	= !$required ? $param->getDefaultValue() : null;		//	Получение значения по умолчанию для параметра
			$params[$param->getName()] = [
				'type'		=> $type,
				'required'	=> $required,
				'default'	=> $default
			];
		}
		// $this->parameters[$class] = $params;
		return $params;
	}




	/*
	 *
	 */
	private function getClass(string $name)
	{
		return isset($this->container[$name]) ? $name : (isset($this->aliases[$name]) ? $this->aliases[$name] : null);
	}




	/*
	 *
	 */
	private function resolveDependencies(string $class)
	{
		// Проверить существования класса
		if (!class_exists($class)) throw new Exception("Class «{$class}» does not exist");

		$singleton = $this->getSingleton($class);
		if ($singleton) return $singleton;

		// Получение информацию о классе
		$reflection = new ReflectionClass($class);

		// Получение аргументов
		$arguments = $this->getArguments($class);

		//	Создание экземпляра класса с переданными параметрами
		$object = $reflection->newInstanceArgs($arguments);

		if($this->isSingleton($class) && !$this->hasSingleton($class))
		{
			$this->setSingleton($class, $object);
		}

		return $object;
		// result
	}




	/*
	 *
	 */
	private function isSingleton($class)
	{
		return (isset($this->container[$class]['singleton']) && $this->container[$class]['singleton']);
	}




	/*
	 *
	 */
	private function hasSingleton($class)
	{
		return (
			$this->isSingleton($class) && 
			isset($this->singletons[$class]) && 
			is_object($this->singletons[$class]) && 
			$this->singletons[$class] instanceof $class
		);
	}




	/*
	 *
	 */
	private function getSingleton($class)
	{
		return $this->hasSingleton($class) ? $this->singletons[$class] : null;
	}




	/*
	 *
	 */
	private function setSingleton(string $class, $object)
	{
		if ($this->isSingleton($class) && is_object($object)) $this->singletons[$class] = $object;
	}




}