<?php

class GridBand {
    private $name;
    private $caption;
    private $columns;
    private $useConsolidatedHeader;

    public function  __construct($name, $caption, $useConsolidatedHeader = false) {
        $this->name = $name;
        $this->caption = $caption;
        $this->useConsolidatedHeader = $useConsolidatedHeader;
        $this->columns = array();
    }

    public function GetUseConsolidatedHeader() {
        return $this->useConsolidatedHeader;
    }

    public function GetColumnCount() {
        return count($this->columns);
    }

    public function GetName() {
        return $this->name;
    }

    public function HasColumns() {
        return $this->GetColumnCount() > 0;
    }

    public function AddColumn($column) {
        $this->columns[] = $column;
    }

    public function AddColumns($columns) {
        foreach ($columns as $column)
            $this->AddColumn($column);
    }

    /**
     * @return CustomViewColumn[]
     */
    public function GetColumns() {
        return $this->columns;
    }

    public function GetCaption() {
        return $this->caption;
    }

    public function SetCaption($value) {
        $this->caption = $value;
    }

    public function GetViewData() {
        $columnsViewData = array();
        foreach ($this->GetColumns() as $column) {
            /** CustomViewColumn $column */
            $columnsViewData[$column->GetName()] = $column->GetViewData();
        }
        return array(
            'Columns' => $columnsViewData,
            'ColumnCount' => $this->GetColumnCount(),
            'Caption' => $this->GetCaption(),
            'ConsolidateHeader' => $this->GetUseConsolidatedHeader()
        );
    }
}
