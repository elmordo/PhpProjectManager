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
        $parameters = $
    }

}
