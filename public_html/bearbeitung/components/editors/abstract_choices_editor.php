<?php

include_once dirname(__FILE__) . '/custom.php';

abstract class AbstractChoicesEditor extends CustomEditor
{
    /** @var array */
    private $choices = array();

    /** @var string */
    private $value;

    /**
     * @param array $choices
     *
     * @return $this
     */
    public function setChoices(array $choices)
    {
        $this->choices = $choices;
        return $this;
    }

    /**
     * @return array
     */
    public function getChoices()
    {
        return $this->choices;
    }

    /**
     * @param string $value
     * @return bool
     */
    public function hasChoice($value)
    {
        return array_key_exists($value, $this->choices);
    }

    /**
     * @param string $value
     * @param string $displayValue
     *
     * @return $this
     */
    public function addChoice($value, $displayValue = null)
    {
        $this->choices[$value] = is_null($displayValue) ? $value : $displayValue;

        return $this;
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function removeChoice($value)
    {
        if ($this->hasChoice($value)) {
            unset($this->choices[$value]);
        }

        return $this;
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string|null
     */
    public function getDisplayValue()
    {
        if (!array_key_exists((string)$this->value, $this->choices)) {
            return null;
        }

        return $this->choices[$this->value];
    }

}
