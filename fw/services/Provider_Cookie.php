<?php
return function(\Fw\Di\Container $container)
{
	$obj = new \Fw\Services\Cookie\Cookie();
	return $obj;
};