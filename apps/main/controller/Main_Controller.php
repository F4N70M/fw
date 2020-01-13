<?php
/**
 * Created by PhpStorm.
 * User: KONARD
 * Date: 03.11.2019
 * Time: 6:21
 */

namespace Apps\Main\Controller;


use Apps\Main\Model\Main_Model;
use Fw\Core;

class Main_Controller
{
	private $Fw;

	private $model;
	private $view;

	private $data = [];
	private $action;
	private $template = 'default';


	/**
	 * Main_Controller constructor.
	 * @param Core $Fw
	 */
	public function __construct(Core $Fw)
	{
		$this->Fw = $Fw;
		$this->model = new Main_Model($this->Fw);

		// Обработка запросов
		$this->request();

//		debug('Main_Controller: __construct');
	}


	/**
	 * @param string $uri
	 */
	public function direct(string $uri)
	{
//		debug('uri:',$uri);
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


		debug('Accounts:', $this->Fw->Account->getList());

		?>
		<?php




		debug('УРА! ЭТО СТРАНИЦА!');
	}
}