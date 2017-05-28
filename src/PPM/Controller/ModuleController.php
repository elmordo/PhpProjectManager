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
    }

    protected function saveModulesConfig(array $moduleNames)
    {
        $configAdapter = $this->getServiceManager()->getService("globalConfigAdapter");
        $config = $configAdapter->getAdapter()->load();
        $config->modules = $moduleNames;
        #$configAdapter->getAdapter()->save($config);
    }

    protected function addModules(array $modules, ModuleManager $project)
    {
        foreach ($modules as $module)
        {
            $project->initializeModule($module->getName());
        }
    }

    protected function askForModulesAdd(array $modules) : array
    {
        $result = [];

        foreach ($modules as $module)
        {
            if ($this->askForModuleAction("Do you want to add module \"{{name}}\"?", $module))
                $result[] = $module;
            break;
        }

        return $result;
    }

    protected function askForModuleAction(string $template, Module $module) : bool
    {
        $question = strtr($template, [ "{{name}}" => $module->getName() ]);
        $io = $this->getServiceManager()->getService("io");

        return $io->askYesNoAbort($question);
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
