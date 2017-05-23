<?php

namespace PPM\Router;


class Parser
{

	protected $factory;

	protected $arguments = [];

	public function __construct()
	{
		$this->factory = new Parser\Factory();
	}

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

		return $this;
	}

	/**
	 * parse arguments from the command line
	 * @param array $arguments set of command line arguments
	 * @return array parset arguments
	 * @throws \PPM\Router\Parser\Exception arguments do not match to route
	 */
	public function parse(array $arguments) : array
	{
		$result = [];
		$data = new Parser\Data($arguments);

		foreach ($this->arguments as $argument)
		{
			$argument->parseValue($data);
		}

		return $result;
	}

	/**
	 * setup arguments
	 * @param  array $arguments set of argument definitions
	 * @return Parser referece to this
	 */
	public function setupArguments(array $arguments) : Parser
	{
		$optionalArgumentGroup = null;

		foreach ($arguments as $argumentDefinition)
		{
			$argument = $this->setupArgument($argumentDefinition);

			if ($argument->isRequired())
			{
				$this->arguments[] = $argument;
				$optionalArgumentGroup = null;
			}
			else
			{
				throw new \Exception("Optional arguments are not supported", 1);

			}
		}

		return $this;
	}

	/**
	 * setup one argument
	 * @param array $argumentDefinition argument definition
	 * @return IArgument created argument
	 */
	private function setupArgument(array $argumentDefinition) : Parser\IArgument
	{
		$type = $argumentDefinition["type"];
		$options = $argumentDefinition["options"];
		return $this->factory->createArgument($type, $options);
	}

}
