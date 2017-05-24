<?php

namespace PPM\Config\Adapter;


class Json extends AFileAdapter
{

    /**
     * encode data from array to string
     * @param array $data data to encode
     * @return string encoded data
     */
    public function encodeData(array $data) : string
    {
        return json_encode($data);
    }

    /**
     * decode data from string to array
     * @param string $data data to decode
     * @return array decoded data
     */
    public function decodeData(string $data) : array
    {
        $decoded = json_decode($data);

        return (array)$decoded;
    }

}
