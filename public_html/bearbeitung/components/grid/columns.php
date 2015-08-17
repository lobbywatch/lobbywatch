<?php

include_once dirname(__FILE__) . '/' . '../utils/system_utils.php';
// require_once 'components/utils/system_utils.php';

function GetOrderTypeCaption($orderType)
{
    global $orderTypeCaptions;
    return $orderTypeCaptions[$orderType];
}

abstract class CustomViewColumn
{
    /** @var string */
    private $caption;

    /** @var null|string */
    private $fixedWidth;

    /** @var null|string */
    private $verticalLine;

    /** @var string */
    private $description;

    /** @var Component */
    public $headerControl;

    /** @var Grid */
    protected $grid;

    /** @var CustomEditColumn */
    public $editOperationColumn; // public?

    /** @var CustomEditColumn */
    private $insertOperationColumn;

    /** @var bool */
    private $wordWrap;

    /**
     * @param string $caption
     */
    public function __construct($caption)
    {
        $this->caption = $caption;
        $this->fixedWidth = null;
        $this->verticalLine = null;
        $this->insertOperationColumn = null;
        $this->wordWrap = true;
    }

    public function GetGridColumnClass() {
        return null;
    }

    public function GetDescription()
    { return $this->description; }
    public function SetDescription($value)
    { $this->description = $value; }
    
    public function GetVerticalLine()
    {
        return $this->verticalLine;
    }
    public function SetVerticalLine($value)
    {
        $this->verticalLine = $value;
    }

    public function GetWordWrap()
    {
        return $this->wordWrap;
    }

    public function SetWordWrap($value)
    {
        $this->wordWrap = $value;
    }

    protected function CreateHeaderControl()
    {
        $result = new HintedTextBox('HeaderControl', $this->GetCaption());
        $result->SetHint($this->GetDescription());
        return $result;
    }

    /**
     * @return string
     */
    public function GetName()
    { }

    public function GetCaption()
    { 
        return $this->caption;
    }

    public function SetGrid($value)
    {
        $this->grid = $value;
        $this->caption = $this->grid->GetPage()->RenderText($this->caption);
        if ($this->GetEditOperationColumn() != null) {
            $this->GetEditOperationColumn()->SetGrid($this->grid);
        }
        if ($this->GetInsertOperationColumn() != null) {
            $this->GetInsertOperationColumn()->SetGrid($this->grid);
        }
    }

    /**
     * @return Grid
     */
    public function GetGrid()
    { 
        return $this->grid;
    }

    abstract public function GetValue();

    public function GetData()
    {
        return null;
    }

    /**
     * @param Renderer $renderer
     * @return void
     */
    public function Accept($renderer)
    {
        $renderer->RenderCustomViewColumn($this);
    }

    public function ProcessMessages()
    {
        if (GetOperation() == OPERATION_AJAX_REQUERT_INLINE_EDIT)
        {
            if (isset($this->editOperationColumn))
                $this->editOperationColumn->ProcessMessages();
        }
        elseif (GetOperation() == OPERATION_AJAX_REQUERT_INLINE_INSERT)
        {
            if (isset($this->insertOperationColumn))
                $this->insertOperationColumn->ProcessMessages();
        }
    }

    public function GetHeaderControl()
    {
        if (!isset($this->headerControl))
            $this->headerControl = $this->CreateHeaderControl();
        return $this->headerControl;
    }

    public function GetAfterRowControl()
    {
        return new NullComponent('');
    }

    public function SetFixedWidth($value)
    { 
        $this->fixedWidth = $value;
    }

    public function GetFixedWidth()
    { 
        return $this->fixedWidth;
    }
    
    public function UseFixedWidth()
    {
        $fixedWidth = $this->GetFixedWidth();
        return isset($fixedWidth);
    }

    public function IsDataColumn()
    { 
        return false;
    }

    public function GetAlign()
    {
        return null;
    }

    #region Edit operation
    public function SetEditOperationColumn(CustomEditColumn $value)
    {
        $this->editOperationColumn = $value;
    }

    /**
     * @return CustomEditColumn
     */
    public function GetEditOperationColumn()
    {
        return $this->editOperationColumn;
    }

    public function GetEditOperationEditor()
    {
        if (isset($this->editOperationColumn))
            return $this->editOperationColumn->GetEditControl();
        else
            return null;
    }
    #endregion

    #region Insert operation
    public function SetInsertOperationColumn(CustomEditColumn $value)
    {
        $this->insertOperationColumn = $value;
    }

