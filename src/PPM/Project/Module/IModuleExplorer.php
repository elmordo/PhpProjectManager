<?php

namespace PPM\Project\Module;


interface IModuleExplorer
{

    public function addDirectory(string $directory) : IModuleExplorer;

    public function explore() : array;

}
