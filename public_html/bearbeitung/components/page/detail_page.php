<?php

include_once dirname(__FILE__) . '/navigation.php';

abstract class DetailPage extends Page
{
    /** @var Page */
    private $parentPage;
    /** @var Grid */
    private $masterGrid;
    /** @var Dataset */
    private $masterDataset;
    /** @var array */
    private $foreignKeyFields = array();
    /** @var array */
    private $foreignKeyValues = array();
    /** @var array */
    private $masterKeyFields = array();
    /** @var array */
    private $masterRecordValues = array();
    /** @var array */
    private $parentMasterKeyFields = array();
    /** @var array */
    private $parentMasterKeyValues = array();
    /** @var integer */
    private $totalRowCount = 0;
    /** @var boolean */
    private $showInlineCharts = true;

    /**
     * @param string $id
     * @param Page $parentPage
     * @param array $foreignKeyFields
     * @param array $masterKeyFields
     * @param array $parentMasterKeyFields
     * @param Grid $masterGrid
     * @param Dataset $masterDataset
     * @param IPermissionSet $dataSourceSecurityInfo
     * @param string $contentEncoding
     */
    public function __construct($id, $parentPage, $foreignKeyFields, $masterKeyFields, $parentMasterKeyFields, $masterGrid, $masterDataset, $dataSourceSecurityInfo, $contentEncoding = null)
    {
        $this->foreignKeyFields = $foreignKeyFields;
        $this->parentPage = $parentPage;
        $this->masterKeyFields = $masterKeyFields;
        $this->masterGrid = $masterGrid->setIsMaster(true);

        parent::__construct(
            $id,
            $parentPage->GetPageFileName(),
            $dataSourceSecurityInfo,
            $contentEncoding
        );

        $this->masterDataset = $masterDataset;
        $this->foreignKeyValues = array();
        $this->parentMasterKeyFields = $parentMasterKeyFields;
    }

    /** @inheritdoc */
    public function getTitle() {
        if (!empty($this->masterRecordValues)) {
            return StringUtils::ReplaceVariables(parent::getTitle(), $this->masterRecordValues);
        }

        return parent::getTitle();
    }

    /** @inheritdoc */
    public function GetReadyPageList() {
        return $this->parentPage->GetReadyPageList();
    }

    public function GetParentPage() {
        return $this->parentPage;
    }

    public function GetForeignKeyFields() {
        return $this->foreignKeyFields;
    }

    public function GetMasterGrid() {
        return $this->masterGrid;
    }

    public function ProcessMessages() {
        $this->UpdateValuesFromUrl();

        if ($this->isInline()) {
            $this->grid->ProcessMessages();
        } else {
            parent::ProcessMessages();
        }


        $masterGrid = $this->GetMasterGrid();
        if ($masterGrid) {
            $masterGrid->ProcessMessages();
            $this->parentPage->getGrid()->setViewMode($masterGrid->getViewMode());
        }
    }

    public function UpdateValuesFromUrl() {
        for($i = 0; $i < count($this->foreignKeyFields); $i++) {
            if (GetApplication()->GetSuperGlobals()->IsGetValueSet('fk' . $i)) {
                $this->foreignKeyValues[] = $_GET['fk' . $i];
            }
        }

        $this->applyDatasetFilters();

        if ($this->isInline()) {
            $this->totalRowCount = $this->dataset->GetTotalRowCount();
        }

        $this->RetrieveMasterDatasetValues();
    }

    private function applyDatasetFilters() {
        foreach ($this->foreignKeyFields as $i => $field) {
            $this->dataset->AddFieldFilter($field, new FieldFilter($this->foreignKeyValues[$i], '='));
            $this->dataset->SetMasterFieldValue($field, $this->foreignKeyValues[$i]);
        }

        $this->masterDataset->GetSelectCommand()->ClearAllFilters();
        foreach ($this->masterKeyFields as $i => $masterField) {
            $this->masterDataset->AddFieldFilter($masterField, new FieldFilter($this->foreignKeyValues[$i], '='));
            $this->masterDataset->SetMasterFieldValue($masterField, $this->foreignKeyValues[$i]);
        }
    }