    /**
     * @return CustomEditColumn
     */
    public function GetInsertOperationColumn()
    {
        return $this->insertOperationColumn;
    }

    public function GetInsertOperationEditor()
    {
        if (isset($this->insertOperationColumn))
            return $this->insertOperationColumn->GetEditControl();
        else
            return null;
    }
    #endregion

    private function GetTotalValueAsHtml($value)
    {
        $result = $value;
        if (is_numeric($value))
            $result = number_format((double)$value, 2);
        return $result;
    }

    private function GetCustomTotalPresentation($originalValue)
    {
        $aggregate = $this->GetGrid()->GetAggregateFor($this)->AsString();
        $result = '';
        $handled = false;
        $this->GetGrid()->OnCustomRenderTotal->Fire(array($originalValue, $aggregate, $this->GetName(), &$result, &$handled));
        if ($handled)
            return $result;
        else
            return null;
    }

    public function GetTotalPresentationData($totalValue)
    {
        $result = array();
        $result['IsEmpty'] = !isset($totalValue);

        if(isset($totalValue))
        {
            $result['Value'] = $this->GetTotalValueAsHtml($totalValue);
            $result['Aggregate'] = $this->GetGrid()->GetAggregateFor($this)->AsString();
            $result['UserHTML'] = $this->GetCustomTotalPresentation($totalValue);
            $result['CustomValue'] = $result['UserHTML'] != null;
        }
        return $result;
    }

    protected function IsNull()
    {
        return false;
    }

    public function GetViewData() {
        return array(
            'Attributes' => $this->UseFixedWidth() ? ('width="' . $this->GetFixedWidth() . '"') : '',
            'Name' => $this->GetName(),
            'Caption' => $this->GetCaption(),
            'Classes' => $this->GetGridColumnClass(),
            'Comment' => $this->GetDescription(),
            'Styles' => $this->UseFixedWidth() ? ($this->GetFixedWidth() . ';') : ''
        );
    }
}

abstract class CustomDatasetFieldViewColumn extends CustomViewColumn
{
    /** @var string */
    private $fieldName;

    /** @var Dataset */
    private $dataset;

    /** @var bool */
    private $orderable;

    /** @var Dataset|null */
    private $lookupDataset;

    #region Events
    public $BeforeColumnRender;
    #endregion

    public function __construct($fieldName, $caption, $dataset, $orderable = true)
    {
        parent::__construct($caption);
        $this->BeforeColumnRender = new Event();
        //
        $this->fieldName = $fieldName;
        $this->dataset = $dataset;
        $this->orderable = $orderable;
        $this->lookupDataset = null;
        $this->lookupHandlerName = null;
    }

    public function GetGridColumnClass() {
        $result = parent::GetGridColumnClass() ? parent::GetGridColumnClass() : parent::GetGridColumnClass();
        if ($this->GetGrid()->GetShowKeyColumnsImagesInHeader()) {
            if ($this->dataset->IsFieldPrimaryKey($this->fieldName)) {
                StringUtils::AddStr($result, 'primary-key', ' ');
            }
            if ($this->dataset->IsLookupField($this->fieldName)) {
                if ($this->dataset->IsLookupFieldNameByDisplayFieldName($this->fieldName))
                    if ($this->dataset->IsFieldPrimaryKey($this->dataset->IsLookupFieldNameByDisplayFieldName($this->fieldName)))
                        StringUtils::AddStr($result, 'primary-key', ' ');
                StringUtils::AddStr($result, 'foreign-key', ' ');
            }
        }

        if ($this->ShowOrderingControl()) {
            StringUtils::AddStr($result, 'sortable', ' ');
        }
        StringUtils::AddStr($result, $this->GetSortOrderColumnClass(), ' ');
        return $result;
    }

    public function SetLookupDataset(Dataset $dataset) {
        $this->lookupDataset = $dataset;
    }

    public function GetLookupDataset() {
        return $this->lookupDataset;
    }

    public function GetLookupHandlerName() {
        return $this->lookupHandlerName;
    }

    public function RegisterLookupHTTPHandler($parentPageName, $idField, $valueField) {
        $this->lookupHandlerName = $parentPageName . '_' . $this->fieldName . '_search';
        GetApplication()->RegisterHTTPHandler(new DynamicSearchHandler(
            $this->lookupDataset,
            null,
            $this->lookupHandlerName,
            $idField,
            $valueField,
            null
        ));
    }

    public function SetOrderable($value)
    { $this->orderable = $value; }

    public function GetOrderable()
    { return $this->orderable; }

    protected function GetFieldName() {
        return $this->fieldName;
    }

