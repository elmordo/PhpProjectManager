<?php

namespace PPM\View;

use PPM\Service\IService;
use PPM\Service\ServiceTrait;


class Service extends \PPM\View implements IService
{

    use ServiceTrait;

    public function getDependencies() : array
    {
        return [];
    }

    public function initialize()
    {
    }

}