    private function RetrieveMasterDatasetValues() {
        $this->masterDataset->Open();

        if ($this->masterDataset->Next()) {
            $this->masterRecordValues = $this->masterDataset->getCurrentFieldValues();
            for($i = 0; $i < count($this->parentMasterKeyFields); $i++) {
                $this->parentMasterKeyValues[] = $this->masterDataset->GetFieldValueByName($this->parentMasterKeyFields[$i]);
            }
        }

        $this->masterDataset->Close();
    }

    /** @inheritdoc */
    function Accept($visitor) {
        $visitor->RenderDetailPage($this);
    }

    public function GetHiddenGetParameters() {
        $result = parent::GetHiddenGetParameters();
        for($i = 0; $i < count($this->foreignKeyValues); $i++) {
            $result['fk' . $i] = $this->foreignKeyValues[$i];
        }

        return $result;
    }

    public function GetParentPageLink() {
        $result = $this->parentPage->CreateLinkBuilder();

        for($i = 0; $i < count($this->parentMasterKeyFields); $i++)
            $result->AddParameter('fk' . $i, $this->parentMasterKeyValues[$i]);

        return $result->GetLink();
    }

    public function CreateLinkBuilder($withViewMode = true) {
        $result = $this->parentPage->CreateLinkBuilder();
        $result->addParameter('hname', $this->GetHttpHandlerName());

        for($i = 0; $i < count($this->foreignKeyValues); $i++) {
            $result->AddParameter('fk' . $i, $this->foreignKeyValues[$i]);
        }

        if (!is_null($this->masterGrid) && $withViewMode) {
            $result->AddParameter('master_viewmode', $this->masterGrid->getViewMode());
        }


        return $result;
    }

    public function getLink($withViewMode = true) {
        return $this->CreateLinkBuilder($withViewMode)->getLink();
    }

    public function getFirstPageRecordCount() {
        $rowsPerPage = 20;
        $navigator = $this->getPageNavigator();
        if ($navigator instanceof CompositePageNavigator) {
            foreach ($navigator->GetPageNavigators() as $nav) {
                if ($nav instanceof pageNavigator) {
                    $rowsPerPage = $nav->GetRowsPerPage();
                    break;
                }
            }
        }

        return min($this->totalRowCount, $rowsPerPage);
    }

    /** @return integer */
    public function getTotalRowCount() {
        return $this->totalRowCount;
    }

    /** @return boolean */
    public function isInline() {
        return ArrayWrapper::createGetWrapper()->getValue('inline', false);
    }

    /** @inheritdoc */
    public function getNavigation($fieldValues = array()) {
        if ($fieldValues) {
            $this->foreignKeyValues = $fieldValues;
            $this->applyDatasetFilters();
            $this->RetrieveMasterDatasetValues();
        }

        $result = $this->parentPage->getNavigation($this->parentMasterKeyValues);

        return $result->append(
            $this->getTitle(),
            $this->getLink(),
            $this->getSiblingsNavigation()
        );
    }

    private function getSiblingsNavigation() {
        $details = $this->parentPage->getGrid()->getDetails();

        if (count($details) <= 1) {
            return null;
        }

        $result = new Navigation($this);
        $selfUrl = $this->getLink();

        foreach($details as $detail) {
            $url = $detail->GetSeparateViewLink();
            if ($url === $selfUrl) {
                continue;
            }

            $result->append($detail->getCaption(), $url);
        }

        return $result;
    }

    public function getViewId() {
        return implode('_', $this->foreignKeyValues);
    }

    /**
     * @param bool $showInlineCharts
     * @return $this
     */
    public function setShowInlineCharts($showInlineCharts) {
        $this->showInlineCharts = $showInlineCharts;
        return $this;
    }

    /** @return bool */
    public function getShowInlineCharts() {
        return $this->showInlineCharts;
    }

    public function getType() {
        return PageType::Data;
    }

    /** @return  array */
    public function getMasterRecordValues() {
        return $this->masterRecordValues;
    }

}
