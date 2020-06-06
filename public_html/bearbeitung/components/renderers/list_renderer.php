<?php

include_once dirname(__FILE__) . '/' . 'renderer.php';
include_once dirname(__FILE__) . '/' . '../env_variables.php';
include_once dirname(__FILE__) . '/' . '../utils/html_utils.php';
include_once dirname(__FILE__) . '/' . '../utils/system_utils.php';

class ViewAllRenderer extends Renderer
{
    public $renderSingleRow;
    private $renderPageNavigator;

    #region Pages

    private function GetPageNavigator(Page $page) {
        if (($page->GetShowTopPageNavigator() || $page->GetShowBottomPageNavigator()) && $page->GetPageNavigator()) {
            $pageNavigators = $page->GetPageNavigator()->GetPageNavigators();
            foreach ($pageNavigators as $pnav) {
                if ($pnav->GetName() == 'pnav') {
                    return $pnav;
                }
            }
        }

        return false;
    }

    private function GetPageNavigator1(Page $page) {
        $this->renderPageNavigator = $page->GetShowTopPageNavigator();
        return $this->RenderDef($page->GetPageNavigator());
    }

    private function GetPageNavigator2(Page $page) {
        $this->renderPageNavigator = $page->GetShowBottomPageNavigator();
        if ($page->GetShowBottomPageNavigator()){
            if ($page->GetShowTopPageNavigator())
                return $this->RenderDef($page->GetPageNavigator());
            else
                return $this->RenderDef($page->GetPageNavigator());
        }
        else
            return $this->RenderDef($page->GetPageNavigator());
    }

    /**
     * @param Page $page
     */
    public function RenderPage(Page $page) {

        $this->SetHTTPContentTypeByPage($page);
        $page->BeforePageRender->Fire(array(&$page));
        $grid = $page->getShowGrid() ? $this->Render($page->GetGrid()) : null;

        $customParams = array();
        $layoutTemplate = $page->GetCustomTemplate(PagePart::Layout, PageMode::ViewAll, 'common/layout.tpl', $customParams);

        $this->DisplayTemplate('list/page.tpl',
            array(
                'Page' => $page
            ),
            array_merge($customParams, $this->getChartsParams($page),
                array(
                    // Template override
                    'LayoutTemplateName' => $layoutTemplate,
                    // View data
                    'Authentication' => $page->GetAuthenticationViewData(),
                    'common' => $page->GetListViewData(),
                    // Rendered controls
                    'Grid' => $grid,
                    'PageList' => $this->RenderDef($page->GetReadyPageList()),
                    'HideSideBarByDefault' => $page->GetHidePageListByDefault(),
                    'Variables' => $this->GetPageVariables($page),
                    // Page navigators
                    'PageNavigator' => $this->GetPageNavigator($page),
                    'PageNavigator1' => $this->GetPageNavigator1($page),
                    'PageNavigator2' => $this->GetPageNavigator2($page),
                    'ViewModes' => ViewMode::getList(),
                    'navigation' => $page->getShowNavigation() ?
                        $this->RenderDef($page->getNavigation())
                        : null,
                    'EnableRunTimeCustomization' => $page->getGrid()->getEnableRunTimeCustomization(),
                )
            )
        );
    }

