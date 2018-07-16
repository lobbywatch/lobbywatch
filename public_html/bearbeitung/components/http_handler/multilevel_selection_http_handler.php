<?php

include_once dirname(__FILE__) . '/abstract_http_handler.php';

class MultiLevelSelectionHandler extends AbstractHTTPHandler
{
    private $dataset;
    private $idFieldName;
    private $captionFieldName;
    private $parentIdFieldName;
    private $arrayWrapper;
    /** @var int */
    private $numberOfValuesToDisplay;

    const SearchTermParamName = 'term';
    const ParentParamName = 'term2';

    public function __construct($name, Dataset $dataset,
        $idFieldName, $captionFieldName, $parentIdFieldName, ArrayWrapper $arrayWrapper, $numberOfValuesToDisplay = 20)
    {
        parent::__construct($name);
        $this->dataset = $dataset;
        $this->idFieldName = $idFieldName;
        $this->captionFieldName = $captionFieldName;
        $this->parentIdFieldName = $parentIdFieldName;
        $this->arrayWrapper = $arrayWrapper;
        $this->numberOfValuesToDisplay = $numberOfValuesToDisplay;
    }

    public function Render(Renderer $renderer)
    {
        $result = array();

        $this->dataset->AddFieldFilter(
            $this->captionFieldName,
            FieldFilter::Contains($this->arrayWrapper->GetValue(self::SearchTermParamName))
        );

        if (
            !StringUtils::IsNullOrEmpty($this->parentIdFieldName)
            && $this->arrayWrapper->IsValueSet(self::ParentParamName)
        ) {
            $this->dataset->AddFieldFilter(
                $this->parentIdFieldName,
                FieldFilter::Equals(
                    $this->arrayWrapper->GetValue(self::ParentParamName)));
        }

        $this->dataset->Open();
        $valueCount = 0;

        while ($this->dataset->Next()) {
            $result[] = array(
                'id' => $this->dataset->GetFieldValueByName($this->idFieldName),
                'value' => $this->dataset->GetFieldValueByName($this->captionFieldName)
            );

            if ($this->numberOfValuesToDisplay !== -1) {
                if (++$valueCount >= $this->numberOfValuesToDisplay) {
                    break;
                }
            }
        }
        $this->dataset->Close();

        return SystemUtils::ToJSON($result);
    }
}
