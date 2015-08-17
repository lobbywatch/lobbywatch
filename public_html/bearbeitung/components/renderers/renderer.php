<?php

// require_once 'components/grid/grid.php';
// require_once 'components/utils/file_utils.php';
// require_once 'components/utils/html_utils.php';

include_once dirname(__FILE__) . '/' . '../grid/grid.php';
include_once dirname(__FILE__) . '/' . '../utils/file_utils.php';
include_once dirname(__FILE__) . '/' . '../utils/html_utils.php';

abstract class EditorsRenderer
{
    abstract public function RenderTimeEdit(TimeEdit $editor);
    abstract public function RenderMaskedEdit(MaskedEdit $editor);
    abstract public function RenderMultiLevelComboBoxEditor(MultiLevelComboBoxEditor $editor);
    abstract public function RenderAutocompleteComboBox(AutocomleteComboBox $comboBox);
    abstract public function RenderCheckBox(CheckBox $checkBox);
    abstract public function RenderColorEdit(ColorEdit $colorEdit);
    abstract public function RenderCheckBoxGroup(CheckBoxGroup $checkBoxGroup);
    abstract public function RenderMultiValueSelect(MultiValueSelect $multiValueSelect);
    abstract public function RenderComboBox(ComboBox $comboBox);
    abstract public function RenderDateTimeEdit(DateTimeEdit $dateTimeEdit);
    abstract public function RenderHtmlWysiwygEditor(HtmlWysiwygEditor $editor);
    abstract public function RenderImageUploader(ImageUploader $imageUploader);
    abstract public function RenderRadioEdit(RadioEdit $radioEdit);
    abstract public function RenderSpinEdit(SpinEdit $spinEdit);
    abstract public function RenderRangeEdit(RangeEdit $rangeEdit);
    abstract public function RenderTextEdit(TextEdit $textEdit);
    abstract public function RenderTextAreaEdit(TextAreaEdit $textArea);
}

abstract class Renderer extends EditorsRenderer
{
    protected $result;
    /** @var Captions */
    private $captions;
    private $renderScripts = true;
    private $renderText = true;
    private $additionalParams = null;

    protected function DisableCacheControl() {
        // Fixes the IE bug
        // see http://www.alagad.com/blog/post.cfm/error-internet-explorer-cannot-download-filename-from-webserver
        header('Pragma: public');
        header('Cache-Control: max-age=0');
    }

    /**
     * @param Page $page
     */
    protected function SetHTTPContentTypeByPage($page)  {
        $headerString = 'Content-Type: text/html';
        if ($page->GetContentEncoding() != null)
            AddStr($headerString, 'charset=' . $page->GetContentEncoding(), ';');
        header($headerString);
    }

    protected function Captions() {
        return $this->captions;
    }

    /**
     * @return Captions
     */
    public function GetCaptions() {
        return $this->captions;
    }

    private function CreateSmatryObject()  {
        $result = new Smarty();
        $result->template_dir = 'components/templates';

        return $result;
    }

    public function __construct($captions) {
        $this->captions = $captions;
    }

    #region Rendering

    public function DisplayTemplate($TemplateName, $InputObjects, $InputValues) {
        $smarty = $this->CreateSmatryObject();
        foreach($InputObjects as $ObjectName => &$Object)
            $smarty->assign_by_ref($ObjectName, $Object);
        $smarty->assign_by_ref('Renderer', $this);
        $smarty->assign_by_ref('Captions', $this->captions);
        $smarty->assign('RenderScripts', $this->renderScripts);
        $smarty->assign('RenderText', $this->renderText);
        
        if (isset($this->additionalParams))
        {
            foreach($this->additionalParams as $ValueName => $Value)
            {
                $smarty->assign($ValueName, $Value);
            }
        }

        foreach($InputValues as $ValueName => $Value)
            $smarty->assign($ValueName, $Value);

        $this->result = $smarty->fetch($TemplateName);
    }

