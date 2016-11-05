<?php

include_once dirname(__FILE__) . '/abstract_xml_renderer.php';

class XmlRecordRenderer extends AbstractXmlRenderer
{
    protected function getGridPagePart()
    {
        return PagePart::RecordCard;
    }
}
