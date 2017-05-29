<?php

namespace PPM\Vcs\Git;

use PPM\Vcs\IIgnoreFile;


class IgnoreFile implements IIgnoreFile
{

    const LINE_BREAK = "\n";

    private $fileName;

    private $entries = [];

    public function __construct(string $fileName)
    {
        $this->fileName = $fileName;
        $this->reset();
    }

    /**
     * add entry to file
     * if entry exists, do nothing
     * @param string $entry entry to add
     * @return IIgnoreFile reference to this instance
     */
    public function addEntry(string $entry) : IIgnoreFile
    {
        if (!$this->hasEntry($entry))
            $this->entries[] = $entry;

        return $this;
    }

    /**
     * test if entry exists in ignore file
     * @param string $entry entry to test
     * @return boolean true if entry exists, false otherwise
     */
    public function hasEntry(string $entry) : bool
    {
        return in_array($entry, $this->entries);
    }

    /**
     * return list of all entries
     * @return array array of string entries
     */
    public function getEntries() : array
    {
        return $this->entries;
    }

    /**
     * remove entry from file
     * if entry does not exist, do nothing
     * @param string $entry entry to remove
     * @return IIgnoreFile reference to this instance
     */
    public function removeEntry(string $entry) : IIgnoreFile
    {
        $index = array_search($entry, $this->entries);

        if ($index !== false)
            unset($this->entries[$index]);

        return $this;
    }

    /**
     * save ignorefile to disk
     * @return IIgnoreFile reference to this instance
     */
    public function save() : IIgnoreFile
    {
        $content = implode(self::LINE_BREAK, $this->entries);
        file_put_contents($this->fileName, $content);
        return $this;
    }

    /**
     * return file name of ignore file
     * @return string file name
     */
    public function getFileName() : string
    {
        return $this->fileName;
    }

    /**
     * reload data from file
     * @return IIgnoreFile reference to this instance
     */
    public function reset() : IIgnoreFile
    {
        $content = "";

        if (is_file($this->fileName))
        {
            $content = file_get_contents($this->fileName);
        }

        $this->entries = preg_split("/\\r\\n|\\r|\\n/", $content);
    }

}