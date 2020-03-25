<?php
/**
 * Treat: F4N70M
 * Version: 0.1
 * Date: 16.01.2020
 */

namespace Fw\Components\Modules\Template\Block;


class Tpl
{

	protected $name = 'default';
	protected $tpl;
	protected $file;
	protected $data;

	public function __construct(array $data)
	{
		$this->data = $data;
		$this->file = str_replace("\\","/",__DIR__) . "/" . $this->tpl . "/src/tpl/" . $this->name . ".tpl";
	}

	public function get()
	{
		ob_start();
			extract($this->data);
			require $this->file;
		$result = ob_get_clean();
		return $result;
	}
}