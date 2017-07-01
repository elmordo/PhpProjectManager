<?php

namespace PPM\Project\Module;

use PPM\Config\ConfigData;


class Manager
{

    protected $moduleExplorer;

    protected $modules = [];

    protected $defaultGlobalConfig;

    protected $defaultLocalConfig;

    public function __construct()
    {
        $globalConfig = [];
        $localConfig = [];

        $this->defaultGlobalConfig = new ConfigData($globalConfig);
        $this->defaultLocalConfig = new ConfigData($localConfig);
    }

    public function getDefaultGlobalConfig() : ConfigData
    {
        return $this->defaultGlobalConfig;
    }

    public function setDefaultGlobalConfig(ConfigData $value) : Manager
    {
        $this->defaultGlobalConfig = $value;
        return $this;
    }

    public function getDefaultLocalConfig() : ConfigData
    {
        return $this->defaultLocalConfig;
    }

    public function setDefaultLocalConfig(ConfigData $value) : Manager
    {
        $this->defaultLocalConfig = $value;
        return $this;
    }

    public function getModuleExplorer() : IModuleExplorer
    {
        return $this->moduleExplorer;
    }

    public function setModuleExplorer(IModuleExplorer $value) : Manager
    {
        $this->moduleExplorer = $value;
        return $this;
    }

    public function initializeModule(string $moduleName) : Module
    {
        $module = $this->moduleExplorer->initializeModule(
            $moduleName,
            $this->defaultGlobalConfig,
            $this->defaultLocalConfig);

        $this->modules[$module->getName()] = $module;

        return $module;
    }

    public function addModuleByName(string $moduleName) : Module
    {
        $module = $this->moduleExplorer->loadModule($moduleName, $this->defaultGlobalConfig);
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