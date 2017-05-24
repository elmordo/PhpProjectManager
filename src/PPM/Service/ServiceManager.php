<?php

namespace PPM\Service;


class ServiceManager implements IServiceManager
{

	protected $services = [];

	/**
	 * Test if service is in manager
	 * @param string $name name of the service
	 * @return bool true if service is registered, false otherwise
	 */
	public function hasService(string $name) : bool
	{
		return isset($this->services[$name]);
	}

	/**
	 * get service instance
	 * @param string $name name of service
	 * @return IService service instance
	 * @throws \PPM\Service\Exception service not found
	 */
	public function getService(string $name) : IService
	{
		if (!$this->hasService($name))
			throw new \DomainException("Service '$name' is not registered", 404);

		return $this->services[$name]->getInstance();
	}

	public function getServices() : array
	{
		return $this->services;
	}

	/**
	 * register new service into manager
	 * @param string $name name of new service
	 * @param IServiceProvider $provider service provider
	 * @return IServiceManager
	 */
	public function setService(string $name, IServiceProvider $provider) : IServiceManager
	{
		if ($this->hasService($name))
			throw new \DomainException("Service '$name' is already registered", 409);

		// test dependencies
		foreach ($provider->getDependencies() as $dependency)
		{
			if (!isset($this->services[$dependency]))
				throw new Exception("Dependency '$dependency' is not satisified.", 404);
		}

		$provider->setServiceManager($this);
		$this->services[$name] = $provider;

		return $this;
	}

}