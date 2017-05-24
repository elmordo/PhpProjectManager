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

    protected $dispatcher;

    public function getDependencies() : array
    {
        return [ "config" ];
    }

    public function initialize()
    {
        $this->basePath = $_SERVER["PWD"];
        $this->dispatcher = $this->getServiceManager()->getService("dispatcher");
    }

    public function getBasePath() : string
    {
        return $this->basePath;
    }

    public function handle($arguments)
    {
        # code...
    }

}
