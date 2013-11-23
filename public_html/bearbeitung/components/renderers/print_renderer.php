<?php

// require_once 'components/renderers/renderer.php';
// require_once 'components/utils/query_utils.php';

include_once dirname(__FILE__) . '/' . 'renderer.php';
include_once dirname(__FILE__) . '/' . '../utils/query_utils.php';


class PrintRenderer extends Renderer
{
    function RenderPageNavigator($PageNavigator)
    { }

    function RenderDetailPageEdit($DetailPage)
    {
        $Grid = $this->Render($DetailPage->GetGrid());
        $masterGrid = $this->Render($DetailPage->GetMasterGrid());

        $this->DisplayTemplate('print/detail_page.tpl',
            array('Page' => $DetailPage),
            array(
                'Grid' => $Grid,
                'MasterGrid' => $masterGrid
            ));
    }

    function RenderPage(Page $Page)
    {
        $this->SetHTTPContentTypeByPage($Page);
        $Page->BeforePageRender->Fire(array(&$Page));
        set_time_limit(0);

        $Grid = $this->Render($Page->GetGrid());
        $this->DisplayTemplate('print/page.tpl',
            array('Page' => $Page),
            array('Grid' => $Grid));
    }

    protected function GetCustomRenderedViewColumn(CustomViewColumn $column, $rowValues)
    {
        $result = '';
        $handled = false;
        $column->GetGrid()->OnCustomRenderPrintColumn->Fire(array(
            $this->GetFriendlyColumnName($column), $column->GetData(), $rowValues, &$result, &$handled)
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
            $Rows[] = Q::ToArray(Q::Select($Grid->GetPrintColumns(),
                Q::L('$c => $_1->RenderViewColumn($c, $_2)', $this, $rowValues)));
        }
        $totals = array();
        if ($Grid->HasTotals())
        {
            $totalValues = $Grid->GetTotalValues();
            foreach($Grid->GetPrintColumns() as $column)
                $totals[] = $column->GetTotalPresentationData(
                    ArrayUtils::GetArrayValueDef($totalValues, $column->GetName(), null));
        }


        $this->DisplayTemplate('print/grid.tpl',
            array(
                'Grid' => $Grid
                ),
            array(
                'Columns' => $Grid->GetPrintColumns(),
                'Rows' => $Rows,
                'Totals' => $totals
            ));
    }
    
    protected function ChildPagesAvailable() 
    { 
        return false; 
    }
}
