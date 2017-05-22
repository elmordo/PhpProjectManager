<?php

namespace PPM\Router;


class Parser
{

	/**
	 * setup parser
	 * @param array $options parser options
	 * @return Parser referene to this instance
	 */
	public function setup(array $options) : Parser
	{
		foreach ($options as $name => $value)
		{
			switch ($name)
			{
				case "arguments":
					$this->setupArguments($value);
					break;
			}
		}
	}

	/**
	 * parse arguments from the command line
	 * @param array $arguments set of command line arguments
	 * @return array parset arguments
	 * @throws \PPM\Router\Parser\Exception arguments do not match to route
	 */
	public function parse(array $arguments) : array
	{

	}

	/**
	 * setup arguments
	 * @param  array $arguments set of argument definitions
	 * @return Parser referece to this
	 */
	public function setupArguments(array $arguments) : Parser
	{
		foreach ($this->arguments as $argumentDefinition)
		{
			$this->setupArgument($argumentDefinition);
		}

		return $this;
	}

}
