<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 04.06.2020
 */

namespace Fw\Components\Modules\TemplateEngine;


use stdClass;

class Template
{
	protected $title;
	protected $description;
	protected $keywords;

	protected $meta = [];

	protected $data = [];

	public $header;
	public $aside;
	public $content;
	public $footer;


//	public function __construct($serializeContent = null)
//	{
//		if (!empty($serializeContent))
//			$this->content = unserialize($serializeContent);
//	}


}