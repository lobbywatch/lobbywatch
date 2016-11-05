<?php

include_once dirname(__FILE__) . '/custom_edit_column.php';
include_once dirname(__FILE__) . '/../../utils/file_utils.php';
include_once dirname(__FILE__) . '/../../exceptions/file_size_exceed_max_size.php';
include_once dirname(__FILE__) . '/../../exceptions/image_size_exceed_max_size.php';

class UploadFileToFolderColumn extends CustomEditColumn
{
    private $targetFolderTemplate;
    private $targetFilenameTemplate;
    private $useThumbnailGeneration;
    private $fieldNameToSaveThumbnailPath;

    /** @var Delegate */
    private $generateFileNameDelegate;

    /** @var ImageFilter */
    private $thumbnailImageFilter;
    private $directoryToSaveThumbnails;
    private $replaceUploadedFileIfExist;
    private $keepFileNameOnly = false;

    /**
     * @var Event
     */
    private $onGetCustomFileName;

    /**
     * @param string     $caption
     * @param string     $fieldName
     * @param CustomEditor     $editControl
     * @param Dataset     $dataset
     * @param boolean    $allowSetToNull
     * @param boolean    $allowSetToDefault
     * @param string     $targetFolderTemplate
     * @param string     $targetFilenameTemplate
     * @param Event|null $onGetCustomFileName
     */
    public function __construct(
        $caption,
        $fieldName,
        $editControl,
        $dataset,
        $allowSetToNull = false,
        $allowSetToDefault = false,
        $targetFolderTemplate = '',
        $targetFilenameTemplate = null,
        Event $onGetCustomFileName = null,
        $keepFileNameOnly = false)
    {
        parent::__construct(
            $caption,
            $fieldName,
            $editControl,
            $dataset,
            $allowSetToNull,
            $allowSetToDefault
        );

        $this->targetFolderTemplate = $targetFolderTemplate;
        $this->targetFilenameTemplate = $targetFilenameTemplate;
        $this->useThumbnailGeneration = false;
        $this->replaceUploadedFileIfExist = true;
        $this->onGetCustomFileName = $onGetCustomFileName;
        $this->keepFileNameOnly = $keepFileNameOnly;
    }

    public function GetFullImageLink()
    {
        if (GetOperation() !== OPERATION_EDIT) {
            return null;
        }

        $result = $this->GetDataset()->GetFieldValueByName($this->GetFieldName());

        if ($this->keepFileNameOnly) {
            $targetFolder = FormatDatasetFieldsTemplate($this->GetDataset(), $this->targetFolderTemplate);
            $result = Path::Combine($targetFolder, $result);
        }

        return $result;
    }

    public function IsValueNull()
    {
        return false;
    }

    private function GetNewFileName($originalFileName, $originalFileExtension, $fileSize)
    {
        $targetFolder = FormatDatasetFieldsTemplate($this->GetDataset(), $this->targetFolderTemplate);
        FileUtils::ForceDirectories($targetFolder);

        $result = Path::Combine(
            $targetFolder,
            $this->generateFilename(
                $originalFileName,
                $originalFileExtension,
                $fileSize
            )
        );

        if (strpos($this->targetFilenameTemplate, '%random%') !== false) {
            while (file_exists($result)) {
                $result = Path::Combine(
                    $targetFolder,
                    $this->generateFilename(
                        $originalFileName,
                        $originalFileExtension,
                        $fileSize
                    )
                );
            }
        }

        if (is_null($this->onGetCustomFileName)) {
            return $result;
        }

        $handled = false;
        $customResult = $result;
        $this->onGetCustomFileName->Fire(array(
            $this->getFieldName(),
            &$customResult,
            &$handled,
            $originalFileName,
            $originalFileExtension,
            $fileSize
        ));

        return $handled ? $customResult : $result;
    }

    private function generateFilename(
        $originalFileName,
        $originalFileExtension,
        $fileSize)
    {
        return ApplyVarablesMapToTemplate($this->targetFilenameTemplate, array(
            'original_file_name' => $originalFileName,
            'original_file_extension' => $originalFileExtension,
            'file_size' => $fileSize,
            'random' => rand(),
        ));
    }

