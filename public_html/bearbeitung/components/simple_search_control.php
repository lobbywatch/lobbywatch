<?php

// require_once 'components/application.php';
include_once dirname(__FILE__) . '/' . 'application.php';

class SimpleSearch
{
    private $name;
    private $fieldsForFilter;
    private $filterTypes;
    private $dataset;
    private $fieldCaptions;

    private $activeFilterTypeName;
    private $activeFieldName;
    private $activeFilterText;
    private $localizerCaptions;
    private $page;
    private $hiddenValues;
    private $defaultFilterName;

    const allFieldsName = 'AnyField';

    public function __construct($name, 
        $dataset, 
        $fieldsForFilter,
        $fieldCaptions,
        $filterTypes,
        $localizerCaptions,
        $page,
        $defaultFilterName = 'CONTAINS')
    {
        $this->name = $name;
        $this->fieldsForFilter = $fieldsForFilter;
        $this->filterTypes = $filterTypes;
        $this->dataset = $dataset;
        $this->fieldCaptions = $fieldCaptions;
        $this->localizerCaptions = $localizerCaptions;
        $this->activeFieldName = self::allFieldsName;
        $this->activeFilterTypeName = $defaultFilterName;
        $this->defaultFilterName = $defaultFilterName;
        $this->page = $page;
        $this->hiddenValues = array();
    }

    private function GetPage()
    { 
        return $this->page; 
    }

    private function IsRequestParameterSet($name)
    {
        return GetApplication()->GetSuperGlobals()->IsGetValueSet($name);
    }
    
    private function GetRequestParameter($name)
    {
        if (GetApplication()->GetSuperGlobals()->IsGetValueSet($name))
            return GetApplication()->GetSuperGlobals()->GetGetValue($name);
        else
            return null;
    }

    public function ResetFilter()
    {
        GetApplication()->GetSuperGlobals()->UnSetSessionVariable($this->name . 'SearchField');
        GetApplication()->GetSuperGlobals()->UnSetSessionVariable($this->name . 'FilterType');
        GetApplication()->GetSuperGlobals()->UnSetSessionVariable($this->name . 'FilterText');
        $this->activeFieldName = self::allFieldsName;
        $this->activeFilterTypeName = $this->defaultFilterName;
        $this->activeFilterText = '';
    }

    public function ExtractValuesFromSession()
    {
        $this->activeFieldName = GetApplication()->GetSuperGlobals()->GetSessionVariable($this->name . 'SearchField');
        $this->activeFilterTypeName = GetApplication()->GetSuperGlobals()->GetSessionVariable($this->name . 'FilterType');
        $this->activeFilterText = GetApplication()->GetSuperGlobals()->GetSessionVariable($this->name . 'FilterText');
    }

    public function ExtractValuesFromPost()
    {
        $this->activeFieldName = $this->GetRequestParameter('SearchField');
        $this->activeFilterTypeName = $this->GetRequestParameter('FilterType');
        $this->activeFilterText = $this->GetRequestParameter('FilterText');
        GetApplication()->GetSuperGlobals()->SetSessionVariable($this->name . 'SearchField', $this->activeFieldName);
        GetApplication()->GetSuperGlobals()->SetSessionVariable($this->name . 'FilterType', $this->activeFilterTypeName);
        GetApplication()->GetSuperGlobals()->SetSessionVariable($this->name . 'FilterText', $this->activeFilterText);
    }
    
    private function IsSearchVariablesComletelySet()
    {
        return isset($this->activeFieldName) && (isset($this->activeFilterTypeName)) &&
            (isset($this->activeFilterText) && trim($this->activeFilterText) != '');
    }

    public function CreateFieldFilter($ignoreDataType)
    {
        if ($this->activeFilterTypeName == 'STARTS')
            return new FieldFilter($this->activeFilterText.'%', 'ILIKE', $ignoreDataType);
        elseif ($this->activeFilterTypeName == 'ENDS')
            return new FieldFilter('%'.$this->activeFilterText, 'ILIKE', $ignoreDataType);
        elseif ($this->activeFilterTypeName == 'CONTAINS')
            return new FieldFilter('%'.$this->activeFilterText.'%', 'ILIKE', $ignoreDataType);
        else
            return new FieldFilter($this->activeFilterText, $this->activeFilterTypeName, $ignoreDataType);
    }
    
