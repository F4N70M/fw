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
use Fw\Components\Classes\Controller;
use Fw\Core;

class NotFound_Controller extends Controller
{
    protected $Fw;

	protected $model;
    protected $view;

    protected $theme = 'public/crm';  //  Default value
    protected $template = 'error/404';    //  Default value


    public function __construct(Core $Fw, $config = [])
    {
        http_response_code(404);
        parent::__construct($Fw, $config, new Model($Fw), new View($Fw), new Request($Fw));
    }
}