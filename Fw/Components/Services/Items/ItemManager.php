<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 19.03.2020
 */

namespace Fw\Components\Services\Items;


use Exception;
use Fw\Components\Services\Entity\Entity;

class ItemManager
{
	protected $entityName;
	protected $entityType;
	protected $Entity;

	/**
	 * ItemManager constructor.
	 * @param Entity $Entity
	 * @param string $entityName
	 */
	public function __construct(Entity $Entity, string $entityName)
	{
		$this->entityName = $entityName;
		$this->Entity = $Entity;
	}

	/**
	 * @param array $data
	 * @return int
	 * @throws Exception
	 */
	public function new(array $data)
	{
		if (!empty($this->entityType))
			$data['type'] = $this->entityType;
		return $this->Entity->insert($this->entityName, $data);
	}

	/**
	 * @param array $where
	 * @param bool $byId
	 * @return array
	 * @throws Exception
	 */
	public function get(array $where = [], $byId = false)
	{
		if (!empty($this->entityType))
			$where['type'] = $this->entityType;
		return $this->Entity->select($this->entityName, $where, $orderBy=null, $limit=null);
	}

	/**
	 * @param array $data
	 * @param array $where
	 * @return int $count
	 * @throws Exception
	 */
	public function edit(array $data, array $where)
	{
		if (!empty($this->entityType))
			$where['type'] = $this->entityType;
		return $this->Entity->update($this->entityName, $data, $where);
	}

	/**
	 * @param array $where
	 * @return bool
	 * @throws Exception
	 */
	public function delete(array $where)
	{
		if (!empty($this->entityType))
			$where['type'] = $this->entityType;
		return $this->Entity->delete($this->entityName, $where);
	}

}