    public function ApplyFilterToDataset()
    {
        if ($this->activeFieldName == self::allFieldsName)
        {
            $fieldNames = array();
            $fieldFilters = array();
    
            foreach($this->fieldsForFilter as $fieldName)
            {
                $fieldNames[] = $fieldName;
                $fieldFilters[] = $this->CreateFieldFilter(true);
            }

            if (count($fieldFilters) > 0)
                $this->dataset->AddCompositeFieldFilter('OR', $fieldNames, $fieldFilters);
        }
        else
        {
            $this->dataset->AddFieldFilter(
                $this->activeFieldName,
                $this->CreateFieldFilter(false));
        }
    }

    public function IsAdvancedSearchOperation()
    {
        return GetApplication()->GetSuperGlobals()->IsPostValueSet('operation') && 
            (GetApplication()->GetSuperGlobals()->GetPostValue('operation') == 'asearch');
    }

    public function GetHiddenValues()
    {
        return $this->hiddenValues;
    }

    public function ProcessMessages()
    {
        $this->hiddenValues = $this->GetPage()->GetHiddenGetParameters();
        if (
            ($this->IsRequestParameterSet('ResetFilter') && $this->GetRequestParameter('ResetFilter') == '1') ||
            GetOperation() == 'resetall' || $this->IsAdvancedSearchOperation()
            )
        {
            $this->ResetFilter();
        }
        else
        {
            if (GetApplication()->GetSuperGlobals()->IsSessionVariableSet($this->name . 'SearchField'))
                $this->ExtractValuesFromSession();

            if ($this->IsRequestParameterSet('SearchField'))
                $this->ExtractValuesFromPost();

            if ($this->IsSearchVariablesComletelySet())
                $this->ApplyFilterToDataset();
        }
    }

    public function GetActiveFilterText()
    {
        return $this->activeFilterText;
    }

    public function GetActiveFilterTypeName()
    {
        return $this->activeFilterTypeName;
    }

    public function GetActiveFieldName()
    {
        return $this->activeFieldName;
    }

    public function GetFieldsToFilter()
    {
        $result = array();
        for($i = 0; $i < count($this->fieldsForFilter); $i++)
            $result[$this->fieldsForFilter[$i]] = $this->fieldCaptions[$i];
        $result[self::allFieldsName] = $this->localizerCaptions->GetMessageString('AnyField');
        return $result;
        
    }

    public function GetFilterTypes()
    {
        return $this->filterTypes;
    }

    public function Accept($Renderer)
    {
        $Renderer->RenderSimpleSearch($this);
    }
    
    public function GetHighlightedFields()
    {
        if ($this->activeFieldName == self::allFieldsName)
            return $this->fieldsForFilter;
        else
            return $this->activeFieldName;
    }
    
    public function UseTextHighlight()
    {
        return (
            ($this->activeFilterTypeName == 'ILIKE') ||
            ($this->activeFilterTypeName == '=') ||
            ($this->activeFilterTypeName == 'STARTS') ||
            ($this->activeFilterTypeName == 'ENDS') ||
            ($this->activeFilterTypeName == 'CONTAINS'))
        && ($this->activeFilterText != '');
    }
    
    public function GetTextForHighlight()
    {
        return str_replace('%', '', $this->activeFilterText);
    }

    /**
     * @param string $fieldName
     * @return bool
     */
    public function ContainsField($fieldName) {
        return in_array($fieldName, $this->fieldsForFilter);
    }

    public function GetHighlightOption()
    {
        $trimmed = trim($this->activeFilterText);
        if ($this->activeFilterTypeName == 'ILIKE')
        {
            if ($trimmed[0] == '%' && $trimmed[strlen($trimmed) - 1] == '%')
                return 'ALL';
            elseif ($trimmed[0] == '%')
                return 'END';
            elseif ($trimmed[strlen($trimmed) - 1] == '%')
                return 'START';
        }
        elseif ($this->activeFilterTypeName == 'STARTS')
            return 'START';
        elseif ($this->activeFilterTypeName == 'ENDS')
            return 'END';
        elseif ($this->activeFilterTypeName == 'CONTAINS')
            return 'ALL';
        else
            return 'ALL';
    }
}