    public function GetName() {
        return $this->fieldName; 
    }

    /**
     * @return Dataset
     */
    public function GetDataset() {
        return $this->dataset; 
    }

    public function GetData() {
        return $this->GetDataset()->GetFieldValueByName($this->GetFieldName()); 
    }

    protected abstract function DoGetValue();

    public function GetValue()
    {
        $result = $this->GetData();
        return isset($result) ? $this->DoGetValue() :  null;
    }

    /**
     * @param Renderer $renderer
     * @return void
     */
    public function Accept($renderer)
    {
        $renderer->RenderCustomDatasetFieldViewColumn($this);
    }

    public function ShowOrderingControl()
    {
        if ($this->GetGrid() != null)
            return $this->GetOrderable() && $this->GetGrid()->GetAllowOrdering();
        else
            return $this->GetOrderable();
    }

    protected function CreateHeaderControl()
    {
        if ($this->ShowOrderingControl())
        {
            $result = new HintedTextBox('HeaderControl', $this->GetCaption());
            $result->SetHint($this->GetDescription());
            return $result;
        }
        else
            return parent::CreateHeaderControl();
    }

    private function GetOrderByLink($currentOrderType = null)
    {
        $linkBuilder = $this->GetGrid()->CreateLinkBuilder();

        switch($currentOrderType)
        {
            case otAscending:
                $linkBuilder->AddParameter('order', GetOrderTypeCaption(otDescending) . $this->fieldName);
                break;
            case otDescending:
                $linkBuilder->AddParameter(OPERATION_PARAMNAME, 'resetorder');
                break;
            case null:
                $linkBuilder->AddParameter('order', GetOrderTypeCaption(otAscending) . $this->fieldName);
                break;
        }

        return $linkBuilder->GetLink();
    }

    private function GetSortCaption($currentOrderType = null)
    {
        switch($currentOrderType)
        {
            case otAscending:
                return ' <img src="images/sort_up.gif" style="border: 0;">';
                break;
            case otDescending:
                return ' <img src="images/sort_down.gif" style="border: 0;">';
                break;
            default:
                return ' <img src="images/sort_none.gif" style="border: 0;">';
                break;
        }
    }

    private function GetSortOrderColumnClass() {
        $orderColumn = $this->GetGrid()->GetOrderColumnFieldName();
        if ($orderColumn == $this->fieldName)
        {
            switch($this->GetGrid()->GetOrderType())
            {
                case otAscending:
                    return 'ascending';
                    break;
                case otDescending:
                    return 'descending';
                    break;
                default:
                    return '';
                    break;
            }
        }
        else
        {
            return '';
        }
    }

    public function GetViewData() {
        $result = parent::GetViewData();
        if ($this->ShowOrderingControl()) {
            if (isset($this->sortUrl))
                $result['SortUrl'] = $this->sortUrl;
        }
        return $result;
    }

    public function ProcessMessages()
    {
        parent::ProcessMessages();
        if ($this->ShowOrderingControl())
        {
            $orderColumn = $this->GetGrid()->GetOrderColumnFieldName();
            if ($orderColumn == $this->fieldName)
            {
                $this->sortUrl = $this->GetOrderByLink($this->GetGrid()->GetOrderType());
                $this->GetHeaderControl()->SetAfterLinkText(
                    ' <a href="'.$this->GetOrderByLink($this->GetGrid()->GetOrderType()).'">'.
                    $this->GetSortCaption($this->GetGrid()->GetOrderType()).
                    '</a>'
                );
            }
            else
            {
                $this->sortUrl = $this->GetOrderByLink();
                $this->GetHeaderControl()->SetAfterLinkText(
                    ' <a href="' . $this->GetOrderByLink()  .'">'.$this->GetSortCaption(null).'</a>');
            }
        }
    }

    public function IsDataColumn()
    { 
        return true; 
    }
}

class TextViewColumn extends CustomDatasetFieldViewColumn
{
    private $maxLength;
    private $replaceLFByBR;
    private $escapeHTMLSpecialChars;
    private $fullTextWindowHandlerName;

    public function __construct($fieldName, $caption, $dataset, $orderable = true)
    {
        parent::__construct($fieldName, $caption, $dataset, $orderable);
        $this->maxLength = null;
        $this->replaceLFByBR = false;
        $this->escapeHTMLSpecialChars = false;
        $this->fullTextWindowHandlerName = null;
    }

