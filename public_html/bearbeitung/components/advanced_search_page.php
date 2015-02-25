<?php
// Processed by afterburner.sh



// require_once 'phpgen_settings.php';
// require_once 'components/utils/string_utils.php';
// require_once 'components/superglobal_wrapper.php';
// require_once 'components/renderers/renderer.php';
// require_once 'components/editors/editors.php';

include_once dirname(__FILE__) . '/' . '../phpgen_settings.php';
include_once dirname(__FILE__) . '/' . 'utils/string_utils.php';
include_once dirname(__FILE__) . '/' . 'superglobal_wrapper.php';
include_once dirname(__FILE__) . '/' . 'renderers/renderer.php';
include_once dirname(__FILE__) . '/' . 'editors/editors.php';


/** @var SearchFilterOperator[] $operators  */
$operators = array();

class SearchFilterOperator {
    private $name;
    private $imageClass;
    private $captionId;

    public function __construct($name, $imageClass, $captionId) {
        $this->name = $name;
        $this->imageClass = $imageClass;
        $this->captionId = $captionId;
    }

    public function GetImageClass() {
        return $this->imageClass;
    }

    public function GetName() {
        return $this->imageClass;
    }

    public function GetViewData(Captions $localizer) {
        return array(
            'Name' => $this->name,
            'ImageClass' => $this->GetImageClass(),
            'Caption' => $localizer->GetMessageString($this->captionId)
        );
    }

    /**
     * @param $name
     * @return mixed
     */
    public static function GetOperatorByName($name) {
        global $operators;
        return $operators[$name];
    }


    public static function GetOperatorImageClassByName($name) {
        global $operators;
        return $operators[$name]->GetImageClass();
    }
}

$operators['']      = new SearchFilterOperator('', '', 'None');
$operators['LIKE']      = new SearchFilterOperator('LIKE', 'pg-icon-operator-is-like', 'Like');
$operators['NOT-LIKE']      = new SearchFilterOperator('NOT-LIKE', 'pg-icon-operator-is-not-like', 'NotLike');
$operators['STARTS']    = new SearchFilterOperator('STARTS', 'pg-icon-operator-begins-with', 'StartsWith');
$operators['ENDS']      = new SearchFilterOperator('ENDS', 'pg-icon-operator-ends-with', 'EndsWith');
$operators['CONTAINS']  = new SearchFilterOperator('CONTAINS', 'pg-icon-operator-contains', 'Contains');
$operators['NOT-CONTAINS']  = new SearchFilterOperator('NOT-CONTAINS', 'pg-icon-operator-does-not-contain', 'NotContains');
$operators['IS NULL']   = new SearchFilterOperator('IS NULL', 'pg-icon-operator-is-blank', 'isBlank');
$operators['IS NOT NULL']   = new SearchFilterOperator('IS NOT NULL', 'pg-icon-operator-is-not-blank', 'isNotBlank');
$operators['between']   = new SearchFilterOperator('between', 'pg-icon-operator-contains', 'between');
$operators['=']         = new SearchFilterOperator('=', 'pg-icon-operator-equals', 'equals');
$operators['<>']        = new SearchFilterOperator('<>', 'pg-icon-operator-does-not-equal', 'doesNotEquals');
$operators['>']         = new SearchFilterOperator('>', 'pg-icon-operator-is-greater-than', 'isGreaterThan');
$operators['>=']        = new SearchFilterOperator('>=', 'pg-icon-operator-is-greater-than-or-equal-to', 'isGreaterThanOrEqualsTo');
$operators['<']         = new SearchFilterOperator('<', 'pg-icon-operator-is-less-than', 'isLessThan');
$operators['<=']        = new SearchFilterOperator('<=', 'pg-icon-operator-is-less-than-or-equal-to', 'isLessThanOrEqualsTo');


abstract class SearchColumn {
    private $fieldName;
    private $editorControl;
    private $secondEditorControl;
    private $caption;
    private $superGlobals;
    private $variableContainer;
    
    private $applyNotOperator;
    private $filterIndex;
    private $firstValue;
    private $secondValue;

    /** @var Captions */
    protected $localizerCaptions;

    public function GetValue() {
        return $this->firstValue;
    }

    protected function GetApplyNotOperator()
    {
        return $this->applyNotOperator;
    }

    /*afterburner*/ public function SetApplyNotOperator($value)
    {
        $this->applyNotOperator = $value;
    }

    public function GetFilterIndex()
    {
        return $this->filterIndex;
    }
    
    /*afterburner*/ public function SetFilterIndex($value)
    {
        $this->filterIndex = $value;
    }

