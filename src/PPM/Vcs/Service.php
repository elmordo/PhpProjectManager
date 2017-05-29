<?php

namespace PPM\Vcs;

use PPM\Service\IService;
use PPM\Service\ServiceTrait;


class Service implements IService
{

    use ServiceTrait;

    protected $vcs;

    public function initialize()
    {
        $config = $this->getServiceManager()->getService("config");
        $vcsName = $config->getValue("vcs", Factory::VCS_GIT);

        $factory = new Factory();

        $this->vcs = $factory->createVcs($vcsName, ".");
    }

    public function getDependencies() : array
    {
        return [ "config" ];
    }

    /**
     * get 'vcs' value
     * @return IVcs
     */
    public function getVcs() : IVcs
    {
        return $this->vcs;
    }

    /**
     * set new 'vcs' value
     * @param IVcs $value new value of 'vcs'
     * @return Service
     */
    public function setVcs(IVcs $value) : Service
    {
        $this->vcs = $value;
        return $this;
    }

}
