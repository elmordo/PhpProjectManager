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
        $newModules = $this->filterExistingAndIgnored($allModules, $moduleManager);

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

    public function ignoreAction()
    {
        $params = $this->getServiceManager()->getService("router")->getParams();
        $io = $this->getServiceManager()->getService("io");

        // get global config and add module to it
        $configAdapter = $this->getServiceManager()->getService("globalConfigAdapter");
        $config = $configAdapter->getAdapter()->load();
        $ignoredModules = $this->getIgnoredModules($config);

        $moduleName = $params["moduleName"];

        if (!in_array($moduleName, $ignoredModules))
        {
            $ignoredModules[] = $moduleName;
            $config->ignored_modules = $ignoredModules;
            $configAdapter->getAdapter()->save($config);
            $io->write("Module '$moduleName' was added to the ignoration modules.");
        }
        else
        {
            $io->write("Module '$moduleName' is already ignored.");
        }
    }

    public function ignoredsAction()
    {
        $config = $this->getServiceManager()->getService("config")->toArray();
        $ignoredModules = $config["ignored_modules"] ?? [];

        $this->getServiceManager()->getService("view")->ignoredModules = $ignoredModules;
    }

    public function unignoreAction()
    {
        $params = $this->getServiceManager()->getService("router")->getParams();
        $io = $this->getServiceManager()->getService("io");

        // get global config and add module to it
        $configAdapter = $this->getServiceManager()->getService("globalConfigAdapter");
        $config = $configAdapter->getAdapter()->load();
        $ignoredModules = $this->getIgnoredModules($config);

        $moduleName = $params["moduleName"];

        if (in_array($moduleName, $ignoredModules))
        {
            if (($key = array_search($moduleName, $ignoredModules)) !== false)
                unset($ignoredModules[$key]);

            $config->ignored_modules = $ignoredModules;
            $configAdapter->getAdapter()->save($config);
            $io->write("Module '$moduleName' was removed from the ignored modules.");
        }
        else
        {
            $io->write("Module '$moduleName' is not in ignoration list.");
        }
    }

    protected function getIgnoredModules(\PPM\Config\ConfigData $config) : array
    {
        $ignoredModules = $config->ignored_modules;

        if ($ignoredModules === null)
            $ignoredModules = [];
        else if ($ignoredModules instanceof \PPM\Config\ConfigData)
            $ignoredModules = $ignoredModules->toArray();

        return $ignoredModules;
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

    protected function filterExistingAndIgnored(array $allModules, ModuleManager $moduleManager) : array
    {
        $result = [];
        $config = $this->getServiceManager()->getService("config")->toArray();
        $ignoredModules = $config["ignored_modules"] ?? [];

        foreach ($allModules as $moduleName)
        {
            if (!$moduleManager->hasModule($moduleName) && !in_array($moduleName, $ignoredModules))
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
