<?php

namespace PPM\Service;


interface IService
{

	/**
	 * return set of service dependencies
	 * @return array
	 */
	public function getDependencies() : array;

	/**
	 * initialize service
	 */
	public function initialize();

	/**
	 * return current service provider
	 * @return IServiceProvider
	 */
	public function getServiceProvider() : IServiceProvider;

	/**
	 * set new service provider
	 * @param IServiceProvider $provider new provider to set
	 * @return IService reference to current instance
	 */
	public function setServiceProvider(IServiceProvider $provider) : IService;

	/**
	 * get service manager
	 * @return IServiceManager current service manager
	 */
	public function getServiceManager() : IServiceManager;

	/**
	 * set new service manager
	 * @param IServiceManager $manager new service manager to set
	 * @return IService reference to this instance
	 */
	public function setServiceManager(IServiceManager $manager) : IService;

}