    public function __construct($fieldName, $caption, $stringLocalizer, SuperGlobals $superGlobals,
        IVariableContainer $variableContainer)
    {
        $this->fieldName = $fieldName;
        $this->localizerCaptions = $stringLocalizer;
        $this->editorControl = $this->CreateEditorControl();
        $this->secondEditorControl = $this->CreateSecondEditorControl();
        $this->caption = $caption;
        $this->superGlobals = $superGlobals;
        $this->variableContainer = $variableContainer;
    }

    public function GetCaption()
    {
        return $this->caption;
    }
    
    public function SetCaption($value)
    {
        $this->caption = $value;
    }

    protected abstract function CreateEditorControl();
    protected abstract function CreateSecondEditorControl();

    protected abstract function SetEditorControlValue($value);
    protected abstract function SetSecondEditorControlValue($value);

    public function GetFieldName()
    {
        return $this->fieldName;
    }

    public function GetAvailableFilterTypes()
    {
        return array();
    }

    public final function GetAvailableFilterTypesViewData()
    {
        $result = array();
        foreach($this->GetAvailableFilterTypes() as $filterName => $caption) {
            $result[] = SearchFilterOperator::GetOperatorByName($filterName)->GetViewData($this->localizerCaptions);
        }
        return $result;
    }

    public function GetActiveFilterType()
    {
        return '';
    }

    /**
     * @return CustomEditor
     */
    public function GetEditorControl()
    {
        return $this->editorControl;
    }

    /**
     * @return CustomEditor
     */
    public function GetSecondEditorControl()
    {
        return $this->secondEditorControl;
    }

    public function ExtractSearchValuesFromSession()
    {
        if (
            $this->superGlobals->IsSessionVariableSet('not_' . $this->GetFieldName()) &&
            $this->superGlobals->IsSessionVariableSet('filtertype_' . $this->GetFieldName())
        )
        {
            $this->applyNotOperator = $this->superGlobals->GetSessionVariable('not_' . $this->GetFieldName());
            $this->filterIndex = $this->superGlobals->GetSessionVariable('filtertype_' . $this->GetFieldName());
            $this->firstValue = $this->superGlobals->GetSessionVariable($this->GetEditorControl()->GetName());
            $this->secondValue = $this->superGlobals->GetSessionVariable($this->GetSecondEditorControl()->GetName());

            $this->SetEditorControlValue($this->firstValue);
            $this->SetSecondEditorControlValue($this->secondValue);
        }
    }

    /*afterburner*/ public function SaveSearchValuesToSession()
    {
        $this->superGlobals->SetSessionVariable('not_' . $this->GetFieldName(), $this->applyNotOperator);
        $this->superGlobals->SetSessionVariable('filtertype_' . $this->GetFieldName(), $this->filterIndex);
        $this->superGlobals->SetSessionVariable($this->GetEditorControl()->GetName(), $this->firstValue);
        $this->superGlobals->SetSessionVariable($this->GetSecondEditorControl()->GetName(), $this->secondValue);
    }

    private function ResetSessionValues()
    {
        $this->superGlobals->UnSetSessionVariable('not_' . $this->GetFieldName());
        $this->superGlobals->UnSetSessionVariable('filtertype_' . $this->GetFieldName());
        $this->superGlobals->UnSetSessionVariable($this->GetEditorControl()->GetName());
        $this->superGlobals->UnSetSessionVariable($this->GetSecondEditorControl()->GetName());
    }

    public function GetFilterTypeInputName()
    {
        return 'filtertype_' . 
            StringUtils::ReplaceIllegalPostVariableNameChars($this->GetFieldName());
    }

    public function GetNotMarkInputName()
    {
        return 'not_' . 
            StringUtils::ReplaceIllegalPostVariableNameChars($this->GetFieldName());
    }

    public function ExtractSearchValuesFromPost()
    {
        $valueChanged = true;
        $this->applyNotOperator = GetApplication()->GetSuperGlobals()->IsPostValueSet($this->GetNotMarkInputName());
        $this->filterIndex = GetApplication()->GetSuperGlobals()->GetPostValueDef($this->GetFilterTypeInputName(), '');
        $this->firstValue = $this->GetEditorControl()->ExtractsValueFromPost($valueChanged);
        $this->secondValue = $this->GetSecondEditorControl()->ExtractsValueFromPost($valueChanged);

        $this->SaveSearchValuesToSession();

        $this->SetEditorControlValue($this->firstValue);
        $this->SetSecondEditorControlValue($this->secondValue);
    }

    public function ResetFilter()
    {
        $this->applyNotOperator = null;
        $this->filterIndex = null;
        $this->firstValue = null;
        $this->secondValue = null;

        $this->ResetSessionValues();
    }

