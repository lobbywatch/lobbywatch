<?php

class ViewRenderer extends Renderer
{
    function RenderDetailPage(DetailPage $page) {
        $this->RenderPage($page);
    }

    function RenderPage(Page $Page) {
        $this->SetHTTPContentTypeByPage($Page);
        $Page->BeforePageRender->Fire(array(&$Page));

        $customParams = array();
        $layoutTemplate = $Page->GetCustomTemplate(PagePart::Layout, PageMode::View, 'common/layout.tpl', $customParams);

        $this->DisplayTemplate('view/page.tpl',
            array('Page' => $Page),
            array_merge($customParams,
                array(
                    'common' => $Page->GetSingleRecordViewData(),
                    'Authentication' => $Page->GetAuthenticationViewData(),
                    'LayoutTemplateName' => $layoutTemplate,
                    'PageList' => $this->RenderDef($Page->GetReadyPageList()),
                    'Grid' => $this->Render($Page->GetGrid()),
                    'HideSideBarByDefault' => $Page->GetHidePageListByDefault()
                )
            )
        );
    }

    function RenderAdminPage(AdminPage $page) {
        $this->SetHTTPContentTypeByPage($page);

        $customParams = array();
        $layoutTemplate = $page->GetCustomTemplate(
            PagePart::Layout,
            PageMode::View,
            'common/layout.tpl',
            $customParams
        );

        $this->DisplayTemplate('admin_panel.tpl',
            array('Page' => $page),
            array_merge($customParams, array(
                'common' => $page->getCommonViewData()->setEntryPoint('admin'),
                'Authentication' => $page->GetAuthenticationViewData(),
                'Users' => $page->GetAllUsersAsJson(),
                'PageList' => $this->RenderDef($page->GetReadyPageList()),
                'HideSideBarByDefault' => false,
                'LayoutTemplateName' => $layoutTemplate,
            ))
        );
    }

    function RenderGrid(Grid $Grid)
    {
        $page = $Grid->GetPage();
        $viewData = $Grid->GetViewSingleRowViewData();

        $navigation = clone $page->getNavigation();
        $navigation->append($viewData['Title']);

        $customParams = array();
        $template = $Grid->GetPage()->GetCustomTemplate(PagePart::RecordCard, PageMode::View,
            'view/grid.tpl', $customParams);

        $this->DisplayTemplate($template,
            array(
                'Grid' => $viewData,
            ),
            array_merge($customParams,
                array(
                    'Authentication' => $page->GetAuthenticationViewData(),
                    'navigation' => $page->getShowNavigation() ?
                        $this->RenderDef($navigation)
                        : null,
                )
            )
        );
    }

    protected function ShowHtmlNullValue()
    {
        return true;
    }

    protected function handleLongValuedTextFields() {
        return false;
    }
}

class DeleteRenderer extends Renderer
{
    function RenderDetailPage(DetailPage $page)
    {
        $this->RenderPage($page);
    }

    function RenderPage(Page $Page)
    {
        $this->DisplayTemplate('delete/page.tpl',
            array('Page' => $Page),
            array(
            'Grid' => $this->Render($Page->GetGrid())
        ));
    }

    function RenderGrid(Grid $Grid)
    {
        $primaryKeyMap = array();
        $Grid->GetDataset()->Open();

        $Row = array();
        $hiddenValues = '';
        if($Grid->GetDataset()->Next())
        {
            foreach($Grid->GetSingleRecordViewColumns() as $column)
                $Row[] = $this->Render($column);

            $hiddenValues = array(OPERATION_PARAMNAME => OPERATION_COMMIT_DELETE);
            AddPrimaryKeyParametersToArray($hiddenValues, $Grid->GetDataset()->GetPrimaryKeyValues());

            $primaryKeyMap = $Grid->GetDataset()->GetPrimaryKeyValuesMap();
        }

        $this->DisplayTemplate('delete/grid.tpl',
            array(
            'Grid' => $Grid,
            'Columns' => $Grid->GetSingleRecordViewColumns()),
            array(
            'Title' => $Grid->GetPage()->GetTitle(),
            'PrimaryKeyMap' => $primaryKeyMap,
            'ColumnCount' => count($Grid->GetSingleRecordViewColumns()),
            'Row' => $Row,
            'HiddenValues' => $hiddenValues
        ));
    }
}
