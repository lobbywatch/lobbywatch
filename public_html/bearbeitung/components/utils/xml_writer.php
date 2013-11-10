<?php

require_once 'string_utils.php';

interface IPGXMLWriter
{
    function StartDocument($xmlVersion, $xmlEncoding);

    function StartElement($name);

    function WriteElement($name, $value);

    function EndElement($name);

    function GetResult();
}

class PGXMLWriter implements IPGXMLWriter
{
    private $result;

    private function AddStr($string, $delimiter = '')
    {
        StringUtils::AddStr($this->result, $string, $delimiter);
    }

    public function __construct()
    {
        $this->result = '';
    }

    function EndElement($name)
    {
        $this->AddStr("</$name>");
    }

    function GetResult()
    {
        return $this->result;
    }

    function StartDocument($xmlVersion, $xmlEncoding)
    {
        $this->AddStr("<?xml version=\"$xmlVersion\" encoding=\"$xmlEncoding\"?>\n");
    }

    function StartElement($name)
    {
        $this->AddStr("<$name>");
    }

    function WriteElement($name, $value)
    {
        $this->AddStr("<$name>" . htmlspecialchars($value). "</$name>");
    }
}

class NativeXMLWriterAdapter implements IPGXMLWriter
{
    private $xmlWriter;

    public function __construct()
    {
        $this->xmlWriter = new XMLWriter();
        $this->xmlWriter->openMemory();
    }

    public function EndElement($name)
    {
        $this->xmlWriter->endElement();
    }

    public function StartDocument($xmlVersion, $xmlEncoding) {
        $this->xmlWriter->startDocument($xmlVersion, $xmlEncoding);
    }

    public function StartElement($name)
    {
        $this->xmlWriter->startElement($name);
    }

    public function WriteElement($name, $value)
    {
        $this->xmlWriter->writeElement($name, $value);
    }

    public function GetResult()
    {
        return $this->xmlWriter->outputMemory(true);
    }
    
}

class XMLWriterFactory
{
    public static function CreateXMLWriter()
    {
        if (class_exists('XMLWriter'))
            return new NativeXMLWriterAdapter();
        else
            return new PGXMLWriter();
    }
}

?>
