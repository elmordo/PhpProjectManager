<?php

namespace PPM\Router\Parser;


class Positional extends AArgument
{

    /**
     * set requried flag to true
     * @param string|null $name argument name
     * @param string|null $description description
     * @param string|null $defaultValue default value
     * @return type
     */
    public function __construct(string $name=null, string $description=null, string $defaultValue=null)
    {
        parent::__construct($name, $description, $defaultValue);
        $this->required = true;
    }

    /**
     * any available item is valid
     * @param Data $data data to parse
     * @return string parsed value
     * @throws Exception data cab bit ve oarsed
     */
	public function parseValue(Data $data) : IArgument
	{
		$currentItem = $data->current();

		// all is ok
		$this->lastParsedValue = $currentItem;
		$this->parsed = true;
        $data->next();

		return $this;
	}

    public function setRequired(bool $value) : AArgument
    {
        if (!$value)
            throw new \InvalidArgumentException("Positional arguments are always required");

        return parent::setRequired($value);
    }

}
