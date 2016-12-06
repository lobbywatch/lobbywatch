<?php

class ViewAllGridState extends GridState
{
    public function ProcessMessages()
    {
        $page = $this->grid->getPage();

        if (!$this->grid->isMaster()) {
            if (!$page->isInline()) {
                $page->setupFilters($this->grid);
                if ($this->grid->processFilters()) {
                    $protocol = isset($_SERVER['HTTPS']) ? 'https' : 'http';
                    header(
                        'Location: '
                        . $protocol . '://'
                        . $_SERVER['HTTP_HOST']
                        . $_SERVER['REQUEST_URI']
                    );
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
