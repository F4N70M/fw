<?php
/**
 * Project: F4N70M
 * Version: 0.1
 * Date: 17.01.2020
 */

namespace Fw\Components\Modules\Tpl\Blocks\Form;


trait Forms
{
	public function getFormLogin()
	{
		$file = str_replace("\\","/",__DIR__) . "/login.tpl";

		ob_start();
			require $file;
		$result = ob_get_clean();
		return $result;
	}
}