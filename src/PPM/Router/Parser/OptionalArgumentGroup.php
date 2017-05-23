<?php

namespace PPM\Router\Parser;


class OptionalArgumentGroup implements IArgumentGroup
{

    /**
     * set of registered arguments
     * @var array
     */
    protected $arguments = [];

    /**
     * add argument into list
     * @param IArgument $argument [description]
     */
    public function addArgument(IArgument $argument) : OptionalArgumentGroup
    {
        $this->arguments[] = $argument;
        return $this;
    }

    /**
     * parse data
     * @param Data $data input data
     * @return array parsed data
     */
    public function parse(Data $data) : array
    {
        $rest = $this->arguments;

        do
        {
            $last = $rest;

            foreach ($rest as $key => $argument)
            {
                try
                {
                    $argument->parseValue($data);
                    unset($rest[$key]);
                    break;
                }
                catch (Exception $e)
                {
                    // nothing to do
                }
            }
        }
        while (count($rest) > 0 && count($rest) != count($last));

        return $this->getLastData();
    }

    /**
     * return last parsed data
     * @return array last parsed data
     */
    public function getLastData() : array
    {
        $result = [];

        foreach ($this->arguments as $argument)
        {
            try
            {
                if ($argument->isParsed())
                {
                    $mapping = $argument->getMapping();
                    $value = $argument->getValue();
                    $result[$mapping] = $value;
                }
            }
            catch (\TypeError $e)
            {
                // nothing to do
            }
        }

        return $result;
    }

    /**
     * group is always valid
     * @return IArgumentGroup reference to this instance
     * @throws AssertionError parsed data is not valid
     */
    public function validate() : IArgumentGroup
    {
        return $this;
    }

}