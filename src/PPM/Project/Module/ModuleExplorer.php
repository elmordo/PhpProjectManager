<?php

namespace PPM\Project\Module;


class ModuleExplorer implements IModuleExplorer
{

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
            if ($entry == "." || $entry == "..")
                // skip . and ..
                continue;

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
        $config = [];
        $module = new Module($name, $directory, $config);

        return $module;
    }

}
