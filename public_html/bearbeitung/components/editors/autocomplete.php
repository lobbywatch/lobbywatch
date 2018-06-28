<?php

include_once dirname(__FILE__) . '/' . 'text.php';
include_once dirname(__FILE__) . '/' . '../utils/array_wrapper.php';
include_once dirname(__FILE__) . '/' . '../utils/link_builder.php';

class Autocomplete extends TextEdit {
    /** @var string */
    private $handlerName;
    /** @var LinkBuilder */
    private $linkBuilder;
    /** @var int */
    private $minimumInputLength = 1;

    /**
     * @param string $name
     * @param LinkBuilder $linkBuilder
     * @param string $handlerName
     */
    public function __construct($name, LinkBuilder $linkBuilder, $handlerName) {
        parent::__construct($name);
        $this->linkBuilder = $linkBuilder;
        $this->handlerName = $handlerName;
    }

    /** @inheritdoc */
    public function getEditorName() {
        return 'autocomplete';
    }

    /** @return string */
    public function getHandlerName() {
        return $this->handlerName;
    }

    /** @param string $value */
    public function setHandlerName($value) {
        $this->handlerName = $value;
    }

    /** @return int */
    public function getMinimumInputLength() {
        return $this->minimumInputLength;
    }

    /** @param int $value */
    public function setMinimumInputLength($value) {
        $this->minimumInputLength = (int) $value;
    }

    /** @return string */
    public function getDataUrl() {
        $linkBuilder = $this->linkBuilder->CloneLinkBuilder();
        $linkBuilder->AddParameter(OPERATION_HTTPHANDLER_NAME_PARAMNAME, $this->getHandlerName());
        return $linkBuilder->GetLink();
    }

}