    public function Render($Object, $renderScripts = true, $renderText = true, $additionalParams = null) {
        $oldRenderScripts = $this->renderScripts;
        $oldRenderText = $this->renderText;
        $oldAdditionalParams = $this->additionalParams;

        $this->renderScripts = $renderScripts;
        $this->renderText = $renderText;
        $this->additionalParams = array();
        if (isset($additionalParams))
            $this->additionalParams = $additionalParams;

        if (defined('SHOW_VARIABLES') && ($Object instanceof IVariableContainer)) {
            $this->additionalParams['Variables'] = $this->RenderVariableContainer($Object);
        }



        $Object->Accept($this);

        $this->renderScripts = $oldRenderScripts;
        $this->renderText = $oldRenderText;
        $this->additionalParams = $oldAdditionalParams;
        return $this->result;
    }

    public function RenderDef($object, $default = '', $additionalParams = null) {
        if (isset($object))
            return $this->Render($object, true, true, $additionalParams);
        else
            return $default;
    }

    #endregion

    #region Editors

    private function RenderEditor(CustomEditor $editor, $nameInTemplate, $templateFile, $additionalParams = array()) {
        $validatorsInfo = array();
        $validatorsInfo['InputAttributes'] = $editor->GetValidationAttributes();
        $validatorsInfo['InputAttributes'] .= StringUtils::Format(
            ' data-legacy-field-name="%s" data-pgui-legacy-validate="true"',
            $editor->GetFieldName()
        );

        $this->DisplayTemplate(
            Path::Combine('editors', $templateFile),
            array($nameInTemplate => $editor),
            array_merge(
                array(
                    'Validators' => $validatorsInfo
                ),
                $additionalParams
            ));
    }

    public final function RenderTimeEdit(TimeEdit $editor)
    {
        $this->RenderEditor($editor, 'TimeEdit', 'time_edit.tpl');
    }

    public final function RenderMaskedEdit(MaskedEdit $editor)
    {
        $this->RenderEditor($editor, 'MaskedEdit', 'masked_edit.tpl');
    }

    public final function RenderMultiLevelComboBoxEditor(MultiLevelComboBoxEditor $editor)
    {
        $this->RenderEditor($editor, 'MultilevelEditor', 'multilevel_selection.tpl');
    }    

    public final function RenderAutocompleteComboBox(AutocomleteComboBox $comboBox) 
    {
        $this->RenderEditor($comboBox, 'AutocompleteComboBox', 'autocomplete_combo_box.tpl');
    }

    public final function RenderCheckBox(CheckBox $checkBox) 
    {
        $this->RenderEditor($checkBox, 'CheckBox', 'check_box.tpl');
    }

    public final function RenderColorEdit(ColorEdit $colorEdit)
    {
        $this->RenderEditor($colorEdit, 'ColorEdit', 'color_edit.tpl');
    }

    public final function RenderCheckBoxGroup(CheckBoxGroup $checkBoxGroup)
    {
        $this->RenderEditor($checkBoxGroup, 'CheckBoxGroup', 'check_box_group.tpl');
    }

    public final function RenderMultiValueSelect(MultiValueSelect $multiValueSelect)
    {
        $this->RenderEditor($multiValueSelect, 'MultiValueSelect', 'multivalue_select.tpl');
    }

    public final function RenderComboBox(ComboBox $comboBox) 
    {
        $this->RenderEditor($comboBox, 'ComboBox', 'combo_box.tpl');
    }

    public final function RenderDateTimeEdit(DateTimeEdit $dateTimeEdit) 
    {
        $this->RenderEditor($dateTimeEdit, 'DateTimeEdit', 'datetime_edit.tpl');
    }

    public final function RenderHtmlWysiwygEditor(HtmlWysiwygEditor $editor)
    {
        $this->RenderEditor($editor, 'HTMLWysiwygEditor', 'html_wysiwyg_editor.tpl');
    }

    protected function ForceHideImageUploaderImage()
    {
        return false;
    }

    public final function RenderImageUploader(ImageUploader $imageUploader)
    {
        $this->RenderEditor($imageUploader, 'Uploader', 'image_uploader.tpl', 
            array('HideImage' => $this->ForceHideImageUploaderImage()));
    }

    public final function RenderRadioEdit(RadioEdit $radioEdit) 
    {   
        $this->RenderEditor($radioEdit, 'RadioEdit', 'radio_edit.tpl');
    }

    public final function RenderSpinEdit(SpinEdit $spinEdit) 
    {
        $this->RenderEditor($spinEdit, 'SpinEdit', 'spin_edit.tpl');
    }

