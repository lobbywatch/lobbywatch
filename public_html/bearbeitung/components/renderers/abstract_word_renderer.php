<?php

include_once dirname(__FILE__) . '/abstract_export_renderer.php';

abstract class AbstractWordRenderer extends AbstractExportRenderer
{
    abstract protected function getGridPagePart();

    public function RenderPage(Page $Page)
    {
        if ($Page->GetContentEncoding() != null) {
            header('Content-type: application/vnd.ms-word; charset=' . $Page->GetContentEncoding());
        } else {
            header("Content-type: application/vnd.ms-word");
        }

        $options = array(
            'filename' => Path::ReplaceFileNameIllegalCharacters($Page->GetTitle() . ".doc"),
        );
        $Page->GetCustomExportOptions(
            'doc',
            $this->getCurrentRowData($Page->GetGrid()),
            $options
        );

        $this->DisableCacheControl();
        header("Content-Disposition: attachment;Filename=" . $options['filename']);
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
        header("Pragma: public");
        set_time_limit(0);

        $customParams = array();
        $template = $Page->GetCustomTemplate(
            PagePart::ExportLayout,
            PageMode::ExportWord,
            'export/word_page.tpl',
            $customParams
        );

        $Grid = $this->Render($Page->GetGrid());
        $this->DisplayTemplate($template,
            array('Page' => $Page),
            array_merge($customParams, array('Grid' => $Grid))
        );
    }

    protected function GetCustomRenderedViewColumn(AbstractViewColumn $column, $rowValues)
    {
        $result = '';
        $handled = false;
        $column->GetGrid()->OnCustomRenderExportColumn->Fire(array(
            'word',
            $this->GetFriendlyColumnName($column),
            $column->GetValue(),
            $rowValues,
            &$result,
            &$handled,
        ));

        if ($handled) {
            return $result;
        }

        return null;
    }

    function RenderGrid(Grid $Grid)
    {
        $Rows = array();
        $HeaderCaptions = array();
        $Grid->GetDataset()->Open();

        foreach($Grid->GetExportColumns() as $Column) {
            $HeaderCaptions[] = $Column->GetCaption();
        }

        while($Grid->GetDataset()->Next()) {
            $rowValues = $Grid->GetDataset()->GetCurrentFieldValues();
            $Row = array();
            foreach($Grid->GetExportColumns() as $Column) {
                $cell['Value'] = $this->RenderViewColumn($Column, $rowValues);
                $cell['Align'] = $Column->GetAlign();
                $Row[] = $cell;
            }
            $Rows[] = $Row;
        }

        $customParams = array();
        $template = $Grid->getPage()->GetCustomTemplate(
            $this->getGridPagePart(),
            PageMode::ExportWord,
            'export/word_grid.tpl',
            $customParams
        );

        $this->DisplayTemplate($template,
            array('Grid' => $Grid),
            array_merge($customParams, array(
                'HeaderCaptions' => $HeaderCaptions,
                'Rows' => $Rows,
                'Totals' => $Grid->getTotalsViewData($Grid->GetExportColumns())
            ))
        );
    }

    public function RenderPageNavigator($PageNavigator)
    {
    }

    public function RenderDetailPage(DetailPage $DetailPage)
    {
        $this->RenderPage($DetailPage);
    }

    protected function HttpHandlersAvailable()
    {
        return false;
    }
    protected function HtmlMarkupAvailable()
    {
        return true;
    }

    protected function InteractionAvailable()
    {
        return false;
    }
}
