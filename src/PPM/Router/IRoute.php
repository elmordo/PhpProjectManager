<?php

namespace PPM\Router;


interface IRoute
{

	/**
	 * setup route from array
	 * @param array $options set of options
	 * @return IRoute reference to this instance
	 */
	public function setupFromArray(array $options) : IRoute;

	/**
	 * return name of the route
	 * @return string route name
	 */
	public function getName() : string;

	/**
	 * return description of route
	 * @return string
	 */
	public function getDescription() : string;

	/**
	 * test arguments againts the route
	 * @param array $args set of arguments
	 * @return bool true if arguments match
	 */
	public function match(array $args) : bool;

	/**
	 * return parsed controller
	 * @return string controller name
	 */
	public function getController() : string;

	/**
	 * return parsed action
	 * @return string action name
	 */
	public function getAction() : string;

	/**
	 * return parsed parameters
	 * @return array set of parameters
	 */
	public function getParams() : array;

	/**
	 * return parameter value defined by name
	 * @param string $name name of parameter
	 * @param string|null $defaultValue default value if parameters was not found
	 * @return string parameter value
	 */
	public function getParam(string $name, $defaultValue=null) : string;

}
