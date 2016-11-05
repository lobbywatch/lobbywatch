<?php

include_once dirname(__FILE__) . '/' . 'engine.php';
include_once dirname(__FILE__) . '/' . 'commands.php';
include_once dirname(__FILE__) . '/' . 'filterable.php';
include_once dirname(__FILE__) . '/' . '../components/utils/string_utils.php';

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

    #region Joins

    protected final function HasJoins() {
        return count($this->joins) > 0;
    }

    protected final function GetJoinsClause() {
        $result = '';
        foreach ($this->joins as $joinInfo)
            StringUtils::AddStr($result, $this->GetCommandImp()->CreateJoinClause($joinInfo), ' ');
        return $result;
    }

    public final function AddJoin($joinKind, $table, $fieldName, $linkField, $tableAlias = null) {
        $this->joins[] = new JoinInfo(
            $joinKind, $table,
            $this->GetFieldByName($fieldName),
            $linkField,
            $tableAlias);
    }

    #endregion

    #region Ordering

    protected final function HasOrdering() {
        return count($this->sortedColumns) > 0;
    }

    protected final function GetOrderByClause()
    {
        if ($this->HasOrdering()) {
            $orderByFields = '';
            foreach ($this->sortedColumns as $currentColumnToSort) {
                if (is_numeric($currentColumnToSort->getFieldName())) {
                    StringUtils::AddStr(
                        $orderByFields,
                        $currentColumnToSort->getFieldName() . ' ' . $currentColumnToSort->getSQLOrderType(),
                        ', '
                    );
                    continue;
                }
                $field = $this->GetFieldByName($currentColumnToSort->getFieldName());
                if (!is_null($field)) {
                    StringUtils::AddStr(
                        $orderByFields,
                        $this->GetCommandImp()->GetFieldFullName($field).' '.$currentColumnToSort->getSQLOrderType(),
                        ', '
                    );
                }
            }

            if ($orderByFields) {
                return 'ORDER BY '.$orderByFields;
            }
        }

        return '';
    }

    public final function GetFieldFullName(FieldInfo $field)
    {
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
     */
    public final function addDistinct($fieldName, $asDate = false)
    {
        $this->distincts[] = $fieldName;

        if ($asDate) {
            $this->distinctsAsDate[] = $fieldName;
        }
    }

    #endregion

    #region Fields

    protected final function GetFieldListClause() {
        $result = StringUtils::EmptyStr;
        foreach ($this->GetFields() as $field) {
            $fieldAsSql = $this->GetCommandImp()->GetFieldAsSQLInSelectFieldList($field);

            if (!in_array($field->getNameInDataset(), $this->selects)) {
                continue;
            }

            if (in_array($field->getNameInDataset(), $this->distincts)) {
                $fieldAsSql = 'DISTINCT ' . (in_array($field->getNameInDataset(), $this->distinctsAsDate)
                    ? $this->GetCommandImp()->GetCastToDateExpression($this->GetCommandImp()->getFieldFullName($field)) . ' AS ' . $this->GetCommandImp()->QuoteIdentifier($field->getNameInDataset())
                    : $fieldAsSql);
            }

            StringUtils::AddStr($result, $fieldAsSql, ', ');
        }
        return $result;
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
    public final function AddFieldInfo(FieldInfo $fieldInfo)
    {
        if (!in_array($fieldInfo, $this->fieldInfos)) {
            $this->fieldInfos[] = $fieldInfo;
            $this->selects[] = $fieldInfo->getNameInDataset();
        }
    }

    public final function setSelects(array $selects)
    {
        $this->selects = $selects;
    }

    public final function GetFieldByName($name)
    {
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

    #endregion

    #region Record count limits

    protected function DoGetLimitClause($limitCount, $upLimit) {
        return $this->GetCommandImp()->GetLimitClause($limitCount, $upLimit);
    }

    protected final function GetLimitClause() {
        $result = '';
        $upLimit = $this->GetUpLimit();
        $limitCount = $this->GetLimitCount();
        if (isset($upLimit) && isset($limitCount)) {
            if ($limitCount <= 0)
                $limitCount = 1;
            if ($upLimit < 0)
                $upLimit = 0;
            $result = $this->DoGetLimitClause($limitCount, $upLimit);
        }
        return $result;
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

    #endregion

    #region Filters

    public final function HasCondition() {
        $condition = $this->GetFieldFilterCondition();
        return !empty($condition);
    }

    protected final function GetFieldFilterCondition() {
        $result = '';

        foreach ($this->fieldFilters as $fieldName => $filters)
            foreach ($filters as $filter)
                StringUtils::AddStr($result,
                    $this->GetCommandImp()->GetFilterConditionGenerator()->CreateCondition(
                        $filter, $this->GetFieldByName($fieldName)),
                    ' AND ');

        foreach ($this->compositeFieldFilters as $filter) {
            $condition = $this->GetCommandImp()
                ->GetFilterConditionGenerator()
                ->CreateCondition($filter, null);

            if (!empty($condition)) {
                StringUtils::AddStr($result, '(' . $condition . ')', ' AND ');
            }
        }

        foreach ($this->customConditions as $condition)
            StringUtils::AddStr($result, '(' . $condition . ')', ' AND ');

        return $result;
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

    public final function ClearFieldFilters() {
        foreach (array_keys($this->fieldFilters) as $fieldName) {
            unset($this->fieldFilters[$fieldName]);
        }
    }

    #endregion

    #region Command building result

    public abstract function GetSQL();

    public abstract function GetSelectRecordCountSQL();

    #endregion
}

class CustomSelectCommand extends BaseSelectCommand {
    const CustomSelectSubqueryAlias = 'SM_SOURCE_SQL';

    /** @var string */
    private $sql;

    /**
     * @var string[]
     */
    private $groupBy = array();

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

    /**
     * @return string
     */
    public function GetSQL() {
        if ($this->HasCondition() || $this->HasJoins() || $this->HasOrdering() || $this->hasGroupBy()) {
            $fieldList = $this->GetFieldListClause();

            $result = 'SELECT ' . $fieldList . ' FROM (' . $this->sql . ') ' . $this->GetCommandImp()->QuoteTableIdentifier(self::CustomSelectSubqueryAlias);

            StringUtils::AddStr($result, $this->GetJoinsClause(), StringUtils::Space);
            StringUtils::AddStr($result, $this->GetFieldFilterCondition(), ' WHERE ');
            StringUtils::AddStr($result, $this->GetGroupByClause(), StringUtils::Space);
            StringUtils::AddStr($result, $this->GetOrderByClause(), StringUtils::Space);
        } else {
            $result = $this->sql;
        }

        return $result;
    }

    public function GetSelectRecordCountSQL() {
        $result = 'SELECT COUNT(*) FROM (' . $this->sql . ') ' . $this->GetCommandImp()->QuoteTableIdentifier(self::CustomSelectSubqueryAlias);

        StringUtils::AddStr($result, $this->GetJoinsClause(), ' ');
        StringUtils::AddStr($result, $this->GetFieldFilterCondition(), ' WHERE ');

        return $result;
    }

    public function Execute(EngConnection $connection) {
        $this->GetCommandImp()->SetServerVersion($connection->GetServerVersion());
        return $this->GetCommandImp()->ExecuteCustomSelectCommand($connection, $this);
    }

    #endregion

    /**
     * @param array $fields
     */
    public final function setGroupBy(array $fields)
    {
        $this->groupBy = $fields;
    }

    /**
     * @return boolean
     */
    public final function hasGroupBy()
    {
        return count($this->groupBy) > 0;
    }

    protected final function GetGroupByClause()
    {
        if (!$this->hasGroupBy()) {
            return '';
        }

        $fieldNames = array();
        foreach ($this->groupBy as $fieldName) {
            $field = $this->GetFieldByName($fieldName);
            $fieldNames[] = $this->GetCommandImp()->GetFieldFullName($field);
        }

        return ' GROUP BY ' . implode(',', $fieldNames);
    }
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

    public function GetSQL($withLimit = true) {
        $fieldList = $this->GetFieldListClause();

        $afterSelectSql = $this->GetCommandImp()->GetAfterSelectSQL($this, $withLimit);
        if ($afterSelectSql != '')
            $afterSelectSql = ' ' . $afterSelectSql;

        $result = "SELECT$afterSelectSql $fieldList FROM " .
            $this->GetCommandImp()->QuoteTableIdentifier($this->sourceTable) .
            ((isset($this->sourceTableAlias) && $this->sourceTableAlias != '') ? ' ' . $this->sourceTableAlias : '');


        StringUtils::AddStr($result, $this->GetJoinsClause(), ' ');
        StringUtils::AddStr($result, $this->GetFieldFilterCondition(), ' WHERE ');
        StringUtils::AddStr($result, $this->GetOrderByClause(), ' ');

        if ($withLimit) {
            StringUtils::AddStr($result, $this->GetLimitClause(), ' ');
        }


        return $result;
    }

    public function GetSelectRecordCountSQL() {
        $result = 'SELECT COUNT(*) FROM ' . $this->GetCommandImp()->QuoteTableIdentifier($this->sourceTable);

        StringUtils::AddStr($result, $this->GetJoinsClause(), ' ');
        StringUtils::AddStr($result, $this->GetFieldFilterCondition(), ' WHERE ');

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
            $this->selectCommand->GetSQL(false),
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
