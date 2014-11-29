<?php

include_once dirname(__FILE__) . '/' . '../env_variables.php';
include_once dirname(__FILE__) . '/' . '../utils/system_utils.php';
include_once dirname(__FILE__) . '/' . '../utils/file_utils.php';
include_once dirname(__FILE__) . '/' . '../utils/dataset_utils.php';

// require_once 'components/env_variables.php';
// require_once 'components/utils/system_utils.php';
// require_once 'components/utils/file_utils.php';
// require_once 'components/utils/dataset_utils.php';

class SMException extends Exception
{
    /**
     * @param Captions $captions
     * @return string
     */
    public function getLocalizedMessage($captions)
    {
        return $this->getMessage();
    }
}

class FileSizeExceedMaxSize extends SMException
{
    private $fieldName;
    private $actualFileSize;
    private $maxSize;

    public function  __construct($fieldName, $actualFileSize, $maxSize)
    {
        parent::__construct('', 0);
        $this->fieldName = $fieldName;
        $this->actualFileSize = $actualFileSize;
        $this->maxSize = $maxSize;
    }

    public function GetFieldName()
    {
        return $this->fieldName;
    }

    /**
     * @param Captions $captions
     * @return string
     */
    public function getLocalizedMessage($captions)
    {
        return sprintf($captions->GetMessageString('FileSizeExceedMaxSizeForField'), $this->fieldName, $this->actualFileSize, $this->maxSize);
    }
}

class ImageSizeExceedMaxSize extends SMException
{
    private $fieldName;
    private $actualWidth;
    private $actualHeight;
    private $maxWidth;
    private $maxHeight;

    public function  __construct($fieldName, $actualWidth, $actualHeight, $maxWidth, $maxHeight)
    {
        parent::__construct('', 0);
        $this->fieldName = $fieldName;
        $this->actualWidth = $actualWidth;
        $this->actualHeight = $actualHeight;
        $this->maxWidth = $maxWidth;
        $this->maxHeight = $maxHeight;
    }

    public function GetFieldName()
    {
        return $this->fieldName;
    }

    /**
     * @param Captions $captions
     * @return string
     */
    public function getLocalizedMessage($captions)
    {
        return sprintf($captions->GetMessageString('ImageSizeExceedMaxSizeForField'), $this->fieldName, $this->actualWidth, $this->actualHeight, $this->maxWidth, $this->maxHeight);
    }
}

