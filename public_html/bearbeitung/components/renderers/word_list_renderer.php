<?php

include_once dirname(__FILE__) . '/abstract_word_renderer.php';

class WordListRenderer extends AbstractWordRenderer
{
    protected function getGridPagePart()
    {
        return PagePart::Grid;
    }

    protected function getCurrentRowData(Grid $grid)
    {
        return null;
    }
}
