<?php

include_once dirname(__FILE__) . '/../renderers/renderer.php';
include_once dirname(__FILE__) . '/abstract_choices_editor.php';

class ComboBox extends AbstractChoicesEditor
{
    /**
     * @var array
     */
    private $mfuChoices = array();

    /**
     * @var string
     */
    private $emptyDisplayValue;

    /**
     * @param string $name
     * @param string $emptyDisplayValue
     */
    public function __construct($name, $emptyDisplayValue = '')
    {
        parent::__construct($name);
        $this->emptyDisplayValue = $emptyDisplayValue;
        $this->addChoice('', $emptyDisplayValue);
    }

    /**
     * @return bool
     */
    public function hasEmptyChoice()
    {
        return array_key_exists('', $this->getChoices());
    }

    /**
     * @return string
     */
    public function getEmptyDisplayValue()
    {
        return $this->emptyDisplayValue;
    }

    /**
     * @return array
     */
    public function getMFUChoices()
    {
        return array_intersect_key(
            $this->getChoices(),
            array_flip($this->mfuChoices)
        );
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function addMFUChoice($value)
    {
        $this->mfuChoices[] = $value;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasMFUChoices()
    {
        return count($this->mfuChoices) > 0;
    }

    public function extractValueFromArray(
        ArrayWrapper $arrayWrapper,
        &$valueChanged)
    {
        if ($arrayWrapper->IsValueSet($this->GetName())) {
            $valueChanged = true;
            $value = $arrayWrapper->GetValue($this->GetName());
            if ($value == '')
                return null;
            else
                return $value;
        } else {
            $valueChanged = false;
            return null;
        }
    }

    public function CanSetupNullValues()
    {
        return false;
    }

    /**
     * @return string
     */
    public function getEditorName()
    {
        return 'combobox';
    }

    protected function DoSetAllowNullValue($allowNullValue)
    {
        if ($allowNullValue) {
            $this->addChoice('', $this->emptyDisplayValue);
        } elseif ($this->hasChoice('')) {
            $this->removeChoice('');
        }
    }
}
