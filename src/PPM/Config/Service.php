<?php

namespace PPM\Config;


class Service extends \PPM\Config implements \PPM\Service\IService
{

	use \PPM\Service\ServiceTrait;

	public function __construct(array $data=[])
	{
		parent::__construct($data);
	}

	public function getDependencies() : array
	{
		return [];
	}

	public function initialize()
	{
	}

}