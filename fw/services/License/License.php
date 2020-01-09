<?php
/**
 * Created by PhpStorm.
 * User: edkon
 * Date: 28.09.2019
 * Time: 0:30
 */

namespace Fw\Core\Services;


class License
{
    private $config;
    private $editions;
    private $edition;
    private $apps;
    private $modules;


	/**
	 * License constructor.
	 * @param array $config
	 * @param array $editions
	 */
    function __construct(array $config, array $editions)
    {
		$this->config	= $config;
		$this->editions	= $editions;

        $this->edition	= $this->getEdition();

		$this->initAvailableApps();
		$this->initAvailableModules();
    }


	/**
	 * @param string|null $key
	 */
    private function keyVerification(string $key = null)
    {
    }


	/**
	 *
	 */
    public function verify()
    {}


	/**
	 *
	 */
	private function initAvailableApps()
	{
		$apps = [];
		foreach ($this->editions['apps'] as $app => $editions)
		{
			if (isset($editions[$this->edition]) && $editions[$this->edition])
				$apps[]	= $app;
		}
		$this->apps		= $apps;
	}


	/**
	 *
	 */
	private function initAvailableModules()
	{
		$modules = [];
		foreach ($this->editions['modules'] as $module => $editions)
		{
			if (isset($editions[$this->edition]) && $editions[$this->edition])
				$modules[]	= $module;
		}
		$this->modules	= $modules;
	}


	/**
	 * @return mixed
	 */
	private function getAvailableApps()
	{
		return $this->apps;
	}


	/**
	 * @return mixed
	 */
	private function getAvailableModules()
	{
		return $this->modules;
	}


	/**
	 * @return string
	 */
	private function getEdition()
	{
		return "free";
	}
}