    public function GetMoreLink()
    {
        $result = $this->GetGrid()->CreateLinkBuilder();
        if ($this->GetFullTextWindowHandlerName() != null)
            $result->AddParameter('hname', $this->GetFullTextWindowHandlerName());
        else
            $result->AddParameter('hname', $this->GetFieldName() . '_handler');

        AddPrimaryKeyParameters($result, $this->GetDataset()->GetPrimaryKeyValues());
        return $result->GetLink();
    }

    protected function DoGetValue()
    {
        return $this->GetData();
    }

    public function IsNull()
    {
        $value = $this->GetData();
        return !isset($value);
    }

    /**
     * @param Renderer $renderer
     * @return void
     */
    public function Accept($renderer)
    {
        $renderer->RenderTextViewColumn($this);
    }

    #region Column options

    public function SetMaxLength($value)
    { 
        $this->maxLength = $value; 
    }

    public function GetMaxLength()
    { 
        return $this->maxLength; 
    }

    public function SetReplaceLFByBR($value)
    { 
        $this->replaceLFByBR = $value; 
    }

    public function GetReplaceLFByBR()
    { 
        return $this->replaceLFByBR; 
    }

    public function SetEscapeHTMLSpecialChars($value)
    { 
        $this->escapeHTMLSpecialChars = $value; 
    }

    public function GetEscapeHTMLSpecialChars()
    { 
        return $this->escapeHTMLSpecialChars; 
    }

    public function SetFullTextWindowHandlerName($value)
    { 
        $this->fullTextWindowHandlerName = $value; 
    }

    public function GetFullTextWindowHandlerName()
    { 
        return $this->fullTextWindowHandlerName; 
    }

    #endregion
}

class DateTimeViewColumn extends CustomDatasetFieldViewColumn
{
    private $dateTimeFormat;

    public function __construct($fieldName, $caption, $dataset, $orderable = true)
    {
        parent::__construct($fieldName, $caption, $dataset, $orderable);
        $this->dateTimeFormat = 'Y-m-d';
    }

    public function SetDateTimeFormat($value)
    { 
        $this->dateTimeFormat = $value; 
    }

    public function GetDateTimeFormat()
    { 
        return $this->dateTimeFormat; 
    }

    public function GetOSDateTimeFormat()
    {
        return DateFormatToOSFormat($this->dateTimeFormat);
    }

    protected function DoGetValue()
    {
        $value = $this->GetDataset()->GetFieldValueByNameAsDateTime($this->GetName());

        $stringValue = isset($value) ? $value->ToString($this->dateTimeFormat) : null;
        $dataset = $this->GetDataset();
        $this->BeforeColumnRender->Fire(array(&$stringValue, &$dataset));

        return isset($stringValue) ? $stringValue : 'NULL';
    }
}

abstract class CustomFormatValueViewColumnDecorator extends CustomViewColumn
{
    /** @var CustomDatasetFieldViewColumn */
    protected $innerField;

    public function __construct($innerField)
    {
        parent::__construct('');
        $this->innerField = $innerField;
        $this->Bold = null;
        
    }

    public function GetDescription()
    { return $this->innerField->GetDescription(); }
    public function SetDescription($value)
    { $this->innerField->SetDescription($value); }

    public function GetName()
    { return $this->innerField->GetName(); }
    public function GetData()
    { return $this->innerField->GetData(); }

    protected function GetInnerFieldValue()
    {
        return $this->innerField->GetValue();
    }

    protected function IsNull()
    {
        return $this->innerField->IsNull();
    }

    public function GetInnerField()
    {
        return $this->innerField;
    }

    public function GetCaption()
    { return $this->innerField->GetCaption(); }

    public function SetGrid($value)
    { $this->innerField->SetGrid($value); }

    public function GetAfterRowControl()
    { return $this->innerField->GetAfterRowControl(); }

    public function GetHeaderControl()
    { return $this->innerField->GetHeaderControl(); }

    public function ProcessMessages()
    {
        $this->innerField->ProcessMessages();
    }

    public function SetFixedWidth($value)
    {
        $this->innerField->SetFixedWidth($value);
    }

    public function GetFixedWidth()
    {
        return $this->innerField->GetFixedWidth();
    }

    public function IsDataColumn()
    { return $this->innerField->IsDataColumn(); }

    #region Edit operation
    public function SetEditOperationColumn(CustomEditColumn $value)
    {
        $this->innerField->SetEditOperationColumn($value);
    }

    public function GetEditOperationColumn()
    {
        return $this->innerField->GetEditOperationColumn();
    }

    public function GetEditOperationEditor()
    {
        return $this->innerField->GetEditOperationEditor();
    }
    #endregion

