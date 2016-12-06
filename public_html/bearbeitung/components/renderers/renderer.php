<?php

include_once dirname(__FILE__) . '/../grid/grid.php';
include_once dirname(__FILE__) . '/../utils/file_utils.php';
include_once dirname(__FILE__) . '/../utils/html_utils.php';

abstract class Renderer
{
    protected $result;
    /** @var Captions */
    private $captions;
    private $renderScripts = true;
    private $renderText = true;
    private $additionalParams = null;

    private $renderingRecordCardView = false;

    protected function DisableCacheControl() {
        // Fixes the IE bug
        // see http://www.alagad.com/blog/post.cfm/error-internet-explorer-cannot-download-filename-from-webserver
        header('Pragma: public');
        header('Cache-Control: max-age=0');
    }

    /**
     * @param CommonPage $page
     */
    protected function SetHTTPContentTypeByPage(CommonPage $page)  {
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
        return $variableContainer;
    }

    #endregion

    #region Columns

    private function GetNullValuePresentation(AbstractViewColumn $column)  {
        if ($this->ShowHtmlNullValue()) {
            $nullLabel = $column->getNullLabel();
            if (is_null($nullLabel)) {
                $nullLabel = $this->GetCaptions()->GetMessageString('NullAsString');
            }
            return sprintf('<em class="pgui-null-value">%s</em>', $nullLabel);
        }

        return '';
    }

    protected function GetFriendlyColumnName(AbstractViewColumn  $column) {
        return $column->GetGrid()->GetDataset()->IsLookupField($column->GetName()) ?
            $column->GetGrid()->GetDataset()->IsLookupFieldNameByDisplayFieldName($column->GetName()) :
            $column->GetName();
    }

    /**
     * @param AbstractViewColumn $column
     * @param array $rowValues
     * @return string
     */
    protected function GetCustomRenderedViewColumn(AbstractViewColumn $column, $rowValues) {
        return null;
    }

    /**
     * @param \AbstractViewColumn $column
     * @param array $rowValues
     * @return string
     */
    public final function RenderViewColumn(AbstractViewColumn $column, $rowValues)
    {
        $customValue = $this->GetCustomRenderedViewColumn($column, $rowValues);
        if (isset($customValue)) {
            return $column->GetGrid()->GetPage()->RenderText($customValue);
        }

        return $this->Render($column);
    }

    /**
     * @param AbstractDatasetFieldViewColumn $column
     */
    public final function RenderDatasetFieldViewColumn(AbstractDatasetFieldViewColumn $column)
    {
        $value = $column->GetValue();
        if (!isset($value)) {
            $this->result = $this->GetNullValuePresentation($column);
        } else {
            $this->result = $this->getWrappedViewColumnValue($column, $column->getValue());
        }
    }

    private function getWrappedViewColumnValue($column, $value)
    {
        return $this->viewColumnRenderStyleProperties(
            $column,
            $this->viewColumnRenderHyperlinkProperties($column, $value)
        );
    }

    private function viewColumnRenderHyperlinkProperties(AbstractDatasetFieldViewColumn $column, $value)
    {
        if ($this->HtmlMarkupAvailable() && !is_null($column->getHrefTemplate())) {
            $href = FormatDatasetFieldsTemplate(
                $column->getDataset(),
                $column->getHrefTemplate()
            );

            return sprintf('<a href="%s" target="%s">%s</a>',
                $href,
                $column->GetTarget(),
                $value
            );
        }

        return $value;
    }

    private function viewColumnRenderStyleProperties(AbstractDatasetFieldViewColumn $column, $value)
    {
        if (is_null($column->getValue())) {
            return $this->GetNullValuePresentation($column);
        }

        if ($this->HtmlMarkupAvailable()) {
            $style = $this->getColumnStyle($column);

            $customAttributes = '';
            if (!is_null($column->getCustomAttributes())) {
                $customAttributes = ' ' . trim($column->getCustomAttributes());
            }

            if (!empty($style) || !empty($customAttributes)) {
                return sprintf(
                    '<div%s%s>%s</div>',
                    $style,
                    $customAttributes,
                    $value
                );
            }
        }

        return $value;
    }

