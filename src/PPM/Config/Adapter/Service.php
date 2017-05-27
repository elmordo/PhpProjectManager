<?php

namespace PPM\Config\Adapter;

use PPM\Config\IAdapter;
use PPM\Service\IService;
use PPM\Service\ServiceTrait;


class Service implements IService
{

    use ServiceTrait;

    protected $adapter;

    public function getDependencies() : array
    {
        return [];
    }

    public function initialize()
    {
    }

    public function getAdapter() : IAdapter
    {
        return $this->adapter;
    }

    public function setAdapter(IAdapter $value) : Service
    {
        $this->adapter = $value;
        return $this;
    }

}