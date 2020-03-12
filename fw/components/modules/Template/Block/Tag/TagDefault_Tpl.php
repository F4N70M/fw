<?php
/**
 * Project: F4N70M
 * Version: 0.1
 * Date: 16.01.2020
 */

namespace Fw\Components\Modules\Template\Block\Tag;


use Fw\Components\Modules\Template\Block\Tpl;

class TagDefault_Tpl extends Tpl
{
	protected $tpl = 'Tag';

	public function __construct(array $data)
	{
		parent::__construct($data);
	}

	public function get()
	{
		return parent::get();
	}
}