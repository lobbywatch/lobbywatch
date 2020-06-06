<?php

include_once dirname(__FILE__) . '/../columns/column_interface.php';

class FilterColumn
{
    /**
     * @var Dataset
     */
    private $dataset;

    /**
     * @var string
     */
    private $fieldName;

    /**
     * @var string
     */
    private $displayFieldName;

    /**
     * @var string
     */
    private $caption;

    /**
     * @param Dataset $dataset
     * @param string  $fieldName
     * @param string  $displayFieldName
     * @param string  $caption
     */
    public function __construct(Dataset $dataset = null, $fieldName, $displayFieldName, $caption)
    {
        $this->dataset = $dataset;
        $this->fieldName = $fieldName;
        $this->displayFieldName = $displayFieldName;
        $this->caption = $caption;
    }

    /**
     * @return string
     */
    public function getFieldName()
    {
        return $this->fieldName;
    }

    /**
     * @return string
     */
    public function getDisplayFieldName()
    {
        return $this->displayFieldName;
    }

    /**
     * @return string
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * @param string $value
     */
    public function setCaption($value) {
        $this->caption = $value;
    }

    /**
     * @return FieldInfo
     */
    public function getFieldInfo()
    {
        if (is_null($this->dataset)) {
            return null;
        }

        return $this->dataset->getSelectCommand()->getFieldByName(
            $this->fieldName
        );
    }

    /**
     * @return FieldInfo
     */
    public function getDisplayFieldInfo()
    {
        if (is_null($this->dataset)) {
            return null;
        }

        return $this->dataset->getSelectCommand()->getFieldByName(
            $this->displayFieldName
        );
    }

    /**
     * @return boolean
     */
    public function typeIsDateTime()
    {
        if (!is_null($this->getFieldInfo())) {
            return (($this->getFieldInfo()->FieldType == ftDate) || ($this->getFieldInfo()->FieldType == ftDateTime));
        } else {
            return false;
        }
    }

    /**
     * @return boolean
     */
    public function typeIsDateTimeExactly()
    {
        if (!is_null($this->getFieldInfo())) {
            return ($this->getFieldInfo()->FieldType == ftDateTime);
        } else {
            return false;
        }
    }

}
