<?php

include_once dirname(__FILE__) . '/abstract_export_renderer.php';
include_once dirname(__FILE__) . '/../utils/file_utils.php';
include_once dirname(__FILE__) . '/../utils/string_utils.php';

abstract class AbstractCsvRenderer extends AbstractExportRenderer
{
    /** @var string */
    private $delimiter = ',';

    abstract protected function getGridPagePart();

    public function RenderPage(Page $Page)
    {
        if ($Page->GetContentEncoding() != null) {
            header('Content-type: application/csv; charset=' . $Page->GetContentEncoding());
        } else {
            header("Content-type: application/csv");
        }

    	$this->DisableCacheControl();

        $options = array(
            'filename' => Path::ReplaceFileNameIllegalCharacters($Page->GetTitle() . ".csv"),
            'delimiter' => $this->delimiter
        );
        $Page->GetCustomExportOptions(
            'csv',
            $this->getCurrentRowData($Page->GetGrid()),
            $options
        );
        $this->delimiter = $options['delimiter'];

        header("Content-Disposition: attachment;Filename=" . $options['filename']);
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
        header("Pragma: public");
        set_time_limit(0);

        $customParams = array();
        $template = $Page->GetCustomTemplate(
            PagePart::ExportLayout,
            PageMode::ExportCsv,
            'export/csv_page.tpl',
            $customParams
        );

        $Grid = $this->Render($Page->GetGrid());
        $this->DisplayTemplate($template,
            array('Page' => $Page),
            array_merge(
                $customParams,
                array(
                    'Grid' => $Grid
                )
            )
        );
    }

    public function RenderGrid(Grid $Grid)
    {
        $HeaderCaptions = array();
        foreach($Grid->GetExportColumns() as $Column) {
            $HeaderCaptions[] = $Column->GetCaption();
        }

        $Rows = array();
        $Grid->GetDataset()->Open();
        while($Grid->GetDataset()->Next()) {
            $rowValues = $Grid->GetDataset()->GetCurrentFieldValues();
            $Row = array();
            foreach($Grid->GetExportColumns() as $Column)
               $Row[] = str_replace('"', '""', $this->RenderViewColumn($Column, $rowValues));
            $Rows[] = $Row;
        }

        $customParams = array();
        $template = $Grid->getPage()->GetCustomTemplate(
            $this->getGridPagePart(),
            PageMode::ExportCsv,
            'export/csv_grid.tpl',
            $customParams
        );

        $this->DisplayTemplate($template,
            array('Grid' => $Grid),
            array_merge($customParams, array(
                'HeaderCaptions' => $HeaderCaptions,
                'Rows' => $Rows,
                'Delimiter' => $this->delimiter
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

    protected function GetCustomRenderedViewColumn(AbstractViewColumn $column, $rowValues)
    {
        $result = '';
        $handled = false;
        $column->GetGrid()->OnCustomRenderExportColumn->Fire(array(
            'csv', $this->GetFriendlyColumnName($column), $column->GetValue(), $rowValues, &$result, &$handled)
        );

        if ($handled) {
            return $result;
        }

        return null;
    }

    protected function HttpHandlersAvailable()
    {
        return false;
    }

    protected function HtmlMarkupAvailable()
    {
        return false;
    }

    protected function InteractionAvailable()
    {
        return false;
    }
}
