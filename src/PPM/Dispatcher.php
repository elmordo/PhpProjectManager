<?php

namespace PPM;

use PPM\Controller\IController;


class Dispatcher
{

    /**
     * @var array stack of requests to do
     */
    protected $stack = [];

    /**
     * @var bool true if dispatch loop running
     */
    private $dispatchLoopRunning = false;

    /**
     * @var PPM\Router router
     */
    protected $router;

    /**
     * set of path where controllers will be searched
     * @var array
     */
    protected $controllerSearchPaths = [];

    /**
     * add new controller search path to instance
     * @param Dispatcher\ControllerPath $pathSpecification controller search path
     */
    public function addControllerPath(Dispatcher\ControllerPath $pathSpecification) : Dispatcher
    {
        $this->controllerSearchPaths[] = $pathSpecification;
        return $this;
    }

    /**
     * start dispatch loop
     */
    public function dispatch(array $args)
    {
        $this->stack[] = $args;
        if (!$this->dispatchLoopRunning)
            $this->runDispatchLoop();
    }

    /**
     * return dispatch loop run status
     * @return bool true if dispatch loop running, false otherwise
     */
    public function isDispatchLoopRunning() : bool
    {
        return $this->dispatchLoopRunning;
    }

    private function runDispatchLoop()
    {
        while (count($this->stack) > 0)
        {
            $currentArgs = array_shift($this->stack);
            $this->dispatchRequest($currentArgs);
        }
    }

    private function dispatchRequest(array $args)
    {
        $route = $this->router->match($args);
        $parameters = $route->getParams();

        $controllerBaseName = $parameters["controller"] ?? null;
        $actionBaseName = $parameters["action"] ?? null;

        if ($controllerBaseName === null || $actionBaseName === null)
        {
            // throw error
            throw new Dispatcher\Exception("Invalid route specs. Controller or action is not set");
        }

        $controller = $this->resolveController($controllerBaseName);
        $this->setupController($controller);
    }

    public function resolveController(string $controllerName) : IController
    {
        foreach ($this->controllerSearchPaths as $info)
        {
            $path = $info->getPath();
            $sufix = $info->getSufix();

            $fileName = joinPath($path, $controllerName . $sufix . ".php");

            if (is_file($fileName))
            {
                require_once $fileName;
                $className = $info->getNamespace() . "\\" . $controllerName;

                return new $className();
            }
        }

        throw new Dispatcher\Exception(sprintf("Controller '%s' not found.", $controllerName));
    }

    /**
     * setup controller
     * @param IController $controller controller to setup
     * @return IController setuped controller (instance from the argument)
     */
    private function setupController(IController $controller) : IController
    {
        $serviceManager = $this->getServiceManager();
        $controller->setServiceManager();
    }

}
