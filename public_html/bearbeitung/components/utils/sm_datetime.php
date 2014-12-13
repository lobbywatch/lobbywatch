<?php


include_once dirname(__FILE__) . '/' . 'system_utils.php';
include_once dirname(__FILE__) . '/' . 'strptime.php';

function strToTimestamp($date, $format) {
    if (version_compare(PHP_VERSION, "5.3.0", ">=")) {
        $datetime = DateTime::createFromFormat('!' . $format, $date);
        if ($datetime !== false)
            return $datetime->getTimestamp();
        else
            return false;
    }
    else
        return false;
}

function strToTimestamp2($date, $format) {
    $decoded_date = strptimeEx($date, dateFormatToStrftime($format));
    if ($decoded_date !== false) {
        return mktime(
            $decoded_date['tm_hour'],
            $decoded_date['tm_min'],
            $decoded_date['tm_sec'],
            $decoded_date['tm_mon'] + 1,
            $decoded_date['tm_mday'],
            $decoded_date['tm_year'] + 1900
        );
    } else
        return false;
}

function dateFormatToStrftime($dateFormat) {
// from http://php.net/manual/en/function.strftime.php#96424
    $caracs = array(
        // Day - no strf eq : S
        'd' => '%d', 'D' => '%a', 'j' => '%e', 'l' => '%A', 'N' => '%u', 'w' => '%w', 'z' => '%j',
        // Week - no date eq : %U, %W
        'W' => '%V',
        // Month - no strf eq : n, t
        'F' => '%B', 'm' => '%m', 'M' => '%b',
        // Year - no strf eq : L; no date eq : %C, %g
        'o' => '%G', 'Y' => '%Y', 'y' => '%y',
        // Time - no strf eq : B, G, u; no date eq : %r, %R, %T, %X
        'a' => '%P', 'A' => '%p', 'g' => '%l', 'h' => '%I', 'H' => '%H', 'i' => '%M', 's' => '%S',
        // Timezone - no strf eq : e, I, P, Z
        'O' => '%z', 'T' => '%Z',
        // Full Date / Time - no strf eq : c, r; no date eq : %c, %D, %F, %x
        'U' => '%s'
    );
    return strtr((string)$dateFormat, $caracs);
}


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

    public function __construct($timestamp) {
        if (SMReflection::IsInstanceOf($timestamp, 'SMDateTime')) {
            if (self::UseNativeDateTimeClass())
                $this->dateTime = $timestamp->GetDatetime();
            else
                $this->timestamp = $timestamp->GetTimestamp();
        } else {
            if (self::UseNativeDateTimeClass())
                $this->dateTime = new DateTime($timestamp);
            else
                $this->timestamp = $timestamp;
        }
    }

    public static function Parse($stringValue, $format) {
        if (self::UseNativeDateTimeClass()) {
            if (is_object($stringValue) && (get_class($stringValue) == 'DateTime'))
                return new SMDateTime($stringValue->format('d-m-Y H:i:s'));
            else
                return new SMDateTime($stringValue);
        } else {
            // HACK: move to client code
            if (is_object($stringValue) && (get_class($stringValue) == 'DateTime'))
                return new SMDateTime(strtotime($stringValue->format('d-m-Y H:i:s')));
            if (is_object($stringValue) && (get_class($stringValue) == 'SMDateTime')) {
                return $stringValue;
            } else {
                if ($format) {
                  $timestamp = strToTimestamp($stringValue, $format);
                  if ($timestamp === false) {
                      $timestamp = strToTimestamp2($stringValue, $format);
                      if ($timestamp === false) {
                          $timestamp = strtotime($stringValue);
                      }
                  }
                }
                else
                  $timestamp = strtotime($stringValue);
                return new SMDateTime($timestamp);
            }
        }
    }

    public static function Now() {
        if (self::UseNativeDateTimeClass())
            return new SMDateTime("now");
        else
            return new SMDateTime(time());
    }

    public function ToRfc822String() {
        if (self::UseNativeDateTimeClass())
            return
                $this->dateTime->format('D, d M Y H:i:s T');
        else
            return
                @date('D, d M Y H:i:s T', $this->timestamp);
    }

    public function ToString($format) {
        if (self::UseNativeDateTimeClass())
            return $this->dateTime->format($format);
        else
            return @date($format, $this->timestamp);
    }

    public function ToAnsiSQLString() {
        return $this->ToString("Y-m-d H:i:s");
    }

    public function __toString() {
        return $this->ToAnsiSQLString();
    }
}
