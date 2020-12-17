<?php
/**
 * @author: KONARD
 * @version: 1.0
 */




/**
 *
 */
function mb_ucfirst($string, $enc = 'UTF-8')
{
	return mb_strtoupper(mb_substr($string, 0, 1, $enc), $enc) .
		mb_substr($string, 1, mb_strlen($string, $enc), $enc);
}




/**
 *
 */
function init_log_settings($displayErrors = 0)
{
	ini_set('error_reporting', E_ALL);
	ini_set('display_errors', $displayErrors);
	ini_set('display_startup_errors', $displayErrors);
	ini_set('log_errors', 'On');
	//	Задать функцию для обработки фатальных ошибок
	register_shutdown_function('catchFatalErrors');

	if ( !file_exists(LOG_DIR) ) mkdir(LOG_DIR);
	$logDirY = LOG_DIR . '/' . date('Y');
	if ( !file_exists($logDirY) ) mkdir($logDirY);
	$logDirM = $logDirY . '/' . date('Y-m');
	if ( !file_exists($logDirM) ) mkdir($logDirM);
	$logName = $logDirM . '/' . date('Y-m-d') . '.log';
	ini_set('error_log', $logName);
}
/**
 *	Функция обработки фатальных ошибок
 */
function catchFatalErrors()
{
	$error = error_get_last();
	if($error) :
        ob_start();
        ?>
        <!doctype html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport"
                  content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <title>500: Fatal error</title>
            <style type="text/css">
                body {margin: 0;overflow: hidden;}
                .error500__wrap {
                    width: 100vw;
                    height: 100vh;
                    display: grid;
                    align-content: center;
                    position: fixed;
                    top: 0;
                    left: 0;
                    background-color: #30373E;
                    color: #ffffff;
                }
                .error500__content {text-align: center;}
                .error500__info {display: inline-block;text-align: left; padding: 16px;}
                h1,h2 {font-family: Arial;line-height: 1;margin: 16px 0;}
                h1 {font-size: 96px;}
                h2 {font-size: 36px;}
            </style>
        </head>
        <body>
            <div class="error500__wrap">
                <div class="error500__content">
                    <h1>500</h1>
                    <h2>Fatal Error</h2>
                    <div class="error500__info" style="max-width: 100vw; overflow:auto;"><?php
                        print_r($error['message']);
                        print_r('<br>in ' . $error['file']);
                        print_r('on line ' . $error['line']);
                    ?></div>
                </div>
            </div>
        </body>
        </html>

        <?php
        $bufer = ob_get_clean();
        header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
//        echo $bufer;
        debug($error);
    endif;
}


/**
 * Функция Рекурсивного перебора многоуровнего массива с применением пользовательской функции
 * @param $array - Массив
 * @param $callback - Пользовательская функция
 * @param $merge - Слить в один массив
 * @param $saveKeys - созранение ключей
 * @return array
 */
function recursive(array $array, $callback, bool $merge = false, bool $saveKeys = false)
{
	//	Проверка функции
	$callback = is_callable($callback) ? $callback : function($value){return $value;};

	$result = [];
	foreach ($array as $key => $value)
	{
		//	Применение рекурсии
		if (is_array($value))
		{
			$tmp = recursive($value, $callback);
		}
		else
		{
			$tmp = $callback($key,$value);
		}

		//	Сливание
		if (is_array($tmp) && is_array($value) && $merge )
		{
			foreach ($tmp as $tmpKey => $tmpValue)
			{
				if ($saveKeys)
				{
					$result[$tmpKey] = $tmpValue;
				}
				else
				{
					$result[] = $tmpValue;
				}
			}
		}
		else
		{
			if ($saveKeys)
			{
				$result[$key] = $tmp;
			}
			else
			{
				$result[] = $tmp;
			}
		}
	}

	return $result;
}




/**
 *
 */
function pre($data,$var_dump = false)
{
	echo '<pre>';
	if ($var_dump)
		var_dump($data);
	else
		print_r($data);
	echo '</pre>';
}


/**
 * Class Debug
 */
