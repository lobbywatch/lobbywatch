<?php

include_once dirname(__FILE__) . '/' . 'commands.php';
include_once dirname(__FILE__) . '/' . '../components/utils/string_utils.php';

abstract class AbstractSelectQueryBuilder {
    /** @var EngCommandImp */
    private $commandImp;
    /** @var string */
    private $fields;
    /** @var bool */
    private $isDistinct = false;
    /** @var string */
    private $joins;
    /** @var string */
    private $conditions;
    /** @var string */
    private $orderBy;
    /** @var int */
    private $limitNumber;
    /** @var int */
    private $limitOffset;

    /** @param EngCommandImp $commandImp */
    public function __construct($commandImp) {
        $this->commandImp = $commandImp;
    }

    /** @return EngCommandImp */
    public function getCommandImp() {
        return $this->commandImp;
    }

    /** @param FieldInfo[] $fields */
    public function addFields($fields) {
        foreach ($fields as $field) {
            $this->addField($field);
        }
    }

    /** @param FieldInfo $field */
    public function addField($field) {
        $fieldAsSql = $this->commandImp->GetFieldAsSQLInSelectFieldList($field);
        StringUtils::AddStr($this->fields, $fieldAsSql, ', ');
    }

    /** @param $expression */
    public function addFieldExpression($expression) {
        StringUtils::AddStr($this->fields, $expression, ', ');
    }

    /** @param bool $value */
    public function setIsDistinct($value) {
        $this->isDistinct = $value;
    }

    /** @return bool */
    public function getIsDistinct() {
        return $this->isDistinct;
    }

    /** @param JoinInfo $join */
    public function addJoin($join) {
        StringUtils::AddStr($this->joins, $this->commandImp->CreateJoinClause($join), ' ');
    }

    /** @return bool */
    protected function hasJoins() {
        return !empty($this->joins);
    }

    /**
     * @param FieldInfo $field
     * @param FieldFilter $fieldFilter
     */
    public function addFieldFilter($field, $fieldFilter) {
        StringUtils::AddStr($this->conditions, $this->commandImp->GetFilterConditionGenerator()->CreateCondition($fieldFilter, $field), ' AND ');
    }

    /** @param CompositeFilter $compositeFilter */
    public function addCompositeFilter($compositeFilter) {
        $condition = $this->commandImp->GetFilterConditionGenerator()->CreateCondition($compositeFilter, null);
        if (!empty($condition)) {
            StringUtils::AddStr($this->conditions, '(' . $condition . ')', ' AND ');
        }
    }

    /** @param $condition*/
    public function addCustomCondition($condition) {
        StringUtils::AddStr($this->conditions, '(' . $condition . ')', ' AND ');
    }

    /** @return bool */
    protected function hasConditions() {
        return !empty($this->conditions);
    }

    /**
     * @param string $expression
     * @param string $orderType
     */
    public function addSorting($expression, $orderType) {
        StringUtils::AddStr($this->orderBy, sprintf('%s %s', $expression, $orderType), ', ');
    }

    /** @return bool */
    protected function hasSorting() {
        return !empty($this->orderBy);
    }

    /**
     * @param int $limitNumber
     * @param int $limitOffset
     */
    public function addLimitation($limitNumber, $limitOffset) {
        $this->limitNumber = $limitNumber;
        $this->limitOffset = $limitOffset;
    }

    /** @return int */
    protected function getLimitNumber() {
        return $this->limitNumber;
    }

    /** @return int */
    protected function getLimitOffset() {
        return $this->limitOffset;
    }

    /** @return bool */
    protected function hasLimitation() {
        return isset($this->limitNumber) && isset($this->limitOffset);
    }

    /** @return string */
    private function getFieldsExpression() {
        if ($this->isDistinct) {
            return 'DISTINCT ' . $this->fields;
        } else {
            return $this->fields;
        }
    }

    /** @return string */
    protected abstract function getDataSourceExpression();

    /** @return string */
    public function getSQL() {
        $result = sprintf('SELECT %s FROM %s', $this->getFieldsExpression(), $this->getDataSourceExpression());
        if ($this->hasJoins()) {
            StringUtils::AddStr($result, $this->joins, ' ');
        }
        if ($this->hasConditions()) {
            StringUtils::AddStr($result, sprintf('WHERE %s', $this->conditions), ' ');
        }
        if ($this->hasSorting()) {
            StringUtils::AddStr($result, sprintf('ORDER BY %s', $this->orderBy), ' ');
        }
        if ($this->hasLimitation()) {
            $result = $this->commandImp->getSelectSQLWithLimitation($result, $this->limitNumber, $this->limitOffset, $this->hasSorting());
        }
        return $result;
    }

    public function clear() {
        $this->fields = '';
        $this->isDistinct = false;
        $this->joins = '';
        $this->conditions = '';
        $this->orderBy = '';
        $this->limitNumber = null;
        $this->limitOffset = null;
    }
}

class SelectQueryBuilder extends AbstractSelectQueryBuilder
{
    /** @var string */
    private $sourceTable;

    /** @return string */
    public function getSourceTable() {
        return $this->sourceTable;
    }

    /** @param string */
    public function setSourceTable($value) {
        $this->sourceTable = $value;
    }

    /** @inheritdoc */
    protected function getDataSourceExpression() {
        return $this->getCommandImp()->QuoteTableIdentifier($this->sourceTable);
    }

    public function clear() {
        parent::clear();
        $this->sourceTable = '';
    }
}

class CustomSelectQueryBuilder extends AbstractSelectQueryBuilder
{
    /** @var string */
    private $sql;
    /** @var string */
    private $sqlAlias;
    /** @var bool */
    private $alwaysWrapSql;

    /**
     * @param string $sql
     * @param string $sqlAlias
     */
    public function setSQLInfo($sql, $sqlAlias) {
        $this->sql = $sql;
        $this->sqlAlias = $sqlAlias;
    }

    /** @inheritdoc */
    protected function getDataSourceExpression() {
        return sprintf('(%s) %s', $this->sql, $this->getCommandImp()->QuoteTableIdentifier($this->sqlAlias));
    }

    /** @return bool */
    public function getAlwaysWrapSql() {
        return $this->alwaysWrapSql;
    }

    /** @param bool */
    public function setAlwaysWrapSql($value) {
        $this->alwaysWrapSql = $value;
    }

    /** @inheritdoc */
    public function addFieldExpression($expression) {
        parent::addFieldExpression($expression);
        $this->alwaysWrapSql = true;
    }

    /** @inheritdoc */
    public function getSQL() {
        if ($this->getIsDistinct() || $this->hasJoins() || $this->hasConditions() || $this->hasSorting() || $this->hasLimitation() || $this->alwaysWrapSql) {
            return parent::getSQL();
        } else {
            return $this->sql;
        }
   }

    public function clear() {
        parent::clear();
        $this->sql = '';
        $this->sqlAlias = '';
        $this->alwaysWrapSql = false;
    }
}
