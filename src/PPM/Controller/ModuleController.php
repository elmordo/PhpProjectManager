<?php

namespace PPM\Controller;

use PPM\Project;
use PPM\Project\Module\Manager as ModuleManager;

class ModuleController extends AController
{

    public function discoverAction()
    {
        $project = $this->getServiceManager()->getService("project");
        $moduleManager = $project->getModuleManager();

        // setup explorer
        $allModules = $this->loadAllModules();
        $newModules = $this->filterExisting($allModules, $moduleManager);
        var_dump($newModules);
    }

    protected function filterExisting(array $allModules, ModuleManager $moduleManager) : array
    {
        $result = [];

        foreach ($allModules as $module)
        {
            $moduleName = $module->getName();

            if (!$moduleManager->hasModule($moduleName))
            {
                $result[] = $module;
            }
        }

        return $result;
    }

    protected function loadAllModules() : array
    {
        $application = $this->getServiceManager()->getService("application");
        $config = $this->getServiceManager()->getService("config");

        $basePath = $application->getBasePath();
        $moduleDir = $config->getStrict("module_dir");
        $modulePath = joinPath($basePath, $moduleDir);

        $explorerClass = $config->getStrict("module_explorer");

        // setup explorer
        $explorer = new $explorerClass;
        $explorer->addDirectory($modulePath);

        $modules = $explorer->explore();
        return $modules;
    }

}
