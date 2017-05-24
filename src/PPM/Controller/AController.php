<?php

namespace PPM\Controller;


abstract class AController extends IController
{

    protected $serviceManager;

    /**
     * get service manager
     * @return IServiceManager current service manager
     */
    public function getServiceManager() : IServiceManager
    {
        return $this->serviceManager;
    }

    /**
     * set new service manager
     * @param IServiceManager $manager new service manager to set
     * @return IService reference to this instance
     */
    public function setServiceManager(IServiceManager $manager) : IService
    {
        $this->serviceManager = $manager;
        return $this;
    }

}
