<?php

include_once dirname(__FILE__) . '/custom_edit_column.php';
include_once dirname(__FILE__) . '/../../utils/dataset_utils.php';
include_once dirname(__FILE__) . '/../../utils/string_utils.php';

class LookUpEditColumn extends CustomEditColumn
{
    /** @var stirng */
    private $linkFieldName;

    /** @var string */
    private $displayFieldName;

    /** @var Dataset */
    private $lookUpDataset;

    /** @var string|null */
    private $captionTemplate;

    /** @var string */
    private $insertFormLink;

    /** @var array|null */
    private $lookupValues;
    /**
     * @param string $caption
     * @param string $fieldName
     * @param CustomEditor $editControl
     * @param Dataset $dataset
     * @param string $linkFieldName
     * @param string $displayFieldName
     * @param Dataset $lookUpDataset
     */
    public function __construct($caption, $fieldName, $editControl, $dataset,
        $linkFieldName, $displayFieldName, $lookUpDataset)
    {
        parent::__construct($caption, $fieldName, $editControl, $dataset);
        $this->linkFieldName = $linkFieldName;
        $this->displayFieldName = $displayFieldName;
        $this->lookUpDataset = $lookUpDataset;
        $this->captionTemplate = null;
    }

    private function GetLookupValues()
    {
        if (is_null($this->lookupValues)) {
            $this->lookupValues = array();
            $this->lookUpDataset->Open();

            while ($this->lookUpDataset->Next()) {
                $linkValue = $this->lookUpDataset->GetFieldValueByName($this->linkFieldName);
                $this->lookupValues[$linkValue] = StringUtils::IsNullOrEmpty($this->captionTemplate)
                    ? $this->lookUpDataset->GetFieldValueByName($this->displayFieldName)
                    : DatasetUtils::FormatDatasetFieldsTemplate($this->lookUpDataset, $this->captionTemplate);
            }

            $this->lookUpDataset->Close();
        }

        return $this->lookupValues;
    }

    public function IsValueNull()
    {
        if (GetOperation() == OPERATION_INSERT)
            return false;
        else
        {
            $value = $this->GetDataset()->GetFieldValueByName($this->GetFieldName());
            return !isset($value);
        }
    }

    public function PrepareEditorControl()
    {
        foreach($this->GetLookupValues() as $name => $value) {
            $this->GetEditControl()->addChoice($name, $value);
        }
    }

    public function SetControlValuesFromDataset()
    {
        $this->PrepareEditorControl();
        parent::SetControlValuesFromDataset();
    }

    public function GetCaptionTemplate() { return $this->captionTemplate; }

    public function SetCaptionTemplate($value) { $this->captionTemplate = $value; }

    /**
     * @param string $insertFormLink
     */
    public function setNestedInsertFormLink($insertFormLink)
    {
        $this->insertFormLink = $insertFormLink;
    }

    public function getViewData()
    {
        return array_merge(parent::getViewData(), array(
            'NestedInsertFormLink' => $this->insertFormLink,
            'DisplayFieldName' => $this->displayFieldName,
        ));
    }
}
