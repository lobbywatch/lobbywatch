<?php

require_once 'renderer.php';
require_once 'components/utils/file_utils.php';

class XmlRenderer extends Renderer
{
    function RenderPageNavigator($PageNavigator)
    { }

    function RenderDetailPageEdit($DetailPage)
    {
        $this->RenderPage($DetailPage);
    }
    
    function RenderPage(Page $Page)
    {
        header("Content-type: text/xml");
        $this->DisableCacheControl();
        header("Content-Disposition: attachment;Filename=" .
            Path::ReplaceFileNameIllegalCharacters($Page->GetCaption() . ".xml"));
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
        header("Pragma: public");
        set_time_limit(0);
        
        $Grid = $this->Render($Page->GetGrid());
        $this->DisplayTemplate('export/xml_page.tpl',
            array('Page' => $Page),
            array('Grid' => $Grid));
    }
    
    private function PrepareColumnCaptionForXml($caption)
    {
        return htmlspecialchars(str_replace(' ', '', $caption));
    }
    
    protected function GetCustomRenderedViewColumn(CustomViewColumn $column, $rowValues)
    {
        $result = '';
        $handled = false;
        $column->GetGrid()->OnCustomRenderExportColumn->Fire(array(
            'xml', $this->GetFriendlyColumnName($column), $column->GetData(), $rowValues, &$result, &$handled)
        );

        if ($handled)
            return $result;
        else
            return null;
    }

    function RenderGrid(Grid $Grid)
    {
        $Rows = array();
        $Grid->GetDataset()->Open();

        while($Grid->GetDataset()->Next())
        {
            $rowValues = $Grid->GetDataset()->GetCurrentFieldValues();
            $Row = array();
            foreach($Grid->GetExportColumns() as $column)
                $Row[$this->PrepareColumnCaptionForXml($column->GetCaption())] = $this->RenderViewColumn($column, $rowValues);
            $Rows[] = $Row;
        }
        
        $this->DisplayTemplate('export/xml_grid.tpl',
            array(
                    'Grid' => $Grid
                    ),
                array(
                    'Rows' => $Rows,
                    'TableName' => $Grid->GetPage()->GetCaption()
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
?>