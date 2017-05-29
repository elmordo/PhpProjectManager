<?php

namespace PPM\Vcs;


interface IVcs
{

    /**
     * get current base path
     * @return string base path
     */
    public function getBasePath() : string;

    /**
     * set new base path
     * @param string $basePath new base path
     * @return IVcs reference to this instance
     */
    public function setBasePath(string $basePath) : IVcs;

    /**
     * test if repository is initialized in base path
     * @return boolean true if initialized, false otherwise
     */
    public function isInitialized() : bool;

    /**
     * initialize repository in base path
     * @return IVcs reference to this instance
     */
    public function initialize() : IVcs;

    /**
     * get list of changed files
     * @return array list of changed files
     */
    public function getChangeds() : array;

    /**
     * add changes into next commit
     * @param string $pattern pattern to filter items
     */
    public function add(string $pattern) : IVcs;

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