    private function getColumnStyle(AbstractDatasetFieldViewColumn $column)
    {
        $styleBuilder = new StyleBuilder();

        if ($column->getBold()) {
            $styleBuilder->Add('font-weight', 'bold');
        }

        if ($column->getItalic()) {
            $styleBuilder->Add('font-style', 'italic');
        }

        if (!is_null($column->getAlign())) {
            $styleBuilder->Add('text-align', $column->getAlign());
        }

        $style = '';
        if (!$styleBuilder->isEmpty() || $column->getInlineStyles()) {
            $style = sprintf(' style="%s%s"', $styleBuilder->GetStyleString(), $column->getInlineStyles());
        }

        return $style;
    }

    /**
     * @param TextViewColumn $column
     */
    public function RenderTextViewColumn(TextViewColumn $column)
    {
        $value = $column->GetValue();
        $dataset = $column->GetDataset();

        $column->BeforeColumnRender->Fire(array(&$value, &$dataset));

        if (!isset($value)) {
            $this->result = $this->GetNullValuePresentation($column);
            return;
        }

        if ($column->GetEscapeHTMLSpecialChars()) {
            $value = htmlspecialchars($value);
        }

        $columnMaxLength = $column->GetMaxLength();

        if ($this->handleLongValuedTextFields() &&
            $this->HttpHandlersAvailable() &&
            $this->ChildPagesAvailable() &&
            isset($columnMaxLength) &&
            isset($value) &&
            StringUtils::StringLength($value, $column->GetGrid()->GetPage()->GetContentEncoding()) > $columnMaxLength)
        {
            $originalValue = $value;
            if ($this->HtmlMarkupAvailable() && $column->GetReplaceLFByBR()) {
                $originalValue = str_replace("\n", "<br/>", $originalValue);
            }

            $value = StringUtils::SubString($value, 0, $columnMaxLength, $column->GetGrid()->GetPage()->GetContentEncoding());

            $value = $this->getWrappedViewColumnValue(
                $column,
                $value
                . '... <a class="js-more-hint" href="' . $column->GetMoreLink() . '">'
                . $this->captions->GetMessageString('more') . '</a>'
                . '<div class="js-more-box hide">' . $originalValue . '</div>'
            );


        } elseif ($this->HtmlMarkupAvailable()) {
            $value = $this->getWrappedViewColumnValue($column, $value);
        }

        if ($this->HtmlMarkupAvailable() && $column->GetReplaceLFByBR()) {
            $value = str_replace("\n", "<br/>", $value);
        }

        $this->result = $value;
    }

    protected function handleLongValuedTextFields() {
        return !$this->renderingRecordCardView;
    }

    /**
     * @param CheckboxViewColumn $column
     */
    public function RenderCheckboxViewColumn(CheckboxViewColumn $column)
    {
        $value = $column->GetValue();

        if (empty($value)) {
            if ($this->HtmlMarkupAvailable()) {
                $this->result = $column->GetFalseValue();
            } else {
                $this->result = 'false';
            }
        } else {
            if ($this->HtmlMarkupAvailable()) {
                $this->result = $column->GetTrueValue();
            } else {
                $this->result = 'true';
            }
        }
    }

    /**
     * @param EmbeddedVideoViewColumn $column
     */
    public function RenderEmbeddedVideoViewColumn(EmbeddedVideoViewColumn $column)
    {
        $value = $column->GetValue();
        if ($value == null) {
            $this->result = $this->GetNullValuePresentation($column);
            return;
        }

        if ($this->HtmlMarkupAvailable() && $this->InteractionAvailable()) {

            $isYoutube = preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $value, $matches);
            if ($isYoutube) {
                $this->result = sprintf(
                    '<div class="pgui-field-embedded-video" data-url="%s">'
                    . '<img class="pgui-field-embedded-video-thumb" src="https://i.ytimg.com/vi/%s/default.jpg">'
                    . '<span class="pgui-field-embedded-video-icon icon-play"></span>'
                    . '</div>',
                    $value,
                    $matches[1]
                );
            }
            else {
                $this->result = sprintf(
                    '<div class="pgui-field-embedded-video" data-url="%s">'
                    . '<img class="pgui-field-embedded-video-preloader" src="components/assets/img/loading.gif">'
                    . '</div>',
                    $value
                );
            }
            return;
        }

