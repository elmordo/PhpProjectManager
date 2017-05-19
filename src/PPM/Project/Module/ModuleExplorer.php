<?php

namespace PPM\Project\Module;


class ModuleExplorer implements IModuleExplorer
{

    protected $directories = [];

    public function addDirectory(string $directory)
    {
        $this->directories[] = $directory;
        return $this;
    }

    public function explore() : array
    {
        $result = [];

        foreach ($this->directories as $directory)
        {
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

}
