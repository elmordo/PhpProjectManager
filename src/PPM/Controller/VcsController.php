<?php

namespace PPM\Controller;


class VcsController extends AController
{

    public function pullAllAction()
    {
        $serviceManager = $this->getServiceManager();
        $project = $serviceManager->getService("project");
        $application = $serviceManager->getService("application");
        $config = $serviceManager->getService("config");

        $vcsType = $config->getStrict("vcs");
        $mainPath = $application->getBasePath();
        $this->pull($mainPath, $vcsType);

        $moduleManager = $project->getModuleManager();
        $moduleNames = $moduleManager->getModuleNames();

        foreach ($moduleNames as $moduleName)
        {
            $module = $moduleManager->getModule($moduleName);
            $this->pull($module->getPath(), $vcsType);
        }
    }

    public function commitAllAction()
    {
        $serviceManager = $this->getServiceManager();
        $project = $serviceManager->getService("project");
        $application = $serviceManager->getService("application");
        $config = $serviceManager->getService("config");

        $vcsType = $config->getStrict("vcs");
        $moduleManager = $project->getModuleManager();
        $moduleNames = $moduleManager->getModuleNames();

        foreach ($moduleNames as $moduleName)
        {
            $module = $moduleManager->getModule($moduleName);
            $this->commit($module->getPath(), $vcsType);
        }
        $mainPath = $application->getBasePath();
        $this->commit($mainPath, $vcsType);
    }

    /**
     * do pull in given directory
     * @param string $path path to the directory
     * @param string $vcsType type of used VCS
     */
    public function pull(string $path, string $vcsType)
    {
        $factory = new \PPM\Vcs\Factory();
        $vcs = $factory->createVcs($vcsType, $path);

        if ($vcs->isInitialized())
        {
            echo "Pulling '$path'" . PHP_EOL;
            $vcs->pull();
        }
        else
        {
            echo "Path '$path' is not repository" . PHP_EOL;
        }
    }

    public function commit(string $path, string $vcsType)
    {
        $factory = new \PPM\Vcs\Factory();
        $vcs = $factory->createVcs($vcsType, $path);

        if ($vcs->isInitialized())
        {
            echo "Commiting '$path'" . PHP_EOL;
            $vcs->add("*");
            $vcs->commit();
        }
    }

}
