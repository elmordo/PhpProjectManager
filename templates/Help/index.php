PhpProject manager help:
========================

Routes (for more information run with arguments help <route_name>)

<?php
$maxLength = 30;

foreach ($this->routes as $route)
{
    try
    {
        $name = $route->getName();
        $descirption = $route->getDescription();
    }
    catch (\TypeError $e)
    {
        continue;
    }

    // skip route with no name
    if (empty($name))
        continue;

    $filler = "";

    for ($i = 0; $i < ($maxLength - strlen($name)); ++$i)
        $filler .= " ";

    printf("%s%s%s\n", $name, $filler, $descirption);
}
?>

