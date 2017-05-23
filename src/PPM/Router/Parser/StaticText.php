<?php

namespace PPM\Router\Parser;


class StaticText extends AArgument
{

    /**
     * parsed item have to match to internal token value
     * @param Data $data data to parse
     * @return string parsed value
     * @throws Exception data cab bit ve oarsed
     */
	public function parseValue(Data $data) : IArgument
	{
		$currentItem = $data->current();

		try
		{
			$name = $this->getName();
		}
		catch (\TypeError $e)
		{
			throw new \RuntimeException("Name of StaticText has to be set.", 400);
		}

		if ($currentItem == $name)
		{
			// all is ok
			$this->lastParsedValue = $currentItem;
			$this->parsed = true;
		}
		else
		{
			throw new Exception("Unable to parse data.", 400);

		}

		// move to next position
		$data->next();

		return $this;
	}

}
