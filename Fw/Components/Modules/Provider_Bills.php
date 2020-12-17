<?php


namespace Fw\Components\Modules;

use Fw\Components\Classes\Provider;
use Fw\Components\Di\Container;

class Provider_Bills extends Provider
{
    protected $container;

    /**
     * Provider_TemplateEngine constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        parent::__construct($container);

        $this->init(
            \Fw\Components\Modules\Bills\Bills::class,
            'Bills',
            true
        );
        $this->init(
            \Fw\Components\Modules\Bills\Bill::class,
            'Bill',
            false
        );
    }
}