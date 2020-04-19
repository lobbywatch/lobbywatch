<?php

include_once dirname(__FILE__) . '/' . 'custom.php';
include_once dirname(__FILE__) . '/' . '../utils/link_builder.php';
include_once dirname(__FILE__) . '/' . '../dataset/dataset.php';
include_once dirname(__FILE__) . '/' . '../page/page.php';

class ForeignKeyInfo {
    /** @var  string */
    private $parentFieldName;
    /** @var  string */
    private $childFieldName;

    /**
     * @param string $parentFieldName
     * @param string $childFieldName
     */
    public function __construct($parentFieldName, $childFieldName)
    {
        $this->parentFieldName = $parentFieldName;
        $this->childFieldName = $childFieldName;
    }

    /** @return string */
    public function getParentFieldName() {
        return $this->parentFieldName;
    }

    /** @return string */
    public function getChildFieldName() {
        return $this->childFieldName;
    }
}

class CascadingEditorLevel {
    /** @var string */
    private $caption;
    /** @var string */
    private $name;
    /** @var string|null */
    private $parentLevelName;
    /** @var string */
    private $dataUrl;
    /** @var Dataset */
    private $dataset;
    /** @var string */
    private $idFieldName;
    /** @var string */
    private $captionFieldName;
    /** @var ForeignKeyInfo|null */
    private $foreignKey;
    /** @var  string */
    private $value;
    /** @var  string */
    private $displayValue;
    /** @var string|null */
    private $nestedInsertFormLink = null;

    /**
     * @param string $caption
     * @param string $levelName
     * @param string|null $parentLevelName
     * @param string $link
     * @param Dataset $dataset
     * @param string $idFieldName
     * @param string $captionFieldName
     * @param ForeignKeyInfo|null $foreignKey
     * @return CascadingEditorLevel
     */
    public function __construct($caption, $levelName, $parentLevelName, $link, $dataset, $idFieldName, $captionFieldName, $foreignKey) {
        $this->caption = $caption;
        $this->name = $levelName;
        $this->parentLevelName = $parentLevelName;
        $this->dataUrl = $link;
        $this->dataset = $dataset;
        $this->idFieldName = $idFieldName;
        $this->captionFieldName = $captionFieldName;
        $this->foreignKey = $foreignKey;
    }

    /** @return string */
    public function getCaption() {
        return $this->caption;
    }

    /** @return string */
    public function getName() {
        return $this->name;
    }

    /** @return string|null */
    public function getParentLevelName() {
        return $this->parentLevelName;
    }

    /** @return string */
    public function getDataUrl() {
        return $this->dataUrl;
    }

    /** @return Dataset */
    public function getDataset() {
        return $this->dataset;
    }

    /** @return string */
    public function getIdFieldName() {
        return $this->idFieldName;
    }

    /** @return string */
    public function getCaptionFieldName() {
        return $this->captionFieldName;
    }

    /** @return ForeignKeyInfo|null */
    public function getForeignKey() {
        return $this->foreignKey;
    }

    /** @return string */
    public function getValue() {
        return $this->value;
    }

    /** @param string $value */
    public function setValue($value) {
        $this->value = $value;
    }

    /** @return string */
    public function getDisplayValue() {
        return $this->displayValue;
    }

    /** @param string $value */
    public function setDisplayValue($value) {
        $this->displayValue = $value;
    }

    /** @return string */
    public function getNestedInsertFormLink() {
        return $this->nestedInsertFormLink;
    }

    /** @param $value */
    public function setNestedInsertFormLink($value) {
        $this->nestedInsertFormLink = $value;
    }

    /** @return string */
    public function getParentLinkFieldName() {
        if (isset($this->foreignKey)) {
            return $this->foreignKey->getChildFieldName();
        } else {
            return '';
        }
    }
}

abstract class CascadingEditor extends CustomEditor {
    /** @var  string */
    private $value;
    /** @var CascadingEditorLevel[] */
    private $levels = array();
    /** @var LinkBuilder */
    private $linkBuilder;

    /**
     * @param string $name
     * @param LinkBuilder $linkBuilder
     */
    public function __construct($name, $linkBuilder) {
        parent::__construct($name);
        $this->linkBuilder = $linkBuilder;
    }

    /** @inheritdoc */
    public function GetValue() {
        return $this->value;
    }