    public function GetFilterForField()
    {
        $result = null;
        $filter = null;
        if ($this->DoGetFilterForField($filter))
            $result = $filter;
        if (!isset($result) && isset($this->firstValue) && $this->firstValue != '')
        {
            // Between is not supported in the Filter Row
            /*
            if ($this->filterIndex == 'between')
                $result = new BetweenFieldFilter(
                    EnvVariablesUtils::EvaluateVariableTemplate($this->variableContainer, $this->firstValue),
                    EnvVariablesUtils::EvaluateVariableTemplate($this->variableContainer, $this->secondValue));
            */
            if ($this->filterIndex == 'IS NOT NULL')
                $result = new NotPredicateFilter(new IsNullFieldFilter());
            elseif ($this->filterIndex == 'STARTS')
                $result = $this->createFieldFilter('ILIKE', false, true);
            elseif ($this->filterIndex == 'NOT-LIKE')
                $result = new NotPredicateFilter($this->createFieldFilter('ILIKE', false, false));
            elseif ($this->filterIndex == 'ENDS')
                $result = $this->createFieldFilter('ILIKE', true, false);
            elseif ($this->filterIndex == 'CONTAINS')
                $result = $this->createFieldFilter('ILIKE', true, true);
            elseif ($this->filterIndex == 'NOT-CONTAINS')
                $result = new NotPredicateFilter($this->createFieldFilter('ILIKE', true, true));
            else
                $result = $this->createFieldFilter($this->filterIndex);
        }
        if (isset($result) && $this->applyNotOperator)
            $result = new NotPredicateFilter($result);
        return $result;
    }

    private function createFieldFilter($condition, $usePrefix = false, $useSuffix = false){
        $filterStr = EnvVariablesUtils::EvaluateVariableTemplate(
            $this->variableContainer, $this->getFilterValueForDataset());
        if ($usePrefix)
            $filterStr = '%'.$filterStr;
        if ($useSuffix)
            $filterStr = $filterStr.'%';
        return new FieldFilter($filterStr, $condition);
    }

    protected function DoGetFilterForField(&$filter)
    {
        $filter = null;
        return false;
    }

    protected function GetValueForUserFriendlyCondition($originalValue)
    {
        return $originalValue;
    }

    protected function getFilterValueForDataset() {
        return $this->firstValue;
    }

    public function GetUserFriendlyCondition()
    {
        $result = '';
        $filterTypes = $this->GetAvailableFilterTypes();
        if (isset($this->firstValue) && $this->firstValue != '')
        {
            if ($this->filterIndex == 'between')
                $result = sprintf("between <strong>%s</strong> and <strong>%s</strong>",
                        $this->GetValueForUserFriendlyCondition($this->firstValue),
                        $this->GetValueForUserFriendlyCondition($this->secondValue));
            else
                $result = $filterTypes[$this->filterIndex] . ' ' . '<b>'.
                        $this->GetValueForUserFriendlyCondition($this->firstValue)
                        .'</b>';
            if ($this->applyNotOperator)
                $result = $this->localizerCaptions->GetMessageString('Not') . ' (' . $result . ')';
        }
        return $result;
    }

    public function IsFilterActive()
    {
        $result = false;
        if (isset($this->filterIndex))
        {
            $result = isset($this->firstValue) && $this->firstValue != '';
            if ($this->filterIndex == 'between')
                $result = $result && isset($this->secondValue) && $this->secondValue != '';
        }
        return $result;
    }

    public function GetActiveFilterIndex()
    {
        return $this->filterIndex;
    }

    public function GetFilterValue()
    {
        return $this->firstValue;
    }

    public function IsApplyNotOperator()
    {
        return $this->applyNotOperator;
    }

    public function GetLocalizerCaptions()
    {
        return $this->localizerCaptions;
    }

}

class BlobSearchColumn extends SearchColumn {
    protected function CreateEditorControl()
    {
        return new NullComponent($this->GetFieldName() . '_value');
    }

    protected function CreateSecondEditorControl()
    {
        return new NullComponent($this->GetFieldName() . '_secondvalue');
    }

    protected function SetEditorControlValue($value)
    { }
    
    protected function SetSecondEditorControlValue($value)
    { }

    public function GetAvailableFilterTypes()
    {
        return array(
            '' => '',
            'IS NULL' => $this->localizerCaptions->GetMessageString('isBlank'),
            'IS NOT NULL' => $this->localizerCaptions->GetMessageString('isNotBlank')
            );
    }

    public function IsFilterActive()
    {
        return $this->GetFilterIndex() != '';
    }

    public function GetFilterForField()
    {
        $result = null;
        if ($this->GetFilterIndex() != '')
        {
            if ($this->GetFilterIndex() == 'IS NULL')
                $result = new IsNullFieldFilter();
            elseif ($this->GetFilterIndex() == 'IS NOT NULL')
                $result = new NotPredicateFilter(new IsNullFieldFilter());
            if ($this->GetApplyNotOperator())
                $result = new NotPredicateFilter($result);
        }
        return $result;
    }

}