    /**
     * @inheritdoc
     */
    public function RenderDetailPage(DetailPage $detailPage)
    {
        $parentPage = $detailPage->GetParentPage();
        $siblingDetails = $parentPage->GetGrid()->GetDetailLinksViewData();

        $this->SetHTTPContentTypeByPage($detailPage);

        $grid = $detailPage->getShowGrid() ? $this->Render($detailPage->GetGrid()) : null;

        if ($detailPage->GetReadyPageList() != null) {
            $pageList = $this->Render($detailPage->GetReadyPageList());
        } else {
            $pageList = null;
        }

        $customParams = array();
        $layoutTemplate = $detailPage->GetCustomTemplate(PagePart::Layout, PageMode::ViewAll, 'common/layout.tpl',
            $customParams);

        $navigation = $detailPage->getShowNavigation() ?
            $this->RenderDef($detailPage->getNavigation())
            : null;

        $masterGrid = $this->Render($detailPage->GetMasterGrid());
        $chartsParams = !$detailPage->isInline() || $detailPage->getShowInlineCharts()
            ? $this->getChartsParams($detailPage)
            : array();

        $this->DisplayTemplate('list/detail_page.tpl',
            array(
                'common' => $detailPage->GetListViewData(),
                'isInline' => $detailPage->isInline(),
                'Page' => $detailPage,
                'detailPage' => $detailPage,
                'SiblingDetails' => $siblingDetails,
                'DetailPageName' => $detailPage->GetHttpHandlerName(),
                'PageList' => $pageList,
                'PageNavigator' => $this->GetPageNavigator($detailPage),
                'PageNavigator1' => $this->GetPageNavigator1($detailPage),
                'PageNavigator2' => $this->GetPageNavigator2($detailPage),
                'ViewModes' => ViewMode::getList(),
            ),
            array_merge($customParams, $chartsParams,
                array(
                    'PageTitle' => $detailPage->GetTitle(),
                    'LayoutTemplateName' => $layoutTemplate,
                    'Grid' => $grid,
                    'Authentication' => $detailPage->GetAuthenticationViewData(),
                    'HideSideBarByDefault' => $detailPage->GetHidePageListByDefault(),
                    'MasterGrid' => $masterGrid,
                    'Variables' => $this->GetPageVariables($detailPage),
                    'navigation' => $navigation,
                    'EnableRunTimeCustomization' => $detailPage->getGrid()->getEnableRunTimeCustomization(),
                )
            )
        );
    }

    private function getChartsParams(Page $page)
    {
        if (GetOfflineMode())
            return array();

        $renderedCharts = array(
            ChartPosition::BEFORE_GRID => array(),
            ChartPosition::AFTER_GRID => array(),
        );

        $chartsClasses = array();
        foreach ($page->getCharts() as $position => $charts) {
            ksort($charts);
            $chartsClasses[$position] = array();
            foreach ($charts as $chart) {
                $page->OnPrepareChart->Fire(array($chart['chart']));
                $renderedCharts[$position][] = $this->Render($chart['chart']);
                $chartsClasses[$position][] = 'col-md-' . $chart['cols'];
            }
        }

        return array(
            'ChartsBeforeGrid' => $renderedCharts[ChartPosition::BEFORE_GRID],
            'ChartsBeforeGridClasses' => $chartsClasses[ChartPosition::BEFORE_GRID],
            'ChartsAfterGrid' => $renderedCharts[ChartPosition::AFTER_GRID],
            'ChartsAfterGridClasses' => $chartsClasses[ChartPosition::AFTER_GRID],
        );
    }

    #endregion

    #region Page parts

    public function RenderGrid(Grid $grid) {
        $page = $grid->GetPage();

        $templates = array(
            ViewMode::TABLE => array(
                'grid'   => 'list/grid_table.tpl',
                'single' => 'list/single_row.tpl'
            ),
            ViewMode::CARD => array(
                'grid'   => 'list/grid_card.tpl',
                'single' => 'list/single_row_card.tpl'
            )
        );

        $selectedTemplates = $templates[$grid->GetViewMode()];
        $template = $this->renderSingleRow ? $selectedTemplates['single'] : $selectedTemplates['grid'];
        $customParams = array();

        $singleRowTemplate = $page->GetCustomTemplate(PagePart::GridRow, PageMode::ViewAll, $selectedTemplates['single'], $customParams);
        if (!$this->renderSingleRow) {
            $template = $page->GetCustomTemplate(PagePart::Grid, PageMode::ViewAll, $template, $customParams);
        } else {
            $template = $singleRowTemplate;
        }

        $gridToolbarTemplate = $page->GetCustomTemplate(PagePart::GridToolbar, PageMode::ViewAll, 'list/grid_toolbar.tpl', $customParams);

        $this->DisplayTemplate(
            $template,
            array(
                'Grid' => $grid,
                'Page' => $page,
                'DataGrid' => $grid->GetViewData($this)
            ),
            array_merge($customParams,
                array(
                    'isMasterGrid' => $grid->isMaster(),
                    'SingleRowTemplate' => $singleRowTemplate,
                    'GridToolbarTemplate' => $gridToolbarTemplate,
                    'isInline' => $page->isInline(),
                    'HiddenValues' => $grid->GetHiddenValues(),
                    'Authentication' => $page->GetAuthenticationViewData(),
                    'Columns' => $grid->getViewColumnGroup()->getLeafs(),
                    'GridViewMode' => $grid->getViewMode() === ViewMode::TABLE ? 'table' : 'card',
                    'CurrentViewMode' => $grid->getViewMode(),
                    'ViewModes' => ViewMode::getList(),
                )
            )
        );
    }

