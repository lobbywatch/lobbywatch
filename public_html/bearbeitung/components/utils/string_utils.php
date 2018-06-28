<?php

class JavaScriptStringLiteralEscapeMode
{
    const DoubleQuote = 1;
    const SingleQuote = 2;
}

class StringUtils
{
    const EmptyStr = '';
    const Space = ' ';

    public static function SameText($val1, $val2)
    {
        return strtolower($val1) == strtolower($val2);
    }

    public static function NormalizeQuotation($string, $firstQuote, $lastQuote)
    {
        return StringUtils::QuoteString(
            StringUtils::UnquoteString($string, $firstQuote, $lastQuote),
            $firstQuote, $lastQuote) ;
    }

    public static function UnquoteString($string, $firstQuote, $lastQuote)
    {
        return ltrim(rtrim($string, $lastQuote), $firstQuote);
    }

    public static function QuoteString($string, $firstQuote, $lastQuote)
    {
        return $firstQuote . $string.  $lastQuote;
    }

    public static function SplitString($delimiter, $string)
    {
        return explode ($delimiter, $string);
    }

    public static function JSStringLiteral($stringLiteral,
        $mode = JavaScriptStringLiteralEscapeMode::DoubleQuote)
    {
        switch ($mode)
        {
            case JavaScriptStringLiteralEscapeMode::DoubleQuote:
                $searches = array('\\', '"', "\n" );
                $replacements = array('\\\\', '\\"', "\\n\"\n\t+\"" );
                $quote = '"';
                break;
            case JavaScriptStringLiteralEscapeMode::SingleQuote:
                $searches = array('\\', "'", "\n" );
                $replacements = array( '\\\\', "\\'", "\\n'\n\t+'" );
                $quote = "'";
                break;
        }
        return $quote . str_replace($searches, $replacements, $stringLiteral) . $quote;
    }

    /**
     * @static
     * @param string $value
     * @return string
     */
    public static function Upper($value)
    {
        return strtoupper($value);
    }

    /**
     * @static
     * @param string $value
     * @return string
     */
    public static function Lower($value)
    {
        return strtolower($value);
    }

    public static function ReplaceVariableInTemplate($template, $varName, $varValue)
    {
        if (is_array($varValue))
            return $template;
        else
            return str_ireplace('%' . $varName . '%', $varValue, $template);
    }

    public static function ReplaceVariables($template, $vars)
    {
        foreach ($vars as $key => $value) {
            $template = self::ReplaceVariableInTemplate($template, $key, $value);
        }
        return $template;
    }

    public static function StartsWith($string, $pattern)
    {
        $matches = array();
        return preg_match("/^$pattern/", $string, $matches) >= 1;
    }

    public static function EndsBy($string, $pattern)
    {
        $matches = array();
        return preg_match('/' . $pattern . '$/', $string, $matches) >= 1;
    }

    public static function Contains($string, $pattern)
    {
        $matches = array();
        return preg_match("/$pattern/", $string, $matches) >= 1;
    }

    public static function EscapeXmlString($strin)
    {
        $strout = null;

        for ($i = 0; $i < strlen($strin); $i++) {
            $ord = ord($strin[$i]);

            if (($ord > 0 && $ord < 32) || ($ord >= 127)) {
                $strout .= "&amp;#{$ord};";
            }
            else {
                switch ($strin[$i]) {
                    case '<':
                        $strout .= '&lt;';
                        break;
                    case '>':
                        $strout .= '&gt;';
                        break;
                    case '&':
                        $strout .= '&amp;';
                        break;
                    case '"':
                        $strout .= '&quot;';
                        break;
                    default:
                        $strout .= $strin[$i];
                }
            }
        }

        return $strout;
    }

    public static function AddStr(&$result, $string, $delimiter = '')
    {
        if (isset($string) && $string != '')
        {
            if(!($result == ''))
                $result = $result . $delimiter;
            $result = $result . $string;
        }
    }

    public static function Replace($oldValue, $newValue, $string)
    {
        return str_replace($oldValue, $newValue, $string);
    }

    public static function ApplyVariablesMapToTemplate($template, $varArray)
    {
        $result = $template;
        foreach($varArray as $varName => $varValue)
            $result = StringUtils::ReplaceVariableInTemplate($result, $varName, $varValue);
        return $result;
    }

    public static function Combine($Left, $Right, $Delimiter = ' = ')
    {
        return $Left . $Delimiter . $Right;
    }

    public static function IsNullOrEmpty($value, $trimmed = false)
    {
        if (!isset($value))
            return true;
        else if (!$trimmed)
            return ($value === '');
        else
            return (trim($value) === '');
    }

    public static function ReplaceIllegalPostVariableNameChars($string)
    {
        return StringUtils::Replace(' ', '_', $string);
    }

    public static function Format($format)
    {
        $arg_list = func_get_args();
        return call_user_func_array('sprintf', $arg_list);
    }

    public static function ConvertTextToEncoding($text, $sourceEncoding, $targetEncoding)
    {
        $iconvEncodings = array('windows-1250', 'windows-1253', 'windows-1254', 'windows-1255', 'windows-1256', 'windows-1257');

        if ($sourceEncoding != '' && $targetEncoding != '' && $targetEncoding != $sourceEncoding)
        {
            if (!in_array($sourceEncoding, $iconvEncodings) &&
                !in_array($targetEncoding, $iconvEncodings) &&
                    function_exists("mb_convert_encoding"))
            {
                if ($sourceEncoding == null)
                    return mb_convert_encoding($text, $targetEncoding);
                else
                    return mb_convert_encoding($text, $targetEncoding, $sourceEncoding);
            }
            elseif (function_exists("iconv"))
                return iconv($sourceEncoding, $targetEncoding, $text);
            else
                return $text;
        }
        else
        {
            return $text;
        }
    }

    public static function getEncodedArray($array, $sourceEncoding, $targetEncoding) {
        $result = array();
        foreach($array as $value) {
            $result[] = self::ConvertTextToEncoding($value, $sourceEncoding, $targetEncoding);
        }
        return $result;
    }

    public static function SubString($value, $start, $length, $encoding)
    {
        if (function_exists('mb_substr') && $encoding != '')
            return mb_substr($value, $start, $length, $encoding);
        else
            return substr($value, $start, $length);
    }

    public static function StringLength($value, $encoding) {
        if (function_exists('mb_strlen') && $encoding != '')
            return mb_strlen($value, $encoding);
        else
            return strlen($value);
    }

    public static function EscapeString($value, $encoding) {
        return htmlspecialchars($value, ENT_COMPAT, $encoding);
    }
}
