<?php
/**
 * @author: KONARD
 * @version: 1.0
 */

namespace Fw\Services\Db;

use \Fw\Services\Db\QueryBuilder;

use \PDO;

class Db
{
	private $_link;
	private $_qb;

	/**
	 * Db constructor
	 * @param array $config
	 */
	public function __construct(array $config, QueryBuilder $queryBuilder)
	{
		$dsn = "mysql:host=".$config['HOST'].";dbname=".$config['BASE'].";charset=utf8";
		$user = $config['USER'];
		$pass = $config['PASS'];

		$this->_link = new PDO($dsn, $user, $pass);

		$this->_qb = $queryBuilder;
	}




	/**
	 * @return bool
	 */
	public function checkLink()
	{
		return ($this->_link) ? true : false;
	}




	/**
	 * @return PDO
	 */
	public function getLink()
	{
		return $this->checkLink() ? $this->_link : false;
	}




	/**
	 * @param string $sql
	 * @param array $params
	 * @return QueryBuilder
	 */
	public function query(string $sql, array $params = [])
	{
		$object = $this->_qb->getObject($this->_link);
		$object->custom($sql,$params);
		
		return $object;
	}




	/**
	 * @param mixed $columns
	 * @return QueryBuilder
	 * @throws \Exception
	 */
	public function select($columns=null)
	{
		$object = $this->_qb->getObject($this->_link, "SELECT");
		$object->columns($columns);
		
		return $object;
	}




	/**
	 * @param $table
	 * @return QueryBuilder
	 */
	public function insert($table)
	{
		$object = $this->_qb->getObject($this->_link, "INSERT");
		$object->into($table);
		
		return $object;
	}




	/**
	 * @param string $table
	 * @return QueryBuilder
	 */
	public function update(string $table)
	{
		$object = $this->_qb->getObject($this->_link, "UPDATE");
		$object->table($table);
		
		return $object;
	}




	/**
	 * @return QueryBuilder
	 */
	public function delete()
	{
		$object = $this->_qb->getObject($this->_link, "DELETE");
		
		return $object;
	}
}