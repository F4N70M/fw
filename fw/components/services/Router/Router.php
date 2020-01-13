<?php
/**
 * @author: KONARD
 * @version: 1.0
 */

namespace Fw\Components\Services\Router;

use Exception;
use Fw\Core;

class Router
{
	private $apps		= [];
	private $routes		= [];


	/**
	 * @param $fw
	 * @param null $uri
	 * @return mixed
	 */
	function init(Core $fw, $uri=null)
	{
		$config = $this->get($uri);

		//TODO: Сделать проверку существования метода

		$app = new $config['controller']($fw);

		//TODO: Сделать проверку аргументов рефлексии метода и упорядочивание по ключам
		call_user_func_array(array($app, $config['method']), $config['arguments']);

		return $app;
	}


	/**
	 * @param string $name
	 * @param string $prefix
	 */
	public function setApp(string $name, string $prefix)
	{
		$this->apps[$name]	= $prefix;
	}


	/**
	 * @param string $app
	 * @param string $pattern
	 * @param array $route
	 * @return bool
	 */
	public function setRoute(string $app, string $pattern, array $route)
	{
		if (!$this->hasApp($app)) return false;

		$fullPattern = $this->getFullPattern($this->apps[$app], $pattern);

		$this->routes[$fullPattern]	= $route;
		return true;
	}


	/**
	 * @param string $app
	 * @return bool
	 */
	private function hasApp(string $app)
	{
		return isset($this->apps[$app]);
	}


	/**
	 * @param string $prefix
	 * @param string $pattern
	 * @return string
	 */
	private function getFullPattern(string $prefix, string $pattern)
	{
		$fullPattern = trim($prefix . '/' . $pattern, '/');
		return $fullPattern;
	}


	/**
	 * @return string
	 */
	public function getCurrentUri()
	{
		return trim( parse_url($_SERVER['REQUEST_URI'])['path'], '/' );
	}


	/**
	 * @param string|null $uri
	 * @return bool
	 */
	public function get(string $uri = null)
	{
		if ($uri === null)
			$uri = $this->getCurrentUri();

		foreach ($this->routes as $pattern => $route)
		{
			$pattern = '#^'.$pattern.'$#i';

			if (preg_match($pattern, $uri, $matches))
			{
				if (is_string($route['method']))
					$route['method'] = preg_replace($pattern,$route['method'],$uri);
				foreach ($route['arguments'] as $key => $argument)
				{
					if (is_string($argument))
						$route['arguments'][$key] = preg_replace($pattern,$argument,$uri);
				}
				return $route;
			}
		}
	}
}