class StringSearchColumn extends SearchColumn {
    protected function CreateEditorControl()
    {
        return new TextEdit(
            StringUtils::ReplaceIllegalPostVariableNameChars($this->GetFieldName()) .
            '_value');
    }

    protected function CreateSecondEditorControl()
    {
        return new TextEdit(
            StringUtils::ReplaceIllegalPostVariableNameChars($this->GetFieldName()) .
            '_secondvalue');
    }

    public function IsFilterActive()
    {
        if ($this->GetFilterIndex() == 'IS NULL' || $this->GetFilterIndex() == 'IS NOT NULL')
            return true;
        else
            return parent::IsFilterActive();
    }

    protected function SetEditorControlValue($value)
    {
        $this->GetEditorControl()->SetValue($value);
    }

    protected function SetSecondEditorControlValue($value)
    {
        $this->GetSecondEditorControl()->SetValue($value);
    }

    protected function DoGetFilterForField(&$filter)
    {
        if ($this->GetFilterIndex() == 'IS NULL')
        {
            $filter = new IsNullFieldFilter();		
            return true;
        }
        else if ($this->GetFilterIndex() == 'IS NOT NULL')
        {
            $filter = new NotPredicateFilter(new IsNullFieldFilter());
            return true;
        }
        $filter = null;
        return false;
    }

    public function GetUserFriendlyCondition()
    {
        if ($this->GetFilterIndex() == 'IS NULL')
        {
            $result = sprintf('is blank');
            if ($this->GetApplyNotOperator())
                $result = $this->localizerCaptions->GetMessageString('Not') . ' (' . $result . ')';
            return $result;
        }
        else
            return parent::GetUserFriendlyCondition();
    }

    public function GetAvailableFilterTypes()
    {
        return array(
            'CONTAINS' => $this->localizerCaptions->GetMessageString('Contains'),
            'NOT-CONTAINS' => $this->localizerCaptions->GetMessageString('NotContains'),
            'LIKE' => $this->localizerCaptions->GetMessageString('Like'),
            'NOT-LIKE' => $this->localizerCaptions->GetMessageString('NotLike'),
            'STARTS' => $this->localizerCaptions->GetMessageString('StartsWith'),
            'ENDS' => $this->localizerCaptions->GetMessageString('EndsWith'),
            'IS NULL' => $this->localizerCaptions->GetMessageString('isBlank'),
            'IS NOT NULL' => $this->localizerCaptions->GetMessageString('isNotBlank'),
            '='  => $this->localizerCaptions->GetMessageString('equals'),
            '<>' => $this->localizerCaptions->GetMessageString('doesNotEquals'),
            '>'  => $this->localizerCaptions->GetMessageString('isGreaterThan'),
            '>=' => $this->localizerCaptions->GetMessageString('isGreaterThanOrEqualsTo'),
            '<'  => $this->localizerCaptions->GetMessageString('isLessThan'),
            '<=' => $this->localizerCaptions->GetMessageString('isLessThanOrEqualsTo')
            );
    }
}

class DateTimeSearchColumn extends StringSearchColumn {

    /** @var string */
    private $format;

    protected function CreateEditorControl()
    {
        return new DateTimeEdit(
            StringUtils::ReplaceIllegalPostVariableNameChars($this->GetFieldName()) .
            '_value', false, $this->format);
    }

    protected function CreateSecondEditorControl()
    {
        return new DateTimeEdit(
            StringUtils::ReplaceIllegalPostVariableNameChars($this->GetFieldName()) . 
            '_secondvalue', false, $this->format);
    }

    public function GetAvailableFilterTypes()
    {
        return array(
            '='  => $this->localizerCaptions->GetMessageString('equals'),
            '<>' => $this->localizerCaptions->GetMessageString('doesNotEquals'),
            'IS NULL' => $this->localizerCaptions->GetMessageString('isBlank'),
            'IS NOT NULL' => $this->localizerCaptions->GetMessageString('isNotBlank'),
            '>'  => $this->localizerCaptions->GetMessageString('isGreaterThan'),
            '>=' => $this->localizerCaptions->GetMessageString('isGreaterThanOrEqualsTo'),
            '<'  => $this->localizerCaptions->GetMessageString('isLessThan'),
            '<=' => $this->localizerCaptions->GetMessageString('isLessThanOrEqualsTo')
            );
    }

    public function __construct($fieldName, $caption, $stringLocalizer, SuperGlobals $superGlobals,
                                IVariableContainer $variableContainer, $format) {
        $this->format = $format;
        parent::__construct($fieldName, $caption, $stringLocalizer, $superGlobals, $variableContainer);
    }

