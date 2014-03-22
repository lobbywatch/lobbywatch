<?php

class Event {

    /** @var array */
    private $listeners;

    public function __construct() {
        $this->listeners = array();
    }

    /**
     * @param string $functionName
     * @param null|mixed $object
     * @return void
     *
     * NB. All methods added as listeners must be declared public.
     */
    public function AddListener($functionName, $object = null) {
        if ($object == null)
            $this->listeners[] = $functionName;
        else
            $this->listeners[] = array($object, $functionName);
    }

    public function RemoveListener($functionName, $object = null) {
        if ($object == null)
            $elementToDelete = $functionName;
        else
            $elementToDelete = array($object, $functionName);

        if (($key = array_search($elementToDelete, $this->listeners)) !== false) {
            unset($this->listeners[$key]);
            $this->listeners = array_values($this->listeners);
        }
    }

    /**
     * @return void
     */
    public function Fire() {
        $arguments = func_get_args();
        foreach ($this->listeners as $listener)
            call_user_func_array($listener, $arguments[0]);
    }

    /**
     * @return int
     */
    public function GetListenerCount() {
        return count($this->listeners);
    }
}
