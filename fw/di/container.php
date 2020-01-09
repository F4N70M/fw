<?php

namespace Fw\Di;

use \InvalidArgumentException;
use \ReflectionClass;
use \ReflectionMethod;
use \Exception;

/**
 * 
 */
class Container implements \ArrayAccess
{




	/*
	 *
	 */
	private $definitions = [];
	private $singletons = [];
	private $objects = [];
	private $parameters	= [];


	/**
	 * @param string $id
	 * @return bool
	 */
	private function has(string $id)
	{
		return isset($this->definitions[$id]);
	}


	/**
	 * @param string $id
	 * @param string $value
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
	 * @return mixed
	 */
	public function get(string $id)
	{
		if(array_key_exists($id, $this->objects))
		{
			return $this->objects[$id];
		}

		if(!array_key_exists($id, $this->definitions))
		{
			throw new InvalidArgumentException('Undefined key "' . $id . '" in container');
		}

		$definition = $this->definitions[$id];
		if ($definition instanceof \Closure)
		{
			$this->objects[$id] = $definition($this);
		}
		else
		{
			$this->objects[$id] = $definition;
		}
		return $this->objects[$id];
	}

	/**
	 * Whether a offset exists
	 * @link https://php.net/manual/en/arrayaccess.offsetexists.php
	 * @param mixed $offset
	 * @return boolean
	 * The return value will be casted to boolean if non-boolean was returned.
	 */
	public function offsetExists($offset)
	{
		return $this->has($offset);
	}

	/**
	 * Offset to retrieve
	 * @link https://php.net/manual/en/arrayaccess.offsetget.php
	 * @param mixed $offset
	 * @return mixed
	 */
	public function offsetGet($offset)
	{
		return $this->get($offset);
	}

	/**
	 * Offset to set
	 * @link https://php.net/manual/en/arrayaccess.offsetset.php
	 * @param mixed $offset
	 * @param mixed $value
	 * @return void
	 */
	public function offsetSet($offset, $value)
	{
		return $this->set($offset,$value);
	}

	/**
	 * Offset to unset
	 * @link https://php.net/manual/en/arrayaccess.offsetunset.php
	 * @param mixed $offset
	 * @return void
	 */
	public function offsetUnset($offset)
	{
	}
}