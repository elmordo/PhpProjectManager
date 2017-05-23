<?php

namespace PPM\Dispatcher;


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
