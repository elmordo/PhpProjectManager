<?php

namespace PPM\Project;

use PPM\Service\IService;
use PPM\Service\ServiceTrait;


class Service extends \PPM\Project implements IService
{

	use ServiceTrait;

	public function getDependencies() : array
	{
		return [ "config" ];
	}

	public function initialize()
	{
		$this->loadModules();
	}

	private function loadModules()
	{
		// setup explorer
		$application = $this->getServiceManager()->getService("application");
		$config = $this->getServiceManager()->getService("config");
		$basePath = $application->getBasePath();
		$moduleDir = $config->getStrict("module_dir");
		die(var_dump($moduleDir));
		$modulePath = joinPath($basePath, $moduleDir);

		$explorerClass = $config->getStrict("module_explorer");

		// setup explorer
		$explorer = new $explorerClass;
		$explorer->addDirectory($modulePath);

		$modules = $explorer->explore();

		foreach ($modules as $module)
			$this->modules->addModule($module);
	}

}
