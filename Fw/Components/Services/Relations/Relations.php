<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 25.03.2020
 */

namespace Fw\Components\Services\Relations;

use Fw\Components\Services\Entity\Entity;
use Exception;

class Relations
{
	protected $Entity;
	protected $entityName;

	public function __construct(Entity $Entity)
	{
		$this->Entity = $Entity;
		$this->entityName = 'relations';
	}


	public function getList(string $parentTable, int $parentId, string $childTable)
	{
		$result = $this->Entity->select($this->entityName,['parent_table'=>$parentTable,'parent_id'=>$parentId,'child_table'=>$childTable]);
		debug($result);
	}


	/**
	 * @param string $parentTable
	 * @param int $parentId
	 * @param string $childTable
	 * @param int $childId
	 * @return int
	 * @throws Exception
	 */
	public function set(string $parentTable, int $parentId, string $childTable, int $childId)
	{
		$result = $this->Entity->insert($this->entityName,['parent_table'=>$parentTable, 'parent_id'=>$parentId, 'child_table'=>$childTable, 'child_id'=>$childId]);
		return $result;
	}
}