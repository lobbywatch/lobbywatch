<?php

class InsertRenderer extends EditRenderer
{
    protected function GetPageViewData(Page $page) {
        return $page->GetSeparatedInsertViewData();
    }

    protected function getPageMode() {
        return PageMode::Insert;
    }

    public function RenderGrid(Grid $grid)
    {
        $this->doRenderGrid($grid, $grid->GetInsertViewData(), array());
    }

    protected function getOperationName() {
        return OPERATION_INSERT;
    }
}
