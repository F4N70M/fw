<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 10.01.2020
 */


namespace Fw\Services\Autorization;

use Fw\Services\Cookie\Cookie;
use Fw\Services\Database\Db;

class Autorization
{
	private $db;
	private $cookie;


	/**
	 * Autorization constructor.
	 * @param Db $db
	 * @param Cookie $cookie
	 */
	public function __construct(Db $db, Cookie $cookie)
	{
		$this->db = $db;
		$this->cookie = $cookie;
	}


	/**
	 * @param int $userID
	 */
	public function in(int $userID)
	{
		$expires = time()+60*60*24*1;   //  1 день
		$this->cookie->set('Auth', $userID, $expires);
	}


	/**
	 *
	 */
	public function out()
	{
		$this->cookie->unset('Auth');
	}
}