<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 29.06.2020
 */

namespace Fw\Components\Services\Cache;

class Cache
{
	private $table;
	private $storageLife = 30; // days
	private $lifetime    = 60; // seconds

	public function __construct()
	{
		$this->table = $this->getTable();
	}

	public function save(string $key, string $value, $params=[])
	{
		$format = 'Y\\'.DIRECTORY_SEPARATOR.'m\\'.DIRECTORY_SEPARATOR.'d\\'.DIRECTORY_SEPARATOR.'H-i-s__';
		$prefix = date($format);
		$path = $prefix . $key;
		debug($path);

		$file = __DIR__ . $path;
	}

	public function load($key)
	{
	}

	private function getTable()
	{
		$table = [];
		$tableFile = __DIR__.DIRECTORY_SEPARATOR.'storage'.DIRECTORY_SEPARATOR.'table.json';
		if (file_exists($tableFile))
		{
			$tableJson = file_get_contents($tableFile);
			$table = json($tableJson, false);
		}
		return $table;
	}
}