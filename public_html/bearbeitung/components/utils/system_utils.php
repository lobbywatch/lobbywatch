<?php

include_once dirname(__FILE__) . '/string_utils.php';

class SMReflection
{
    /**
     * @static
     * @param mixed $object
     * @return string
     */
    public static function ClassName($object)
    {
        if ($object != null && is_object($object))
            return get_class($object);
        else
            return '';
    }

    public static function IsInstanceOf($object, $className) {
        return $object instanceof $className;
    }
}

class SystemUtils
{
    public static function GetPHPMinorVersion()
    {
        $versions = explode('.', phpversion());
        return $versions[1];
    }

    public static function SetTimeZoneIfNeed($timezoneIdentifier)
    {
        if (version_compare(PHP_VERSION, "5.3.0", ">=") && StringUtils::IsNullOrEmpty(ini_get('date.timezone'))) {
            date_default_timezone_set($timezoneIdentifier);
        }
    }

    public static function DisableMagicQuotesRuntime()
    {
        if (function_exists('set_magic_quotes_runtime'))
        {
            try
            {
                set_magic_quotes_runtime(false);
            }
            catch (Exception $e)
            { }
        }
    }

    public static function ToJSON($value)
    {
	return json_encode($value);
    }

    public static function FromJSON($json, $assoc = false)
    {
        return json_decode($json, $assoc);
    }

}

class ImageUtils
{
    public static function EnableAntiAliasing($imageResource)
    {
        if (function_exists('imageantialias'))
            imageantialias($imageResource, true);
    }

    public static function GetImageSize($fileName)
    {
        list($width, $height, $type, $attr) = getimagesize($fileName);
        return array($width, $height);
    }

    public static function CheckImageSize($fileName, $maxWidth, $maxHeight)
    {
        list($width, $height) = ImageUtils::GetImageSize($fileName);
        if (($width > $maxWidth) || ($height > $maxHeight))
            return false;
        else
            return true;
    }
}

class DebugUtils
{
    public static function PrintArray($array)
    {
        echo '<pre>';
        print_r($array);
        echo '</pre>';
    }

    public static function PrintCallStack()
    {
        $trace = debug_backtrace();
        echo '<pre>';
        foreach($trace as $traceItem)
        {
            echo sprintf(
                "%s.%s\t\t(%s:%d)\n",
                $traceItem['class'],
                $traceItem['function'],
                $traceItem['file'],
                $traceItem['line']
                );
        }
        echo '</pre>';
    }

    public static function GetDebugLevel()
    {
        /** @noinspection PhpUndefinedConstantInspection */
        return defined('DEBUG_LEVEL') ? DEBUG_LEVEL : 0;
    }
}

class Random
{
    public static function GetIntRandom($min = 0, $max = -1)
    {
        if ($max == -1)
            return mt_rand($min, mt_getrandmax());
        else
            return mt_rand($min, $max);
    }
}

class Argument
{
    public static $Arg1;
    public static $Arg2;
    public static $Arg3;
    public static $Arg4;
    public static $Arg5;
    public static $Arg6;
    public static $Arg7;
    public static $Arg8;
    public static $Arg9;

    public static function Init()
    {
        self::$Arg1 = 1;
        self::$Arg2 = 2;
        self::$Arg3 = 3;
        self::$Arg4 = 4;
        self::$Arg5 = 5;
        self::$Arg6 = 6;
        self::$Arg7 = 7;
        self::$Arg8 = 8;
        self::$Arg9 = 9;
    }

    private $number;

    private function __construct($number)
    {
        $this->number = $number;
    }

    public static function GetArgumentNumber($arg)
    {
        return $arg - 1;
    }
}
Argument::Init();

interface IDelegate
{
    function Call();

    function CallFromArray($argumentsArray);
}

class Delegate implements IDelegate
{
    private $phpDelegate;

    private function __construct($phpDelegate)
    {
        $this->phpDelegate = $phpDelegate;
    }

    public static function CreateFromFunction($name)
    {
        return new Delegate($name);
    }

    /**
     * @static
     * @param mixed $object
     * @param string $methodName
     * @return Delegate
     */
    public static function CreateFromMethod($object, $methodName)
    {
        return new Delegate(array($object, $methodName));
    }

    public static function CreateFromStaticMethod($className, $methodName)
    {
        return new Delegate(array($className, $methodName));
    }

    public function Call()
    {
        $arguments = func_get_args();
        return call_user_func_array($this->phpDelegate, $arguments);
    }

    public function CallFromArray($argumentsArray)
    {
        return call_user_func_array($this->phpDelegate, $argumentsArray);
    }
}
