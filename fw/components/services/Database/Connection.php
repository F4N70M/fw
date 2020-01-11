<?php


namespace Fw\Components\Services\Database;


use \PDO;

class Connection
{
	private $_link;


	public function __construct(array $config)
	{
		$dsn = "mysql:host=".$config['HOST'].";dbname=".$config['BASE'].";charset=utf8";
		$user = $config['USER'];
		$pass = $config['PASS'];

		$this->_link = new PDO($dsn, $user, $pass);
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