<?php

include_once dirname(__FILE__) . '/' . 'abstract_pdf_renderer.php';
include_once dirname(__FILE__) . '/' . '../utils/file_utils.php';


class PdfRecordRenderer extends AbstractPdfRenderer
{
    public function RenderGrid(Grid $Grid) {

        $customParams = array();

        $template = $Grid->GetPage()->GetCustomTemplate(
            PagePart::RecordCard,
            PageMode::ExportPdf,
            'export/pdf_record.tpl',
            $customParams
        );

        $this->DisplayTemplate($template,
            array('Grid' => $Grid->GetExportSingleRowViewData($this, 'pdf')),
            $customParams
        );
    }
}
