<?php

namespace PPM\Router;


class Route implements IRoute
{

	/**
	 * @var array route parameters
	 */
	protected $params = [];

	/**
	 * @var string|null name of route
	 */
	protected $name;

	/**
	 * @var array default values
	 */
	protected $defaults = [];

	/**
	 * @var Parser argument parser
	 */
	protected $parser;

	/**
	 * initialize instance
	 */
	public function __construct()
	{
		$this->parser = new Parser();
	}

	/**
	 * setup route from array
	 * @param array $options set of options
	 * @return IRoute reference to this instance
	 */
	public function setupFromArray(array $options) : IRoute
	{
		foreach ($options as $name => $value)
		{
			switch ($name)
			{
				case "name":
					// set route name
					$this->setName($value);
					break;

				case "defaults":
					$this->setDefaults((array)$value);
					break;

				case "definition":
					$this->parser->setup([ "arguments" =>  $value ]);
					break;
			}
		}

		return $this;
	}

	/**
	 * return name of the route
	 * @return string route name
	 */
	public function getName() : string
	{
		return $this->name;
	}

	/**
	 * set new name to route
	 * @param string $value new name
	 * @return Route reference to this
	 */
	public function setName(string $value) : Route
	{
		$this->name = $value;
		return $this;
	}

	/**
	 * return default values of route
	 * @return array route's default values
	 */
	public function getDefaults() : array
	{
		return $this->defaults;
	}

	/**
	 * set new default values for route
	 * @param array $value default values for route
	 * @return Route reference to this instance
	 */
	public function setDefaults(array $value) : Route
	{
		$this->defaults = $value;
		return $this;
	}

	/**
	 * test arguments againts the route
	 * @param array $args set of arguments
	 * @return bool true if arguments match
	 */
	public function match(array $args) : bool
	{
		$this->reset();

		try
		{
			$parsedData = $this->parser->parse($args);
			$this->params = array_merge($this->defaults, $parsedData);
		}
		catch (Parser\Exception $e)
		{
			return false;
		}

		return true;
	}

	/**
	 * return parsed controller
	 * @return string controller name
	 */
	public function getController() : string
	{
		return $this->params["controller"];
	}

	/**
	 * return parsed action
	 * @return string action name
	 */
	public function getAction() : string
	{
		return $this->params["action"];
	}

	/**
	 * return parsed parameters
	 * @return array set of parameters
	 */
	public function getParams() : array
	{
		return $this->params;
	}

	/**
	 * return parameter value defined by name
	 * @param string $name name of parameter
	 * @param string|null $defaultValue default value if parameters was not found
	 * @return string parameter value
	 */
	public function getParam(string $name, $defaultValue=null) : string
	{
		return $this->params[$name] ?? null;
	}

	/**
	 * reset parsed arguments
	 */
	protected function reset()
	{
		$this->params = $this->defaults;
	}

}
