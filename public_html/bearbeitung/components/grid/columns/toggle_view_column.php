<?php

class ToggleViewColumn extends AbstractDatasetFieldViewColumn
{
    /** @var string */
    private $onToggleCaption;

    /** @var string */
    private $offToggleCaption;

    /** @var boolean */
    private $allowEditing = false;

    /** @var string|null */
    private $editorName;

    /** @var string */
    private $toggleSize = 'small';

    /** @var string */
    private $onToggleStyle = 'primary';

    /** @var string */
    private $offToggleStyle = 'default';

    /**
     * @param string       $fieldName
     * @param string       $datasetFieldName
     * @param string       $caption
     * @param Dataset      $dataset
     * @param string       $onToggleCaption
     * @param string       $offToggleCaption
     * @param boolean      $allowEditing
     * @param string|null  $editorName
     */
    public function __construct($fieldName, $datasetFieldName, $caption, $dataset, $onToggleCaption, $offToggleCaption, $allowEditing, $editorName = null)
    {
        parent::__construct($fieldName, $datasetFieldName, $caption, $dataset, false);

        $this->onToggleCaption = $onToggleCaption;
        $this->offToggleCaption = $offToggleCaption;
        $this->allowEditing = $allowEditing;
        $this->editorName = $editorName;
    }

    /** @return string */
    public function getOnToggleCaption() {
        return $this->onToggleCaption;
    }

    /** @param string $value */
    public function setOnToggleCaption($value) {
        $this->onToggleCaption = $value;
    }

    /** @return string */
    public function getOffToggleCaption() {
        return $this->offToggleCaption;
    }

    /** @param string $value */
    public function setOffToggleCaption($value) {
        $this->offToggleCaption = $value;
    }

    /** @return string */
    public function getToggleSize() {
        return $this->toggleSize;
    }

    /** @param string $value */
    public function setToggleSize($value) {
        $this->toggleSize = $value;
    }

    /** @return string */
    public function getOnToggleStyle() {
        return $this->onToggleStyle;
    }

    /** @param string $value */
    public function setOnToggleStyle($value) {
        $this->onToggleStyle = $value;
    }

    /** @return string */
    public function getOffToggleStyle() {
        return $this->offToggleStyle;
    }

    /** @param string $value */
    public function setOffToggleStyle($value) {
        $this->offToggleStyle = $value;
    }

    /** @return boolean */
    public function getAllowEditing() {
        return $this->allowEditing;
    }

    /** @return string */
    public function getEditingLink() {
        $result = $this->GetGrid()->CreateLinkBuilder();
        $result->AddParameter('hname', $this->GetGrid()->GetPage()->GetGridEditHandler());

        return $result->GetLink();
    }

    /** @return string|null */
    public function getEditorName() {
        return $this->editorName;
    }

    public function Accept($renderer)
    {
        $renderer->RenderToggleViewColumn($this);
    }
}
