<?php


namespace Apps\Crm\Request;


use Exception;
use Fw\Components\Classes\Request;

class Crm_Request extends Request
{


    /**
     * @return bool
     */
    protected function billNewRequest()
    {
//        debug($_POST);
        if (
            $this->checkPostKeysNoEmpty(['from', 'to'])
        )
        {
            $lastNumber = ($resultRequest = $this->Fw->Db
                ->select()
                ->from('objects')
                ->where([
                    'type'  => 'bill',
                    'date'  =>  ['like', $_POST['date'].'%']
                ])
                ->orderBy(['title'=>false])
                ->limit(1)
                ->result()) ? $resultRequest[0]['title'] : 0;
            $numberBase = date('ymd', strtotime($_POST['date']));
            $numberCounter = (int) str_replace($numberBase, '', $lastNumber);
            $numberNew = $numberBase . str_pad(0, 2, $numberCounter+1);
            $fromUserId = $this->Fw->Db
                ->select()
                ->from('relations')
                ->where([
                    'parent_table'  => 'users',
                    'child_table'   => 'objects',
                    'child_id'      => $_POST['from']
                ])
                ->result()[0]['parent_id'];
            $toUserId = $this->Fw->Db
                ->select()
                ->from('relations')
                ->where([
                    'parent_table'  => 'users',
                    'child_table'   => 'objects',
                    'child_id'      => $_POST['to']
                ])
                ->result()[0]['parent_id'];

            $requisiteFrom = $this->Fw->Db
                ->select()
                ->from('objects')
                ->where([
                    'type'  =>  'requisite',
                    'id'    => $_POST['from']
                ])
                ->result()[0];
            $requisiteTo = $this->Fw->Db
                ->select()
                ->from('objects')
                ->where([
                    'type'  =>  'requisite',
                    'id'    => $_POST['to']
                ])
                ->result()[0];
            $services = [];
            foreach ($_POST['service'] as $col => $keys) {
                foreach ($keys as $key => $val) {
                    $services[$key][$col] = !empty($val) ? $val : 0;
                }
            }
            foreach ($services as $key => $service)
            {
                if (empty($service['title'])) unset($services[$key]);
            }

            if (count($services) == 0)
                return false;

            $data = [
                'type'      => 'bill',
                'name'      => 'bill-'.$numberNew,
                'title'     => $numberNew,
                'date'      => $_POST['date'],
                'content'   => json([
                    'id'        =>  $numberNew,
                    'date'      =>  $_POST['date'],
                    'services'  =>  $services,
                    'from'      =>  ['id'=>$requisiteFrom['id']]+json($requisiteFrom['content'], false),
                    'to'        =>  ['id'=>$requisiteTo['id']]+json($requisiteTo['content'], false)
                ], true)
            ];
            $id = $this->Fw->Db->insert()->into('objects')->values($data)->result();
            return $id;

        }
        return false;
    }


    /**
     * @return bool
     */
    protected function debtNewRequest()
    {
        if (
            isset($_POST['borrower']) && !empty($_POST['borrower']) &&
            isset($_POST['lender']) && !empty($_POST['lender']) &&
            isset($_POST['content']) && !empty($_POST['content']) &&
            isset($_POST['value']) && !empty($_POST['value'])
        )
        {
            $data = [
                'type'=>'debt',
                'name'=>  $this->getUniqueName('objects', $this->titleToName($_POST['title'])),
                'borrower'=>$_POST['borrower'],
                'lender'=>$_POST['lender'],
                'content'=>$_POST['content'],
                'value'=>$_POST['value']
            ];

//            $id = $this->Fw->ObjectManager->new($data);
            $id = $this->Fw->Db->insert()->into('objects')->values($data)->result();

            return $id;
        }
        return false;
    }





    /**
     * @return bool
     */
    protected function changePasswordRequest()
    {
        if (
            isset($_POST['id']) && !empty($_POST['id']) &&
            isset($_POST['password']) && !empty($_POST['password'])
        )
        {
            $password = $_POST['password'];
            $rehash = $this->Fw->Account->hashPassword($password);
            $id = $_POST['id'];
            $data = ['password' => $rehash];
            $result = $this->Fw->Db->update('users')->set($data)->where(['id'=>$id])->result();
            return $result;
        }
        return false;
    }