    public final function RenderRangeEdit(RangeEdit $rangeEdit)
    {
        $this->RenderEditor($rangeEdit, 'RangeEdit', 'range_edit.tpl');
    }

    public final function RenderTextEdit(TextEdit $textEdit) 
    {
        $this->RenderEditor($textEdit, 'TextEdit', 'text_edit.tpl');
    }

    public final function RenderTextAreaEdit(TextAreaEdit $textArea) 
    {
        $this->RenderEditor($textArea, 'TextArea', 'textarea.tpl');
    }

    #endregion

    #region HTML Components
    
    public function RenderComponent($Component)  {
        $this->result = '';
    }


    /**
     * @param TextBox $textBox
     */
    public function RenderTextBox($textBox)  {
        $this->result = $textBox->GetCaption();
    }

    public function RenderImage($Image)  {
        $this->DisplayTemplate('image.tpl',
            array('Image' => $Image),
            array());
    }

    /**
     * @param CustomHtmlControl $control
     */
    public function RenderCustomHtmlControl($control)  {
        $this->result = $control->GetHtml();
    }

    /**
     * @param Hyperlink $hyperLink
     */
    public function RenderHyperLink($hyperLink)  {
        $this->result = sprintf('<a href="%s">%s</a>%s', $hyperLink->GetLink(), $hyperLink->GetInnerText(), $hyperLink->GetAfterLinkText());
    }

    public function RenderHintedTextBox($textBox)  {
        $this->DisplayTemplate('hinted_text_box.tpl',
            array('TextBox' => $textBox),
            array());
    }

    #endregion

    #region Variables

    public function GetPageVariables(Page $page) {
        if (defined('SHOW_VARIABLES'))
        {
            $this->RenderVariableContainer(
                $page->GetColumnVariableContainer()
                );
            $variables = $this->result;
        }
        else
        {
            $variables = '';
        }
        return $variables;
    }

    public function RenderVariableContainer(IVariableContainer $variableContainer) {
        $values = array();
        $variableContainer->FillVariablesValues($values);
        $this->DisplayTemplate('variables_container.tpl',
            array(),
            array('Variables' => $values)
            );
    }

    #endregion
    
    #region Columns

    private function GetNullValuePresentation($column)  {
        if ($this->ShowHtmlNullValue())
            return StringUtils::Format('<em class="pgui-null-value">%s</em>', $this->GetCaptions()->GetMessageString('NullAsString')) ;
        else
            return '';
    }

    /**
     * @param CustomViewColumn $column
     * @return void
     */
    public final function RenderCustomViewColumn(CustomViewColumn $column) {
        $this->result = $column->GetValue();
    }

    protected function GetFriendlyColumnName(CustomViewColumn  $column) {
        return $column->GetGrid()->GetDataset()->IsLookupField($column->GetName()) ?
            $column->GetGrid()->GetDataset()->IsLookupFieldNameByDisplayFieldName($column->GetName()) :
            $column->GetName();
    }

    /**
     * @param CustomViewColumn $column
     * @param array $rowValues
     * @return string
     */
    protected function GetCustomRenderedViewColumn(CustomViewColumn $column, $rowValues) {
        return null;
    }

    /**
     * @param \CustomViewColumn $column
     * @param array $rowValues
     * @return string
     */
    public final function RenderViewColumn(CustomViewColumn $column, $rowValues)
    {
        $customValue = $this->GetCustomRenderedViewColumn($column, $rowValues);
        if (isset($customValue))
            return $column->GetGrid()->GetPage()->RenderText($customValue);
        else
            return $this->Render($column);
    }

    public final function RenderCustomDatasetFieldViewColumn(CustomDatasetFieldViewColumn $column)
    {
        $value = $column->GetValue();
        if (!isset($value)) 
            $this->result = $this->GetNullValuePresentation($column);
        else
            $this->result = $value;
    }

