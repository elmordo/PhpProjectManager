<?php

namespace PPM\Router;

use PPM\Service\IService;
use PPM\Service\ServiceTrait;


class Service extends \PPM\Router implements IService
{

	use ServiceTrait;

	public function getDependencies() : array
	{
		return [ "config" ];
	}

	public function initialize()
	{
		# code...
	}

}
