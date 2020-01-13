<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 12.01.2020
 */

namespace Fw\Components\Services\Entity;

use Exception;
use Fw\Components\Services\Database\QueryBuilder;

/**
 * Class Entity
 * @package Fw\components\services\Database
 */
class Entity
{
	public $entities;
	public $QueryBuilder;


	/**
	 * Entity constructor.
	 * @param QueryBuilder $QueryBuilder
	 * @throws Exception
	 */
	public function __construct(QueryBuilder $QueryBuilder)
	{
		$this->QueryBuilder = $QueryBuilder;
		$this->entities = $this->getEntities();
	}


	/**
	 * @param string $entity
	 * @param array $where
	 * @param null $columns
	 * @param null $orderBy
	 * @param null $limit
	 * @return array|false
	 * @throws Exception
	 */
	public function getOne(string $entity, array $where, $columns=null, $orderBy=null, $limit=null)
	{
		if (!array_key_exists($entity, $this->entities))
			throw new Exception("\"{$entity}\" entity not found");

		$result = $this->QueryBuilder->select($columns)->from($entity)->where($where)->orderBy($orderBy)->limit($limit)->one();

		return $result;
	}


	/**
	 * @return array
	 * @throws Exception
	 */
	private function getEntities()
	{
		$tables = $this->getNameTables();
		$result = [];
		foreach ($tables as $key => $entity)
		{
			$matches = preg_grep("#^{$entity}_.+$#", $tables);
			if (count($matches) > 0)
			{
				unset($tables[$key]);

				$result[$entity] = [];
				foreach ($matches as $matchKey => $match)
				{
					$result[$entity][] = str_replace("{$entity}_","",$match);
					unset($tables[$matchKey]);
				}
			}
		}
		foreach ($tables as $entity)
		{
			$result[$entity] = [];
		}
		unset($tables);

		return $result;
	}


	/**
	 * @return array
	 * @throws Exception
	 */
	private function getNameTables()
	{
		$result = $this->QueryBuilder->show("tables")->all();

		$tables = [];
		foreach ($result as $table)
		{
			$tableName = array_shift($table);
			$tables[] = $tableName;
		}
		return $tables;
	}
}