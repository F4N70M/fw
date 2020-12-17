<?php


namespace Fw\Components\Modules\Bills;


use Fw\Components\Classes\DbItem;
use Fw\Components\Services\Database\QueryBuilder;

class Bill extends DbItem
{
    public function __construct(QueryBuilder $db, int $id)
    {
        parent::__construct($db,'objects',$id);
    }
}