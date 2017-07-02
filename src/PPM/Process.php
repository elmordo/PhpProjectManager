<?php

namespace PPM;


class Process
{

    const WRITE_PIPE = 0;
    const READ_PIPE = 1;

    protected $pipes;

    protected $resource;

    public function __construct($command, $cwd)
    {
        $descriptor = [
            [ "pipe", "r" ],
            [ "pipe", "w" ],
            [ "pipe", "r" ],
        ];

        $this->resource = proc_open("cd $cwd && " . $command, $descriptor, $this->pipes);
    }

    public function read()
    {
        return stream_get_contents($this->pipes[self::READ_PIPE]);
    }

    public function close()
    {
        fclose($this->pipes[self::READ_PIPE]);
        return proc_close($this->resource);
    }

}