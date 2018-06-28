<?php

include_once dirname(__FILE__) . '/abstract_http_handler.php';

class SelectionHandler extends AbstractHTTPHandler
{
    /** @var Grid*/
    private $grid;

    /**
     * @param string $name
     * @param Grid $grid
     */
    public function __construct($name, $grid) {
        parent::__construct($name);
        $this->grid = $grid;
    }

    public function Render(Renderer $renderer) {
        if (!$this->grid->isMaster()) {
            $this->grid->getPage()->setupFilters($this->grid);
            $this->grid->processFilters();
        }

        $result = array();
        $getWrapper = ArrayWrapper::createGetWrapper();
        $filterName = $getWrapper->getValue('filterName');

        $selectionFilters = $this->grid->getSelectionFilters();
        if (is_null($filterName) || !array_key_exists($filterName, $selectionFilters)) {
            return $result;
        }

        $resultFilter = new FilterGroup(FilterGroupOperator::OPERATOR_OR);
        $resultFilter->insertChild($selectionFilters[$filterName], $filterName);

        $dataset = $this->grid->GetDataset();
        $dataset->getSelectCommand()->AddCompositeFilter($resultFilter->getCommandFilter());

        $dataset->Open();
        while ($dataset->Next()) {
            $result[] = $dataset->GetPrimaryKeyValues();
        }

        return SystemUtils::ToJSON($result);
    }
}
