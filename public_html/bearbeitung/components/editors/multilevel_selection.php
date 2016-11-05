<?php

include_once dirname(__FILE__) . '/' . 'custom.php';
include_once dirname(__FILE__) . '/' . '../utils/array_wrapper.php';
include_once dirname(__FILE__) . '/' . '../dataset/dataset.php';
include_once dirname(__FILE__) . '/' . '../utils/link_builder.php';


class ForeignKeyInfo
{
    private $parentFieldName;
    private $childFieldName;

    public function __construct($parentFieldName, $childFieldName)
    {
        $this->parentFieldName = $parentFieldName;
        $this->childFieldName = $childFieldName;
    }

    public function GetParentFieldName() { return $this->parentFieldName; }

    public function GetChildFieldName() { return $this->childFieldName; }
}

class MultiLevelComboBoxLevelInfo
{
    private $name;
    private $dataUrl;
    private $parentEditor;
    private $dataset;
    private $idFieldName;
    private $captionFieldName;
    private $foreignKey;
    private $caption;

    private $value;
    private $displayValue;

    /** @var string */
    private $formatResult;

    /** @var string */
    private $formatSelection;

    public function __construct($name, $dataUrl, $parentEditor,
        Dataset $dataset, $idFieldName, $captionFieldName, $caption, ForeignKeyInfo $foreignKey = null)
    {
        $this->name = $name;
        $this->dataUrl = $dataUrl;
        $this->parentEditor = $parentEditor;
        $this->dataset = $dataset;
        $this->idFieldName = $idFieldName;
        $this->captionFieldName = $captionFieldName;
        $this->foreignKey = $foreignKey;
        $this->caption = $caption;
    }

    public function getName() { return $this->name; }

    public function SetName($value) { $this->name = $value; }

    public function GetDataUrl() { return $this->dataUrl; }

    public function GetParentEditor() { return $this->parentEditor; }
    public function SetParentEditor($value) { $this->parentEditor = $value; }

    public function GetDataset() { return $this->dataset; }

    public function GetIdFieldName() { return $this->idFieldName; }

    public function GetCaptionFieldName() { return $this->captionFieldName; }

    public function GetForeignKey() { return $this->foreignKey; }

    public function GetValue() { return $this->value; }

    public function SetValue($value) { $this->value = $value; }

    public function GetDisplayValue() { return $this->displayValue; }

    public function SetDisplayValue($displayValue) { $this->displayValue = $displayValue; }

    public function GetCaption() { return $this->caption; }

    /**
     * @param string $formatResult
     */
    public function setFormatResult($formatResult)
    {
        $this->formatResult = $formatResult;
    }

    /**
     * @return string
     */
    public function getFormatResult()
    {
        return $this->formatResult;
    }

    /**
     * @param string $formatSelection
     */
    public function setFormatSelection($formatSelection)
    {
        $this->formatSelection = $formatSelection;
    }

    /**
     * @return string
     */
    public function getFormatSelection()
    {
        return $this->formatSelection;
    }
}

class MultiLevelComboBoxEditor extends CustomEditor
{
    /** @var MultiLevelComboBoxLevelInfo[] */
    private $levels = array();
    private $value;
    private $linkBuilder;

    /** @var bool */
    private $allowClear = false;

    /** @var int */
    private $minimumInputLength = 0;

    public function __construct($name, LinkBuilder $linkBuilder) {
        parent::__construct($name);
        $this->linkBuilder = $linkBuilder;
    }

    public function SetName($value) {
        parent::SetName($value);

        $levelNumber = 0;
        foreach($this->GetLevels() as $level)
        {
            $level->SetName($this->getEditorId($levelNumber));
            if ($levelNumber > 0)
                $level->SetParentEditor($this->getEditorId($levelNumber - 1));
            $levelNumber++;
        }
    }

    private function getEditorId($level) {
        return $this->GetName() . '_editor_level_' . $level;
    }

    private function getHttpHandlerName()
    {
        return $this->GetName() . $this->GetLevelCount() . '_h';
    }

