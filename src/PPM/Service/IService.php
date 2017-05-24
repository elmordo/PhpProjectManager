<?php

namespace PPM\Service;


interface IService extends IServiceManagerAware
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

}