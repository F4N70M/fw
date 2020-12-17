<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 14.01.2020
 */

namespace Apps\Main\View;


use Fw\Components\Classes\View;
use Fw\Core;
use Exception;

class Main_View extends View
{
	/**
	 * @var Core
	 */
    protected $Fw;
    protected $model;


	/**
	 * Lk_View constructor.
	 * @param Core $Fw
	 * @param $model
	 */
	public function __construct(Core $Fw)
	{
	    parent::__construct($Fw);
	}
}