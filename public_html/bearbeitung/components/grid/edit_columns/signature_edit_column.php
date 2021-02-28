<?php

include_once dirname(__FILE__) . '/custom_edit_column.php';
include_once dirname(__FILE__) . '/../../../components/utils/file_utils.php';
include_once dirname(__FILE__) . '/../../../components/utils/string_utils.php';

class SignatureEditColumn extends CustomEditColumn {

    /** @var  string */
    private $folderToSaveTemplate;
    /** @var boolean */
    private $storeFileNameOnly;
    /** @var  string */
    private $fileNameTemplate;
    /** @var boolean */
    private $replaceFileIfExist;

    public function __construct(
        $caption, $fieldName, $editControl, $dataset, $allowSetToNull, $allowSetToDefault,
        $folderToSaveTemplate, $fileNameTemplate, $storeFileNameOnly, $replaceFileIfExist)
    {
        parent::__construct(
            $caption,
            $fieldName,
            $editControl,
            $dataset,
            $allowSetToNull,
            $allowSetToDefault
        );

        $this->folderToSaveTemplate = $folderToSaveTemplate;
        $this->fileNameTemplate = $fileNameTemplate;
        $this->storeFileNameOnly = $storeFileNameOnly;
        $this->replaceFileIfExist = $replaceFileIfExist;
    }

    public function AfterSetAllDatasetValues() {
        $valueChanged = true;

        $value = $this->GetEditControl()->GetValue();
        $folderToSave = FormatDatasetFieldsTemplate($this->GetDataset(), $this->folderToSaveTemplate);
        $fileName = $this->generateFileName($folderToSave);

        if ($valueChanged && $value) {
            FileUtils::ForceDirectories($folderToSave);
            $valueParts = explode(",", $value);
            $encodedImage = $valueParts[1];

            $imgData = base64_decode($encodedImage);
            $this->createSignatureFile($fileName, $imgData);
            $this->GetDataset()->SetFieldValueByName(
                $this->GetFieldName(),
                $this->storeFileNameOnly ? basename($fileName) : $fileName
            );
        }

    }

    private function generateFilename($folderToSave) {
        $generateRandomFileName = strpos($this->fileNameTemplate, '%random%') !== false;
        if ($generateRandomFileName) {
            $fileName = StringUtils::ReplaceVariableInTemplate($this->fileNameTemplate, 'random', rand());
            while (file_exists(Path::Combine($folderToSave, $fileName))) {
                $fileName = StringUtils::ReplaceVariableInTemplate($this->fileNameTemplate, 'random', rand());
            }
        } else {
            $fileName = FormatDatasetFieldsTemplate($this->GetDataset(), $this->fileNameTemplate);
        }
        return Path::Combine($folderToSave, $fileName);
    }

    private function createSignatureFile($fileName, $imgData) {
        if (!$this->replaceFileIfExist && FileUtils::FileExists($fileName)) {
            return;
        }
        file_put_contents($fileName, $imgData);
    }

    public function SetControlValuesFromDataset() {
        if (GetOperation() == OPERATION_EDIT || GetOperation() == OPERATION_COPY) {
            $result = $this->GetDataset()->GetFieldValueByName($this->GetFieldName());
            if (!is_null($result) && $this->storeFileNameOnly) {
                $targetFolder = FormatDatasetFieldsTemplate($this->GetDataset(), $this->folderToSaveTemplate);
                $result = Path::Combine($targetFolder, $result);
                if (file_exists($result)) {
                    $img = file_get_contents($result);
                    if ($img) {
                        $encodedImage = base64_encode($img);
                        $imgPrefix = 'data:image/png;base64,';
                        $fileExtension = pathinfo($result, PATHINFO_EXTENSION);
                        if ($fileExtension == 'svg') {
                            $imgPrefix = 'data:image/svg+xml;base64,';
                        } elseif ($fileExtension == 'jpg') {
                            $imgPrefix = 'data:image/jpeg;base64,';
                        }

                        $this->GetEditControl()->SetValue($imgPrefix . $encodedImage);
                    } else {
                        $this->GetEditControl()->SetValue('');
                    }
                }
            }
        }
    }

}
