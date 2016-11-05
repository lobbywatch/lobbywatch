<?php

include_once dirname(__FILE__) . '/' . 'renderer.php';
include_once dirname(__FILE__) . '/' . '../env_variables.php';
include_once dirname(__FILE__) . '/' . '../utils/html_utils.php';
include_once dirname(__FILE__) . '/' . '../utils/system_utils.php';

class CompareRenderer extends Renderer
{
    /**
     * {@inheritdoc}
     */
    public function RenderPage(Page $page)
    {
        $this->SetHTTPContentTypeByPage($page);

        $customParams = array();
        $layoutTemplate = $page->GetCustomTemplate(
            PagePart::Layout,
            PageMode::Compare,
            'common/layout.tpl',
            $customParams
        );

        $navigation = clone $page->getNavigation();
        $navigation->append($this->GetCaptions()->GetMessageString('Compare'));

        $this->DisplayTemplate(
            'compare/page.tpl',
            array('Page' => $page),
            array_merge($customParams, array(
                'layoutTemplate' => $layoutTemplate,
                'common' => $page->getCompareViewData(),
                'Authentication' => $page->GetAuthenticationViewData(),
                'PageList' => $this->RenderDef($page->GetReadyPageList()),
                'HideSideBarByDefault' => $page->GetHidePageListByDefault(),
                'Variables' => $this->GetPageVariables($page),
                'navigation' => $page->getShowNavigation() ?
                    $this->RenderDef($navigation)
                    : null,
                'Grid' => $this->Render($page->GetGrid())
            ))
        );
    }

    /**
     * {@inheritdoc}
     */
    public function RenderDetailPage(DetailPage $detailPage)
    {
        $this->RenderPage($detailPage);
    }

    /**
     * {@inheritdoc}
     */
    public function RenderGrid(Grid $grid)
    {
        $page = $grid->getPage();

        $customParams = array();
        $template = $page->GetCustomTemplate(
            PagePart::Grid,
            PageMode::Compare,
            'compare/grid.tpl',
            $customParams
        );

        $this->DisplayTemplate(
            $template,
            array(
                'Page' => $grid->getPage(),
                'DataGrid' => $grid->getCompareViewData($this),
            ),
            array_merge($customParams, array())
        );
    }

    protected function ShowHtmlNullValue() {
        return true;
    }
}