Class Debug
{
	private $args;

	public function __construct(array $args = [])
	{
		$this->args = $args;

		$this->open();

		$this->openContent();
		$this->openPre();
		$this->content();
		$this->closePre();
		$this->closeContent();

		$this->openBacktrace();
		$this->openPre();
		$this->backtrace();
		$this->closePre();
		$this->closeBacktrace();

		$this->close();
	}


	private function is_json($string)
	{
		return is_string($string) && is_array(json_decode($string, true)) ? true : false;
	}


	private function type($type)
	{
		switch ($type) {
			case 'boolean':
				$type = 'bool';
				break;
			case 'integer':
				$type = 'int';
				break;

			default:
				# code...
				break;
		}

		?><span style="display: inline-block; background-color: rgb(191,191,191); padding: .25em .5em; line-height: 1.25;"><?=$type;?></span> <?php
	}


	private function content()
	{
		foreach ($this->args as $key => $arg)
		{
			$type = gettype($arg);

			$this->type($type);

			?><span style="line-height: 1.75;"><?php

			if ($type == 'object')
			{
				print_r($arg);
			}
            elseif ($type == 'array')
			{
				print_r($arg);
			}
            elseif($type == 'boolean')
			{
				echo $arg ? 'true' : 'false';
			}
            elseif($type == 'string')
			{

				$arg = preg_replace('#<#', '&lt;', $arg);
				$arg = preg_replace('#>#', '&gt;', $arg);

				echo $arg;
			}
			else
			{
				echo $arg;
			}

			?></span><?php



			if( $key < count($this->args) - 1)
			{
				echo PHP_EOL;
				if(!is_array($arg) && !is_object($arg))
				{
					echo PHP_EOL;
				}
			}
		}
	}


	private function backtrace()
	{
		$debug = debug_backtrace();
		$traces = [];
		$maxStrlenTrace = 0;
		$maxStrlenLine = 0;
		$i = 0;

		array_shift($debug);
		if(count($debug) > 1)
			array_shift($debug);

		foreach ($debug as $key => $backtrace)
		{
			if ($i >= 4) break;
			if(empty($debug[$key]['file'])) continue;

			$trace = str_replace('\\', '/', $debug[$key]['file']);

			$line = ": " . $debug[$key]['line'];

			$strlenTrace = mb_strlen($trace);
			$strlenLine = mb_strlen($line);
			if ($strlenTrace > $maxStrlenTrace) $maxStrlenTrace = $strlenTrace;
			if ($strlenLine > $maxStrlenLine) $maxStrlenLine = $strlenLine;
			$func = /** @lang text */
				'<span style="color: rgb(180, 180, 180);">' . $debug[$key]['function'] . "(";
			$funcArgs = [];
			foreach ($debug[$key]['args'] as $value)
			{
				if (is_array($value)) $funcArgs[] = 'Array()';
                elseif (is_object($value)) $funcArgs[] = 'Obj()';
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
			$func .= implode(',', $funcArgs);
			$func .= ")" . "</span>";
			$traces[]	=	[$trace,$line,$func];
			$i++;
		}
		foreach ($traces as $key => $trace)
		{
			echo "\r\n"
				. str_pad(($key . ":"), 4, ' ', STR_PAD_RIGHT)
				. str_pad(($trace[0]), ($maxStrlenTrace + 2), ' ', STR_PAD_RIGHT)
				. '	'
				. str_pad(($trace[1]), ($maxStrlenLine + 2), ' ', STR_PAD_RIGHT)
				. '	'
				. $trace[2];
		}
	}


	private function open()
	{
		?><div style="box-sizing: border-box; font-size: 1rem; line-height: 1.15; width: 100%; background-color: rgb(241,241,241); color: rgb(0,0,0); border: dashed 1px rgb(191,191,191); border-radius: .5rem; display: grid; grid-gap: .5rem; margin: 1rem 0; padding-top: .5rem;"><?php
	}
	private function close()
	{
		?></div><?php
	}


	private function openContent()
	{
		?><div style="box-sizing: border-box; padding: 0 1rem; min-width: 0; max-width: 100%; max-height: 50vh; overflow:auto;"><?php
	}
	private function closeContent()
	{
		?></div><?php
	}


	private function openBacktrace()
	{
		?><div style="box-sizing: border-box; padding: 0 1rem; min-width: 0; max-width: 100%; overflow:auto; color: rgb(136, 136, 136); font-size: .75rem;"><?php
	}
	private function closeBacktrace()
	{
		?></div><?php
	}


	private function openPre()
	{
		?><pre style="margin: 0; padding: .5rem 0; font-size: .75rem; text-align: left;"><?php
	}
	private function closePre()
	{
		?></pre><?php
	}
}


/**
 *
 */
function debug()
{
// массив переданных аргументов
	$args = func_get_args();
	new Debug($args);
}




/**
 * @param $string
 * @return string
 */
function tags_to_code($string)
{
	$objectAsArray = is_object( $string ) ? false : true;
	$string = [$string];
	$string = json($string,true);

	$string = str_replace('<', '&lt;', $string);
	$string = str_replace('>', '&gt;', $string);

	$string = json($string,false, $objectAsArray);
	$string = $string[0];

	return $string;
}




/**
 * @param $string
 * @return bool
 */
function is_json($string)
{
	return is_string($string) && is_array(json_decode($string, true)) ? true : false;
}




/**
 * @param $json
 * @param mixed $polus
 * @param bool $objectAsArray
 * @return false|mixed|string
 */
function json( $json, bool $polus=null, bool $objectAsArray=true )
{
	$is_json = is_json( $json );

	//	Если ( Принудительно кодировать и Это уже json ) или ( Принудительно декодировать и Это не json )
	if ( ( $polus === true && $is_json ) || ( $polus === false && !$is_json ) )
	{
		return $json;
	}
	else
	{
		//	Декодировать
		if ( $is_json )
		{
			return json_decode( $json, $objectAsArray );
		}
		//	Кодировать
		else
		{
			//if ( in_array($json, ) )
			return json_encode( $json, JSON_UNESCAPED_UNICODE );
		}
	}
}