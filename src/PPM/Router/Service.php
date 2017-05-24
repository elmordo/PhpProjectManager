<?php

namespace PPM\Router;

use PPM\Service\IService;
use PPM\Service\ServiceTrait;


class Service extends \PPM\Router implements IService
{

	use ServiceTrait;

	public function getDependencies() : array
	{
		return [ "config" ];
	}

	public function initialize()
	{
        $this->clearRoutes();
		$this->setupRoutesFromConfig();
	}

    public function setupRoutesFromConfig() : Service
    {
        $config = $this->getServiceManager()->getService("config");
        $routes = $config->routes->toArray();

        foreach ($routes as $route)
        {
            $this->createRoute()->setupFromArray($route);
        }

        return $this;
    }

}
