<?php
return function(\Fw\Di\Container $container) {

	$obj = new \Fw\Services\Db\QueryBuilder();
	return $obj;
};