<?php

namespace PPM\Service;


class ServiceProvider implements IServiceProvider
{

	/**
	 * @var bool if true, service is shared
	 */
	protected $shared = true;

	/**
	 * @var IService shared instance of service
	 */
	protected $instance;

	/**
	 * @var moxed original definition of service
	 */
	protected $definition;

	/**
	 * @var callable service factory function
	 */
	protected $factory;

	/**
	 * @var ServiceManager parent service manager
	 */
	protected $serviceManager;

	/**
	 * @var array set of service dependencies
	 */
	protected $dependencies;

	/**
	 * initialize instance
	 * @param mixed $definition service definition
	 * @param array $dependencies set of service dependencies
	 * @param ServiceManager|null $serviceManager service manager to set
	 */
	public function __construct($definition=null, array $dependencies=[], ServiceManager $serviceManager=null)
	{
		if ($serviceManager) $this->setServiceManager($serviceManager);
		if ($definition) $this->setDefinition($definition);
		$this->dependencies = $dependencies;
	}

	/**
	 * return shared service flag value
	 * @return bool true if service is shared, false otherwise
	 */
	public function isShared() : bool
	{
		return $this->shared;
	}

	/**
	 * get service dependencies
	 * @return array
	 */
	public function getDependencies() : array
	{
		return $this->dependencies;
	}

	/**
	 * set shared flag
	 * @param bool $shared set to true if service should be shared or false otherwise
	 * @return IServiceProvider reference to this instance
	 */
	public function setShared(bool $shared) : IServiceProvider
	{
		$this->shared = $shared;
		return $this;
	}

	/**
	 * set new definition of service
	 * @param mixed $definition service definition
	 * @return IServiceProvider reference to this instance
	 */
	public function setDefinition($definition) : IServiceProvider
	{
		$this->definition = $definition;
		$this->factory = $this->createFactory($definition);
		return $this;
	}

	/**
	 * create new (unique) instance of service
	 * @return IService instance of service
	 */
	public function createInstance() : IService
	{
		$instance = ($this->factory)();
		$instance->setServiceProvider($this)->setServiceManager($this->serviceManager);
		$instance->initialize();
		return $instance;
	}

	/**
	 * return instance of service
	 * instance can be unique or shared (depends on isShared flag)
	 * @return IService instance of service
	 */
	public function getInstance() : IService
	{
		if ($this->shared)
		{
			$instance = $this->getSharedInstance();
		}
		else
		{
			$instance = $this->createInstance();
		}

		return $instance;
	}

	/**
	 * clear shared instance
	 * @return IServiceProvider reference to this instance
	 */
	public function clearInstance() : IServiceProvider
	{
		$this->instance = null;
		return $this;
	}

	/**
	 * set new service manager to instance
	 * @param IServiceManager $manager service manager
	 * @return IServiceProvider reference to this instance
	 */
	public function setServiceManager(IServiceManager $manager) : IServiceProvider
	{
		$this->serviceManager = $manager;
		return $this;
	}

	/**
	 * @return service manager of the provider
	 */
	public function getServiceManager() : IServiceManager
	{
		return $this->serviceManager;
	}

	/**
	 * create factory function for definition
	 * @param mixed $definition definition of service
	 * @return callable
	 */
	protected function createFactory($definition) : callable
	{
		// test type of definition
		if (is_string($definition))
		{
			return $this->createFactoryForStringDefinition($definition);
		}
		else if (is_object($definition))
		{
			return $this->createFactoryForPrototypeDefinition($definition);
		}
		else if (is_callable($definition))
		{
			return $this->createFactoryForCallableDefinition($definition);
		}
		else
		{
			throw new Exception("Invalid service definition", 500);

		}
	}

	/**
	 * return shared instance.
	 * If instance is not created, create new instance is created.
	 * @return IService
	 */
	protected function getSharedInstance() : IService
	{
		if ($this->instance === null)
			$this->instance = $this->createInstance();

		return $this->instance;
	}

	/**
	 * create service factory function for service defined by string (class name)
	 * @param string $definition name of service class
	 * @return callable
	 */
	public function createFactoryForStringDefinition(string $definition) : callable
	{
		$factory = function () use ($definition) : IService
		{
			return new $definition();
		};

		return $factory;
	}

	/**
	 * create service factory function for service defined by prototype
	 * @param object $definition prototype of service
	 * @return callable
	 */
	public function createFactoryForPrototypeDefinition(IService $definition) : callable
	{
		$factory = function () use ($definition) : IService
		{
			return clone $definition;
		};

		return $factory;
	}

	/**
	 * create service factory function for service defined by callable object
	 * @param callable $definition definition
	 * @return callable
	 */
	public function createFactoryForCallableDefinition(callable $definition) : callable
	{
		$factory = function () use ($definition) : IService
		{
			return $definition();
		};

		return $factory;
	}

}

