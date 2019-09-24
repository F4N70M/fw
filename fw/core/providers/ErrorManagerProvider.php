<?php

namespace Fw\Core\Providers;
	
use \Fw\Core\iServiceProvider;
use \Exception;

/**
 * 
 */
class ErrorManagerProvider implements iServiceProvider
{




	/*
	 *
	 */
	function getConnectionParams()
	{
		return
		[
			"class"		=>	\Fw\Core\Services\ErrorManager::class,
			"singleton"	=>	true,
			"preload"	=>	false,

			"args"		=>	[]
		];
	}
}