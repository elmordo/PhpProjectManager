<?php

namespace PPM;

use PPM\Project\Module;


class Project
{

	protected $moduleManager;

	public function __construct()
	{
		$this->moduleManager = new Module\Manager();
	}

	public function getModuleManager() : Module\Manager
	{
		return $this->moduleManager;
	}

}