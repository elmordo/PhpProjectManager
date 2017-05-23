<?php

namespace PPM\Router\Parser;


interface IArgumentGroup
{

    /**
     * parse data
     * @param Data $data input data
     * @return array parsed data
     */
    public function parse(Data $data) : array;

    /**
     * return last parsed data
     * @return array last parsed data
     */
    public function getLastData() : array;

    /**
     * validate parsed arguments
     * @return IArgumentGroup reference to this instance
     * @throws AssertionError parsed data is not valid
     */
    public function validate() : IArgumentGroup;

}