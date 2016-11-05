<?php

interface FilterComponentInterface
{
    /**
     * @return FieldInfo
     */
    public function getFieldInfo();

    /**
     * @return FilterComponentInterface[]
     */
    public function getChildren();

    /**
     * @param FilterComponentInterface[] $children
     *
     * @return FilterComponentInterface
     */
    public function setChildren(array $children);

    /**
     * @param FilterComponentInterface $child
     * @param mixed                    $key
     *
     * @return FilterComponentInterface
     */
    public function insertChild(FilterComponentInterface $child, $key = null);

    /**
     * @return bool
     */
    public function isEmpty();

    /**
     * @return bool
     */
    public function isCommandFilterEmpty();

    /**
     * @return string
     */
    public function getOperator();

    /**
     * @param string $operator
     */
    public function setOperator($operator);

    /**
     * @return mixed one of *FieldFilter classes instance
     */
    public function getCommandFilter();

    /**
     * @param bool $isEnabled
     *
     * @return FilterComponentInterface
     */
    public function setEnabled($isEnabled);

    /**
     * @return bool
     */
    public function isEnabled();

    /**
     * @param Captions $captions
     * @param bool     $ignoreDisabled
     * @param string   $disabledTemplate
     *
     * @return string
     */
    public function toString(
        Captions $captions,
        $ignoreDisabled = false,
        $disabledTemplate = '%s');

    /**
     * @return array
     */
    public function serialize();

    /**
     * @param FixedKeysArray $columns
     * @param array          $serializedComponent
     *
     * @return FilterComponentInterface
     */
    static public function deserialize(
        FixedKeysArray $columns,
        array $serializedComponent);
}
