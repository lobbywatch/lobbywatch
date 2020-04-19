<?php

include_once dirname(__FILE__) . '/abstract_http_handler.php';

class ShowTextBlobHandler extends AbstractHTTPHandler
{
    /** @var IDataset */
    private $dataset;
    /** @var Page */
    private $parentPage;
    /** @var AbstractViewColumn */
    private $column;

    public function __construct($dataset, $parentPage, $name, $column) {
        parent::__construct($name);
        $this->dataset = $dataset;
        $this->parentPage = $parentPage;
        $this->column = $column;
    }

    public function Render(Renderer $renderer) {
        echo $renderer->Render($this);
    }

    public function Accept(Renderer $renderer) {
        $renderer->RenderTextBlobViewer($this);
    }

    public function GetParentPage() {
        return $this->parentPage;
    }

    public function GetCaption() {
        return $this->column->GetCaption();
    }

    public function GetValue(Renderer $renderer) {
        $result = '';
        $primaryKeyValues = array ( );
        ExtractPrimaryKeyValues($primaryKeyValues, METHOD_GET);

        $this->dataset->SetSingleRecordState($primaryKeyValues);
        $this->dataset->Open();
        if ($this->dataset->Next())
        {
            if ($this->column == null)
                ;//$result = $this->dataset->GetFieldValueByName($this->fieldName);
            else
                $result = $renderer->Render($this->column);
        }
        $this->dataset->Close();
        return $result;
    }
}
