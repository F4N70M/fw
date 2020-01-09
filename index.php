<?php
/**
 * @author: KONARD
 * @version: 0.1
 */

//	Определяем корневую директорию
// define('ROOT_DIR', str_replace('\\', '/', getcwd()) );

// Подключить фреймворк
//require 'fw/bootstrap.php';



/*
 * Подключение системы
 */
try
{
	require 'fw/bootstrap.php';

	//  Старт
	$Fw = new Fw\Core();

	debug(
		'Router full :',
		$Fw->container->get('Router')->getAppConfig(),

		'Router as array :',
		$Fw->container['Router']->getAppConfig(),

		'Router short :',
		$Fw->Router->getAppConfig()
	);

	debug(
		'Db',
		$Fw->Db
			->select()
			->from('options')
			->all(),
		$Fw->Db
			->select()
			->from('options')
			->all()
	);

	throw new Exception('test error');

}
catch (Exception $e)
{
	debug(
		'Error: '.$e->getMessage(),
		'in ' . $e->getFile(),
		'on line ' . $e->getLine()
	);
}



debug('LOCK IMAGES USE CANVAS');

?>
