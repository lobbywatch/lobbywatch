<?php

include_once dirname(__FILE__) . '/' . 'engine.php';
include_once dirname(__FILE__) . '/' . 'commands.php';
include_once dirname(__FILE__) . '/' . 'filterable.php';
include_once dirname(__FILE__) . '/' . '../components/utils/string_utils.php';
include_once dirname(__FILE__) . '/' . 'select_query_builder.php';

abstract class BaseSelectCommand extends EngCommand implements IFilterable {
    private $upLimit;
    private $limitCount;
    private $fieldInfos;
    private $distincts = array();
    private $distinctsAsDate = array();

    /** @var SortColumn[] */
    private $sortedColumns;

    private $joins;

    private $fieldFilters;
    private $compositeFieldFilters;
    private $customConditions;
    private $selects;

    public function __construct(EngCommandImp $engCommandImp) {
        parent::__construct($engCommandImp);
        $this->upLimit = null;
        $this->limitCount = null;
        $this->fieldInfos = array();
        $this->sortedColumns = array();
        $this->joins = array();
        $this->compositeFieldFilters = array();
        $this->customConditions = array();
        $this->fieldFilters = array();
    }

    public final function AddJoin($joinKind, $table, $fieldName, $linkField, $tableAlias = null) {
        $this->joins[] = new JoinInfo(
            $joinKind, $table,
            $this->GetFieldByName($fieldName),
            $linkField,
            $tableAlias);
    }

    public final function GetFieldFullName(FieldInfo $field) {
        return $this->GetCommandImp()->GetFieldFullName($field);
    }

    /**
     * @param SortColumn[] $sortedColumns
     * @return void
     */
    public final function SetOrderBy($sortedColumns) {
        $this->sortedColumns = $sortedColumns;
    }

    /**
     * @param string $fieldName
     * @param bool $asDate
     */
    public final function addDistinct($fieldName, $asDate = false) {
        $this->distincts[] = $fieldName;

        if ($asDate) {
            $this->distinctsAsDate[] = $fieldName;
        }
    }

    /**
     * @param string $tableName
     * @param string $fieldName
     * @param int $fieldType See FieldType enum
     * @param string $alias
     * @return FieldInfo
     *
     */
    protected function DoCreateFieldInfo($tableName, $fieldName, $fieldType, $alias) {
        return new FieldInfo($tableName, $fieldName, $fieldType, $alias);
    }

    /**
     * @param string $tableName
     * @param string $fieldName
     * @param int $fieldType See FieldType enum
     * @param string $alias
     *
     */
    public final function AddField($tableName, $fieldName, $fieldType, $alias) {
        $this->AddFieldInfo($this->DoCreateFieldInfo($tableName, $fieldName, $fieldType, $alias));
    }

    /**
     * @param FieldInfo $fieldInfo
     */
    public final function AddFieldInfo(FieldInfo $fieldInfo) {
        if (!in_array($fieldInfo, $this->fieldInfos)) {
            $this->fieldInfos[] = $fieldInfo;
            $this->selects[] = $fieldInfo->getNameInDataset();
        }
    }

    public final function setSelects(array $selects) {
        $this->selects = $selects;
    }

    public final function GetFieldByName($name) {
        foreach ($this->fieldInfos as $field) {
            if (isset($field->Alias) && $field->Alias != '' && $field->Alias == $name) {
                return $field;
            } elseif ($field->Name == $name) {
                return $field;
            }
        }
        return null;
    }

    public final function GetFields() {
        return $this->fieldInfos;
    }

    public final function GetUpLimit() {
        return $this->upLimit;
    }

    public final function SetUpLimit($upLimit) {
        $this->upLimit = $upLimit;
    }

    public final function GetLimitCount() {
        return $this->limitCount;
    }

    public final function SetLimitCount($limitCount) {
        $this->limitCount = $limitCount;
    }

    #region Filters

    public final function HasCondition() {
        return empty($this->fieldFilters) && empty($this->compositeFieldFilters) && empty($this->customConditions);
    }

    public final function AddFieldFilter($fieldName, $fieldFilter) {
        $this->fieldFilters[$fieldName][] = $fieldFilter;
    }

    public final function RemoveFieldFilter($fieldName, $fieldFilter) {
        if (isset($this->fieldFilters[$fieldName]))
            unset($this->fieldFilters[$fieldName][array_search($fieldFilter, $this->fieldFilters[$fieldName])]);
    }

