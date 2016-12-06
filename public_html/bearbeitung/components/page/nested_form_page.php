<?php

abstract class NestedFormPage extends Page
{
    public function __construct($parentPage, $grants)
    {
        parent::__construct($parentPage->GetPageFileName(), null, $grants, $parentPage->GetContentEncoding());
    }

    final protected function CreateComponents()
    {
        $this->grid = $this->CreateGrid();
        $this->attachGridEventHandlers($this->grid);
        $this->attachEventHandlers();
        $this->setClientSideEvents($this->grid);
        $this->RegisterHandlers();
    }

    final protected function CreateGrid()
    {
        $grid = new Grid($this, $this->dataset, get_class($this) . '_grid');
        $grid->setAllowAddMultipleRecords(false);
        $this->AddInsertColumns($grid);

        return $grid;
    }

    public function DoProcessMessages()
    {
    }

    public function RegisterHandlers()
    {
        GetApplication()->RegisterHTTPHandler(new GridEditHandler(
            $this->GetGridInsertHandler(),
            new VerticalGrid($this->grid, OPERATION_INSERT, true)
        ));
    }

    /**
     * @return string
     */
    static public function getNestedInsertHandlerName()
    {
    }

    /**
     * @return bool
     */
    final public function GetEnableModalGridInsert()
    {
        return true;
    }
}
