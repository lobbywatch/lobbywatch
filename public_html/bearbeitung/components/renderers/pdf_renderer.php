<?php

// require_once 'renderer.php';
// require_once 'components/utils/file_utils.php';

include_once dirname(__FILE__) . '/' . 'renderer.php';
include_once dirname(__FILE__) . '/' . '../utils/file_utils.php';


class PdfRenderer extends Renderer
{
    function RenderPageNavigator($PageNavigator)
    { }

    function RenderDetailPageEdit($page)
    {
        $this->RenderPage($page);
    }

    /**
     * @param Page $Page
     * @return void
     */
    function RenderPage(Page $Page)
    {
        include_once 'components/utils/check_utils.php';
        CheckMbStringExtension();
        CheckIconvExtension();

        include_once 'libs/mpdf/mpdf.php';

        set_time_limit(0);
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache");
        header("Pragma: no-cache");
        
        $html = $this->Render($Page->GetGrid());

        $mpdf = new mPDF('utf-8', 'A4', '8', '', 10, 10, 7, 7, 10, 10);
        $mpdf->charset_in = 'utf-8';


        $stylesheet = FileUtils::ReadAllText('components/css/pdf.css');
        $mpdf->WriteHTML($stylesheet, 1);

        $mpdf->list_indent_first_level = 0;
        $mpdf->WriteHTML($html, 2);
        //echo $html;
        $mpdf->Output('mpdf.pdf', 'I');

        $this->result =  '';
    }


    protected function GetCustomRenderedViewColumn(CustomViewColumn $column, $rowValues)
    {
        $result = '';
        $handled = false;
        $column->GetGrid()->OnCustomRenderExportColumn->Fire(array(
            'pdf', $this->GetFriendlyColumnName($column), $column->GetData(), $rowValues, &$result, &$handled)
        );

        if ($handled)
            return $result;
        else
            return null;
    }


    private function CreateTableHeaderData(Grid $Grid)
    {
        $headCellsData = array();
        $exportColumns = $Grid->GetExportColumns();
        foreach($exportColumns as $Column)
        {
            $headColumnsStyleBuilder = new StyleBuilder();

            if ($Column->GetFixedWidth() != null)
                $headColumnsStyleBuilder->Add('width', $Column->GetFixedWidth());

            array_push(
                $headCellsData,
                array(
                    'Caption' => $Column->GetCaption(),
                    'Style' => $headColumnsStyleBuilder->GetStyleString()
                ));
        }
        return array(
            'Cells' => $headCellsData
        );
    }

    private function GetStylesForColumn(Grid $grid, $rowData)
    {
        $rowCssStyle = '';
        $cellCssStyles = array();
        $grid->OnCustomDrawCell->Fire(array($rowData, &$cellCssStyles, &$rowCssStyle));

        $cellFontColor = array();
        $cellFontSize = array();
        $cellBgColor = array();
        $cellItalicAttr = array();
        $cellBoldAttr = array();

        $grid->OnCustomDrawCell_Simple->Fire(array($rowData, &$cellFontColor, &$cellFontSize, &$cellBgColor, &$cellItalicAttr, &$cellBoldAttr));

        $result = array();
        $fieldNames = array_unique(array_merge(
            array_keys($cellFontColor),
            array_keys($cellFontSize),
            array_keys($cellBgColor),
            array_keys($cellItalicAttr),
            array_keys($cellBoldAttr)));

        $fieldStylesBuilder = new StyleBuilder();
        foreach ($fieldNames as $fieldName)
        {
            $fieldStylesBuilder->Clear();
            if (array_key_exists($fieldName, $cellFontColor))
                $fieldStylesBuilder->Add('color', $cellFontColor[$fieldName]);
            if (array_key_exists($fieldName, $cellFontSize))
                $fieldStylesBuilder->Add('font-size', $cellFontSize[$fieldName]);
            if (array_key_exists($fieldName, $cellBgColor))
                $fieldStylesBuilder->Add('background-color', $cellBgColor[$fieldName]);
            if (array_key_exists($fieldName, $cellItalicAttr))
                $fieldStylesBuilder->Add('font-style',
                    $cellItalicAttr[$fieldName] ? 'italic' : 'normal');
            if (array_key_exists($fieldName, $cellBoldAttr))
            {
                $fieldStylesBuilder->Add('font-weight',
                    $cellBoldAttr[$fieldName] ? 'bold' : 'normal');
            }
            $result[$fieldName] = $fieldStylesBuilder->GetStyleString();
        }

        return array_merge($result, $cellCssStyles);
    }

