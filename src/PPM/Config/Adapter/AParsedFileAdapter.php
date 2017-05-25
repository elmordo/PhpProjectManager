<?php

namespace PPM\Config\Adapter;

use PPM\Config\ConfigData;
use PPM\Config\IAdapter;


abstract class AParsedFileAdapter extends AFileAdapter
{

    /**
     * load data from store
     * @return ConfigData config data
     * @throws PPM\Config\Adapter\Exception data could not be read
     */
    public function load() : ConfigData
    {
        $content = $this->getDataFromFile($this->fileName);
        $dataArray = $this->decodeData($content);

        return new ConfigData($dataArray);
    }

    /**
     * save data into storage
     * @param ConfigData $data data to save
     * @return IAdapter reference to this instance
     * @throws PPM\Config\Adapter\Exception data can not be written
     */
    public function save(ConfigData $data) : IAdapter
    {
        $dataArray = $data->toArray();
        $content = $this->encodeData($dataArray);
        $this->putDataToFile($this->fileName, $content);

        return $this;
    }

    /**
     * encode data from array to string
     * @param array $data data to encode
     * @return string encoded data
     */
    public abstract function encodeData(array $data) : string;

    /**
     * decode data from string to array
     * @param string $data data to decode
     * @return array decoded data
     */
    public abstract function decodeData(string $data) : array;

    /**
     * load data from file
     * @param string $fileName name of file
     * @return string file content
     */
    protected function getDataFromFile(string $fileName) : string
    {
        if (!is_file($fileName))
            throw new Exception("Could not to open file '$fileName'.");

        $content = file_get_contents($fileName);
        return $content;
    }

    /**
     * put encoded data into file
     * @param string $fileName name of target file
     * @param string $data encoded data
     */
    protected function putDataToFile(string $fileName, string $data)
    {
        file_put_contents($fileName, $data);
    }

}