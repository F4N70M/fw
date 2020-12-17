<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 13.01.2020
 */

namespace Apps\Main\Model;


use Exception;
use Fw\Components\Classes\Model;
use Fw\Core;

class Main_Model extends Model
{
	/**
	 * @var Core
	 */
	protected $Fw;


	/**
	 * Lk_Model constructor.
	 * @param Core $Fw
	 */
	public function __construct(Core $Fw)
	{
	    parent::__construct($Fw);
	}


	/**
	 * @param string $action
	 * @return bool
	 */
	public function request(string $action)
	{
		$method = ucfirst($action).'Request';
		if (method_exists($this,$method))
		{
			if (isset($_POST['redirect']))
			{
				header("Location: {$_POST['redirect']}");
				exit();
			}

			return $this->$method();
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
        $index = $this->Fw->Db
            ->select()
            ->from('options')
            ->where(['name'=>'index'])
            ->limit(1)
            ->result();
        if ($index)
        {
            return $index[0]['value'];
        }
        return false;
	}

	public function getInfo($name)
	{
		$uniq = ($name == 'index' ? $this->getIndex() : $name);

		$result = $this->Fw->Db
            ->select()
            ->from('objects')
            ->where(['id'=>$uniq, ['or','name'=>$uniq]])
            ->limit(1)
            ->result();

		if ($result)
		    $result = $result[0];
		else
            $result =  $this->getInfo404();

		return $result;
	}

	private function getInfo404()
	{
		return [
		    'type' => 'error',
			'name' => '404',
			'title' => '404',
			'content' => 'Страница не найдена'
		];
	}


	/**
	 * @return bool
	 */
	private function LoginRequest()
	{
		if (isset($_POST['login']) && !empty($_POST['login']) && isset($_POST['password']) && !empty($_POST['password']))
		{
			return $this->Fw->Account->login($_POST['login'], $_POST['password']);
		}
		return false;
	}
}