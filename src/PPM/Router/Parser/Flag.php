<?php

namespace PPM\Router\Parser;


class Flag extends AArgument
{

    protected $foundValue = "1";

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
        $this->required = false;
        $this->parsed = true;
        $this->defaultValue = "0";
    }

    /**
     * set new found value
     * @param mixes $value new found value
     * @return Flag reference to this instance
     */
    public function setFoundValue($value) : Flag
    {
        $this->foundValue = $value;
        return $this;
    }

    /**
     * return found value
     * @return mixed found value
     */
    public function getFoundValue()
    {
        return $this->foundValue;
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

        if ($currentItem != $this->getName())
            throw new Exception();

        // all is ok
        $this->lastParsedValue = $this->foundValue;
        $this->parsed = true;
        $data->next();

        return $this;
    }

    public function setRequired(bool $value) : AArgument
    {
        if ($value)
            throw new \InvalidArgumentException("Optional arguments are always optional");

        return parent::setRequired($value);
    }

    public function reset() : IArgument
    {
        parent::reset();
        $this->parsed = true;
        return $this;
    }

}
