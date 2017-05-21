<?php

namespace PPM;

use PPM\Service\ServiceManager;
use PPM\Service\IServiceManager;


class Application
{

    protected $basePath;

    protected $serviceManager;

    public function __construct()
    {
        $this->setup();
        $this->serviceManager = new ServiceManager();
    }

    public function getServiceManager() : IServiceManager
    {
        return $this->serviceManager;
    }

    public function setServiceManager(IServiceManager $value) : Application
    {
        $this->serviceManager = $value;
        return $this;
    }

    public function setup()
    {
        $this->basePath = $_SERVER["PWD"];
    }

    public function handle($arguments)
    {
        # code...
    }

}
