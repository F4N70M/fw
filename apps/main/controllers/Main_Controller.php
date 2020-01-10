<?php
/**
 * Created by PhpStorm.
 * User: KONARD
 * Date: 03.11.2019
 * Time: 6:21
 */

namespace Apps\Main\Controllers;


class Main_Controller
{
	private $Fw;


	/**
	 * Main_Controller constructor.
	 */
	public function __construct(\Fw\Core $Fw)
	{
		$this->Fw = $Fw;
//		debug('Main_Controller: __construct');
	}


	/**
	 * @param string $uri
	 */
	public function direct(string $uri)
	{
//		debug('Main_Controller: direct');
//		debug('uri:',$uri);
	}


	public function render()
	{
		$cookie1 = $this->Fw->Cookie->get();
		$this->Fw->Cookie->set('lol','kek');
		$cookie2 = $this->Fw->Cookie->get();
		$this->Fw->Cookie->unset('lol');
		$cookie3 = $this->Fw->Cookie->get();

		debug($cookie1,$cookie2,$cookie3);

		debug('УРА! ЭТО СТРАНИЦА!');
	}
}