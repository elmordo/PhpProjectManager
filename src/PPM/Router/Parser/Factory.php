<?php

namespace PPM\Router\Parser;


class Factory
{

	const TYPE_STATIC = "static";

	const TYPE_DYNAMIC = "positional";

	public function createArgument(string $type, array $options) : IArgument
	{
		switch ($type)
		{
		case self::TYPE_STATIC:
			$instance = new StaticText();
			break;

		case self::TYPE_DYNAMIC:
			$instance = new Positional();
			break;

		default:
			throw new Exception();
		}

		$instance->setOptions($options);

		return $instance;
	}

}