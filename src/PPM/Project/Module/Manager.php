<?php

namespace PPM\Project\Module;


class Manager
{

    protected $modules = [];

    public function addModule(Module $module)
    {
        $this->modules[$module->getName()] = $module;
    }

    public function getModules()
    {
        return array_keys($this->modules);
    }

    public function getModule(string $name)
    {
        return $this->modules[$name];
    }

}