    private function GetThumbnailFileName($original_file_name, $original_file_extension, $file_size)
    {
        $result = '';

        $handled = false;
        $this->generateFileNameDelegate->CallFromArray(array(
            &$result,
            &$handled,
            $original_file_name,
            $original_file_extension,
            $file_size
        ));

        $targetFolder = FormatDatasetFieldsTemplate($this->GetDataset(), $this->directoryToSaveThumbnails);
        FileUtils::ForceDirectories($this->directoryToSaveThumbnails);

        if (!$handled) {
            do {
                $filename = FileUtils::AppendFileExtension(rand(), $original_file_extension);
                $result = Path::Combine($targetFolder, $filename);
            } while(file_exists($result));
        }

        return $result;
    }

    public function AfterSetAllDatasetValues()
    {
        $valueChanged = true;
        $postWrapper = ArrayWrapper::createPostWrapper();
        $filesWrapper = ArrayWrapper::createFilesWrapper();

        $this->GetEditControl()->checkFile($this->getCaption(), $postWrapper, $filesWrapper);

        $value = $this->GetEditControl()->extractFilePathFromArray(
            $postWrapper,
            $filesWrapper,
            $valueChanged
        );

        if ($valueChanged && $value === null) {
            $this->clearImageAndThumbnail();
            return;
        }

        $original_file_extension = $this->GetEditControl()->extractFileTypeFromArray(
            $postWrapper,
            $filesWrapper
        );
        $original_file_name = $this->GetEditControl()->extractFileNameFromArray(
            $postWrapper,
            $filesWrapper
        );
        $file_size = $this->GetEditControl()->extractFileSizeFromArray(
            $postWrapper,
            $filesWrapper
        );

        $target = $this->GetNewFileName($original_file_name, $original_file_extension, $file_size);

        if ($valueChanged && isset($target) && !empty($target)) {
            FileUtils::MoveUploadedFile($value, $target, $this->replaceUploadedFileIfExist);

            if ($this->GetSetToNullFromPost()) {
                $this->clearImageAndThumbnail();
            } elseif ($this->GetSetToDefaultFromPost()) {
                $this->GetDataset()->SetFieldValueByName($this->GetFieldName(), null, true);

                if ($this->useThumbnailGeneration) {
                   $this->GetDataset()->SetFieldValueByName($this->fieldNameToSaveThumbnailPath, null);
                }

            } else {
                $this->GetDataset()->SetFieldValueByName(
                    $this->GetFieldName(),
                    $this->keepFileNameOnly ? basename($target) : $target
                );

                if ($this->useThumbnailGeneration) {
                    $image = file_get_contents($target);
                    $thumbnailFileName = $this->GetThumbnailFileName(
                        $original_file_name,
                        $original_file_extension,
                        $file_size
                    );
                    $this->thumbnailImageFilter->ApplyFilter($image, $thumbnailFileName);
                    $this->GetDataset()->SetFieldValueByName($this->fieldNameToSaveThumbnailPath, $thumbnailFileName);
                }
            }
        }
    }

    private function clearImageAndThumbnail() {
        $this->GetDataset()->SetFieldValueByName($this->GetFieldName(), null);

        if ($this->useThumbnailGeneration) {
            $this->GetDataset()->SetFieldValueByName($this->fieldNameToSaveThumbnailPath, null);
        }
    }

    public function SetDatasetValuesFromPost()
    {
    }

    public function SetControlValuesFromPost()
    {
        $this->GetEditControl()->SetLink($this->GetFullImageLink());
    }

    public function PrepareEditorControl()
    {
        if (GetOperation() == OPERATION_EDIT) {
            $this->GetEditControl()->SetLink($this->GetFullImageLink());
        }
    }

    public function SetControlValuesFromDataset()
    {
        $this->PrepareEditorControl();
    }

    public function SetGenerationImageThumbnails(
        $fieldNameToSaveThumbnailPath,
        $directoryToSave,
        IDelegate $generateFileNameDelegate,
        ImageFilter $thumbnailImageFilter)
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
