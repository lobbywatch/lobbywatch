<?php

class FormLayoutGroup
{
    /** @var string */
    private $mode;

    /** @var string */
    private $name;

    /** @var bool */
    private $visible;

    /** @var int */
    private $width;

    /** @var string */
    private  $customAttributes;

    /** @var string */
    private $inlineStyles;

    /** @var FormLayoutRow[] */
    private $rows = array();

    /**
     * @param bool       $mode
     * @param string     $name
     * @param int        $width
     * @param string $customAttributes
     * @param string $inlineStyles
     */
    public function __construct($mode, $name, $width, $customAttributes, $inlineStyles) {
        $this->mode = $mode;
        $this->name = $name;
        $this->visible = true;
        $this->width = $width;
        $this->customAttributes = $customAttributes;
        $this->inlineStyles = $inlineStyles;
    }

    /**
     * @return FormLayoutRow
     */
    public function addRow()
    {
        $row = new FormLayoutRow($this->isHorizontal());

        $this->rows[] = $row;

        return $row;
    }

    /**
     * @return FormLayoutRow[]
     */
    public function getRows()
    {
        $result = array();
        foreach ($this->rows as $row) {
            if (!$row->isEmpty()) {
                $result[] = $row;
            }
        }
        return $result;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @return boolean
     */
    public function isEmpty()
    {
        return count($this->getRows()) === 0;
    }

    /**
     * @param string $mode
     *
     * @return FormLayoutGroup
     */
    public function setMode($mode) {
        $this->mode = $mode;
        return $this;
    }

    /**
     * @return string
     */
    public function getMode() {
        return $this->mode;
    }

    /**
     * @return boolean
     */
    public function isHorizontal() {
        return $this->mode === FormLayoutMode::HORIZONTAL;
    }

    /**
     * @param bool $value
     * @return FormLayoutGroup
     */
    public function setVisible($value) {
        $this->visible = $value;
        return $this;
    }

    /** @return bool */
    public function getVisible() {
        return $this->visible;
    }

    /**
     * @param string $customAttributes
     * @return FormLayoutGroup
     */
    public function setCustomAttributes($customAttributes) {
        $this->customAttributes = $customAttributes;
        return $this;
    }

    /** @return string */
    public function getCustomAttributes() {
        return $this->customAttributes;
    }

    /**
     * @param string $inlineStyles
     * @return FormLayoutGroup
     */
    public function setInlineStyles($inlineStyles) {
        $this->inlineStyles = $inlineStyles;
        return $this;
    }

    /** @return string */
    public function getInlineStyles() {
        return $this->inlineStyles;
    }

}
