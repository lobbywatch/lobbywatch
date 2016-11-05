<?php

include_once dirname(__FILE__) . '/' . 'abstract_multi_choice_editor.php';

class CheckBoxGroup extends AbstractMultiChoiceEditor
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

    /**
     * @return string
     */
    public function getEditorName()
    {
        return 'checkboxgroup';
    }
}
