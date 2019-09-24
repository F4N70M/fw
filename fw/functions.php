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
	 * Функция Рекурсивного перебора многоуровнего массива с применением пользовательской функции
	 * @param $array	- Массив
	 * @param $callback	- Пользовательская функция
	 * @param $merge	- Слить в один массив
	 * @param $saveKeys	- созранение ключей
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
		'<pre style="
			font-size: 1rem;
			line-height: 1.15;
			box-sizing: border-box;
			width: 100%;
			max-height: 66vh;
			padding: 2rem;
			overflow:auto;
			background-color: rgb(241,241,241);
			color: rgb(0,0,0);
			border: dashed 1px rgb(191,191,191);
			border-radius: .5rem;
		">';

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
		echo '<span style="color: rgb(136, 136, 136); font-size: .8rem;">';
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
			$func = '<span style="color: rgb(180, 180, 180); font-size: .8rem;">' . $debug[$key]['function'] . "(";
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
				"\r\n" . str_pad(($key . ":"), 4, ' ', STR_PAD_RIGHT) .
				str_pad(($trace[0]), ($maxStrlenTrace + 2), ' ', STR_PAD_RIGHT) .
				' ' .
				str_pad(($trace[1]), ($maxStrlenLine + 2), ' ', STR_PAD_RIGHT) .
				$trace[2];
		}
		echo "</span></pre>";
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