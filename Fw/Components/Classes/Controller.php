<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 27.07.2020
 */

namespace Fw\Components\Classes;

use Fw\Core;

class Controller
{
	protected $model;
	protected $view;
	protected $request;

	protected $Fw;

	protected $theme = 'public/default';
	protected $template;
	protected $info = [];
	protected $data = [];

	protected $access = true;


	public function __construct(Core $Fw, $config=[], Model $model=null, View $view=null, Request $request=null)
	{
		$this->Fw = $Fw;
        $this->info = $config['app'];
        $this->format = $config['route']['format'];

		$this->model = $model;
		$this->view = $view;
		$this->request = $request;

		$response = $this->request();
//		debug($response);
	}

	protected function request()
    {
//        debug(get_class($this->request));
        if (
            array_key_exists('request', $_POST) &&
            is_string($_POST['request']) &&
            !empty($_POST['request'])
        )
            return $this->request->execute($_POST['request']);

        return null;
    }

	public function render()
	{
//	    debug($this->theme);
	    switch ($this->format)
        {
            case "json":
                echo json($this->data, true);
                break;
            case "popup":
                $this->view->renderPopup($this->theme, $this->template, $this->data, $this->info);
                break;
            default:
                $this->view->render($this->theme, $this->template, $this->data, $this->info);
                break;
        }

	}
}