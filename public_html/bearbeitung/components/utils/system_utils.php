<?php

include_once dirname(__FILE__) . '/' . '../../libs/JSON.php';
include_once dirname(__FILE__) . '/' . 'xml_writer.php';

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
        if (SystemUtils::GetPHPMinorVersion() >= 3)
            if (StringUtils::IsNullOrEmpty(ini_get('date.timezone')))
                date_default_timezone_set($timezoneIdentifier);
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
        if (function_exists('json_encode'))
        {
            return json_encode($value);
        }
        else
        {
            $jsonConverter = new Services_JSON();
            return $jsonConverter->encode($value);
        }
    }

    public static function FromJSON($json)
    {
        if (function_exists('json_dencode'))
        {
            return json_dencode($json);
        }
        else
        {
            $jsonConverter = new Services_JSON();
            return $jsonConverter->decode($json);
        }
    }

    public static function ToXML($data, $startElement = 'fx_request', $xml_version = '1.0', $xml_encoding = 'UTF-8')
    {
      $xmlWriter = XMLWriterFactory::CreateXMLWriter();
      $xmlWriter->StartDocument($xml_version, $xml_encoding);
      $xmlWriter->StartElement($startElement);

      function write(IPGXMLWriter $xmlWriter, $data)
      {
          foreach($data as $key => $value)
          {
              if(is_array($value))
              {
                  $xmlWriter->StartElement($key);
                  write($xmlWriter, $value);
                  $xmlWriter->EndElement($key);
                  continue;
              }
              $xmlWriter->WriteElement($key, $value);
          }
      }
      write($xmlWriter, $data);

      $xmlWriter->EndElement($startElement);
      
      return $xmlWriter->GetResult();
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

    function Bind($rules);
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

    public static function CreateFromText($arguments, $body)
    {
        return new Delegate(create_function($arguments, $body));
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

    /**
     * @param  $rules
     * @return BindableDelegate
     */
    public function Bind($rules)
    {
        return new BindableDelegate($this, $rules);
    }
}

class BindableDelegate implements IDelegate
{
    private $delegate;
    private $rules;

    public function __construct(IDelegate $delegate, $rules)
    {
        $this->delegate = $delegate;
        $this->rules = $rules;
        if ($this->rules != null)
            uksort($this->rules, create_function('$a1,$a2', 'return $a1 > $a2;'));
    }

    private function array_insert_before(&$array, $index, $value)
    {
        for ($i = count($array); $i > $index; $i--)
            $array[$i] = $array[$i - 1];
        $array[$index] = $value;
    }

    function Call()
    {
        $arguments = func_get_args();
        if ($this->rules == null)
        {
            return $this->delegate->CallFromArray($arguments);
        }
        else
        {
            foreach($this->rules as $arg => $value)
            {
                $this->array_insert_before($arguments, Argument::GetArgumentNumber($arg), $value);
            }
            return $this->delegate->CallFromArray($arguments);
        }
    }

    function CallFromArray($argumentsArray)
    {
        if ($this->rules == null)
        {
            return $this->delegate->CallFromArray($argumentsArray);
        }
        else
        {
            foreach($this->rules as $arg => $value)
                $this->array_insert_before($argumentsArray, Argument::GetArgumentNumber($arg), $value);
            return $this->delegate->CallFromArray($argumentsArray);
        }
    }

    public function Bind($rules)
    {
        return new BindableDelegate($this, $rules);
    }
}

?>