<?php

namespace PPM\Project\Module;

use PPM\Config\ConfigData;


interface IModuleExplorer
{

    /**
     * add directory to explorer
     * @param string $directory directory name
     * @return IModuleExplorer reference to this instance
     */
    public function addDirectory(string $directory) : IModuleExplorer;

    /**
     * search for all available modules (initialized and uninitialized)
     * @return array array of module names
     */
    public function explore() : array;

    /**
     * load initialized module
     * @param string $moduleName module name
     * @param array $defaultConfig config used as default when global config was not found
     * @return Module module
     */
    public function loadModule(string $moduleName, ConfigData $defaultConfig) : Module;

    /**
     * initialize module and return instance
     * @param string $moduleName module name
     * @param ConfigData $globalConfig global config
     * @param ConfigData $localConfig local config
     * @return Module name of module
     */
    public function initializeModule($moduleName, ConfigData $globalConfig, ConfigData $localConfig) : Module;

}
