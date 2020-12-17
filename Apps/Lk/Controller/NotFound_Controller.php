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
use Fw\Components\Classes\Controller;
use Fw\Core;

class NotFound_Controller extends Controller
{
    protected $Fw;

	protected $model;
    protected $view;

    protected $theme = 'public/lk';  //  Default value
    protected $template = 'error/404';    //  Default value


    public function __construct(Core $Fw, $config = [])
    {
        http_response_code(404);
        parent::__construct($Fw, $config, new Lk_Model($Fw), new Lk_View($Fw), new Lk_Request($Fw));
    }
}