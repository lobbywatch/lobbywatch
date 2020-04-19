<?php

include_once dirname(__FILE__) . '/utils/string_utils.php';
include_once dirname(__FILE__) . '/utils/system_utils.php';

class Captions
{
    static private $instances = array();

    private $encoding;

    private function __construct($encoding)
    {
        $this->encoding = $encoding;

        $defaultLangTranslations = require($this->getDefaultLangFile());
        $selectedLangTranslations = require($this->getLangFile());

        $this->translations = array_merge($defaultLangTranslations, $selectedLangTranslations);
    }

    /**
     * @param string $encoding
     * @return Captions
     */
    static public function getInstance($encoding)
    {
        if (!isset(self::$instances[$encoding])) {
            self::$instances[$encoding] = new self($encoding);
        }

        return self::$instances[$encoding];
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

    private function getDefaultLangFile()
    {
        return $this->getFullFileName('default_lang');
    }

    private function getLangFile()
    {
        $result = $this->getDefaultLangFile();
        if (file_exists($this->getFullFileName('lang'))) {
            $result = $this->getFullFileName('lang');
        }

        if (isset($_GET['resetlang'])) {
            $_COOKIE['lang'] = '';
            setcookie('lang', '', time() - 3600);
        } else if (isset($_GET['lang'])) {
            $lang = substr($_GET['lang'], 0, 2);
            $filename = $this->getFullFileName('lang.' . $lang);
            if (file_exists($filename)) {
                $_COOKIE['lang'] = $lang;
                setcookie('lang', $lang, time() + 3600);
                $result = $filename;
            }
        } elseif (isset($_COOKIE['lang'])) {
            $lang = substr($_COOKIE['lang'], 0, 2);
            $filename = $this->getFullFileName('lang.' . $lang);
            if (file_exists($filename)) {
                $result = $filename;
            }
        }

        if ($this->checkLangFile($result)) {
            return $result;
        } else {
            return $this->getDefaultLangFile();
        }
    }

    private function getFullFileName($fileName)
    {
        return sprintf(dirname(__FILE__) . '/languages/%s.php', $fileName);
    }

}
