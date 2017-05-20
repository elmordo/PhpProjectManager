<?php

namespace PPM\Service;


interface IServiceManager
{

	/**
	 * Test if service is in manager
	 * @param string $name name of the service
	 * @return bool true if service is registered, false otherwise
	 */
	public function hasService(string $name) : bool;

	/**
	 * get service instance
	 * @param string $name name of service
	 * @return IService service instance
	 * @throws \PPM\Service\Exception service not found
	 */
	public function getService(string $name) : IService;

	/**
	 * register new service into manager
	 * @param string $name name of new service
	 * @param IServiceProvider $provider service provider
	 * @return IServiceManager
	 */
	public function setService(string $name, IServiceProvider $provider) : IServiceManager;

}