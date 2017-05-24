<?php

namespace PPM\Controller;


class HelpController extends AController
{

    public function indexAction()
    {
        $this->view->routes = $this->getServiceManager()->getService("router")->getRoutes();
    }

}
