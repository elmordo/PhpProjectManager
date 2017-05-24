<?php

namespace PPM\Dispatcher;

use PPM\Service\IService;
use PPM\Service\ServiceTrait;


class Service extends \PPM\Dispatcher implements IService
{

    use ServiceTrait;

    public function getDependencies() : array
    {
        return [ "router" ];
    }

    public function initialize()
    {
    }

}