    public function getFilterValueForDataset()
    {
        return SMDateTime::Parse(parent::getFilterValueForDataset(), $this->format);
    }

    public function GetOSDateTimeFormat()
    {
        return DateFormatToOSFormat($this->format);
    }
}

class LookupSearchColumn extends StringSearchColumn {
    /** @var LinkBuilder */
    private $linkBuilder;
    /** @var Dataset */
    private $lookupDataset;
    /** @var string */
    private $idColumn;
    /** @var string */
    private $valueColumn;
    /** @var string */
    private $handlerName;
    /** @var boolean */
    private $useComboBox;
    /** @var int  */
    private $itemCount = 0;

    public function __construct($fieldName, $caption, $stringLocalizer,
        SuperGlobals $superGlobals,
        IVariableContainer $variableContainer,
        LinkBuilder $linkBuilder,
        Dataset $lookupDataset,
        $idColumn, $valueColumn, $useComboBox = false, $itemCount = 0/*, $lookupFieldName*/)
    {
        //$this->lookupFieldName = $lookupFieldName;
        $this->linkBuilder = $linkBuilder;
        $this->lookupDataset = $lookupDataset;
        $this->idColumn = $idColumn;
        $this->valueColumn = $valueColumn;
        $this->useComboBox = $useComboBox;
        $this->itemCount = $itemCount;
        $this->handlerName = StringUtils::ReplaceIllegalPostVariableNameChars($fieldName . '_advanced_search_lookup_handler');
        parent::__construct($fieldName, $caption, $stringLocalizer, $superGlobals, $variableContainer);

        GetApplication()->RegisterHTTPHandler(
            new LookupSearchColumnDataHandler($lookupDataset, $this->handlerName, $idColumn, $valueColumn, $itemCount)
        );

    }

    public function getItemCount() {
        return $this->itemCount;
    }

    public function GetHandlerName() {
        return $this->handlerName;
    }

    protected function CreateEditorControl()
    {
        $controlName = StringUtils::ReplaceIllegalPostVariableNameChars($this->GetFieldName()) . '_value';
        if (!$this->useComboBox)
        {
            $result = new AutocomleteComboBox($controlName, $this->linkBuilder);
            $result->SetHandlerName($this->handlerName);
            $result->SetSize('155px');
        }
        else
        {
            $result = new ComboBox($controlName, $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));

            $this->lookupDataset->Open();
            while ($this->lookupDataset->Next())
            {
                $result->AddValue(
                    $this->lookupDataset->GetFieldValueByName($this->idColumn),
                    $this->lookupDataset->GetFieldValueByName($this->valueColumn)
                );
            }
            $this->lookupDataset->Close();
        }
        return $result;
    }

    protected function CreateSecondEditorControl()
    {
        $controlName = StringUtils::ReplaceIllegalPostVariableNameChars($this->GetFieldName()) . '_secondvalue';
        if (!$this->useComboBox)
        {
            $result = new AutocomleteComboBox($controlName, $this->linkBuilder);
            $result->SetHandlerName($this->handlerName);
            $result->SetSize('155px');
        }
        else
        {
            $result = new ComboBox($controlName, $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));

            $this->lookupDataset->Open();
            while ($this->lookupDataset->Next())
            {
                $result->AddValue(
                    $this->lookupDataset->GetFieldValueByName($this->idColumn),
                    $this->lookupDataset->GetFieldValueByName($this->valueColumn)
                );
            }
            $this->lookupDataset->Close();
        }
        return $result;
    }

    private function GetDisplayValueById($idValue)
    {
        $result = '';
        $this->lookupDataset->AddFieldFilter($this->idColumn, new FieldFilter($idValue, '='));
        $this->lookupDataset->Open();
        if ($this->lookupDataset->Next())
        {
            $result = $this->lookupDataset->GetFieldValueByName($this->valueColumn);
        }
        $this->lookupDataset->Close();
        $this->lookupDataset->ClearFieldFilters();
        return $result;
    }

    public function GetDisplayValue() {

        $result = '';
        if (!$this->useComboBox)
        {
            $this->lookupDataset->AddFieldFilter($this->idColumn, new FieldFilter($this->GetValue(), '='));
            $this->lookupDataset->Open();
            if ($this->lookupDataset->Next())
            {
                $result = $this->lookupDataset->GetFieldValueByName($this->valueColumn);
            }
            $this->lookupDataset->Close();
            $this->lookupDataset->ClearFieldFilters();
        }
        return $result;
    }

    protected function SetEditorControlValue($value)
    {
        parent::SetEditorControlValue($value);

        if (!$this->useComboBox)
        {
            $this->lookupDataset->AddFieldFilter($this->idColumn, new FieldFilter($value, '='));
            $this->lookupDataset->Open();
            if ($this->lookupDataset->Next())
            {
                /** @var AutocomleteComboBox $editorControl */
                $editorControl = $this->GetEditorControl();
                $editorControl->SetDisplayValue($this->lookupDataset->GetFieldValueByName($this->valueColumn));
            }
            $this->lookupDataset->Close();
            $this->lookupDataset->ClearFieldFilters();
        }
    }

    protected function SetSecondEditorControlValue($value)
    {
        parent::SetSecondEditorControlValue($value);

        if (!$this->useComboBox)
        {
            $this->lookupDataset->AddFieldFilter($this->idColumn, new FieldFilter($value, '='));
            $this->lookupDataset->Open();
            if ($this->lookupDataset->Next())
            {
                /** @var AutocomleteComboBox $editorControl */
                $editorControl = $this->GetSecondEditorControl();
                $editorControl->SetDisplayValue($this->lookupDataset->GetFieldValueByName($this->valueColumn));
            }
            $this->lookupDataset->Close();
            $this->lookupDataset->ClearFieldFilters();
        }
    }

    protected function GetValueForUserFriendlyCondition($originalValue)
    {
        return $this->GetDisplayValueById($originalValue);
    }

    public function GetAvailableFilterTypes()
    {
        return array(
            '='  => $this->localizerCaptions->GetMessageString('equals'),
            '<>'  => $this->localizerCaptions->GetMessageString('doesNotEquals'),
            'IS NULL' => $this->localizerCaptions->GetMessageString('isBlank'),
            'IS NOT NULL' => $this->localizerCaptions->GetMessageString('isNotBlank')
            );
    }
}

