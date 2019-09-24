<?php
/**
 * @author: KONARD
 * @version: 1.0
 */

namespace Fw\Core\Services;

use \Exception;

class Router
{
	private $routes		= [];
	private $aliases	= [];
	private $host;
	
	function __construct()
	{
	}


	/*
	 *
	 */
	public function setApp(string $name, string $prefix)
	{}


	/*
	 *
	 */
	public function setRoute(string $app, string $route, string $pattern, string $value)
	{}


	/*
	 *
	 */
	public function getAppRoutes(string $app)
	{}


	/*
	 *
	 */
	public function getAppRoute(string $app, string $route)
	{}


	/*
	 *
	 */
	public function getUri()
	{
		if ( !empty( $_SERVER['REQUEST_URI'] ) )
		{
			$return = trim( parse_url($_SERVER['REQUEST_URI'])['path'], '/' );
			return $return;
		}
		return false;
	}


	/*
	 *
	 */
	public function getCurrentApp()
	{
		$uri = $this->getUri();
		debug($uri);
	}


	/*
	 *
	 */
	public function getApp()
	{}
}