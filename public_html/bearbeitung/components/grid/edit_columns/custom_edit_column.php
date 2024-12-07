<?php

include_once dirname(__FILE__) . '/../columns/column_interface.php';

class CustomEditColumn implements ColumnInterface
{
    private $caption;
    private $editControl;
    private $fieldName;
    private $dataset;

    /** @var Grid */
    private $grid;

    private $allowSetToNull;
    private $allowSetToDefault;
    private $insertDefaultValue;

    private $commitOperations = array(OPERATION_COMMIT_EDIT, OPERATION_COMMIT_MULTI_EDIT, OPERATION_COMMIT_INSERT, OPERATION_COMMIT_MULTI_UPLOAD);
    private $editOperations = array(OPERATION_EDIT, OPERATION_INSERT, OPERATION_COPY);
    private $fieldIsReadOnly;
    private $displaySetToNullCheckBox;
    private $displaySetToDefaultCheckBox;
    private $readOnly;
    private $variableContainer;

    private $useHTMLFilter;
    private $htmlFilterString;

    private $allowListCellEdit = true;
    private $allowSingleViewCellEdit = true;
    /** @var string */
    private $hint = '';

        /**
     * @param string $caption
     * @param string $fieldName
     * @param CustomEditor $editControl
     * @param Dataset $dataset
     * @param bool $allowSetToNull
     * @param bool $allowSetToDefault
     */
    public function __construct($caption, $fieldName, $editControl, $dataset, $allowSetToNull = false, $allowSetToDefault = false)
    {
        $this->caption = $caption;
        $this->editControl = $editControl;
        $this->fieldIsReadOnly = is_null($dataset->GetFieldByName($fieldName)) || $dataset->isOwnerFieldName($fieldName);

        $this->editControl->SetReadOnly($this->fieldIsReadOnly);
        $this->editControl->SetFieldName($fieldName);

        $this->fieldName = $fieldName;
        $this->dataset = $dataset;
        $this->SetAllowSetToNull($allowSetToNull);
        $this->allowSetToDefault = $allowSetToDefault;
        $this->displaySetToNullCheckBox = false;
        $this->displaySetToDefaultCheckBox = false;
        $this->readOnly = false;
        $this->SetVariableContainer(null);
        $this->setVisible(true);
        $this->setEnabled(true);
    }

    /**
     * @return string
     */
    public function GetFieldName()
    { return $this->fieldName; }

    final public function GetName()
    {
        return $this->GetFieldName();
    }

    /**
     * @return string
     */
    public function GetCaption()
    { return $this->caption; }

    /**
     * @param string
     */
    public function setCaption($value) {
        $this->caption = $value;
    }

    /**
     * @return CustomEditor
     */
    public function GetEditControl()
    { return $this->editControl; }

    /**
     * @return Dataset
     */
    public function GetDataset()
    { return $this->dataset; }

    public function GetAllowSetToNull()
    {
        return $this->allowSetToNull && !($this->fieldIsReadOnly || $this->readOnly);
    }
    public function SetAllowSetToNull($value)
    {
        $this->allowSetToNull = $value;
        //$this->GetEditControl()->SetAllowNullValue($value);
    }

    /**
     * @return boolean
     */
    public function DisplayAsRequired()
    {
        return !$this->allowSetToNull;
    }

    public function GetAllowSetToDefault()
    { return $this->allowSetToDefault && !($this->fieldIsReadOnly || $this->readOnly); }
    public function SetAllowSetToDefault($value)
    { $this->allowSetToDefault = $value; }

    public function GetInsertDefaultValue()
    { return $this->insertDefaultValue; }
    public function SetInsertDefaultValue($value)
    { $this->insertDefaultValue = $value; }

    /**
     * @param null|IVariableContainer $variableContainer
     */
    public function SetVariableContainer($variableContainer = null)
    {
        if ($variableContainer == null)
            $this->variableContainer = new NullVariableContainer();
        else
            $this->variableContainer = $variableContainer;
    }

