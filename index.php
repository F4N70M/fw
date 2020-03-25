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
	$traces = $e->getTrace();
	$resultTraces = [];
	foreach ($traces as $key => $trace)
	{
		$resultTraces[] = '';
		$funcArgs = [];
		foreach ($trace['args'] as $value)
		{
			if (is_array($value)) $funcArgs[] = 'Array()';
			elseif (is_object($value)) $funcArgs[] = 'Object()';
			elseif ($value === null) $funcArgs[] = 'null';
			elseif ($value === true) $funcArgs[] = 'true';
			elseif ($value === false) $funcArgs[] = 'false';
			elseif ($value === '') $funcArgs[] = '""';
			else {
				$value = preg_replace('#<#', '&lt;', $value);
				$value = preg_replace('#>#', '&gt;', $value);
				$funcArgs[] = '"'.$value.'"';
			}
		}
		$trace['args'] = implode(', ',$funcArgs);
		$traces[$key] = $trace['file'] . " : " . $trace['line'] . " — " . $trace['class'] . '::' . $trace['function'] . ' ( ' . $trace['args'] . ' )';
	}
	debug(
		'Error: '.$e->getMessage()
		.' in ' . $e->getFile()
		.' on line ' . $e->getLine(),
		$traces
	);
}