class CustomEditColumn
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

    private $commitOperations = array(OPERATION_COMMIT, OPERATION_COMMIT_INSERT, OPERATION_AJAX_REQUERT_INLINE_EDIT_COMMIT, OPERATION_AJAX_REQUERT_INLINE_INSERT_COMMIT);
    private $editOperations = array(OPERATION_EDIT, OPERATION_INSERT, OPERATION_COPY, OPERATION_AJAX_REQUERT_INLINE_EDIT, OPERATION_AJAX_REQUERT_INLINE_INSERT);
    private $fieldIsReadOnly;
    private $displaySetToNullCheckBox;
    private $displaySetToDefaultCheckBox;
    private $readOnly;
    private $variableContainer;

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
        if ($dataset->GetFieldByName($fieldName) != null)
            $this->fieldIsReadOnly = $dataset->GetFieldByName($fieldName)->GetReadOnly();
        else
            $this->fieldIsReadOnly = true;
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

    /**
     * @return string
     */
    public function GetCaption()
    { return $this->caption; }

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

    public function SetVariableContainer(IVariableContainer $variableContainer = null)
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
    public function SetGrid($value)
    {
        $this->grid = $value;
        $this->caption = $this->grid->GetPage()->RenderText($this->caption);
    }

    public function Accept($renderer)
    {
        $this->editControl->Accept($renderer);
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
        $value = $this->editControl->ExtractsValueFromPost($valueChanged);
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
        $value = $this->editControl->ExtractsValueFromPost($valueChanged); 
        $this->SetControlValuesFromPost();
        
        $this->CheckValueIsCorrect($value);

        if ($valueChanged)
        {
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
        if (GetOperation() == OPERATION_INSERT)
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

    public function SetControlValuesFromDataset()
    {
        if (!$this->dataset->GetFieldByName($this->fieldName)->GetReadOnly())
        {
            
            if ((GetOperation() == OPERATION_EDIT) || (GetOperation() == OPERATION_AJAX_REQUERT_INLINE_EDIT))
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
            elseif (GetOperation() == OPERATION_INSERT || (GetOperation() == OPERATION_AJAX_REQUERT_INLINE_INSERT))
            {
                $masterFieldValue = $this->dataset->GetMasterFieldValueByName($this->fieldName);
                if (!isset($masterFieldValue))
                    $this->DoSetDefaultValues();
                else
                    $this->editControl->SetValue($masterFieldValue);
            }
        }
        else
        {
            $this->editControl->SetValue(
                $this->dataset->GetFieldByName($this->fieldName)->GetDefaultValue());
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
}

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
        $result = array();
        $this->lookUpDataset->Open();
        while ($this->lookUpDataset->Next())
        {
            $result[$this->lookUpDataset->GetFieldValueByName($this->linkFieldName)] =
                StringUtils::IsNullOrEmpty($this->captionTemplate) ?
                    $this->lookUpDataset->GetFieldValueByName($this->displayFieldName) :
                    DatasetUtils::FormatDatasetFieldsTemplate($this->lookUpDataset, $this->captionTemplate);
        }
        $this->lookUpDataset->Close();

        return $result;
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
        foreach($this->GetLookupValues() as $name => $value)
            $this->GetEditControl()->AddValue($name, $value);
    }

    public function SetControlValuesFromDataset()
    {
        $this->PrepareEditorControl();
        parent::SetControlValuesFromDataset();
    }

    public function GetCaptionTemplate() { return $this->captionTemplate; }

    public function SetCaptionTemplate($value) { $this->captionTemplate = $value; }    
}

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

    /**
     * @param string $caption
     * @param string $fieldName
     * @param string $displayFieldName
     * @param string $handlerName
     * @param AutocomleteComboBox $editControl
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
        if (GetOperation() == OPERATION_EDIT || GetOperation() == OPERATION_AJAX_REQUERT_INLINE_EDIT )
        {
            $this->GetEditControl()->SetDisplayValue($this->GetDisplayValueFromDataset());
        }
        elseif (GetOperation() == OPERATION_COPY)
        {
            $this->GetEditControl()->SetDisplayValue($this->GetDisplayValueFromDataset());

            /* $masterFieldValue = $this->dataset->GetMasterFieldValueByName($this->fieldName);
            if (isset($masterFieldValue))
                $this->editControl->SetValue($masterFieldValue); */
        }
        elseif (GetOperation() == OPERATION_INSERT || GetOperation() == OPERATION_AJAX_REQUERT_INLINE_INSERT )
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
}

class MultiLevelLookupEditColumn extends CustomEditColumn
{
    public function __construct($caption,
        $fieldName,
        $editControl,
        $dataset,
        $allowSetToNull = false, $allowSetToDefault = false)
    {
        parent::__construct($caption, $fieldName, $editControl, $dataset, $allowSetToNull, $allowSetToDefault);
    }

    public function PrepareEditorControl()
    {
        $this->GetEditControl()->SetValue($this->GetDataset()->GetFieldValueByName($this->GetFieldName()));
        $this->GetEditControl()->ProcessLevelValues();
    }

    public function SetControlValuesFromDataset()
    {
        if (GetOperation() == OPERATION_EDIT || GetOperation() == OPERATION_AJAX_REQUERT_INLINE_EDIT )
        {
            $this->GetEditControl()->SetValue(
                $this->GetDataset()->GetFieldValueByName($this->GetFieldName())
            );
            $this->GetEditControl()->ProcessLevelValues();
        }        
    }
}

