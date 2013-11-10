<?php

require_once 'renderer.php';

class EditRenderer extends Renderer
{
    #region Pages

    protected function GetPageViewData(Page $page) {
        return $page->GetSeparatedEditViewData();
    }

    function RenderPage(Page $page) {
        $this->SetHTTPContentTypeByPage($page);
        $page->BeforePageRender->Fire(array(&$page));

        $layoutTemplate = $page->GetCustomTemplate(PagePart::Layout, PageMode::Edit, 'common/layout.tpl');

        $this->DisplayTemplate('edit/page.tpl',
            array(
                'Page' => $page
            ),
            array(
                'App' => $this->GetPageViewData($page),
                'Authentication' => $page->GetAuthenticationViewData(),

                'HideSideBarByDefault' => $page->GetHidePageListByDefault(),
                'LayoutTemplateName' => $layoutTemplate,
                'PageList' => $this->RenderDef($page->GetReadyPageList()),
                'Grid' => $this->Render($page->GetGrid()),
                'Variables' => $this->GetPageVariables($page)
            )
        );
    }

    function RenderDetailPageEdit($Page) {
        $layoutTemplate = $Page->GetCustomTemplate(PagePart::Layout, PageMode::Edit, 'common/layout.tpl');

        $this->DisplayTemplate('edit/page.tpl',
            array(
                'Page' => $Page
            ),
            array(
                'App' => $Page->GetSeparatedEditViewData(),
                'Authentication' => $Page->GetAuthenticationViewData(),

                'HideSideBarByDefault' => $Page->GetHidePageListByDefault(),
                'PageList' => $this->RenderDef($Page->GetReadyPageList()),
                'LayoutTemplateName' => $layoutTemplate,
                'Grid' => $this->Render($Page->GetGrid()
            )
        ));
    }

    #endregion

    #region Page parts

    function RenderGrid(Grid $grid) {
        $hiddenValues = array(OPERATION_PARAMNAME => OPERATION_COMMIT);
        AddPrimaryKeyParametersToArray($hiddenValues, $grid->GetDataset()->GetPrimaryKeyValues());

        $template = $grid->GetPage()->GetCustomTemplate(PagePart::VerticalGrid, PageMode::Edit, 'edit/grid.tpl');

        $this->DisplayTemplate($template,
            array(
                'Grid' => $grid->GetEditViewData($this),
            ),
            array(
                'Authentication' => $grid->GetPage()->GetAuthenticationViewData(),
                'HiddenValues' => $hiddenValues
            )
        );
    }

    #endregion
}
