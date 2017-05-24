<?php

namespace PPM\Router\Parser;


class Data implements \Iterator
{

    protected $data;

    protected $position;

    protected $length;

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->position = 0;
        $this->length = count($data);
    }

    public function getRestCount() : int
    {
        return $this->length - $this->position;
    }

    public function read(int $length) : array
    {
        $maxLength = $this->length - $this->position;

        if ($maxLength < $length)
            throw new \OverflowException("Data buffer overflow");

        $result = array_slice($this->data, $this->position, $length);
        $this->position += $length;

        return $result;
    }

    public function peek($offset=1)
    {
        $position = $this->position + $offset;

        return $this->data[$position];
    }

    public function current()
    {
        if ($this->position >= $this->length)
            throw new \OverflowException("Data buffer overflow");

        return $this->data[$this->position];
    }

    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        ++$this->position;
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function valid()
    {
        return $this->position < $this->length;
    }

}