    #region Insert operation
    public function SetInsertOperationColumn(CustomEditColumn $value)
    {
        $this->innerField->SetInsertOperationColumn($value);
    }

    public function GetInsertOperationColumn()
    {
        return $this->innerField->GetInsertOperationColumn();
    }

    public function GetInsertOperationEditor()
    {
        return $this->innerField->GetInsertOperationEditor();
    }

    public function GetGrid()
    {
        return $this->innerField->GetGrid();
    }

    public function SetOrderable($value)
    {
        if (SMReflection::IsInstanceOf($this->GetInnerField(), 'CustomDatasetFieldViewColumn')) {
            $this->GetInnerField()->SetOrderable($value);
        }
    }

    public function GetOrderable()
    {
        if (SMReflection::IsInstanceOf($this->GetInnerField(), 'CustomDatasetFieldViewColumn')) {
            return $this->GetInnerField()->GetOrderable();
        }
        else {
            return false;
        }
    }

    public function ShowOrderingControl() {
        return $this->GetInnerField()->ShowOrderingControl();
    }

    public function GetGridColumnClass() {
        return $this->GetInnerField()->GetGridColumnClass();
    }


    public function GetViewData() {
        return $this->GetInnerField()->GetViewData();
    }



    #endregion
}

class DivTagViewColumnDecorator extends CustomFormatValueViewColumnDecorator
{
    public $Bold;
    public $Italic;
    public $CustomAttributes;
    public $Align;

    public function __construct($innerField)
    {
        
        parent::__construct($innerField);
        $this->Bold = null;
        $this->Italic = null;
        $this->CustomAttributes = null;
        $this->innerField = $innerField;
    }

    //TODO: remove
    public function GetValue()
    {
        return $this->GetInnerField()->GetValue();
    }

    /**
     * @param Renderer $renderer
     * @return void
     */
    public function Accept($renderer)
    {
        $renderer->RenderDivTagViewColumnDecorator($this);
    }

    public function GetAlign()
    {
        return $this->Align;
    }
}

class CheckBoxFormatValueViewColumnDecorator extends CustomFormatValueViewColumnDecorator
{
    private $trueValue;
    private $falseValue;

    public function GetValue()
    {
        $value = $this->GetInnerField()->GetDataset()->GetFieldValueByName($this->GetName());
        if (!isset($value))
            return $this->GetInnerFieldValue();
        else if (empty($value))
                return '<input type="checkbox" onclick="return false;">';
            else
                return '<input type="checkbox" checked="checked" onclick="return false;">';
    }

    public function SetDisplayValues($trueValue, $falseValue)
    {
        $this->trueValue = $trueValue;
        $this->falseValue = $falseValue;
    }

    public function GetTrueValue()
    { return $this->trueValue; }
    public function GetFalseValue()
    { return $this->falseValue; }

    /**
     * @param Renderer $renderer
     * @return void
     */
    public function Accept($renderer)
    {
        $renderer->RenderCheckBoxViewColumn($this);
    }

    public function IsDataColumn()
    { return false; }
}

class NumberFormatValueViewColumnDecorator extends CustomFormatValueViewColumnDecorator
{
    private $numberAfterDecimal;
    private $thousandsSeparator;
    private $decimalSeparator;

    public function __construct($innerField, $numberAfterDecimal, $thousandsSeparator, $decimalSeparator)
    {
        parent::__construct($innerField);
        $this->numberAfterDecimal = $numberAfterDecimal;
        $this->thousandsSeparator = $thousandsSeparator;
        $this->decimalSeparator = $decimalSeparator;
    }

    protected function GetNumberAfterDecimal()
    { return $this->numberAfterDecimal; }

    public function GetValue()
    {
        if (!$this->IsNull())
            return number_format(
                (double)$this->GetInnerFieldValue(),
                $this->numberAfterDecimal,
                $this->decimalSeparator,
                $this->thousandsSeparator);
        else
            return $this->GetInnerFieldValue();
    }
}

class CurrencyFormatValueViewColumnDecorator extends NumberFormatValueViewColumnDecorator
{
    private $currencySign;

    public function __construct($innerField, $numberAfterDecimal, $thousandsSeparator, $decimalSeparator, $currencySign = '$')
    {
        parent::__construct($innerField, $numberAfterDecimal, $thousandsSeparator, $decimalSeparator);
        $this->currencySign = $currencySign;
    }

    public function GetValue()
    {
        if (!$this->IsNull())
            return $this->currencySign . parent::GetValue();
        else
            return $this->GetInnerFieldValue();
    }
}

