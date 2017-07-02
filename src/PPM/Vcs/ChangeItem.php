<?php

namespace PPM\Vcs;


class ChangeItem
{

    const CHANGE_NEW = 1;
    const CHANGE_MODIFIED = 2;
    const CHANGE_DELETED = 3;
    const CHANGE_UNKNOWN = 99;

    private $filename;

    private $changeType;

    public function __construct(string $filename, int $changeType)
    {
        $this->filename = $filename;
        $this->changeType = $changeType;
    }

    public function setFilename(string $value)
    {
        $this->filename = $value;
    }

    public function getFilename() : string
    {
        return $this->filename;
    }

    public function setChangeType(int $value)
    {
        $this->changeType = $value;
    }

    public function getChangeType() : int
    {
        return $this->changeType;
    }

}