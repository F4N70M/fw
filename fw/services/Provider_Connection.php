<?php
return function(\Fw\Di\Container $container) {
	$ip = $_SERVER['SERVER_ADDR'];
	$connections	= $container['config']['db'];
	$connection		= array_key_exists($ip, $connections) ? $connections[$ip] : $connections['default'];

	$obj = new \Fw\Services\Database\Connection($connection);
	return $obj;
};