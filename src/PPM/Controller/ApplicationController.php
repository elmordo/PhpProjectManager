<?php

namespace PPM\Controller;


class ApplicationController extends ApplicationController
{

    const UPLOAD_PATH = "https://github.com/elmordo/PhpProjectManager.git";

    public function updateAction()
    {
        $path = $this->cloneRepo();
    }

    private function cloneRepo()
    {
        $tmpDir = $this->createTempDir();

    }

    public function createTempDir()
    {
        $tmpDir = sys_get_temp_dir();
        $tmpName = tempnam($tmpDir, "ppm");

        if (is_file($tmpName))
            unlink($tmpName);

        mkdir($tmpName);

        return $tmpName;
    }

}