class FileUploadingColumn extends CustomEditColumn
{
    private $handlerName;
    private $sizeCheckEnabled;
    private $imageSizeCheckEnabled;
    private $maxSize;
    private $maxWidth;
    private $maxHeight;
    private $fileTypeFieldName;
    private $fileNameFieldName;
    private $fileSizeFieldName;
    private $imageFilter;

    public function __construct($caption, $fieldName, $editControl, $dataset, $allowSetToNull = false, $allowSetToDefault = false, $handlerName = '')
    {
        parent::__construct($caption, $fieldName, $editControl, $dataset, $allowSetToNull, $allowSetToDefault);
        $this->handlerName = $handlerName;
        $this->sizeCheckEnabled = false;
        $this->maxSize = 0;

        $this->imageSizeCheckEnabled = false;
        $this->maxWidth = 0;
        $this->maxHeight = 0;
        $this->imageFilter = new NullFilter();
    }

    public function SetFileTypeFieldName($value) { $this->fileTypeFieldName = $value; }
    public function SetFileNameFieldName($value) { $this->fileNameFieldName = $value; }
    public function SetFileSizeFieldName($value) { $this->fileSizeFieldName = $value; }

    public function GetFileTypeFieldName() { return $this->fileTypeFieldName; }
    public function GetFileNameFieldName() { return $this->fileNameFieldName; }
    public function GetFileSizeFieldName() { return $this->fileSizeFieldName; }

    public function DoSetDatasetValuesFromPost($value)
    {
        if ($value) {
            $tempFileName = FileUtils::GetTempFileName();

            $imageString = file_get_contents($value);
            $this->imageFilter->ApplyFilter($imageString, $tempFileName);

            $this->GetDataset()->SetFieldValueAsFileNameByName($this->GetFieldName(), $tempFileName);

            DatasetUtils::SetDatasetFieldValue($this->GetDataset(),
                $this->GetFileTypeFieldName(),
                $this->GetEditControl()->ExtractFileTypeFromPost()
            );
            DatasetUtils::SetDatasetFieldValue($this->GetDataset(),
                $this->GetFileNameFieldName(),
                $this->GetEditControl()->ExtractFileNameFromPost()
            );
            ;
            DatasetUtils::SetDatasetFieldValue($this->GetDataset(),
                $this->GetFileSizeFieldName(),
                filesize($tempFileName)
            );
        }
        else {
            $this->GetDataset()->SetFieldValueByName($this->GetFieldName(), null);
        }

    }

    public function GetFullImageLink()
    {
        if (GetOperation() == OPERATION_EDIT || GetOperation() == OPERATION_AJAX_REQUERT_INLINE_EDIT ||
            GetOperation() == OPERATION_AJAX_REQUERT_INLINE_EDIT_COMMIT)
        {
            $result = $this->GetGrid()->CreateLinkBuilder();
            $result->AddParameter('hname', $this->handlerName);
            $result->AddParameter('large', '1');
            AddPrimaryKeyParameters($result, $this->GetDataset()->GetPrimaryKeyValues());
            return $result->GetLink();
        }
    }

    public function SetFileSizeCheckMode($enabled, $maxSize = 0)
    {
        if ($enabled && $maxSize <= 0)
            $this->sizeCheckEnabled = false;
        else
        {
            $this->sizeCheckEnabled = $enabled;
            $this->maxSize = $maxSize;
        }
    }

    public function SetImageSizeCheckMode($enabled, $maxWidth, $maxHeight)
    {
        if ($enabled && ($maxWidth <= 0) || ($maxHeight <= 0))
        {
            $this->imageSizeCheckEnabled = false;
            $this->maxWidth = 0;
            $this->maxHeight = 0;
        }
        else
        {
            $this->imageSizeCheckEnabled = $enabled;
            $this->maxWidth = $maxWidth;
            $this->maxHeight = $maxHeight;
        }
    }

