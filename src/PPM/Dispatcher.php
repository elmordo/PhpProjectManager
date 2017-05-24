<?php

namespace PPM;


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
            $this->processArgs($currentArgs);
        }
    }

    private function processArgs(array $args)
    {
        $route = $this->router->match($args);
        $parameters = $route->getParams();

        $controller = $parameters["controller"] ?? null;
        $action = $parameters["action"] ?? null;

        if ($controller === null || $action === null)
        {
            // throw error
            throw new Dispatcher\Exception("Invalid route specs. Controller or action is not set");
        }
    }

}
