<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 13.07.2020
 */

namespace Fw\Components\Modules\TemplateEngine\Block\Structure;


use Fw\Components\Modules\TemplateEngine\Block;

class Structure extends Block
{
	protected $family = 'Structure';

	public function __construct(array $data = [], array $blocks = [])
	{
		parent ::__construct($data, $blocks);

		$this->script[] = 'structure.js';
		$this->style[]  = 'structure.css';
	}
}