    /**
     * @return bool
     */
    public function requisiteNewRequest()
    {
        debug($_POST);
        if (
            isset($_POST['user']) && !empty($_POST['user'])
        )
        {
            $requisites = json($_POST['requisites'], true);
            $id = $_POST['user'];
            $requisiteId = $this->Fw->Db
                ->insert()
                ->into('objects')
                ->values([
                    'type'      =>  'requisite',
                    'name'      =>  $this->getUniqueName('objects', 'requisite'),
                    'content'   =>  $requisites
                ])
                ->result();
            $result = $this->Fw->Db
                ->insert()
                ->into('relations')
                ->values([
                    'parent_table'  =>  'users',
                    'parent_id'     =>  $id,
                    'child_table'   =>  'objects',
                    'child_id'      =>  $requisiteId
                ])
                ->result();
            debug($requisiteId);
            return (bool) $result;
        }
        return false;
    }


    /**
     * @return bool
     */
    public function requisiteDelRequest()
    {
//        debug($_POST);
        if (
            isset($_POST['id']) && !empty($_POST['id'])
        )
        {
            $id = $_POST['id'];
            $result = $this->Fw->Db
                ->delete()
                ->from('objects')
                ->where([
                    'type'  =>  'requisite',
                    'id'    =>  $id
                ])
                ->result();
            return (bool) $result;
        }
        return false;
    }


    /**
     * @return bool
     */
    public function requisiteEditRequest()
    {
        if (
            isset($_POST['id']) && !empty($_POST['id'])
        )
        {
            $requisites = json($_POST['requisites'], true);
            $data = ['content' => $requisites];
//            $data['lol']='kek';
            $id = $_POST['id'];
            $result = $this->Fw->Db->update('objects')->set($data)->where(['id'=>$id])->result();

            return ($result !== false);
        }
        return false;
    }





    /**
     * @return bool
     */
    protected function debtChangeStateRequest()
    {
        if (
            isset($_POST['id']) && !empty($_POST['id']) &&
            isset($_POST['state']) && !empty($_POST['state'])
        )
        {
            $id = $_POST['id'];
            $state = $_POST['state'];
//            $debt = $this->Fw->Debt($id);
//            $result = $debt->changeState($state);
            $data = ['state' => $state];
            $result = $this->Fw->Db->update('objects')->set($data)->where(['id'=>$id, 'type'=>'debt'])->result();
            return $result;
        }
        return false;
    }


    /**
     * @return bool
     */
    protected function thailandNewRequest()
    {
        if (
            isset($_POST['user']) && !empty($_POST['user']) &&
            isset($_POST['date']) && !empty($_POST['date']) &&
            isset($_POST['value']) && !empty($_POST['value'])
        )
        {
            $data = [
                'type'=>'thailand',
                'user'=>$_POST['user'],
                'date'=>$_POST['date'],
                'value'=>$_POST['value']
            ];

//            $id = $this->Fw->ValueManager->new($data);
            $id = $this->Fw->Db->insert()->into('vars')->values($data)->result();

            return $id;
        }
        return false;
    }


    /**
     * @return bool
     */
    protected function tasksChangeExecutorRequest()
    {
        if (
            isset($_POST['executor']) && !empty($_POST['executor'])
        )
        {
            $value = $_POST['executor'];
            $result = $this->Fw->Cookie->set('executor', $value);
            return $result;
        }
        return false;
    }


    /**
     * @return bool
     */
    protected function tasksChangeStateRequest()
    {
        if (
            isset($_POST['state']) && !empty($_POST['state'])
        )
        {
            $value = $_POST['state'];
            $result = $this->Fw->Cookie->set('state', $value);
            return $result;
        }
        return false;
    }


    /**
     * @return bool
     */
    protected function taskNewRequest()
    {
        if (
//            isset($_POST['project']) && !empty($_POST['project']) &&
            isset($_POST['title']) && !empty($_POST['title']) &&
            isset($_POST['content']) && !empty($_POST['content']) //&&
//			isset($_POST['executor']) && !empty($_POST['executor'])
        )
        {
            $data = $this->Fw->Db->intersectTableData('objects', $_POST);

//            debug($data['executor']);

            $name = $this->titleToName($_POST['title']);
            $data['name'] = $this->getUniqueName('objects', $name);


            $data['type'] = 'task';
            $data['user'] = $this->Fw->Account->getCurrentId();
            $data['state'] = 'new';
//            $id = $this->Fw->TaskManager->new($_POST);
            $id = $this->Fw->Db->insert()->into('objects')->values($data)->result();
//            debug($id,$data);
            if ($id)
            {
                return $id;
            }
        }
        return false;
    }


