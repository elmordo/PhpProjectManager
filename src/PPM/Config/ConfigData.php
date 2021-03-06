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
	public function __construct(array &$data)
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

    public function __set(string $name, $value)
    {
        $this->setValue($name, $value);
    }

    public static function createEmptyInstance()
    {
    	$config = [];
    	return new self($config);
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
				$value = new ConfigData($this->data[$name]);
			}
			else
			{
				$value = $this->data[$name];
			}

		}

		return $value;
	}

	public function getStrict($name)
	{
		if (!isset($this->data[$name]))
			throw new Exception("Key '$name' does not exist", 500);

		return $this->getValue($name);
	}

    public function setValue(string $name, $value) : ConfigData
    {
        $this->data[$name] = $value;
        return $this;
    }

	public function mergeWith(ConfigData $other) : ConfigData
	{
		$this->mergeWithArray($other->toArray());
		return $this;
	}

	/**
	 * inplace merge current data with new array config data
	 * @param array $data data to merge with
	 * @return ConfigData reference to this instance
	 */
	public function mergeWithArray(array $data) : ConfigData
	{
		$this->mergeArrayIntoFirst($this->data, $data);
		$this->removeDuplicates($this->data);

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

	private function mergeArrayIntoFirst(array &$array1, array $array2) : array
	{
		foreach ($array2 as $key => $value)
		{
			if (isset($array1[$key]))
			{
				if (is_array($value) && is_array($array1[$key]))
				{
					$this->mergeArrayIntoFirst($array1[$key], $value);
				}
				else
				{
					$array1[$key] = $value;
				}
			}
			else
			{
				$array1[$key] = $value;
			}
		}

		return $array1;
	}

	private function removeDuplicates(array &$input) : array
	{
		$result = [];

		foreach ($input as $key => $val)
		{

			if (is_array($val))
			{
				$val = $this->removeDuplicates($val);
			}

			$result[$key] = $val;
		}

		$result = array_unique($result, SORT_REGULAR);

		return $result;
	}

}
