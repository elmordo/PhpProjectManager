<?php

namespace PPM\Project\Module;

use PPM\Config\ConfigData;
use PPM\Config\Adapter\Factory as AdapterFactory;


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
    public function loadModule(string $moduleName, ConfigData $defaultConfig) : Module
    {
        $module = null;

        foreach ($this->directories as $directory)
        {
            $path = $this->constructModulePath($directory, $moduleName);

            if (is_dir($path))
            {
                $module = $this->constructModule($path, $defaultConfig);
                break;
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
        $modulePath = null;

        foreach ($this->directories as $directory)
        {
            $path = $this->constructModulePath($directory, $moduleName);

            if (is_dir($path))
                $modulePath = $path;
        }

        if ($modulePath === null)
            throw new Exception("Module '$moduleName' not found", 404);

        // write config files
        $this->writeConfig($modulePath, self::GLOBAL_CONFIG_NAME, $globalConfig);
        $this->writeConfig($modulePath, self::LOCAL_CONFIG_NAME, $localConfig);

        return $this->constructModule($modulePath, ConfigData::createEmptyInstance());
    }

    private function writeConfig(string $directory, string $fileName, ConfigData $config)
    {
        $factory = new AdapterFactory();
        $fullPath = joinPath($directory, $fileName);
        $adapter = $factory->createAdapter($fullPath);
        $adapter->save($config);
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

            $path = $this->constructModulePath($directory, $entry);

            if (is_dir($path))
            {
                $modules[] = $entry;
            }
        }

        return $modules;
    }

    /**
     * construct module path from module name and directory name
     * @param string $directory name of directory
     * @param string $moduleName name of module
     * @return string path to the module directory
     */
    public function constructModulePath(string $directory, string $moduleName) : string
    {
        $relativePath = joinPath($directory, $moduleName);
        $absolutePath = realpath($relativePath);
        return $absolutePath;
    }

    /**
     * construct one module
     * @param string $directory path to module directory
     * @param ConfigData $defaultConfig default config used for module initialization.
     * @return Module instance of module
     */
    private function constructModule(string $directory, ConfigData $defaultConfig) : Module
    {
        // get name
        $name = basename($directory);

        // load config (if config is not found, directory is not registered
        // module and can not be constructed)
        try
        {
            $config = $this->loadModuleConfig($directory, false, $defaultConfig);
        }
        catch (Exception $e)
        {
            $config = $defaultConfig;
        }

        $module = new Module($name, $directory, $config);

        return $module;
    }

    private function loadModuleConfig(string $directory) : ConfigData
    {
        $globalPath = joinPath($directory, self::GLOBAL_CONFIG_NAME);
        $localPath = joinPath($directory, self::LOCAL_CONFIG_NAME);

        // global config must exists
        if (is_file($globalPath))
        {
            $factory = new AdapterFactory();
            $adapter = $factory->createAdapter($globalPath);

            $config = $adapter->load();
        }
        else
        {
            throw new Exception("Can not load global config file "
                . "'$globalPath'.", 500);
        }

        // load local config
        try
        {
            $adapter = $factory->createAdapter($localPath);
            $localConfig = $adapter->load();
            $config->mergeWith($localConfig);
        }
        catch (\Exception $e)
        {
            // error in local config loading -> shit happened
        }

        return $config;
    }

}
