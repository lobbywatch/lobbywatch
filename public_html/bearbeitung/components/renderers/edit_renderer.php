<?php

// require_once 'renderer.php';
include_once dirname(__FILE__) . '/' . 'renderer.php';

class EditRenderer extends Renderer
{
    #region Pages

    protected function GetPageViewData(Page $page) {
        return $page->GetSeparatedEditViewData();
    }

    protected function getPageMode() {
        return PageMode::Edit;
    }

    function RenderPage(Page $page) {
        $this->SetHTTPContentTypeByPage($page);
        $page->BeforePageRender->Fire(array(&$page));

        $customParams = array();
        $layoutTemplate = $page->GetCustomTemplate(PagePart::Layout, $this->getPageMode(), 'common/layout.tpl', $customParams);

        $this->DisplayTemplate('view/page.tpl',
            array(
                'Page' => $page
            ),
            array_merge($customParams,
                array(
                    'common' => $this->GetPageViewData($page),
                    'Authentication' => $page->GetAuthenticationViewData(),

                    'HideSideBarByDefault' => $page->GetHidePageListByDefault(),
                    'LayoutTemplateName' => $layoutTemplate,
                    'PageList' => $this->RenderDef($page->GetReadyPageList()),
                    'Grid' => $this->Render($page->GetGrid()),
                    'Variables' => $this->GetPageVariables($page)
                )
            )
        );
    }

    public function RenderDetailPage(DetailPage $page) {
        $customParams = array();
        $layoutTemplate = $page->GetCustomTemplate(PagePart::Layout, $this->getPageMode(), 'common/layout.tpl', $customParams);

        $this->DisplayTemplate('view/page.tpl',
            array(
                'Page' => $page
            ),
            array_merge($customParams,
                array(
                    'common' => $this->GetPageViewData($page),
                    'Authentication' => $page->GetAuthenticationViewData(),

                    'HideSideBarByDefault' => $page->GetHidePageListByDefault(),
                    'PageList' => $this->RenderDef($page->GetReadyPageList()),
                    'LayoutTemplateName' => $layoutTemplate,
                    'Grid' => $this->Render($page->GetGrid())
                )
            )
        );
    }

    public function RenderGrid(Grid $grid)
    {
        $hiddenValues = array();
        AddPrimaryKeyParametersToArray($hiddenValues, $grid->GetDataset()->GetPrimaryKeyValues());

        $this->doRenderGrid($grid, $grid->GetEditViewData(), $hiddenValues, PageMode::Edit, true);
    }

    protected function doRenderGrid(Grid $grid, $viewData, $hiddenValues, $pageMode, $isEditOperation)
    {
        $page = $grid->GetPage();

        $navigation = clone $page->getNavigation();
        $navigation->append($viewData['Title']);

        $customParams = array();
        $template = $page->GetCustomTemplate(PagePart::VerticalGrid, $pageMode, 'forms/page_form.tpl', $customParams);

        $forms = array();
        $count = ArrayWrapper::createGetWrapper()->getValue('count', 1);
        for ($i = 0; $i < $count; $i++) {
            $forms[] = $this->RenderForm($page, $viewData, $hiddenValues, $isEditOperation, true);
        }

        $this->DisplayTemplate(
            $template,
            array('Grid' => $viewData),
            array_merge($customParams, array(
                'Authentication' => $page->GetAuthenticationViewData(),
                'Forms' => $forms,
                'navigation' => $page->getShowNavigation() ?
                    $this->RenderDef($navigation)
                    : null,
            ))
        );
    }
}