    /**
     * @param TextViewColumn $column
     */
    public function RenderTextViewColumn($column)
    {
        $value = $column->GetValue();
        $dataset = $column->GetDataset();

        $column->BeforeColumnRender->Fire(array(&$value, &$dataset));

        if (!isset($value))
        {
            $this->result = $this->GetNullValuePresentation($column);
        }
        else 
        {
            if ($column->GetEscapeHTMLSpecialChars())
                $value = htmlspecialchars($value);

            $columnMaxLength = $column->GetMaxLength();

            if ($this->HttpHandlersAvailable() && 
                $this->ChildPagesAvailable() && 
                isset($columnMaxLength) && 
                isset($value) && 
                StringUtils::StringLength($value, $column->GetGrid()->GetPage()->GetContentEncoding()) > $columnMaxLength)
            {
                $originalValue = $value;
                if ($this->HtmlMarkupAvailable() && $column->GetReplaceLFByBR())
                    $originalValue = str_replace("\n", "<br/>", $originalValue);

                $value = StringUtils::SubString($value, 0, $columnMaxLength, $column->GetGrid()->GetPage()->GetContentEncoding());

                $value .= 
                    '... <span class="more_hint"><a href="' . $column->GetMoreLink() . '" '.
                    'onClick="javascript: pwin = window.open(\'\',null,\'height=300,width=400,status=yes,resizable=yes,toolbar=no,menubar=no,location=no,left=150,top=200,scrollbars=yes\'); pwin.location=\''.
                    $column->GetMoreLink() . '\'; return false;">' . $this->captions->GetMessageString('more') .
                    '</a>';
                $value .= 
                    '<div class="box_hidden">' . $originalValue . '</div></span>';
            }

            if ($this->HtmlMarkupAvailable() && $column->GetReplaceLFByBR())
                $value = str_replace("\n", "<br/>", $value);

            $this->result = $value;
        }
    }


    /**
     * @param CheckBoxFormatValueViewColumnDecorator $column
     */
    public function RenderCheckBoxViewColumn($column)
    {
        $value = $column->GetInnerField()->GetData();

        if (!isset($value))
            $this->result = $this->Render($column->GetInnerField());
        else if (empty($value)) 
        {
            if ($this->HtmlMarkupAvailable())
                $this->result = $column->GetFalseValue();
            else
                $this->result = 'false';
        }
        else 
        {
            if ($this->HtmlMarkupAvailable())
                $this->result = $column->GetTrueValue();
            else
                $this->result = 'true';

        }
    }

    /**
     * @param DivTagViewColumnDecorator $column
     */
    public function RenderDivTagViewColumnDecorator($column)
    {
        if ($this->HtmlMarkupAvailable()) 
        {
            $styleBuilder = new StyleBuilder();

            if (isset($column->Bold))
                $styleBuilder->Add('font-weight', ($column->Bold ? 'bold' : 'normal'));
            if (isset($column->Italic))
                $styleBuilder->Add('font-style', ($column->Italic ? 'italic' : 'normal'));

            $this->result = '<div '. (!$styleBuilder->IsEmpty() ? ('style="' . $styleBuilder->GetStyleString() . '"') : '') .
                (isset($column->Align) ? ' align="' . $column->Align . '" ' : '') .
                (isset($column->CustomAttributes) ? $column->CustomAttributes . ' ' : '') . '>'.
                $this->Render($column->GetInnerField()) .
                '</div>';
        }
        else 
        {
            $this->result = $this->Render($column->GetInnerField());
        }
    }

    /**
     * @param ExtendedHyperLinkColumnDecorator $columnDecorator
     */
    public function RenderExtendedHyperLinkColumnDecorator($columnDecorator)
    {
        if ($columnDecorator->GetData() == null)
        {
            $this->result = $this->GetNullValuePresentation($columnDecorator);
        }
        else
        {
            if ($this->HtmlMarkupAvailable())
            {
                $this->result = sprintf('<a href="%s" target="%s">%s</a>',
                    $columnDecorator->GetLink(),
                    $columnDecorator->GetTarget(),
                    $this->Render($columnDecorator->GetInnerField())
                    );
            }
            else
                $this->result = $this->Render($columnDecorator->GetInnerField());
        }
    }

    /**
     * @param DownloadDataColumn $column
     */
    public function RenderDownloadDataColumn($column)
    {
        if ($column->GetData() == null) 
        {
            $this->result = $this->GetNullValuePresentation($column);
        }
        else 
        {
            if ($this->HtmlMarkupAvailable() && $this->HttpHandlersAvailable())
                $this->result =
                    '<i class="pg-icon-download"></i>&nbsp;' .
                    '<a target="_blank" title="download" href="' . $column->GetDownloadLink() . '">' . $column->GetLinkInnerHtml() . '</a>';
            else
                $this->result = $this->Captions()->GetMessageString('BinaryDataCanNotBeExportedToXls');
        }
    }

