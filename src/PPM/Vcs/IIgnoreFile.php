<?php

namespace PPM\Vcs;


interface IIgnoreFile
{

    /**
     * add entry to file
     * if entry exists, do nothing
     * @param string $entry entry to add
     * @return IIgnoreFile reference to this instance
     */
    public function addEntry(string $entry) : IIgnoreFile;

    /**
     * test if entry exists in ignore file
     * @param string $entry entry to test
     * @return boolean true if entry exists, false otherwise
     */
    public function hasEntry(string $entry) : bool;

    /**
     * return list of all entries
     * @return array array of string entries
     */
    public function getEntries() : array;

    /**
     * remove entry from file
     * if entry does not exist, do nothing
     * @param string $entry entry to remove
     * @return IIgnoreFile reference to this instance
     */
    public function removeEntry(string $entry) : IIgnoreFile;

    /**
     * save ignorefile to disk
     * @return IIgnoreFile reference to this instance
     */
    public function save() : IIgnoreFile;

    /**
     * return file name of ignore file
     * @return string file name
     */
    public function getFileName() : string;

    /**
     * reload data from file
     * @return IIgnoreFile reference to this instance
     */
    public function reset() : IIgnoreFile;

}
