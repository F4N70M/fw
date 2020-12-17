<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 20.07.2020
 */

namespace Fw\Components\Interfaces;


interface iDatabase
{
	public function select($columns=null);
	public function insert();
	public function update(string $table);
	public function delete();
}