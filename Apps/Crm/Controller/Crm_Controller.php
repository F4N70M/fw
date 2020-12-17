<?php
/**
 * Created by PhpStorm.
 * Treat: KONARD
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

class Crm_Controller extends Controller
{
    protected $Fw;

	protected $model;
	protected $view;

	protected $info = [];
	protected $data = [];

	protected $theme = 'public/crm';  //  Default value
	protected $template = 'error/404';    //  Default value

    protected $accessRank = 5;


    public function __construct(Core $Fw, $config = [])
    {
        parent::__construct($Fw, $config, new Model($Fw), new View($Fw), new Request($Fw));
    }


    /**
     * @throws Exception
     */
    public function accessAction()
    {
        http_response_code(403);
//        $this->template = 'auth/login';
        $this->template = 'error/403';
    }


    /**
     * @throws Exception
     */
    public function requisitesAction($id)
    {
        if (!$this->model->getAccess($this->accessRank))
            return $this->accessAction();

        $this->template = 'page/requisites-list';
        $this->data = [
            'title' =>  'Реквизиты',
            'id'    =>  $id
        ];
    }


    /**
     * @throws Exception
     */
    public function requisiteNewAction($id)
    {
        if (!$this->model->getAccess($this->accessRank))
            return $this->accessAction();

        $this->template = 'page/requisites-new';
        $this->data = [
            'title' =>  'Реквизиты',
            'id'    =>  $id
        ];
    }


    /**
     * @throws Exception
     */
    public function requisiteEditAction($id)
    {
        if (!$this->model->getAccess($this->accessRank))
            return $this->accessAction();

        $this->template = 'page/requisite-edit';
        $this->data = [
            'title' =>  'Реквизиты',
            'id'    =>  $id
        ];
    }


    /**
     * @throws Exception
     */
    public function changePasswordAction($id)
    {
        if (!$this->model->getAccess($this->accessRank))
            return $this->accessAction();

        $this->template = 'page/new-password';
        $this->data = [
            'title' =>  'Новый пароль',
            'id'    =>  $id
        ];
    }


    /**
     * @throws Exception
     */
    public function indexAction()
    {
        if (!$this->model->getAccess($this->accessRank))
            return $this->accessAction();

        $this->template = 'page/tasks';
        $this->data = [
            'title' =>  'Задачи',
        ];
    }


    /**
     * @throws Exception
     */
    public function clientsAction()
    {
        if (!$this->model->getAccess($this->accessRank))
            return $this->accessAction();

        $this->template = 'page/client-list';
        $this->data = [
            'title' =>  'Клиенты'
        ];
    }


    /**
     * @param int $clientId
     * @throws Exception
     */
    public function clientAction(int $clientId)
    {
        if (!$this->model->getAccess($this->accessRank))
            return $this->accessAction();

        $this->template = 'page/client';
        $this->data = [
            'title' =>  'Проекты',
            'id'    =>  $clientId
        ];
    }


    /**
     * @throws Exception
     */
    public function clientNewAction()
    {
        if (!$this->model->getAccess($this->accessRank))
            return $this->accessAction();

        $this->template = 'page/client-new';
        $this->data = [];
    }


	/**
	 * @throws Exception
	 */
	public function taskNewAction()
	{
		$this->template = 'page/task-new';
		$this->data = [
			'title' =>  'Новая задача'
		];
	}


    /**
     * @param int $id
     * @throws Exception
     */
	public function taskAction(int $id)
	{
        if (!$this->model->getAccess($this->accessRank))
            return $this->accessAction();

		$this->template = 'page/task';
		$this->data = [
			'title' =>  'Задача #'.$id,
			'id'    =>  $id
		];

		/*$this->Fw->Notifications->status(
			[
				'user' => $this->Fw->Account->getCurrentId(),
				'type' => [
					'in',
					['task-new','task-new-message']
				]
			]
		);*/
	}


	/**
	 * @throws Exception
	 */
	public function taskChangeStateAction(int $id)
	{
        if (!$this->model->getAccess($this->accessRank))
            return $this->accessAction();

        $this->template = 'page/task-change-state';
		$this->data = [
			'id' =>  $id
		];
	}


	/**
	 * @throws Exception
	 */
	public function taskAddFilesAction(int $id)
	{
        if (!$this->model->getAccess($this->accessRank))
            return $this->accessAction();

        $this->template = 'page/task-add-files';
		$this->data = [
			'id' =>  $id
		];
	}


	/**
	 * @throws Exception
	 */
	public function thailandAction()
	{
        if (!$this->model->getAccess($this->accessRank))
            return $this->accessAction();

        $this->template = 'page/thailand';
		$this->data = [
			'title' =>  'Тайланд'
		];
	}


	/**
	 * @throws Exception
	 */
	public function thailandNewAction()
	{
        if (!$this->model->getAccess($this->accessRank))
            return $this->accessAction();

        $this->template = 'page/thailand-new';
		$this->data = [
			'title' =>  'Тайланд. Новый платеж'
		];
	}


    /**
     * @throws Exception
     */
    public function billsAction()
    {
        if (!$this->model->getAccess($this->accessRank))
            return $this->accessAction();

        $this->template = 'page/bill-list';
        $this->data = [
            'title' =>  'Долги'
        ];
    }


    /**
     * @throws Exception
     */
    public function billNewAction()
    {
        if (!$this->model->getAccess($this->accessRank))
            return $this->accessAction();

        $this->template = 'page/bill-new';
        $this->data = [];
    }


    /**
     * @param $debtId
     * @throws Exception
     */
    public function billAction($debtId)
    {
        if (!$this->model->getAccess($this->accessRank))
            return $this->accessAction();

        $this->template = 'page/bill';
        $this->data = [
            'title' =>  'Счет',
            'id'    =>  $debtId
        ];
    }


    /**
     * @param $debtId
     * @throws Exception
     */
    public function billPrintAction($debtId)
    {
        if (!$this->model->getAccess($this->accessRank))
            return $this->accessAction();

        $this->template = 'page/bill-print';
        $this->data = [
            'title' =>  'Счет',
            'id'    =>  $debtId
        ];
    }


    /**
     * @throws Exception
     */
    public function debtsAction()
    {
        if (!$this->model->getAccess($this->accessRank))
            return $this->accessAction();

        $this->template = 'page/debt-list';
        $this->data = [
            'title' =>  'Долги'
        ];
    }


    /**
     * @throws Exception
     */
    public function debtNewAction()
    {
        if (!$this->model->getAccess($this->accessRank))
            return $this->accessAction();

        $this->template = 'page/debt-new';
        $this->data = [];
    }


    /**
     * @param $debtId
     * @throws Exception
     */
    public function debtAction($debtId)
    {
        if (!$this->model->getAccess($this->accessRank))
            return $this->accessAction();

        $this->template = 'page/debt';
        $this->data = [
            'title' =>  'Долг',
            'id'    =>  $debtId
        ];
    }


    /**
     * @param int $clientId
     * @throws Exception
     */
	public function projectsAction(int $clientId)
	{
        if (!$this->model->getAccess($this->accessRank))
            return $this->accessAction();

        $this->template = 'page/project-list';
		$this->data = [
			'title' =>  'Проекты',
			'id'    =>  $clientId
		];
	}


    /**
     * @param $projectID
     * @throws Exception
     */
	public function projectAction($projectID)
	{
        if (!$this->model->getAccess($this->accessRank))
            return $this->accessAction();

        $this->template = 'page/project';
		$this->data = [
			'id' =>  $projectID
		];
	}


    /**
     * @param $userId
     * @throws Exception
     */
	public function projectNewAction($userId)
	{
        if (!$this->model->getAccess($this->accessRank))
            return $this->accessAction();

        $this->template = 'page/project-new';
		$this->data = [
			'id' =>  $userId
		];
	}


	/**
	 * @throws Exception
	 */
	public function ticketsAction()
	{
        if (!$this->model->getAccess($this->accessRank))
            return $this->accessAction();

        $this->template = 'page/tickets';
	}


    /**
     * @param int $ticketId
     * @throws Exception
     */
	public function ticketAction(int $ticketId)
	{
        if (!$this->model->getAccess($this->accessRank))
            return $this->accessAction();

        $this->template = 'page/ticket';
		$this->data = [
			'id' =>  $ticketId
		];
	}


    /**
     * @param int $projectID
     * @throws Exception
     */
	public function ticketNewAction(int $projectID)
	{
        if (!$this->model->getAccess($this->accessRank))
            return $this->accessAction();

        $this->template = 'page/ticket-new';
		$this->data = [
			'id' =>  $projectID
		];
	}


	/**
	 * @throws Exception
	 */
	public function servicesAction(int $id)
	{
        if (!$this->model->getAccess($this->accessRank))
            return $this->accessAction();

        $this->template = 'page/service-list';
		$this->data = [
			'id' =>  $id
		];
	}


	/**
	 * @throws Exception
	 */
	public function serviceAction(int $id)
	{
        if (!$this->model->getAccess($this->accessRank))
            return $this->accessAction();

        $this->template = 'page/service';
		$this->data = [
			'id' =>  $id
		];
	}


	/**
	 * @throws Exception
	 */
	public function serviceNewAction(int $id)
	{
        if (!$this->model->getAccess($this->accessRank))
            return $this->accessAction();

        $this->template = 'page/service-new';
		$this->data = [
			'id' =>  $id
		];
	}


    /**
     * @param int|null $id
     * @throws Exception
     */
	public function accessesAction(int $id = null)
	{
        if (!$this->model->getAccess($this->accessRank))
            return $this->accessAction();

        $this->template = 'page/client-accesses';
		$this->data = [
			'id' =>  $id
		];
	}


    /**
     * @param int|null $id
     * @throws Exception
     */
	public function accessNewAction(int $id = null)
	{
        if (!$this->model->getAccess($this->accessRank))
            return $this->accessAction();

        $this->template = 'page/access-new';
		$this->data = [
			'id' =>  $id
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