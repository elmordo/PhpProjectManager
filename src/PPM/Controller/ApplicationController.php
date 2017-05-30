<?php

namespace PPM\Controller;


class ApplicationController extends AController
{

    const REPOSITORY_URL = "https://github.com/elmordo/PhpProjectManager.git";

    const CMD_CLONE = "git clone {url} {dir}";

    const CMD_CHECKOUT = "cd {dir} && git checkout {version}";

    const CMD_BUILD = "cd {dir} && php build.php";

    public function updateAction()
    {
        $application = $this->getServiceManager()->getService("application");
        $io = $this->getServiceManager()->getService("io");

        $appPath = $application->getBasePath();

        $repoPath = $this->cloneRepo();
        $newVersion = $this->checkoutRelease($repoPath);
        $currentVersion = $this->getVersion(joinPath("phar://" . $appPath, "ppm.phar"));

        $io->write(sprintf("Current version is '%s', new version is '%s'.", $currentVersion, $newVersion));
        $message = "Do you want to update the PPM?";

        try
        {
            if (!$io->askYesNoAbort($message)) throw new \Exception();
        }
        catch (\Exception $e)
        {
            $io->write("Update was aborted.");
            return;
        }

        $this->build($repoPath);
        $this->deploy($repoPath, $appPath);
        $this->cleanup($repoPath);
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
    private function getVersion(string $path) : string
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

    private function build(string $repoPath)
    {
        $cmd = strtr(self::CMD_BUILD, [ "{dir}" => $repoPath ]);
        passthru($cmd);
    }

    private function deploy(string $repoPath, string $appDir)
    {
        $sourceFile = joinPath($repoPath, "build/ppm.phar");
        $targetFile = joinPath($appDir, "ppm.phar");

        if (is_file($targetFile))
            unlink($targetFile);

        copy($sourceFile, $targetFile);
    }

    /**
     * delete files and directories
     * @param string $path path to delete
     */
    private function cleanup(string $path)
    {
        if (is_dir($path))
        {
            $dir = dir($path);

            while ($entry = $dir->read())
            {
                if ($entry != "." && $entry != "..")
                {
                    $entryPath = joinPath($path, $entry);
                    $this->cleanup($entryPath);
                }
            }

            rmdir($path);
        }
        else
        {
            unlink($path);
        }
    }

}
