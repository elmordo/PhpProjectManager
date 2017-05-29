<?php

namespace PPM;

use PPM\IO\IAdapter;


class IO
{

    protected $adapter;

    const OPT_NO = 0;

    const OPT_YES = 1;

    const OPT_ABORT = 2;

    /**
     * read data from user
     * @return str data from user
     */
    public function read() : string
    {
        return $this->adapter->read();
    }

    /**
     * write data to output
     * @param string $message data to write
     * @param bool $breakLine if true, add break line
     * @return IO reference to this instance
     */
    public function write(string $message, $breakLine=true) : IO
    {
        $this->adapter->write($message, $breakLine);
        return $this;
    }

    /**
     * prompt data from user
     * @param string $message message
     * @return string data from user
     */
    public function prompt(string $message) : string
    {
        $this->write($message, false);
        return $this->read();
    }

    /**
     * ask user with choices defined in array
     * @param string $question question
     * @param array $choices array with choices (numeric indexes)
     * @param string $choices message displayed when invalid data was read
     * @return int index of the selected choice
     */
    public function askWithChoices(
        string $question, array $choices, string $invalidChoiceMessage=null) : int
    {
        $selectedOption = false;

        // create full question
        $choicesManager = new IO\Choices($choices);
        $fullQuestion = sprintf("%s %s ", $question, implode(" | ", $choicesManager->getRealLabels()));

        do
        {
            $response = $this->prompt($fullQuestion);

            try
            {
                $selectedOption = $choicesManager->evaluate($response);
            }
            catch (IO\Exception $e)
            {
                if (is_string($invalidChoiceMessage))
                    $this->write($invalidChoiceMessage);
            }
        } while ($selectedOption === false);

        return $selectedOption;
    }

    public function askYesNoAbort(string $question, string $invalidChoiceMessage=null) : int
    {
        $choices = [ self::OPT_YES => "yes", self::OPT_NO => "no", self::OPT_ABORT => "abort" ];
        $result = $this->askWithChoices($question, $choices, $invalidChoiceMessage);

        if ($result == self::OPT_ABORT)
            throw new IO\AbortException("Aborted");

        return $result;
    }

    /**
     * get current adapter
     * @return IAdapter reference to adapter
     */
    public function getAdapter() : IAdapter
    {
        return $this->adapter;
    }

    /**
     * set new IO adapter
     * @param IAdapter $value new adapter to set
     * @return IO reference to this instance
     */
    public function setAdapter(IAdapter $value) : IO
    {
        $this->adapter = $value;
        return $this;
    }

}
