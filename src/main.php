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

// config setup
$configService = new PPM\Config\Service();

// setup application config
$configs = [ ".ppm.global.php", ".ppm.global.php", ];

foreach ($configs as $config)
{
    $fullPath = joinPath($currentPath, $config);
    if (is_file($fullPath))
    {
        $configData = inlcude($fullPath);
        $configService->mergeWithArray($configData);
    }
}

$serviceManager->setService("config", new \PPM\Service\ServiceProvider($configService));
$serviceManager->setService("router", new \PPM\Service\ServiceProvider(new \PPM\Router\Service(), [ "config" ]));
$serviceManager->setService("dispatcher", new \PPM\Service\ServiceProvider(new \PPM\Dispatcher\Service(), [ "router" ]));
$serviceManager->setService("application", new \PPM\Service\ServiceProvider(new \PPM\Application(), [ "config", "dispatcher" ]));
$serviceManager->setService("project", new \PPM\Service\ServiceProvider(new \PPM\Project\Service(), [ "config", "application" ]));

$application = $serviceManager->getService("application");

try
{
    $application->handle($argv);
}
catch (\Exception $error)
{
	echo $error->getMessage() . "\n";
	exit($error->getCode());
}
