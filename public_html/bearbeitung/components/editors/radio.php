<?php

include_once dirname(__FILE__) . '/abstract_choices_editor.php';
include_once dirname(__FILE__) . '/' . '../utils/array_wrapper.php';

class RadioEdit extends AbstractChoicesEditor
{
    const StackedMode = 0;
    const InlineMode = 1;

    /**
     * @var int
     */
    private $displayMode;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        parent::__construct($name);
        $this->displayMode = self::StackedMode;
    }

    public function GetDisplayMode()
    {
        return $this->displayMode;
    }

    public function SetDisplayMode($value)
    {
        $this->displayMode = $value;
    }

    /** @return bool */
    public function IsInlineMode()
    {
        return $this->displayMode == self::InlineMode;
    }

    public function extractValueFromArray(
        ArrayWrapper $arrayWrapper,
        &$valueChanged)
    {
        if ($arrayWrapper->IsValueSet($this->GetName())) {
            $valueChanged = true;
            return $arrayWrapper->GetValue($this->GetName());
        } else {
            $valueChanged = false;
            return null;
        }
    }

    /**
     * @return string
     */
    public function getEditorName()
    {
        return 'radio';
    }
}
