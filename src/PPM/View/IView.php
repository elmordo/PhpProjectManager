<?php

namespace PPM\View;


interface IView
{

    /**
     * set path of template to render
     * @param string $path path to template
     * @return IView reference to this instance
     */
    public function setTemplatePath(string $path) : IView;

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
    public function setVariable(string $name, $value) : IView;

    /**
     * get variable
     * or null if no variable with $name is defined
     * @param string $name variable name
     * @return mixed variable content
     */
    public function getVariable(string $name);

}
