<?php

include_once dirname(__FILE__) . '/' . 'abstract_export_renderer.php';
include_once dirname(__FILE__) . '/' . '../utils/file_utils.php';

abstract class AbstractPdfRenderer extends AbstractExportRenderer
{
    /**
     * @param Page $Page
     * @return void
     */
    public function RenderPage(Page $Page)
    {
        include_once dirname(__FILE__) . '/' . '../utils/check_utils.php';
        CheckMbStringExtension();
        CheckIconvExtension();

        include_once dirname(__FILE__) . '/' . '../../libs/mpdf/mpdf_common.php';

        set_time_limit(0);
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache");
        header("Pragma: no-cache");

        $customParams = array();
        $template = $Page->GetCustomTemplate(
            PagePart::ExportLayout,
            PageMode::ExportPdf,
            'export/pdf_page.tpl',
            $customParams
        );

        $Grid = $this->Render($Page->GetGrid());
        $this->DisplayTemplate($template,
            array('Page' => $Page),
            array_merge($customParams, array('Grid' => $Grid))
        );

        $html = $this->result;
        $options = array(
            'size' => 'A4',
            'orientation' => 'P',
            'filename' => Path::ReplaceFileNameIllegalCharacters($Page->GetTitle() . ".pdf"),
            'margin-left' => 10,
            'margin-right' => 10,
            'margin-top' => 10,
            'margin-bottom' => 10,
            'margin-header' => 5,
            'margin-footer' => 5
        );
        $Page->GetCustomExportOptions(
            'pdf',
            $this->getCurrentRowData($Page->GetGrid()),
            $options
        );

        $orientationString = $options['orientation'] === 'L' ? '-L' : '';

        $configParams = array(
            'mode' => 'utf-8',
            'format' => $options['size'] . $orientationString,
            'default_font_size' => 8,
            'default_font' => '',
            'margin_left' => $options['margin-left'],
            'margin_right' => $options['margin-right'],
            'margin_top' => $options['margin-top'],
            'margin_bottom' => $options['margin-bottom'],
            'margin_header' => $options['margin-header'],
            'margin_footer' => $options['margin-footer']
        );

        $mpdf = createMPDF($configParams);
        $mpdf->charset_in = $Page->GetContentEncoding();

        $stylesheet = FileUtils::ReadAllText('components/assets/css/pdf.css');
        $userCss = 'components/assets/css/user_pdf.css';
        if (FileUtils::FileExists($userCss)) {
            $stylesheet .= FileUtils::ReadAllText($userCss);
        }

        if (array_key_exists('header', $options)) {
            $mpdf->SetHeader($options['header']);
        } elseif (array_key_exists('html-header', $options)) {
            $mpdf->SetHTMLHeader($options['html-header']);
        }

        if (array_key_exists('footer', $options)) {
            $mpdf->SetFooter($options['footer']);
        } elseif (array_key_exists('html-footer', $options)) {
            $mpdf->SetHTMLFooter($options['html-footer']);
        }

        $mpdf->WriteHTML($stylesheet, 1);

        $mpdf->list_indent_first_level = 0;
        $mpdf->WriteHTML($html, 2);

        $mpdf->Output($options['filename'], 'I');

        $this->result =  '';
    }

    public function RenderPageNavigator($PageNavigator)
    {
    }

    public function RenderDetailPage(DetailPage $page)
    {
        $this->RenderPage($page);
    }

    protected function GetCustomRenderedViewColumn(AbstractViewColumn $column, $rowValues)
    {
        $result = '';
        $handled = false;
        $column->GetGrid()->OnCustomRenderExportColumn->Fire(array(
            'pdf', $this->GetFriendlyColumnName($column), $column->GetValue(), $rowValues, &$result, &$handled)
        );

        if ($handled)
            return $result;
        else
            return null;
    }

    protected function GetStylesForColumn(Grid $grid, $rowData)
    {
        $rowCssStyle = '';
        $cellCssStyles = array();
        $rowClasses = '';
        $cellClasses = array();
        $grid->OnExtendedCustomDrawRow->Fire(array($rowData, &$cellCssStyles, &$rowCssStyle, &$rowClasses, &$cellClasses));

        $cellFontColor = array();
        $cellFontSize = array();
        $cellBgColor = array();
        $cellItalicAttr = array();
        $cellBoldAttr = array();

        $grid->OnCustomDrawRow->Fire(array($rowData, &$cellFontColor, &$cellFontSize, &$cellBgColor, &$cellItalicAttr, &$cellBoldAttr));

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

    protected function ShowHtmlNullValue()  {
        return true;
    }

    /** @inheritdoc */
    public function RenderImageViewColumn(ImageViewColumn $column)
    {
        if (is_null($column->GetValue())) {
            $this->result = $this->GetNullValuePresentation($column);
        } else {
            $this->result =
                sprintf(
                    '<img src="%s" %s%s>',
                    $column->GetImageLink(),
                    $column->generateImageSizeString(),
                    $this->getColumnStyle($column)
                );
        }
    }
}
