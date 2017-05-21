<?php

namespace PPM;

use PPM\Service\ServiceManager;
use PPM\Service\IServiceManager;
use PPM\Service\IService;
use PPM\Service\ServiceTrait;


class Application implements IService
{

    use ServiceTrait;

    protected $basePath;

    public function getDependencies() : array
    {
        return [ "config" ];
    }

    public function initialize()
    {
        $this->basePath = $_SERVER["PWD"];
    }

    public function handle($arguments)
    {
        # code...
    }

}
