<?php

namespace PPM\Service;


interface IServiceProvider
{

	/**
	 * return shared service flag value
	 * @return bool true if service is shared, false otherwise
	 */
	public function isShared() : bool;

	/**
	 * set new defintiion of service
	 * @param mixed $definition service definition
	 * @return IServiceProvider reference to this instance
	 */
	public function setDefinition($definition) : IServiceProvider;

	/**
	 * set shared flag
	 * @param bool $shared set to true if service should be shared or false otherwise
	 * @return IServiceProvider reference to this instance
	 */
	public function setShared(bool $shared) : IServiceProvider;

	/**
	 * create new (unique) instance of service
	 * @return IService instance of service
	 */
	public function createInstance() : IService;

	/**
	 * return instance of service
	 * instance can be unique or shared (depends on isShared flag)
	 * @return IService instance of service
	 */
	public function getInstance() : IService;

	/**
	 * clear shared instance
	 * @return IServiceProvider reference to this instance
	 */
	public function clearInstance() : IServiceProvider;

	/**
	 * set new service manager to instance
	 * @param IServiceManager $manager service manager
	 * @return IServiceProvider reference to this instance
	 */
	public function setServiceManager(IServiceManager $manager) : IServiceProvider;

	/**
	 * @return service manager of the provider
	 */
	public function getServiceManager() : IServiceManager;

}