    protected function CheckValueIsCorrect($value)
    {
        $filename = $value;
        if ($this->sizeCheckEnabled)
        {
            if (filesize($filename) > $this->maxSize)
                throw new FileSizeExceedMaxSize($this->GetFieldName(), filesize($filename), $this->maxSize);
        }
        if ($this->imageSizeCheckEnabled)
        {
            if (!ImageUtils::CheckImageSize($filename, $this->maxWidth, $this->maxHeight))
            {
                list($actualWidth, $actualHeight) = ImageUtils::GetImageSize($filename);
                throw new ImageSizeExceedMaxSize($this->GetFieldName(), $actualWidth, $actualHeight, $this->maxWidth, $this->maxHeight);
            }
        }
    }

    public function IsValueNull()
    {
        return false;
    }

    public function SetControlValuesFromPost()
    {
        $this->GetEditControl()->SetLink($this->GetFullImageLink());
    }

    public function PrepareEditorControl()
    {
        if (GetOperation() == OPERATION_EDIT || GetOperation() == OPERATION_AJAX_REQUERT_INLINE_EDIT ||
            GetOperation() == OPERATION_AJAX_REQUERT_INLINE_EDIT_COMMIT)
            $this->GetEditControl()->SetLink($this->GetFullImageLink());
    }

    public function SetControlValuesFromDataset()
    {
        $this->PrepareEditorControl();
    }

    public function SetImageFilter(ImageFilter $imageFilter)
    {
        $this->imageFilter = $imageFilter;
    }
}

class UploadFileToFolderColumn extends CustomEditColumn
{
    private $targetFolderTemplate;
    public $OnCustomFileName;

    private $useThumbnailGeneration;
    private $fieldNameToSaveThumbnailPath;

    /** @var Delegate */
    private $generateFileNameDelegate;

    /** @var ImageFilter */
    private $thumbnailImageFilter;
    private $directoryToSaveThumbnails;
    private $replaceUploadedFileIfExist;

    public function __construct($caption, $fieldName, $editControl, $dataset, $allowSetToNull = false, $allowSetToDefault = false, $targetFolderTemplate = '', $fileExtension = '')
    {
        parent::__construct($caption, $fieldName, $editControl, $dataset, $allowSetToNull, $allowSetToDefault);
        $this->targetFolderTemplate = $targetFolderTemplate;
        $this->OnCustomFileName = new Event();
        $this->useThumbnailGeneration = false;
        $this->replaceUploadedFileIfExist = true;
    }

    public function GetFullImageLink()
    {
        if (GetOperation() == OPERATION_EDIT || GetOperation() == OPERATION_AJAX_REQUERT_INLINE_EDIT)
        {
            $value = $this->GetDataset()->GetFieldValueByName($this->GetFieldName());
            return $value;
        }
    }

    public function IsValueNull()
    { return false; }

    private function GetNewFileName($original_file_name, $original_file_extension, $file_size)
    {
        $result = '';
        $handled = false;
        $this->OnCustomFileName->Fire(array(&$result, &$handled, $original_file_name, $original_file_extension, $file_size));

        $targetFolder = FormatDatasetFieldsTemplate($this->GetDataset(), $this->targetFolderTemplate);
        FileUtils::ForceDirectories($targetFolder);

        if (!$handled)
        {
            $filename = FileUtils::AppendFileExtension(rand(), $original_file_extension);
            $result = Path::Combine($targetFolder, $filename);
            
            while (file_exists($result))
            {
                $filename = FileUtils::AppendFileExtension(rand(), $original_file_extension);
                $result = Path::Combine($targetFolder, $filename);
            }
        }

        return $result;
    }

    private function GetThumbnailFileName($original_file_name, $original_file_extension, $file_size)
    {
        $result = '';
        $handled = false;
        $this->generateFileNameDelegate->CallFromArray(
            array(&$result, &$handled, $original_file_name, $original_file_extension, $file_size));

        $targetFolder = FormatDatasetFieldsTemplate($this->GetDataset(), $this->directoryToSaveThumbnails);
        FileUtils::ForceDirectories($this->directoryToSaveThumbnails);
        if (!$handled)
        {
            $filename = FileUtils::AppendFileExtension(rand(), $original_file_extension);
            $result = Path::Combine($targetFolder, $filename);

            while (file_exists($result))
            {
                $filename = FileUtils::AppendFileExtension(rand(), $original_file_extension);
                $result = Path::Combine($targetFolder, $filename);
            }
        }

        return $result;
    }

