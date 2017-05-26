<?php

namespace PPM\Project\Module;


class Manager
{

    protected $modules = [];

    public function addModule(Module $module)
    {
        $this->modules[$module->getName()] = $module;
    }

    /**
     * tests if module defined by its name exists
     * @param string $name name of the module
     * @return bool true if module exists, false otherwise
     */
    public function hasModule(string $name) : bool
    {
        foreach ($this->modules as $module)
        {
            if ($module->getName() == $name)
                return true;
        }

        return false;
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