<?php

namespace PPM\Controller;

use PPM\Config\ConfigData;
use PPM\Config\Adapter\Json;
use PPM\Config\Adapter\Factory as ConfigAdapterFactory;

class ProjectController extends AController
{

    const APP_GLOBAL_CONFIG = ".ppm.global.json";

    const APP_LOCAL_CONFIG = ".ppm.local.json";

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
        $globalAppConfig = $this->getGlobalAppConfig($config);
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


        $fileName = $this->getAppConfigFileName();
        $factory = new ConfigAdapterFactory();
        $adapter = $factory->createAdapter($fileName);
        $defaultConfig = $adapter->load();

        $result->mergeWith($defaultConfig);

        return $result;
    }

    protected function getAppConfigFileName() : string
    {
        return joinPath(__DIR__, "../../../resource/application.default.config.php");
    }

}
