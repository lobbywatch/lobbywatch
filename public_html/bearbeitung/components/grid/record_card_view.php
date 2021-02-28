<?php

class RecordCardView
{
    /** @var \Grid */
    private $grid;

    public function __construct(Grid $grid)
    {
        $this->grid = $grid;
    }

    /**
     * @return Grid
     */
    public function GetGrid()
    {
        return $this->grid;
    }

    public function ProcessMessages()
    {
        $this->grid->getPage()->UpdateValuesFromUrl();
        GetApplication()->SetOperation(OPERATION_VIEW);
        $this->grid->SetState(OPERATION_VIEW);
        $this->grid->ProcessMessages();
    }

    public function Accept(Renderer $renderer)
    {
        $renderer->RenderRecordCardView($this);
    }

    public function isInline() {
        return (bool) ArrayWrapper::createGetWrapper()->getValue('is_inline', false);
    }

}