class LookupSearchColumnDataHandler extends HTTPHandler {
    /** @var \Dataset */
    private $dataset;
    /** @var string */
    private $idField;
    /** @var string */
    private $valueField;
    /** @var int */
    private $itemCount;

    /**
     * @param Dataset $dataset
     * @param string $name
     * @param string $idField
     * @param string $valueField
     * @param int $itemCount
     */
    public function __construct(Dataset $dataset, $name, $idField, $valueField, $itemCount)
    {
        parent::__construct($name);
        $this->dataset = $dataset;
        $this->idField = $idField;
        $this->valueField = $valueField;
        $this->itemCount = $itemCount;
    }

    /**
     * @param Renderer $renderer
     * @return void
     */
    public function Render(Renderer $renderer)
    {
        if (GetApplication()->GetSuperGlobals()->IsGetValueSet('term'))
        {
            $this->dataset->AddFieldFilter(
                $this->valueField,
                new FieldFilter('%'.GetApplication()->GetSuperGlobals()->GetGetValue('term').'%', 'ILIKE', true)
            );
        }

        if ($this->itemCount > 0) {
            $this->dataset->SetUpLimit(0);
            $this->dataset->SetLimit($this->itemCount);
        }

        $this->dataset->Open();

        $result = array();

        $highLightCallback = Delegate::CreateFromMethod($this, 'ApplyHighlight')->Bind(array(
            Argument::$Arg3 => $this->valueField,
            Argument::$Arg4 => GetApplication()->GetSuperGlobals()->GetGetValue('term')
        ));

        while ($this->dataset->Next())
        {
            $result[] = array(
                "id" => $this->dataset->GetFieldValueByName($this->idField),
                "label" => (
                            $highLightCallback->Call(
                                $this->dataset->GetFieldValueByName($this->valueField),
                                $this->valueField
                            )
                ),
                "value" => $this->dataset->GetFieldValueByName($this->valueField)
            );
        }

        echo SystemUtils::ToJSON($result);

        $this->dataset->Close();
    }

    public function ApplyHighlight($value, $currentFieldName, $displayFieldName, $term)
    {
        if ($currentFieldName == $displayFieldName && !StringUtils::IsNullOrEmpty($term))
        {
            $patterns = array();
            $patterns[0] = '/(' . preg_quote($term) . ')/i';
            $replacements = array();
            $replacements[0] = '<em class="highlight_autocomplete">' . '$1' . '</em>';
            return preg_replace($patterns, $replacements, $value);
        }
        else
            return $value;
    }
}

class AdvancedSearchControl {
    /** @var string */
    private $name;
    /** @var Dataset */
    private $dataset;
    /** @var SearchColumn[] */
    private $columns;
    /** @var boolean */
    private $applyAndOperator;
    /** @var string */
    private $target;
    /** @var boolean */
    private $hidden;
    /** @var Captions */
    private $stringLocalizer;
    /** @var boolean */
    private $allowOpenInNewWindow;
    /** @var boolean */
    private $openInNewWindowLink;
    /** @var \IVariableContainer */
    private $variableContainer;
    /** @var \LinkBuilder */
    private $linkBuilder;
    /** @var int */
    private $timerInterval;
    
