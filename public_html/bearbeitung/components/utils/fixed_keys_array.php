<?php

class FixedKeysArray implements ArrayAccess, IteratorAggregate
{
    /**
     * @var array
     */
    private $coll;

    public function __construct(array $coll = array())
    {
        $this->coll = $coll;
    }

    #[\ReturnTypeWillChange]
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->coll);
    }

    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        $this->checkOffset($offset);

        return $this->coll[$offset];
    }

    #[\ReturnTypeWillChange]
    public function offsetSet($offset, $value)
    {
        $this->checkOffset($offset);
        $this->coll[$offset] = $value;
    }

    #[\ReturnTypeWillChange]
    public function offsetUnset($offset)
    {
        throw new InvalidArgumentException('Unset operation is not allowed.');
    }

    #[\ReturnTypeWillChange]
    public function getIterator()
    {
        return new ArrayIterator($this->coll);
    }

    public function toArray()
    {
        return $this->coll;
    }

    private function checkOffset($offset)
    {
        if (!$this->offsetExists($offset)) {
            throw new InvalidArgumentException(sprintf(
                'Invalid key "%s". Available keys are: "%s"',
                $offset,
                implode('", "', array_keys($this->coll))
            ));
        }
    }
}
