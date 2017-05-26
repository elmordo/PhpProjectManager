<?php

namespace PPM\IO\Adapter;

use PPM\IO\IAdapter;


class Console implements IAdapter
{

    private $input;

    private $output;

    public function __construct()
    {
        $this->input = fopen("php://stdin", "r");
        $this->output = fopen("php://stdout", "w");
    }

    public function __destruct()
    {
        fclose($this->input);
        fclose($this->output);
    }

    /**
     * read data from user
     * @return str data from user
     */
    public function read() : string
    {
        return rtrim(fgets($this->input), PHP_EOL);
    }

    /**
     * write data to output
     * @param string $message data to write
     * @param bool $breakLine if true, add break line
     * @return IAdapter reference to this instance
     */
    public function write(string $message, bool $breakLine=true) : IAdapter
    {
        fwrite($this->output, $message);

        if ($breakLine) fwrite($this->output, PHP_EOL);

        return $this;
    }

}
