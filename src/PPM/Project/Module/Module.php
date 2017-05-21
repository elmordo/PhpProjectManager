<?php

namespace PPM\Project\Module;


class Module
{

    protected $name;

    protected $path;

    protected $config;

    public function __construct(string $name, string $path, array $config=array())
    {
    	$this->config = $config;
    	$this->name = $name;
    	$this->path = $path;
    }

    public function getName() : string
    {
    	return $this->name;
    }

}
