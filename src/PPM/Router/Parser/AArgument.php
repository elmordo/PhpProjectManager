<?php

namespace PPM\Router\Parser;


abstract class AArgument implements IArgument
{

	/**
	 * @var string name of the argument
	 */
	protected $name;

	/**
	 * @var string mapped name of the argument
	 */
	protected $mapping;

	/**
	 * @var string descroption of the argument
	 */
	protected $description;

	/**
	 * @var string default value
	 */
	protected $defaultValue;

	/**
	 * @var string last parsed value
	 */
	protected $lastParsedValue;

	/**
	 * @var bool true if value was parsed since last reset
	 */
	protected $parsed;

	/**
	 * @var bool true if argument is required
	 */
	protected $required;

	/**
	 * create and initialize instance
	 * @param string|null $name argument name
	 * @param string|null $description description
	 * @param string|null $defaultValue default value
	 * @return type
	 */
	public function __construct(string $name=null, string $description=null, string $defaultValue=null)
	{
		$this->name = $name;
		$this->mapping = $name;
		$this->description = $description;
		$this->defaultValue = $defaultValue;
		$this->parsed = false;
		$this->required = true;
	}

	/**
	 * setup instance from array of options
	 * @param array $options set of options
	 * @return AArgument reference to this instance
	 */
	public function setOptions(array $options) : AArgument
	{
		foreach ($options as $name => $value)
		{
			switch ($name)
			{
				case "name":
					$this->setName((string)$value);
					break;

				case "description":
					$this->setDescription((string)$value);
					break;

				case "mapping":
					$this->setMapping((string)$value);
					break;

				case "defaultValue":
					$this->setDefaultValue((string)$value);
					break;

				case "required":
					$this->setRequired((bool)$value);
					break;
			}
		}

		return $this;
	}

    /**
     * return name of the argument
     * @return string name of the argument
     */
    public function getName() : string
    {
    	return $this->name;
    }

    /**
     * set new name
     * @param string $value new name name
     * @return AArgument reference to this instance
     */
    public function setName(string $value) : AArgument
    {
    	$this->name = $value;
    	return $this;
    }

    /**
     * return argument description for help
     * @return string argument description
     */
    public function getDescription() : string
    {
    	return $this->description;
    }

    /**
     * set new description
     * @param string $value new description name
     * @return AArgument reference to this instance
     */
    public function setDescription(string $value) : AArgument
    {
    	$this->description = $value;
    	return $this;
    }

    /**
     * return default value for argument
     * @return mixed default value
     */
    public function getDefaultValue() : string
    {
    	return $this->defaultValue;
    }

    /**
     * set new default value
     * @param string $value new default value name
     * @return AArgument reference to this instance
     */
    public function setDefaultValue(string $value) : AArgument
    {
    	$this->defaultValue = $value;
    	return $this;
    }

    /**
     * mapping of the argument
     * @return string mapping
     */
    public function getMapping() : string
    {
    	return $this->mapping;
    }

    /**
     * set new mapping
     * @param string $value new mapping name
     * @return AArgument reference to this instance
     */
    public function setMapping(string $value) : AArgument
    {
    	$this->mapping = $value;
    	return $this;
    }

    /**
     * parse value from data
     * @param Data $data data to parse
     * @return string parsed value
     * @throws Exception data cab bit ve oarsed
     */
    public abstract function parseValue(Data $data) : IArgument;

    /**
     * return last parsed value
     * @return string last parsed value
     */
    public function getValue() : string
    {
    	return $this->lastParsedValue;
    }

    /**
     * reset parsed data
     * @return IArgument refernece to this instance
     */
    public function reset() : IArgument
    {
    	$this->lastParsedValue = $this->defaultValue;
    }

    /**
     * return true if value was parsed since the last reset.
     * @return bool true if value was parsed, false otherwise
     */
    public function isParsed() : bool
    {
    	return $this->parsed;
    }

    /**
     * return true if argument is required, false if argument is optional
     * @return bool true if argument is required, false otherwise
     */
    public function isRequired() : bool
    {
    	return $this->required;
    }

    public function setRequired(bool $value) : AArgument
    {
    	$this->required = $value;
    	return $this;
    }

}
