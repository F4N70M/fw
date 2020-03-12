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
	require 'Fw/bootstrap.php';

	//	Старт
	$Fw = new Fw\Core();

	//	Приложение
	$App = $Fw->Router->init($Fw);

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