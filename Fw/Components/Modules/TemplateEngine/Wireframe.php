<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 04.06.2020
 */

namespace Fw\Components\Modules\TemplateEngine;


class Wireframe
{
	protected $content;
	protected $header;
	protected $sidebar;
	protected $footer;
	protected $sidebarPosition;

	const SIDEBAR_NONE = 0;
	const SIDEBAR_LEFT = -1;
	const SIDEBAR_RIGHT = 1;

	/**
	 * Wireframe constructor.
	 * @param Template $content
	 * @param Template|null $header
	 * @param Template|null $sidebar
	 * @param Template|null $footer
	 */
	public function __construct(Template $content, Template $header=null, Template $sidebar=null, Template $footer=null)
	{
		$this->content = $content;
		$this->header = $header;
		$this->sidebar = $sidebar;
		$this->footer = $footer;
		$this->sidebarPosition = self::SIDEBAR_NONE;
	}
}