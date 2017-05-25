<?php

namespace PPM\IO;

use PPM\Service\IService;
use PPM\Service\ServiceTrait;


class Service extends \PPM\IO implements IService
{

    use ServiceTrait;

    public function getDependencies() : array
    {
        return [ "config" ];
    }

    public function initialize()
    {
        $config = $this->getServiceManager()->getService("config")->io;
        $adapterClass = $config->adapter;

        $adapter = new $adapterClass();
        $this->setAdapter($adapter);
    }

}