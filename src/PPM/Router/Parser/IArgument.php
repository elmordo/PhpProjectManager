<?php

namespace PPM\Route\Parser;


/**
 * interface for all arguments.
 * Flag arguments have value true (argument is in list) or false (argument missing)
 */
interface IArgument
{

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
    public function getDefaultValue();

    /**
     * mapping of the argument
     * @return string mapping
     */
    public function getMapping() : string;

    /**
     * return true if argument is flag
     * @return boolean true if value is flag
     */
    public function isFlag() : bool;

    /**
     * parse value from data
     * @param Data $data data to parse
     * @return string parsed value
     * @throws Exception data cab bit ve oarsed
     */
    public function parseValue(Data $data) : string;

}
