<?php
/**
 * Created by PhpStorm.
 * Project: KONARD
 * Date: 03.11.2019
 * Time: 6:21
 */

namespace Apps\Lk\Controller;


use Apps\Lk\Model\Main_Model;
use Apps\Lk\View\Main_View;
use Exception;
use Fw\Core;

class Main_Controller
{
	private $Fw;

	private $model;
	private $view;

	private $data = [];
	private $action;
	private $template = 'default';

	private $tpl;


	/**
	 * Main_Controller constructor.
	 * @param Core $Fw
	 */
	public function __construct(Core $Fw)
	{
		$this->Fw = $Fw;
		$this->model = new Main_Model($this->Fw);
		$this->view = new Main_View($this->Fw, $this->model);

		// Обработка запросов
		$this->request();

	}


	/**
	 * @throws Exception
	 */
	public function indexAction()
	{
		$this->tpl = 'page/index';
		$this->data = [
			'title' =>  'Личный кабинет'
		];

	}


	/**
	 * @throws Exception
	 */
	public function projectsAction()
	{
		$this->tpl = 'page/projects';
	}


	/**
	 * @param $projectID
	 */
	public function projectAction($projectID)
	{
		$this->tpl = 'page/project';
		$this->data = [
			'id' =>  $projectID
		];
	}


	/**
	 * @throws Exception
	 */
	public function projectCreateAction()
	{
		$this->tpl = 'page/projectCreate';
	}


	/**
	 * @throws Exception
	 */
	public function treatsAction()
	{
		$this->tpl = 'page/treats';
	}


	/**
	 * @throws Exception
	 */
	public function treatAction()
	{
		$this->tpl = 'page/treat';
	}


	/**
	 * @throws Exception
	 */
	public function servicesAction()
	{
		$this->tpl = 'page/services';
	}


	/**
	 * @throws Exception
	 */
	public function serviceAction()
	{
		$this->tpl = 'page/service';
	}


	/**
	 * @throws Exception
	 */
	public function accessAction()
	{
		$this->tpl = 'auth/login';
	}


	/**
	 *
	 */
	public function request()
    {
        if (isset($_POST['request']))
        {
            $this->model->request($_POST['request']);
        }
	}


	/**
	 *
	 */
    public function render()
	{
		if (!$this->model->getAccess())
		{
			$this->accessAction();
		}

		$this->view->render($this->tpl,$this->data);
	}
}