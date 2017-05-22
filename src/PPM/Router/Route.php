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
	 * @var string default controller name
	 */
	protected $defaultController = "help";

	/**
	 * @var string default action name
	 */
	protected $defaultAction = "index";

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

				case "controller":
					// set default controller
					$this->params["controller"] = $value;
					break;

				case "action":
					// set default action
					$this->params["action"] = $value;
					break;

				case "definition":
					$this->parser->setup($value);
					break;
			}
		}

		return $this;
	}

	/**
	 * return name of the route
	 * @return string route name
	 */
	public function getName()
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
	 * test arguments againts the route
	 * @param array $args set of arguments
	 * @return bool true if arguments match
	 */
	public function match(array $args) : bool
	{
		$this->reset();

		try
		{
			$this->params = $this->parser->parse($args);
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
		$this->params = [
			"controller" => $this->defaultController,
			"action" => $this->defaultAction,
		];
	}

}