    public final function AddCompositeFieldFilter($filterLinkType, $fieldNames, $fieldFilters) {
        $compositeFilter = new CompositeFilter($filterLinkType);
        for ($i = 0; $i < count($fieldNames); $i++)
            $compositeFilter->AddFilter(
                $this->GetFieldByName($fieldNames[$i]),
                $fieldFilters[$i]);
        $this->compositeFieldFilters[] = $compositeFilter;
    }

    public final function AddCompositeFilter(CompositeFilter $compositeFilter)
    {
        $this->compositeFieldFilters[] = $compositeFilter;
    }

    public final function AddCustomCondition($condition) {
        if (!StringUtils::IsNullOrEmpty($condition))
            $this->customConditions[] = $condition;
    }

    public final function ClearAllFilters() {
        $this->ClearFieldFilters();
        $this->ClearCompositeFieldFilters();
        $this->ClearCustomConditions();
    }

    public function ClearFieldFilters() {
        $this->fieldFilters = array();
    }

    public function ClearCompositeFieldFilters() {
        $this->compositeFieldFilters = array();
    }

    public function ClearCustomConditions() {
        $this->customConditions = array();
    }
    #endregion

    /**
     * @param bool $withLimit
     * @param bool $withOrderBy
     * @return string
     */
    public function GetSQL($withLimit = true, $withOrderBy = true) {
        $builder = $this->createSelectQueryBuilder();
        $this->buildFieldList($builder);
        $this->buildJoins($builder);
        $this->buildConditions($builder);
        if ($withLimit) {
            $this->buildLimitation($builder);
        }
        if ($withOrderBy) {
            $this->buildOrderBy($builder);
        }
        return $builder->getSQL();
    }

    /** @return string */
    public function GetSelectRecordCountSQL() {
        $builder = $this->createSelectQueryBuilder();
        $builder->addFieldExpression('COUNT(*)');
        $this->buildJoins($builder);
        $this->buildConditions($builder);

        return $builder->getSQL();
    }

    /** @return AbstractSelectQueryBuilder */
    public abstract function createSelectQueryBuilder();

    /** @param AbstractSelectQueryBuilder $builder */
    protected function buildFieldList($builder) {
        foreach ($this->GetFields() as $field) {
            if (!in_array($field->getNameInDataset(), $this->selects)) {
                continue;
            }
            if (in_array($field->getNameInDataset(), $this->distincts)) {
                $builder->setIsDistinct(true);
                if (in_array($field->getNameInDataset(), $this->distinctsAsDate)) {
                    $builder->addFieldExpression(
                        $this->GetCommandImp()->GetCastToDateExpression($this->GetCommandImp()->getFieldFullName($field)) . ' AS ' .
                            $this->GetCommandImp()->QuoteIdentifier($field->getNameInDataset()));
                } else {
                    $builder->addField($field);
                }
            } else {
                $builder->addField($field);
            }
        }
    }

    /** @param AbstractSelectQueryBuilder $builder */
    protected function buildJoins($builder) {
        foreach ($this->joins as $joinInfo) {
            $builder->addJoin($joinInfo);
        }
    }

    /** @param AbstractSelectQueryBuilder $builder */
    protected function buildConditions($builder) {
        foreach ($this->fieldFilters as $fieldName => $filters) {
            foreach ($filters as $filter) {
                $builder->addFieldFilter($this->GetFieldByName($fieldName), $filter);
            }
        }

        foreach ($this->compositeFieldFilters as $filter) {
            $builder->addCompositeFilter($filter);
        }

        foreach ($this->customConditions as $condition) {
            $builder->addCustomCondition($condition);
        }
    }

    /** @param AbstractSelectQueryBuilder $builder */
    protected function buildOrderBy($builder) {
        foreach ($this->sortedColumns as $sortedColumn) {
            if (is_numeric($sortedColumn->getFieldName())) {
                $builder->addSorting($sortedColumn->getFieldName(), $sortedColumn->getSQLOrderType());
            } else {
                $field = $this->GetFieldByName($sortedColumn->getFieldName());
                if (!is_null($field)) {
                    $builder->addSorting($this->GetCommandImp()->GetFieldFullName($field), $sortedColumn->getSQLOrderType());
                }
            }
        }
    }

    /** @param AbstractSelectQueryBuilder $builder */
    protected function buildLimitation($builder) {
        $limitNumber = $this->GetLimitCount();
        $limitOffset = $this->GetUpLimit();
        if (isset($limitNumber) && isset($limitOffset)) {
            if ($limitNumber <= 0) {
                $limitNumber = 1;
            }
            if ($limitOffset < 0) {
                $limitOffset = 0;
            }
            $builder->addLimitation($limitNumber, $limitOffset);
        }
    }

}