    /**
     * @return bool
     */
    protected function taskChangeStateRequest()
    {
       // debug($_POST);
        if (
            isset($_POST['id']) && !empty($_POST['id']) &&
            isset($_POST['state']) && !empty($_POST['state'])
        )
        {
            $data = $this->Fw->Db->intersectTableData('objects', $_POST);
           // debug($data);

//            unset($data['request']);
            $id = $data['id'];
            $state = $data['state'];
//            $task = $this->Fw->Task($id);
//			$result = $task->changeState($state);

            if (isset($_POST['spent_hours']) && isset($_POST['spent_minutes']))
            {
                $data['spent_time'] = $_POST['spent_hours'] * 3600 + $_POST['spent_minutes'] * 60;
                unset($data['spent_hours']);
                unset($data['spent_minutes']);
            }

//            $result = $task->edit($data);
//            $result = $this->Fw->Db->select()->from('objects')->where(['id'=>$id, 'type'=>'task'])->result();



            $result = $this->Fw->Db->update('objects')->set($data)->where(['id'=>$id, 'type'=>'task'])->result();
            return $result;
        }
        return false;
    }


    /**
     * @return bool
     */
    protected function taskChangeStatusRequest()
    {
        if (
            isset($_POST['id']) && !empty($_POST['id']) &&
            isset($_POST['status']) && !empty($_POST['status'])
        )
        {
            $id = $_POST['id'];
            $status = $_POST['status'];
//            $task = $this->Fw->Task($id);
//            $result = $task->changeStatus($status);
            $result = $this->Fw->Db->update('objects')->set(['status'=>$status])->where(['type'=>'task','id'=>$id])->result();
            return $result;
        }
        return false;
    }


    /**
     * @return bool
     */
    protected function taskAddFilesRequest()
    {
        if (
            isset($_POST['task']) && !empty($_POST['task']) &&
            isset($_POST['user']) && !empty($_POST['user'])
        )
        {
            $uploadFiles = $this->Fw->Uploader->upload();
            foreach ($uploadFiles as $fileId)
            {
//                $this->Fw->Relations->set('objects',$_POST['task'],'files',$fileId);
                $data = [
                    'parent_table'  => 'objects',
                    'parent_id'     => $_POST['task'],
                    'child_table'   => 'files',
                    'child_id'      => $fileId
                ];
                $result = $this->Fw->Db->insert()->into('relations')->values($data)->result();
            }

            return true;
        }
        return false;
    }


    /**
     * @return bool
     */
    protected function taskAddMessageRequest()
    {
        // debug($_POST);
        if (
            isset($_POST['task']) && !empty($_POST['task']) &&
            isset($_POST['user']) && !empty($_POST['user']) &&
            isset($_POST['content']) && (!empty($_POST['content'] || $_POST['content'] === '0'))
        )
        {
            $data = [
                'type'      => 'message',
                'name'  =>  $this->getUniqueName('objects', 'message'),
                'content'   => $_POST['content'],
                'user'      => $_POST['user'],
                'task'      => $_POST['task']
            ];
//            $messageId = $this->Fw->MessageManager->new($data);
            $messageId = $this->Fw->Db->insert()->into('objects')->values($data)->result();
            // debug($messageId);
            $uploadFiles = $this->Fw->Uploader->upload();
            foreach ($uploadFiles as $fileId)
            {
//                $this->Fw->Relations->set('objects',$messageId,'files',$fileId);
                $data = [
                    'parent_table'  => 'objects',
                    'parent_id'     => $messageId,
                    'child_table'   => 'files',
                    'child_id'      => $fileId
                ];
                $result = $this->Fw->Db->insert()->into('relations')->values($data)->result();
            }
//            $users = $this->Fw->UserManager->get(['id'=>['<>',$_POST['user']],'type'=>['<>','client']]);
//
//            foreach ($users as $user)
//            {
//                $this->Fw->Notifications->new('task-new-message', $_POST['task'], $user['id']);
//            }

            return $messageId;
        }
        return false;
    }


    /**
     * @return bool
     */
    protected function signUpRequest()
    {
        if (
            isset($_POST['login']) &&
            !empty($_POST['login']) &&
            isset($_POST['password']) &&
            !empty($_POST['password']))
        {
            return $this->Fw->Account->signup($_POST['login'], $_POST['password'],$_POST);
        }
        return false;
    }

    public function getNameByUri(string $uri)
    {
        $arr = explode('/', $uri);

        if (empty($arr))
            throw new Exception("Empty URI");

        $name = end($arr);
        return $name;
    }

