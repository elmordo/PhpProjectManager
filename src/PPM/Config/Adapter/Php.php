<?php

namespace PPM\Config\Adapter;

use PPM\Config\ConfigData;
use PPM\Config\IAdapter;


class Php extends AFileAdapter
{

    /**
     * load data from store
     * @return ConfigData config data
     * @throws PPM\Config\Adapter\Exception data could not be read
     */
    public function load() : ConfigData
    {
        $fileName = $this->assertFileExists()->getFileName();
        $data = include $fileName;

        return new ConfigData($data);
    }

    /**
     * save data into storage
     * @param ConfigData $data data to save
     * @return IAdapter reference to this instance
     * @throws PPM\Config\Adapter\Exception data can not be written
     */
    public function save(ConfigData $data) : IAdapter
    {
        $dataArr = $data->toArray();
        $serialized = var_export($dataArr, true);

        $content = sprintf("<?php return %s;", $serialized);

        file_put_contents($this->getFileName(), $content);
    }

}
