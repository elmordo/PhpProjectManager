<?php

namespace PPM;


class Config
{

	protected $config;

	public function __construct(array $data)
	{
		$this->config = new Config\ConfigData($data);
	}

}
