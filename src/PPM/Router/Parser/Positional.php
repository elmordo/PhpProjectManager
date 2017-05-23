<?php

namespace PPM\Router\Parser;


class Positional extends AArgument
{

    /**
     * any available item is valid
     * @param Data $data data to parse
     * @return string parsed value
     * @throws Exception data cab bit ve oarsed
     */
	public function parseValue(Data $data) : IArgument
	{
		$currentItem = $data->current();

		// all is ok
		$this->lastParsedValue = $currentItem;
		$this->parsed = true;
        $data->next();

		return $this;
	}

}
