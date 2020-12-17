<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 10.01.2020
 */

namespace Fw\Components\Di;

use Closure;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use Exception;

/**
 * Class Container
 * @package Fw\Di
 */
class Container /*implements \ArrayAccess*/
{


	/*
	 *
	 */
	private $singletons = [];
	private $aliases = [];
	private $definitions = [];
	private $objects = [];
	private $parameters = [];


	/**
	 * @param string $id
	 * @return bool
	 */
	public function has(string $id)
	{
		return isset($this->definitions[$id]) || isset($this->aliases[$id]);
	}


	/**
	 * @param string $id
	 * @param string $value
	 * @param bool $singleton
	 */
	public function set(string $id, $value, $singleton = true)
	{
		if (isset($this->objects[$id]))
			unset($this->objects[$id]);

		$this->singletons[$id] = $singleton;
		$this->definitions[$id] = $value;
	}


	/**
	 * @param string $id
	 * @param array $parameters
	 * @return mixed
	 * @throws Exception
	 */
	public function get(string $id, $parameters = [])
	{
		if (!class_exists($id) && isset($this->aliases[$id]))
			$id = $this->aliases[$id];


		if (array_key_exists($id, $this->objects))
			return $this->objects[$id];

		if (!array_key_exists($id, $this->definitions))
			throw new InvalidArgumentException('Undefined key "' . $id . '" in container');

		$definition = $this->definitions[$id];

//		debug(is_callable($definition), func_get_args($definition));
		if ($definition instanceof Closure)
		{
			$class = $id;
			$object = $definition($this, $class, $parameters);
//			debug($id, $this->singletons[$id]);
			if ($this->singletons[$id])
				$this->objects[$id] = $object;
			$result = $object;
		}
		else
		{
			$result = $definition;
		}

		return $result;
	}


	public function setAlias($id, $alias)
	{
		$this->aliases[$id] = $alias;
	}


	/**
	 * @param $class
	 * @param array $parameters
	 * @return object
	 * @throws ReflectionException
	 * @throws Exception
	 */
	public function getInstance($class, $parameters = [])
	{
		if (!class_exists($class))
			throw new Exception("Class \"{$class}\" not found!");

		$reflectClass = new ReflectionClass($class);
		if ($reflectClass->hasMethod('__construct'))
		{
			$reflectMethod = $reflectClass->getMethod('__construct');
			$reflectParameters = $this->getParameters($reflectMethod);
//			debug($reflectParameters);

			$resultParameters = [];
			foreach ($reflectParameters as $name => $reflectParameter) {
				// Если указан параметр при вызове
				if (isset($parameters[$name]))
				{
					$resultParameters[$name] = $parameters[$name];
					unset($parameters[$name]);
				}
				// Если параметр был указан ранее
				elseif (isset($this->parameters[$class][$name]))
				{
					$resultParameters[$name] = $this->parameters[$class][$name];
				}
				// Если в типе параметра указан класс
				elseif (isset($reflectParameter['class']))
				{
					$resultParameters[$name] = $this->get($reflectParameter['class']);
				}
				// Если есть доступные параметры указанные при вызове
				elseif (count($parameters) > 0)
				{
					$resultParameters[$name] = array_shift($parameters);
				}
				// Если указано значение по умолчанию
				elseif (key_exists('default', $reflectParameter))
				{
					$resultParameters[$name] = $reflectParameter['default'];
				}
				else
				{
					debug(
						'$reflectParameter', $reflectParameter, key_exists('default',$reflectParameter)
					);
					throw new Exception("Argument \"{$name}\" for {$class} passed");
				}
			}
			$instance = $reflectClass->newInstanceArgs($resultParameters);
		}
		else
		{
			$instance = $reflectClass->newInstance();
		}

		return $instance;
	}

	/**
	 * @param ReflectionMethod $reflectMethod
	 * @return array
	 * @throws ReflectionException
	 */
	private function getParameters(ReflectionMethod $reflectMethod)
	{
		$reflectParameters = [];
		foreach ($reflectMethod->getParameters() as $parameter)
		{
			$reflectParameter = [];

			$name = $parameter->getName();

			$class = $parameter->getClass();
			if (method_exists($class,'getName')) $reflectParameter['class'] = $class->getName();

			$reflectParameter['type'] = $parameter->getType();
			$reflectParameter['type'] = method_exists($reflectParameter['type'],'getName') ? $reflectParameter['type']->getName() : (string) $reflectParameter['type'];

			if ($parameter->isDefaultValueAvailable()) $reflectParameter['default'] = $parameter->getDefaultValue();

			$reflectParameters[$name] = $reflectParameter;
		}
		return $reflectParameters;
	}

	/**
	 * @param string $class
	 * @param string $key
	 * @param $value
	 */
	public function setParameter(string $class, string $key, $value)
	{
		$this->parameters[$class][$key] = $value;
	}
}