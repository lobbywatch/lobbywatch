<?php

include_once dirname(__FILE__) . '/' . 'string_utils.php';

class ArrayWrapper {

    /** @var array */
    private $wrappedArray;

    /** @var string */
    private $prefix;

    /**
     * @param array $arrayToWrap
     * @param string|null $prefix
     */
    public function __construct($arrayToWrap, $prefix = null) {
        $this->wrappedArray = $arrayToWrap;
        $this->prefix = empty($prefix) ? '' : $prefix . '_';
    }

    /**
     * @param string $paramName
     * @param mixed  $defaultValue
     * @return mixed
     */
    public function getValue($paramName, $defaultValue = null) {
        if (!$this->isValueSet($paramName)) {
            return $defaultValue;
        }

        return $this->wrappedArray[$this->applyPrefix($paramName)];
    }

    /**
     * @param string $paramName
     * @return bool
     */
    public function isValueSet($paramName) {
        return isset($this->wrappedArray[$this->applyPrefix($paramName)]);
    }

    /**
     * @param string $paramName
     * @param mixed $newValue
     */
    public function setValue($paramName, $newValue) {
        $this->wrappedArray[$this->applyPrefix($paramName)] = $newValue;
    }

    /**
     * @param $paramName
     */
    public function unsetValue($paramName) {
        unset($this->wrappedArray[$this->applyPrefix($paramName)]);
    }

    /**
     * @param array &$arrayToWrap
     */
    public function setArrayByReference(&$arrayToWrap)
    {
        $this->wrappedArray = &$arrayToWrap;
    }

    private function applyPrefix($key)
    {
        return $this->prefix . $key;
    }

    /**
     * @param $array
     * @return bool
     */
    public function isWrappedOn($array) {
        return $this->wrappedArray === $array;
    }

    #region Superglobal wrappers
    public static function createSessionWrapper($prefix = null) {
        return ArrayWrapper::createFromReference($_SESSION, $prefix);
    }

    public static function createSessionWrapperForDirectory() {
        $prefix = str_replace('/', '_', trim(dirname($_SERVER['REQUEST_URI']), '/'));

        return ArrayWrapper::createSessionWrapper($prefix);
    }

    public static function createCookiesWrapper($prefix = null) {
        return new ArrayWrapper($_COOKIE, $prefix);
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

    public static function createFilesWrapper($prefix = null) {
        return new ArrayWrapper($_FILES, $prefix);
    }

    public static function createFromReference(&$arrayToWrap, $prefix = null)
    {
        $wrapper = new ArrayWrapper(array(), $prefix);
        $wrapper->setArrayByReference($arrayToWrap);

        return $wrapper;
    }
    #endregion
}
