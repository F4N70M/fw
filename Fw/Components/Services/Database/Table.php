<?php


namespace Fw\Components\Services\Database;


class Table
{
    protected $db;
    protected $table;


    public function __construct(EntityQueryBuilder $db, string $tableName)
    {
        $this->db = $db;
        $this->name = $tableName;
    }


    public function select(array $where)
    {}

    public function update()
}