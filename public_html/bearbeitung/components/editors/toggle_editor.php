<?php

include_once dirname(__FILE__) . '/' . 'custom.php';
include_once dirname(__FILE__) . '/' . '../utils/array_wrapper.php';

class ToggleEditor extends CheckBox {

    /** @var string */
    private $onToggleCaption;

    /** @var string */
    private $offToggleCaption;

    /** @var string */
    private $toggleSize = 'small';

    /** @var string */
    private $onToggleStyle = 'primary';

    /** @var string */
    private $offToggleStyle = 'default';

    public function __construct($name, $onToggleCaption, $offToggleCaption, $customAttributes = null) {
        parent::__construct($name, $customAttributes);
        $this->onToggleCaption = $onToggleCaption;
        $this->offToggleCaption = $offToggleCaption;
    }

    public function getOnToggleCaption() {
        return $this->onToggleCaption;
    }

    public function setOnToggleCaption($value) {
        $this->onToggleCaption = $value;
    }

    public function getOffToggleCaption() {
        return $this->offToggleCaption;
    }

    public function setOffToggleCaption($value) {
        $this->offToggleCaption = $value;
    }

    public function getToggleSize() {
        return $this->toggleSize;
    }

    public function setToggleSize($value) {
        $this->toggleSize = $value;
    }

    public function getOnToggleStyle() {
        return $this->onToggleStyle;
    }

    public function setOnToggleStyle($value) {
        $this->onToggleStyle = $value;
    }

    public function getOffToggleStyle() {
        return $this->offToggleStyle;
    }

    public function setOffToggleStyle($value) {
        $this->offToggleStyle = $value;
    }

    /** * @return string */
    public function getEditorName()
    {
        return 'toggle';
    }
}
