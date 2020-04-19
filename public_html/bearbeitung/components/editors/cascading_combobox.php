<?php

include_once dirname(__FILE__) . '/' . 'cascading_editor.php';

class CascadingComboboxLevel extends CascadingEditorLevel {
    /** @var array */
    private $values = array();

    /** @return array */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * @param string $value
     * @param string $displayValue
     * @return $this
     */
    public function addValue($value, $displayValue)
    {
        $this->values[$value] = $displayValue;
        return $this;
    }

    /** @return int */
    public function getValueCount() {
        return count($this->values);
    }

    public function clearValues() {
        $this->values = array();
    }
}

class CascadingCombobox extends CascadingEditor {

    /** @inheritdoc */
    public function createLevel($caption, $levelName, $parentLevelName, $link, $dataset, $idFieldName, $captionFieldName, $foreignKey) {
        return new CascadingComboboxLevel($caption, $levelName, $parentLevelName, $link, $dataset, $idFieldName, $captionFieldName, $foreignKey);
    }

    /** @inheritdoc */
    public function getEditorName() {
        return 'cascading_combobox';
    }

    /** @inheritdoc */
    public function getNumberOfValuesToDisplay() {
        return -1;
    }

    /** @inheritdoc */
    public function ProcessLevelValues()
    {
        parent::ProcessLevelValues();

        /** @var $reversedLevels CascadingComboboxLevel[] */
        $reversedLevels = array_reverse($this->getLevels());
        foreach($reversedLevels as $key => $level) {
            $dataset = $level->getDataset();
            $dataset->ClearFieldFilters();
            if (isset($reversedLevels[$key + 1]) && ($level->getForeignKey() != null)) {
                $parentLevel = $reversedLevels[$key + 1];
                $parentIdValue = $parentLevel->getValue();
                $dataset->AddFieldFilter(
                    $level->getForeignKey()->getChildFieldName(),
                    FieldFilter::Equals($parentIdValue)
                );
            }
            $level->clearValues();
            $dataset->Open();
            while ($dataset->Next()) {
                $level->addValue($dataset->GetFieldValueByName($level->getIdFieldName()), $dataset->GetFieldValueByName($level->getCaptionFieldName()));
            }
            $dataset->Close();
        }
    }

}
