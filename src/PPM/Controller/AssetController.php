<?php

namespace PPM\Controller;

use PPM\Project\Module\Module;


class AssetController extends AController
{

    public function buildAction()
    {
        $project = $this->getServiceManager()->getService("project");
        $config = $this->getServiceManager()->getService("config");
        $moduleManager = $project->getModuleManager();

        $moduleNames = $moduleManager->getModuleNames();
        $targetAssetsPath = realpath($config->getStrict("asset_dir"));

        foreach ($moduleNames as $moduleName)
        {
            $module = $moduleManager->getModule($moduleName);
            $this->buildAssetsForModule($module, $targetAssetsPath);
        }
    }

    private function buildAssetsForModule(Module $module, string $targetPath)
    {
        $vcs = $this->getServiceManager()->getService("vcs")->getVcs();

        $assetBuilder = new \PPM\Asset\Builder();
        $assetBuilder->setTargetPath($targetPath);
        $assetBuilder->setVcs($vcs);

        $assetBuilder->build($module);
    }

}
