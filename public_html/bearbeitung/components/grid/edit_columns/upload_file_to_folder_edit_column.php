<?php

include_once dirname(__FILE__) . '/custom_edit_column.php';
include_once dirname(__FILE__) . '/../../utils/file_utils.php';
include_once dirname(__FILE__) . '/../../exceptions/file_size_exceed_max_size.php';
include_once dirname(__FILE__) . '/../../exceptions/image_size_exceed_max_size.php';

class UploadFileToFolderColumn extends CustomEditColumn
{
    private $targetFolderTemplate;
    private $targetFilenameTemplate;
    private $keepFileNameOnly = false;
    private $replaceUploadedFileIfExist;

    private $useThumbnailGeneration;
    private $fieldNameToSaveThumbnailPath;
    private $directoryToSaveThumbnails;
    private $storeThumbnailNameOnly = false;
    /** @var ImageFilter */
    private $thumbnailImageFilter;
    /** @var int */
    private $maxUploadFileSize = 0;

    /** @var Delegate */
    private $generateFileNameDelegate;

    /**
     * @var Event
     */
    private $OnFileUpload;

    /**
     * @param string     $caption
     * @param string     $fieldName
     * @param CustomEditor     $editControl
     * @param Dataset    $dataset
     * @param boolean    $allowSetToNull
     * @param boolean    $allowSetToDefault
     * @param string     $targetFolderTemplate
     * @param string     $targetFilenameTemplate
     * @param Event|null $onFileUpload
     * @param boolean    $keepFileNameOnly
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
        $onFileUpload = null,
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
        $this->OnFileUpload = $onFileUpload;
        $this->keepFileNameOnly = $keepFileNameOnly;
    }

    public function GetFullImageLink()
    {
        if (GetOperation() !== OPERATION_EDIT) {
            return null;
        }

        $result = $this->GetDataset()->GetFieldValueByName($this->GetFieldName());

        if (!is_null($result) && $this->keepFileNameOnly) {
            $targetFolder = FormatDatasetFieldsTemplate($this->GetDataset(), $this->targetFolderTemplate);
            $result = Path::Combine($targetFolder, $result);
        }

        return $result;
    }

    public function IsValueNull()
    {
        return false;
    }

    private function getTargetFileName($originalFileName, $originalFileExtension, $fileSize, $targetFolder)
    {
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

        return $result;
    }

    private function generateFilename(
        $originalFileName,
        $originalFileExtension,
        $fileSize)
    {
        return ApplyVariablesMapToTemplate($this->targetFilenameTemplate, array(
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

    /** @return ImageUploader */
    public function GetEditControl() {
        return parent::GetEditControl();
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

        $this->checkFileSizeLimitation($value);

        if ($valueChanged && $value === null) {
            $this->clearImageAndThumbnail();
            return;
        }

        $originalFileExtension = $this->GetEditControl()->extractFileTypeFromArray(
            $postWrapper,
            $filesWrapper
        );
        $originalFileName = $this->GetEditControl()->extractFileNameFromArray(
            $postWrapper,
            $filesWrapper
        );
        $fileSize = $this->GetEditControl()->extractFileSizeFromArray(
            $postWrapper,
            $filesWrapper
        );

        $targetFolder = FormatDatasetFieldsTemplate($this->GetDataset(), $this->targetFolderTemplate);

        $targetFileName = $this->getTargetFileName($originalFileName, $originalFileExtension, $fileSize, $targetFolder);

        $acceptFile = true;
        if (!is_null($this->OnFileUpload)) {
            $this->OnFileUpload->Fire(array(
                $this->getFieldName(),
                &$targetFileName,
                &$acceptFile,
                $originalFileName,
                $originalFileExtension,
                $fileSize,
                $value
            ));
        }

        if ($acceptFile && $valueChanged && !empty($targetFileName)) {
            FileUtils::ForceDirectories($targetFolder);
            FileUtils::MoveUploadedFile($value, $targetFileName, $this->replaceUploadedFileIfExist);

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
                    $this->keepFileNameOnly ? basename($targetFileName) : $targetFileName
                );

                if ($this->useThumbnailGeneration) {
                    $image = file_get_contents($targetFileName);
                    $thumbnailFileName = $this->GetThumbnailFileName(
                        $originalFileName,
                        $originalFileExtension,
                        $fileSize
                    );
                    $this->thumbnailImageFilter->ApplyFilter($image, $thumbnailFileName);
                    $this->GetDataset()->SetFieldValueByName(
                        $this->fieldNameToSaveThumbnailPath,
                        $this->storeThumbnailNameOnly ? basename($thumbnailFileName) : $thumbnailFileName
                    );
                }
            }
        }
    }

    /** @return int */
    public function getMaxUploadFileSize() {
        return $this->maxUploadFileSize;
    }

    /** @var int $value */
    public function setMaxUploadFileSize($value) {
        $this->maxUploadFileSize = $value;
    }

    /**
     * @param string $fileName
     * @throws FileSizeExceedMaxSize
     */
    private function checkFileSizeLimitation($fileName) {
        if ($this->maxUploadFileSize > 0) {
            if (filesize($fileName) > $this->maxUploadFileSize) {
                throw new FileSizeExceedMaxSize($this->GetFieldName(), filesize($fileName), $this->maxUploadFileSize);
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
        ImageFilter $thumbnailImageFilter,
        $storeNameOnly = false)
    {
        $this->useThumbnailGeneration = true;
        $this->directoryToSaveThumbnails = $directoryToSave;
        $this->fieldNameToSaveThumbnailPath = $fieldNameToSaveThumbnailPath;
        $this->generateFileNameDelegate = $generateFileNameDelegate;
        $this->thumbnailImageFilter = $thumbnailImageFilter;
        $this->storeThumbnailNameOnly = $storeNameOnly;
    }

    public function GetReplaceUploadedFileIfExist()
    {
        return $this->replaceUploadedFileIfExist;
    }

    public function SetReplaceUploadedFileIfExist($replaceUploadedFileIfExist)
    {
        $this->replaceUploadedFileIfExist = $replaceUploadedFileIfExist;
    }

    public function SetTargetFolderTemplate($value)
    {
        $this->targetFolderTemplate = $value;
    }

}
