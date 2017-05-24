<?php

namespace PPM\Controller;

use PPM\Service\IServiceManagerAware;


interface IController extends IServiceManagerAware
{

    /**
     * do some action
     * @param string $actionName action name
     * @return IController reference to this instance
     */
    public function doActionCall(string $actionName) : IController;

}