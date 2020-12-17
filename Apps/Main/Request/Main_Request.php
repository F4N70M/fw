<?php

namespace Apps\Main\Request;

use Fw\Components\Classes\Request;
use Fw\Core;

/**
 * User: F4N70M
 * Version: 0.1
 * Date: 08.07.2020
 */

class Main_Request extends Request
{
	protected $Fw;
	protected $model;


	/**
	 * Shop_Request constructor.
	 * @param Core $Fw
	 * @param $model
	 */
	public function __construct(Core $Fw)
	{
	    parent::__construct($Fw);
	}
}