class StringFormatValueViewColumnDecorator extends CustomFormatValueViewColumnDecorator
{
    private $stringTransaformFunction;

    private function TransformString($string)
    {
        if (function_exists($this->stringTransaformFunction))
            return call_user_func($this->stringTransaformFunction, $string);
        else
            return $string;
    }

    public function __construct($innerField, $stringTransaformFunction)
    {
        parent::__construct($innerField);
        $this->stringTransaformFunction = $stringTransaformFunction;
    }

    public function GetValue()
    {
        return $this->TransformString($this->GetInnerFieldValue());
    }
}

class PercentFormatValueViewColumnDecorator extends NumberFormatValueViewColumnDecorator
{
    public function GetValue()
    {
        return parent::GetValue() . '%';
    }
}   

class ExtendedHyperLinkColumnDecorator extends CustomFormatValueViewColumnDecorator
{
    private $template;
    private $target;
    private $dataset;
    
    public function __construct($innerField, $dataset, $template, $target = '_blank')
    {
        parent::__construct($innerField);
        $this->template = $template;
        $this->target = $target;
        $this->dataset = $dataset;
    }

    public function GetLink()
    {
        return FormatDatasetFieldsTemplate($this->dataset, $this->template);
    }

    public function GetTarget()
    {
        return $this->target;
    }

    // TODO: delete
    public function GetValue()
    {
        return sprintf('<a href="%s" target="%s">%s</a>',
            $this->GetLink(),
            $this->target,
            $this->GetInnerFieldValue()
        );
    }

    /**
     * @param Renderer $renderer
     * @return void
     */
    public function Accept($renderer)
    {
        $renderer->RenderExtendedHyperLinkColumnDecorator($this);
    }
}

class DownloadDataColumn extends CustomViewColumn
{
    private $dataset;
    private $fieldName;
    private $linkInnerHtml;

    public function __construct($fieldName, $caption, $dataset, $linkInnerHtml = 'download')
    {
        parent::__construct($caption);
        $this->fieldName = $fieldName;
        $this->dataset = $dataset;
        $this->linkInnerHtml = $linkInnerHtml;
    }

    public function GetName()
    { return $this->fieldName; }

    /**
     * @return Dataset
     */
    public function GetDataset() { return $this->dataset; }

    public function GetData()
    {
        return $this->GetDataset()->GetFieldValueByName($this->fieldName);
    }

    public function GetValue() { return $this->GetData(); }
    public function GetLinkInnerHtml() { return $this->linkInnerHtml; }

    public function GetDownloadLink()
    {
        $result = $this->GetGrid()->CreateLinkBuilder();
        $result->AddParameter('hname', $this->fieldName . '_handler');
        AddPrimaryKeyParameters($result, $this->GetDataset()->GetPrimaryKeyValues());
        return $result->GetLink();
    }

    /**
     * @param Renderer $renderer
     * @return void
     */
    public function Accept($renderer)
    {
        $renderer->RenderDownloadDataColumn($this);
    }

    public function IsDataColumn()
    { return false; }
}

class DownloadExternalDataColumn extends  CustomViewColumn
{
    private $fieldName;
    private $dataset;
    private $downloadTextTemplate;
    private $downloadLinkHintTemplate;

    private $sourcePrefix;
    private $sourceSuffix;
    private $captions;


    public function __construct($fieldName, $caption, $dataset, $downloadTextTemplate, Captions $captions, $downloadLinkHintTemplate = '')
    {
        parent::__construct($caption);
        $this->fieldName = $fieldName;
        $this->dataset = $dataset;
        $this->downloadTextTemplate = $downloadTextTemplate;
        $this->downloadLinkHintTemplate = $downloadLinkHintTemplate;
        $this->captions = $captions;
    }

    public function GetName()
    { return $this->fieldName; }

    /**
     * @return Dataset
     */
    public function GetDataset() { return $this->dataset; }

    public function GetData()
    {
        return $this->GetDataset()->GetFieldValueByName($this->fieldName);
    }

    public function SetSourcePrefix($value) { $this->sourcePrefix = $value; }
    public function GetSourcePrefix() { return $this->sourcePrefix; }

    public function SetSourceSuffix($value) { $this->sourceSuffix = $value; }
    public function GetSourceSuffix() { return $this->sourceSuffix; }

