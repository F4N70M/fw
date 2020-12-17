<?php


namespace Fw\Components\Classes;


use Fw\Components\Services\Database\QueryBuilder;

class DbItem
{
    protected $db;
    protected $table;
    protected $id;

    public function __construct(QueryBuilder $db, string $table, int $id)
    {
        $this->db = $db;
        $this->table = $table;
        $this->id = $id;
    }

    public function get(string $column)
    {
        return ($request = $this->db->select()->from($this->table)->where(['id'=>$this->id])->result()) && isset($request[0][$column]) ? $request[0][$column] : false;
    }
}