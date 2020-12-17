<?php


namespace Fw\Components\Services\Database;


use PDO;
use Exception;

class Connection
{
	private $_link;

	public $microtime = [];


	/*public function __construct(array $config)
	{
		$dsn = "mysql:host=".$config['HOST'].";dbname=".$config['BASE'].";charset=utf8";
		$user = $config['USER'];
		$pass = $config['PASS'];

		$this->_link = new PDO($dsn, $user, $pass);
		$this->_link->query('SET NAMES utf8');
	}*/
	public function __construct($host, $user, $password, $base)
	{
		$dsn = "mysql:host=".$host.";dbname=".$base.";charset=utf8";

		try
		{
			$this->_link = new PDO($dsn, $user, $password);
			$this->_link->query('SET NAMES utf8');
		}
		catch (Exception $e)
		{
			throw new Exception("failed to connect to database");
		}
	}




	/**
	 * @return PDO
	 */
	public function get()
	{
		return $this->check() ? $this->_link : false;
	}




	/**
	 * @return bool
	 */
	private function check()
	{
		return !empty($this->_link);
	}
}