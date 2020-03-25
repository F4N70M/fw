<?php
/**
 * Treat: F4N70M
 * Version: 0.1
 * Date: 13.01.2020
 */

namespace Fw\Components\Interfaces;


use Fw\Core;

interface iAppController
{
	public function __construct(Core $Fw);

	public function request();

	public function render();
}