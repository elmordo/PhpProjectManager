<?php

namespace PPM\Router\Parser;


class RequiredArgumentGroup implements IArgumentGroup
{

    /**
     * current argument
     * @var IArgument
     */
    protected $argument;

    /**
     * initialize instance with argument
     * @param IArgument $argument argument to set
     */
    public function __construct(IArgument $argument)
    {
        $this->argument = $argument;
    }

    /**
     * return current argument
     * @return IArgument current argument
     */
    public function getArgument() : IArgument
    {
        return $this->argument;
    }

    /**
     * set new argument
     * @param IArgument $argument new argument to set
     * @return IArgumentGroup reference to this instance
     */
    public function setArgument(IArgument $argument) : IArgumentGroup
    {
        $this->argument = $argument;
        return $this;
    }

    /**
     * parse data
     * @param Data $data input data
     * @return array parsed data
     * @throws Exception data can not be parsed
     */
    public function parse(Data $data) : array
    {
        $this->argument->reset()->parseValue($data);
        return $this->getLastData();
    }

    /**
     * return last parsed data
     * @return array last parsed data
     */
    public function getLastData() : array
    {
        try
        {
            $mapping = $this->argument->getMapping();
            $value = $this->argument->getValue();
            $result = [ $mapping => $value ];
        }
        catch (\TypeError $e)
        {
            $result = [];
        }

        return $result;
    }

    /**
     * validate parsed arguments
     * @return IArgumentGroup reference to this instance
     * @throws AssertionError parsed data is not valid
     */
    public function validate() : IArgumentGroup
    {
        if ($this->argument->isRequired())
        {
            assert($this->argument->isParsed());
        }

        return $this;
    }

}
