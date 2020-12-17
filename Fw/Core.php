<?php

namespace Fw;
	
use \Exception;
use \Fw\Components\Di\Container;

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
		//  Проверка подключения БД
		$installUri = 'admin/setup';
//		debug(!$this->validateDatabase(), $this->Router->getCurrentUri(), $installUri);
		if (!$this->validateDatabase() && $this->Router->getCurrentUri() != $installUri)
		{
			header('Location: /'.$installUri);
			exit;
		}


		$this->Auth;
	}



	private function validateDatabase()
	{
		$validate = true;
		$qb =  $this->Db;
		$show = $qb->show('tables')->result();
//		debug($qb->show('index')->result());
		$tables = [];
		foreach ($show as $item)
		{
			$tables[] = array_shift($item);
		}
		$sample = [
			'objects'
		];
		foreach ($sample as $table)
		{
			if (!in_array($table, $tables))
				$validate = false;
		}

		return $validate;
	}


	/**
	 * @param string $name
	 * @return mixed
	 * @throws Exception
	 */
	public function __get(string $name)
	{
		return $this->container->get($name);
	}


	/**
	 * @param string $name
	 * @param array $parameters
	 * @return mixed
	 * @throws Exception
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
					$providerClass = $name;
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
		$this->initComponents($this->getComponentList('services'));
	}


	/**
	 * @throws Exception
	 */
	private function initModules()
	{
		$this->initComponents($this->getComponentList('modules'));
	}


	/**
	 * @param string $componentsName
	 * @return array
	 * @throws Exception
	 */
	public function getComponentList(string $componentsName)
	{
		$list = [];
		$config = $this->container->get('config');
		if (isset($config[$componentsName]))
		{
			foreach ($config[$componentsName] as $name => $include)
			{
				$providerClass = "\\Fw\\Components\\" . ucfirst($componentsName) . "\\Provider_{$name}";
				$list[$providerClass] = $include;
			}
		}
		return $list;
	}


	/**
	 * Инициализация роутов
	 * @throws Exception
	 */
	private function initRoutes()
	{
		$FwConfig = $this->container->get('config');
		$apps = $FwConfig['apps'];

		foreach ($apps as $app => $config)
		{
			if (!$config['include']) continue;

			$appConfigPath = APPS_DIR.'/'.ucfirst($app).'/config.json';

			if (file_exists($appConfigPath))
			{
				$appConfig = json(file_get_contents($appConfigPath));
				if (isset($appConfig['routes']))
				{
					$routes = $appConfig['routes'];
				}
			}

			if (!isset($routes)) continue;

			if (is_array($routes))
			{
				$router = $this->container->get('Router');
				$router->setApp($app, $config['prefix'], $appConfig);

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