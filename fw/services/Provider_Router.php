<?php
return function(\Fw\Di\Container $container) {

	$obj = new \Fw\Services\Router\Router();
	return $obj;
};