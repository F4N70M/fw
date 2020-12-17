<?php

namespace Apps\Admin\Request;

use Fw\Core;

/**
 * User: F4N70M
 * Version: 0.1
 * Date: 08.07.2020
 */

class Admin_Request
{
	private $Fw;
	private $model;


	/**
	 * Shop_Request constructor.
	 * @param Core $Fw
	 * @param $model
	 */
	public function __construct(Core $Fw, $model)
	{
		$this->Fw = $Fw;
		$this->model = $model;
	}
}