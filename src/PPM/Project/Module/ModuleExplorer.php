<?php

namespace PPM\Project\Module;


class ModuleExplorer implements IModuleExplorer
{

    const GLOBAL_CONFIG_NAME = ".ppm.config.global.php";

    const LOCAL_CONFIG_NAME = ".ppm.config.local.php";

    protected $directories = [];

    public function addDirectory(string $directory) : IModuleExplorer
    {
        $this->directories[] = $directory;
        return $this;
    }

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

    private function exploreDirectory($directory) : array
    {
        $modules = [];

        $dir = dir($directory);

        while ($entry = $dir->read())
        {
            $path = joinPath($directory, $entry);

            if (is_dir($path))
            {
                try
                {
                    $module = $this->constructModule($path);
                }
                catch (\Exception $e)
                {
                    continue;
                }

                $modules[] = $module;
            }
        }

        return $modules;
    }

    public function constructModule(string $directory) : Module
    {
        $name = basename($directory);
        $config = $this->loadConfig($directory);
        $module = new Module($name, $directory, $config);

        return $module;
    }

    public function loadConfig(string $directory) : array
    {
        $globalPath = joinPath($directory, self::GLOBAL_CONFIG_NAME);
        $localPath = joinPath($directory, self::LOCAL_CONFIG_NAME);

        // global config files has to exists
        if (!is_file($globalPath))
            throw new \Exception("Module config was not found", 500);

        $result = [];

        foreach ([ $globalPath, $localPath] as $configPath)
        {
            if (is_file($configPath))
            {
                $config = require $configPath;
                $result = array_merge($result, $config);
            }
        }

        return $result;
    }

}