    public function getIndex()
    {

    }

//    public function getInfo($name)
//    {
//        $uniq = ($name == 'index' ? $this->Fw->Pages->getIndexID() : $name);
//        $result = $this->Fw->Pages->get($uniq);
//
//        if (!$result)
//            return $this->getInfo404();
//        return $result;
//    }

    protected function getInfo404()
    {
        return [
            'type' => 'error',
            'name' => '404',
            'title' => '404',
            'content' => 'Страница не найдена'
        ];
    }


    /**
     * @throws Exception
     */
    public function getAccess()
    {
        $user = $this->Fw->Account->getCurrent();
        //TODO: Написать нормальную проверку доступа
        return (bool) $user;
    }


    /**
     * @return bool
     * @throws Exception
     */
    protected function clientCreateRequest()
    {
        // debug($_POST);
        if (
            isset($_POST['login']) &&
            !empty($_POST['login']) &&
            isset($_POST['name']) &&
            !empty($_POST['name']) &&
            isset($_POST['password']) &&
            !empty($_POST['password']) &&
            $this->getAccess()
        )
        {
            $data = [
                'type' =>  'client',
                'login' =>  $_POST['login'],
                'name'  =>  $_POST['name'],
                'password'  =>  $_POST['password']
            ];
//            return $this->Fw->UserManager->new($data);
            return $this->Fw->Db->insert()->into('users')->values($data)->result();
        }
        return false;
    }


    /**
     * @return bool
     * @throws Exception
     */
    protected function projectCreateRequest()
    {
        if (
            isset($_POST['title']) &&
            !empty($_POST['title']) &&
            isset($_POST['user']) &&
            !empty($_POST['user']) &&
            $this->getAccess()
        )
        {
            $data = [
                'title' =>  $_POST['title'],
                'name'  =>  $this->getUniqueName('objects', $this->titleToName($_POST['title'])),
                'link'  =>  $_POST['link'],
                'description'   =>  $_POST['description'],
                'user'  =>  $_POST['user'],
                'type'  =>  'project'
            ];
//            return $this->Fw->ProjectManager->new($data);
            $result = $this->Fw->Db->insert()->into('objects')->values($data)->result();
//            debug($data,$result);
            return $result;
        }
        return false;
    }


    /**
     * @return bool
     * @throws Exception
     */
    protected function projectDeleteRequest()
    {
        if (
            isset($_POST['id']) && !empty($_POST['id']) &&
            $this->getAccess()
        )
        {
//            $project = $this->Fw->Project($_POST['id']);
//            return $project->delete();
            return $this->Fw->Db->delete()->from('objects')->where(['type'=>'project','id'=>$_POST['id']])->result();
//			return $this->Fw->Projects->deleteProject($_POST['id']);
        }
        return false;
    }


    /**
     * @return bool
     * @throws Exception
     */
    protected function ticketCreateRequest()
    {
        if (
            isset($_POST['project']) && !empty($_POST['project']) &&
            isset($_POST['user']) && !empty($_POST['user']) &&
            isset($_POST['title']) && !empty($_POST['title']) &&
            isset($_POST['content']) && !empty($_POST['content']) &&
            $this->getAccess()
        )
        {
//            $TicketManager = $this->Fw->TicketManager();
            $data = [
                'type'  => 'ticket',
                'title'=>$_POST['title'],
                'name'  =>  $this->getUniqueName('objects', $this->titleToName($_POST['title'])),
                'content'=>$_POST['content'],
                'project'=>$_POST['project'],
                'user'=>$_POST['user']
            ];
//            return $TicketManager->new($data);
            return $this->Fw->Db->insert()->into('objects')->values($data)->result();
        }
        return false;
    }


    /**
     * @return bool
     * @throws Exception
     */
    protected function ticketDeleteRequest()
    {
        if (
            isset($_POST['id']) && !empty($_POST['id']) &&
            $this->getAccess()
        )
        {
//            $ticket = $this->Fw->Ticket($_POST['id']);
//            return $ticket->delete();
            return $this->Fw->Db->delete()->from('objects')->where(['type'=>'ticket','id'=>$_POST['id']])->result();
//			return $this->Fw->Projects->deleteProject($_POST['id']);
        }
        return false;
    }