    function RenderGrid(Grid $Grid)
    {
        /*
        $Rows = array();
        $RowPrimaryKeys = array();
        $AfterRows = array();
        $rowCssStyles = array();
        $rowColumnsChars = array();
        $rowColumnsCssStyles = array();
        $bandHeadColumnsStyles = array();
        $columnsNames = array();

        $exportColumns = $Grid->GetExportColumns();
        foreach($exportColumns as $Column)
        {
            $headColumnsStyleBuilder = new StyleBuilder();

            if ($Column->GetFixedWidth() != null)
                $headColumnsStyleBuilder->Add('width', $Column->GetFixedWidth());

            $headColumnsStyles[] = $headColumnsStyleBuilder->GetStyleString();
            $columnsNames[] = $Column->GetName();
        }

        $Grid->GetDataset()->Open();
        $recordCount = 0;
        while($Grid->GetDataset()->Next())
        {
            $show = true;
            $Grid->BeforeShowRecord->Fire(array(&$show));
            if (!$show)
                continue;

            $Row = array();
            $AfterRowControls = '';

            $rowValues = $Grid->GetDataset()->GetFieldValues();
            $rowCssStyle = '';
            $cellCssStyles = array();

            $Grid->OnCustomDrawCell->Fire(array($rowValues, &$cellCssStyles, &$rowCssStyle));
            $cellCssStyles_Simple = $this->GetStylesForColumn($Grid, $rowValues);
            $cellCssStyles = array_merge($cellCssStyles_Simple, $cellCssStyles);

            $currentRowColumnsCssStyles = array();

            $columnsChars = array();
            for($i = 0; $i < count($bands); $i++)
            {
                $band = $bands[$i];

                foreach($band->GetColumns() as $Column)
                {
                    $columnName = $Grid->GetDataset()->IsLookupField($Column->GetName()) ?
                        $Grid->GetDataset()->IsLookupFieldNameByDisplayFieldName($Column->GetName()) :
                        $Column->GetName();


                    if (array_key_exists($columnName, $cellCssStyles))
                    {
                        $styleBuilder = new StyleBuilder();
                        $styleBuilder->AddStyleString($rowCssStyle);
                        $styleBuilder->AddStyleString($cellCssStyles[$columnName]);
                        $currentRowColumnsCssStyles[] = $styleBuilder->GetStyleString();
                    }
                    else
                        $currentRowColumnsCssStyles[] = $rowCssStyle;

                    if ($Column->GetFixedWidth() != null)
                        $currentRowColumnsCssStyles[count($currentRowColumnsCssStyles) - 1] .=  sprintf('width: %s;', $Column->GetFixedWidth());
                    if (!$Column->GetWordWrap())
                        $currentRowColumnsCssStyles[count($currentRowColumnsCssStyles) - 1] .=  sprintf('white-space: nowrap;', $Column->GetFixedWidth());


                    $columnRenderResult = '';
                    $customRenderColumnHandled = false;
                    $Grid->OnCustomRenderColumn->Fire(array($columnName, $Column->GetData(), $rowValues, &$columnRenderResult, &$customRenderColumnHandled));
                    $columnRenderResult = $customRenderColumnHandled ? $Grid->GetPage()->RenderText($columnRenderResult) : $this->Render($Column);
                    $Row[] = $columnRenderResult;
                    $columnsChars[] = ($Column->IsDataColumn() ? 'data' : 'misc');

                    $afterRow = $Column->GetAfterRowControl();
                    if (isset($afterRow))
                        $AfterRowControls .= $this->Render($afterRow);
                }

                if ($i < (count($bands) - 1))
                    $currentRowColumnsCssStyles[count($currentRowColumnsCssStyles) - 1] .= ($Grid->GetPage()->GetPageDirection() == 'rtl' ? 'border-left: ' : 'border-right: ' ). 'solid 2px' . ' #000000;';
            }
            $recordCount++;
            if ($Grid->GetAllowDeleteSelected())
                $RowPrimaryKeys[] = $Grid->GetDataset()->GetPrimaryKeyValues();
            $Rows[] = $Row;
            $AfterRows[] = $AfterRowControls;
            $rowCssStyles[] = $rowCssStyle;
            $rowColumnsCssStyles[] = $currentRowColumnsCssStyles;
            $rowColumnsChars[] = $columnsChars;
        }

        

        */
        
        $Rows = array();
        $HeaderCaptions = array();
        $Grid->GetDataset()->Open();
        foreach($Grid->GetExportColumns() as $Column)
            $HeaderCaptions[] = $Column->GetCaption();
        while($Grid->GetDataset()->Next())
        {
            $Row = array();
            $rowValues = $Grid->GetDataset()->GetFieldValues();
            $cellStyles = $this->GetStylesForColumn($Grid, $rowValues);

            foreach($Grid->GetExportColumns() as $column)
            {
                $columnName =
                    $Grid->GetDataset()->IsLookupField($column->GetName()) ?
                        $Grid->GetDataset()->IsLookupFieldNameByDisplayFieldName($column->GetName()) :
                        $column->GetName();

                $cell['Value'] = $this->RenderViewColumn($column, $rowValues);
                $cell['Align'] = $column->GetAlign();

                $cellStyle = new StyleBuilder();
                $cellStyle->Add('width', $column->GetFixedWidth());
                if (!$column->GetWordWrap())
                    $cellStyle->Add('white-space', 'nowrap');
                $cellStyle->AddStyleString(ArrayUtils::GetArrayValueDef($cellStyles, $columnName));

                $cell['Style'] = $cellStyle->GetStyleString();

                $Row[] = $cell;
            }
            $Rows[] = $Row;
        }

        $this->DisplayTemplate('export/pdf_grid.tpl',
            array(),
            array(
                'TableHeader' => $this->CreateTableHeaderData($Grid),
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
