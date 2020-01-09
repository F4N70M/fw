<?php
return function(\Fw\Di\Container $container) {
	$obj = new \Fw\Services\Database\Db($container['Connection']);
	return $obj;
};