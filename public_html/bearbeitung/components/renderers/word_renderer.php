<?php

// require_once 'renderer.php';
// require_once 'components/utils/file_utils.php';

include_once dirname(__FILE__) . '/' . 'renderer.php';
include_once dirname(__FILE__) . '/' . '../utils/file_utils.php';


class WordRenderer extends Renderer
{
    function RenderPageNavigator($PageNavigator)
    { }

    function RenderDetailPageEdit($DetailPage)
    {
        $this->RenderPage($DetailPage);
    }

    function RenderPage(Page $Page)
    {
        if ($Page->GetContentEncoding() != null)
            header('Content-type: application/vnd.ms-word; charset=' . $Page->GetContentEncoding());
        else
            header("Content-type: application/vnd.ms-word");
        $this->DisableCacheControl();
        header("Content-Disposition: attachment;Filename=" . Path::ReplaceFileNameIllegalCharacters($Page->GetCaption() . ".doc"));
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
        header("Pragma: public");
        set_time_limit(0);
        
        $Grid = $this->Render($Page->GetGrid());
        $this->DisplayTemplate('export/word_page.tpl',
            array('Page' => $Page),
            array('Grid' => $Grid));
    }

    protected function GetCustomRenderedViewColumn(CustomViewColumn $column, $rowValues)
    {
        $result = '';
        $handled = false;
        $column->GetGrid()->OnCustomRenderExportColumn->Fire(array(
            'word', $this->GetFriendlyColumnName($column), $column->GetData(), $rowValues, &$result, &$handled)
        );

        if ($handled)
            return $result;
        else
            return null;
    }
    
    function RenderGrid(Grid $Grid)
    {
        $Rows = array();
        $HeaderCaptions = array();
        $Grid->GetDataset()->Open();
        foreach($Grid->GetExportColumns() as $Column)
            $HeaderCaptions[] = $Column->GetCaption();
        while($Grid->GetDataset()->Next())
        {
            $rowValues = $Grid->GetDataset()->GetCurrentFieldValues();
            $Row = array();
            foreach($Grid->GetExportColumns() as $Column)
            {
                $cell['Value'] = $this->RenderViewColumn($Column, $rowValues);
                $cell['Align'] = $Column->GetAlign();
                $Row[] = $cell;
            }
            $Rows[] = $Row;
        }
        
        $this->DisplayTemplate('export/word_grid.tpl',
            array(
                    'Grid' => $Grid
                    ),
                array(
                    'HeaderCaptions' => $HeaderCaptions,
                    'Rows' => $Rows
                    ));
    }
    
    protected function HttpHandlersAvailable() 
    { 
        return false; 
    }
    protected function HtmlMarkupAvailable() 
    { 
        return false; 
    }        
}
