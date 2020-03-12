<?php
/**
 * Project: F4N70M
 * Version: 0.1
 * Date: 16.01.2020
 */

namespace Fw\Components\Modules\Template\Block\Head;

use Fw\Components\Modules\Template\Block\Tpl;

class HeadDefault_Tpl extends Tpl
{
	protected $tpl = 'Head';

	public function __construct(array $data)
	{
		parent::__construct($data);
	}

	public function get()
	{
		return parent::get();
	}
}