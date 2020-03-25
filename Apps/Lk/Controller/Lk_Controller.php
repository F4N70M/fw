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
use Exception;
use Fw\Core;

class Lk_Controller
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
		$this->model = new Lk_Model($this->Fw);
		$this->view = new Lk_View($this->Fw, $this->model);

		// Обработка запросов
		$this->request();

	}


	/**
	 * @throws Exception
	 */
	public function indexAction()
	{
		$user = $this->Fw->Account->getCurrent();
		if ($user && $user->get('type') == 'client')
		{
			$this->projectsAction($user->get('id'));
		}
		else
		{
			$this->clientsAction();
		}

	}


	/**
	 * @throws Exception
	 */
	public function clientsAction()
	{
		$this->tpl = 'page/client-list';
		$this->data = [
			'title' =>  'Клиенты'
		];
	}


	/**
	 * @throws Exception
	 */
	public function clientNewAction()
	{
		$this->tpl = 'page/client-new';
		$this->data = [];
	}


	/**
	 * @param int $clientId
	 */
	public function projectsAction(int $clientId)
	{
		$this->tpl = 'page/project-list';
		$this->data = [
			'title' =>  'Проекты',
			'id'    =>  $clientId
		];
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
	 * @param $userId
	 */
	public function projectNewAction($userId)
	{
		$this->tpl = 'page/project-new';
		$this->data = [
			'id' =>  $userId
		];
	}


	/**
	 * @throws Exception
	 */
	public function ticketsAction()
	{
		$this->tpl = 'page/tickets';
	}


	/**
	 * @param int $ticketId
	 */
	public function ticketAction(int $ticketId)
	{
		$this->tpl = 'page/ticket';
		$this->data = [
			'id' =>  $ticketId
		];
	}


	/**
	 * @param int $projectID
	 */
	public function ticketNewAction(int $projectID)
	{
		$this->tpl = 'page/ticket-new';
		$this->data = [
			'id' =>  $projectID
		];
	}


	/**
	 * @throws Exception
	 */
	public function servicesAction(int $id)
	{
		$this->tpl = 'page/service-list';
		$this->data = [
			'id' =>  $id
		];
	}


	/**
	 * @throws Exception
	 */
	public function serviceAction(int $id)
	{
		$this->tpl = 'page/service';
		$this->data = [
			'id' =>  $id
		];
	}


	/**
	 * @throws Exception
	 */
	public function serviceNewAction(int $id)
	{
		$this->tpl = 'page/service-new';
		$this->data = [
			'id' =>  $id
		];
	}


	/**
	 * @param int|null $id
	 */
	public function accessesAction(int $id = null)
	{
		$this->tpl = 'page/access-list';
		$this->data = [
			'id' =>  $id
		];
	}


	/**
	 * @throws Exception
	 */
	public function accessAction()
	{
		$this->tpl = 'auth/login';
	}


	/**
	 * @param int|null $id
	 */
	public function accessNewAction(int $id = null)
	{
		$this->tpl = 'page/access-new';
		$this->data = [
			'id' =>  $id
		];
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