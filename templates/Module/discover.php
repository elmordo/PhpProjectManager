<?php
if (!$this->somethingFound)
{
    echo "No new module found\n";
    return;
}
?>

New initialized modules
=======================

<?php
foreach ($this->newModules as $moduleName)
    echo "* " . $moduleName . "\n";
?>

