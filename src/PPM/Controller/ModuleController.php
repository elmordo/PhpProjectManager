<?php

namespace PPM\Controller;


class ModuleController extends AController
{

    public function discoverAction()
    {
        $project = $this->getServiceManager()->getService("project");
        $moduleManager = $project->getModuleManager();
    }

}
