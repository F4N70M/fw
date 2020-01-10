<?php
/**
 * @author: KONARD
 * @version: 1.0
 */

namespace Fw\Services\Cookie;


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
			return ($this->has($name) ? $this->cookies[$name] : null);
		}
		else
		{
			return $this->cookies;
		}
	}

	private function has($name)
	{
		return isset($this->cookies[$name]);
	}

	public function set($name, string $value="", int $expire=0, string $path="", string $domain="", bool $sequre=false, bool $httponly=false)
	{
		$result = setcookie($name, $value, $expire, $path, $domain, $sequre, $httponly);

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
		$result = setcookie($name, "", -3600);

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