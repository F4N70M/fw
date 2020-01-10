<?php
return function(\Fw\Di\Container $container)
{
	$obj = new \Fw\Services\Autorization\Autorization($container['Db'],$container['Cookie']);
	return $obj;
};