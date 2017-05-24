<?php

namespace PPM\Dispatcher;


class ControllerPath
{

    protected $path;

    protected $namespace;

    protected $sufix;

    public function __construct($path, $namespace, $sufix="Controller")
    {
        $this->path = $path;
        $this->namespace = $namespace;
        $this->sufix = $sufix;
    }

    /**
     * get 'path' value
     * @return string
     */
    public function getPath() : string
    {
        return $this->path;
    }

    /**
     * set new 'path' value
     * @param string $value new value of 'path'
     * @return ControllerPath
     */
    public function setPath(string $value) : ControllerPath
    {
        $this->path = $value;
        return $this;
    }

    /**
     * get 'namespace' value
     * @return string
     */
    public function getNamespace() : string
    {
        return $this->namespace;
    }

    /**
     * set new 'namespace' value
     * @param string $value new value of 'namespace'
     * @return ControllerPath
     */
    public function setNamespace(string $value) : ControllerPath
    {
        $this->namespace = $value;
        return $this;
    }

    /**
     * get 'sufix' value
     * @return string
     */
    public function getSufix() : string
    {
        return $this->sufix;
    }

    /**
     * set new 'sufix' value
     * @param string $value new value of 'sufix'
     * @return ControllerPath
     */
    public function setSufix(string $value) : ControllerPath
    {
        $this->sufix = $value;
        return $this;
    }

}
