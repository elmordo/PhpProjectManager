<?php

namespace PPM;


class Application
{

    protected $basePath;

    public function __construct()
    {
        $this->setup();
    }

    public function setup()
    {
        $this->basePath = $_SERVER["PWD"];
    }

    public function handle($arguments)
    {
        # code...
    }

}
