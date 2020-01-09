<?php

namespace Fw;
	
use \Exception;
use \Fw\Di\Container;

/**
 * Class Core
 * @package Fw
 */
class Core
{


	/**
	 * @var Container
	 */
	public 	$container;


	/**
	 *
	 * @throws Exception
	 */
	function __construct(array $config = [])
	{
	    //  Создаем объект DI контейнера
		$this->container = new Container();
		//  Получаем стандартную конфигурацию
		$config = json(file_get_contents(__DIR__.'/config/default.json'),false);
		$this->config = $config;
		//  Создаем константы
		$this->definePathConstants($this->config['paths']);
		//  Включаем логирование
		init_log_settings($this->config['debug']);
		//  Подключаем сервисы
		$this->initServices();
		//  Инициализация роутов
		$this->initRoutes();




		$responseServer = null;
//		debug('Mailer',$this->container['Mailer']->send('edkontr@yandex.ru', 'test2', 'Hello','Sender'));
		//$a = 1 / 0;

		//$this->initConstants();
		//$this->initDefaultConfigs();
		//$this->initSettings();
		//$this->initServices();
		//$this->verifyLicense();
		//$this->initModules();
	}


	/**
	 * @param $name
	 * @return mixed
	 * @throws Exception
	 */
	public function __get($name)
	{
		if (isset($this->container[$name]))
			return $this->container[$name];
		else
			throw new Exception("\$Fw->{$name} Not found");

	}


	/**
	 * @param $name
	 * @param $value
	 * @return mixed
	 * @throws Exception
	 */
	public function __set($name,$value)
	{
		return $this->container[$name] = $value;

	}


	/**
	 * @throws Exception
	 */
	private function initServices()
	{
		$services = $this->container['config']['services'];

		foreach ($services as $service => $config)
		{
			if ($config['include'])
			{
				$providerFile = sprintf("%s/services/Provider_%s.php", FW_DIR, $service);
				if(file_exists($providerFile))
				{
					$this->container[$service] = require $providerFile;
				}
				else
				{
					throw new Exception('Service provider "' . $service . '" not found');
				}
			}
		}
	}


	/**
	 * Инициализация роутов
	 * @throws Exception
	 */
	private function initRoutes()
	{
		$apps = $this->container['config']['apps'];

		foreach ($apps as $app => $config)
		{
			$routes = $config['routes'];

			if (is_array($routes))
			{

				$this->container['Router']->setApp($app, $config['prefix']);

				$classPrefix = 'Apps\\' . ucfirst($app) . '\\Controllers';

				foreach ($routes as $pattern => $route)
				{
					$route['controller'] = $classPrefix . '\\' . $route['controller'];

					$this->container['Router']->setRoute($app, $pattern, $route);
				}
			}
			else
			{
				throw new Exception("");
			}
		}
	}


	/**
	 * @param array $constants
	 */
	private function definePathConstants(array $constants)
	{
		foreach($constants as $key => $value)
		{
			define(strtoupper($key) . '_DIR', str_replace('\\', '/', getcwd() . $value));
			define(strtoupper($key) . '_URI', str_replace('\\', '/', $value));
		}
	}





























































	public function getApp(string $uri = null)
	{
		$config = $this->Services->Router->getAppConfig($uri);
		debug('$config', $config);
//		debug('$this->config', $this->config);

//		$controller =
		$this->di->set('app',$config['controller'],true,$config['arguments']);
		$app = $this->di->get('app');

		return $app;
	}




	/*
	 * Конвертация Uri в Class
	 */
	private function convertUriToClass($uri)
	{
		$classPrefixArray = preg_split(
			"#[\/\\\\]#u",
			$uri,
			-1,
			PREG_SPLIT_NO_EMPTY
		);
		foreach ($classPrefixArray as $prefixKey => $prefixPart)
		{
			$classPrefixArray[$prefixKey] = mb_ucfirst($prefixPart);
		}
		return implode('\\', $classPrefixArray);
	}


    /**
     *
     */
	public function initDefaultConfigs()
	{
//		try
//		{
			$pattern = '#/([^/]+)\.json'.'$#';
			$files = glob(CONFIG_DIR.'/*.json');
			foreach ($files as $file)
			{
				if (preg_match($pattern, $file, $matches))
				{
					$name	= $matches[1];

					$content = file_get_contents($file);
					if (!is_json($content)) throw new Exception("Invalid format for contents of configuration file «{$name}»");

					$config = json($content,false);

					$this->config[$name] = $config;
				}
			}
//		}
//		catch (Exception $e)
//		{
//			debug('Error: '.$e->getMessage());
//		}
	}




	/*
	 * Инициализация Настроек
	 */
	private function initSettings()
	{
		$settingsJson = $this->getConfig('settings');
		$this->settings = json($settingsJson,false);
	}




	/*
	 *
	 */
	/*public function initServices()
	{
		$this->di->set(
			'Services',			//	$name
			'Fw\\Core\\Services',		//	$class
			true,				//	$singleton
			[					//	$arguments
				'di'		=>	$this->di,
				'services'	=>	$this->getConfig('services')
			]
		);

		$this->Services = $this->di->get('Services');
	}*/




	/*
	 *
	 */
	public function initModules()
	{
		$this->di->set(
			'Modules',				//	$name
			'Fw\\Core\\Modules',	//	$class
			true,					//	$singleton
			[						//	$arguments
				'di'		=>	$this->di,
				'modules'	=>	$this->getConfig('modules')
			]
		);

		$this->Modules = $this->di->get('Modules');
	}




	/*
	 *
	 */
	public function initModule($name,$srcName)
	{
		$path		= FW_DIR . '/modules/'.$name;
		$providerClass	= "\\Fw\\Modules\\".ucfirst($name)."\\Provider";
		$providerPath	= $path . "/provider.php";
		if (file_exists($providerPath))
		{
			require $providerPath;
			$provider = new $providerClass($srcName, $this->di);
			
			$this->di->set(
				$provider->name,
				$provider->class,
				$provider->params,
				$provider->singleton
			);
		}
	}

    private function verifyLicense()
    {
        $this->Services->License->verify();
    }
}