    public function AfterSetAllDatasetValues()
    {
        $valueChanged = true;

        $value = $this->GetEditControl()->ExtractsValueFromPost($valueChanged);

        if ($valueChanged && $value === null) {
            $this->clearImageAndThumbnail();
            return;
        }

        $original_file_extension = $this->GetEditControl()->ExtractFileTypeFromPost($valueChanged);
        $original_file_name = $this->GetEditControl()->ExtractFileNameFromPost($valueChanged);
        $file_size = $this->GetEditControl()->ExtractFileSizeFromPost($valueChanged);

        $target = $this->GetNewFileName($original_file_name, $original_file_extension, $file_size);

        if ($valueChanged && isset($target) && !empty($target))
        {
            FileUtils::MoveUploadedFile($value, $target, $this->replaceUploadedFileIfExist);

            if ($this->GetSetToNullFromPost())
            {
                $this->clearImageAndThumbnail();
            }
            elseif ($this->GetSetToDefaultFromPost())
            {
                $this->GetDataset()->SetFieldValueByName($this->GetFieldName(), null, true);
                if ($this->useThumbnailGeneration)
                   $this->GetDataset()->SetFieldValueByName($this->fieldNameToSaveThumbnailPath, null);
            }
            else
            {
                $this->GetDataset()->SetFieldValueByName($this->GetFieldName(), $target);

                if ($this->useThumbnailGeneration)
                {
                    $image = file_get_contents($target);
                    $thumbnailFileName = $this->GetThumbnailFileName($original_file_name,
                        $original_file_extension, $file_size);
                    $this->thumbnailImageFilter->ApplyFilter($image, $thumbnailFileName);
                    $this->GetDataset()->SetFieldValueByName($this->fieldNameToSaveThumbnailPath, $thumbnailFileName);
                }
            }
        }
    }

    private function clearImageAndThumbnail() {
        $this->GetDataset()->SetFieldValueByName($this->GetFieldName(), null);
        if ($this->useThumbnailGeneration)
            $this->GetDataset()->SetFieldValueByName($this->fieldNameToSaveThumbnailPath, null);
    }

    public function SetDatasetValuesFromPost()
    { }

    public function SetControlValuesFromPost()
    {
        $this->GetEditControl()->SetLink($this->GetFullImageLink());
    }

    public function PrepareEditorControl()
    {
        if (GetOperation() == OPERATION_EDIT || GetOperation() == OPERATION_AJAX_REQUERT_INLINE_EDIT)
            $this->GetEditControl()->SetLink($this->GetFullImageLink());
    }
    
    public function SetControlValuesFromDataset()
    {
        $this->PrepareEditorControl();
    }

    public function SetGenerationImageThumbnails($fieldNameToSaveThumbnailPath,
        $directoryToSave, IDelegate $generateFileNameDelegate, ImageFilter $thumbnailImageFilter)
    {
        $this->useThumbnailGeneration = true;
        $this->directoryToSaveThumbnails = $directoryToSave;
        $this->fieldNameToSaveThumbnailPath = $fieldNameToSaveThumbnailPath;
        $this->generateFileNameDelegate = $generateFileNameDelegate;
        $this->thumbnailImageFilter = $thumbnailImageFilter;

    }

    public function GetReplaceUploadedFileIfExist()
    {
        return $this->replaceUploadedFileIfExist;
    }

    public function SetReplaceUploadedFileIfExist($replaceUploadedFileIfExist)
    {
        $this->replaceUploadedFileIfExist = $replaceUploadedFileIfExist;
    }
}
