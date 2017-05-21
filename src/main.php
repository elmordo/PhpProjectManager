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

// load config
$appConfigData = include __DIR__ . "/../resource/application.default.config.php";
$config = new PPM\Config\Service($appConfigData);

// setup application
$application = new \PPM\Application();
$serviceManager = $application->getServiceManager();

$serviceManager->setService("config", new \PPM\Service\ServiceProvider($config));

try
{
    $application->handle($argv);
}
catch (\Exception $error)
{

}
