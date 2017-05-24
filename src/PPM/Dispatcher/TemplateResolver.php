<?php

namespace PPM\Dispatcher;


class TemplateResolver implements ITemplateResolver
{

    /**
     * @var string directory where to search for templates
     */
    private $basePath;

    /**
     * return directory where to search for templates
     * @return string
     */
    public function getBasePath() : string
    {
        return $this->basePath;
    }

    /**
     * set directory where to search for templates
     * @param string $value directory path
     * @return string
     */
    public function setBasePath(string $value) : TemplateResolver
    {
        $this->basePath = $value;
        return $this;
    }

    /**
     * get path to template
     * @param string $controller name of the controller
     * @param string $action name of the action
     * @return string path to template
     */
    public function getTemplate(string $controller, string $action) : string
    {
        return joinPath($this->basePath, $controller, $action . ".php");
    }

}
