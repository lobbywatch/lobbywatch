<?php

include_once dirname(__FILE__) . '/abstract_word_renderer.php';

class WordRecordRenderer extends AbstractWordRenderer
{
    protected function getGridPagePart()
    {
        return PagePart::RecordCard;
    }
}
