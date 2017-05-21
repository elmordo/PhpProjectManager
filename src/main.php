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
$application = new \PPM\Application();
$serviceManager = $application->getServiceManager();

$serviceManager->setService("config", new \PPM\Service\ServiceProvider(new PPM\Config\Service()));
$serviceManager->setService("project", new \PPM\Service\ServiceProvider(new \PPM\Project\Service(), [ "config" ]));

try
{
    $application->handle($argv);
}
catch (\Exception $error)
{

}
