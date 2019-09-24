<?php

return [
	'ErrorManager'	=>	[
		'class'		=>	Fw\Modules\ErrorManager\Base\ErrorManager::class,
		'singleton'	=>	true,
		'preload'	=>	false,
		'arguments'	=>	[],
	],
	'Db'	=>	[
		'class'			=>	Fw\Modules\Db\Base\Db::class,
		'singleton'	=>	true,
		'preload'	=>	true,
		'arguments'		=>	[
			require FW_DIR . '/config/' . ($_SERVER['SERVER_ADDR'] == '127.0.0.1' ? 'db_localhost' : 'db_hosting') . '.php',
			'%QueryBuilder::class%',
		],
	],
	'QueryBuilder'	=>	[
		'class'		=>	Fw\Modules\Db\Base\QueryBuilder::class,
		'singleton'	=>	false,
		'preload'	=>	true,
		'arguments'	=>	[],
	],
	'Router'	=>	[
		'class'		=>	Fw\Modules\ErrorManager\Base\ErrorManager::class,
		'singleton'	=>	true,
		'preload'	=>	false,
		'arguments'	=>	[],
	],
];