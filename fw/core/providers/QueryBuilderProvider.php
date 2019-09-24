<?php

namespace Fw\Core\Providers;
	
use \Fw\Core\iServiceProvider;
use \Exception;

/**
 * 
 */
class QueryBuilderProvider implements iServiceProvider
{




	/*
	 *
	 */
	function getConnectionParams()
	{
		return
		[
			"class"		=>	\Fw\Core\Services\QueryBuilder::class,
			"singleton"	=>	false,
			"preload"	=>	false,

			"args"		=>	[]
		];
	}
}