<?php

namespace PPM\Config;


interface IAdapter
{

    /**
     * load data from store
     * @return ConfigData config data
     * @throws PPM\Config\Adapter\Exception data could not be read
     */
    public function load() : ConfigData;

    /**
     * save data into storage
     * @param ConfigData $data data to save
     * @return IAdapter reference to this instance
     * @throws PPM\Config\Adapter\Exception data can not be written
     */
    public function save(ConfigData $data) : IAdapter;

}
