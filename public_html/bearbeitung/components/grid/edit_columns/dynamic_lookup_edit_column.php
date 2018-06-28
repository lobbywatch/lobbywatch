<?php

include_once dirname(__FILE__) . '/custom_edit_column.php';
include_once dirname(__FILE__) . '/../../utils/string_utils.php';

class DynamicLookupEditColumn extends CustomEditColumn
{
    /** @var string */
    private $displayFieldName;

    /** @var \Dataset */
    private $lookupDataset;

    /** @var string */
    private $lookupIdFieldName;

    /** @var string */
    private $lookupDisplayFieldName;

    /** @var string */
    private $insertFormLink;

    /**
     * @param string $caption
     * @param string $fieldName
     * @param string $displayFieldName
     * @param string $handlerName
     * @param DynamicCombobox $editControl
     * @param Dataset $dataset
     * @param Dataset $lookupDataset
     * @param string $lookupIdFieldName
     * @param string $lookupDisplayFieldName
     * @param string $captionTemplate
     */
    public function __construct($caption,
        $fieldName,
        $displayFieldName,
        $handlerName,
        $editControl,
        $dataset,
        $lookupDataset,
        $lookupIdFieldName,
        $lookupDisplayFieldName,
        $captionTemplate)
    {
        parent::__construct($caption, $fieldName, $editControl, $dataset);
        $this->displayFieldName = $displayFieldName;
        $editControl->SetHandlerName($handlerName);

        $this->lookupDataset = $lookupDataset;
        $this->lookupIdFieldName = $lookupIdFieldName;
        $this->lookupDisplayFieldName = $lookupDisplayFieldName;
        $this->captionTemplate = $captionTemplate;
    }

    public function PrepareEditorControl()
    {
        $this->GetEditControl()->SetDisplayValue($this->GetDisplayValueFromDataset());
    }

    private function GetDisplayValueFromDataset() {
        if (!StringUtils::IsNullOrEmpty($this->captionTemplate)) {
            $this->lookupDataset->AddFieldFilter(
                $this->lookupIdFieldName,
                new FieldFilter($this->GetDataset()->GetFieldValueByName($this->GetFieldName()), '='));

            $this->lookupDataset->Open();
            if ($this->lookupDataset->Next())
            {
                $displayValue = DatasetUtils::FormatDatasetFieldsTemplate($this->lookupDataset, $this->captionTemplate);
                return $displayValue;
            }
            $this->lookupDataset->Close();
        }
        return $this->GetDataset()->GetFieldValueByName($this->displayFieldName);
    }

    public function SetControlValuesFromDataset()
    {
        if (GetOperation() == OPERATION_EDIT)
        {
            $this->GetEditControl()->SetDisplayValue($this->GetDisplayValueFromDataset());
        }
        elseif (GetOperation() == OPERATION_COPY)
        {
            $this->GetEditControl()->SetDisplayValue($this->GetDisplayValueFromDataset());
        }
        elseif (GetOperation() == OPERATION_INSERT)
        {
            $insertDefaultValue = $this->GetInsertDefaultValue();
            if (isset($insertDefaultValue))
            {
                $this->lookupDataset->AddFieldFilter(
                    $this->lookupIdFieldName,
                    new FieldFilter($insertDefaultValue, '='));

                $this->lookupDataset->Open();
                if ($this->lookupDataset->Next())
                {
                    $displayValue = $this->lookupDataset->GetFieldValueByName($this->lookupDisplayFieldName);
                    $this->GetEditControl()->SetDisplayValue($displayValue);
                }
                $this->lookupDataset->Close();
            }
        }
        parent::SetControlValuesFromDataset();
    }

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
            'DisplayFieldName' => $this->lookupDisplayFieldName,
        ));
    }
}
