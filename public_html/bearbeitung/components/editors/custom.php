<?php

include_once dirname(__FILE__) . '/' . '../component.php';
include_once dirname(__FILE__) . '/' . '../utils/array_wrapper.php';
include_once dirname(__FILE__) . '/' . 'validators.php';

/**
 * @property mixed GetViewData
 */
abstract class CustomEditor extends Component {

    /** @var null|string */
    private $customAttributes;

    /** @var null|string */
    private $inlineStyles;

    /** @var string */
    private $maxWidth;

    /** @var boolean */
    private $readOnly;

    /** @var boolean */
    private $visible;

    /** @var boolean */
    private $enabled;

    /** @var string */
    private $fieldName;

    /** @var \ValidatorCollection */
    private $validators;

    /**
     * @param string $name
     * @param string $customAttributes
     */
    public function __construct($name, $customAttributes = null) {
        parent::__construct($name);
        $this->customAttributes = $customAttributes;
        $this->inlineStyles = null;
        $this->readOnly = false;
        $this->visible = true;
        $this->enabled = true;
        $this->validators = new ValidatorCollection();
        $this->fieldName = null;
        $this->maxWidth = '100%';
    }

    /**
     * @return bool
     */
    protected function SuppressRequiredValidation() {
        return false;
    }

    /**
     * @return string
     */
    public final function GetValidationAttributes() {
        return $this->GetValidatorCollection()->GetInputAttributes($this->SuppressRequiredValidation());
    }

    /**
     * @return ValidatorCollection
     */
    public final function GetValidatorCollection() {
        return $this->validators;
    }

    /**
     * @return array
     */
    final public function getViewData()
    {
        return array(
            'Validators' => array(
                'InputAttributes' => sprintf(
                    '%s data-legacy-field-name="%s" data-pgui-legacy-validate="true"',
                    $this->GetValidationAttributes(),
                    $this->GetFieldName()
                ),
            ),
            'Editor' => $this,
        );
    }

    /**
     * @return string
     */
    abstract public function getEditorName();

    /**
     * @param ArrayWrapper $arrayWrapper
     * @param bool         $valueChanged
     *
     * @return mixed
     */
    public abstract function extractValueFromArray(ArrayWrapper $arrayWrapper, &$valueChanged);

    /**
     * @abstract
     * @return mixed
     */
    public abstract function GetValue();

    /**
     * @return mixed
     */
    public function GetDisplayValue()
    {
        return $this->GetValue();
    }

    /**
     * @abstract
     * @param mixed $value
     * @return void
     */
    public abstract function SetValue($value);

    /**
     * @return string
     */
    public function GetFieldName() {
        return $this->fieldName;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function SetFieldName($value) {
        $this->fieldName = $value;

        return $this;
    }

    /**
     * @return boolean
     */
    public function GetReadOnly() {
        return $this->readOnly;
    }

    /**
     * @param boolean $value
     * @return void
     */
    public function SetReadOnly($value) {
        $this->readOnly = $value;
    }

    /**
     * @param string $value
     * @return void
     */
    public function setCustomAttributes($value) {
        $this->customAttributes = $value;
    }

    /**
     * @return null|string
     */
    public function getCustomAttributes() {
        return $this->customAttributes;
    }

    /**
     * @param string $value
     * @return void
     */
    public function setInlineStyles($value) {
        $this->inlineStyles = $value;
    }

    /**
     * @return null|string
     */
    public function getInlineStyles() {
        return $this->inlineStyles;
    }

    /**
     * @return string
     */
    public function getMaxWidth() {
        return $this->maxWidth;
    }

    /**
     * @param string $value
     */
    public function setMaxWidth($value) {
        if (is_integer($value)) {
            $value .= 'em';
        }

        $this->maxWidth = $value;
    }

    /**
     * @param string $value
     * @return mixed
     */
    public function prepareValueForDataset($value) {
        return $value;
    }

    /**
     * @return bool
     */
    public function getVisible() {
        return $this->visible;
    }

    /**
     * @param bool $value
     * @return $this
     */
    public function setVisible($value) {
        $this->visible = $value;
        return $this;
    }

    /**
     * @return bool
     */
    public function getEnabled() {
        return $this->enabled;
    }

    /**
     * @param bool $value
     * @return $this
     */
    public function setEnabled($value) {
        $this->enabled = $value;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isInlineLabel()
    {
        return false;
    }
}