class CustomSelectCommand extends BaseSelectCommand {
    const CustomSelectSubqueryAlias = 'SM_SOURCE_SQL';

    /** @var string */
    private $sql;

    public function __construct(EngCommandImp $engCommandImp, $sql) {
        parent::__construct($engCommandImp);
        $this->sql = $sql;
    }

    public function DoCreateFieldInfo($tableName, $fieldName, $fieldType, $alias) {
        if (!isset($tableName) || $tableName == '')
            return parent::DoCreateFieldInfo(
                $this->GetCommandImp()->QuoteTableIdentifier(self::CustomSelectSubqueryAlias),
                $fieldName, $fieldType, $alias);
        else
            return parent::DoCreateFieldInfo($tableName, $fieldName, $fieldType, $alias);
    }

    #region Command building result

    /** @inheritdoc */
    public function createSelectQueryBuilder() {
        $result = new CustomSelectQueryBuilder($this->GetCommandImp());
        $result->setSQLInfo($this->sql, self::CustomSelectSubqueryAlias);
        return $result;
    }

    public function Execute(EngConnection $connection) {
        $this->GetCommandImp()->SetServerVersion($connection->GetServerVersion());
        return $this->GetCommandImp()->ExecuteCustomSelectCommand($connection, $this);
    }

    #endregion
}

class SelectCommand extends BaseSelectCommand {
    /** @var string */
    private $sourceTable;
    /** @var string */
    private $sourceTableAlias;

    public function __construct(EngCommandImp $engCommandImp) {
        parent::__construct($engCommandImp);
    }

    public function SetSourceTableName($sourceTable, $sourceTableAlias = null) {
        $this->sourceTable = $sourceTable;
        $this->sourceTableAlias = $sourceTableAlias;
    }

    #region Command building result

    /** @inheritdoc */
    public function createSelectQueryBuilder() {
        $result = new SelectQueryBuilder($this->GetCommandImp());
        $result->setSourceTable($this->sourceTable);
        return $result;
    }

    public function Execute(EngConnection $connection) {
        $this->GetCommandImp()->SetServerVersion($connection->GetServerVersion());
        return $this->GetCommandImp()->ExecuteSelectCommand($connection, $this);
    }

    #endregion
}

class FieldAggregationInfo {
    /** @var Aggregate */
    private $aggregate;

    /** @var string */
    private $alias;

    /**
     * @param Aggregate $aggregate
     * @param string $alias
     */
    public function __construct(Aggregate $aggregate, $alias = '') {
        $this->aggregate = $aggregate;
        $this->alias = $alias;
    }

    /**
     * @return Aggregate
     */
    public function GetAggregate() {
        return $this->aggregate;
    }

    public function GetAlias() {
        return $this->alias;
    }

    public function HasAlias() {
        return !StringUtils::IsNullOrEmpty($this->alias);
    }
}

class AggregationValuesQuery {
    private $selectCommand;
    private $engCommandImp;

    /** @var FieldAggregationInfo[] */
    private $fieldAggregations;

    const SubQueryAlias = 'SM_AGGR_ALIAS';

    public function __construct(BaseSelectCommand $selectCommand, EngCommandImp $engCommandImp) {
        $this->selectCommand = $selectCommand;
        $this->engCommandImp = $engCommandImp;
        $this->fieldAggregations = array();
    }

    public function AddAggregate($fieldName, Aggregate $aggregate, $alias = '') {
        $this->fieldAggregations[$fieldName] = new FieldAggregationInfo($aggregate, $alias);
    }

    public function GetSQL() {
        return StringUtils::Format(
            'SELECT %s FROM (%s) %s',
            $this->GetFieldListAsSQL(),
            $this->selectCommand->GetSQL(false, false),
            self::SubQueryAlias);
    }

    private function GetFieldListAsSQL() {
        $result = '';
        foreach ($this->fieldAggregations as $fieldName => $aggregationInfo)
            if (!$aggregationInfo->HasAlias())
                StringUtils::AddStr($result,
                    $aggregationInfo->GetAggregate()->AsSQL($this->engCommandImp->QuoteIdentifier($fieldName)),
                    ', ');
            else
                StringUtils::AddStr($result,
                    $this->engCommandImp->GetAliasedAsFieldExpression(
                        $aggregationInfo->GetAggregate()->AsSQL($this->engCommandImp->QuoteIdentifier($fieldName)),
                        $this->engCommandImp->QuoteIdentifier($aggregationInfo->GetAlias())
                    ),
                    ', ');
        return $result;
    }

    public function Execute(EngConnection $connection) {
        return $this->engCommandImp->ExecuteReader($connection, $this->GetSQL());
    }
}
