<?php

namespace PPM;

use PPM\Project\Module;


class Project
{

	protected $modules;

	public function __construct()
	{
		$this->modules = new Module\Manager();
	}

	public function getModuleManager() : Module\Manager
	{
		return $this->modules;
	}

}