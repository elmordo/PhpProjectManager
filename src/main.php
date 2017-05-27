<?php

use PPM\Service;

function joinPath(...$parts)
{
    return join(DIRECTORY_SEPARATOR, $parts);
}

require_once joinPath(__DIR__, "PPM", "Loader.php");


$loader = new PPM\Loader();
$loader->registerLoader();
$loader->registerDir(joinPath(__DIR__));

$currentPath = $_SERVER["PWD"];

// setup application
$serviceManager = new Service\ServiceManager();
$configAdapterFactory = new PPM\Config\Adapter\Factory();

// config setup
$configService = new PPM\Config\Service();
$globalConfigPath = joinPath($currentPath, \PPM\Controller\ProjectController::APP_GLOBAL_CONFIG);

// setup application config
$configs = [
    joinPath(__DIR__, "../resource/application.minimal.config.php"),
    $globalConfigPath,
    joinPath($currentPath, \PPM\Controller\ProjectController::APP_LOCAL_CONFIG),
];

foreach ($configs as $path)
{
    try
    {
        $adapter = $configAdapterFactory->createAdapter($path);
        $config = $adapter->load();
    }
    catch (\PPM\Config\Adapter\Exception $e)
    {
        continue;
    }

    $configService->mergeWith($config);
}

// setup dispatcher
$dispatcher = new \PPM\Dispatcher\Service();
$dispatcher->getTemplateResolver()->setBasePath(joinPath(__DIR__, "/../templates"));

try
{
    $globalConfigAdapter = $configAdapterFactory->createAdapter($globalConfigPath);
    $service = new \PPM\Config\Adapter\Service();
    $service->setAdapter($globalConfigAdapter);
    $serviceManager->setService("globalConfigAdapter", new \PPM\Service\ServiceProvider($service, []));
}
catch (\PPM\Config\Adapter\Exception $e)
{
    // do nothing
}

$serviceManager->setService("config", new \PPM\Service\ServiceProvider($configService));
$serviceManager->setService("view", new \PPM\Service\ServiceProvider(new \PPM\View\Service));
$serviceManager->setService("router", new \PPM\Service\ServiceProvider(new \PPM\Router\Service(), [ "config" ]));
$serviceManager->setService("io", new \PPM\Service\ServiceProvider(new \PPM\IO\Service(), [ "config" ]));
$serviceManager->setService("dispatcher", new \PPM\Service\ServiceProvider($dispatcher, [ "router", "view" ]));
$serviceManager->setService("application", new \PPM\Service\ServiceProvider(new \PPM\Application(), [ "config", "dispatcher" ]));
$serviceManager->setService("project", new \PPM\Service\ServiceProvider(new \PPM\Project\Service(), [ "config", "application" ]));

$application = $serviceManager->getService("application");

try
{
    echo $application->handle($argv);
}
catch (\Exception $error)
{
	echo "Error: " . $error->getMessage() . "\n";
    echo var_dump($error->getTraceAsString()) . "\n";
	exit($error->getCode());
}
