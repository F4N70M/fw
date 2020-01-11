<?php
/**
 * Created by PhpStorm.
 * User: KONARD
 * Date: 03.11.2019
 * Time: 6:21
 */

namespace Apps\Main\Controllers;


class Main_Controller
{
	private $Fw;


	/**
	 * Main_Controller constructor.
	 */
	public function __construct(\Fw\Core $Fw)
	{
		$this->Fw = $Fw;
//		debug('Main_Controller: __construct');
	}


	/**
	 * @param string $uri
	 */
	public function direct(string $uri)
	{
//		debug('Main_Controller: direct');
//		debug('uri:',$uri);
	}


	/**
	 *
	 */
	public function request()
    {
	}


    /**
     *
     */
    public function render()
	{

		if (isset($_POST['login']) && !empty($_POST['login']) && isset($_POST['password']) && !empty($_POST['password']))
		{
			$this->Fw->Account->login($_POST['login'],$_POST['password']);
		}

		debug($this->Fw->Account->getList());

		?>
		<form method="post">
			<input type="hidden" name="request" value="login">
			<input type="text" name="login">
			<input type="password" name="password">
			<button type="submit">submit</button>
			<button type="reset">reset</button>
		</form>
		<?php




		debug('УРА! ЭТО СТРАНИЦА!');
	}
}