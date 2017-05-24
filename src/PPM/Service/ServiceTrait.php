<?php

namespace PPM\Service;


trait ServiceTrait
{

	/**
	 * @var IServiceProvider current service provider
	 */
	protected $serviceProvider = null;

	/**
	 * @var IServiceManager current service manager
	 */
	protected $serviceManager = null;

	/**
	 * return current service provider
	 * @return IServiceProvider
	 */
	public function getServiceProvider() : IServiceProvider
	{
		return $this->serviceProvider;
	}

	/**
	 * set new service provider
	 * @param IServiceProvider $provider new provider to set
	 * @return IService reference to current instance
	 */
	public function setServiceProvider(IServiceProvider $provider) : IService
	{
		$this->serviceProvider = $provider;
		return $this;
	}

	/**
	 * get service manager
	 * @return IServiceManager current service manager
	 */
	public function getServiceManager() : IServiceManager
	{
		return $this->serviceManager;
	}

	/**
	 * set new service manager
	 * @param IServiceManager $manager new service manager to set
	 * @return IService reference to this instance
	 */
	public function setServiceManager(IServiceManager $manager) : IServiceManagerAware
	{
		$this->serviceManager = $manager;
		return $this;
	}

}