    public function __construct($name, $dataset, $stringLocalizer, IVariableContainer $variableContainer, LinkBuilder $linkBuilder)
    {
        $this->name = $name;
        $this->dataset = $dataset;
        $this->stringLocalizer = $stringLocalizer;
        //
        $this->columns = array();
        $this->applyAndOperator = null;
        $this->isActive = false;
        $this->hidden = true;
        $this->target = '';
        $this->allowOpenInNewWindow = true;
        $this->variableContainer = $variableContainer;
        $this->linkBuilder = $linkBuilder;
        $this->timerInterval = 1000;
    }

    #region Factory methods

    public function CreateLookupSearchInput($fieldName, $caption, Dataset $lookupDataset, $idColumn, $valueColumn,
                                            $useComboBox = false, $itemCount = 0/*, $lookupFieldName*/)
    {
        return new LookupSearchColumn($fieldName, $caption, $this->stringLocalizer,
            new SuperGlobals($this->name), $this->variableContainer, $this->linkBuilder->CloneLinkBuilder(),
            $lookupDataset, $idColumn, $valueColumn, $useComboBox, $itemCount/*, $lookupFieldName*/
        );
    }

    public function CreateDateTimeSearchInput($fieldName, $caption, $format)
    {
        return new DateTimeSearchColumn($fieldName, $caption, $this->stringLocalizer,
            new SuperGlobals($this->name), $this->variableContainer, $format
        );
    }

    public function CreateStringSearchInput($fieldName, $caption)
    {
        return new StringSearchColumn($fieldName, $caption, $this->stringLocalizer, 
            new SuperGlobals($this->name), $this->variableContainer
        );
    }

    public function CreateBlobSearchInput($fieldName, $caption)
    {
        return new BlobSearchColumn($fieldName, $caption, $this->stringLocalizer,
            new SuperGlobals($this->name), $this->variableContainer
        );
    }

    #endregion

    public function FindSearchColumnByName($name) {
        foreach($this->columns as $column) {
            if ($column->GetFieldName() == $name) {
                return $column;
            }
        }
        return null;
    }

    #region Options

    public function getName() { /*afterburner*/
      return $this->name;
    }

    public function GetTarget()
    {
        return $this->target;
    }

    public function SetTarget($value)
    {
        $this->target = $value;
    }

    public function SetAllowOpenInNewWindow($value)
    {
        $this->allowOpenInNewWindow = $value;
    }

    public function GetAllowOpenInNewWindow()
    {
        return $this->allowOpenInNewWindow;
    }

    public function GetOpenInNewWindowLink()
    {
        return $this->openInNewWindowLink;
    }

    public function SetOpenInNewWindowLink($value)
    {
        $this->openInNewWindowLink = $value;
    }

    public function SetHidden($value)
    {
        $this->hidden = $value;
    }

    public function GetHidden()
    {
        return $this->hidden;
    }

    /**
     * @param int $value
     */
    public function setTimerInterval($value) {
        $this->timerInterval = $value;
    }

    /**
     * @return int
     */
    public function getTimerInterval() {
        return $this->timerInterval;
    }

    #endregion

    /**
     * @param Renderer $renderer
     * @return void
     */
    public function Accept($renderer)
    {
        $renderer->RenderAdvancedSearchControl($this);
    }

    public function AddSearchColumn($column)
    {
        $this->columns[] = $column;
    }

    public function GetSearchColumns()
    {
        return $this->columns;
    }
    
    public function GetIsApplyAndOperator()
    {
        return $this->applyAndOperator;
    }

    private function ResetFilter()
    {
        foreach($this->columns as $column)
            $column->ResetFilter();
        $this->applyAndOperator = null;
        GetApplication()->UnSetSessionVariable($this->name . 'SearchType');
    }

    private function ExtractValuesFromSession()
    {
        foreach($this->columns as $column)
            $column->ExtractSearchValuesFromSession();
        $this->applyAndOperator = GetApplication()->GetSessionVariable($this->name . 'SearchType');
    }

    private function ExtractValuesFromPost()
    {
        foreach($this->columns as $column)
            $column->ExtractSearchValuesFromPost();
        $this->applyAndOperator = GetApplication()->GetPOSTValue('SearchType') == 'and';
        GetApplication()->SetSessionVariable($this->name . 'SearchType', $this->applyAndOperator);
    }

    private function ApplyFilterToDataset()
    {
        $fieldNames = array();
        $fieldFilters = array();

        foreach($this->columns as $column)
            if ($column->IsFilterActive())
            {
                $fieldNames[] = $column->GetFieldName();
                $fieldFilters[] = $column->GetFilterForField();
            }

        if (count($fieldFilters) > 0)
            $this->dataset->AddCompositeFieldFilter(
                $this->applyAndOperator ? 'AND' : 'OR',
                $fieldNames,
                $fieldFilters);
        $this->isActive = (count($fieldFilters) > 0);
    }

