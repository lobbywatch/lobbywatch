<?php

include_once dirname(__FILE__) . '/' . '../renderers/renderer.php';
include_once dirname(__FILE__) . '/' . '../utils/file_utils.php';
include_once dirname(__FILE__) . '/' . 'custom.php';

class ImageUploader extends CustomEditor {
    private $showImage;
    private $imageLink;

    public function __construct($name) {
        parent::__construct($name);
        $this->showImage = false;
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

    public function extractValueFromArray(ArrayWrapper $arrayWrapper, &$valueChanged, ArrayWrapper $filesWrapper = null) {
        $action = $this->extractImageActionFromArray($arrayWrapper);

        if ($action != REPLACE_IMAGE_ACTION) {
            $valueChanged = REMOVE_IMAGE_ACTION === $action;
            return null;
        }

        if (is_null($filesWrapper)) {
            $filesWrapper = ArrayWrapper::createFilesWrapper();
        }

        $fileInfo = $filesWrapper->getValue($this->GetName() . "_filename");
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
        return;
    }

    public function SetValue($value) {
        return;
    }

    public function extractFilePathFromArray(ArrayWrapper $postWrapper, ArrayWrapper $filesWrapper, &$valueChanged) {
        $action = $this->extractImageActionFromArray($postWrapper);
        if ($action !== REPLACE_IMAGE_ACTION) {
            $valueChanged = REMOVE_IMAGE_ACTION === $action;

            return;
        }

        $valueChanged = true;
        $fileInfo = $filesWrapper->getValue($this->GetName() . "_filename");

        return  $fileInfo["tmp_name"];
    }

    public function extractFileTypeFromArray(ArrayWrapper $postWrapper, ArrayWrapper $filesWrapper) {
        $action = $this->extractImageActionFromArray($postWrapper);
        if ($action !== REPLACE_IMAGE_ACTION) {
            return;
        }

        $fileInfo = $filesWrapper->getValue($this->GetName() . "_filename");

        return Path::GetFileExtension($fileInfo["name"]);
    }

    public function extractFileNameFromArray(ArrayWrapper $postWrapper, ArrayWrapper $filesWrapper) {
        $action = $this->extractImageActionFromArray($postWrapper);
        if ($action !== REPLACE_IMAGE_ACTION) {
            return;
        }

        $fileInfo = $filesWrapper->getValue($this->GetName() . "_filename");

        return Path::GetFileTitle($fileInfo['name']);
    }

    public function extractFileSizeFromArray(ArrayWrapper $postWrapper, ArrayWrapper $filesWrapper) {
        $action = $this->extractImageActionFromArray($postWrapper);
        if ($action !== REPLACE_IMAGE_ACTION) {
            return;
        }

        $fileInfo = $filesWrapper->getValue($this->GetName() . "_filename");

        return $fileInfo['size'];
    }

    public function GetDataEditorClassName()
    {
        return 'ImageUploader';
    }

    public function Accept(EditorsRenderer $renderer) {
        $renderer->RenderImageUploader($this);
    }
}