    public function RenderCustomPageNavigator(CustomPageNavigator $pageNavigator) {
        if ($this->renderPageNavigator) {
            $templateName = 'custom_page_navigator.tpl'; // here $pageNavigator->GetNavigationStyle() == NS_LIST
            if ($pageNavigator->GetNavigationStyle() == NS_COMBOBOX)
                $templateName = 'combo_box_custom_page_navigator.tpl';

            $this->DisplayTemplate('list/'.$templateName,
                array(
                        'PageNavigator' => $pageNavigator,
                        'PageNavigatorPages' => $pageNavigator->GetPages()),
                 array()
            );
        }
         else {
            $this->result = '';
        }
    }

    public function RenderCompositePageNavigator($PageNavigator) {
        $this->DisplayTemplate('list/composite_page_navigator.tpl',
            array(
                'PageNavigator' => $PageNavigator
            ),
            array()
        );
    }

    public function RenderPageNavigator($PageNavigator) {
        if ($this->renderPageNavigator) {
            $this->DisplayTemplate('list/page_navigator.tpl',
                array(
                    'PageNavigator' => $PageNavigator,
                    'PageNavigatorPages' => $PageNavigator->GetPages()),
                array()
            );
        }
        else {
            $this->result = '';
        }
    }

    #endregion

    #region Column rendering options

    protected function ShowHtmlNullValue()
    {
        return true;
    }

    #endregion
}

class ErrorStateRenderer extends ViewAllRenderer
{
    /** @var  Exception */
    private $exception;

    public function  __construct($captions, $exception)
    {
        parent::__construct($captions);
        $this->exception = $exception;
    }

    public function RenderDetailPage(DetailPage $page)
    {
        $this->RenderPage($page);
    }

    public function RenderPage(Page $page)
    {
        $this->SetHTTPContentTypeByPage($page);

        $pageList = $page->GetReadyPageList();
        $pageList = isset($pageList) ? $this->Render($pageList) : '';

        $displayDebugInfo = DebugUtils::GetDebugLevel();
        $reloadWithDefailtsUrlBuilder = $page->CreateLinkBuilder();
        $reloadWithDefailtsUrlBuilder->addParameter('clear_options', true);

        $inputValues = array(
            'PageList' => $pageList,
            'common' => $page->getCommonViewData(),
            'ErrorMessage' => $this->exception->getMessage(),
            'DisplayDebugInfo' => $displayDebugInfo,
            'ReloadWithDefaultsUrl' => $reloadWithDefailtsUrlBuilder->getLink(),
        );

        if ($displayDebugInfo == 1) {
            $inputValues['File'] = $this->exception->getFile();
            $inputValues['Line'] = $this->exception->getLine();
            $inputValues['Trace'] = $this->exception->getTraceAsString();
        }

        $this->DisplayTemplate('list/error_page.tpl',
            array('Page' => $page),
            $inputValues
        );
    }
}
