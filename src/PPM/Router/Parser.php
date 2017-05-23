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
		$this->parseArguments($arguments);

		try
		{
			$this->validate();
		}
		catch (\AssertionError $e)
		{
			throw new Exception("Data is invalid", 400);
		}

		return $this->getLastData();
	}

	/**
	 * validate parsed data (assert all required arguments are parsed)
	 * @return Parser reference to this instance
	 * @throws \AssertionError data is invalid
	 */
	public function validate() : Parser
	{
		foreach ($this->arguments as $argument)
		{
			if ($argument->isRequired())
				assert($argument->isParsed());
		}

		return $this;
	}

	/**
	 * get last parsed data
	 * @return array key is mapping and value is parsed value
	 */
	public function getLastData() : array
	{
		$result = [];

		foreach ($this->arguments as $argument)
		{
			try
			{
				$result[$argument->getMapping()] = $argument->getValue();
			}
			catch (\TypeError $e)
			{
				// mapping is not set
			}
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

	/**
	 * parse arguments
	 * @param array $arguments set of arguments
	 * @return Parser reference to this instance
	 */
	private function parseArguments(array $arguments) : Parser
	{
		$data = new Parser\Data($arguments);

		foreach ($this->arguments as $argument)
		{
			$argument->parseValue($data);
		}

		return $this;
	}

}
