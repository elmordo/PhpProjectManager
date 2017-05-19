<?php

function joinPath(...$parts)
{
    return join(DIRECTORY_SEPARATOR, $parts);
}

require_once joinPath(__DIR__, "PPM", "Loader.php");


$loader = new PPM\Loader();
$loader->registerLoader();
$loader->registerDir(joinPath(__DIR__));

$application = new \PPM\Application();

try
{
    $application->handle($argv);
}
catch (\Exception $error)
{

}
