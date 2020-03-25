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
		$config = json(file_get_contents(__DIR__.'/Config/default.json'),false);
		$this->config = $config;
		//  Создаем константы
		$this->definePathConstants($this->config['paths']);
		//  Включаем логирование
		init_log_settings($this->config['debug']);
		//  Подключаем сервисы
		$this->initServices();
		//  Подключаем модули
		$this->initModules();
		//  Инициализация роутов
		$this->initRoutes();


		$this->Auth;
	}


	/**
	 * @param string $name
	 * @return mixed
	 */
	public function __get(string $name)
	{
		return $this->container->get($name);
	}


	/**
	 * @param string $name
	 * @param array $parameters
	 * @return mixed
	 */
	public function __call(string $name, array $parameters = [])
	{
		return $this->container->get($name, $parameters);
	}


	/**
	 * @param string $name
	 * @param mixed $value
	 */
	public function __set(string $name, $value)
	{
		$this->container->set($name, $value);
	}


	/**
	 * @throws Exception
	 */
	private function initComponents($list)
	{
		if (is_array($list)) {
			foreach ($list as $name => $include)
			{
				if ($include)
				{
					$providerClass = "\\Fw\\Components\\Providers\\Provider_{$name}";
					if (class_exists($providerClass))
						new $providerClass($this->container);
					else
						throw new Exception("Provider for component \"{$name}\" is not found");
				}
			}
		}
	}


	/**
	 * @throws Exception
	 */
	private function initServices()
	{
		$config = $this->container->get('config');
		if (isset($config['services']))
		{
			$list = $config['services'];
			$this->initComponents($list);
		}
	}


	/**
	 * @throws Exception
	 */
	private function initModules()
	{
		$config = $this->container->get('config');
		if (isset($config['modules']))
		{
			$list = $config['modules'];
			$this->initComponents($list);
		}
	}


	/**
	 * Инициализация роутов
	 * @throws Exception
	 */
	private function initRoutes()
	{
		$config = $this->container->get('config');
		$apps = $config['apps'];

		foreach ($apps as $app => $appConfig)
		{
			if (!$appConfig['include']) continue;

			$appConfigPath = APPS_DIR.'/'.ucfirst($app).'/config.json';

			if (file_exists($appConfigPath))
			{
				$tmpConfig = json(file_get_contents($appConfigPath));
				if (isset($tmpConfig['routes']))
				{
					$routes = $tmpConfig['routes'];
				}
			}
			if (!isset($routes)) continue;

//			$routes = $appConfig['routes'];


			if (is_array($routes))
			{
				$router = $this->container->get('Router');
				$router->setApp($app, $appConfig['prefix']);

				$classPrefix = 'Apps\\' . ucfirst($app) . '\\Controller';

				foreach ($routes as $pattern => $route)
				{
					$route['controller'] = $classPrefix . '\\' . $route['controller'];

					$router->setRoute($app, $pattern, $route);
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
	public function initModule($name,$srcName)
	{
		$path		= FW_DIR . '/modules/'.$name;
		$providerClass	= "\\Fw\\Components\\Modules\\".ucfirst($name)."\\Components_Provider";
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