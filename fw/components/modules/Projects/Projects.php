<?php
/**
 * Project: F4N70M
 * Version: 0.1
 * Date: 10.01.2020
 */

namespace Fw\Components\Modules\Projects;

use Exception;
use Fw\Components\Services\Entity\Entity;

class Projects
{
	private $Entity;

	/**
	 * Projects constructor.
	 * @param Entity $Entity
	 */
	public function __construct(Entity $Entity)
	{
		$this->Entity = $Entity;
	}


	/**
	 * @param int $userID
	 * @return array|false
	 * @throws Exception
	 */
	public function getUserProjects(int $userID)
	{
		return $this->Entity->select('objects', ['type'=>'project','user'=>$userID]);
	}


	/**
	 * @param int $projectID
	 * @return array|false
	 * @throws Exception
	 */
	public function get(int $projectID)
	{
		return $this->Entity->selectFirst('objects', ['type'=>'project','id'=>$projectID]);
	}


	/**
	 * @param string $name
	 * @param int $userID
	 * @return int
	 * @throws Exception
	 */
	public function createProject(string $name, int $userID)
	{
		$data = [
			'title'  => $name,
			'type'  => 'project',
			'user'  => $userID
		];
		return $this->Entity->insert('objects', $data);
	}


	/**
	 * @param int $projectId
	 * @return array
	 * @throws Exception
	 */
	public function deleteProject(int $projectId)
	{
		return $this->Entity->delete('objects', ['id'=>$projectId]);
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