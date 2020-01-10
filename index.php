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

	//	Старт
	$Fw = new Fw\Core();

	//	Приложение
	$App = $Fw->Router->init($Fw);

	//	Обработка запросов
	//	$App->request();

	//	Рендер страницы
	$App->render();



	//	Router full :
	//	$Fw->container->get('Router')->get()

	//	Router as array :
	//	$Fw->container['Router']->get()

	//	Router short :
	//	$Fw->Router->get()

	//	debug(
	//		'Db',
	//		$Fw->Db
	//			->select()
	//			->from('options')
	//			->all(),
	//		$Fw->Db
	//			->select()
	//			->from('options')
	//			->all()
	//	);

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



debug($Fw);

debug('LOCK IMAGES USE CANVAS');

?>