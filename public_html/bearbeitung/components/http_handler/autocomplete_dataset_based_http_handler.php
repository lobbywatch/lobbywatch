<?php

include_once dirname(__FILE__) . '/abstract_http_handler.php';

class AutocompleteDatasetBasedHTTPHandler extends AbstractHTTPHandler
{
    /** @var Dataset */
    private $dataset;
    /** @var string */
    private $suggestionsFieldName;
    /** @var int */
    private $numberOfSuggestionsToDisplay;

    /**
     * @param Dataset $dataset
     * @param string $suggestionsFieldName
     * @param string $name
     * @param int $numberOfSuggestionsToDisplay
     */
    public function __construct($dataset, $suggestionsFieldName, $name, $numberOfSuggestionsToDisplay = 20)
    {
        parent::__construct($name);
        $this->dataset = $dataset;
        $this->suggestionsFieldName = $suggestionsFieldName;
        $this->numberOfSuggestionsToDisplay = $numberOfSuggestionsToDisplay;
    }

    public function Render(Renderer $renderer)
    {
        $getWrapper = ArrayWrapper::createGetWrapper();

        /** @var string $term */
        $term = trim($getWrapper->getValue('query', ''));
        if (!empty($term)) {
            $this->dataset->AddFieldFilter(
                $this->suggestionsFieldName,
                new FieldFilter('%'.$term.'%', 'ILIKE', true)
            );
        }

        header('Content-Type: application/json; charset=utf-8');

        $this->dataset->Open();
        $result['suggestions'] = array();
        $suggestionCount = 0;
        while ($this->dataset->Next()) {
            $result['suggestions'][] = $this->dataset->GetFieldValueByName($this->suggestionsFieldName);
            if (++$suggestionCount >= $this->numberOfSuggestionsToDisplay) {
                break;
            }
        }
        $this->dataset->Close();

        return SystemUtils::ToJSON($result);
    }
}
