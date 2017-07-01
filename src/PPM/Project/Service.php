<?php

namespace PPM\Project;

use PPM\Service\IService;
use PPM\Service\ServiceTrait;


class Service extends \PPM\Project implements IService
{

	use ServiceTrait;

	public function getDependencies() : array
	{
		return [ "config", "vcs", "application" ];
	}

	public function initialize()
	{
		$this->loadDefaultModuleConfig();
		$explorer = $this->initializeModuleExplorer();
		$this->moduleManager->setModuleExplorer($explorer);
		$this->loadConfiguredModules();
	}

	private function loadDefaultModuleConfig()
	{
		// setup default global module config
		$config = $this->getServiceManager()->getService("config");
		$configPath = $config->getStrict("resources")->getStrict("defaultConfigs")->getStrict("module");

		$adapterFactory = new \PPM\Config\Adapter\Factory();
		$adapter = $adapterFactory->createAdapter($configPath);
		$this->moduleManager->setDefaultGlobalConfig($adapter->load());
	}

	private function loadConfiguredModules()
	{
		$config = $this->getServiceManager()->getService("config");
		$moduleNames = (array)$config->modules->toArray();
		$moduleManager = $this->getModuleManager();

		foreach ($moduleNames as $name)
		{
			$this->moduleManager->addModuleByName($name);
		}
	}

	private function initializeModuleExplorer() : Module\IModuleExplorer
	{
		$config = $this->getServiceManager()->getService("config");
		$explorerClass = $config->getStrict("module_explorer");
		$explorer = new $explorerClass;
		$explorer->addDirectory($config->getStrict("module_dir"));

		return $explorer;
	}

}
