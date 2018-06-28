<?php

include_once dirname(__FILE__) . '/' . 'abstract_pdf_renderer.php';

class PdfListRenderer extends AbstractPdfRenderer
{
    public function RenderGrid(Grid $Grid)
    {
        $Rows = array();
        $Grid->GetDataset()->Open();

        while($Grid->GetDataset()->Next()) {
            $Row = array();
            $rowValues = $Grid->GetDataset()->GetFieldValues();
            $cellStyles = $this->GetStylesForColumn($Grid, $rowValues);

            foreach($Grid->GetExportColumns() as $column) {
                $columnName = $Grid->GetColumnName($column);

                $cell['Value'] = $this->RenderViewColumn($column, $rowValues);
                $cell['Align'] = $column->GetAlign();

                $cellStyle = new StyleBuilder();
                $cellStyle->Add('width', $column->GetFixedWidth());
                if (!$column->GetWordWrap())
                    $cellStyle->Add('white-space', 'nowrap');
                $cellStyle->AddStyleString(ArrayUtils::GetArrayValueDef($cellStyles, $columnName));

                $cell['Style'] = $cellStyle->GetStyleString();

                $Row[$columnName] = $cell;
            }
            $Rows[] = $Row;
        }

        $customParams = array();
        $template = $Grid->getPage()->GetCustomTemplate(
            PagePart::Grid,
            PageMode::ExportPdf,
            'export/pdf_grid.tpl',
            $customParams
        );

        $this->DisplayTemplate($template,
            array(),
            array_merge($customParams, array(
                'TableHeader' => $this->CreateTableHeaderData($Grid),
                'Rows' => $Rows,
                'Totals' => $Grid->getTotalsViewData($Grid->GetExportColumns())
            )));
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
                    'Name' => $Grid->GetColumnName($Column),
                    'Caption' => $Column->GetCaption(),
                    'Style' => $headColumnsStyleBuilder->GetStyleString()
                ));
        }
        return array(
            'Cells' => $headCellsData
        );
    }

    protected function getCurrentRowData(Grid $grid)
    {
        return null;
    }
}
