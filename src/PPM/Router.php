<?php

namespace PPM;

use PPM\Router\IRoute;
use PPM\Router\Route;


class Router
{

	protected $routes = [];

	public function createRoute() : IRoute
	{
		$route = new Route();
		$this->routes[] = $route;

		return $route;
	}

    public function clearRoutes() : Router
    {
        $this->routes = [];
        return $this;
    }

	/**
	 * try to match route
	 * @param array $arguments from command line
	 * @return IRoute route that match request
	 * @throws \PPM\Router\Exception no route match
	 */
	public function match(array $args) : IRoute
	{
		$result = null;

	 	foreach ($this->routes as $route)
        {
            if ($route->match($args))
            {
                $result = $route;
                break;
            }
        }

		if (is_null($result))
			throw new Router\Exception("No matching route found", 500);


		return $result;
	}

}
