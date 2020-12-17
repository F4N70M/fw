<?php
/**
 * Created by PhpStorm.
 * Treat: KONARD
 * Date: 03.11.2019
 * Time: 6:21
 */

namespace Apps\Lk\Controller;


use Apps\Lk\Model\Lk_Model;
use Apps\Lk\View\Lk_View;
use Apps\Lk\Request\Lk_Request;
use Exception;
use Fw\Components\Classes\Controller;
use Fw\Core;

class Lk_Controller extends Controller
{
    protected $Fw;

	protected $model;
	protected $view;

	protected $info = [];
	protected $data = [];

	protected $theme = 'public/lk';  //  Default value
	protected $template = 'error/404';    //  Default value

    protected $accessRank = 1;


    public function __construct(Core $Fw, $config = [])
    {
        parent::__construct($Fw, $config, new Lk_Model($Fw), new Lk_View($Fw), new Lk_Request($Fw));
    }


    /**
     * @throws Exception
     */
    public function accessAction()
    {
//        debug($this->model->getAccess($this->accessRank));
        $this->template = 'auth/login';
    }


	/**
	 * @throws Exception
	 */
	public function indexAction()
	{
	    if (!$this->model->getAccess($this->accessRank))
            return $this->accessAction();

        $this->template = 'page/project-list';
        $this->data = [
            'title' =>  'Задачи',
        ];
	}


    /**
     * @param string $name
     * @throws Exception
     */
    public function directAction(string $name)
    {
        if (!$this->model->getAccess($this->accessRank))
            return $this->accessAction();

        $this->template = 'page/'.$name;
    }


    /**
     * @param int $id
     */
    public function projectAction(int $id)
    {
        $this->template = 'page/project';
        $this->data = [
            'title' =>  'Проект #'.$id,
            'id'    =>  $id
        ];
    }


    /**
     * @param int $id
     */
    public function taskAction(int $id)
    {
        $this->template = 'page/task';
        $this->data = [
            'title' =>  'Задача #'.$id,
            'id'    =>  $id
        ];
    }


	/**
	 *
	 * @throws Exception
	 */
    public function render()
	{
	    parent::render();
	}
}