<?php

include_once dirname(__FILE__) . '/abstract_choices_editor.php';
include_once dirname(__FILE__) . '/' . '../utils/array_wrapper.php';
include_once dirname(__FILE__) . '/../utils/string_utils.php';

abstract class AbstractMultiChoiceEditor extends AbstractChoicesEditor
{
    /**
     * @var array
     */
    private $values = array();

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        parent::__construct($name);
    }

    /**
     * @param string $value
     *
     * @return bool
     */
    public function hasValue($value) {
        return in_array($value, $this->values);
    }

    /**
     * @return mixed|string
     */
    public function GetValue() {
        return implode(',', $this->values);
    }

    /**
     * @inheritdoc
     */
    public function setValue($valuesAsString) {
        $this->values = explode(',', $valuesAsString);
    }

    /**
     * @inheritdoc
     */
    public function extractValueFromArray(ArrayWrapper $arrayWrapper, &$valueChanged) {
        $valueChanged = $arrayWrapper->isValueSet($this->GetName());
        if ($valueChanged) {
            $valuesArray = $arrayWrapper->GetValue($this->GetName());
            $result = '';
            foreach ($valuesArray as $value)
                StringUtils::AddStr($result, $value, ',');
            return $result;
        } else {
            return '';
        }
    }
}
