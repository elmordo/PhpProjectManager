PhpProject manager help:
========================

Routes (for more information run with argument help <route_name>)

<?php
$maxLength = 30;

foreach ($this->routes as $route)
{
    $name = $route->getName();
    $descirption = $route->getDescription();
    $filler = "";

    for ($i = 0; $i < ($maxLength - strlen($name)); ++$i)
        $filler .= " ";

    printf("%s%s%s", $name, $filler, $descirption);
}
?>

