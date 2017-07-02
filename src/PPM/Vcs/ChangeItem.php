<?php

namespace PPM\Vcs;


class ChangeItem
{

    const CHANGE_NEW = 1;
    const CHANGE_MODIFIED = 2;
    const CHANGE_DELETED = 3;
    const CHANGE_RENAMED = 4;
    const CHANGE_UNTRACKED = 5;
    const CHANGE_UNKNOWN = 99;

    const STR_NEW = "NEW";
    const STR_MODIFIED = "MODIFIED";
    const STR_DELETED = "DELETED";
    const STR_RENAMED = "RENAMED";
    const STR_UNTRACKED = "UNTRACKED";
    const STR_UNKNOWN = "UNKNOWN";

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

    public function getChangeTypeAsStr() : string
    {
        switch ($this->changeType)
        {
            case self::CHANGE_NEW:
                return self::STR_NEW;
                break;

            case self::CHANGE_MODIFIED:
                return self::STR_MODIFIED;
                break;

            case self::CHANGE_DELETED:
                return self::STR_DELETED;
                break;

            case self::CHANGE_RENAMED:
                return self::STR_RENAMED;
                break;

            case self::CHANGE_UNTRACKED:
                return self::STR_UNTRACKED;
                break;

            default:
                return self::STR_UNKNOWN;
        }
    }

}