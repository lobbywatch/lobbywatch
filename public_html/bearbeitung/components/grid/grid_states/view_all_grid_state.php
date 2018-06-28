<?php

include_once dirname(__FILE__) . '/' . '../../common_utils.php';

class ViewAllGridState extends GridState
{
    public function ProcessMessages()
    {
        $page = $this->grid->getPage();

        if (!$this->grid->isMaster()) {
            if (!$page->isInline()) {
                $page->setupFilters($this->grid);
                if ($this->grid->processFilters()) {
                    header('Location: ' . GetCurrentPageFullUrl());
                    exit;
                }
            }

            $pageNavigator = $page->getPageNavigator();
            if (isset($pageNavigator)) {
                $pageNavigator->ProcessMessages();
            }
        }

        $this->getDataset()->setOrderByFields($this->grid->getSortedColumns());

        foreach ($this->grid->GetViewColumns() as $column) {
            $column->ProcessMessages();
        }

    }
}
