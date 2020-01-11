<?php
/**
 * @author: KONARD
 * @version: 0.1
 */

/**
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
	$App->request();
	//	Рендер страницы
	$App->render();
}
catch (Exception $e)
{
	debug(
		'Error: '.$e->getMessage(),
		'in ' . $e->getFile(),
		'on line ' . $e->getLine(),
		$e->getTrace()
	);
}
?>


