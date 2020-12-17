<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 27.07.2020
 */

namespace Fw\Components\Classes;


use Fw\Core;

class Model
{
    protected $Fw;

    /**
     * Lk_View constructor.
     * @param Core $Fw
     */
    public function __construct(Core $Fw)
    {
        $this->Fw = $Fw;
    }

    public function getAccess($rank=5)
    {
        $user = $this->Fw->Account->getCurrent();
        //TODO: Написать нормальную проверку доступа
        return ($user['rank'] >= $rank);
    }
}