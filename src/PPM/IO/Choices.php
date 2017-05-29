<?php

namespace PPM\IO;


class Choices
{

    protected $choices = [];

    protected $originalChoices = [];

    public function __construct(array $choices)
    {
        $this->originalChoices = $choices;
        $this->setup();
    }

    public function getRealLabels() : array
    {
        $labels = [];

        foreach ($this->choices as $choice)
            $labels[] = $choice->getRealLabel();

        return $labels;
    }

    public function evaluate(string $input) : int
    {
        $input = $this->filterInput($input);

        foreach ($this->choices as $choice)
        {
            if ($choice->evaluate($input))
                return $choice->getValue();
        }

        throw new Exception("Invalid input '$input'.", 400);
    }

    public function setup()
    {
        $this->choices = [];
        $shortcuts = [];

        foreach ($this->originalChoices as $value => $label)
        {
            $choice = new ChoiceItem($value, $label, $shortcuts);

            try
            {
                $shortcuts[] = $choice->getShortcut();
            }
            catch (\TypeError $e)
            {
            }

            $this->choices[] = $choice;
        }
    }

    /**
     * filter data from input
     * @param string $input input data
     * @return string filtered input
     */
    private function filterInput(string $input) : string
    {
        return mb_strtolower(trim($input));
    }

}