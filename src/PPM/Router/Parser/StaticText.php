<?php

namespace PPM\Router\Parser;


class StaticText extends AArgument
{

	protected $token;

	public function getToken() : string
	{
		return $this->token;
	}

	public function setToken(string $value) : StaticText
	{
		$this->token = $token;
		return $this;
	}

    /**
     * parsed item have to match to internal token value
     * @param Data $data data to parse
     * @return string parsed value
     * @throws Exception data cab bit ve oarsed
     */
	public function parseValue(Data $data) : IArgument
	{
		$currentItem = $data->current();

		if ($currentItem == $this->token)
		{
			// all is ok
			$this->lastParsedValue = $currentItem;
			$this->parsed = true;
		}
		else
		{
			throw new Exception("Unable to parse data.", 400);

		}

		return $this;
	}

}