    /**
     * @param ImageViewColumn $column
     */
    public function RenderImageViewColumn($column)
    {
        if ($column->GetData() == null) 
        {
            $this->result = $this->GetNullValuePresentation($column);
        }
        else 
        {
            if ($this->HtmlMarkupAvailable() && $this->HttpHandlersAvailable()) 
            {
                if($column->GetEnablePictureZoom())
                    $this->result = sprintf(
                        '<a class="image gallery-item" href="%s" title="%s"><img data-image-column="true" src="%s" alt="%s"></a>',
                        $column->GetFullImageLink(),
                        $column->GetImageHint(),
                        $column->GetImageLink(),
                        $column->GetImageHint());
                else
                    $this->result = sprintf(
                        '<img data-image-column="true" src="%s" alt="%s">',
                        $column->GetImageLink(),
                        $column->GetImageHint());
            }
            else 
            {
                $this->result = $this->Captions()->GetMessageString('BinaryDataCanNotBeExportedToXls');
            }
        }
    }

    /**
     * @param DetailColumn $detailColumn
     */
    public function RenderDetailColumn($detailColumn)
    {
        $this->result =
            '<a class="page_link" onclick="expand(' . $detailColumn->GetDataset()->GetCurrentRowIndex() .
            ' , this);" href="' . $detailColumn->GetLink() . '">+</a>&nbsp;' .
            '<a class="page_link" href="' . $detailColumn->GetSeparateViewLink() . '">view</a>';
    }

    #endregion

    #region Pages

    /**
     * @param PageNavigator $PageNavigator
     */
    public function RenderPageNavigator($PageNavigator) { }

    public abstract function RenderPage(Page $Page);

    /**
     * @param CustomErrorPage $errorPage
     */
    public function RenderCustomErrorPage($errorPage)  {
        $this->DisplayTemplate('security_error_page.tpl',
            array(
                'Page' => $errorPage
            ),
            array(
                'JavaScriptMain' => '',
                'Authentication' => $errorPage->GetAuthenticationViewData(),
                'App' => $errorPage->GetViewData(),

                'Message' => $errorPage->GetMessage(),
                'Description' => $errorPage->GetDescription()
            )
        );
    }

    public function RenderDetailPage(DetailPage $detailPage)  {
        $this->SetHTTPContentTypeByPage($detailPage);

        $customParams = array();
        $layoutTemplate = $detailPage->GetCustomTemplate(PagePart::Layout, PageMode::ViewAll, 'common/layout.tpl',
            $customParams);

        $Grid = $this->Render($detailPage->GetGrid());
        $this->DisplayTemplate('list/detail_page.tpl',
            array(
                'App' => $detailPage->GetListViewData(),
                'LayoutTemplateName' => $layoutTemplate,
                'Page' => $detailPage,
                'DetailPage' => $detailPage
            ),
            array_merge($customParams,
                array(
                    'Authentication' => $detailPage->GetAuthenticationViewData(),
                    'Grid' => $Grid
                )
            )
        );
    }

    /**
     * @param DetailPageEdit $DetailPage
     */
    public function RenderDetailPageEdit($DetailPage) { }


    //TODO: introduce ILoginPage and change the generated code accordingly
    /**
     * @param LoginPage $loginPage
     */
    public function RenderLoginPage($loginPage)  {
        $this->SetHTTPContentTypeByPage($loginPage);

        $customParams = array();
        $template = $loginPage->GetCustomTemplate(PagePart::LoginPage, 'login_page.tpl', $customParams);

        $this->DisplayTemplate($template,
            array(
                'Page' => $loginPage,
                'LoginControl' => $loginPage->GetLoginControl()),
            array_merge($customParams,
                array(
                    'Title' => $loginPage->GetCaption()
                )
            )
        );
    }

    #endregion

    #region Page parts

    /**
     * @param ShowTextBlobHandler $textBlobViewer
     */
    public function RenderTextBlobViewer($textBlobViewer)  {
        $this->DisplayTemplate('text_blob_viewer.tpl',
            array(
                'Viewer' => $textBlobViewer,
                'Page' => $textBlobViewer->GetParentPage()),
            array());
    }

