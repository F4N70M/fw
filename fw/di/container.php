<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 10.01.2020
 */

namespace Fw\Di;

use \InvalidArgumentException;
use \ReflectionClass;
use \ReflectionMethod;
use \Exception;

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
	public function set(string $id, $value, $singleton=true)
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
	 */
	public function get(string $id, $parameters=[])
	{
		if (!class_exists($id) && isset($this->aliases[$id]))
			$id = $this->aliases[$id];

		if(array_key_exists($id, $this->objects))
			return $this->objects[$id];

		if(!array_key_exists($id, $this->definitions))
			throw new InvalidArgumentException('Undefined key "' . $id . '" in container');

		$definition = $this->definitions[$id];

		if ($definition instanceof \Closure)
		{
			$this->objects[$id] = $definition($this, $parameters);
			$result = $this->objects[$id];
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
	 * Whether a offset exists
	 * @link https://php.net/manual/en/arrayaccess.offsetexists.php
	 * @param mixed $offset
	 * @return boolean
	 * The return value will be casted to boolean if non-boolean was returned.
	 */
//	public function offsetExists($offset)
//	{
//		return $this->has($offset);
//	}

	/**
	 * Offset to retrieve
	 * @link https://php.net/manual/en/arrayaccess.offsetget.php
	 * @param mixed $offset
	 * @return mixed
	 */
//	public function offsetGet($offset)
//	{
//		return $this->get($offset);
//	}

	/**
	 * Offset to set
	 * @link https://php.net/manual/en/arrayaccess.offsetset.php
	 * @param mixed $offset
	 * @param mixed $value
	 * @return void
	 */
//	public function offsetSet($offset, $value)
//	{
//		return $this->set($offset,$value);
//	}

	/**
	 * Offset to unset
	 * @link https://php.net/manual/en/arrayaccess.offsetunset.php
	 * @param mixed $offset
	 * @return void
	 */
//	public function offsetUnset($offset)
//	{
//	}
}