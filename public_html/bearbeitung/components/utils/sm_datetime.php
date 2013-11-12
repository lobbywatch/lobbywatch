<?php


include_once dirname(__FILE__) . '/' . 'system_utils.php';

class SMDateTime {
    private $timestamp;
    private $dateTime;

    public function GetDateTime() {
        return $this->dateTime;
    }

    public function GetTimestamp() {
        return $this->timestamp;
    }

    private static function UseNativeDateTimeClass() {
        //return SystemUtils::GetPHPMinorVersion() >= 2;
        return false;
    }

    public function __construct($timestamp)
    {
        if (SMReflection::IsInstanceOf($timestamp, 'SMDateTime'))
        {
            if (self::UseNativeDateTimeClass())
                $this->dateTime = $timestamp->GetDatetime();
            else
                $this->timestamp = $timestamp->GetTimestamp();
        }
        else {
            if (self::UseNativeDateTimeClass())
                $this->dateTime = new DateTime($timestamp);
            else
                $this->timestamp = $timestamp;
        }
    }

    public static function Parse($stringValue, $format)
    {
        if (self::UseNativeDateTimeClass())
        {
            if (is_object($stringValue) && (get_class($stringValue) == 'DateTime'))
                return new SMDateTime($stringValue->format('d-m-Y H:i:s'));
            else
                return new SMDateTime($stringValue);
        }
        else
        {
            // HACK: move to client code
            if (is_object($stringValue) && (get_class($stringValue) == 'DateTime'))
                return new SMDateTime(strtotime($stringValue->format('d-m-Y H:i:s')));
            if (is_object($stringValue) && (get_class($stringValue) == 'SMDateTime')) {
                return $stringValue;
            }
            else {
                return new SMDateTime(strtotime($stringValue));
            }

        }
    }

    public static function Now()
    {
        if (self::UseNativeDateTimeClass())
            return new SMDateTime("now");
        else
            return new SMDateTime(time());
    }

    public function ToRfc822String()
    {
        if (self::UseNativeDateTimeClass())
            return
                $this->dateTime->format('D, d M Y H:i:s T');
        else
            return
                @date('D, d M Y H:i:s T', $this->timestamp);
    }

    public function ToString($format)
    {
        if (self::UseNativeDateTimeClass())
            return $this->dateTime->format($format);
        else
            return @date($format, $this->timestamp);
    }
}
