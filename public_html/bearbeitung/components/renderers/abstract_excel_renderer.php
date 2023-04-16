<?php

include_once dirname(__FILE__) . '/' . 'abstract_export_renderer.php';
include_once dirname(__FILE__) . '/' . '../utils/string_utils.php';

if (version_compare(PHP_VERSION, '7.2', '<')) {
    include_once dirname(__FILE__) . '/' . 'phpexcel_common.php';
} else {
    include_once dirname(__FILE__) . '/' . 'phpspreadsheet_common.php';
}

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
            'engine' => 'phpexcel',
            'file-format' => 'xlsx',
            'filename' => Path::ReplaceFileNameIllegalCharacters($page->GetTitle() . '.xlsx')
        );

        $page->GetCustomExportOptions(
            'xls',
            $this->getCurrentRowData($page->GetGrid()),
            $options
        );

        if ($options['file-format'] == 'xls') {
            if (StringUtils::EndsBy($options['filename'], '.xlsx')) {
                $options['filename'] = rtrim($options['filename'], 'x');
            }
        }

        if ($options['file-format'] == 'xlsx') {
            if (StringUtils::EndsBy($options['filename'], '.xls')) {
                $options['filename'] = $options['filename'] . 'x';
            }
        }

        ob_end_clean();
        set_time_limit(0);

        $contentType = $options['file-format'] == 'xlsx' ? 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' : 'application/vnd.ms-excel';
        if (!is_null($page->GetContentEncoding())) {
            $contentType .= '; charset=' . $page->GetContentEncoding();
        }
        header('Content-type: ' . $contentType);

        header('Content-Disposition: attachment;filename="' . $options['filename'] . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1'); // IE9
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate');
        header ('Pragma: public');

        if ($options['engine'] === 'phpexcel') {
            $this->RenderPagePhpExcel($page, $options['file-format']);
        } else {
            $this->RenderPageTemplate($page, $options['file-format']);
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

    private function RenderPageTemplate(Page $page, $fileFormat)
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

        $phpExcelObject = CreatePHPExcelObjectBasedOnHTML($this->result);
        $phpExcelIOWriter = CreatePHPExcelIOWriter($phpExcelObject, $fileFormat);
        $phpExcelIOWriter->save('php://output');

        exit;
    }

    /**
     * @param Page $page
     * @param string $fileFormat
     */
    private function RenderPagePhpExcel($page, $fileFormat)
    {
        if (version_compare(PHP_VERSION, '7.2', '<')) {
            $columnAIndex = 0;
        } else {
            $columnAIndex = 1;
        }
        $phpExcelObject = CreatePHPExcelObject();
        $sheet = $phpExcelObject->setActiveSheetIndex(0);

        $grid = $page->getGrid();
        $grid->GetDataset()->Open();

        foreach($grid->GetExportColumns() as $i => $column) {
            $sheet->setCellValueByColumnAndRow($i + $columnAIndex, 1, $column->GetCaption());
            $sheet->getColumnDimensionByColumn($i + $columnAIndex)->setAutoSize(true);
        }

        $currentRow = 2;
        while ($grid->GetDataset()->Next()) {
            $rowValues = $grid->GetDataset()->GetCurrentFieldValues();
            foreach($grid->GetExportColumns() as $i => $column) {
                $sheet->setCellValueByColumnAndRow($i + $columnAIndex, $currentRow, $this->RenderViewColumn($column, $rowValues));
            }

            $currentRow++;
        }

        $totals = $grid->getTotalsViewData($grid->GetExportColumns());
        if (!is_null($totals)) {
            foreach($totals as $i => $total) {
                $sheet->setCellValueByColumnAndRow($i + $columnAIndex, $currentRow, $total['Value']);
            }
        }

        $phpExcelIOWriter = CreatePHPExcelIOWriter($phpExcelObject, $fileFormat);
        $phpExcelIOWriter->save('php://output');

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
