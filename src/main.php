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

$serviceManager->setService("config", new \PPM\Service\ServiceProvider(new PPM\Config\Service()));
$serviceManager->setService("router", new \PPM\Service\ServiceProvider(new \PPM\Router\Service(), [ "config" ]));
$serviceManager->setService("application", new \PPM\Service\ServiceProvider(new \PPM\Application(), [ "config" ]));
$serviceManager->setService("project", new \PPM\Service\ServiceProvider(new \PPM\Project\Service(), [ "config", "application" ]));

// try to setup one route
$router = $serviceManager->getService("router");

$routeParams = [
	"definition" => [
		[
			"type" => "static",
			"options" => [
				"required" => true,
				"name" => "placeholder",
			],
		],
		[
			"type" => "positional",
			"options" => [
				"required" => true,
				"name" => "controller",
				"mapping" => "hovno2",
			],
		],
	],
	"defaults" => [
		"controller" => "my",
		"action" => "bitch",
	],
	"name" => "my_route",
];

$route = $router->createRoute()->setupFromArray($routeParams);

$args = [ "placeholder", "bar" ];
var_dump($router->match($args)->getParams());

$application = $serviceManager->getService("application");

try
{
    $application->handle($argv);
}
catch (\Exception $error)
{

}
