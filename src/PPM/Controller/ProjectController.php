<?php

namespace PPM\Controller;

use PPM\Config\ConfigData;
use PPM\Config\Adapter\Json;
use PPM\Config\Adapter\Factory as ConfigAdapterFactory;

class ProjectController extends AController
{

    const APP_GLOBAL_CONFIG = ".ppm.global.json";

    const APP_LOCAL_CONFIG = ".ppm.local.json";

    const APP_DEFAULT_CONFIG = "application.default.config.php";

    const APP_MINIMAL_CONFIG = "application.minimal.config.php";

    public function initAction()
    {
        // prepare new config instance
        $mainConfig = $this->getServiceManager()->getService("config")->toArray();
        $config = new ConfigData($mainConfig);

        // prepare file names
        $application = $this->getServiceManager()->getService("application");
        $appPath = $application->getBasePath();

        $globalConfigFileName = joinPath($appPath, self::APP_GLOBAL_CONFIG);
        $localConfigFileName = joinPath($appPath, self::APP_LOCAL_CONFIG);
        $adapter = new Json();

        // save global config
        $globalAppConfig = $this->getGlobalAppConfig();
        $adapter->setFileName($globalConfigFileName);
        $adapter->save($globalAppConfig);

        // save local config
        $emptyConfig = [];
        $localConfig = new ConfigData($emptyConfig);
        $adapter->setFileName($localConfigFileName);
        $adapter->save($localConfig);
    }

    protected function getGlobalAppConfig(ConfigData $initialData=null)
    {
        $emptyConfig = [];
        $result = new ConfigData($emptyConfig);

        if ($initialData)
            $result->mergeWith($initialData);

        $defaultConfig = $this->loadConfigFromResources(self::APP_DEFAULT_CONFIG);
        $minimalConfig = $this->loadConfigFromResources(self::APP_MINIMAL_CONFIG);

        $result->mergeWith($minimalConfig)->mergeWith($defaultConfig);

        return $result;
    }

    protected function loadConfigFromResources(string $configName) : ConfigData
    {
        $fileName = $this->getAppConfigFileName($configName);
        $factory = new ConfigAdapterFactory();
        $adapter = $factory->createAdapter($fileName);
        $configData = $adapter->load();

        return $configData;
    }

    protected function getAppConfigFileName($configName) : string
    {
        return joinPath(__DIR__, "../../../resource", $configName);
    }

}
