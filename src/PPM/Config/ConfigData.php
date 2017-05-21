<?php

namespace PPM\Config;


class ConfigData
{

	/**
	 * @var configuration data
	 */
	protected $data;

	/**
	 * create new instance initialized with configuration data
	 * @param array $data configuration data
	 */
	public function __construct(array $data)
	{
		$this->data = $data;
	}

	/**
	 * access to configuration property
	 * @param string $name name of requested property
	 * @return mixed content of requested property or NULL if property is undefined
	 */
	public function __get(string $name)
	{
		return $this->getValue($name);
	}

	public function getValue(string $name, $default=null)
	{
		if (!isset($this->data[$name]))
		{
			$value = $default;
		}

		else
		{
			if (is_array($this->data[$name]))
			{
				$this->data[$name] = new ConfigData($this->data[$name]);
			}

			$value = $this->data[$name];
		}

		return $value;
	}

	/**
	 * inplace merge current data with new array config data
	 * @param array $data data to merge with
	 * @return ConfigData reference to this instance
	 */
	public function mergeWithArray(array $data) : ConfigData
	{
		$this->data = array_merge($this->data, $data);
		return $this;
	}

	/**
	 * recursively convert configuration data to array
	 * @return configuration data
	 */
	public function toArray() : array
	{
		$result = [];

		foreach ($this->data as $key => $value)
		{
			if ($value instanceof ConfigData)
				$value = $value->toArray();

			$result[$key] = $value;
		}

		return $result;
	}

}
