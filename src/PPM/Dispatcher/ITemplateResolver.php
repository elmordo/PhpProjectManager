<?php

namespace PPM\Dispatcher;


interface ITemplateResolver
{

    /**
     * get path to template
     * @param string $controller name of the controller
     * @param string $action name of the action
     * @return string path to template
     */
    public function getTemplate(string $controller, string $action) : string;

}