    public function GetDisplaySetToNullCheckBox()
    {
        if ($this->GetEditControl()->CanSetupNullValues())
            return false;
        else
            return  $this->GetAllowSetToNull() && $this->displaySetToNullCheckBox;
    }

    public function SetDisplaySetToNullCheckBox($value)
    { $this->displaySetToNullCheckBox = $value; }

    public function GetDisplaySetToDefaultCheckBox()
    {
        return  $this->GetAllowSetToDefault() && $this->displaySetToDefaultCheckBox;
    }

    public function SetDisplaySetToDefaultCheckBox($value)
    {
        $this->displaySetToDefaultCheckBox = $value;
    }

    public function GetGrid()
    {
        return $this->grid;
    }

    /**
     * @param Grid $value
     * @return void
     */
    public function SetGrid(Grid $value)
    {
        $this->grid = $value;
    }

    public function GetSetToNullFromPost()
    {
        return
            GetApplication()->IsPOSTValueSet($this->GetFieldName() . '_null') &&
            GetApplication()->GetPOSTValue($this->GetFieldName() . '_null') == 1;
    }

    public function GetSetToDefaultFromPost()
    {
        return
            GetApplication()->IsPOSTValueSet($this->GetFieldName() . '_def') &&
            GetApplication()->GetPOSTValue($this->GetFieldName() . '_def') == 1;
    }

    public function SetControlValuesFromPost()
    {
        $valueChanged = true;
        $value = $this->editControl->extractValueFromArray(ArrayWrapper::createPostWrapper(), $valueChanged);
        $this->editControl->SetValue($value);
    }

    public function PrepareEditorControl()
    { }

    protected function CheckValueIsCorrect($value)
    { }

    public function DoSetDatasetValuesFromPost($value) {
        if (StringUtils::IsNullOrEmpty($value) && $this->allowSetToNull)
            $this->dataset->SetFieldValueByName($this->GetFieldName(), null);
        else
            $this->dataset->SetFieldValueByName($this->GetFieldName(),
                $this->editControl->prepareValueForDataset($value));
    }

    public function SetDatasetValuesFromPost()
    {
        $valueChanged = true;
        $value = $this->editControl->extractValueFromArray(ArrayWrapper::createPostWrapper(), $valueChanged);
        $this->SetControlValuesFromPost();

        if ($valueChanged)
        {
            $this->CheckValueIsCorrect($value);
            if ($this->GetSetToNullFromPost())
                $this->dataset->SetFieldValueByName($this->GetFieldName(), null);
            elseif ($this->GetSetToDefaultFromPost())
                $this->dataset->SetFieldValueByName($this->GetFieldName(), null, true);
            else
                $this->DoSetDatasetValuesFromPost($value);
        }
    }

    public function GetValue() {
        return $this->dataset->GetFieldValueByName($this->GetFieldName());
    }

    public function IsValueNull()
    {
        if ((GetOperation() == OPERATION_INSERT) || (GetOperation() == OPERATION_MULTI_EDIT))
            return false;
        else
        {
            $value = $this->dataset->GetFieldValueByName($this->GetFieldName());
            return !isset($value);
        }
    }

    public function IsValueSetToDefault()
    {
        return $this->GetDataset()->GetFieldByName($this->GetFieldName())->GetIsAutoincrement();
    }

    public function DoSetDefaultValues()
    {
        $insertValue = $this->GetInsertDefaultValue();
        $insertValue = EnvVariablesUtils::EvaluateVariableTemplate($this->variableContainer, $insertValue);
        $this->editControl->SetValue($insertValue);
    }

    public function SetReadOnly($value)
    {
        $this->readOnly = $value;
        $this->GetEditControl()->SetReadOnly($value || $this->fieldIsReadOnly);
    }

    public function GetReadOnly()
    { return $this->readOnly; }

    /**
     * @param mixed $value
     */
    public function setControlValue($value) {
        $this->editControl->SetValue($value);
    }

