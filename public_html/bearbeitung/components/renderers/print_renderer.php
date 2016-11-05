<?php

// require_once 'components/renderers/renderer.php';
// require_once 'components/utils/query_utils.php';

include_once dirname(__FILE__) . '/' . 'renderer.php';
include_once dirname(__FILE__) . '/' . '../utils/query_utils.php';

abstract class AbstractPrintRenderer extends Renderer
{
    public function RenderPageNavigator($PageNavigator)
    {
    }

    protected function doRenderPage(Page $Page, $pageMode, $templatePath)
    {
        $this->SetHTTPContentTypeByPage($Page);
        $Page->BeforePageRender->Fire(array(&$Page));
        set_time_limit(0);
        $Grid = $this->Render($Page->GetGrid());

        $customParams = array();
        $template = $Page->GetCustomTemplate(PagePart::PrintLayout, $pageMode, $templatePath, $customParams);

        $this->DisplayTemplate($template,
            array('Page' => $Page),
            array_merge($customParams,
                array(
                    'Grid' => $Grid
                )
            )
        );
    }

    protected function doRenderGrid(Grid $Grid, $pageMode, $templatePath, $customParams = array())
    {
        $Rows = array();

        while ($Grid->GetDataset()->Next()) {
            $rowValues = $Grid->GetDataset()->GetCurrentFieldValues();
            $Rows[] = Q::ToArray(Q::Select($Grid->GetPrintColumns(),
                Q::L('$c => $_1->RenderViewColumn($c, $_2)', $this, $rowValues)));
        }

        $template = $Grid->GetPage()->GetCustomTemplate(PagePart::Grid, $pageMode, $templatePath, $customParams);

        $this->DisplayTemplate($template,
            array(
                'Grid' => $Grid
                ),
            array_merge($customParams,
                array(
                    'Columns' => $Grid->GetPrintColumns(),
                    'Rows' => $Rows,
                )
            )
        );
    }

    protected function GetCustomRenderedViewColumn(AbstractViewColumn $column, $rowValues)
    {
        $result = '';
        $handled = false;
        $column->GetGrid()->OnCustomRenderPrintColumn->Fire(array(
            $this->GetFriendlyColumnName($column), $column->GetValue(), $rowValues, &$result, &$handled)
        );

        if ($handled)
            return $result;
        else
            return null;
    }

    protected function ChildPagesAvailable()
    {
        return false;
    }

    protected function InteractionAvailable()
    {
        return false;
    }

    protected function ShowHtmlNullValue()
    {
        return true;
    }
}

class PrintRenderer extends AbstractPrintRenderer
{
    public function RenderDetailPage(DetailPage $DetailPage)
    {
        $Grid = $this->Render($DetailPage->GetGrid());
        $masterGrid = $this->Render($DetailPage->GetMasterGrid());

        $customParams = array();
        $template = $DetailPage->GetParentPage()->GetCustomTemplate(PagePart::PrintLayout, PageMode::PrintDetailPage, 'print/detail_page.tpl', $customParams);

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

    public function RenderPage(Page $Page)
    {
        $this->doRenderPage($Page, PageMode::PrintAll, 'print/page.tpl');
    }

    public function RenderGrid(Grid $Grid)
    {
        $Grid->GetDataset()->Open();

        $totals = array();
        if ($Grid->HasTotals()) {
            $totalValues = $Grid->GetTotalValues();
            foreach($Grid->GetPrintColumns() as $column) {
                $totals[] = $column->GetTotalPresentationData(
                    ArrayUtils::GetArrayValueDef($totalValues, $column->GetName(), null));
            }
        }

        $this->doRenderGrid($Grid, PageMode::PrintAll, 'print/grid.tpl', array(
            'Totals' => $totals,
        ));
    }
}

class PrintOneRecordRenderer extends AbstractPrintRenderer
{
    function RenderDetailPage(DetailPage $page)
    {
        $this->RenderPage($page);
    }

    function RenderPage(Page $Page)
    {
        $this->doRenderPage($Page, PageMode::PrintOneRecord, 'print/page.tpl');
    }

    function RenderGrid(Grid $Grid)
    {
        $Grid->GetDataset()->Open();
        $this->doRenderGrid($Grid, PageMode::PrintOneRecord, 'view/print_grid.tpl');
    }
}
