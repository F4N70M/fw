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
	private $apps   = [];
	private $routes	= [];


	public function __construct()
	{
		$this->setApp('API', 'api', [
			'title' =>  'API'
		]);
	}


	/**
	 * @param Core $Fw
	 * @param null $uri
	 * @return mixed
	 * @throws Exception
	 */
	function init(Core $Fw, $uri=null)
	{
		$route = $this->get($uri);
//		debug($route);
		//TODO: Сделать проверку существования метода
		$app = new $route['route']['controller']($Fw, $route);

//		debug($route);
		//TODO: Сделать проверку аргументов рефлексии метода и упорядочивание по ключам
		if (isset($route['route']['method']))
		{
			if (!method_exists($app, $route['route']['method']))
				throw new Exception("method {$route['route']['controller']}::{$route['route']['method']} is not exist");

			call_user_func_array(array($app, $route['route']['method']), $route['route']['arguments']);
		}

		return $app;
	}


    /**
     * @param string|null $uri
     * @return array
     * @throws Exception
     */
    public function get(string $uri = null)
    {
        if ($uri === null) $uri = $this->getCurrentUri();
        $uriSplitFormat = $this->uriSplitFormat($uri);
//        debug($uriSplitFormat);

        foreach ($this->routes as $app)
        {
            foreach ($app['routes'] as $pattern => $route)
            {
                $pattern = '#^'.$pattern.'$#i';
                if (preg_match($pattern, $uriSplitFormat['path'], $matches))
                {
                    $route = $this->compilationRoute($pattern, $route, $uriSplitFormat);
                    $appPrefix =  !empty(($app['prefix'])) ? '/'.($app['prefix']) : null;
                    define('APP_NAME', ($app['name']));
                    define('APP_PREFIX', ($appPrefix));

                    $result = [
                        "route" => $route,
                        "app"   => $app
                    ];
//                    debug($result);
                    return $result;
                }
            }
        }

        throw new Exception("Route for url \"{$uri}\" not found");
    }



    private function compilationRoute($pattern, $route, $splitPath)
    {
        if (isset($route['method']) && is_string($route['method']))
            $route['method'] = preg_replace($pattern,$route['method'],$splitPath['path']);

        if (isset($route['arguments']) && is_array($route['arguments']))
        {
            foreach ($route['arguments'] as $key => $value)
            {
                if (is_string($value))
                {
                    $route['arguments'][$key] = preg_replace($pattern, $value, $splitPath['path']);
                }
            }
        }
//        $route['pattern'] = $pattern;

        $result = array_merge(
            $splitPath,
            ['pattern' => $pattern],
            $route
        );

        return $result;
    }


    private function uriSplitFormat(string $uri)
    {
        preg_match('#^(|[^.]+)(?:\.([a-z]+))*$#i',$uri,$matches);
        $result = [
            'uri'  => $matches[0],
            'path'  => $matches[1],
            'format'  => isset($matches[2]) ? $matches[2] : null,
        ];

        return $result;
    }


	/**
	 * @param string $name
	 * @param string $prefix
	 * @param array $config
	 */
	public function setApp(string $name, string $prefix, array $config)
	{
//		$this->apps[$name]	= $prefix;
		/** **/
		$this->routes[] = [
			'name'       => $name,
			'prefix'    => $prefix,
			'routes'    => [],
			'config'    => $config
		];
		end($this->routes);         // move the internal pointer to the end of the array
		$this->apps[$name] = key($this->routes);
		/** **/
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

//		$fullPattern = $this->getFullPattern($this->apps[$app], $pattern);
		$fullPattern = $this->getFullPattern($this->routes[$this->apps[$app]]['prefix'], $pattern);

		/** **/
		$this->routes[$this->apps[$app]]['routes'][$fullPattern] = $route;
		/** **/

//		$route['app'] = [
//			'name'  => $app,
//			'prefix'=> $this->apps[$app]
//		];
//
//
//		$this->routes[$fullPattern]	= $route;

		return true;
	}


	/**
	 * @param string $app
	 * @return bool
	 */
	private function hasApp(string $app)
	{
//		return isset($this->apps[$app]);
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
	private function getCurrentUri()
	{
		return trim( parse_url($_SERVER['REQUEST_URI'])['path'], '/' );
	}
}