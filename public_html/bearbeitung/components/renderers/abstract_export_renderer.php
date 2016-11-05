<?php

include_once dirname(__FILE__) . '/' . 'renderer.php';

abstract class AbstractExportRenderer extends Renderer
{
    protected function getCurrentRowData(Grid $grid)
    {
        $grid->GetDataset()->Open();
        if ($grid->GetDataset()->Next()) {
            return $grid->GetDataset()->GetCurrentFieldValues();
        }

        return null;
    }
}
