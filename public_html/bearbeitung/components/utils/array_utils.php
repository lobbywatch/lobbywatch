<?php

class ArrayUtils
{
    public static function Merge($array1, $array2) {
        return array_merge($array1, $array2);
    }

    public static function Find($array, $predicate)
    {
        foreach($array as $elem)
        {
            if ($predicate($elem))
                return $elem;
        }
        return null;
    }

    public static function GetArrayValueDef($array, $key, $defaultValue = null)
    {
        return isset($array[$key]) ? $array[$key] : $defaultValue;
    }
}