    /** @inheritdoc */
    public function SetValue($value) {
        $this->value = $value;
    }

    /** @inheritdoc */
    public function GetDisplayValue() {
        $lastLevel = end($this->levels);
        return $lastLevel->GetDisplayValue();
    }

    /**
     * @param Dataset $dataset
     * @param string $idFieldName
     * @param string $captionFieldName
     * @param string $caption
     * @param ForeignKeyInfo|null $foreignKey
     * @return CascadingEditorLevel
     */
    public function addLevel(Dataset $dataset, $idFieldName, $captionFieldName, $caption, $foreignKey = null) {
        $linkBuilder = $this->linkBuilder->CloneLinkBuilder();
        $linkBuilder->AddParameter(OPERATION_HTTPHANDLER_NAME_PARAMNAME, $this->getHttpHandlerName());

        $level = $this->createLevel($caption,
            $this->getLevelName($this->getLevelCount()),
            $this->getLevelCount() == 0 ? null : $this->getLevelName($this->getLevelCount() - 1),
            $linkBuilder->GetLink(),
            $dataset, $idFieldName, $captionFieldName, $foreignKey
        );

        $this->levels[] = $level;
        return $level;
    }

    /**
     * @param string $caption
     * @param string $levelName
     * @param string|null $parentLevelName
     * @param string $link
     * @param Dataset $dataset
     * @param string $idFieldName
     * @param string $captionFieldName
     * @param ForeignKeyInfo|null $foreignKey
     * @return CascadingEditorLevel
     */
    abstract function createLevel($caption, $levelName, $parentLevelName, $link, $dataset, $idFieldName, $captionFieldName, $foreignKey);

    /**
     * @return CascadingEditorLevel[]
     */
    public function getLevels() {
        return $this->levels;
    }

    /** @return int */
    public function getLevelCount() {
        return count($this->levels);
    }

    /**
     * @param int $value
     * @return string
     */
    private function getLevelName($value) {
        return $this->GetName() . '_editor_level_' . $value;
    }

    public function createHttpHandler(Dataset $dataset, $idFieldName, $captionFieldName, ForeignKeyInfo $foreignKey = null, ArrayWrapper $arrayWrapper) {
        return new MultiLevelSelectionHandler(
            $this->getHttpHandlerName(),
            $dataset, $idFieldName, $captionFieldName,
            $foreignKey == null ? null : $foreignKey->GetChildFieldName(),
            $arrayWrapper,
            $this->getNumberOfValuesToDisplay()
        );
    }

    /** @return string */
    private function getHttpHandlerName() {
        return $this->GetName() . $this->GetLevelCount() . '_h';
    }

    /** @return int */
    abstract function getNumberOfValuesToDisplay();

    /** @inheritdoc */
    public function extractValueFromArray(ArrayWrapper $arrayWrapper, &$changed) {
        $editorName = $this->getLevelName($this->getLevelCount() - 1);
        if ($arrayWrapper->isValueSet($editorName)) {
            $changed = true;

            $value = $arrayWrapper->getValue($editorName);
            return $value;
        } else {
            $changed = false;
            return null;
        }
    }

    public function ProcessLevelValues() {
        /** @var $reversedLevels CascadingEditorLevel[] */
        $reversedLevels = array_reverse($this->levels);

        $isFirstLevel = true;
        $parentIdFieldName = '';
        $parentIdValue = '';
        foreach($reversedLevels as $level) {
            $dataset = $level->getDataset();
            $dataset->ClearFieldFilters();

            if ($isFirstLevel) {
                $dataset->AddFieldFilter(
                    $level->getIdFieldName(),
                    FieldFilter::Equals($this->value)
                );
                $isFirstLevel = false;
            } else {
                $dataset->AddFieldFilter(
                    $parentIdFieldName,
                    FieldFilter::Equals($parentIdValue)
                );
            }

            $dataset->Open();
            if ($dataset->Next()) {
                $level->setDisplayValue($dataset->GetFieldValueByName($level->getCaptionFieldName()));
                $level->setValue($dataset->GetFieldValueByName($level->getIdFieldName()));
            }
            $dataset->Close();

            if ($level->getForeignKey() != null) {
                $parentIdFieldName = $level->getForeignKey()->getParentFieldName();
                $parentIdValue = $dataset->GetFieldValueByName($level->getForeignKey()->getChildFieldName());

            }
        }
    }

}
