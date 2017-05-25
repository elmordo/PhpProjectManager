<?php

namespace PPM\IO;


interface IAdapter
{

    /**
     * read data from user
     * @return str data from user
     */
    public function read() : string;

    /**
     * write data to output
     * @param string $message data to write
     * @return IAdapter reference to this instance
     */
    public function write(string $message) : IAdapter;

}
