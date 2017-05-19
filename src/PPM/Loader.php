<?php

namespace PPM;

class Loader
{

    protected $registeredDirs = [];

    public function __invoke($name)
    {
        // create path
        $parts = explode("\\", $name);

        foreach ($this->registeredDirs as $directoryName)
        {
            $pathArr = array_merge([ $directoryName ], $parts);
            $path = call_user_func_array("joinPath", $pathArr) . ".php";

            if (is_file($path))
            {
                die;
                require $path;
                break;
            }
        }
    }

    public function registerLoader($value='')
    {
        spl_autoload_register($this);
    }

    public function registerDir($directoryName)
    {
        $this->registeredDirs[] = $directoryName;
    }

}
