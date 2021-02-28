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
    } else {
        return strToTimestamp2($date, $format);
    }
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

    public function GetTimestamp() {
        return $this->timestamp;
    }

    public function getPHPDateTime() {
        $result = new DateTime();
        $result->setTimestamp($this->timestamp);
        return $result;
    }

    public function __construct($timestamp) {
        if (SMReflection::IsInstanceOf($timestamp, 'SMDateTime')) {
            $this->timestamp = $timestamp->GetTimestamp();
        } else {
            $this->timestamp = $timestamp;
        }
    }

    public static function Parse($stringValue, $format) {
        if (is_object($stringValue) && (get_class($stringValue) == 'DateTime'))
            return new SMDateTime(strtotime($stringValue->format('d-m-Y H:i:s')));
        if (is_object($stringValue) && (get_class($stringValue) == 'SMDateTime')) {
            return $stringValue;
        } else {
            if ($format) {
                $timestamp = strToTimestamp($stringValue, $format);
                if ($timestamp === false) {
                    $timestamp = strtotime($stringValue);
                }
            }
            else
                $timestamp = strtotime($stringValue);
            return new SMDateTime($timestamp);
        }
    }

    public static function Now() {
        return new SMDateTime(time());
    }

    public function ToRfc822String() {
        return @date('D, d M Y H:i:s T', $this->timestamp);
    }

    public function format($format)
    {
        return @date($format, $this->timestamp);
    }

    public function ToString($format) {
        return @date($format, $this->timestamp);
    }

    public function ToAnsiSQLString($withTime = true) {
        return $this->ToString('Y-m-d' . ($withTime ? ' H:i:s' : ''));
    }

    public function __toString() {
        return $this->ToAnsiSQLString();
    }

    public function inThePast() {
        return $this->getPHPDateTime() < new DateTime();
    }

    public function inTheFuture() {
        return $this->getPHPDateTime() > new DateTime();
    }

    public function getDayOfMonth() {
        return $this->getPHPDateTime()->format('j');
    }

    public function getMonth() {
        return $this->getPHPDateTime()->format('n');
    }

    public function getYear() {
        return $this->getPHPDateTime()->format('Y');
    }

    public function addDays($days) {
        $auxDateTime = $this->getPHPDateTime();
        $daysString = (($days > 0) ? '+': '-') . abs($days);
        $auxDateTime->modify($daysString . ' days');
        $this->timestamp = $auxDateTime->getTimestamp();
    }

    private function isDayOfWeek($day) {
        return $this->getPHPDateTime()->format('w') == $day;
    }

    public function isMonday() {
        return $this->isDayOfWeek(1);
    }

    public function isTuesday() {
        return $this->isDayOfWeek(2);
    }

    public function isWednesday() {
        return $this->isDayOfWeek(3);
    }

    public function isThursday() {
        return $this->isDayOfWeek(4);
    }

    public function isFriday() {
        return $this->isDayOfWeek(5);
    }

    public function isSaturday() {
        return $this->isDayOfWeek(6);
    }

    public function isSunday() {
        return $this->isDayOfWeek(0);
    }

    /**
     * @param $date SMDateTime
     * @return int
     */
    public function diffInDays($date) {
        $diffDate = new DateTime();
        $diffDate->setTimestamp($date->GetTimestamp());
        $dateInterval = $this->getPHPDateTime()->diff($diffDate);
        return $dateInterval->format('%a');
    }

}

function AnsiSQLStringToDateTime($value) {
    if (isset($value))
        return SMDateTime::Parse($value, 'Y-m-d H:i:s');
    else
        return null;
}

function AnsiSQLStringToDate($value) {
    if (isset($value))
        return SMDateTime::Parse($value, 'Y-m-d');
    else
        return null;
}

function AnsiSQLStringToTime($value) {
    if (isset($value))
        return SMDateTime::Parse($value, 'H:i:s');
    else
        return null;
}
