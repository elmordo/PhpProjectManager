<?php

namespace PPM\Controller;

use PPM\Project;
use PPM\Project\Module\Manager as ModuleManager;
use PPM\Project\Module\Module;

class ModuleController extends AController
{

    public function discoverAction()
    {
        $project = $this->getServiceManager()->getService("project");
        $io = $this->getServiceManager()->getService("io");
        $moduleManager = $project->getModuleManager();

        // setup explorer
        $allModules = $this->loadAllModules();
        $newModules = $this->filterExisting($allModules, $moduleManager);

        try
        {
            $modulesToAdd = $this->askForModulesAdd($newModules);
        }
        catch (\PPM\IO\AbortException $e)
        {
            $io->write("Operation was aborted. Do nothing.");
            return;
        }

        // add modules to project and save config
        $this->addModules($modulesToAdd, $project->getModuleManager());
        $this->saveModulesConfig($project->getModuleManager()->getModuleNames());

        $view = $this->getServiceManager()->getService("view");
        $view->newModules = $modulesToAdd;
        $view->somethingFound = count($newModules) > 0;
    }

    protected function saveModulesConfig(array $moduleNames)
    {
        $configAdapter = $this->getServiceManager()->getService("globalConfigAdapter");
        $config = $configAdapter->getAdapter()->load();
        $config->modules = $moduleNames;
        $configAdapter->getAdapter()->save($config);
    }

    protected function addModules(array $modules, ModuleManager $moduleManager)
    {
        foreach ($modules as $moduleName)
        {
            $moduleManager->initializeModule($moduleName);
        }
    }

    protected function askForModulesAdd(array $modules) : array
    {
        $result = [];

        foreach ($modules as $moduleName)
        {
            if ($this->askForModuleAction("Do you want to add module \"{{name}}\"?", $moduleName))
                $result[] = $moduleName;
        }

        return $result;
    }

    protected function askForModuleAction(string $template, string $moduleName) : bool
    {
        $question = strtr($template, [ "{{name}}" => $moduleName ]);
        $io = $this->getServiceManager()->getService("io");

        return $io->askYesNoAbort($question);
    }

    protected function filterExisting(array $allModules, ModuleManager $moduleManager) : array
    {
        $result = [];

        foreach ($allModules as $moduleName)
        {
            if (!$moduleManager->hasModule($moduleName))
            {
                $result[] = $moduleName;
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
