<?php

class ArrayChoiceCollection implements IteratorAggregate
{
    /**
     * @var array
     */
    private $choices = array();

    /**
     * @param array $choices
     */
    public function __construct(array $choices = array())
    {
        $this->choices = $choices;
    }

    /**
     * @param mixed $key
     *
     * @return bool
     */
    public function has($key)
    {
        return array_key_exists($key, $this->choices);
    }

    /**
     * @param mixed $key
     *
     * @return mixed
     */
    public function get($key)
    {
        if (!$this->has($key)) {
            return null;
        }

        return $this->choices[$key];
    }

    /**
     * @param mixed $key
     * @param mixed $value
     *
     * @return $this
     */
    public function set($key, $value)
    {
        $this->choices[$key] = $value;

        return $this;
    }

    /**
     * @param mixed $key
     *
     * @return $this
     */
    public function remove($key)
    {
        if ($this->has($key)) {
            unset($this->choices[$key]);
        }

        return $this;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->choices);
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new ArrayIterator($this->choices);
    }
}
