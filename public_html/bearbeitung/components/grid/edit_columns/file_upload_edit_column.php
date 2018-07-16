<?php

include_once dirname(__FILE__) . '/custom_edit_column.php';
include_once dirname(__FILE__) . '/../../utils/file_utils.php';
include_once dirname(__FILE__) . '/../../exceptions/file_size_exceed_max_size.php';
include_once dirname(__FILE__) . '/../../exceptions/image_size_exceed_max_size.php';

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

            $postWrapper = ArrayWrapper::createPostWrapper();
            $filesWrapper = ArrayWrapper::createFilesWrapper();

            $this->GetEditControl()->checkFile($this->getCaption(), $postWrapper, $filesWrapper);

            DatasetUtils::SetDatasetFieldValue($this->GetDataset(),
                $this->GetFileTypeFieldName(),
                $this->GetEditControl()->extractFileTypeFromArray($postWrapper, $filesWrapper)
            );
            DatasetUtils::SetDatasetFieldValue($this->GetDataset(),
                $this->GetFileNameFieldName(),
                $this->GetEditControl()->extractFileNameFromArray($postWrapper, $filesWrapper)
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
        if ((GetOperation() !== OPERATION_EDIT) || ($this->GetDataset()->GetFieldValueByName($this->GetFieldName()) === null))  {
            return null;
        }

        $result = $this->GetGrid()->CreateLinkBuilder();
        $result->AddParameter('hname', $this->handlerName);
        $result->AddParameter('large', '1');
        AddPrimaryKeyParameters($result, $this->GetDataset()->GetPrimaryKeyValues());
        return $result->GetLink();
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
        if (!(isset($value))) {
            return;
        }

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
        if (GetOperation() == OPERATION_EDIT)
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
