<?php

namespace Fw\Di;

use \ReflectionClass;
use \Exception;

/**
 * 
 */
class Di
{



	/*
	 *
	 */
	private $container	= [];
	private $arguments	= [];
	private $singletons	= [];



	/*
	 *
	 */
	public function __construct()
	{
	}



	/*
	 *
	 */
	public function set(string $name, string $class, array $args, bool $singleton = false, bool $preload = false)
	{
		try {
			
			if(!class_exists($class)) throw new Exception ("Class «{$class}» is not exists");

			$params = $this->getParams(new ReflectionClass($class));

			$this->container[$name] = [
				'class'	=>	$class,
				'singleton'	=>	$singleton
			];
			$this->setArgs($name, $args, $params);
			
			/*if ($preload && $singleton)
			{
				$object = $this->get($name);
				$this->setSingleton($name, $object);
			}*/
		// return true;
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
	public function get($name)
	{
		try {
			//	Проверка на существование
			if (!$this->has($name)) throw new Exception("Injection «{$name}» does not exsist");

			//	Проверка наличия объекта синглтона
			if ($this->isSingleton($name) && isset($this->singletons[$name]))
				return $this->singletons[$name];

			//	Проверка аргументов
			$args = $this->getArgs($name);
			//	Конвертация ссылок на объекты в аргументах
			$args = $this->convertArgs($args);

			$object = $this->callUserClassArray($this->container[$name]['class'], $args);
			
			if ($this->isSingleton($name) && !isset($this->singletons[$name]))
				$this->setSingleton($name, $object);

			return $object;	
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
	public function setArg(string $name, string $key, $value)
	{
		$this->arguments[$name][$key] = $value;
	}




	/*
	 *
	 */
	public function setArgs(string $name, array $args, array $params)
	{
		foreach ($params as $key)
		{
			if (isset($args[$key]))
			{
				$this->setArg($name, $key, $args[$key]);
				unset($args[$key]);
				unset($params[$key]);
			}
			else
			{
				$this->setArg($name, $key, null);
			}
		}
		foreach ($params as $key)
		{
			if (empty($args)) break;


			$this->setArg($name, $key, array_shift($args));
		}
	}




	/*
	 *
	 */
	public function getArg(string $name, string $key)
	{
		return isset($this->arguments[$name][$key]) ? $this->arguments[$name][$key] : null;
	}




	/*
	 *
	 */
	public function getArgs(string $name)
	{
		return isset($this->arguments[$name]) && is_array($this->arguments[$name]) ? $this->arguments[$name] : [];
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
	public function isSingleton($name)
	{
		return (bool) $this->container[$name]['singleton'];
	}




	/*
	 *
	 */
	public function setSingleton(string $name, $object)
	{
		//	Если синглтон
		if (is_object($object) && !isset($this->singletons[$name]) && $this->isSingleton($name))
			//	Запоминаем
			$this->singletons[$name] = $object;
	}




	/*
	 *
	 */
	private function convertArgs(array $args)
	{
		try {
			foreach ($args as $key => $value)
			{
				//	Если ссылка на объект di
				if (is_string($value) && preg_match('#%(.+)::(\w+)%#', $value, $matches))
				{
					//	Получение объекта
					$argObj = $this->get($matches[1]);
					//	Проверка объекта
					if (!is_object($argObj)) throw new Exception("Argument «{$matches[1]}» is non object");
					//	Замена аргумента на объект
					$args[$key] = $argObj;
				}
			}
			return $args;
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
	private function callUserClassArray($class, array $args=[])
	{
		// Проверка обявлен ли класс
		if(!class_exists($class)) return null;
		//	Получение информацию о классе
		$refClass = new ReflectionClass($class);
		// Получаем массив необходимых параметров конструктора
		$params = $this->getParams($refClass);
		// Подставляем необходимые аргументы
		$veryfyArgs = $this->getVerifyArgs($args,$params);
		//	Создание экземпляра класса с переданными параметрами
		$object = $refClass->newInstanceArgs($args);

		return $object;
	}




	/*
	 *
	 */
	private function getVerifyArgs(array $args,array $params)
	{
		$verifyArgs = [];
		foreach ($params as $param)
		{
			if (!isset($args[$param])) return false;

			$verifyArgs[] = $args[$param];
		}

		return $verifyArgs;
	}




	/*
	 *
	 */
	private function getParams(ReflectionClass $reflectionClass, string $methodName = '__construct')
	{
		debug('getParams',$reflectionClass->getName());
		$params = [];
		if (is_object($reflectionClass) && $reflectionClass->hasMethod($methodName))
		{
			$refParams = $reflectionClass->getMethod($methodName)->getParameters();
			debug($refParams);
			foreach ($refParams as $key => $param)
			{

				/*debug(
					$param->getName(),
					$param->hasType(),
					$param->getDeclaringClass(),
					$param->getClass()->getName()
				);*/

				$params[$key] = $param->getName();
			}
		}
		return $params;
	}


}