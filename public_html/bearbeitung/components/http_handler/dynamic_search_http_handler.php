<?php

include_once dirname(__FILE__) . '/abstract_http_handler.php';

class DynamicSearchHandler extends AbstractHTTPHandler
{
    /** @var Dataset */
    private $dataset;
    /** @var string */
    private $name;
    /** @var string */
    private $idField;
    /** @var string */
    private $valueField;
    /** @var string */
    private $captionTemplate;
    /** @var int */
    private $numberOfValuesToDisplay;

    /**
     * @param Dataset $dataset
     * @param Page|null $parentPage
     * @param string $name
     * @param string $idField
     * @param string $valueField
     * @param string $captionTemplate
     * @param int $numberOfValuesToDisplay
     */
    public function __construct($dataset, $parentPage, $name, $idField, $valueField, $captionTemplate = null, $numberOfValuesToDisplay = 20)
    {
        parent::__construct($name);
        $this->dataset = $dataset;
        $this->parentPage = null;
        $this->name = $name;
        $this->idField = $idField;
        $this->valueField = $valueField;
        $this->captionTemplate = $captionTemplate;
        $this->numberOfValuesToDisplay = $numberOfValuesToDisplay;
   }

    /**
     * @param Renderer $renderer
     * @return void
     */
    public function Render(Renderer $renderer)
    {
        $getWrapper = ArrayWrapper::createGetWrapper();

        /** @var string $term */
        $term = trim($getWrapper->getValue('term', ''));
        if (!empty($term)) {
            $this->dataset->AddFieldFilter(
                $this->valueField,
                new FieldFilter('%'.$term.'%', 'ILIKE', true)
            );
        }

        $id = $getWrapper->getValue('id');
        if (!empty($id)) {
            $this->dataset->AddFieldFilter($this->idField, FieldFilter::Equals($id));
        }

        $fields = $getWrapper->getValue('fields', array());
        foreach ($fields as $field => $value) {
            $this->dataset->AddFieldFilter($field, FieldFilter::Equals($value));
        }

        $excludedValues = $getWrapper->getValue('excludedValues', array());
        foreach ($excludedValues as $value) {
            $this->dataset->AddFieldFilter($this->valueField, FieldFilter::DoesNotEqual($value));
        }

        header('Content-Type: application/json; charset=utf-8');

        $this->dataset->Open();

        $result = array();
        $valueCount = 0;

        while ($this->dataset->Next()) {
            $result[] = array(
                'id' => $this->dataset->GetFieldValueByName($this->idField),
                'value' => $this->dataset->GetFieldValueByName($this->valueField),
                'formatted_value' => StringUtils::IsNullOrEmpty($this->captionTemplate)
                    ? $this->dataset->GetFieldValueByName($this->valueField)
                    : DatasetUtils::FormatDatasetFieldsTemplate($this->dataset, $this->captionTemplate),
                'fields' => $this->dataset->getFieldValues(true),

            );

            if (++$valueCount >= $this->numberOfValuesToDisplay) {
                break;
            }
        }

        echo SystemUtils::ToJSON($result);

        $this->dataset->Close();
    }
}
