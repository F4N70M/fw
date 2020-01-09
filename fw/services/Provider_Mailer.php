<?php
return function(\Fw\Di\Container $container)
{
	$config = $container['config']['mail'];
	$obj = new \Fw\Services\Mailer\Mailer($config);
	return $obj;
};