    public function SetControlValuesFromDataset()
    {
        if (!$this->dataset->isOwnerFieldName($this->fieldName))
        {
            if (GetOperation() == OPERATION_EDIT)
            {
                $this->editControl->SetValue(
                    $this->dataset->GetFieldValueByName($this->GetFieldName())
                    );
            }
            elseif (GetOperation() == OPERATION_COPY)
            {
                $this->editControl->SetValue(
                    $this->dataset->GetFieldValueByName($this->GetFieldName())
                    );
                $masterFieldValue = $this->dataset->GetMasterFieldValueByName($this->fieldName);
                if (isset($masterFieldValue))
                    $this->editControl->SetValue($masterFieldValue);

            }
            elseif (GetOperation() == OPERATION_INSERT)
            {
                $masterFieldValue = $this->dataset->GetMasterFieldValueByName($this->fieldName);
                if (!isset($masterFieldValue))
                    $this->DoSetDefaultValues();
                else
                    $this->editControl->SetValue($masterFieldValue);
            }
        }
        else {
            $this->editControl->SetValue($this->dataset->getRlsPolicy()->getOwnerId());
        }
    }

    public function ProcessMessages()
    {
        $operation = GetOperation();
        if (in_array($operation, $this->commitOperations))
            $this->SetDatasetValuesFromPost();
        elseif(in_array($operation, $this->editOperations))
            $this->SetControlValuesFromDataset();
    }

    public function AfterSetAllDatasetValues()
    { }

    /**
     * @return bool
     */
    public function getVisible() {
        return $this->GetEditControl()->getVisible();
    }

    /**
     * @param bool $value
     * @return void
     */
    public function setVisible($value) {
        $this->GetEditControl()->setVisible($value);
    }

    /**
     * @return bool
     */
    public function getEnabled() {
        return $this->GetEditControl()->getEnabled();
    }

    /**
     * @param bool $value
     * @return void
     */
    public function setEnabled($value) {
        $this->GetEditControl()->setEnabled($value);
    }

    /**
     * @return bool
     */
    public function getUseHTMLFilter() {
        return $this->useHTMLFilter;
    }

    /**
     * @param bool $value
     */
    public function setUseHTMLFilter($value) {
        $this->useHTMLFilter = $value;
    }

    /** @return string */
    public function getHTMLFilterString() {
        return $this->htmlFilterString;
    }

    /** @param string $value */
    public function setHTMLFilterString($value) {
        $this->htmlFilterString = $value;
    }

    public function getDisplayValue(Renderer $renderer)
    {
    }

    /**
     * @return array
     */
    public function getViewData()
    {
        return array(
            'FieldName' => $this->GetFieldName(),
            'Id' => $this->GetEditControl()->GetName(),
            'EditorViewData' => $this->getEditControl()->getViewData(),
            'Caption' => $this->GetCaption(),
            'Required' => $this->DisplayAsRequired(),
            'EditorHint' => $this->getHint(),
            'DisplaySetToNullCheckBox' => $this->GetDisplaySetToNullCheckBox(),
            'DisplaySetToDefaultCheckBox' => $this->GetDisplaySetToDefaultCheckBox(),
            'IsValueNull' => $this->IsValueNull(),
            'IsValueSetToDefault' => $this->IsValueSetToDefault(),
            'SetNullCheckBoxName' => $this->GetFieldName() . '_null',
            'SetDefaultCheckBoxName' => $this->GetFieldName() . '_def',
        );
    }

    public function setAllowListCellEdit($allowListCellEdit)
    {
        $this->allowListCellEdit = $allowListCellEdit;
    }

    public function setAllowSingleViewCellEdit($allowSingleViewCellEdit)
    {
        $this->allowSingleViewCellEdit = $allowSingleViewCellEdit;
    }

    public function getAllowListCellEdit()
    {
        return $this->allowListCellEdit;
    }

    public function getAllowSingleViewCellEdit()
    {
        return $this->allowSingleViewCellEdit;
    }

    /** @return Validator[] */
    public function getValidators() {
        return $this->GetEditControl()->GetValidatorCollection()->getItems();
    }

    /** @return string */
    public function getHint() {
        return $this->hint;
    }

    /** @param string $value */
    public function setHint($value) {
        $this->hint = $value;
    }

}
