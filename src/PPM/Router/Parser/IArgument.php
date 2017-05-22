<?php

namespace PPM\Router\Parser;


/**
 * interface for all arguments.
 * Flag arguments have value true (argument is in list) or false (argument missing)
 */
interface IArgument
{

    /**
     * return true if value was parsed since the last reset.
     * @return bool true if value was parsed, false otherwise
     */
    public function isParsed() : bool;

    /**
     * return true if argument is required, false if argument is optional
     * @return bool true if argument is required, false otherwise
     */
    public function isRequred() : bool;

    /**
     * return name of the argument
     * @return string name of the argument
     */
    public function getName() : string;

    /**
     * return argument description for help
     * @return string argument description
     */
    public function getDescription() : string;

    /**
     * return default value for argument
     * @return mixed default value
     */
    public function getDefaultValue() : string;

    /**
     * mapping of the argument
     * @return string mapping
     */
    public function getMapping() : string;

    /**
     * parse value from data
     * @param Data $data data to parse
     * @return string parsed value
     * @throws Exception data cab bit ve oarsed
     */
    public function parseValue(Data $data) : IArgument;

    /**
     * return last parsed value
     * @return string last parsed value
     */
    public function getValue() : string;

    /**
     * reset parsed data
     * @return IArgument refernece to this instance
     */
    public function reset() : IArgument;

}
