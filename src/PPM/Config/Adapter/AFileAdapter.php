<?php

namespace PPM\Config\Adapter;

use PPM\Config\ConfigData;
use PPM\Config\IAdapter;


abstract class AFileAdapter implements IAdapter
{

    /**
     * @var string name of data source file
     */
    private $fileName;

    /**
     * get current name of source file
     * @return string name of file
     */
    public function getFileName() : string
    {
        return $this->fileName;
    }

    /**
     * set new name of data source file
     * @param string $value name of source file
     * @return AFileAdapter reference to this instance
     */
    public function setFileName(string $value) : AFileAdapter
    {
        $this->fileName = $value;
        return $this;
    }

    /**
     * load data from store
     * @return ConfigData config data
     * @throws PPM\Config\Adapter\Exception data could not be read
     */
    public abstract function load() : ConfigData;

    /**
     * save data into storage
     * @param ConfigData $data data to save
     * @return IAdapter reference to this instance
     * @throws PPM\Config\Adapter\Exception data can not be written
     */
    public abstract function save(ConfigData $data) : IAdapter;

    /**
     * test if file exists
     * @return AFileAdapter reference to this instance
     * @throws Exception file does not exist
     */
    protected function assertFileExists() : AFileAdapter
    {
        $fileName = $this->fileName;

        if (!is_file($fileName))
            throw new Exception("File '$fileName' does not exist. ", 404);

        return $this;
    }

}
