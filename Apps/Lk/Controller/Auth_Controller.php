<?php
/**
 * Created by PhpStorm.
 * Treat: edkon
 * Date: 03.11.2019
 * Time: 6:21
 */

namespace Apps\Lk\Controller;


use Apps\Lk\Model\Lk_Model;
use Apps\Lk\Request\Lk_Request;
use Apps\Lk\View\Lk_View;
use Exception;
use Fw\Components\Classes\Controller;
use Fw\Core;

class Auth_Controller extends Controller
{
    protected $theme = 'public/lk';  //  Default value
    protected $template = 'error/404';    //  Default value


    public function __construct(Core $Fw, $config = [])
    {
        parent::__construct($Fw, $config, new Lk_Model($Fw), new Lk_View($Fw), new Lk_Request($Fw));
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