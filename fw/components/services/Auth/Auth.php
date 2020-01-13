<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 10.01.2020
 */


namespace Fw\Components\Services\Auth;

use Fw\Components\Services\Cookie\Cookie;
use Fw\Components\Services\Database\QueryBuilder;
use Exception;

class Auth
{
	private $db;
	private $cookie;

	private $data = [
		'list'      =>  [],
		'current'   =>  null,
		'session'   =>  null
	];


	/**
	 * Auth constructor.
	 * @param QueryBuilder $db
	 * @param Cookie $cookie
	 * @throws Exception
	 */
	public function __construct(QueryBuilder $db, Cookie $cookie)
	{
		$this->db = $db;
		$this->cookie = $cookie;

		$data = $this->cookie->has('auth') ? $this->cookie->get('auth') : [];

		if (isset($data['list']) && is_array($data['list']) && !empty($data['list']))
		{
			$this->data['list'] = $data['list'];

			if (isset($data['current']) && !empty($data['current']) && is_int($data['current']) && in_array($data['current'],$data['list']))
			{
				$this->data['current'] = $data['current'];
			}
			else
			{
				$this->data['current'] = array_key_first($data->data['list']);
			}

			foreach ($this->data['list'] as $userID => $remember)
			{
				if (!$remember && (isset($data['session']) && $data['session'] == session_id()))
				{
					unset($this->data['list'][$userID]);
				}
			}

			$this->data['session'] = session_id();
		}
		$this->cookie->set('auth', $this->data, time() + 60 * 60 * 24 * 30);
	}


	/**
	 * @return array
	 */
	public function getlist()
	{
		return $this->data['list'];
	}


	/**
	 * @return array
	 */
	public function getCurrent()
	{
		return $this->data['current'];
	}


	/**
	 * @param int $userID
	 * @return bool
	 */
	public function check(int $userID)
	{
		return isset($this->data['list'][$userID]);
	}


	/**
	 * @param int $userID
	 * @param bool $remember
	 * @return bool
	 */
	public function in(int $userID, $remember=true)
	{
		$this->getlist();

		$this->data['list'][$userID] = $remember;
		$this->data['current'] = $userID;

		$result = $this->cookie->set('auth', $this->data, time() + 60 * 60 * 24 * 30);

		return $result;
	}


	/**
	 *
	 */
	public function out()
	{
		$this->cookie->unset('auth');
	}
}