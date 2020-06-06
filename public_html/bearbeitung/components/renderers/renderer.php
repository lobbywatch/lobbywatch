<?php

include_once dirname(__FILE__) . '/../grid/grid.php';
include_once dirname(__FILE__) . '/../utils/file_utils.php';
include_once dirname(__FILE__) . '/../utils/html_utils.php';
include_once dirname(__FILE__) . '/template_renderer.php';

abstract class Renderer
{
    protected $result;
    /** @var Captions */
    private $captions;

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

    public function __construct($captions) {
        $this->captions = $captions;
    }

    #region Rendering

    public function DisplayTemplate($TemplateName, $InputObjects, $InputValues) {
        $rendererParams = array();
        $rendererParams['Renderer'] = $this;
        $rendererParams['Captions'] = $this->captions;

        $templateParams = array_merge($InputObjects, $rendererParams, $InputValues);

        $templateRenderer = GetTemplateRenderer();
        $this->result = $templateRenderer->render($TemplateName, $templateParams);
    }

    public function Render($Object) {
        $Object->Accept($this);
        return $this->result;
    }

    public function RenderDef($object) {
        if (isset($object))
            return $this->Render($object);
        else
            return '';
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

    protected function GetNullValuePresentation(AbstractViewColumn $column)  {
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
            return $customValue;
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

    private function viewColumnRenderLinkedImagesProperties(AbstractDatasetFieldViewColumn $column, $value) {
        if (is_null($column->getLinkedImages())) {
            return sprintf('<a class="linked-images-retriever" href="#" data-url="%s">%s</a>', $column->getLinkedImagesLink(), $value);
        } else {
            $linkedImagesInfo = $column->getLinkedImages()->getLinkedImagesInfo($column->GetDataset());

            $imageGalleryExpression = '';
            $imagesToDisplayCount = 0;
            foreach ($linkedImagesInfo as $linkedImageInfo) {
                $imageExpression = '';
                if ($imagesToDisplayCount++ < $column->getNumberOfLinkedImagesToDisplayOnViewForm()) {
                    $imageExpression = sprintf('<img src="%s" alt="%s"%s>',
                        $linkedImageInfo['source'],
                        $linkedImageInfo['caption'],
                        $column->getLinkedImages()->getInlineStyles() == '' ? '' : ' ' . sprintf('style="%s"', trim($column->getLinkedImages()->getInlineStyles()))
                    );
                }

                $imageGalleryExpression .=
                    sprintf(
                        '<a class="image gallery-item" data-name="%s" href="%s" title="%s">%s</a>',
                        $column->getDataNameAttributeValue(),
                        $linkedImageInfo['source'],
                        $linkedImageInfo['caption'],
                        $imageExpression
                    );
            }

            if ($column->getNumberOfLinkedImagesToDisplayOnViewForm() > 0) {
                return $imageGalleryExpression;
            } else {
                return sprintf('<a class="linked-images-retriever retrieved" href="#">%s</a>%s', $value, $imageGalleryExpression);
            }
        }
    }

    private function viewColumnRenderHyperlinkProperties(AbstractDatasetFieldViewColumn $column, $value)
    {
        if ($this->HtmlMarkupAvailable()) {
            if (!is_null($column->getLookupRecordModalViewLink())) {
                return sprintf('<a href="#" data-modal-operation="view" data-content-link="%s">%s</a>', $column->getLookupRecordModalViewLink(), $value);
            } elseif ($column->getDisplayLinkedImagesByClick()) {
                return $this->viewColumnRenderLinkedImagesProperties($column, $value);
            } elseif (!is_null($column->getHrefTemplate())) {
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

    protected function getColumnStyle(AbstractDatasetFieldViewColumn $column)
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
                . '... <a class="js-more-hint" href="#">'
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

    /** @param StringTransformViewColumn $column */
    public function RenderStringTransformViewColumn(StringTransformViewColumn $column)
    {
        if (is_null($column->GetValue())) {
            $this->result = $this->GetNullValuePresentation($column);
            return;
        }

        if (function_exists($column->getStringTransformFunction())) {
            $columnValue = call_user_func($column->getStringTransformFunction(), $column->GetValue());
        } else {
            $columnValue = $column->GetValue();
        }

        $this->result = $this->getWrappedViewColumnValue($column, $columnValue);
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
            $column->getFormattedValue(),
            $suffix
        ));
    }

    /**
     * @param ExternalAudioViewColumn $column
     */
    public function RenderExternalAudioViewColumn(ExternalAudioViewColumn $column)
    {
        $code = '<audio class="pgui-field-external-audio" controls><source src="%s" type="audio/mpeg">Your browser does not support the audio element.</audio>';
        $this->renderExternalMediaViewColumn($column, $code);
    }

    /**
     * @param ExternalVideoViewColumn $column
     */
    public function RenderExternalVideoViewColumn(ExternalVideoViewColumn $column)
    {
        $code = sprintf('<video class="pgui-field-external-video" %scontrols>', $column->generateVideoPlayerSizeString()) .
            '<source src="%s" type="video/mp4">Your browser does not support the video element.</video>';
        $this->renderExternalMediaViewColumn($column, $code);
    }

    /**
     * @param AbstractWrappedDatasetFieldViewColumn $column
     * @param string $code
     */
    private function renderExternalMediaViewColumn(AbstractWrappedDatasetFieldViewColumn $column, $code) {
        $value = $column->GetValue();
        if (is_null($value)) {
            $this->result = $this->GetNullValuePresentation($column);
            return;
        }

        if ($this->HtmlMarkupAvailable() && $this->InteractionAvailable()) {
            $this->result = sprintf($code, $column->getWrappedValue());
            return;
        }

        $this->result = $column->getWrappedValue();
    }

    /**
     * @param DownloadDataColumn $column
     */
    public function RenderDownloadDataViewColumn(DownloadDataColumn $column)
    {
        $value = $column->GetValue();
        if ($value == null) {
            $this->result = $this->GetNullValuePresentation($column);
            return;
        }

        if ($this->HtmlMarkupAvailable() && $this->HttpHandlersAvailable() && $this->InteractionAvailable()) {
            $this->result = sprintf(
                '<i class="icon-download"></i>&nbsp;<a target="_blank" title="download" href="%s">%s</a>',
                $column->GetDownloadLink(),
                $this->captions->GetMessageString('Download')
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
                '<img data-image-column="true" src="%s" %salt="%s"%s%s>',
                $column->GetImageLink(),
                $column->generateImageSizeString(),
                $imageHint,
                $customAttributes,
                $style
            ));

            if ($column->GetEnablePictureZoom() && !$column->getDisplayLinkedImagesByClick()) {
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
                'LoginControl' => $loginPage->GetLoginControl(),
                'ReCaptcha' => $loginPage->getReCaptcha()
            ),
            array_merge(
                $customParams,
                array(
                    'Title' => $loginPage->GetTitle(),
                    'layoutTemplate' => $layoutTemplate,
                    'InactivityTimeoutExpired' => $loginPage->getInactivityTimeoutExpired()
                )
            )
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
            array(
                'LoginControl' => $loginControl,
                'SecurityFeedbackPositive' => $loginControl->getSecurityFeedbackPositive(),
                'SecurityFeedbackNegative' => $loginControl->getSecurityFeedbackNegative(),
                'ReCaptcha' => $loginControl->getReCaptcha()
            ),
            $customParams
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
        $template = $page->GetCustomTemplate(
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
                'Banner' => $page->getBanner()
            ),
            array_merge($customParams, array(
                'layoutTemplate' => $layoutTemplate,
                'Authentication' => $page->GetAuthenticationViewData(),
                'PageList' => $this->RenderDef($pageList),
            ))
        );
    }

    /**
     * @param RegistrationPage $page
     */
    public function RenderRegistrationPage(RegistrationPage $page) {
        $this->SetHTTPContentTypeByPage($page);

        if ($page->getRegistrationForm()->isCommit()) {
            $this->result = SystemUtils::ToJSON($page->getRegistrationForm()->getResponse());
            return;
        }

        $customParams = array();
        $layoutTemplate = $page->GetCustomTemplate(
            PagePart::Layout,
            null,
            'common/layout.tpl',
            $customParams
        );

        $template = $page->GetCustomTemplate(
            PagePart::RegistrationPage,
            null,
            'registration_page.tpl',
            $customParams
        );

        $this->DisplayTemplate(
            $template,
            array(
                'common' => $page->getCommonViewData()->setEntryPoint('register'),
                'Page' => $page,
                'RegistrationForm' => $page->getRegistrationForm(),
                'ReCaptcha' => $page->getReCaptcha()
            ),
            array_merge(
                $customParams,
                array(
                    'layoutTemplate' => $layoutTemplate
                )
            )
        );
    }

    /**
     * @param RegistrationForm $form
     */
    public function RenderRegistrationForm(RegistrationForm $form) {
        $customParams = array();
        $template = $form->getRegistrationPage()->GetCustomTemplate(
            PagePart::RegistrationForm,
            null,
            'registration_form.tpl',
            $customParams
        );

        $this->DisplayTemplate(
            $template,
            array(
                'RegistrationForm' => $form,
                'ReCaptcha' => $form->getRegistrationPage()->getReCaptcha()
            ),
            $customParams
        );
    }

    /**
     * @param PasswordRecoveryPage $page
     */
    public function RenderPasswordRecoveryPage(PasswordRecoveryPage $page) {
        $this->SetHTTPContentTypeByPage($page);

        if ($page->formIsCommit()) {
            $this->result = SystemUtils::ToJSON($page->getResponse());
            return;
        }

        $customParams = array();
        $layoutTemplate = $page->GetCustomTemplate(
            PagePart::Layout,
            null,
            'common/layout.tpl',
            $customParams
        );

        $template = $page->GetCustomTemplate(
            PagePart::PasswordRecovery,
            null,
            'recovering_password_page.tpl',
            $customParams
        );

        $this->DisplayTemplate(
            $template,
            array(
                'common' => $page->getCommonViewData()->setEntryPoint('form'),
                'Page' => $page,
                'ReCaptcha' => $page->getReCaptcha()
            ),
            array_merge($customParams, array(
                'layoutTemplate' => $layoutTemplate
            ))
        );
    }

    /**
     * @param ResetPasswordPage $page
     */
    public function RenderResetPasswordPage(ResetPasswordPage $page) {
        $this->SetHTTPContentTypeByPage($page);

        if ($page->formIsCommit()) {
            $this->result = SystemUtils::ToJSON($page->getResponse());
            return;
        }

        $customParams = array();
        $layoutTemplate = $page->GetCustomTemplate(
            PagePart::Layout,
            null,
            'common/layout.tpl',
            $customParams
        );

        $template = $page->GetCustomTemplate(
            PagePart::ResetPassword,
            null,
            'reset_password_page.tpl',
            $customParams
        );

        $this->DisplayTemplate(
            $template,
            array(
                'common' => $page->getCommonViewData()->setEntryPoint('reset-password'),
                'Page' => $page,
            ),
            array_merge($customParams, array(
                'layoutTemplate' => $layoutTemplate
            ))
        );
    }

    /**
     * @param ResendVerificationPage $page
     */
    public function RenderResendVerificationPage(ResendVerificationPage $page) {
        $this->SetHTTPContentTypeByPage($page);

        if ($page->formIsCommit()) {
            $this->result = SystemUtils::ToJSON($page->getResponse());
            return;
        }

        $customParams = array();
        $layoutTemplate = $page->GetCustomTemplate(
            PagePart::Layout,
            null,
            'common/layout.tpl',
            $customParams
        );

        $template = $page->GetCustomTemplate(
            PagePart::ResendVerification,
            null,
            'resend_verification_page.tpl',
            $customParams
        );

        $this->DisplayTemplate(
            $template,
            array(
                'common' => $page->getCommonViewData()->setEntryPoint('form'),
                'Page' => $page,
                'ReCaptcha' => $page->getReCaptcha()
            ),
            array_merge($customParams, array(
                'layoutTemplate' => $layoutTemplate
            ))
        );
    }

    #endregion

    #region Page parts

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
        $isMultiEditOperation = $verticalGrid->getOperation() === OPERATION_MULTI_EDIT;

        $hiddenValues = array();
        if ($isEditOperation) {
            AddPrimaryKeyParametersToArray($hiddenValues, $verticalGrid->GetGrid()->GetDataset()->GetPrimaryKeyValues());
        } elseif ($isMultiEditOperation) {
            $hiddenValues = $verticalGrid->GetGrid()->GetDataset()->fetchPrimaryKeyValues();
        }

        $getWrapper = ArrayWrapper::createGetWrapper();
        $flashMessages = $getWrapper->getValue('flash', false);

        if ($getWrapper->isValueSet('column')) {
            $this->RenderSingleFieldForm(
                $getWrapper->getValue('column'),
                $this->getGridFormViewData($grid, $verticalGrid->getOperation()),
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
                    : $this->getGridFormViewData($grid, $verticalGrid->getOperation()),
                $hiddenValues,
                $verticalGrid->getOperation(),
                $flashMessages
            );
        }

        if (!$isModal && !$isInline) {
            return;
        }

        $pageMode = null;
        if ($isModal) {
            $pageMode = PageMode::ModalInsert;
            if ($isMultiEditOperation) {
                $pageMode = PageMode::ModalMultiEdit;
            } elseif ($isEditOperation) {
                $pageMode = PageMode::ModalEdit;
            }
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
            array('Grid' => $this->getGridFormViewData($grid, $verticalGrid->getOperation()),
                'isMultiEditOperation' => $isMultiEditOperation
            ),
            array_merge($customParams, array(
                'Forms' => $forms,
                'modalSizeClass' => $this->getModalSizeClass($modalFormSize),
                'isNested' => $verticalGrid->isNested(),
            ))
        );
    }

    private function getGridFormViewData(Grid $grid, $operation)
    {
        if ($operation === OPERATION_EDIT) {
            return $grid->GetEditViewData();
        } elseif ($operation === OPERATION_MULTI_EDIT) {
            return $grid->GetMultiEditViewData();
        } else {
            return $grid->GetInsertViewData();
        }
    }

    private function getGridFormInlineViewData(Grid $grid, $isEditOperation)
    {
        return $isEditOperation
            ? $grid->GetInlineEditViewData()
            : $grid->GetInlineInsertViewData();
    }

    protected function RenderForm(Page $page, $gridViewData, $hiddenValues, $operation, $flashMessages)
    {
        $pageMode = PageMode::FormInsert;
        if ($operation === OPERATION_MULTI_EDIT) {
            $pageMode = PageMode::FormMultiEdit;
        } elseif ($operation === OPERATION_EDIT) {
            $pageMode = PageMode::FormEdit;
        }

        $customParams = array();
        $template = $page->GetCustomTemplate(
            PagePart::VerticalGrid,
            $pageMode,
            'forms/form.tpl',
            $customParams
        );

        $this->DisplayTemplate($template, array(
            'Grid' => $gridViewData,
        ), array_merge($customParams, array(
            'isEditOperation' => ($operation === OPERATION_EDIT || $operation === OPERATION_MULTI_EDIT),
            'flashMessages' => $flashMessages,
            'HiddenValues' => $hiddenValues,
            'isMultiEditOperation' => $operation === OPERATION_MULTI_EDIT,
            'isMultiUploadOperation' => $operation === OPERATION_MULTI_UPLOAD,
            'ShowErrorsOnTop' => $page->getShowFormErrorsOnTop(),
            'ShowErrorsAtBottom' => $page->getShowFormErrorsAtBottom()
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

    #endregion

    /**
     * @param Chart $chart
     */
    public function renderChart(Chart $chart)
    {
        $this->DisplayTemplate('charts/chart.tpl', array(), array(
            'type' => $chart->getChartType(),
            'chart' => $chart->getViewData(),
            'uniqueId' => 'chart_' . uniqid(),
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
