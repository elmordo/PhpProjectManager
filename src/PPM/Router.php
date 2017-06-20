<?php

namespace PPM;

use PPM\Router\IRoute;
use PPM\Router\Route;


class Router
{

    /**
     * @var array set of routes
     */
	protected $routes = [];

    /**
     * @var array parsed parameters of the last matched route
     */
    protected $lastParams = [];

    /**
     * create new route and add it into internal route list
     * @return IRoute new route instance
     */
	public function createRoute() : IRoute
	{
		$route = new Route();
		$this->routes[] = $route;

		return $route;
	}

    /**
     * remove all routes
     * @return Router reference to this instance
     */
    public function clearRoutes() : Router
    {
        $this->routes = [];
        return $this;
    }

    /**
     * return all registered routes
     * @return array set of routes
     */
    public function getRoutes() : array
    {
        return $this->routes;
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

        $this->lastParams = $result->getParams();

		return $result;
	}

    /**
     * return parsed parameters of the last matched routes.
     * @return array parameters from last matched route
     */
    public function getParams() : array
    {
        return $this->lastParams;
    }

}
