<?php
/**
 * Treat: F4N70M
 * Version: 0.1
 * Date: 03.02.2020
 */

namespace Fw\Components\Modules\Pages;

use Exception;
use Fw\Components\Services\Entity\Entity;

class Pages
{
	private $Entity;

	/**
	 * Treats constructor.
	 * @param Entity $Entity
	 */
	public function __construct(Entity $Entity)
	{
		$this->Entity = $Entity;
	}


	/**
	 * @return array|false
	 * @throws Exception
	 */
	public function getIndexID()
	{
		$indexID = $this->Entity->selectFirst('options',['name'=>'index'])['value'];
		return $indexID;
	}


	/**
	 * @param $uniq
	 * @return array|false
	 * @throws Exception
	 */
	public function get($uniq)
	{
		if ((string) $uniq === (string)(int) $uniq)
			$column = 'id';
		elseif (is_string($uniq) && !empty($uniq))
			$column = 'name';
		else
			throw new Exception("Invalid argument type \"{$uniq}\"");

		return $this->Entity->selectFirst('objects',[$column=>$uniq]);
	}

	/**
	 * @param int $id
	 * @param array $parameters
	 * @return mixed
	 */
	public function set(int $id, array $parameters = [])
	{
	}
}