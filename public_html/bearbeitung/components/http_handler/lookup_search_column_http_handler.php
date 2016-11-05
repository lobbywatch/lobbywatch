<?php

include_once dirname(__FILE__) . '/abstract_http_handler.php';

class LookupSearchColumnDataHandler extends AbstractHTTPHandler {
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
        $params = ArrayWrapper::createGetWrapper();
        $term = $params->isValueSet('term') ? $params->getValue('term') : '';

        if ($params->isValueSet('term')) {
            $this->dataset->AddFieldFilter(
                $this->valueField,
                new FieldFilter('%'.$term.'%', 'ILIKE', true)
            );
        }

        if ($params->isValueSet('id')) {
            $this->dataset->AddFieldFilter(
                $this->idField,
                FieldFilter::Equals($params->getValue('id'))
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
            Argument::$Arg4 => $term
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
