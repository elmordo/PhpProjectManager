<?php

namespace PPM\IO;


class ChoiceItem
{

    protected $value;

    protected $originalLabel;

    protected $realLabel;

    protected $shortcut;

    protected $possibleValues = [];

    public function __construct(
        int $value,
        string $originalLabel,
        array $existingShortcuts=[])
    {
        $this->setValue($value);
        $this->setOriginalLabel($originalLabel, $existingShortcuts);
    }

    public function evaluate(string $filteredInput) : bool
    {
        return in_array($filteredInput, $this->possibleValues);
    }

    /**
     * get 'value' value
     * @return int
     */
    public function getValue() : int
    {
        return $this->value;
    }

    /**
     * set new 'value' value
     * @param int $value new value of 'value'
     * @return ChoiceItem
     */
    public function setValue(int $value) : ChoiceItem
    {
        $this->value = $value;
        return $this;
    }

    /**
     * get 'originalLabel' value
     * @return string
     */
    public function getOriginalLabel() : string
    {
        return $this->originalLabel;
    }

    /**
     * set new 'originalLabel' value
     * @param string $value new value of 'originalLabel'
     * @param array $existingShortcuts set of existing shortcuts
     * @return ChoiceItem
     */
    public function setOriginalLabel(string $value, array $existingShortcuts=[]) : ChoiceItem
    {
        $this->originalLabel = $value;
        $this->possibleValues = [ mb_strtolower(trim($value)) ];

        // generate shortcut
        $this->shortcut = null;
        $startPart = "";

        for ($i = 0; $i < strlen($value) && $this->shortcut === null; ++$i)
        {
            $char = $value[$i];

            if (!in_array($char, $existingShortcuts))
            {
                $this->shortcut = $char;
            }
            else
            {
                $startPart .= $char;
            }
        }

        if ($this->shortcut === null)
        {
            $this->realLabel = $value;
        }
        else
        {
            $this->realLabel = $startPart . "(" . $this->shortcut . ")" . substr($value, strlen($startPart) + 1);
            $this->possibleValues[] = mb_strtolower($this->shortcut);
        }

        return $this;
    }

    /**
     * return real label of the choice
     * @return string real label
     */
    public function getRealLabel() : string
    {
        return $this->realLabel;
    }

    /**
     * get 'shortcut' value
     * @return string
     */
    public function getShortcut() : string
    {
        return $this->shortcut;
    }

}