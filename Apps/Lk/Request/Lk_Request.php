<?php


namespace Apps\Lk\Request;


use Exception;
use Fw\Components\Classes\Request;

class Lk_Request extends Request
{

    public function getInfo($name)
    {
        $uniq = ($name == 'index' ? $this->Fw->Pages->getIndexID() : $name);
        $result = $this->Fw->Pages->get($uniq);

        if (!$result)
            return $this->getInfo404();
        return $result;
    }

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


    public function requisitesRequest()
    {
        $requisites = json($_POST['requisites'], true);
//        debug($requisites);
        $data = ['requisites' => $requisites];
        $id = $this->Fw->Account->getCurrentId();
        $result = $this->Fw->Db->update('users')->set($data)->where(['id'=>$id])->result();
        return (bool) $result;
    }


    /**
     * @return bool
     * @throws Exception
     */
    public function accessCreateRequest()
    {
        if (
            isset($_POST['access_type']) && !empty($_POST['access_type']) &&
            isset($_POST['projects']) && !empty($_POST['projects']) &&
            $this->getAccess()
        )
        {
            $data = $this->Fw->Db->intersectTableData('objects', $_POST);
            $data['type'] = 'access';
            $data['name'] = $this->getUniqueName('objects', 'access');

            $accessId = $this->Fw->Db->insert()->into('objects')->values($data)->result();
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
}