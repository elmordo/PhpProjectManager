<?php

namespace PPM\Asset;

use PPM\Project\Module\Module;
use PPM\Vcs\IVcs;


class Builder
{

    protected $targetPath;

    protected $vcs;

    public function build(Module $module) : Builder
    {
        $moduleConfig = $module->getConfig();
        $modulePath = $module->getPath();

        $assetsRoot = joinPath($modulePath, $moduleConfig->getStrict("assets_dir"));

        try
        {
            $this->buildAssetsDir($assetsRoot, ".");
        }
        catch (Exception $e)
        {
            // no assets dir found
        }

        return $this;
    }

    /**
     * get 'targetPath' value
     * @return string
     */
    public function getTargetPath() : string
    {
        return $this->targetPath;
    }

    /**
     * set new 'targetPath' value
     * @param string $value new value of 'targetPath'
     * @return Builder
     */
    public function setTargetPath(string $value) : Builder
    {
        $this->targetPath = $value;
        return $this;
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
     * @return Builder
     */
    public function setVcs(IVcs $value) : Builder
    {
        $this->vcs = $value;
        return $this;
    }

    private function buildAssetsDir(string $sourceDir, string $relativeTarget)
    {
        // if no source dir exists, throw exception
        if (!is_dir($sourceDir))
            throw new Exception("Assets source path '$path' not found.", 404);

        // iterate over directory
        $dir = Dir($sourceDir);

        while ($entry = $dir->read())
        {
            // skip . and ..
            if ($entry == "." || $entry == "..")
                continue;

            // prepare paths
            $sourceItem = joinPath($sourceDir, $entry);
            $targetRelative = joinPath($relativeTarget, $entry);
            $targetAbsoulte = joinPath($this->targetPath, $targetRelative);

            //var_dump($sourceItem, $targetRelative, $targetAbsoulte);
            if (is_dir($sourceItem))
            {
                $this->prepareDirectory($targetAbsoulte);
                $this->buildAssetsDir($sourceItem, $targetRelative);
            }
            else if (is_file($sourceItem))
            {
                $this->buildFile($sourceItem, $targetAbsoulte);
            }
        }
    }

    /**
     * recursively create directory
     * @param string $path [description]
     * @return [type] [description]
     */
    private function prepareDirectory(string $path)
    {
        if (!is_dir($path))
        {
            // directory does not exists, test for parent
            $parentDir = dirname($path);
            $this->prepareDirectory($parentDir);
            mkdir($path);
        }
    }

    /**
     * make symlink from source to target
     * if source exists, it will be deleted
     * @param string $sourcePath [description]
     * @param string $targetPath [description]
     * @return [type] [description]
     */
    private function buildFile(string $sourcePath, string $targetPath)
    {
        if (is_file($targetPath))
            unlink($targetPath);

        symlink($sourcePath, $targetPath);
        $this->addToIgnore($targetPath);
    }

    /**
     * add entry to VCS ignore file
     * @param string $entry [description]
     */
    private function addToIgnore(string $entry)
    {
        $fileName = basename($entry);
        $directoryName = dirname($entry);

        $ignoreFile = $this->vcs->getIgnoreFileInDirectory($directoryName);
        $ignoreFile->addEntry($fileName)->save();
    }

}