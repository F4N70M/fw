<?php

namespace Fw;
	
use \Exception;
use Fw\Di\Di;

/**
 * 
 */
class Core
{




	/*
	 *
	 */
	private	$di;
	private	$config		= [];
	public	$services;
	private	$modules;




	/*
	 *
	 */
	function __construct(array $config = [])
	{
		$this->di = new Di();

		// $this->di->set('db',\Fw\Core\Services\Db::class,true);
		// $this->di->get('db');

		$this->initConstans();

		$this->initSettings();

		$this->initServices();

		$this->initRoutes();


		$this->initModules();

		debug($this->di);
	}




	/*
	 * Регистрация констант
	 */
	private function initRoutes()
	{
		$config = $this->getConfig('routes');
		debug($config);
		$router = $this->di->Router;
	}




	/*
	 * Регистрация роутов
	 */
	private function initConstans()
	{
		$constants = json(file_get_contents(__DIR__.'/config/constants.json'),false);
		foreach($constants as $key => $value)
		{
			define(strtoupper($key) . '_DIR', str_replace('\\', '/', getcwd()) . $value);
		}
	}




	/*
	 * 
	 */
	public function getConfig($name)
	{
		try
		{
			if (!isset($this->config[$name]))
			{
				$config = [];

				$file = CONFIG_DIR.'/'.$name.'.json';

				if (!file_exists($file)) throw new Exception("configuration file «{$name}» does not exists");

				$content = file_get_contents($file);
				if (!is_json($content)) throw new Exception("Invalid format for contents of configuration file «{$name}»");

				$config = json($content,false);

				$this->config[$name] = $config;
			}

			return $this->config[$name];
		}
		catch (Exception $e)
		{
			debug('Error: '.$e->getMessage());
		}
	}




	/*
	 * Подключение функций
	 */
	private function initFunctions()
	{
		require FW_DIR . '/functions.php';
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
	public function initServices()
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

		$this->services = $this->di->get('Services');
	}




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
				'modules'	=>	$this->getConfig('Modules')
			]
		);

		$this->modules = $this->di->get('Modules');
	}




	/*
	 *
	 */
	/*public function initModules()
	{
		$configModules = $this->getConfig('modules');
		// debug($configModules);

		$modulesDir = FW_DIR . '/modules';

		foreach ($configModules as $moduleName => $moduleSrc)
		{
			if (isset($moduleSrc['class']) && isset($moduleSrc['arguments']))
			{
				$this->di->set(
					$moduleName,	//	$name
					$moduleSrc['class'],	//	$class
					$moduleSrc['arguments'],	//	$arguments
					isset($moduleSrc['singleton']) ? $moduleSrc['singleton'] : false,	//	$singleton
					isset($moduleSrc['preload']) ? $moduleSrc['preload'] : false	//	$preload
				);
			}
			//$this->initModule($moduleName,$moduleSrc);
		}
	}*/




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
}