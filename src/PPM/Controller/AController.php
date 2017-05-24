<?php

namespace PPM\Controller;

use PPM\Service\IServiceManagerAware;
use PPM\Service\IServiceManager;


abstract class AController implements IController
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
    public function setServiceManager(IServiceManager $manager) : IServiceManagerAware
    {
        $this->serviceManager = $manager;
        return $this;
    }

    /**
     * do some action
     * @param string $actionName action name
     * @return IController reference to this instance
     */
    public function doActionCall(string $actionName) : IController
    {
        return $this;
    }

}
