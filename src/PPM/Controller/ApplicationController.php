<?php

namespace PPM\Controller;


class ApplicationController extends AController
{

    //const REPOSITORY_URL = "https://github.com/elmordo/PhpProjectManager.git";
    const REPOSITORY_URL = "/home/petr/Projekty/PhpProjectManager";

    const CMD_CLONE = "git clone {url} {dir}";

    const CMD_CHECKOUT = "cd {dir} && git checkout {version}";

    public function updateAction()
    {
        $repoPath = $this->cloneRepo();
        $release = $this->checkoutRelease($repoPath);
    }

    private function cloneRepo() : string
    {
        $tmpDir = $this->createTempDir();

        $cmd = $this->prepareCloneCommand($tmpDir);
        passthru($cmd);

        return $tmpDir;
    }

    private function createTempDir() : string
    {
        $tmpDir = sys_get_temp_dir();
        $tmpName = tempnam($tmpDir, "ppm");

        if (is_file($tmpName))
            unlink($tmpName);

        mkdir($tmpName);

        return $tmpName;
    }

    private function prepareCloneCommand(string $targetDir) : string
    {
        $params = [ "{url}" => self::REPOSITORY_URL, "{dir}" => $targetDir ];
        $cmd = strtr(self::CMD_CLONE, $params);
        return $cmd;
    }

    /**
     * checkout last release and return version
     * @param string $repoPath [description]
     * @return [type] [description]
     */
    private function checkoutRelease(string $repoPath) : string
    {
        $release = $this->getVersion($repoPath);
        $command = $this->prepareCheckoutCommand($repoPath, $release);

        passthru($command);

        return $release;
    }

    private function prepareCheckoutCommand(string $repoPath, string $version) : string
    {
        $params = [ "{dir}" => $repoPath, "{version}" => $version ];
        $cmd = strtr(self::CMD_CHECKOUT, $params);

        return $cmd;
    }

    /**
     * load version from VERSION file
     * @param string $path path where VERSION is located
     * @return string version number
     */
    public function getVersion(string $path) : string
    {
        try
        {
            $version = file_get_contents(joinPath($path, "VERSION"));
        }
        catch (\Exception $e)
        {
            throw new \RuntimeException("Unable to determine version number of downloaded repository");
        }

        return $version;
    }

}
