<?php

namespace PPM\Vcs\Git;

use PPM\Vcs\IVcs;
use PPM\Vcs\IIgnoreFile;
use PPM\Vcs\ChangeItem;


class Git implements IVcs
{

    const CMD_PULL = "git pull";

    const CMD_ADD = "git add";

    const CMD_COMMIT = "git commit";

    const CMD_PUSH = "git push";

    const CMD_STATUS = "git status --porcelain";

    protected $basePath = ".";

    /**
     * get current base path
     * @return string base path
     */
    public function getBasePath() : string
    {
        return $this->basePath;
    }

    /**
     * set new base path
     * @param string $basePath new base path
     * @return IVcs reference to this instance
     */
    public function setBasePath(string $basePath) : IVcs
    {
        $this->basePath = $basePath;
        return $this;
    }

    /**
     * test if repository is initialized in base path
     * @return boolean true if initialized, false otherwise
     */
    public function isInitialized() : bool
    {
        $repoDir = joinPath($this->basePath, ".git");
        return is_dir($repoDir) || is_file($repoDir);
    }

    /**
     * initialize repository in base path
     * @return IVcs reference to this instance
     */
    public function initialize() : IVcs
    {
        throw new Exception("Not implemented", 500);
    }

    /**
     * get list of changed files
     * @return array list of changed files
     */
    public function getChanges() : array
    {
        $changeRows = $this->getStatusItems();
        return $this->parseChanges($changeRows);
    }

    /**
     * add changes into next commit
     * @param string $pattern pattern to filter items
     */
    public function add(string $pattern) : IVcs
    {
        $cmd = "cd '" . $this->basePath . "' && " . self::CMD_ADD . " " . $pattern;
        passthru($cmd);
        return $this;
    }

    /**
     * commit all added changes
     * @param string|null $message commit message
     * @return IVcs reference to this instance
     */
    public function commit(string $message=null) : IVcs
    {
        $messageArg = "";

        if ($message)
            $messageArg = " -m '" . $message . "'";

        $cmd = "cd '" . $this->basePath . "' && " . self::CMD_COMMIT . $messageArg;
        passthru($cmd);
        return $this;
    }

    /**
     * push data to server
     * @return IVcs reference to this instance
     */
    public function push() : IVcs
    {
        $cmd = "cd '" . $this->basePath . "' && " . self::CMD_PUSH;
        passthru($cmd);
        return $this;
    }

    /**
     * pull data from the server
     * @return IVcs reference to this instance
     */
    public function pull() : IVcs
    {
        $cmd = "cd '" . $this->basePath . "' && " . self::CMD_PULL;
        passthru($cmd);
        return $this;
    }

    /**
     * load or create empty ignore file in given directory
     * @param string $path path to directory
     * @return IIgnoreFile instance representing ignore file
     */
    public function getIgnoreFileInDirectory(string $path) : IIgnoreFile
    {
        $ignorePath = joinPath($path, ".gitignore");
        return new IgnoreFile($ignorePath);
    }

    private function getStatusItems() : array
    {
        $process = new \PPM\Process(self::CMD_STATUS, $this->basePath);
        $output = $process->read();
        $process->close();

        $splittedData = explode(PHP_EOL, $output);
        $result = [];

        foreach ($splittedData as $item)
        {
            $item = trim($item);

            if (!empty($item)) $result[] = $item;
        }

        return $result;
    }

    private function parseChanges(array $items) : array
    {
        $result = [];

        foreach ($items as $item)
        {
            $result[] = $this->parseChange($item);
        }

        return $result;
    }

    private function parseChange(string $item)
    {
        list($strType, $file) = explode(" ", $item, 2);

        switch ($strType)
        {
            case "A":
                $intType = ChangeItem::CHANGE_NEW;
                break;

            case "M":
                $intType = ChangeItem::CHANGE_MODIFIED;
                break;

            case "D":
                $intType = ChangeItem::CHANGE_DELETED;
                break;

            case "R":
                $intType = ChangeItem::CHANGE_RENAMED;
                break;

            case "??":
                $intType = ChangeItem::CHANGE_UNTRACKED;
                break;

            default:
                $intType = ChangeItem::CHANGE_UNKNOWN;
        }

        return new ChangeItem(trim($file), $intType);
    }

}