    public function createHttpHandler(Dataset $dataset, $idFieldName, $captionFieldName, ForeignKeyInfo $foreignKey = null, ArrayWrapper $arrayWrapper)
    {
        return new MultiLevelSelectionHandler(
            $this->getHttpHandlerName(),
            $dataset, $idFieldName, $captionFieldName,
            $foreignKey == null ? null : $foreignKey->GetChildFieldName(),
            $arrayWrapper
        );
    }

    public function addLevel(Dataset $dataset, $idFieldName, $captionFieldName, $caption, ForeignKeyInfo $foreignKey = null) {
        $linkBuilder = $this->linkBuilder->CloneLinkBuilder();
        $linkBuilder->AddParameter(OPERATION_HTTPHANDLER_NAME_PARAMNAME, $this->getHttpHandlerName());

        $level = new MultiLevelComboBoxLevelInfo(
            $this->getEditorId($this->GetLevelCount()),
            $linkBuilder->GetLink(),
            $this->GetLevelCount() == 0 ?
                null :
                $this->getEditorId($this->GetLevelCount() - 1),
            $dataset, $idFieldName, $captionFieldName, $caption, $foreignKey
        );

        $this->levels[] = $level;

        return $level;
    }

    public function ProcessLevelValues()
    {
        /** @var $reversedLevels MultiLevelComboBoxLevelInfo[] */
        $reversedLevels = array_reverse($this->levels);

        $isFirstLevel = true;
        $parentIdFieldName = '';
        $parentIdValue = '';
        foreach($reversedLevels as $level)
        {
            $dataset = $level->GetDataset();

            if ($isFirstLevel)
            {
                $dataset->AddFieldFilter(
                    $level->GetIdFieldName(),
                    FieldFilter::Equals($this->value)
                );
                $isFirstLevel = false;
            }
            else
            {
                $dataset->AddFieldFilter(
                    $parentIdFieldName,
                    FieldFilter::Equals($parentIdValue)
                );
            }

            $dataset->Open();
            if ($dataset->Next())
            {
                $level->SetDisplayValue($dataset->GetFieldValueByName($level->GetCaptionFieldName()));
                $level->SetValue($dataset->GetFieldValueByName($level->GetIdFieldName()));
            }
            $dataset->Close();

            if ($level->GetForeignKey() != null)
            {
                $parentIdFieldName = $level->GetForeignKey()->GetParentFieldName();
                $parentIdValue = $dataset->GetFieldValueByName($level->GetForeignKey()->GetChildFieldName());
            }
        }
    }

    public function GetValue()
    {
        return $this->value;
    }

    public function GetDisplayValue()
    {
        $lastLevel = end($this->levels);

        return $lastLevel->GetDisplayValue();
    }

    public function SetValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return MultiLevelComboBoxLevelInfo[]
     */
    public function GetLevels()
    {
        return $this->levels;
    }

    public function GetLevelCount()
    {
        return count($this->levels);
    }

    public function extractValueFromArray(ArrayWrapper $arrayWrapper, &$changed)
    {
        $editorName = $this->getEditorId($this->GetLevelCount() - 1);
        if ($arrayWrapper->isValueSet($editorName))
        {
            $changed = true;

            $value = $arrayWrapper->getValue($editorName);
            return $value;
        }
        else
        {
            $changed = false;
            return null;
        }
    }

    /**
     * @param bool $value
     */
    public function setAllowClear($value)
    {
        $this->allowClear = (bool) $value;
    }

    /**
     * @return bool
     */
    public function getAllowClear()
    {
        return $this->allowClear;
    }

    /**
     * @param int $value
     */
    public function setMinimumInputLength($value)
    {
        $this->minimumInputLength = (int) $value;
    }

    /**
     * @return int
     */
    public function getMinimumInputLength()
    {
        return $this->minimumInputLength;
    }

    /**
     * @return string
     */
    public function getEditorName()
    {
        return 'multilevel_selection';
    }
}