    public abstract function RenderGrid(Grid $Grid);

    public function RenderVerticalGrid(VerticalGrid $grid) {
        $this->SetHTTPContentTypeByPage($grid->GetGrid()->GetPage());

        if ($grid->GetState() == VerticalGridState::JSONResponse) {
            $this->result = SystemUtils::ToXML($grid->GetResponse());
        }
        else if ($grid->GetState() == VerticalGridState::DisplayGrid) {
            $hiddenValues = array(OPERATION_PARAMNAME => OPERATION_COMMIT);
            AddPrimaryKeyParametersToArray($hiddenValues, $grid->GetGrid()->GetDataset()->GetPrimaryKeyValues());

            $customParams = array();
            $this->DisplayTemplate(
                $grid->GetGrid()->GetPage()->GetCustomTemplate(PagePart::VerticalGrid, PageMode::ModalEdit,
                    'edit/vertical_grid.tpl', $customParams),
                array(
                    'Grid' => $grid->GetGrid()->GetModalEditViewData($this)
                ),
                array_merge($customParams,
                    array(
                        'HiddenValues' => $hiddenValues
                    )
                )
            ); 
        }
        else if ($grid->GetState() == VerticalGridState::DisplayInsertGrid) {
            $hiddenValues = array(OPERATION_PARAMNAME => OPERATION_COMMIT);

            $customParams = array();
            $this->DisplayTemplate(
                $grid->GetGrid()->GetPage()->GetCustomTemplate(PagePart::VerticalGrid, PageMode::ModalInsert,
                    'insert/vertical_grid.tpl', $customParams),
                array(
                    'Grid' => $grid->GetGrid()->GetModalInsertViewData($this)
                ),
                array_merge($customParams,
                    array(
                        'HiddenValues' => $hiddenValues
                    )
                )
            );
        }
        else if ($grid->GetState() == VerticalGridState::DisplayCopyGrid) {
            $hiddenValues = array(OPERATION_PARAMNAME => OPERATION_COMMIT);

            $customParams = array();
            $this->DisplayTemplate(
                $grid->GetGrid()->GetPage()->GetCustomTemplate(PagePart::VerticalGrid, PageMode::ModalInsert,
                    'insert/vertical_grid.tpl', $customParams),
                array(
                    'Grid' => $grid->GetGrid()->GetModalInsertViewData($this)
                ),
                array_merge($customParams,
                    array(
                        'HiddenValues' => $hiddenValues
                    )
                )
            );
        }
    }

    public function RenderRecordCardView(RecordCardView $recordCardView) {
        $Grid = $recordCardView->GetGrid();
        $linkBuilder = null;

        $primaryKeyMap = array();
        $Grid->GetDataset()->Open();

        $Row = array();
        if($Grid->GetDataset()->Next())
        {
            $linkBuilder = $Grid->CreateLinkBuilder();
            $linkBuilder->AddParameter(OPERATION_PARAMNAME, OPERATION_PRINT_ONE);

            $keyValues = $Grid->GetDataset()->GetPrimaryKeyValues();
            for($i = 0; $i < count($keyValues); $i++)
                $linkBuilder->AddParameter("pk$i", $keyValues[$i]);

            $primaryKeyMap = $Grid->GetDataset()->GetPrimaryKeyValuesMap();
            $rowValues = $Grid->GetDataset()->GetFieldValues();

            foreach($Grid->GetSingleRecordViewColumns() as $Column)
            {
                $columnName = $Grid->GetDataset()->IsLookupField($Column->GetName()) ?
                    $Grid->GetDataset()->IsLookupFieldNameByDisplayFieldName($Column->GetName()) :
                    $Column->GetName();

                $columnRenderResult = '';
                $customRenderColumnHandled = false;

                $Grid->OnCustomRenderColumn->Fire(array(
                    $columnName,
                    $Column->GetData(),
                    $rowValues,
                    &$columnRenderResult, &$customRenderColumnHandled
                ));

                $columnRenderResult = $customRenderColumnHandled ?
                    $columnRenderResult :
                    $this->Render($Column);

                $Row[] = $columnRenderResult;
            }
        }
        else
        {
            RaiseCannotRetrieveSingleRecordError();
        }

        $customParams = array();
        $this->DisplayTemplate(
            $Grid->GetPage()->GetCustomTemplate(PagePart::VerticalGrid, PageMode::ModalView,
                'view/record_card_view.tpl', $customParams),
            array(
                'Grid' => $Grid,
                'Columns' => $Grid->GetSingleRecordViewColumns()
            ),
            array_merge($customParams,
                array(
                    'PrintOneRecord' => $Grid->GetPage()->GetPrinterFriendlyAvailable(),
                    'PrintRecordLink' => $linkBuilder->GetLink(),
                    'Title' => $Grid->GetPage()->GetShortCaption(),
                    'PrimaryKeyMap' => $primaryKeyMap,
                    'ColumnCount' => count($Grid->GetSingleRecordViewColumns()),
                    'Row' => $Row
                )
            )
        );
    }

