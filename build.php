<?php

function joinPath(...$parts)
{
    return join(DIRECTORY_SEPARATOR, $parts);
}

define("DEFAULT_BUILD_DIR", joinPath(__DIR__, "build"));

function printLines(...$lines)
{
    foreach ($lines as $line)
    {
        echo $line . PHP_EOL;
    }
}

if (count($argv) < 2)
{
    printLines(sprintf("No build directory passed. Using '%s' as default", DEFAULT_BUILD_DIR));
    $buildDir = DEFAULT_BUILD_DIR;
}
else
{
    $buildDir = $argv[1];
}

if (!is_dir($buildDir))
{
    // create build directory
    // TODO: multiplatform solution
    system(sprintf("mkdir -p '%s'", $buildDir));
}

// setup environment
ini_set("phar.readonly", 0);

if (ini_get("phar.readonly"))
{
    printLines("Unable to setup PHAR extenstion.", "Set 'phar.readonly' to 0 manualy and run build again.");
    exit(1);
}

// setup some variables
$targetFile = joinPath($buildDir, "ppm.phar");
$srcDir = joinPath(__DIR__, "src");
$entryPoint = joinPath("main.php");

// build application to phar
$pharApp = new Phar($targetFile, FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::KEY_AS_FILENAME);
$pharApp->buildFromDirectory($srcDir);

$stub = $pharApp->createDefaultStub($entryPoint);

$pharApp->setStub($stub);
$pharApp->compressFiles(Phar::GZ);
