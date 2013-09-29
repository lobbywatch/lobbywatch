<?php

require_once 'components/superglobal_wrapper.php';

class QuickFilter {
    /** @var string */
    private $name;

    /** @var \Dataset */
    private $dataset;

    /** @var \SuperGlobals */
    private $superGlobals;

    /** @var mixed */
    private $value;

    public function __construct($name, Page $page, Dataset $dataset) {
        $this->name = $name;
        $this->dataset = $dataset;
        $this->page = $page;
        $this->superGlobals = GetApplication()->GetSuperGlobals();
    }

    private function ApplyFilter() {
        $fieldNames = array();
        $fieldFilters = array();

        foreach ($this->dataset->GetFields() as $field) {
            if ($this->page->GetSimpleSearchAvailable() && ($this->page->GetGrid()->SearchControl instanceof SimpleSearch)) {
                if ($this->page->GetGrid()->SearchControl->ContainsField($field->GetName())) {
                    if ($field->GetEngFieldType() != ftBlob) {
                        $fieldNames[] = $field->GetName();
                        $fieldFilters[] = new FieldFilter('%' . $this->value . '%', 'ILIKE', true);
                    }
                } else {
                    if ($this->dataset->IsLookupFieldByPrimaryName($field->GetName()) != null) {
                        $lookupFieldName = $this->dataset->IsLookupFieldByPrimaryName($field->GetName());
                        if ($this->page->GetGrid()->SearchControl->ContainsField($lookupFieldName)) {
                            $fieldNames[] = $lookupFieldName;
                            $fieldFilters[] = new FieldFilter('%' . $this->value . '%', 'ILIKE', true);
                        }
                    }
                }
            }
        }

        if (count($fieldFilters) > 0)
            $this->dataset->AddCompositeFieldFilter('OR', $fieldNames, $fieldFilters);
    }

    public function ProcessMessages() {
        if ($this->superGlobals->IsGetValueSet('quick-filter')) {
            $this->SetValue($this->superGlobals->GetGetValue('quick-filter'));
            $this->superGlobals->SetSessionVariable($this->name . 'quick-filter', $this->GetValue());
            $this->ApplyFilter();
        } else if ($this->superGlobals->IsGetValueSet('quick-filter-reset')) {
            $this->SetValue(null);
            $this->superGlobals->UnSetSessionVariable($this->name . 'quick-filter');
        } else if ($this->superGlobals->IsSessionVariableSet($this->name . 'quick-filter')) {
            $this->SetValue($this->superGlobals->GetSessionVariable($this->name . 'quick-filter'));
            $this->ApplyFilter();
        }
    }

    public function GetValue() {
        return $this->value;
    }

    public function SetValue($value) {
        $this->value = $value;
    }

    public function GetViewData() {
        return array(
            'Value' => $this->GetValue()
        );
    }
}