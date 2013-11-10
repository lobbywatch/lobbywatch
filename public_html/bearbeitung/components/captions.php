<?php

ob_start();

include_once("components/default_lang.php");

if (isset($_GET['resetlang']))
{
    setcookie("lang", '', time()-3600);
    include_once("components/lang.php");
}
else
{
    if (isset($_GET['lang']))
    {
        $lang = substr($_GET['lang'], 0, 2);
        setcookie("lang", $lang, time()+3600);
        
        if (file_exists("components/lang.".$lang.".php"))
            /** @noinspection PhpIncludeInspection */
            include_once("components/lang.".$lang.".php");
        else
            include_once("components/lang.php");
    }
    elseif (isset($_COOKIE['lang']))
    {
        $lang = substr($_COOKIE['lang'], 0, 2);
        if (file_exists("components/lang.".$lang.".php"))
            /** @noinspection PhpIncludeInspection */
            include_once("components/lang.".$lang.".php");
        else
            include_once("components/lang.php");
    }
    else
    {
        include_once("components/lang.php");
    }
}
ob_end_clean();

include_once("phpgen_settings.php");
include_once("components/utils/string_utils.php");

class Captions
{
    private $pageEncoding;
    
    public function __construct($pageEncoding)
    { 
        if ($pageEncoding == null || $pageEncoding == '')
            $this->pageEncoding = GetAnsiEncoding();
        else
        $this->pageEncoding = $pageEncoding;
    }

    public function RenderText($text) {
        return ConvertTextToEncoding($text, GetAnsiEncoding(), $this->GetEncoding());
    }

    public function GetEncoding() { return $this->pageEncoding; }
    
    private function GetCaptionByName($name)
    {
        $result = eval('global $c'.$name.'; return $c'.$name.';');
        return StringUtils::ConvertTextToEncoding($result, 'UTF-8', $this->pageEncoding);
    }

    public function GetMessageString($name) { return $this->GetCaptionByName($name); }
}

$captions = new Captions('UTF-8');
$captionsMap = array($captions->GetEncoding() => $captions);

/**
 * @param string $encoding
 * @return Captions
 */
function GetCaptions($encoding = null)
{
    if ($encoding == null || $encoding == '')
    {
        return GetCaptions(GetAnsiEncoding());
    }
    else 
    {
        global $captionsMap;
        if (!array_key_exists($encoding, $captionsMap))
            $captionsMap[$encoding] = new Captions($encoding);
        return $captionsMap[$encoding];
    }
}

?>