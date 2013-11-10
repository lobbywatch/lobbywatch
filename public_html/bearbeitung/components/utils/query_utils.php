<?php

class InvalidOperationException extends Exception
{ }

class Q
{
    /**
     * @static
     * @param Traversable $array
     * @return mixed
     */
    public static function First($array)
    {
        foreach ($array as $value)
            return $value;
        throw new InvalidOperationException('The source sequence is empty');
    }

    /**
     * @static
     * @param Traversable $array
     * @param $function
     * @return bool
     */
    public static function Any($array, $function)
    {
        foreach($array as $item)
            if ($function($item))
                return true;
        return false;
    }

    /**
     * @static
     * @param Traversable $array
     * @param $function
     * @return ArrayIterator
     */
    public static function Select($array, ISMCallable $function)
    {
        $result = array();
        foreach($array as $item)
            $result[] = $function->Call($item);
        return new ArrayIterator($result);
    }

    /**
     * @static
     * @param Iterator $iterator
     * @return array
     */
    public static function ToArray(Iterator $iterator)
    {
        $result = array();
        foreach($iterator as $value)
            $result[] = $value;
        return $result;
    }

    public static function L($lambda)
    {
        list($params, $code) = explode('=>', $lambda, 2);
        $params = rtrim($params, ') ');
        $params = ltrim($params, '( ');
        if (func_num_args() > 1)
        {
            $outParams = array();
            for($i = 1; $i < func_num_args(); $i++)
            {
                $outParams[] = func_get_arg($i);
                if ($params != '')
                    $params .= ',';
                $params .= '$_' . $i;
            }
            return new Lambda($outParams,  create_function($params, 'return ' . $code . ';'));
        }
        return create_function($params, 'return ' . $code . ';');
    }
}

interface ISMCallable
{
    function Call();
}

class Lambda implements ISMCallable
{
    private $outParams;
    private $function;

    public function __construct($outParams, $function)
    {
        $this->outParams = $outParams;
        $this->function = $function;
    }

    public function Call()
    {
        $closureArgs = func_get_args();
        return call_user_func_array($this->function, array_merge($closureArgs, $this->outParams));
    }
}



?>