    public function GetUserFriendlySearchConditions()
    {
        $result = array();

        foreach($this->columns as $column)
            if ($column->IsFilterActive())
            {
                $result[] = array(
                    'Caption' => $column->GetCaption(),
                    'Condition' => $column->GetUserFriendlyCondition()
                    );
            }
        return $result;
    }

    public function IsActive()
    {
        return $this->isActive;
    }

    public function HasCondition()
    {
        if ((GetApplication()->IsPOSTValueSet('ResetFilter') && GetApplication()->GetPOSTValue('ResetFilter') == '1') || (GetApplication()->IsPOSTValueSet('operation') && GetApplication()->GetPOSTValue('operation') == 'ssearch'))
            return false;
        else
        {
            return 
                (GetApplication()->IsPOSTValueSet('SearchType') ||
                GetApplication()->IsSessionVariableSet($this->name . 'SearchType')) && $this->isActive;
        }
    }

    public function ProcessMessages()
    {
        if ((GetApplication()->IsPOSTValueSet('ResetFilter') && GetApplication()->GetPOSTValue('ResetFilter') == '1') ||
            (GetApplication()->IsPOSTValueSet('operation') && GetApplication()->GetPOSTValue('operation') == 'ssearch'))
        {
            $this->ResetFilter();
        }
        else
        {
            if (GetApplication()->IsSessionVariableSet($this->name . 'SearchType'))
                $this->ExtractValuesFromSession();

            if (GetApplication()->IsPOSTValueSet('SearchType'))
                $this->ExtractValuesFromPost();

            if (isset($this->applyAndOperator))
                $this->ApplyFilterToDataset();

        }
        return
            GetApplication()->IsPOSTValueSet('ResetFilter') ||
            (GetApplication()->IsPOSTValueSet('operation') && GetApplication()->GetPOSTValue('operation') == 'ssearch');
    }

    #region Client Highlighting
    private function IsFilterTypeAllowsHighlighting($filterType)
    {
        return in_array($filterType,
            array('LIKE', '=', 'STARTS', 'ENDS', 'CONTAINS')
            );
    }

    public function GetHighlightedFields()
    {
        $result = array();
        foreach($this->columns as $column)
            if (
                $column->IsFilterActive() &&
                    $this->IsFilterTypeAllowsHighlighting($column->GetActiveFilterIndex())
                )
                $result[] = $column->GetFieldName();
        return $result;
    }

    public function GetHighlightedFieldText()
    {
        $result = array();
        foreach($this->columns as $column)
            if ($column->IsFilterActive() && (
                ($column->GetActiveFilterIndex() == 'LIKE') ||
                ($column->GetActiveFilterIndex() == '=') ||
                ($column->GetActiveFilterIndex() == 'STARTS') ||
                ($column->GetActiveFilterIndex() == 'ENDS') ||
                ($column->GetActiveFilterIndex() == 'CONTAINS')
                ))
                $result[] = str_replace('%', '', $column->GetFilterValue());
        return $result;
    }

    public function GetHighlightedFieldOptions()
    {
        $result = array();
        foreach($this->columns as $column)
            if ($column->IsFilterActive() && (
                ($column->GetActiveFilterIndex() == 'LIKE') ||
                ($column->GetActiveFilterIndex() == '=')  ||
                ($column->GetActiveFilterIndex() == 'STARTS') ||
                ($column->GetActiveFilterIndex() == 'ENDS') ||
                ($column->GetActiveFilterIndex() == 'CONTAINS')
                ))
            {
                $trimmed = trim($column->GetFilterValue());
                if ($column->GetActiveFilterIndex() == 'LIKE')
                {
                    if ((count($trimmed) > 1) && ($trimmed[0] == '%' && $trimmed[strlen($trimmed) - 1] == '%'))
                        $result[] =  'ALL';
                    elseif ((count($trimmed) > 0) && ($trimmed[0] == '%'))
                        $result[] =  'END';
                    elseif ((count($trimmed) > 1) && ($trimmed[strlen($trimmed) - 1] == '%'))
                        $result[] =  'START';
                }
                elseif ($column->GetActiveFilterIndex() == 'STARTS')
                    $result[] = 'START';
                elseif ($column->GetActiveFilterIndex() == 'ENDS')
                    $result[] = 'END';
                elseif ($column->GetActiveFilterIndex() == 'CONTAINS')
                    $result[] = 'ALL';
                else
                    $result[] =  'ALL';
            }
        return $result;
    }
    #endregion
}
