<?php

include_once dirname(__FILE__) . '/../utils/file_utils.php';
include_once dirname(__FILE__) . '/custom.php';
include_once dirname(__FILE__) . '/../utils/array_wrapper.php';
include_once dirname(__FILE__) . '/../exceptions/upload_error.php';

class ImageUploader extends CustomEditor {
    private $showImage;
    private $imageLink;
    private $acceptableFileTypes;

    public function __construct($name) {
        parent::__construct($name);
        $this->showImage = false;
        $this->acceptableFileTypes = '';
    }

    public function GetShowImage() {
        return $this->showImage;
    }

    public function SetShowImage($value) {
        $this->showImage = $value;
    }

    public function GetLink() {
        return $this->imageLink;
    }

    public function SetLink($value) {
        $this->imageLink = $value;
    }

    public function getAcceptableFileTypes() {
        return $this->acceptableFileTypes;
    }

    public function setAcceptableFileTypes($value) {
        $this->acceptableFileTypes = $value;
    }

    public function extractValueFromArray(ArrayWrapper $arrayWrapper, &$valueChanged, ArrayWrapper $filesWrapper = null) {
        $action = $this->extractImageActionFromArray($arrayWrapper);

        if ($action != REPLACE_IMAGE_ACTION) {
            $valueChanged = REMOVE_IMAGE_ACTION === $action;
            return null;
        }

        if (is_null($filesWrapper)) {
            $filesWrapper = ArrayWrapper::createFilesWrapper();
        }

        $fileInfo = $filesWrapper->getValue($this->GetName());
        $valueChanged = true;
        return $fileInfo['tmp_name'];
    }

    public function extractImageActionFromArray(ArrayWrapper $arrayWrapper) {
        if ($arrayWrapper->IsValueSet($this->GetName() . "_action")) {
            return $arrayWrapper->GetValue($this->GetName() . "_action");
        }

        return KEEP_IMAGE_ACTION;
    }

    public function GetValue() {
        return null;
    }

    public function SetValue($value) {
        return;
    }

    public function checkFile($caption, ArrayWrapper $postWrapper, ArrayWrapper $filesWrapper)
    {
        $action = $this->extractImageActionFromArray($postWrapper);
        if ($action !== REPLACE_IMAGE_ACTION) {
            return;
        }

        $fileInfo = $filesWrapper->getValue($this->GetName());

        $errors = array(
            UPLOAD_ERR_INI_SIZE => 'FileTooLarge',
            UPLOAD_ERR_FORM_SIZE => 'FileTooLarge',
            UPLOAD_ERR_PARTIAL => 'UploadError',
            UPLOAD_ERR_NO_FILE => 'UploadError',
            UPLOAD_ERR_NO_TMP_DIR => 'ServerError',
            UPLOAD_ERR_CANT_WRITE => 'ServerError',
            UPLOAD_ERR_EXTENSION => 'ServerError',
        );

        $errorCode = isset($fileInfo['error']) ? $fileInfo['error'] : 0;
        if (array_key_exists($errorCode, $errors)) {
            throw new UploadError($this->GetName(), $caption, $errors[$errorCode], $errorCode);
        }
    }

    public function extractFilePathFromArray(ArrayWrapper $postWrapper, ArrayWrapper $filesWrapper, &$valueChanged) {
        $action = $this->extractImageActionFromArray($postWrapper);
        if ($action !== REPLACE_IMAGE_ACTION) {
            $valueChanged = REMOVE_IMAGE_ACTION === $action;

            return null;
        }

        $valueChanged = true;
        $fileInfo = $filesWrapper->getValue($this->GetName());

        return $fileInfo["tmp_name"];
    }

    public function extractFileTypeFromArray(ArrayWrapper $postWrapper, ArrayWrapper $filesWrapper) {
        $action = $this->extractImageActionFromArray($postWrapper);
        if ($action !== REPLACE_IMAGE_ACTION) {
            return null;
        }

        $fileInfo = $filesWrapper->getValue($this->GetName());

        return Path::GetFileExtension($fileInfo["name"]);
    }

    public function extractFileNameFromArray(ArrayWrapper $postWrapper, ArrayWrapper $filesWrapper) {
        $action = $this->extractImageActionFromArray($postWrapper);
        if ($action !== REPLACE_IMAGE_ACTION) {
            return null;
        }

        $fileInfo = $filesWrapper->getValue($this->GetName());

        return Path::GetFileTitle($fileInfo['name']);
    }

    public function extractFileSizeFromArray(ArrayWrapper $postWrapper, ArrayWrapper $filesWrapper) {
        $action = $this->extractImageActionFromArray($postWrapper);
        if ($action !== REPLACE_IMAGE_ACTION) {
            return null;
        }

        $fileInfo = $filesWrapper->getValue($this->GetName());

        return $fileInfo['size'];
    }

    /**
     * @return string
     */
    public function getEditorName()
    {
        return 'imageuploader';
    }
}
