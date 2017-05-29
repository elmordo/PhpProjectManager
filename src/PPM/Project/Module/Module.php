<?php

namespace PPM\Project\Module;

use PPM\Config\ConfigData;


class Module
{

    protected $name;

    protected $path;

    protected $config;

    public function __construct(string $name, string $path, ConfigData $config)
    {
    	$this->config = $config;
    	$this->name = $name;
    	$this->path = $path;
    }

    public function getConfig() : ConfigData
    {
        return $this->config;
    }

    public function getPath() : string
    {
        return $this->path;
    }

    public function getName() : string
    {
    	return $this->name;
    }

}
