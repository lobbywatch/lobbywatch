<?php

class InsertRenderer extends EditRenderer
{
    protected function ForceHideImageUploaderImage()
    {
        return true;
    }

    protected function GetPageViewData(Page $page) {
        return $page->GetSeparatedInsertViewData();
    }

    public function RenderGrid(Grid $grid)
    {
        $hiddenValues = array(OPERATION_PARAMNAME => OPERATION_COMMIT_INSERT);

        $template = $grid->GetPage()->GetCustomTemplate(PagePart::VerticalGrid, PageMode::Insert, 'insert/grid.tpl');

        $this->DisplayTemplate($template,
            array(
                'Grid' => $grid->GetInsertViewData($this)
            ),
            array(
                'Authentication' => $grid->GetPage()->GetAuthenticationViewData(),
                'HiddenValues' => $hiddenValues
            )
        );
    }
}

?>
