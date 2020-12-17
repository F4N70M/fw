<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 27.07.2020
 */

namespace Fw\Components\Classes;


use Fw\Core;

class Request
{
    protected $Fw;

    /**
     * Request constructor.
     * @param Core $Fw
     */
    public function __construct(Core $Fw)
    {
        $this->Fw = $Fw;
    }


    /**
     * @param string $action
     * @return bool
     */
    public function execute(string $action)
    {
        $method = $action.'Request';
//        debug($method);
        if (method_exists($this,$method))
        {
            $result = $this->$method();
            if (isset($_POST['redirect']) && $result)
            {
                $redirect = !empty($_POST['redirect']) ? $_POST['redirect'] : '/';
                header("Location: {$redirect}");
                exit();
            }
            else
            {
                debug($result, $_POST);
            }
            return $result;
        }
        return false;
    }





    /**
     * @return bool
     */
    protected function loginRequest()
    {
        if (isset($_POST['login']) && !empty($_POST['login']) && isset($_POST['password']) && !empty($_POST['password']))
        {
//            debug($_POST);
            return $this->Fw->Account->signin($_POST['login'], $_POST['password']);
        }
        return false;
    }


    protected  function checkPostKeysNoEmpty(array $keys)
    {
        foreach ($keys as $key)
        {
            if (!(array_key_exists($key, $_POST) && !empty($_POST[$key])))
                return false;
        }
        return true;
    }


    /**
     * @param string $table
     * @param string $name
     * @return string
     */
    public function getUniqueName(string $table, string $name)
    {
        $i = 0;
        do
        {
            $resultName = $name . ($i > 0 ? '-'.$i : null);
            $issetName = (bool) $this->Fw->Db
                ->select()
                ->from($table)
                ->where(['name'=>$resultName])
                ->result();
//                debug($resultName, $issetName);
            $i++;
        }
        while (!empty($issetName));
        return $resultName;
    }


    /**
     * @param string $string
     * @return string|string[]|null
     */
    public function titleToName(string $string)
    {
        $translit = [
            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'e', 'ж' => 'zj', 'з' => 'z',
            'и' => 'i', 'й' => 'i', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r',
            'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'kh', 'ц' => 'z', 'ч' => 'ch', 'ш' => 'sh',
            'щ' => 'sch','ы' => 'i','э' => 'e', 'ю' => 'yu', 'я' => 'ya', ' ' => '-', '–' => '-', '—' => '-', '_' => '-'];
        foreach ($translit as $key => $value)
        {
            $pattern = '#['.$key.']#ui';
            $string = preg_replace($pattern,$value,$string);
        }

        $string = preg_replace('#([^A-Za-z0-9\-])#ui','',$string);
        $string = preg_replace('#(-+)#ui','-',$string);
        $string = trim($string,"-");
        return $string;
    }
}