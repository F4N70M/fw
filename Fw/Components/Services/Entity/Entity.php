<?php
/**
 * Treat: F4N70M
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
	protected $entityName;
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
	 * @return array|false
	 * @throws Exception
	 */
	public function selectFirst(string $entity, array $where, $columns=null, $orderBy=null)
	{
		if (!array_key_exists($entity, $this->entities))
			throw new Exception("\"{$entity}\" entity not found");

		$result = $this->select($entity, $where, $orderBy, 1);

		return array_shift($result);
	}


	/**
	 * @param string $entity
	 * @param array $data
	 * @return int
	 * @throws Exception
	 */
	public function insert(string $entity, array $data)
	{
		if ($this->hasTypes($entity) && isset($data['type']))
		{
			$data['type'] = $this->getTypeId($entity, $data['type']);

			if ($this->hasMetaTables($entity))
				$metaColumns = $this->getMetaColumns($entity, $data['type']);
		}

		$columns = $this->entities[$entity]['columns'];

		$queryColumns = [];
		foreach ($columns as $column)
		{
			if (isset($data[$column]))
				$queryColumns[$column] = $data[$column];
		}

		if ($this->NameNeedsCreate($entity, $queryColumns))
		{
			if (!empty($queryColumns['title'])) :
				$name = $this->titleToName($queryColumns['title']);
			elseif(isset($data['type'])) :
				$name = $this->entities[$entity]['types'][$data['type']];
			else :
				$name = $entity;
			endif;

			$queryColumns['name'] = $this->createUniqueNameFromTitle($entity, $name);
		}


		/** @var int $resultID */
		$resultID = $this->QueryBuilder
			->insert()
			->into($entity)
			->values($queryColumns)
			->result();

		if ($resultID && isset($metaColumns) && !empty($metaColumns))
			$this->insertMetaValues($entity, $resultID, $metaColumns, $data);

		return $resultID;
	}


	/**
	 * @param string $entity
	 * @param array $data
	 * @param array|null $where
	 * @return int $updateCount
	 * @throws Exception
	 */
	public function update(string $entity, array $data, array $where=null)
	{
		$updateCount = 0;

		$columns = $this->entities[$entity]['columns'];
		$select = $this->select($entity,$where);

		foreach ($select as $item)
		{
			$queryColumns = [];
			$metaColumns = [];
			foreach ($item as $column => $value)
			{
				if (isset($data[$column]) && $data[$column] !== $value)
				{
					if (in_array($column,$columns))
						$queryColumns[$column] = $data[$column];
					else
					{
						$metaColumns[$column] = $data[$column];
					}
				}
			}


			if (!empty($queryColumns))
			{
				$result = (bool) $this->QueryBuilder
					->update($entity)
					->set($queryColumns)
					->where(['id' => $item['id']])
					->result();

				if ($result) $updateCount++;
			}

			foreach ($metaColumns as $column => $value)
			{
				$issetMetaValue = $this->QueryBuilder
					->select()
					->from($entity.'_meta')
					->where(['item_id' => $item['id'], 'meta_key'=>$column])
					->result();

				if ($issetMetaValue)
				{
					 $this->QueryBuilder
						->update($entity.'_meta')
						->set(['meta_value' => $value])
						->where(['item_id' => $item['id'], 'meta_key' => $column])
						->result();
				}
				else
				{
					$this->QueryBuilder
						->insert()
						->into($entity.'_meta')
						->values(['item_id' => $item['id'], 'meta_key' => $column, 'meta_value' => $value])
						->result();
				}
			}
		}

		return $updateCount;
	}


	/**
	 * @param string $entity
	 * @param array|null $where
	 * @return bool
	 * @throws Exception
	 */
	public function delete(string $entity, array $where=null)
	{
		if ($this->hasMetaTables($entity))
		{
			$list = $this->select($entity, $where);

			if (!empty($list))
			{
				foreach ($list as $item)
				{
					$this->QueryBuilder
						-> delete()
						-> from($entity)
						-> where(['id'=>$item['id']])
						-> result();

					$this->QueryBuilder
						-> delete()
						-> from($entity . "_meta")
						-> where(['item_id'=>$item['id']])
						-> result();
				}
			}
		}
		else
		{
			$this->QueryBuilder
				->delete()
				->from($entity)
				->where($where)
				->result();
		}
		return true;
	}


	/**
	 * @param string $entity
	 * @param array|null $where
	 * @param null $orderBy
	 * @param null $limit
	 * @return array
	 * @throws Exception
	 */
	public function select(string $entity, array $where=null, $orderBy=null, $limit=null)
	{
		$tables =$this->entities[$entity]['tables'];

		$joins = [];
		$columns = [];
		$typesColumns = [];
		$aliases = [];
		$joinOn = [];


		//  Определяем колонки основной таблицы
		foreach ($this->entities[$entity]['columns'] as $column)
		{
			$aliases[$column] = "$entity.$column";
			$columns[$column] = "$entity.$column";
			$typesColumns[null][] = $column;
		}

		if (in_array('types',$tables))
		{
			//  Подменяем поле 'type' из основной таблицы на поле 'name' из таблицы _types
			$columns['type'] = "types.name";
			$joinOn['types']['id'] = "$entity.type";
			$joins['types'] = $entity.'_types';
			$aliases['type'] = "types.name";
		}
		if (in_array('keys',$tables) && in_array('meta',$tables))
		{
			//  Находим ключи meta-полей из таблицы _keys
			$tmpMetaColumns = $this->QueryBuilder
				->select()
				->from($entity.'_keys')
				->where()
				->result();

			if (is_array($tmpMetaColumns))
			{
				foreach ($tmpMetaColumns as $key => $meta)
				{
					$typesColumns[$meta['type']][] = $meta['meta_key'];
					$table = 'meta'.$key;
					//  Добавляем таблицу для выборки
					$joins[$table] = $entity.'_meta';
					//  Добавляем поле для выборки
					$metaKey = $meta['meta_key'];
					$column = $table.'.meta_value';
					$columns[$metaKey] = $column;
					$aliases[$metaKey] = $column;
					//  Добавляем условие
					$joinOn[$table]['meta_key'] = '\''.$meta['meta_key'].'\'';
					$joinOn[$table]['item_id'] = $entity.'.id';
				}
			}
		}

		$where = $this->where($where,$aliases);

		$queryColumnsArr = [];
		foreach ($columns as $key => $column)
		{
			$queryColumnsArr[] = $column.' as '.$key;
		}
		$queryColumns = implode(", ", $queryColumnsArr);

		$queryFrom = "$entity";
		//  joins
		foreach ($joins as $tkey => $join)
		{
			$queryFrom .= " LEFT JOIN ";
			$queryFrom .= "$join as $tkey";
			$queryFrom .= " ON ";
			$queryOn = [];
			foreach ($joinOn[$tkey] as $ckey => $value)
			{
				$queryOn[] = "$tkey.$ckey = $value";
			}
			$queryFrom .= implode(' && ', $queryOn);
		}

		$result = $this->QueryBuilder
			->select($queryColumns)
			->from($queryFrom)
			->where($where)
			->orderBy($orderBy)
			->limit($limit)
			->result();

		if (isset($this->entities[$entity]['types']))
			$typesFlip = array_flip($this->entities[$entity]['types']);

		if(is_array($result))
		{
			foreach ($result as $key => $item)
			{
//				debug($typesColumns[null],$typesFlip[$item['type']],$typesColumns);
				foreach ($item as $column => $value)
				{
					if(
						!((
							isset($typesColumns[null]) &&
							in_array($column,$typesColumns[null])
						) ||
						(
							isset($item['type']) &&
							isset($typesFlip) &&
							isset($typesFlip[$item['type']]) &&
							isset($typesColumns[$typesFlip[$item['type']]]) &&
							in_array($column,$typesColumns[$typesFlip[$item['type']]])
						))
					)
					{
						unset($result[$key][$column]);
					}
				}
			}
		}
		return $result;

		/*
		SELECT
			objects.id as id,
			types.name as type,
			objects.name as name,
			objects.title as title,
			objects.description as description,
			objects.content as content,
			objects.date as date,
			meta0.meta_value as value,
			meta1.meta_value as test,
			meta2.meta_value as test2,
			meta3.meta_value as test4
		FROM
			objects as objects
		LEFT JOIN
		    objects_types as types
		ON
			types.id = objects.type
		LEFT JOIN
		    objects_meta as meta0
		ON
			meta0.meta_key = 'value' &&
		    meta0.item_id = objects.id
		LEFT JOIN
		    objects_meta as meta1
		ON
		    meta1.meta_key = 'test' &&
		    meta1.item_id = objects.id
		LEFT JOIN
		    objects_meta as meta2
		ON
		    meta2.meta_key = 'test2' &&
		    meta2.item_id = objects.id
		LEFT JOIN
		    objects_meta as meta3
		ON
		    meta3.meta_key = 'test4' &&
		    meta3.item_id = objects.id
		WHERE
			meta0.meta_value = 1
		 */
	}


	/**
	 * @param mixed $where
	 * @param array $aliases
	 * @return array
	 */
	private function where($where, array $aliases = [])
	{
		$result = [];
		if (is_array($where))
		{
			foreach ($where as $key => $value)
			{
				if (is_array($value))
				{
					$value = $this->where($value, $aliases);
				}
				$result[(is_string($key) && isset($aliases[$key]) ? $aliases[$key] : $key)] = $value;
			}
		}
		else
		{
			$result = $where;
		}
		unset($where);
		return $result;
	}


	/**
	 * @param string $entity
	 * @return bool
	 */
	private function hasTypes(string $entity)
	{
		return
			in_array('type', $this->entities[$entity]['columns']) &&
			in_array('types', $this->entities[$entity]['tables']);
	}


	/**
	 * @param string $entity
	 * @param $type
	 * @return false|int|string
	 */
	private function getTypeId(string $entity, $type)
	{
		return (!is_numeric($type))
			? $data['type'] = array_search($type, $this->entities[$entity]['types'])
			: $type;
	}


	/**
	 * @param string $entity
	 * @return bool
	 */
	private function hasMetaTables(string $entity)
	{
		return
			in_array('keys', $this->entities[$entity]['tables']) &&
			in_array('meta', $this->entities[$entity]['tables']);
	}


	/**
	 * @param string $entity
	 * @param array $columns
	 * @return bool
	 */
	private function NameNeedsCreate(string $entity, array $columns)
	{
		return
			in_array('title',$this->entities[$entity]['columns']) &&
			in_array('name',$this->entities[$entity]['columns']) &&
			(
				!isset($columns['name']) ||
				(
					isset($columns['name']) &&
					empty($columns['name'])
				)
			);
	}


	/**
	 * @param string $entity
	 * @param string $name
	 * @return string
	 * @throws Exception
	 */
	private function createUniqueNameFromTitle(string $entity, string $name)
	{
		$count = 0;

		do
		{
			$result = $name . ($count > 0 ? '-'.$count : null);
			$issetName = (bool) $this->QueryBuilder
				->select()
				->from($entity)
				->where(['name'=>$result])
				->result();
			$count++;
		}
		while (!empty($issetName));

		return $result;
	}


	/**
	 * @param string $entity
	 * @param int $itemId
	 * @param array $columns
	 * @param array $data
	 * @throws Exception
	 */
	private function insertMetaValues(string $entity, int $itemId, array $columns, array $data)
	{
		foreach ($columns as $column => $value)
		{
			if (isset($data[$column]))
				$value = $data[$column];

			if (!empty($value))
			{
				$metaValues = [
					'item_id'   =>  $itemId,
					'meta_key'  =>  $column,
					'meta_value'=>  $value
				];

				$this->QueryBuilder
					->insert()
					->into($entity.'_meta')
					->values($metaValues)
					->result();
			}
		}
	}


	/**
	 * @param string $entity
	 * @param int $type
	 * @return array
	 * @throws Exception
	 */
	private function getMetaColumns(string $entity, int $type=null)
	{
		$metaColumns = $this->entities[$entity]['metaColumns'][$type];
		return $metaColumns;
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

				$result[$entity]['tables'] = [];
				foreach ($matches as $matchKey => $match)
				{
					$result[$entity]['tables'][] = str_replace("{$entity}_", "", $match);
					unset($tables[$matchKey]);
				}
			}
		}
		foreach ($tables as $entity)
		{
			$result[$entity]['tables'] = [];
		}
		unset($tables);

		foreach ($result as $entity => $data)
		{
			$showColumns =  $this->QueryBuilder->show("columns",$entity)->result();

			foreach ($showColumns as $showColumn)
			{
				$columnName = array_shift($showColumn);
				$result[$entity]['columns'][] = $columnName;
			}

			if (in_array('types',$data['tables']))
			{
				$result[$entity]['types'] = [];
				$typesResults = $this->QueryBuilder->select()->from($entity.'_types')->result();
				foreach ($typesResults as $typesResult)
				{
					$result[$entity]['types'][$typesResult['id']] = $typesResult['name'];

				}


				$metaKeysResult = $this->QueryBuilder->select()->from($entity.'_keys')->result();
				$metaColumns = [];
				foreach ($metaKeysResult as $metaKey)
				{
					if ($metaKey['type'] == null)
					{
						$metaColumns[null][$metaKey['meta_key']] = $metaKey['default_value'];
						foreach ($result[$entity]['types'] as $type => $name)
						{
							$metaColumns[$type][$metaKey['meta_key']] = $metaKey['default_value'];
						}
					}
					else
					{
						$metaColumns[$metaKey['type']][$metaKey['meta_key']] = $metaKey['default_value'];
					}
				}
				$result[$entity]['metaColumns'] = $metaColumns;
			}


		}

		return $result;
	}


	/**
	 * @return array
	 * @throws Exception
	 */
	private function getNameTables()
	{
		$result = $this->QueryBuilder
			->show("tables")
			->result();

		$tables = [];
		foreach ($result as $table)
		{
			$tableName = array_shift($table);
			$tables[] = $tableName;
		}
		return $tables;
	}


	/**
	 * @param string $string
	 * @return string|string[]|null
	 */
	public function titleToName(string $string)
	{
		$translit = [
			'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'e', 'ж' => 'zj', 'з' => 'z',
			'и' => 'i', 'й' => 'i', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r',
			'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'kh', 'ц' => 'z', 'ч' => 'ch', 'ш' => 'sh',
			'щ' => 'sch','э' => 'e', 'ю' => 'yu', 'я' => 'ya', ' ' => '-', '–' => '-', '—' => '-', '_' => '-'];
		foreach ($translit as $key => $value)
		{
			$pattern = '#['.$key.']#ui';
			$string = preg_replace($pattern,$value,$string);
		}

		$string = preg_replace('#([^A-Za-z0-9\-])#ui','',$string);
		$string = preg_replace('#(-+)#ui','-',$string);
		$string = trim($string,"-");
		return $string;
	}
}