<?php

class ViewRenderer extends Renderer
{
    function RenderDetailPageEdit($page) {
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
                    'App' => $Page->GetSingleRecordViewData(),
                    'Authentication' => $Page->GetAuthenticationViewData(),
                    'LayoutTemplateName' => $layoutTemplate,
                    'PageList' => $this->RenderDef($Page->GetReadyPageList()),
                    'Grid' => $this->Render($Page->GetGrid()),
                    'HideSideBarByDefault' => $Page->GetHidePageListByDefault()
                )
            )
        );
    }

    function RenderGrid(Grid $Grid) {

        $customParams = array();
        $template = $Grid->GetPage()->GetCustomTemplate(PagePart::RecordCard, PageMode::View,
            'view/grid.tpl', $customParams);
        $this->DisplayTemplate($template,
            array(
                'Grid' => $Grid->GetViewSingleRowViewData($this),
            ),
            array_merge($customParams,
                array(
                    'Authentication' => $Grid->GetPage()->GetAuthenticationViewData()
                )
            )
        );
    }

    protected function ShowHtmlNullValue()
    { 
        return true;
    }
}

class PrintOneRecordRenderer extends ViewRenderer
{
    function RenderDetailPageEdit($page)
    {
        $this->RenderPage($page);
    }

    function RenderPage(Page $Page)
    {
        $this->SetHTTPContentTypeByPage($Page);
        $Page->BeforePageRender->Fire(array(&$Page));

        $this->DisplayTemplate('view/print_page.tpl',
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
        if($Grid->GetDataset()->Next())
        {
            $primaryKeyMap = $Grid->GetDataset()->GetPrimaryKeyValuesMap();
            foreach($Grid->GetSingleRecordViewColumns() as $Column)
                $Row[] = $this->Render($Column);
        }

        $this->DisplayTemplate('view/print_grid.tpl',
            array(
            'Grid' => $Grid,
            'Columns' => $Grid->GetSingleRecordViewColumns()),
            array(
            'Title' => $Grid->GetPage()->GetShortCaption(),
            'PrimaryKeyMap' => $primaryKeyMap,
            'ColumnCount' => count($Grid->GetSingleRecordViewColumns()),
            'Row' => $Row,
        ));
    }

    protected function ChildPagesAvailable() 
    { 
        return false; 
    }
}

class DeleteRenderer extends Renderer
{
    function RenderDetailPageEdit($page)
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
            'Title' => $Grid->GetPage()->GetShortCaption(),
            'PrimaryKeyMap' => $primaryKeyMap,
            'ColumnCount' => count($Grid->GetSingleRecordViewColumns()),
            'Row' => $Row,
            'HiddenValues' => $hiddenValues
        ));
    }
}
