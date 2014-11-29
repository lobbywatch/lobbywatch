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

        $customParams = array();
        $template = $DetailPage->GetParentPage()->GetCustomTemplate(PagePart::Layout, PageMode::PrintDetailPage, 'print/detail_page.tpl', $customParams);

        $this->DisplayTemplate($template,
            array('Page' => $DetailPage),
            array_merge($customParams,
                array(
                    'Grid' => $Grid,
                    'MasterGrid' => $masterGrid
                )
            )
        );
    }

    function RenderPage(Page $Page)
    {
        $this->SetHTTPContentTypeByPage($Page);
        $Page->BeforePageRender->Fire(array(&$Page));
        set_time_limit(0);
        $Grid = $this->Render($Page->GetGrid());

        $customParams = array();
        $template = $Page->GetCustomTemplate(PagePart::Layout, PageMode::PrintAll, 'print/page.tpl', $customParams);

        $this->DisplayTemplate($template,
            array('Page' => $Page),
            array_merge($customParams,
                array(
                    'Grid' => $Grid
                )
            )
        );
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

        $customParams = array();
        $template = $Grid->GetPage()->GetCustomTemplate(PagePart::Grid, PageMode::PrintAll, 'print/grid.tpl', $customParams);

        $this->DisplayTemplate($template,
            array(
                'Grid' => $Grid
                ),
            array_merge($customParams,
                array(
                    'Columns' => $Grid->GetPrintColumns(),
                    'Rows' => $Rows,
                    'Totals' => $totals
                )
            )
        );
    }
    
    protected function ChildPagesAvailable() 
    { 
        return false; 
    }
}
