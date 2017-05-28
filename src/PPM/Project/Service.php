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
		$config = $this->getServiceManager()->getService("config");
		$explorerClass = $config->getStrict("module_explorer");
		$explorer = new $explorerClass;
		$explorer->addDirectory($config->getStrict("module_dir"));

		$this->moduleManager->setModuleExplorer($explorer);
		$this->loadConfiguredModules();
	}

	public function searchForModules($value='')
	{
		# code...
	}

	private function loadConfiguredModules()
	{
		$config = $this->getServiceManager()->getService("config");
		$moduleNames = (array)$config->modules->toArray();
		$moduleManager = $this->getModuleManager();

		foreach ($moduleNames as $name)
		{
			var_dump($name);
			$this->moduleManager->addModuleByName($name);
		}
	}

}
