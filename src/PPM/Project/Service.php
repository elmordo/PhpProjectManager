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
	}

}
