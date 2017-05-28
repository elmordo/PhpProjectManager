<?php

namespace PPM\Project\Module;

use PPM\Config\ConfigData;


class ModuleExplorer implements IModuleExplorer
{

    const GLOBAL_CONFIG_NAME = ".ppm.global.config.json";

    const LOCAL_CONFIG_NAME = ".ppm.local.config.json";

    protected $directories = [];

    /**
     * add directory to explorer
     * @param string $directory directory name
     * @return IModuleExplorer reference to this instance
     */
    public function addDirectory(string $directory) : IModuleExplorer
    {
        $this->directories[] = $directory;
        return $this;
    }

    /**
     * search for all available modules (initialized and uninitialized)
     * @return array array of module names
     */
    public function explore() : array
    {
        $result = [];

        foreach ($this->directories as $directory)
        {
            if (is_dir($directory))
                $result = array_merge($result, $this->exploreDirectory($directory));
        }

        return $result;
    }

    /**
     * load initialized module
     * @param string $moduleName module name
     * @return Module module
     */
    public function loadModule(string $moduleName) : Module
    {
        $module = null;

        foreach ($this->directories as $directory)
        {
            $path = joinPath($directory, $moduleName);

            if (is_dir($path))
            {
                $module = new Module($moduleName, $path);
            }
        }

        if ($module === null)
            throw new Exception("Module '$moduleName' not found.");

        return $module;
    }

    /**
     * initialize module and return instance
     * @param string $moduleName module name
     * @param ConfigData $globalConfig global config
     * @param ConfigData $localConfig local config
     * @return Module name of module
     */
    public function initializeModule($moduleName, ConfigData $globalConfig, ConfigData $localConfig) : Module
    {

    }

    /**
     * search for modules in one directory. Initialized and uninitialized
     * module names are returned both.
     * @param string $directory directory to search in
     * @return array list of found module names
     */
    private function exploreDirectory($directory) : array
    {
        $modules = [];

        $dir = dir($directory);

        while ($entry = $dir->read())
        {
            if ($entry == "." || $entry == "..")
                // skip . and ..
                continue;

            $path = joinPath($directory, $entry);

            if (is_dir($path))
            {
                $modules[] = $entry;
            }
        }

        return $modules;
    }

    /**
     * construct one module
     * @param string $directory path to module directory
     * @return Module instance of module
     */
    public function constructModule(string $directory) : Module
    {
        $name = basename($directory);
        $config = [];
        $module = new Module($name, $directory, $config);

        return $module;
    }

}
