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
     * @var Dispatcher\ITemplateResolver template name resolver
     */
    protected $templateResolver;

    /**
     * initialize instance
     */
    public function __construct()
    {
        $this->templateResolver = new Dispatcher\TemplateResolver();
    }

    /**
     * return current template resolver
     * @return ITemplateResolver current template resolver
     */
    public function getTemplateResolver() : Dispatcher\ITemplateResolver
    {
        return $this->templateResolver;
    }

    /**
     * set new template resolver
     * @param Dispatcher\ITemplateResolver $value new template resolver
     * @return Dispatcher reference to this instance
     */
    public function setTemplateResolver(Dispatcher\ITemplateResolver $value) : Dispatcher
    {
        $this->templateResolver = $value;
        return $this;
    }

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
        $this->dispatchLoopRunning = true;

        try
        {
            while (count($this->stack) > 0)
            {
                $currentArgs = array_shift($this->stack);
                $this->dispatchRequest($currentArgs);
            }
        }
        finally
        {
            $this->dispatchLoopRunning = false;
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

        // do action
        $controller->doActionCall($actionBaseName);

        // render view
        $this->renderView($controllerBaseName, $actionBaseName);
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
                $className = $info->getNamespace() . "\\" . $controllerName . $sufix;

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
        $controller->setServiceManager($serviceManager);

        return $controller;
    }

    public function renderView($controller, $action)
    {
        $view = $this->getServiceManager()->getService("view");
        $templatePath = $this->templateResolver->getTemplate($controller, $action);
        $view->setTemplatePath($templatePath);

        try
        {
            $view->render();
        }
        catch (\PPM\View\Exception $e)
        {
            // nothing to do
        }
    }

}
