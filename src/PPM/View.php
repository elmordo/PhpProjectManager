<?php

namespace PPM;


class View implements View\IView
{

    private $__templatePath;

    private $__variables = [];

    /**
     * shortcut for getVariable
     * @param string $name variable name
     * @return mixed value of variable or null if variable is not defined
     */
    public function __get($name)
    {
        return $this->getVariable($name);
    }

    /**
     * shortcut for setVariable
     * @param string $name name of variable
     * @param mixed $value value for variable
     */
    public function __set($name, $value)
    {
        $this->setVariable($name, $value);
    }

    /**
     * set path of template to render
     * @param string $path path to template
     * @return IView reference to this instance
     */
    public function setTemplatePath(string $path) : IView
    {
        $this->__templatePath = $path;
        return $this;
    }

    public function getTemplatePath() : string
    {
        return $this->__templatePath;
    }

    /**
     * render template
     * @return string rendered content
     */
    public function render() : string;

    /**
     * set new variable
     * @param string $name variable name
     * @param mixed $value value of variable
     * @return IView reference to this instance
     */
    public function setVariable(string $name, $value) : IView
    {
        $this->__variables[$name] = $value;
        return $this;
    }

    /**
     * get variable
     * or null if no variable with $name is defined
     * @param string $name variable name
     * @return mixed variable content
     */
    public function getVariable(string $name)
    {
        return $this->__variables[$name] ?? null;
    }

}