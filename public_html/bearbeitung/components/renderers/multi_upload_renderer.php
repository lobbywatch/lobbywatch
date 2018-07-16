<?php

class MultiUploadRenderer extends EditRenderer
{
    protected function getPageMode() {
        return PageMode::MultiUpload;
    }

    public function RenderGrid(Grid $grid)
    {
        $viewData = $grid->GetMultiUploadViewData();
        $page = $grid->GetPage();

        $navigation = clone $page->getNavigation();
        $navigation->append($viewData['Title']);

        $customParams = array();
        $template = $page->GetCustomTemplate(PagePart::VerticalGrid, $this->getPageMode(), 'forms/multi_upload_form.tpl', $customParams);

        $this->DisplayTemplate(
            $template,
            array(
                'Grid' => $viewData
            ),
            array_merge($customParams, array(
                'Authentication' => $page->GetAuthenticationViewData(),
                'Form' => $this->RenderForm($page, $viewData, array(), OPERATION_MULTI_UPLOAD, true),
                'navigation' => $page->getShowNavigation() ? $this->RenderDef($navigation) : null
            ))
        );
    }
}