    public function GetValue()
    {
        $fieldValue = $this->GetDataset()->GetFieldValueByName($this->fieldName);
        if ($fieldValue == null)
            return '<em class="pgui-null-value">NULL</em>';
        else
            return StringUtils::Format(
                    '<i class="pg-icon-download"></i>&nbsp;' .
                    '<a target="_blank" title="%s" href="%s">%s</a>',
                    FormatDatasetFieldsTemplate($this->dataset, $this->downloadLinkHintTemplate),
                    $this->sourcePrefix . $fieldValue . $this->sourceSuffix,
                    $this->captions->GetMessageString('Download')
                );
    }
}

class ExternalImageColumn extends  CustomViewColumn
{
    private $fieldName;
    private $dataset;
    private $hintTemplate;
    private $sourcePrefix;
    private $sourceSuffix;

    public function __construct($fieldName, $caption, $dataset, $hintTemplate)
    {
        parent::__construct($caption);
        $this->fieldName = $fieldName;
        $this->dataset = $dataset;
        $this->hintTemplate = $hintTemplate;
        $this->sourcePrefix = '';
        $this->sourceSuffix = '';
    }

    public function GetFieldName() { return $this->fieldName; }

    public function SetSourcePrefix($value) { $this->sourcePrefix = $value; }
    public function GetSourcePrefix() { return $this->sourcePrefix; }

    public function SetSourceSuffix($value) { $this->sourceSuffix = $value; }
    public function GetSourceSuffix() { return $this->sourceSuffix; }

    public function GetName() { return $this->fieldName; }

    /**
     * @return Dataset
     */
    public function GetDataset() { return $this->dataset; }

    public function GetData()
    {
        return $this->GetDataset()->GetFieldValueByName($this->fieldName);
    }

    public function GetValue()
    {
        $fieldValue = $this->GetDataset()->GetFieldValueByName($this->fieldName);
        if ($fieldValue == null)
            return '<em class="pgui-null-value">NULL</em>';
        else
            return sprintf('<img src="%s" alt="%s">',
                $this->sourcePrefix . $fieldValue . $this->sourceSuffix,
                FormatDatasetFieldsTemplate($this->dataset, $this->hintTemplate));
    }
}

class ExternalAudioFileColumn extends ExternalImageColumn
{
    public function GetValue()
    {
        $fieldValue = $this->GetDataset()->GetFieldValueByName($this->GetFieldName());
        if ($fieldValue == null)
            return '<em class="pgui-null-value">NULL</em>';
        else
            return '<audio controls>' .
                   ' <source src="' . $this->GetSourcePrefix() . $fieldValue . $this->GetSourceSuffix() . '" type="audio/mpeg">' .
                   ' Your browser does not support the audio element.' .
                   '</audio>';
    }
}

class ImageViewColumn extends CustomViewColumn
{
    private $dataset;
    private $fieldName;
    private $imageHintTemplate;
    private $enablePictureZoom;
    private $handlerName;

    public function __construct($fieldName, $caption, $dataset, $enablePictureZoom = true, $handlerName)
    {
        parent::__construct($caption);
        $this->fieldName = $fieldName;
        $this->dataset = $dataset;
        $this->imageHintTemplate = null;
        $this->enablePictureZoom = $enablePictureZoom;
        $this->handlerName = $handlerName;
    }

    public function GetName()
    { return $this->fieldName; }

    /**
     * @return Dataset
     */
    public function GetDataset() { return $this->dataset; }

    public function GetData()
    {
        return $this->GetDataset()->GetFieldValueByName($this->fieldName);
    }

    public function GetValue() { return $this->GetData(); }

    public function GetEnablePictureZoom() { return $this->enablePictureZoom; }
    public function SetEnablePictureZoom($value) { $this->enablePictureZoom = $value; }
    public function SetImageHintTemplate($value) { $this->imageHintTemplate = $value; }
    public function GetImageHintTemplate() { return $this->imageHintTemplate; }

    public function GetImageLink()
    {
        $result = $this->GetGrid()->CreateLinkBuilder();
        $result->AddParameter('hname', $this->handlerName);
        AddPrimaryKeyParameters($result, $this->GetDataset()->GetPrimaryKeyValues());
        return $result->GetLink();
    }

    public function GetFullImageLink()
    {
        $result = $this->GetGrid()->CreateLinkBuilder();
        $result->AddParameter('hname', $this->handlerName);
        $result->AddParameter('large', '1');
        AddPrimaryKeyParameters($result, $this->GetDataset()->GetPrimaryKeyValues());
        return $result->GetLink();
    }

    public function GetImageHint()
    {
        if (isset($this->imageHintTemplate))
            return FormatDatasetFieldsTemplate($this->dataset, $this->imageHintTemplate);
        else
            return $this->GetCaption();
    }

