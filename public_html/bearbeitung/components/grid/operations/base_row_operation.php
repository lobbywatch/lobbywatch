<?php

class BaseRowOperation
{
    /**
     * @var string
     */
    private $caption;

    /**
     * @var Dataset
     */
    private $dataset;

    /**
     * @var string|null
     */
    protected $operationName;

    /**
     * @var Grid
     */
    protected $grid;

    /**
     * @var bool
     */
    private $useImage;

    /**
     * @var Event
     */
    public $OnShow;

    /**
     * @param string $caption
     * @param Dataset $dataset
     * @param null $operationName
     * @param null $grid
     */
    function __construct($caption, $operationName, $dataset, $grid = null)
    {
        $this->caption = $caption;
        $this->dataset = $dataset;
        $this->grid = $grid;
        $this->operationName = $operationName;
        $this->useImage = true;
        $this->OnShow = new Event();
    }

    /**
     * @return Dataset
     */
    public function getDataset()
    {
        return $this->dataset;
    }

    /**
     * @return bool
     */
    public function isUseImage()
    {
        return $this->useImage;
    }

    /**
     * @param bool $value
     *
     * @return $this
     */
    public function setUseImage($value)
    {
        $this->useImage = $value;

        return $this;
    }

    /**
     * @return Grid
     */
    public function getGrid()
    {
        return $this->grid;
    }

    /**
     * @return string
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * @param string $caption
     *
     * @return $this
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;

        return $this;
    }

    protected function GetLinkParametersForPrimaryKey()
    {
        $result = array();
        $keyValues = $this->getDataset()->GetPrimaryKeyValues();
        for ($i = 0; $i < count($keyValues); $i++) {
            $result["pk$i"] = $keyValues[$i];
        }

        return $result;
    }

    public function GetLink()
    {
        return null;
    }

    public function GetValue()
    {
        return null;
    }

    public function GetName()
    {
        return $this->operationName;
    }

    public function GetData()
    {
        return $this->operationName;
    }

    public function isEditOperation() {
        return ($this->GetName() == OPERATION_EDIT);
    }

    public function GetIconClassByOperationName()
    {
        switch ($this->getName()) {
            case OPERATION_VIEW:
                return 'icon-view';
            case OPERATION_EDIT:
                return 'icon-edit';
            case OPERATION_DELETE:
                return 'icon-remove';
            case OPERATION_COPY:
                return 'icon-copy';
            case OPERATION_PDF_EXPORT_RECORD:
                return 'icon-file-pdf';
            case OPERATION_CSV_EXPORT_RECORD:
                return 'icon-file-csv';
            case OPERATION_EXCEL_EXPORT_RECORD:
                return 'icon-file-xls';
            case OPERATION_WORD_EXPORT_RECORD:
                return 'icon-file-doc';
            case OPERATION_XML_EXPORT_RECORD:
                return 'icon-file-xml';
            case OPERATION_PRINT_ONE:
                return 'icon-print-page';
        }

        return '';
    }
}
