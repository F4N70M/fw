<?php


namespace Fw\Components\Services\Database;


class Entity extends Table
{
    protected $type;

    public function __construct(EntityQueryBuilder $db, string $tableName, string $type)
    {
        parent::__construct($db, $tableName);

        $this->type = $type;
    }
}