    public function RenderPageList(PageList $pageList) {

        $customParams = array();
        $template = $pageList->GetParentPage()->GetCustomTemplate(PagePart::PageList, null, 'page_list.tpl',
            $customParams);
        $this->DisplayTemplate($template,
            array(
                'PageList' => $pageList),
            array_merge($customParams,
                array(
                    'Authentication' => $pageList->GetParentPage()->GetAuthenticationViewData(),
                    'List' => $pageList->GetViewData()
                )
            )
        );
    }

    /**
     * @param LoginControl $loginControl
     */
    public function RenderLoginControl($loginControl)  {
        $customParams = array();
        $template = $loginControl->GetCustomTemplate(PagePart::LoginControl, 'login_control.tpl', $customParams);
        $this->DisplayTemplate($template,
            array('LoginControl' => $loginControl),
            $customParams);
    }

    public function RenderSimpleSearch($searchControl)  {
        // TODO: remove simple search control
    }

    public function RenderAdvancedSearchControl($advancedSearchControl) {
        //TODO: remove advance search control
    }

    #endregion

    #region Column rendering options
    
    protected function ShowHtmlNullValue()  {
        return false;
    }
    
    protected function HttpHandlersAvailable() 
    {
        return true;
    }
    
    protected function HtmlMarkupAvailable() 
    {
        return true;
    }
    
    protected function ChildPagesAvailable() 
    {
        return true;
    }
    
    #endregion

}

class SingleAdvancedSearchRenderer extends Renderer
{
    function RenderPage(Page $Page)
    {
        $this->SetHTTPContentTypeByPage($Page);
        $Page->BeforePageRender->Fire(array(&$Page));

        if (isset($Page->AdvancedSearchControl))
        {
            $Page->AdvancedSearchControl->SetHidden(false);
            $Page->AdvancedSearchControl->SetAllowOpenInNewWindow(false);
            $linkBuilder = $Page->CreateLinkBuilder();
            $Page->AdvancedSearchControl->SetTarget($linkBuilder->GetLink());
        }
        $this->DisplayTemplate('common/single_advanced_search_page.tpl',
            array(
                    'Page' => $Page
                    ),
                array(
                    'AdvancedSearch' => $this->RenderDef($Page->AdvancedSearchControl),
                    'PageList' => $this->RenderDef($Page->GetPageList())
                    )
                );
    }

    public function RenderDetailPageEdit($Page)
    {
        $this->SetHTTPContentTypeByPage($Page);
        $Page->BeforePageRender->Fire(array(&$Page));

        if (isset($Page->AdvancedSearchControl))
        {
            $Page->AdvancedSearchControl->SetHidden(false);
            $Page->AdvancedSearchControl->SetAllowOpenInNewWindow(false);
            $linkBuilder = $Page->CreateLinkBuilder();
            $Page->AdvancedSearchControl->SetTarget($linkBuilder->GetLink());
        }
        $this->DisplayTemplate('common/single_advanced_search_page.tpl',
            array(
                    'Page' => $Page
                    ),
                array(
                    'AdvancedSearch' => $this->RenderDef($Page->AdvancedSearchControl),
                    'PageList' => $this->RenderDef($Page->GetPageList())
                    )
                );
    }

    function RenderGrid(Grid $Grid)
    {
        $this->result = '';
    }

    public function RenderFilterBuilderControl(FilterBuilderControl $filterBuilderControl) {

    }
}
