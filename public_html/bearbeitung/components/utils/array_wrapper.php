<?php

include_once dirname(__FILE__) . '/' . 'string_utils.php';

class ArrayWrapper {

    /** @var array */
    private $wrappedArray;
    /** @var string|null */
    private $prefix;

    /**
     * @param string $name
     * @return string
     */
    private function RemoveContextFromName($name)
    {
        return StringUtils::IsNullOrEmpty($this->prefix) ?
            $name :
            StringUtils::Replace($this->prefix . '_', '', $name);
    }

    private function IsNameInContext($name)
    {
        return StringUtils::IsNullOrEmpty($this->prefix) ?
            true :
            StringUtils::StartsWith($name, $this->prefix . '_');
    }

    private function getActualParamName($paramName)
    {
        return StringUtils::IsNullOrEmpty($this->prefix) ? $paramName : ($this->prefix . '_' . $paramName);
    }

    /**
     * @param array $arrayToWrap
     * @param string|null $prefix
     */
    public function __construct($arrayToWrap, $prefix = null) {
        $this->wrappedArray = $arrayToWrap;
        $this->prefix = $prefix;
    }

    /**
     * @param string $paramName
     * @return string
     */
    public function getValue($paramName) {
        return $this->wrappedArray[$this->getActualParamName($paramName)];
    }

    /**
     * @param string $paramName
     * @return bool
     */
    public function isValueSet($paramName) {
        return isset($this->wrappedArray[$this->getActualParamName($paramName)]);
    }

    /**
     * @param string $paramName
     * @param mixed $newValue
     */
    public function setValue($paramName, $newValue) {
        $this->wrappedArray[$paramName] = $newValue;
    }

    /**
     * @param $paramName
     * @returns void
     */
    public function unsetValue($paramName) {
        unset($this->wrappedArray[$paramName]);
    }

    #region Superglobal wrappers
    public static function createSessionWrapper($prefix = null) {
        return new ArrayWrapper($_SESSION, $prefix);
    }

    public static function createPostWrapper($prefix = null) {
        return new ArrayWrapper($_POST, $prefix);
    }

    public static function createGetWrapper($prefix = null) {
        return new ArrayWrapper($_GET, $prefix);
    }

    public static function createServerWrapper($prefix = null) {
        return new ArrayWrapper($_SERVER, $prefix);
    }
    #endregion
}
