<?php

include_once dirname(__FILE__) . '/' . 'page.php';

abstract class ViewBasedPage extends Page
{
    /**
     * @param Page $parentPage
     * @param PermissionSet $permissions
     */
    public function __construct(Page $parentPage, $permissions) {
        parent::__construct($parentPage->GetPageFileName(), null, $permissions, $parentPage->GetContentEncoding());
    }

    /** @inheritdoc */
    final protected function CreateGrid() {
        $grid = new Grid($this, $this->dataset);
        $this->AddSingleRecordViewColumns($grid);

        return $grid;
    }

    public function RegisterHandlers() {
        $handler = new RecordCardViewHandler($this->GetModalGridViewHandler(), new RecordCardView($this->GetGrid()));
        GetApplication()->RegisterHTTPHandler($handler);

        $this->doRegisterHandlers();
    }

    /** @param Grid $grid */
    abstract protected function AddSingleRecordViewColumns(Grid $grid);

    /** @inheritdoc */
    public function GetEnableModalSingleRecordView() {
        return true;
    }

    public function DoProcessMessages() {
    }

}
