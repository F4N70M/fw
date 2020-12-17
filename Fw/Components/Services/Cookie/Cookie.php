<?php
/**
 * @author: KONARD
 * @version: 1.0
 */

namespace Fw\Components\Services\Cookie;


class Cookie implements \ArrayAccess
{
	private $cookies = [];

	public function __construct()
	{
		$this->cookies = $_COOKIE;
	}

	public function get(string $name=null)
	{
		if (!empty($name))
		{
			if ($this->has($name))
			{
				$result = $this->cookies[$name];
				if (is_json($result))
				{
					$result = json($result,false);
				}
				return $result;
			}
			else{
				return null;
//				throw new \Exception("Cookie \"{$name}\" Not found");
			}
		}
		else
		{
			$result = [];
			foreach ($this->cookies as $name => $value)
			{
				$result[$name] = is_json($value) ? json($value,false) : $value;
			}
			return $result;
		}
	}

	public function has($name)
	{
		return isset($this->cookies[$name]);
	}

	public function set($name, $value, int $expire=0)
	{
		if (!(is_bool($value) || is_numeric($value) || is_string($value) || is_array($value)))
		{
			$type = gettype($value);
			throw new \InvalidArgumentException("Invalid argument type \"{$type}\"");
		}

		if (is_array($value))
		{
			$value = json($value,true);
		}
		if (is_bool($value))
		{
			$value = $value ? 1 : 0;
		}

//		debug($name, $value);
		$result = setcookie($name, $value, $expire, '/');

		if ($result)
		{
			$this->cookies[$name] = $value;

			if ($expire <= time() && $expire != 0)
				$this->unset($name);
		}

		return $result;
	}

	public function unset($name)
	{
		$result = setcookie($name, "", -3600, '/');

		if ($result)
		{
			unset($this->cookies[$name]);
		}

		return $result;
	}

	/**
	 * Whether a offset exists
	 * @link https://php.net/manual/en/arrayaccess.offsetexists.php
	 * @param mixed $offset <p>
	 * An offset to check for.
	 * </p>
	 * @return boolean true on success or false on failure.
	 * </p>
	 * <p>
	 * The return value will be casted to boolean if non-boolean was returned.
	 * @since 5.0.0
	 */
	public function offsetExists($offset)
	{
		return $this->has($offset);
	}

	/**
	 * Offset to retrieve
	 * @link https://php.net/manual/en/arrayaccess.offsetget.php
	 * @param mixed $offset <p>
	 * The offset to retrieve.
	 * </p>
	 * @return mixed Can return all value types.
	 * @since 5.0.0
	 */
	public function offsetGet($offset)
	{
		return $this->get($offset);
	}

	/**
	 * Offset to set
	 * @link https://php.net/manual/en/arrayaccess.offsetset.php
	 * @param mixed $offset <p>
	 * The offset to assign the value to.
	 * </p>
	 * @param mixed $value <p>
	 * The value to set.
	 * </p>
	 * @return void
	 * @since 5.0.0
	 */
	public function offsetSet($offset, $value)
	{
		$this->set($offset,$value);
	}

	/**
	 * Offset to unset
	 * @link https://php.net/manual/en/arrayaccess.offsetunset.php
	 * @param mixed $offset <p>
	 * The offset to unset.
	 * </p>
	 * @return void
	 * @since 5.0.0
	 */
	public function offsetUnset($offset)
	{
		$this->unset($offset);
	}
}