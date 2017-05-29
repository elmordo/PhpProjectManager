<?php

namespace PPM\Vcs;


interface IVcs
{

    /**
     * push data to server
     * @return IVcs reference to this instance
     */
    public function push() : IVcs;

    /**
     * pull data from the server
     * @return IVcs reference to this instance
     */
    public function pull() : IVcs;

    /**
     * load or create empty ignore file in given directory
     * @param string $path path to directory
     * @return IIgnoreFile instance representing ignore file
     */
    public function getIgnoreFileInDirectory(string $path) : IIgnoreFile;

}
