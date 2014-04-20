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

    protected function getPageMode() {
        return PageMode::Insert;
    }

    public function RenderGrid(Grid $grid)
    {
        $hiddenValues = array(OPERATION_PARAMNAME => OPERATION_COMMIT_INSERT);

        $customParams = array();
        $template = $grid->GetPage()->GetCustomTemplate(PagePart::VerticalGrid, PageMode::Insert, 'insert/grid.tpl',
            $customParams);

        $this->DisplayTemplate($template,
            array(
                'Grid' => $grid->GetInsertViewData($this)
            ),
            array_merge($customParams,
                array(
                    'Authentication' => $grid->GetPage()->GetAuthenticationViewData(),
                    'HiddenValues' => $hiddenValues
                )
            )
        );
    }
}
