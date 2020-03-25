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
use Exception;
use Fw\Core;

class LK_Controller
{
	private $Fw;

	private $model;
	private $view;

	private $data = [];
	private $action;
	private $template = 'default';

	private $tpl;


	/**
	 * Lk_Controller constructor.
	 * @param Core $Fw
	 */
	public function __construct(Core $Fw)
	{
		$this->Fw = $Fw;
		$this->model = new Main_Model($this->Fw);
		$this->view = new Main_View($this->Fw, $this->model);

		// Обработка запросов
		$this->request();

		debug('lk');
	}


	/**
	 * @param string $uri
	 * @throws Exception
	 */
	public function direct(string $uri)
	{
		debug($uri);
		// Получить информацию о странице из БД по uri
		$name = $this->model->getNameByUri($uri);

		$this->data = $this->model->getInfo($name);

		$this->tpl = ($name == 'index') ? 'page/index' : $this->data['type'].'/'.$this->data['name'];

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
		$this->view->render($this->tpl,$this->data);
	}
}