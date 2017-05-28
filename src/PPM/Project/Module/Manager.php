<?php

namespace PPM\Project\Module;

use PPM\Config\ConfigData;


class Manager
{

    protected $moduleExplorer;

    protected $modules = [];

    public function getModuleExplorer() : IModuleExplorer
    {
        return $this->moduleExplorer;
    }

    public function setModuleExplorer(IModuleExplorer $value) : Manager
    {
        $this->moduleExplorer = $value;
        return $this;
    }

    public function initializeModule(string $moduleName)
    {
        $globalConfigData = [];
        $globalConfig = new ConfigData($globalConfigData);

        $localConfigData = [];
        $localConfig = new ConfigData($localConfigData);
        $module = $this->moduleExplorer->initializeModule($moduleName, $globalConfig, $localConfig);
    }

    public function addModuleByName(string $moduleName) : Module
    {
        $module = $this->moduleExplorer->loadModule($moduleName);
        $this->modules[$moduleName] = $module;
        return $module;
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

    public function getModuleNames() : array
    {
        return array_keys($this->modules);
    }

    public function getModule(string $name)
    {
        return $this->modules[$name];
    }

}