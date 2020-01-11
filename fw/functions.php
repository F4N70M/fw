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
                    <pre class="error500__info"><?php
                        print_r($error['message']);
                        print_r('in ' . $error['file']);
                        print_r('on line ' . $error['line']);
                    ?></pre>
                </div>
            </div>
        </body>
        </html>

        <?php
        $bufer = ob_get_clean();
        header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
        echo $bufer;
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
 *
 */
function debug()
{
// массив переданных аргументов
$args = func_get_args();

echo /** @lang text */
'<div style="
	box-sizing: border-box;
	font-size: 1rem;
	line-height: 1.15;
	width: 100%;
	background-color: rgb(241,241,241);
	color: rgb(0,0,0);
	border: dashed 1px rgb(191,191,191);
	border-radius: .5rem;
	display: grid;
	grid-gap: .5rem;
	margin: 1rem 0;
	padding-top: .5rem;
">
	<div style="
		box-sizing: border-box;
		padding: 0 1rem;
		min-width: 0;
		max-width: 100%;
		max-height: 50vh;
		overflow:auto;
	">
		<pre style="margin: 0; padding: .5rem 0; font-size: 1rem; text-align: left;">';

	foreach ($args as $key => $arg)
	{
		if ( is_object($arg) )
		{
			print_r($arg);
			continue;
		}

		$arg = tags_to_code($arg);

		//var_dump('ARG: ', $arg);

		if ( empty($arg) || $arg === true )
		{
			var_dump( $arg );
		}
		else
		{
			$arg = json([$arg],true);

			$arg = preg_replace('#([\:\[\,])(true|false)#', '$1"bool($2)"', $arg);
			$arg = preg_replace('#([\:\[\,])null#', '$1"NULL"', $arg);
			$arg = preg_replace('#([\:\[\,])\"\"#', '$1"sting(0) \"\""', $arg);

			$arg = json($arg,false)[0];

			print_r($arg);
		}

		if( $key < count($args) - 1 || ( $key == count($args) - 1 && ( !is_array($arg) && !is_object($arg) ) ) )
		{
			echo PHP_EOL;
		}
	}

	$debug = debug_backtrace();
	$traces = [];
	$maxStrlenTrace = 0;
	$maxStrlenLine = 0;
echo /** @lang text */
'</pre>
	</div>
	<div style="
		box-sizing: border-box;
		padding: 0 1rem;
		min-width: 0;
		max-width: 100%;
		overflow:auto;
		color: rgb(136, 136, 136);
		font-size: .8rem;
	">
		<pre style="margin: 0; padding: .5rem 0; font-size: 1rem; text-align: left;">';
	$i = 0;
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
'<span style="color: rgb(180, 180, 180); font-size: .8rem;">' . $debug[$key]['function'] . "(";
		$funcArgs = [];
		foreach ($debug[$key]['args'] as $value)
		{
			if (is_array($value)) $funcArgs[] = 'Array()';
			elseif (is_object($value)) $funcArgs[] = 'Object()';
			elseif ($value === null) $funcArgs[] = 'null';
			elseif ($value === true) $funcArgs[] = 'true';
			elseif ($value === false) $funcArgs[] = 'false';
			elseif ($value === '') $funcArgs[] = '""';
			else $funcArgs[] = '"'.$value.'"';

		}
		$func .= implode(',', $funcArgs);
		$func .= ")" . "</span>";
		$traces[]	=	[$trace,$line,$func];
		$i++;
	}
	foreach ($traces as $key => $trace)
	{
		echo
			"\r\n" . str_pad(($key . ":"), 4, ' ', STR_PAD_RIGHT)
			. str_pad(($trace[0]), ($maxStrlenTrace + 2), ' ', STR_PAD_RIGHT)
			. '	'
			. str_pad(($trace[1]), ($maxStrlenLine + 2), ' ', STR_PAD_RIGHT)
			. '	'
			. $trace[2];
	}
echo /** @lang text */
"</pre>
	</div>
</div>";
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