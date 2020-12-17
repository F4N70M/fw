<?php
/**
 * Created by PhpStorm.
 * Treat: edkon
 * Date: 03.11.2019
 * Time: 6:21
 */

namespace Apps\Crm\Controller;


use Apps\Crm\Model\Crm_Model as Model;
use Apps\Crm\Request\Crm_Request as Request;
use Apps\Crm\View\Crm_View as View;
use Exception;
use Fw\Components\Classes\Controller;
use Fw\Core;

class Auth_Controller extends Controller
{
    protected $theme = 'public/crm';  //  Default value
    protected $template = 'error/404';    //  Default value


    public function __construct(Core $Fw, $config = [])
    {
        parent::__construct($Fw, $config, new Model($Fw), new View($Fw), new Request($Fw));
    }

	public function signup()
	{
		$this->template = 'auth/signup';
	}
	public function login()
	{
//	    debug($this->model->getAccess(),APP_PREFIX);
		if ($this->model->getAccess())
		{
			header('Location: '.APP_PREFIX.'/');
		}
		$this->template = 'auth/login';
	}
	public function logout()
	{
		$this->Fw->Auth->out();
//		debug($this->model->getAccess());
		header("Location: ".APP_PREFIX."/login");
	}
	public function recovery()
	{
		$this->template = 'auth/recovery';
	}


}