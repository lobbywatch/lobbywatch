<?php

include_once dirname(__FILE__) . '/' . 'edit_renderer.php';

class MultiEditRenderer extends EditRenderer
{
    protected function getPageMode() {
        return PageMode::MultiEdit;
    }

    public function RenderGrid(Grid $grid)
    {
        $hiddenValues = $grid->GetDataset()->fetchPrimaryKeyValues();
        $this->doRenderGrid($grid, $grid->GetMultiEditViewData(), $hiddenValues);
    }

    protected function getOperationName() {
        return OPERATION_MULTI_EDIT;
    }
}
