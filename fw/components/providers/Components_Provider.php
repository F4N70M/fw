<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 10.01.2020
 */

namespace Fw\Components\Providers;


use Fw\Di\Container;
use ReflectionException;
use ReflectionMethod;
use ReflectionClass;
use Exception;

Class Components_Provider
{
	protected $container;

	public function __construct(Container $container)
	{
		$this->container = $container;
	}

	/**
	 * @param $class
	 * @param array $parameters
	 * @return object
	 * @throws ReflectionException
	 */
	protected function getInstance($class, array $parameters=[])
	{
		if (!class_exists($class))
			throw new Exception("Class \"{$class}\" not found!");

		$reflectClass      = new ReflectionClass($class);
		$reflectMethod     = $reflectClass->getMethod('__construct');
		$reflectParameters = $this->getParameters($reflectMethod);

		$resultParameters = [];
		foreach ($reflectParameters as $name => $reflectParameter)
		{
			if (isset($parameters[$name]))
			{
				$resultParameters[$name] = $parameters[$name];
				unset($parameters[$name]);
			}
			elseif (isset($reflectParameter['class']))
			{
				$resultParameters[$name] = $this->container->get($reflectParameter['class']);
			}
			elseif (count($parameters) > 0)
			{
				$resultParameters[$name] = array_shift($parameters);
			}
			elseif (isset($resultParameter['default']))
			{
				$resultParameters[$name] = $reflectParameter['default'];
			}
			else
			{
				throw new Exception("Argument \"{$name}\" passed");
			}
		}

		$instance = $reflectClass->newInstanceArgs($resultParameters);
		return $instance;
	}

	public function getParameters(ReflectionMethod $reflectMethod)
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

			if ($parameter->isDefaultValueAvailable()) $reflectParameter['default'] = $parameter -> getDefaultValue();

			$reflectParameters[$name] = $reflectParameter;
		}
		return $reflectParameters;
	}
}