<?php
/**
 * Created by PhpStorm.
 * Treat: KONARD
 * Date: 03.11.2019
 * Time: 6:21
 */

namespace Apps\Main\Controller;


use Apps\Main\Model\Main_Model;
use Apps\Main\View\Main_View;
use Apps\Main\Request\Main_Request;
use Exception;
use Fw\Components\Classes\Controller;
use Fw\Core;

class Main_Controller extends Controller
{
    protected $Fw;

    protected $model;
    protected $view;

    protected $info = [];
    protected $data = [];

    protected $theme = 'public/main';  //  Default value
    protected $template = 'error/404';    //  Default value

    protected $access = false;


	/**
	 * Main_Controller constructor.
	 * @param Core $Fw
	 * @param array $config
	 */
	public function __construct(Core $Fw, array $config)
	{
        parent::__construct($Fw, $config, new Main_Model($Fw), new Main_View($Fw), new Main_Request($Fw));


	}


	/**
	 * @param string $uri
	 * @throws Exception
	 */
	public function direct(string $uri)
	{
	    $explode = explode('/',$uri);
		$namePage = array_pop($explode);
		$this->data = $this->model->getInfo($namePage);

		$this->template = ($namePage == 'index' ? 'page/index' : $this->data['type'].'/'.$this->data['name']);
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
		$this->view->render($this->theme, $this->template, $this->data, $this->info);
	}
}