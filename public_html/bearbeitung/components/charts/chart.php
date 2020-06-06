<?php

include_once dirname(__FILE__) . '/chart_column.php';

class Chart
{
    const TYPE_PIE = 'Pie';
    const TYPE_BAR = 'Bar';
    const TYPE_COLUMN = 'Column';
    const TYPE_LINE = 'Line';
    const TYPE_AREA = 'Area';
    const TYPE_GEO = 'Geo';
    const TYPE_CANDLESTICK = 'Candlestick';
    const TYPE_HISTOGRAM = 'Histogram';
    const TYPE_STEPPEDAREA = 'SteppedArea';
    const TYPE_BUBBLE = 'Bubble';
    const TYPE_TIMELINE = 'Timeline';
    const TYPE_GANTT = 'Gantt';
    const TYPE_TREEMAP = 'TreeMap';
    const TYPE_SCATTER = 'Scatter';

    /**
     * @var Dataset
     */
    private $dataset;

    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $sql;

    /**
     * @var string
     */
    private $title;

    /**
     * @var int
     */
    private $height = 200;

    /**
     * @var string
     */
    private $chartType;

    /**
     * @var array
     */
    private $options = array();

    /**
     * @var ChartColumn[]
     */
    private $columns = array();

    /**
     * @var array
     */
    private $domainColumn;

    /** @var string */
    private $generateTooltipFunction;

    /**
     * @param string  $id
     * @param string  $chartType
     * @param Dataset $dataset
     * @param string  $sql
     */
    public function __construct($id, $chartType, Dataset $dataset, $sql = '%source%')
    {
        $this->id = $id;
        $this->chartType = $chartType;
        $this->dataset = $dataset;
        $this->sql = $sql;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getChartType()
    {
        return $this->chartType;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @param int $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

    /**
     * @param string $column
     * @param string $label
     * @param string $type
     * @param string $format
     * @return ChartColumn
     */
    public function addDataColumn($column, $label, $type = 'float', $format = '')
    {
        if ($this->chartType === self::TYPE_PIE && count($this->columns) > 0) {
            throw new LogicException('Cannot have more than one data column in a pie chart');
        }

        $dataColumn = new ChartColumn($column, $label, $type, $format);
        $this->columns[] = $dataColumn;

        return $dataColumn;
    }

    /**
     * @param string $column
     * @param string $label
     * @param string $type
     * @param string $format
     * @return ChartColumn
     */
    public function setDomainColumn($column, $label, $type = 'float', $format = '')
    {
        $this->domainColumn = new ChartColumn($column, $label, $type, $format);

        return $this->domainColumn;
    }

    /**
     * @return array
     */
    public function getViewData()
    {
        $options = array_merge(array(
            'title' => $this->title,
            'height' => $this->height,
        ), $this->options);

        return array(
            'id' => $this->id,
            'data' => $this->getChartData(),
            'options' => $options,
            'height' => $options['height'],
            'generateTooltipFunctionCode' => $this->generateTooltipFunction
        );
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
    }

    /**
     * @param Renderer $renderer
     */
    public function Accept(Renderer $renderer)
    {
        $renderer->renderChart($this);
    }

    private function getChartData()
    {
        $rows = array();
        $rawRows = $this->fetchRows();

        $columns = $this->columns;
        if (isset($this->domainColumn)) {
            array_unshift($columns, $this->domainColumn);
        }

        foreach ($rawRows as $rawRow) {
            $row = array();

            foreach ($columns as $col) {
                $row[] = $this->castValue($rawRow[$col->getColumn()], $col->getType());
                foreach ($col->getHelperColumns() as $helperColumn) {
                    $row[] = $rawRow[$helperColumn];
                }
            }

            $rows[] = $row;
        }

        $columnsData = array();
        foreach ($columns as $col) {
            $columnsData[] = $col->getData();
            foreach ($col->getHelperColumnsData() as $data) {
                $columnsData[] = $data;
            }
        }

        return array(
            'columns' => $columnsData,
            'rows' => $rows,
        );
    }

    private function castValue($value, $type)
    {
        if (is_null($value)) {
            return null;
        }

        switch ($type) {
            case 'date':
                $date = new DateTime($value);
                return $date->format('Y-m-d');
            case 'datetime':
                $date = new DateTime($value);
                return $date->format('c');
            case 'int':
                return (int) $value;
            case 'number';
            case 'float':
                return (float) $value;
            default:
                return $value;
        }
    }

    private function fetchRows()
    {
        $conn = $this->dataset->GetConnection();

        $finalSql = str_replace(
            '%source%',
            $this->dataset->GetSelectCommand()->GetSQL(false, false),
            $this->sql
        );

        return $conn->fetchAll($finalSql);
    }

    /**
     * @return string
     */
    public function getSql() {
        return $this->sql;
    }

    /**
     * @param string $sql
     */
    public function setSql($sql) {
        $this->sql = $sql;
    }

    /**
     * @param string $value
     */
    public function setGenerateTooltipFunction($value) {
        $this->generateTooltipFunction = $value;
    }

    /**
     * @return string
     */
    public function getGenerateTooltipFunction() {
        return $this->generateTooltipFunction;
    }
}
