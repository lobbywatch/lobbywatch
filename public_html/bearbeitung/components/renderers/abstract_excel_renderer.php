<?php

include_once dirname(__FILE__) . '/' . 'abstract_export_renderer.php';

abstract class AbstractExcelRenderer extends AbstractExportRenderer
{
    abstract protected function getGridPagePart();

    protected function GetCustomRenderedViewColumn(AbstractViewColumn $column, $rowValues)
    {
        $result = '';
        $handled = false;
        $column->GetGrid()->OnCustomRenderExportColumn->Fire(array(
            'excel',
            $this->GetFriendlyColumnName($column),
            $column->GetValue(),
            $rowValues,
            &$result,
            &$handled
        ));

        if ($handled) {
            return $result;
        }

        return null;
    }

    public function RenderPage(Page $page)
    {
        $options = array(
            'engine' => 'template',
            'filename' => Path::ReplaceFileNameIllegalCharacters($page->GetTitle() . '.xls'),
        );

        $page->GetCustomExportOptions(
            'xls',
            $this->getCurrentRowData($page->GetGrid()),
            $options
        );

        ob_end_clean();
        set_time_limit(0);

        if (!is_null($page->GetContentEncoding())) {
            header('Content-type: application/vnd.ms-excel; charset=' . $page->GetContentEncoding());
        } else {
            header('Content-type: application/vnd.ms-excel;');
        }

        header('Content-Disposition: attachment;filename="' . $options['filename'] . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1'); // IE9
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate');
        header ('Pragma: public');

        if ($options['engine'] === 'phpexcel') {
            $this->RenderPagePhpExcel($page);
        } else {
            $this->RenderPageTemplate($page);
        }
    }

    public function RenderGrid(Grid $Grid)
    {
        $Rows = array();
        $HeaderCaptions = array();
        $Grid->GetDataset()->Open();

        foreach($Grid->GetExportColumns() as $Column) {
            $HeaderCaptions[] = $this->PrepareForExcel(
                $Column->GetCaption(),
                $Column->GetGrid()->GetPage()->GetContentEncoding()
            );
        }

        while($Grid->GetDataset()->Next()) {
            $rowValues = $Grid->GetDataset()->GetCurrentFieldValues();
            $Row = array();
            foreach($Grid->GetExportColumns() as $Column) {
                $cell['Value'] = $this->PrepareForExcel(
                    $this->RenderViewColumn($Column, $rowValues),
                    $Column->GetGrid()->GetPage()->GetContentEncoding()
                );

                $cell['Align'] = $Column->GetAlign();
                $Row[] = $cell;
            }

            $Rows[] = $Row;
        }

        $customParams = array();
        $template = $Grid->getPage()->GetCustomTemplate(
            $this->getGridPagePart(),
            PageMode::ExportExcel,
            'export/excel_grid.tpl',
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

    private function PrepareForExcel($str, $encoding)
    {
        $ret = StringUtils::EscapeString($str, $encoding);
        if (substr($ret,0,1) == "=")
            $ret = "&#61;".substr($ret,1);
        return $ret;
    }

    public function RenderPageNavigator($PageNavigator)
    {
    }

    private function RenderPageTemplate(Page $page)
    {

        $customParams = array();
        $template = $page->GetCustomTemplate(
            PagePart::ExportLayout,
            PageMode::ExportExcel,
            'export/excel_page.tpl',
            $customParams
        );

        $grid = $this->Render($page->GetGrid());
        $this->DisplayTemplate($template,
            array('Page' => $page),
            array_merge($customParams, array('Grid' => $grid))
        );
    }

    private function RenderPagePhpExcel(Page $page)
    {
        require_once dirname(__FILE__) . '/../../libs/phpoffice/PHPExcel.php';

        $objPHPExcel = new PHPExcel();
        $sheet = $objPHPExcel->setActiveSheetIndex(0);

        $grid = $page->getGrid();
        $grid->GetDataset()->Open();

        foreach($grid->GetExportColumns() as $i => $column) {
            $sheet->setCellValueByColumnAndRow($i, 1, $this->PrepareForExcel(
                $column->GetCaption(),
                $column->GetGrid()->GetPage()->GetContentEncoding()
            ));
        }

        $currentRow = 2;
        while ($grid->GetDataset()->Next()) {
            $rowValues = $grid->GetDataset()->GetCurrentFieldValues();
            foreach($grid->GetExportColumns() as $i => $column) {
                $sheet->setCellValueByColumnAndRow($i, $currentRow, $this->PrepareForExcel(
                    $this->RenderViewColumn($column, $rowValues),
                    $column->GetGrid()->GetPage()->GetContentEncoding()
                ));
            }

            $currentRow++;
        }

        $totals = $grid->getTotalsViewData($grid->GetExportColumns());
        if (!is_null($totals)) {
            foreach($totals as $i => $total) {
                $sheet->setCellValueByColumnAndRow($i, $currentRow, $this->PrepareForExcel(
                    $total['Value'],
                    $page->GetContentEncoding()
                ));
            }
        }

        $objPHPExcel->setActiveSheetIndex(0);

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');

        exit;
    }

    public function RenderDetailPage(DetailPage $DetailPage)
    {
        $this->RenderPage($DetailPage);
    }

    // Column rendering
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
