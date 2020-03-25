<?php
/**
 * Created by PhpStorm.
 * Treat: edkon
 * Date: 03.11.2019
 * Time: 6:21
 */

namespace Apps\Lk\Controller;


use Apps\Lk\Model\Lk_Model;
use Apps\LK\View\Lk_View;
use Fw\Core;

class NotFound_Controller
{
	private $Fw;

	private $model;
	private $view;

	public function __construct(Core $Fw)
	{
		$this->Fw = $Fw;
		$this->model = new Lk_Model($this->Fw);
		$this->view = new Lk_View($this->Fw, $this->model);
	}


	public function render()
	{
		$tpl = "error/404";
		$this->view->render($tpl);
	}
}