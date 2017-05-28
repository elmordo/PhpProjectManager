<?php

namespace PPM\Config\Adapter;

use PPM\Config\IAdapter;


class Factory
{

    public function createAdapter($definition) : IAdapter
    {
        if (is_string($definition))
        {
            return $this->resolveStringDefinition($definition);
        }

        $this->throwNotfound($definition);
    }

    protected function resolveStringDefinition(string $definition) : IAdapter
    {
        if (is_file($definition) || pathinfo($definition)["extension"])
        {
            return $this->resolveFileDefinition($definition);
        }

        $this->throwNotfound($definition);
    }

    protected function resolveFileDefinition(string $fileName) : IAdapter
    {
        $fileInfo = pathinfo($fileName);

        $extension = strtolower($fileInfo["extension"] ?? "");
        $adapter = null;
        switch ($extension)
        {
            case "php":
                $adapter = new Php();
                break;

            case "json":
                $adapter = new Json();
                break;
        }

        if ($adapter === null)
            $this->throwNotfound($fileName);

        $adapter->setFileName($fileName);

        return $adapter;
    }

    protected function throwNotfound(string $definition)
    {
        throw new Exception("Unable to determine adapter type for '$definition'", 500);
    }

}
