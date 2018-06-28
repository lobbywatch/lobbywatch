<?php

include_once dirname(__FILE__) . '/' . 'string_utils.php';

class LinkBuilder {
    /** @var string */
    private $targetPage;

    /** @var string[string] */
    private $parameters;

    /**
     * @param string $targetPage
     */
    public function __construct($targetPage) {
        $this->targetPage = $targetPage;
        $this->parameters = array();
    }

    /**
     * @param string $name
     * @param string $value
     */
    public function AddParameter($name, $value) {
        $this->parameters[$name] = $value;
    }

    /**
     * @param string[string] $parameters
     */
    public function AddParameters($parameters) {
        foreach ($parameters as $name => $value)
            $this->AddParameter($name, $value);
    }

    /**
     * @param string $name
     */
    public function RemoveParameter($name) {
        unset($this->parameters[$name]);
    }

    public function GetParameters() {
        return $this->parameters;
    }

    public function GetLink() {
        $parameterList = '';
        foreach ($this->parameters as $name => $value) {
            if (is_array($value)) {
                StringUtils::AddStr($parameterList, http_build_query(array($name => $value)), '&');
            } else {
                StringUtils::AddStr($parameterList, urlencode($name) . '=' . urlencode($value), '&');
            }
        }
        return $this->targetPage . ($parameterList != '' ? '?' : '') . $parameterList;
    }

    public function CloneLinkBuilder() {
        $result = new LinkBuilder($this->targetPage);
        $result->AddParameters($this->GetParameters());
        return $result;
    }
}
