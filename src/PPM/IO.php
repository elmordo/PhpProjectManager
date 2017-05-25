<?php

namespace PPM;

use PPM\IO\IAdapter;


class IO
{

    protected $adapter;

    /**
     * read data from user
     * @return str data from user
     */
    public function read() : string
    {
        return $this->adapter->read();
    }

    /**
     * write data to output
     * @param string $message data to write
     * @return IO reference to this instance
     */
    public function write(string $message) : IO
    {
        $this->adapter->write($message);
        return $this;
    }

    /**
     * prompt data from user
     * @param string $message message
     * @return string data from user
     */
    public function prompt(string $message) : string
    {
        $this->write($message);
        return $this->read();
    }

    /**
     * get current adapter
     * @return IAdapter reference to adapter
     */
    public function getAdapter() : IAdapter
    {
        return $this->adapter;
    }

    /**
     * set new IO adapter
     * @param IAdapter $value new adapter to set
     * @return IO reference to this instance
     */
    public function setAdapter(IAdapter $value) : IO
    {
        $this->adapter = $value;
        return $this;
    }

}
