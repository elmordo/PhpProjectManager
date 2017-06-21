<?php

namespace PPM\Controller;


class MultirouteController extends AController
{

    public function dispatchAction()
    {
        $params = $this->getServiceManager()->getService("router")->getParams();
        $actions = (array)$params["actions"];
        $dispatcher = $this->getServiceManager()->getService("dispatcher");

        foreach ($actions as $action)
        {
            $dispatcher->dispatch($action);
        }

    }

}
