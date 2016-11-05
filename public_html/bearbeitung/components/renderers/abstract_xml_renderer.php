<?php

include_once dirname(__FILE__) . '/' . 'abstract_export_renderer.php';

abstract class AbstractXmlRenderer extends AbstractExportRenderer
{
    abstract protected function getGridPagePart();

    public function RenderPage(Page $Page)
    {
        $options = array(
            'filename' => Path::ReplaceFileNameIllegalCharacters($Page->GetTitle() . ".xml"),
        );
        $Page->GetCustomExportOptions(
            'xml',
            $this->getCurrentRowData($Page->GetGrid()),
            $options
        );

        header("Content-type: text/xml");
        $this->DisableCacheControl();
        header("Content-Disposition: attachment;Filename=" . $options['filename']);
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
        header("Pragma: public");
        set_time_limit(0);

        $customParams = array();
        $template = $Page->GetCustomTemplate(
            PagePart::ExportLayout,
            PageMode::ExportXml,
            'export/xml_page.tpl',
            $customParams
        );

        $Grid = $this->Render($Page->GetGrid());
        $this->DisplayTemplate($template,
            array('Page' => $Page),
            array_merge($customParams, array('Grid' => $Grid))
        );
    }

    private function PrepareColumnCaptionForXml($caption, $encoding)
    {
        return StringUtils::EscapeString(str_replace(' ', '', $caption), $encoding);
    }

    protected function GetCustomRenderedViewColumn(AbstractViewColumn $column, $rowValues)
    {
        $result = '';
        $handled = false;
        $column->GetGrid()->OnCustomRenderExportColumn->Fire(array(
            'xml', $this->GetFriendlyColumnName($column), $column->GetValue(), $rowValues, &$result, &$handled)
        );

        if ($handled) {
            return $result;
        }

        return null;
    }

    public function RenderGrid(Grid $Grid)
    {
        $Rows = array();
        $Grid->GetDataset()->Open();

        while($Grid->GetDataset()->Next()) {
            $rowValues = $Grid->GetDataset()->GetCurrentFieldValues();
            $Row = array();
            foreach($Grid->GetExportColumns() as $column) {
                $xmlCaption = $this->PrepareColumnCaptionForXml(
                    $column->GetCaption(),
                    $column->GetGrid()->GetPage()->GetContentEncoding()
                );

                $Row[$xmlCaption] = $this->RenderViewColumn($column, $rowValues);
            }
            $Rows[] = $Row;
        }

        $customParams = array();
        $template = $Grid->getPage()->GetCustomTemplate(
            $this->getGridPagePart(),
            PageMode::ExportXml,
            'export/xml_grid.tpl',
            $customParams
        );

        $this->DisplayTemplate($template,
            array('Grid' => $Grid),
            array_merge($customParams, array(
                'Rows' => $Rows,
                'TableName' => $Grid->GetPage()->GetTitle()
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
        return false;
    }

    protected function InteractionAvailable()
    {
        return false;
    }
}
