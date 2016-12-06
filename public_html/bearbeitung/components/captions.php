<?php

if (file_exists(dirname(__FILE__) . '/../phpgen_settings.php')) {
    include_once dirname(__FILE__) . '/../phpgen_settings.php';
} else {
    function GetAnsiEncoding() {
        return 'windows-1251';
    }
}

include_once dirname(__FILE__) . '/utils/string_utils.php';
include_once dirname(__FILE__) . '/utils/system_utils.php';

class Captions
{
    static private $instances = array();

    private $encoding;

    private function __construct($encoding)
    {
        if ($encoding == null || $encoding == '') {
            $this->encoding = GetAnsiEncoding();
        } else {
            $this->encoding = $encoding;
        }

        $langFile = $this->getLangFile();
        $this->translations = require($langFile);
    }

    static public function getInstance($encoding)
    {
        if (!isset(self::$instances[$encoding])) {
            self::$instances[$encoding] = new self($encoding);
        }

        return self::$instances[$encoding];
    }

    public function RenderText($text)
    {
        return ConvertTextToEncoding($text, GetAnsiEncoding(), $this->encoding);
    }

    public function GetEncoding()
    {
        return $this->encoding;
    }

    public function GetMessageString($name)
    {
        return StringUtils::ConvertTextToEncoding(
            isset($this->translations[$name]) ? $this->translations[$name] : $name,
            'UTF-8',
            $this->encoding
        );
    }

    /**
     * @param $langFile string
     * @return boolean
     */
    private function checkLangFile($langFile)
    {
        return is_array(require($langFile));
    }

    private function getLangFile()
    {
        $filenameTemplate = dirname(__FILE__) . '/languages/%s.php';

        $defaultLang = sprintf($filenameTemplate, 'default_lang');
        $result = $defaultLang;
        if (file_exists(sprintf($filenameTemplate, 'lang'))) {
            $result = sprintf($filenameTemplate, 'lang');
        }

        if (isset($_GET['resetlang'])) {
            $_COOKIE['lang'] = '';
            setcookie('lang', '', time() - 3600);
        } else if (isset($_GET['lang'])) {
            $lang = substr($_GET['lang'], 0, 2);
            $filename = sprintf($filenameTemplate, 'lang.' . $lang);
            if (file_exists($filename)) {
                $_COOKIE['lang'] = $lang;
                setcookie('lang', $lang, time() + 3600);
                $result = $filename;
            }
        } elseif (isset($_COOKIE['lang'])) {
            $lang = substr($_COOKIE['lang'], 0, 2);
            $filename = sprintf($filenameTemplate, 'lang.' . $lang);
            if (file_exists($filename)) {
                $result = $filename;
            }
        }

        if ($this->checkLangFile($result)) {
            return $result;
        } else {
            return $defaultLang;
        }
    }
}
