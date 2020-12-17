<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 04.06.2020
 */

namespace Fw\Components\Modules\TemplateEngine\Block\Cover;

use Fw\Components\Modules\TemplateEngine\Block\Cover;

class Cover_Default extends Cover
{
	protected $family = 'Cover';
	protected $name   = 'Default';


	public function __construct(array $data = [], array $blocks = [])
	{
		parent ::__construct($data, $blocks);

		$this->script[] = 'cover.js';
		$this->style[]  = 'cover.css';
	}
}