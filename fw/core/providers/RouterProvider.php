<?php

namespace Fw\Core\Providers;
	
use \Fw\Core\iServiceProvider;
use \Exception;

/**
 * 
 */
class RouterProvider implements iServiceProvider
{




	/*
	 *
	 */
	function getConnectionParams()
	{
		return
		[
			"class"		=>	\Fw\Core\Services\Router::class,
			"singleton"	=>	true,
			"preload"	=>	false,

			"args"		=>	[]
		];
	}
}