    /**
     * @param Renderer $renderer
     * @return void
     */
    public function Accept($renderer)
    {
        $renderer->RenderImageViewColumn($this);
    }

    public function IsDataColumn()
    { return false; }
}

class DetailColumn extends CustomViewColumn
{
    private $masterKeyFields;
    private $separatePageHandlerName;
    private $inlinePageHandlerName;
    private $dataset;
    private $name;
    private $frameRandomNumber;

    public function __construct($masterKeyFields, $name, $separatePageHandlerName, $inlinePageHandlerName, Dataset $dataset, $caption, $tabCaption)
    {
        parent::__construct($caption);
        $this->masterKeyFields = $masterKeyFields;
        $this->name = $name;
        $this->separatePageHandlerName = $separatePageHandlerName;
        $this->inlinePageHandlerName = $inlinePageHandlerName;
        $this->dataset = $dataset;
        $this->frameRandomNumber = Random::GetIntRandom();
        $this->dataset->OnNextRecord->AddListener('NextRecordHandler', $this);
        $this->tabCaption = $tabCaption;
    }

    public function GetSeparatePageHandlerName() {
        return $this->separatePageHandlerName;
    }

    public function NextRecordHandler($sender)
    {
        $this->frameRandomNumber = Random::GetIntRandom();
    }

    public function GetName()
    { return ''; }
    public function GetDataset()
    { return $this->dataset; }
    public function GetData()
    { return null; }

    private function GetDetailsControlSuffix()
    {
        return $this->frameRandomNumber;
    }

    public function GetLink()
    {
        $linkBuilder = $this->GetGrid()->CreateLinkBuilder();
        $linkBuilder->AddParameter('detailrow', 'DetailContent_' . $this->name . '_' . $this->GetDetailsControlSuffix());
        $linkBuilder->AddParameter('hname', $this->inlinePageHandlerName);
        for($i = 0; $i < count($this->masterKeyFields); $i++)
            $linkBuilder->AddParameter('fk' . $i, $this->GetDataset()->GetFieldValueByName($this->masterKeyFields[$i]));
        return $linkBuilder->GetLink();
    }


    public function DecorateLinkForPostMasterRecord(LinkBuilder $linkBuilder) {
        $linkBuilder->AddParameter('details-redirect', $this->separatePageHandlerName);
    }

    public function GetSeparateViewLink()
    {
        $linkBuilder = $this->GetGrid()->CreateLinkBuilder();
        $linkBuilder->AddParameter('hname', $this->separatePageHandlerName);
        for($i = 0; $i < count($this->masterKeyFields); $i++)
            $linkBuilder->AddParameter('fk' . $i, $this->GetDataset()->GetFieldValueByName($this->masterKeyFields[$i]));
        return $linkBuilder->GetLink();
    }

    public function GetAfterRowControl()
    {
        return new CustomHtmlControl(
        '<iframe class="hidden"' .
            ' id="DetailFrame_' . $this->name . '_' . $this->GetDetailsControlSuffix() . '"' .
            ' name="DetailFrame_' . $this->name . '_' . $this->GetDetailsControlSuffix() . '"' .
            ' style="width:100%"></iframe>' .
            '<div class="hidden" id="DetailContent_' . $this->name . '_' . $this->GetDetailsControlSuffix() . '"></div>'
        );
    }

    public function GetValue()
    {
        return
        '<a class="page_link" onclick="expand(' .
            '\'DetailFrame_' . $this->name . '_' . $this->GetDetailsControlSuffix() . '\', ' .
            '\'DetailContent_' . $this->name . '_' . $this->GetDetailsControlSuffix() . '\', ' .
            '\'ExpandImage_' . $this->name . '_' . $this->GetDetailsControlSuffix() . '\', ' .
            'this);" href="' . $this->GetLink() . '">'.
            '<img id="ExpandImage_' . $this->name . '_' . $this->GetDetailsControlSuffix() . '" src="images/expand.gif" class="collapsed">' .
            '</a>&nbsp;' .
            '<a class="page_link" href="' . $this->GetSeparateViewLink() . '">' . $this->GetCaption() . '</a>';
    }

    public function GetViewData() {
        $result = array(
            'caption' => $this->GetCaption(),
            'tabCaption' => $this->tabCaption,
            'gridLink' => $this->GetLink(),
            'SeperatedPageLink' => $this->GetSeparateViewLink(),
            'detailId' => 'detail-' . $this->GetDetailsControlSuffix()
        );
        return $result;
    }
}