        $this->result = $value;
    }

    /**
     * @param CurrencyViewColumn $column
     */
    public function RenderCurrencyViewColumn(CurrencyViewColumn $column)
    {
        $this->RenderNumberViewColumn($column, $column->getCurrencySign());
    }

    /**
     * @param PercentViewColumn $column
     */
    public function RenderPercentViewColumn(PercentViewColumn $column)
    {
        $this->RenderNumberViewColumn($column, null, '%');
    }

    /**
     * @param StringTransformViewColumn $column
     */
    public function RenderStringTransformViewColumn(StringTransformViewColumn $column)
    {
        if (is_null($column->GetValue())) {
            $this->result = $this->GetNullValuePresentation($column);
            return;
        }

        $this->result = $this->getWrappedViewColumnValue($column, call_user_func(
            $column->getStringTransformFunction(),
            $column->getValue()
        ));
    }

    /**
     * @param NumberViewColumn $column
     * @param string $prefix
     * @param string $suffix
     */
    public function RenderNumberViewColumn(NumberViewColumn $column, $prefix = null, $suffix = null)
    {
        if (is_null($column->GetValue())) {
            $this->result = $this->GetNullValuePresentation($column);
            return;
        }

        $this->result = $this->getWrappedViewColumnValue($column, sprintf(
            '%s%s%s',
            $prefix,
            number_format(
                (double) $column->GetValue(),
                $column->GetNumberAfterDecimal(),
                $column->GetDecimalSeparator(),
                $column->GetThousandsSeparator()
            ),
            $suffix
        ));
    }

    /**
     * @param ExternalAudioFileColumn $column
     */
    public function RenderExternalAudioViewColumn(ExternalAudioFileColumn $column)
    {
        if (is_null($column->GetValue())) {
            $this->result = $this->GetNullValuePresentation($column);
            return;
        }

        if ($this->HtmlMarkupAvailable() && $this->InteractionAvailable()) {
            $this->result = sprintf(
                '<audio controls><source src="%s" type="audio/mpeg">Your browser does not support the audio element.</audio>',
                $column->getWrappedValue()
            );
            return;
        }

        $this->result = $column->getWrappedValue();
    }

    /**
     * @param DownloadDataColumn $column
     */
    public function RenderDownloadDataViewColumn(DownloadDataColumn $column)
    {
        if (is_null($column->GetValue())) {
            $this->result = $this->GetNullValuePresentation($column);
            return;
        }

        if ($this->HtmlMarkupAvailable() && $this->HttpHandlersAvailable() && $this->InteractionAvailable()) {
            $this->result = sprintf(
                '<i class="icon-download"></i>&nbsp;<a target="_blank" title="download" href="%s">%s</a>',
                $column->GetDownloadLink(),
                $column->GetLinkInnerHtml()
            );
            return;
        }

        if ($column->GetFieldType() === ftBlob) {
            $this->result = $this->Captions()->GetMessageString('BinaryDataCanNotBeExportedToXls');
        } else {
            $this->result = $column->GetValue();
        }
    }

    /**
     * @param DownloadExternalDataColumn $column
     */
    public function RenderDownloadExternalDataViewColumn(DownloadExternalDataColumn $column)
    {
        if (is_null($column->GetValue())) {
            $this->result = $this->GetNullValuePresentation($column);
            return;
        }

        if ($this->HtmlMarkupAvailable() && $this->HttpHandlersAvailable() && $this->InteractionAvailable()) {
            $this->result = StringUtils::Format(
                '<i class="icon-download"></i>&nbsp;<a target="_blank" title="%s" href="%s">%s</a>',
                FormatDatasetFieldsTemplate($column->getDataset(), $column->getDownloadLinkHintTemplate()),
                $column->getWrappedValue(),
                $this->captions->GetMessageString('Download')
            );
            return;
        }

        $this->result = $column->getWrappedValue();
    }

    /**
     * @param ImageViewColumn $column
     */
    public function RenderImageViewColumn(ImageViewColumn $column)
    {
        if (is_null($column->GetValue())) {
            $this->result = $this->GetNullValuePresentation($column);
            return;
        }

        if ($this->HtmlMarkupAvailable()) {
            $style = $this->getColumnStyle($column);
            $customAttributes = '';
            if (!is_null($column->getCustomAttributes())) {
                $customAttributes = ' ' . trim($column->getCustomAttributes());
            }

            $imageHint = htmlentities(FormatDatasetFieldsTemplate($column->getDataset(), $column->getImageHintTemplate()));

            $this->result = $this->viewColumnRenderHyperlinkProperties($column, sprintf(
                '<img data-image-column="true" src="%s" alt="%s"%s%s>',
                $column->GetImageLink(),
                $imageHint,
                $customAttributes,
                $style
            ));

            if ($column->GetEnablePictureZoom()) {
                $this->result = sprintf(
                    '<a class="image gallery-item" data-name="%s" href="%s" title="%s">%s</a>',
                    $column->getFieldName(),
                    $column->GetFullImageLink(),
                    $imageHint,
                    $this->result
                );
            }

            return;
        }

        $this->result = $column->getWrappedValue();
    }

    /**
     * @param BlobImageViewColumn $column
     */
    public function RenderBlobImageViewColumn(BlobImageViewColumn $column)
    {
        if (!is_null($column->GetValue()) && !$this->HtmlMarkupAvailable() && !$this->HttpHandlersAvailable()) {
            $this->result = $this->Captions()->GetMessageString('BinaryDataCanNotBeExportedToXls');
        } else {
            $this->RenderImageViewColumn($column);
        }
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
    public function RenderCustomErrorPage($errorPage)
    {
        $this->DisplayTemplate(
            'security_error_page.tpl',
            array(
                'Page' => $errorPage
            ),
            array(
                'JavaScriptMain' => '',
                'Authentication' => $errorPage->GetAuthenticationViewData(),
                'common' => $errorPage->GetCommonViewData(),
                'Message' => $errorPage->GetMessage(),
                'Description' => $errorPage->GetDescription(),
            )
        );
    }

    /**
     * @param DetailPage $DetailPage
     */
    public function RenderDetailPage(DetailPage $DetailPage) { }


    //TODO: introduce ILoginPage and change the generated code accordingly
    /**
     * @param LoginPage $loginPage
     */
    public function RenderLoginPage($loginPage)  {
        $this->SetHTTPContentTypeByPage($loginPage);

        $customParams = array();
        $layoutTemplate = $loginPage->GetCustomTemplate(
            PagePart::Layout,
            null,
            'common/layout.tpl',
            $customParams
        );

        $template = $loginPage->GetCustomTemplate(
            PagePart::LoginPage,
            null,
            'login_page.tpl',
            $customParams
        );

        $this->DisplayTemplate(
            $template,
            array(
                'common' => $loginPage->getCommonViewData(),
                'Page' => $loginPage,
                'LoginControl' => $loginPage->GetLoginControl()
            ),
            array_merge($customParams, array(
                'Title' => $loginPage->GetTitle(),
                'layoutTemplate' => $layoutTemplate,
            ))
        );
    }

    /**
     * @param HomePage $page
     */
    public function RenderHomePage(HomePage $page)
    {
        $this->SetHTTPContentTypeByPage($page);

        $pageList = $page->GetReadyPageList();

        if (count($pageList->getPages()) === 0 && function_exists('SetUpUserAuthorization')) {
            header('Location: login.php');
            exit;
        }

        $customParams = array();
        $layoutTemplate = $page->GetCustomTemplate(
            PagePart::Layout,
            null,
            'common/layout.tpl',
            $customParams
        );
        $template = $template = $page->GetCustomTemplate(
            PagePart::HomePage,
            null,
            'home_page.tpl',
            $customParams
        );

        $this->DisplayTemplate(
            $template,
            array(
                'common' => $page->getCommonViewData(),
                'Page' => $page,
            ),
            array_merge($customParams, array(
                'layoutTemplate' => $layoutTemplate,
                'Authentication' => $page->GetAuthenticationViewData(),
                'PageList' => $this->RenderDef($pageList),
            ))
        );
    }


    #endregion

    #region Page parts

    /**
     * @param ShowTextBlobHandler $textBlobViewer
     */
    public function RenderTextBlobViewer($textBlobViewer)
    {
        $this->DisplayTemplate('text_blob_viewer.tpl',
            array(
                'Viewer' => $textBlobViewer,
                'Page' => $textBlobViewer->GetParentPage()),
            array());
    }

    public abstract function RenderGrid(Grid $Grid);


    /**
     * @param VerticalGrid $verticalGrid
     * @return null
     */
    public function RenderVerticalGrid(VerticalGrid $verticalGrid)
    {
        $grid = $verticalGrid->GetGrid();
        $page = $grid->GetPage();
        $page->UpdateValuesFromUrl();

        $this->SetHTTPContentTypeByPage($verticalGrid->GetGrid()->GetPage());
        $modalFormSize = $verticalGrid->GetGrid()->GetPage()->getModalFormSize();

        if ($verticalGrid->isCommit()) {
            $this->result = SystemUtils::ToJSON($verticalGrid->GetResponse());
            return;
        }

        $isModal = $verticalGrid->isModal();
        $isInline = $verticalGrid->isInline();

        $isEditOperation = $verticalGrid->getOperation() === OPERATION_EDIT;

        $hiddenValues = array(OPERATION_PARAMNAME => OPERATION_COMMIT);
        if ($isEditOperation) {
            AddPrimaryKeyParametersToArray($hiddenValues, $verticalGrid->GetGrid()->GetDataset()->GetPrimaryKeyValues());
        }

        $getWrapper = ArrayWrapper::createGetWrapper();
        $flashMessages = $getWrapper->getValue('flash', false);

        if ($getWrapper->isValueSet('column')) {
            $this->RenderSingleFieldForm(
                $getWrapper->getValue('column'),
                $this->getGridFormViewData($grid, true),
                $hiddenValues
            );
            return;
        }

        $forms = array();
        $count = $getWrapper->getValue('count', 1);
        for ($i = 0; $i < $count; $i++) {
            $forms[] = $this->RenderForm(
                $page,
                $isInline
                    ? $this->getGridFormInlineViewData($grid, $isEditOperation)
                    : $this->getGridFormViewData($grid, $isEditOperation),
                $hiddenValues,
                $isEditOperation,
                $flashMessages
            );
        }

        if (!$isModal && !$isInline) {
            return;
        }

        $pageMode = null;
        if ($isModal) {
            $pageMode = $isEditOperation ? PageMode::ModalEdit : PageMode::ModalInsert;
        } else {
            $pageMode = $isEditOperation ? PageMode::InlineEdit : PageMode::InlineInsert;
        }

        $customParams = array();
        $template = $page->GetCustomTemplate(
            PagePart::VerticalGrid,
            $pageMode,
            $isModal ? 'forms/modal_form.tpl' : 'forms/inline_form.tpl',
            $customParams
        );

        $this->DisplayTemplate(
            $template,
            array('Grid' => $this->getGridFormViewData($grid, $isEditOperation)),
            array_merge($customParams, array(
                'Forms' => $forms,
                'modalSizeClass' => $this->getModalSizeClass($modalFormSize),
                'isNested' => $verticalGrid->isNested(),
            ))
        );
    }

    private function getGridFormViewData(Grid $grid, $isEditOperation)
    {
        return $isEditOperation
            ? $grid->GetEditViewData()
            : $grid->GetInsertViewData();
    }

    private function getGridFormInlineViewData(Grid $grid, $isEditOperation)
    {
        return $isEditOperation
            ? $grid->GetInlineEditViewData()
            : $grid->GetInlineInsertViewData();
    }

    protected function RenderForm(Page $page, $gridViewData, $hiddenValues, $isEditOperation, $flashMessages)
    {
        $customParams = array();
        $template = $page->GetCustomTemplate(
            PagePart::VerticalGrid,
            $isEditOperation ? PageMode::FormEdit : PageMode::FormInsert,
            'forms/form.tpl',
            $customParams
        );


        $this->DisplayTemplate($template, array(
            'Grid' => $gridViewData,
        ), array_merge($customParams, array(
            'isEditOperation' => $isEditOperation,
            'flashMessages' => $flashMessages,
            'HiddenValues' => $hiddenValues,
        )));

        return $this->result;
    }

    private function RenderSingleFieldForm($column, array $gridViewData, array $hiddenValues)
    {
        /** @var FormLayout $layout */
        $layout = $gridViewData['FormLayout'];
        $columns = $layout->getColumns();

        $this->DisplayTemplate('forms/single_field_form.tpl', array(
            'Grid' => $gridViewData,
            'ColumnName' => $column,
            'Column' => $columns[$column],
            'Columns' => $columns,
        ), array(
            'HiddenValues' => $hiddenValues,
        ));

        return $this->result;
    }

    public function RenderRecordCardView(RecordCardView $recordCardView) {
        $this->renderingRecordCardView = true;

        try {
            $grid = $recordCardView->GetGrid();
            $customParams = array();
            $template = $grid->GetPage()->GetCustomTemplate(PagePart::VerticalGrid, PageMode::ModalView, 'view/record_card_view.tpl', $customParams);

            $this->DisplayTemplate($template, array(), array_merge(
                $customParams,
                array(
                    'Grid' => $grid->getViewSingleRowViewData(),
                    'modalSizeClass' => $this->getModalSizeClass($grid->GetPage()->getModalViewSize()),
                )
            ));
        } catch (Exception $e) {
            $this->renderingRecordCardView = false;
            throw $e;
        }

        $this->renderingRecordCardView = false;
    }

    private function getModalSizeClass($modalSize)
    {
        $map = array(
            Modal::SIZE_SM => 'modal-sm',
            Modal::SIZE_MD => '',
            Modal::SIZE_LG => 'modal-lg',
        );

        return $map[$modalSize];
    }

    public function RenderPageList(PageList $pageList) {

        $customParams = array();
        $defaultTemplate = $pageList->isTypeSidebar() ? 'page_list_sidebar.tpl' : 'page_list_menu.tpl';

        $template = $pageList->GetParentPage()->GetCustomTemplate(
            PagePart::PageList,
            null,
            $defaultTemplate,
            $customParams
        );

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
     * @param Navigation $navigation
     */
    public function RenderNavigation(Navigation $navigation)
    {
        $page = $navigation->getPage();

        $customParams = array();
        $template = $page->GetCustomTemplate(
            PagePart::Navigation,
            null,
            'navigation.tpl',
            $customParams
        );

        $this->DisplayTemplate(
            $template,
            array('navigation' => $navigation),
            $customParams
        );
    }


    /**
     * @param LoginControl $loginControl
     */
    public function RenderLoginControl($loginControl)  {
        $customParams = array();
        $template = $loginControl->getPage()->GetCustomTemplate(
            PagePart::LoginControl,
            null,
            'login_control.tpl',
            $customParams
        );

        $this->DisplayTemplate(
            $template,
            array('LoginControl' => $loginControl),
            $customParams
        );
    }

    #endregion

    /**
     * @param Chart $chart
     */
    public function renderChart(Chart $chart)
    {
        $this->DisplayTemplate('charts/chart.tpl', array(), array(
            'type' => $chart->getChartType(),
            'chart' => $chart->getViewData(),
            'uniqueId' => uniqid(),
        ));
    }

    public function RenderAdminPage(AdminPage $page) {
        throw new LogicException('Admin page cannot be rendered by class ' . get_class($this));
    }

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

    protected function InteractionAvailable()
    {
        return true;
    }

    #endregion

}
