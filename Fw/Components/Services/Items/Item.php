<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 19.03.2020
 */

namespace Fw\Components\Services\Items;


use Exception;
use Fw\Components\Services\Entity\Entity;

class Item
{
	protected $entityName;
	protected $where = [];
	protected $data;
	protected $Entity;


	/**
	 * ItemManager constructor.
	 * @param Entity $Entity
	 * @param string $entityName
	 * @param int $id
	 */
	public function __construct(Entity $Entity, string $entityName, int $id)
	{
		$this->Entity = $Entity;
		$this->entityName = $entityName;
		$this->where['id'] = $id;
	}


	/**
	 * @return array|false
	 * @throws Exception
	 */
	public function info()
	{
		if (!is_array($this->data))
			$this->data = $this->Entity->selectFirst($this->entityName, $this->where);
		return $this->data;
	}


	/**
	 * @param $key
	 * @return bool
	 * @throws Exception
	 */
	private function has($key)
	{
		$data = $this->info();
		return isset($data[$key]);
	}


	/**
	 * @param $key
	 * @return mixed
	 * @throws Exception
	 */
	public function get($key)
	{
		if ($this->has($key))
			return $this->data[$key];
		else
			return false;
	}


	/**
	 * @param $key
	 * @param $value
	 * @return bool
	 * @throws Exception
	 */
	public function set($key,$value)
	{
		if ($this->has($key))
		{
			$result = $this->Entity->update($this -> entityName, [$key => $value], $this -> where);
			if ($result) {
				$this->data[$key] = $value;
			}
			return (bool) $result;
		}
		else
		{
			return false;
		}
	}


	/**
	 * @param array $data
	 * @return bool
	 * @throws Exception
	 */
	public function edit(array $data)
	{
		return (bool) $this->Entity->update($this->entityName, $data, $this->where);
	}


	/**
	 * @return bool
	 * @throws Exception
	 */
	public function delete()
	{
		$result = $this->Entity->delete($this->entityName, $this->where);
		return $result;
	}
}