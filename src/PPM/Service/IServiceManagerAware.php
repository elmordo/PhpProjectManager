<?php

namespace PPM\Service;


interface IServiceManagerAware
{

    /**
     * get service manager
     * @return IServiceManager current service manager
     */
    public function getServiceManager() : IServiceManager;

    /**
     * set new service manager
     * @param IServiceManager $manager new service manager to set
     * @return IServiceManagerAware reference to this instance
     */
    public function setServiceManager(IServiceManager $manager) : IServiceManagerAware;

}