    /**
     * @return bool
     * @throws Exception
     */
    protected function ticketMessageCreateRequest()
    {
        if (
            isset($_POST['ticket']) && !empty($_POST['ticket']) &&
            isset($_POST['user']) && !empty($_POST['user']) &&
            isset($_POST['content']) && !empty($_POST['content']) &&
            $this->getAccess()
        )
        {
            $data = [
                'type'      =>  'message',
                'name'  =>  $this->getUniqueName('objects', 'message'),
                'user'      =>  $_POST['user'],
                'content'   =>  $_POST['content'],
                'ticket'    =>  $_POST['ticket']
            ];
//            $MessageManager = $this->Fw->MessageManager();
//            return $MessageManager->new($data);
            return $this->Fw->Db->insert()->into('objects')->values($data)->result();
        }
        return false;
    }


    /**
     * @return bool
     * @throws Exception
     */
    protected function ticketMessageDeleteRequest()
    {
        if (
            isset($_POST['message']) && !empty($_POST['message']) &&
            $this->getAccess()
        )
        {
//            return $this->Fw->Message($_POST['message'])->delete();
            return $this->Fw->Db->delete()->from('objects')->where(['type'=>'message','id'=>$_POST['message']])->result();
        }
        return false;
    }


    /**
     * @return bool
     * @throws Exception
     */
    public function accessCreateRequest()
    {
        if (
            isset($_POST['class']) && !empty($_POST['class']) &&
            isset($_POST['projects']) && !empty($_POST['projects']) &&
            $this->getAccess()
        )
        {
            $data = $this->Fw->Db->intersectTableData('objects', $_POST);
            $data['type'] = 'access';
            $data['name'] = $this->getUniqueName('objects', 'access');

            $accessId = $this->Fw->Db->insert()->into('objects')->values($data)->result();
//            debug($data, $accessId);
            if ($accessId)
            {
                $relationData = [
                    'parent_table'  => 'objects',
                    'child_table'   => 'objects',
                    'child_id'      => $accessId
                ];
                foreach ($_POST['projects'] as $projectId)
                {
                    $relationData['parent_id'] = $projectId;
                    $this->Fw->Db->insert()->into('relations')->values($relationData)->result();
                }
            }
            return $accessId;
        }
        return false;
    }


    /**
     * @return bool
     * @throws Exception
     */
    public function serviceCreateRequest()
    {
//        debug('serviceCreate');
        if (
            isset($_POST['class']) && !empty($_POST['class']) &&
//            isset($_POST['projects']) && !empty($_POST['projects']) &&
            isset($_POST['title']) && !empty($_POST['title']) &&
            $this->getAccess()
        )
        {
//            debug($_POST);
            $data = $this->Fw->Db->intersectTableData('objects', $_POST);
            $data['type'] = 'service';
            $data['name'] = $this->getUniqueName('objects', 'service');

            $serviceID = $this->Fw->Db->insert()->into('objects')->values($data)->result();
//            debug($data, $accessId);
            // Привязать услугу к проектам
            if ($serviceID)
            {
//                debug($serviceID);
                /*$relationData = [
                    'parent_table'  => 'objects',
                    'child_table'   => 'objects',
                    'child_id'      => $serviceID
                ];
                foreach ($_POST['projects'] as $projectId)
                {
                    $relationData['parent_id'] = $projectId;
                    $this->Fw->Db->insert()->into('relations')->values($relationData)->result();
                }*/


                // Привязать доступ к услуге
                /*
                if (isset($_POST['access']) && !empty($_POST['access']))
                {
                    $relationData = [
                        'parent_table'  => 'objects',
                        'parent_id'  => $serviceID,
                        'child_table'   => 'objects',
                        'child_id'      => $_POST['access']
                    ];
                    $this->Fw->Db->insert()->into('relations')->values($relationData)->result();
                }
                */

                if (
                    isset($_POST['payment']['value']) && !empty($_POST['payment']['value']) &&
                    isset($_POST['payment']['date_start']) && !empty($_POST['payment']['date_start']) &&
                    isset($_POST['payment']['date_end']) && !empty($_POST['payment']['date_end'])
                )
                {
                    $dataPayment = $this->Fw->Db->intersectTableData('objects', $_POST['payment']);
                    $dataPayment['type'] = 'payment';
                    $dataPayment['name'] = $this->getUniqueName('objects', 'payment');
                    $dataPayment['status'] = 1;
//                    debug($dataPayment);
                    $paymentID = $this->Fw->Db->insert()->into('objects')->values($dataPayment)->result();

                    if ($paymentID)
                    {
                        $relationData = [
                            'parent_table'  => 'objects',
                            'parent_id'  => $serviceID,
                            'child_table'   => 'objects',
                            'child_id'      => $paymentID
                        ];
                        $this->Fw->Db->insert()->into('relations')->values($relationData)->result();
                    }
                }
            }
            return $serviceID;
        }
        return false;
    }


}