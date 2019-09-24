<?php

namespace Fw\Core\Providers;
	
use \Fw\Core\iServiceProvider;
use \Exception;

/**
 * 
 */
class DbProvider implements iServiceProvider
{




	/*
	 *
	 */
	function getConnectionParams()
	{
		$ip = $_SERVER['SERVER_ADDR'];
		$connections	= json(file_get_contents(CONFIG_DIR . "/db_connection.json"),false);
		$connection		= array_key_exists($ip, $connections) ? $connections[$ip] : $connections['default'];


		return
		[
			"class"		=>	\Fw\Core\Services\Db::class,
			"singleton"	=>	true,
			"preload"	=>	false,

			"args"		=>	[
				$connection
			]
		];
	}
}