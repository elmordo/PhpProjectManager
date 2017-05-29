<?php

namespace PPM\Vcs;


class Factory
{

    const VCS_GIT = "git";

    /**
     * create VCS manipulator instance
     * @param string $name name of VSC
     * @param string $basePath base path
     * @return IVcs instance of VCS
     */
    public function createVcs(string $name, string $basePath) : IVcs
    {
        $instance = null;

        switch ($name)
        {
            case self::VCS_GIT:
                $instance = new Git\Git();
                break;

            default:
                throw new Exception("VCS '$name' is not supported", 400);
        }

        $instance->setBasePath($basePath);
        return $instance;
    }

}