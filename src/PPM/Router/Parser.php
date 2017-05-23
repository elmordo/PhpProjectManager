<?php

namespace PPM\Router;


class Parser
{

	protected $factory;

	protected $argumentGroups = [];

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
		foreach ($this->argumentGroups as $argumentGroup)
		{
			$argumentGroup->validate();
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

		foreach ($this->argumentGroups as $argumentGroup)
		{
			try
			{
				$result = array_merge($result, $argumentGroup->getLastData());
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
				$this->argumentGroups[] = new Parser\RequiredArgumentGroup($argument);
				$optionalArgumentGroup = null;
			}
			else
			{
				if (is_null($optionalArgumentGroup))
				{
					$optionalArgumentGroup = new Parser\OptionalArgumentGroup();
					$this->argumentGroups[] = $optionalArgumentGroup;
				}

				$optionalArgumentGroup->addArgument($argument);
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

		foreach ($this->argumentGroups as $argument)
		{
			$argument->parse($data);
		}

		return $this;
	}

}
