<?php
// Processed by afterburner.sh



include_once dirname(__FILE__) . '/' . 'engine.php';
include_once dirname(__FILE__) . '/' . 'database_engine_utils.php';
include_once dirname(__FILE__) . '/' . '../components/common_utils.php';
include_once dirname(__FILE__) . '/' . '../components/error_utils.php';
include_once dirname(__FILE__) . '/field_filters/between_field_filter.php';
include_once dirname(__FILE__) . '/field_filters/composite_field_filter.php';
include_once dirname(__FILE__) . '/field_filters/field_filter.php';
include_once dirname(__FILE__) . '/field_filters/is_blank_field_filter.php';
include_once dirname(__FILE__) . '/field_filters/is_null_field_filter.php';
include_once dirname(__FILE__) . '/field_filters/not_predicate_field_filter.php';
include_once dirname(__FILE__) . '/field_filters/date_field_filter.php';
include_once dirname(__FILE__) . '/field_filters/date_part_field_filter.php';
include_once dirname(__FILE__) . '/field_filters/in_field_filter.php';

class FieldType
{
    const Number     = 1;
    const String     = 2;
    const Blob       = 3;
    const DateTime   = 4;
    const Date       = 5;
    const Time       = 6;
    const Boolean    = 7;
}

class JoinKind
{
    const LeftOuter =  1;
    const Inner     =  2;
}

abstract class Aggregate
{
    public abstract function AsString();

    public abstract function AsSQL($fieldAsSQL);
}

class PredefinedAggregate extends Aggregate
{
    public static $Average;
    public static $Sum;
    public static $Max;
    public static $Min;
    public static $Count;

    public static function Init()
    {
        self::$Average = new PredefinedAggregate('AVG');
        self::$Sum = new PredefinedAggregate('SUM');
        self::$Max = new PredefinedAggregate('MAX');
        self::$Min = new PredefinedAggregate('MIN');
        self::$Count = new PredefinedAggregate('COUNT');
    }

    private $functionAsSQL;

    public function __construct($functionAsSQL)
    {
        $this->functionAsSQL = $functionAsSQL;
    }

    public function AsSQL($fieldAsSQL)
    {
        return StringUtils::Format('%s(%s)', $this->functionAsSQL, $fieldAsSQL);
    }

    public function AsString()
    {
        return $this->functionAsSQL;
    }
}
PredefinedAggregate::Init();

class CustomAggregate extends Aggregate
{
    private $expression;
    private $expressionAsString;

    public function __construct($expression, $expressionAsString)
    {
        $this->expression = $expression;
        $this->expressionAsString = $expressionAsString;
    }

    public function AsSQL($fieldAsSQL)
    {
        return $this->expression;
    }

    public function AsString()
    {
        return $this->expressionAsString;
    }
}

#region Deprecated

define('ftNumber', 1);
define('ftString', 2);
define('ftBlob', 3);
define('ftDateTime', 4);
define('ftDate', 5);
define('ftTime', 6);
define('ftBoolean', 7);

define('jkLeftOuter', 1);

#endregion

class JoinInfo
{
    public $JoinKind;
    public $Table;
    public $Field;
    public $LinkField;
    public $TableAlias;

    public function __construct($joinKind, $table, FieldInfo $field, $linkField, $tableAlias)
    {
        $this->JoinKind = $joinKind;
        $this->Table = $table;
        $this->Field = $field;
        $this->LinkField = $linkField;
        $this->TableAlias = $tableAlias;
    }
}

class FilterConditionGenerator {
    /** @var string */
    private $resultCondition;

    /** @var FieldInfo */
    private $field;

    /** @var EngCommandImp */
    private $engCommandImp;

    /**
     * @param EngCommandImp $engCommandImp
     */
    public function __construct($engCommandImp)
    {
        $this->engCommandImp = $engCommandImp;
    }

    /**
     * @param FieldFilter $filter
     * @param FieldInfo $field
     * @return string
     */
    public function CreateCondition($filter, $field)
    {
        $oldResultCondition = $this->resultCondition;

        $oldField = $this->field;
        $this->field = $field;

        $filter->Accept($this);

        $this->field = $oldField;
        $result = $this->resultCondition;
        $this->resultCondition = $oldResultCondition;

        return $result;
    }

    public function VisitDateFieldFilter(DateFieldFilter $filter)
    {
        $this->resultCondition = sprintf(
            '%s %s %s',
            $this->engCommandImp->GetCastToDateExpression(
                $this->engCommandImp->GetFieldFullName($this->field)
            ),
            $filter->getOperator(),
            $this->engCommandImp->GetCastToDateExpression(
                $this->engCommandImp->GetValueAsSQLString(
                    $filter->getValue()
                )
            )
        );
    }

    public function VisitDatePartFieldFilter(DatePartFieldFilter $filter)
    {
        $this->resultCondition = sprintf(
            '%s %s %s',
            $this->engCommandImp->GetDatePartExpression(
                $this->field,
                $filter->GetPart()
            ),
            $filter->getOperator(),
            $this->engCommandImp->GetValueAsSQLString(
                $filter->getValue()
            )
        );
    }

    /**
     * @param FieldFilter $filter
     * @return void
     */
    public function VisitFieldFilter($filter)
    {
        if ($filter->GetIgnoreFieldDataType())
        {
            if ($filter->GetFilterType() == 'LIKE')
            {
                $this->resultCondition = $this->engCommandImp->GetCaseSensitiveLikeExpression(
                    $this->field,
                    $filter->GetValue()
                    );
            }
            elseif ($filter->GetFilterType() == 'ILIKE')
            {
                $this->resultCondition = $this->engCommandImp->GetCaseInsensitiveLikeExpression(
                    $this->field,
                    $filter->GetValue()
                    );
            }
            else
                $this->resultCondition =
                    $this->engCommandImp->GetCastedToCharFieldExpression($this->field) . ' ' .
                    $filter->GetFilterType() . ' ' .
                    $this->engCommandImp->GetValueAsSQLString($filter->GetValue());
        }
        else
        {
            $value = $filter->GetValue();
            if ($value === '' && $this->field->FieldType == ftNumber)
            {
                if ($filter->GetFilterType() == '=')
                    $this->resultCondition = $this->engCommandImp->GetIsNullCondition($this->engCommandImp->GetFieldFullName($this->field));
                elseif($filter->GetFilterType() == '<>')
                    $this->resultCondition = sprintf('NOT (%s)', $this->engCommandImp->GetIsNullCondition($this->engCommandImp->GetFieldFullName($this->field)));
            }
            elseif (isset($value))
            {
                if ($filter->GetFilterType() == 'LIKE')
                {
                    $this->resultCondition = $this->engCommandImp->GetCaseSensitiveLikeExpression(
                        $this->field,
                        $filter->GetValue()
                        );
                }
                elseif ($filter->GetFilterType() == 'ILIKE')
                {
                    $this->resultCondition = $this->engCommandImp->GetCaseInsensitiveLikeExpression(
                        $this->field,
                        $filter->GetValue()
                        );
                }
                else
                {
                    $this->resultCondition =
                        $this->engCommandImp->GetFieldFullName($this->field) . ' ' .
                        $filter->GetFilterType() . ' ' .
                        $this->engCommandImp->GetFieldValueAsSQL($this->field, $filter->GetValue());
                }
            }
            else
            {
                if ($filter->GetFilterType() == '=')
                    $this->resultCondition = $this->engCommandImp->GetIsNullCondition($this->engCommandImp->GetFieldFullName($this->field));
                elseif($filter->GetFilterType() == '<>')
                    $this->resultCondition = sprintf('NOT (%s)', $this->engCommandImp->GetIsNullCondition($this->engCommandImp->GetFieldFullName($this->field)));
            }
        }

    }

    /**
     * @param BetweenFieldFilter $filter
     * @return void
     */
    public function VisitBetweenFieldFilter($filter)
    {
        $this->resultCondition =
            sprintf('(%s BETWEEN %s AND %s)',
                $this->engCommandImp->GetFieldFullName($this->field),
                $this->engCommandImp->GetFieldValueAsSQL($this->field, $filter->GetStartValue()),
                $this->engCommandImp->GetFieldValueAsSQL($this->field, $filter->GetEndValue()));
    }

    public function VisitIsBlankFieldFilter($filter)
    {
        $this->resultCondition = sprintf('(%s)', $this->engCommandImp->GetIsNullExpression($this->field));
        if ($this->field->FieldType == ftString) {
            $this->resultCondition = sprintf('(' . $this->resultCondition . ' OR (%s = \'\'))',
                $this->engCommandImp->GetCastedToCharFieldExpression($this->field));
        }
    }

    /**
     * @param NotPredicateFilter $filter
     * @return void
     */
    public function  VisitNotPredicateFilter($filter)
    {
        $this->resultCondition = sprintf(
            'NOT (%s)',
            $this->CreateCondition($filter->InnerFilter, $this->field)
        );

        if ($filter->InnerFilter instanceOf FieldFilter
            && (
                $filter->InnerFilter->GetFilterType() == 'LIKE'
                || $filter->InnerFilter->GetFilterType() == 'ILIKE'
            )
        ) {
            $this->resultCondition .= sprintf(
                ' OR %s IS NULL',
                $this->engCommandImp->GetFieldFullName($this->field)
            );
        }
    }

    /**
     * @param CompositeFilter $filter
     * @return void
     */
    public function VisitCompositeFilter($filter)
    {
        $this->resultCondition = '';

        foreach($filter->GetInnerFilters() as $filterInfo) {
            $condition = $this->CreateCondition(
                $filterInfo['filter'],
                $filterInfo['field']
            );

            if (!empty($condition)) {
                AddStr($this->resultCondition,
                '(' . $condition . ')',
                ' ' . $filter->GetFilterLinkType() . ' ');
            }
        }
    }

    public function VisitIsNullFieldFilter($filter)
    {
        $this->resultCondition = $this->engCommandImp->GetIsNullCondition(
                $this->engCommandImp->GetFieldFullName($this->field));
    }

    public function VisitInFieldFilter(InFieldFilter $filter)
    {
        $values = array();
        foreach ($filter->getValues() as $value) {
            $values[] = $this->engCommandImp->GetFieldValueAsSQL(
                $this->field,
                $value
            );
        }

        if (empty($values)) {
            $this->resultCondition = '';
        } else {
            $this->resultCondition = sprintf(
                '%s IN (%s)',
                $this->engCommandImp->GetFieldFullName($this->field),
                implode(',', $values)
            );
        }
    }
}

class FieldInfo
{
    public $TableName;
    public $Name;
    public $FieldType;
    public $Alias;

    /**
     * @param string $tableName
     * @param string $fieldName
     * @param int $fieldType See FieldType enum
     * @param string $alias
     * @return \FieldInfo
     *
     */
    public function __construct($tableName, $fieldName, $fieldType, $alias)
    {
        $this->TableName = $tableName;
        $this->Name = $fieldName;
        $this->FieldType = $fieldType;
        $this->Alias = $alias;
    }

    public function getNameInDataset()
    {
        return $this->Alias ? $this->Alias : $this->Name;
    }
}

abstract class EngCommandImp
{
    /** @var \FilterConditionGenerator */
    private $filterConditionGenerator;

    /** @var ConnectionFactory */
    private $connectionFactory;

    /** @var \SMVersion */
    private $version;

    public function __construct($connectionFactory)
    {
        $this->filterConditionGenerator = new FilterConditionGenerator($this);
        $this->connectionFactory = $connectionFactory;
        $this->version = new SMVersion(0, 0);
    }

    public function GetFirstQuoteChar()
    {
        return '"';
    }

    public function GetLastQuoteChar()
    {
        return '"';
    }

    public function GetServerVersion()
    {
        return $this->version;
    }

    public function SetServerVersion(SMVersion $version)
    {
        $this->version = $version;
    }

    protected function CreateCaseSensitiveLikeExpression($left, $right)
    {
        return sprintf('%s LIKE %s', $left, $right);
    }

    protected function CreateCaseInsensitiveLikeExpression($left, $right)
    {
        return sprintf('%s LIKE %s' /*afterburner: default is case insensitive (utf8mb4_unicode_ci), no need for UPPER function which stops indexes in MySQL*/, $left, $right);
    }

    public function GetCaseSensitiveLikeExpression(FieldInfo $field, $filterValue)
    {
        return $this->CreateCaseSensitiveLikeExpression(
            $this->GetCastedToCharFieldExpression($field),
            $this->GetValueAsSQLString($filterValue, $field)
            );
    }

    public function GetCaseInsensitiveLikeExpression(FieldInfo $field, $filterValue)
    {
        return $this->CreateCaseInsensitiveLikeExpression(
            $this->GetCastedToCharFieldExpression($field),
            $this->GetValueAsSQLString($filterValue, $field)
            );
    }

    protected function GetConnectionFactory()
    { return $this->connectionFactory; }

    public function GetFilterConditionGenerator()
    {
        return $this->filterConditionGenerator;
    }

    protected function GetDateTimeFieldAsSQLForSelect($fieldInfo)
    {
        return $this->GetFieldFullName($fieldInfo);
    }

    protected function GetDateFieldAsSQLForSelect($fieldInfo)
    {
        return $this->GetFieldFullName($fieldInfo);
    }

    protected function GetTimeFieldAsSQLForSelect($fieldInfo)
    {
        return $this->GetFieldFullName($fieldInfo);
    }

    public function GetFieldAsSQLInSelectFieldList($fieldInfo)
    {
        if ($fieldInfo->FieldType == ftDateTime)
            $result = $this->GetDateTimeFieldAsSQLForSelect($fieldInfo);
        elseif ($fieldInfo->FieldType == ftDate)
            $result = $this->GetDateFieldAsSQLForSelect($fieldInfo);
        elseif ($fieldInfo->FieldType == ftTime)
            $result = $this->GetTimeFieldAsSQLForSelect($fieldInfo);
        else
            $result = $this->GetFieldFullName($fieldInfo);

        if (isset($fieldInfo->Alias) && $fieldInfo->Alias != '')
            $result = $this->GetAliasedAsFieldExpression($result, $this->QuoteIdentifier($fieldInfo->Alias));
        else
            $result = $this->GetAliasedAsFieldExpression($result, $this->QuoteIdentifier($fieldInfo->Name));
        return $result;
    }

    public function GetAliasedAsFieldExpression($expression, $alias)
    {
        return StringUtils::Format('%s AS %s', $expression, $alias);
    }

    public function GetCastToCharExpression($value, $fieldInfo)
    {
        return sprintf("CAST(%s AS CHAR)", $value);
    }

    public function GetCastToDateExpression($value)
    {
        return sprintf("CAST(%s AS DATE)", $value);
    }

    public function GetDatePartExpression(FieldInfo $fieldInfo, $part)
    {
        $part = strtoupper($part);
        $dateParts = array('YEAR', 'MONTH', 'DAY', 'HOUR', 'MINUTE', 'SECOND');
        if (!in_array($part, $dateParts)) {
            throw new InvalidArgumentException(sprintf(
                'Invalid date part "%s". Expected one of "%s"',
                $part,
                implode('", "', $dateParts)
            ));
        }

        return $this->doGetDatePartExpression($fieldInfo, $part);
    }

    protected function doGetDatePartExpression(FieldInfo $fieldInfo, $part)
    {
        return sprintf(
            'EXTRACT(%s FROM %s)',
            $part,
            $this->GetFieldFullName($fieldInfo)
        );
    }

    public function GetCastedToCharFieldExpression($fieldInfo)
    {
        return $this->GetCastToCharExpression($this->GetFieldFullName($fieldInfo), $fieldInfo);
    }

    /**
     * @param FieldInfo $fieldInfo
     * @return string
     */
    public function GetFieldFullName($fieldInfo)
    {
        if (isset($fieldInfo->TableName) && $fieldInfo->TableName != '')
            return $this->QuoteTableIdentifier($fieldInfo->TableName) . '.' . $this->QuoteIdentifier($fieldInfo->Name);
        else
            return $this->QuoteIdentifier($fieldInfo->Name);
    }

    /**
     * @param FieldInfo $fieldInfo
     * @param SMDateTime $value
     * @return string
     */
    protected function GetDateTimeFieldValueAsSQL($fieldInfo, $value)
    {
        return '\'' . $value->ToString('Y-m-d H:i:s') . '\'';
    }

    /**
     * @param FieldInfo $fieldInfo
     * @param SMDateTime $value
     * @return string
     */
    protected function GetDateFieldValueAsSQL($fieldInfo, $value)
    {
        return '\'' . $value->ToString('Y-m-d') . '\'';
    }

    /**
     * @param FieldInfo $fieldInfo
     * @param SMDateTime $value
     * @return string
     */
    protected function GetTimeFieldValueAsSQL($fieldInfo, $value)
    {
        return '\'' . $value->ToString('H:i:s') . '\'';
    }

    /**
     * @param mixed $value
     * @return string
     */
    protected abstract function GetBlobFieldValueAsSQL($value);

    /**
     * @param FieldInfo $fieldInfo
     * @param mixed $value
     * @return string
     */
    public function GetFieldValueAsSQL($fieldInfo, $value)
    {
        if ($fieldInfo->FieldType == ftNumber)
        {
            $result = str_replace(',', '.', $value);
            return $this->EscapeString((float) $result);
        }
        elseif ($fieldInfo->FieldType == ftDateTime)
        {
            if (!is_string($value) && get_class($value) == 'SMDateTime')
                return $this->GetDateTimeFieldValueAsSQL($fieldInfo, $value);
            else
                return $this->GetDateTimeFieldValueAsSQL($fieldInfo, SMDateTime::Parse($value, ''));
        }
        elseif ($fieldInfo->FieldType == ftDate)
        {
            if (!is_string($value) && (get_class($value) == 'SMDateTime'))
                return $this->GetDateFieldValueAsSQL($fieldInfo, $value);
            else
                return $this->GetDateFieldValueAsSQL($fieldInfo, SMDateTime::Parse($value, ''));
        }
        elseif ($fieldInfo->FieldType == ftTime)
        {
            if (!is_string($value) && get_class($value) == 'SMDateTime')
                return $this->GetTimeFieldValueAsSQL($fieldInfo, $value);
            else
                return $this->GetTimeFieldValueAsSQL($fieldInfo, SMDateTime::Parse($value, ''));
        }
        elseif ($fieldInfo->FieldType == ftBlob)
            return $this->GetBlobFieldValueAsSQL($value);
        elseif ($fieldInfo->FieldType == ftBoolean) {
            return $this->EscapeString($this->GetBooleanValueAsSQL($value));
        }
        else
            return '\'' . $this->EscapeString($value) . '\'';
    }

    protected final function GetBooleanValueAsSQL($value) {
        if ($this->isBooleanFalse($value)) {
            return $this->getBooleanFalseAsSQL();
        }
        else {
            return $this->getBooleanTrueAsSQL();
        }
    }

    protected function isBooleanFalse($value) {
        return (!$value) || (strtolower($value) === 'false');
    }

    protected function getBooleanFalseAsSQL() {
        return 0;
    }

    protected function getBooleanTrueAsSQL() {
        return 1;
    }

    /**
     * @param string $value
     * @param null|FieldInfo $fieldInfo
     *
     * @return string
     */
    public function GetValueAsSQLString($value, $fieldInfo = null)
    {
        return '\'' . $this->EscapeString($value) . '\'';
    }

    /**
     * @param $string
     * @return string
     */
    public function EscapeString($string)
    {
        return str_replace('\'', '\'\'', $string);
    }

    /**
     * @param FieldInfo $fieldInfo
     * @param mixed $value
     * @param bool $setToDefault
     * @return string
     */
    public function GetFieldValueForInsert($fieldInfo, $value, $setToDefault)
    {
        if ($setToDefault)
            return 'DEFAULT';
        elseif ($value === null || (!isset($value)))
            return 'NULL';
        else
        {
            if (($fieldInfo->FieldType == ftNumber) && ($value === ''))
                return 'NULL';
            return $this->GetFieldValueAsSQL($fieldInfo, $value);
        }
    }

    /**
     * @param FieldInfo $fieldInfo
     * @param mixed $value
     * @return string
     */
    public function GetFieldValueAsSQLForDelete($fieldInfo, $value)
    {
        if ($value == null || (!isset($value)))
            return 'NULL';
        else
        {
            if (($fieldInfo->FieldType == ftNumber) && ($value == ''))
                return 'NULL';
            return $this->GetFieldValueAsSQL($fieldInfo, $value);
        }
    }

    /**
     * @param string $identifier
     * @return string
     */
    public function QuoteTableIdentifier($identifier)
    {
        if (StringUtils::Contains(StringUtils::Lower($identifier), 'select'))
            return $identifier;

        $result = '';
        $parts = StringUtils::SplitString('.', $identifier);
        foreach($parts as $part)
            AddStr($result,
                StringUtils::NormalizeQuotation($part,
                    $this->GetFirstQuoteChar(), $this->GetLastQuoteChar()),
                '.');
        return $result;
    }

    /**
     * @param string $identifier
     * @return string
     */
    public function QuoteIdentifier($identifier)
    {
        return $identifier;
    }

    public function GetSetFieldValueClause($fieldInfo, $value, $default = false)
    {
        return $this->GetFieldFullName($fieldInfo) . ' = ' . $this->GetFieldValueAsSQLForUpdate($fieldInfo, $value, $default);
    }

    public function GetFieldValueAsSQLForUpdate($fieldInfo, $value, $default = false)
    {
        if (!isset($value))
        {
            if ($default)
                return 'DEFAULT';
            else
                return 'NULL';
        }
        else
        {
            if (($fieldInfo->FieldType == ftNumber) && ($value === ''))
                return 'NULL';
            return $this->GetFieldValueAsSQL($fieldInfo, $value);
        }
    }

    /**
     * @param int $joinKind
     * @return string
     */
    private function GetJoinKindAsSQL($joinKind)
    {
        switch ($joinKind)
        {
            case JoinKind::Inner:
                return 'INNER JOIN';
                break;
            case JoinKind::LeftOuter:
                return 'LEFT OUTER JOIN';
                break;
            default:
                return 'JOIN';
                break;
        }
    }

    /**
     * @param JoinInfo $joinInfo
     * @return string
     */
    public function CreateJoinClause($joinInfo)
    {
        return sprintf('%s %s%s ON %s = %s',
            $this->GetJoinKindAsSQL($joinInfo->JoinKind),
            $this->QuoteTableIdentifier($joinInfo->Table),
            $joinInfo->TableAlias != '' ? (' ' . $this->QuoteIdentifier($joinInfo->TableAlias)) : '',
            $this->QuoteTableIdentifier(isset($joinInfo->TableAlias) ? $joinInfo->TableAlias : $joinInfo->Table) . '.' . $this->QuoteIdentifier($joinInfo->LinkField),
            $this->GetFieldFullName($joinInfo->Field));
    }

    /**
     * @param string $fieldName
     * @return string
     */
    public function GetIsNullCondition($fieldName)
    {
        return $fieldName . ' IS NULL';
    }

    public function GetIsNullExpression(FieldInfo $fieldInfo) {
        return $this->GetIsNullCondition($this->GetFieldFullName($fieldInfo));
    }

    /**
     * @param EngConnection $connection
     * @param UpdateCommand $command
     * @return void
     */
    public function ExecuteUpdateCommand($connection, $command)
    {
        $connection->ExecSQL($command->GetSQL());
    }

    /**
     * @param EngConnection $connection
     * @param InsertCommand $command
     * @return void
     */
    public function ExecuteInsertCommand($connection, $command)
    {
        $connection->ExecSQL($command->GetSQL());
    }

    /**
     * @param EngConnection $connection
     * @param CustomInsertCommand $command
     * @return void
     */
    public function ExecuteCustomInsertCommand($connection, $command)
    {
        $connection->ExecSQL($command->GetSQL());
    }

    /**
     * @param EngConnection $connection
     * @param SelectCommand $command
     * @return EngDataReader
     */
    protected function DoExecuteSelectCommand($connection, $command)
    {
        $result = $this->connectionFactory->CreateDataReader($connection, $command->GetSQL());
        $result->Open();
        return $result;
    }

    /**
     * @param EngConnection $connection
     * @param CustomSelectCommand $command
     * @return EngDataReader
     */
    public function DoExecuteCustomSelectCommand($connection, $command)
    {
        $result = $this->connectionFactory->CreateDataReader($connection, $command->GetSQL());
        $result->Open();
        return $result;
    }

    /**
     * @param EngConnection $connection
     * @param string $sql
     * @return EngDataReader
     */
    public function ExecuteReader(EngConnection $connection, $sql)
    {
        $result = $this->connectionFactory->CreateDataReader($connection, $sql);
        $result->Open();
        return $result;
    }

    /**
     * @param EngConnection $connection
     * @param SelectCommand $command
     * @return EngDataReader
     */
    public function ExecuteSelectCommand($connection, $command)
    {
        $result = $this->DoExecuteSelectCommand($connection, $command);
        foreach($command->GetFields() as $fieldInfo)
            $result->AddFieldInfo($fieldInfo);
        return $result;
    }

    /**
     * @param EngConnection $connection
     * @param CustomSelectCommand $command
     * @return EngDataReader
     */
    public function ExecuteCustomSelectCommand($connection, $command)
    {
        $result = $this->DoExecuteCustomSelectCommand($connection, $command);
        foreach($command->GetFields() as $fieldInfo)
            $result->AddFieldInfo($fieldInfo);
        return $result;
    }

    /**
     * @param EngConnection $connection
     * @param CustomUpdateCommand $command
     * @return void
     */
    public function ExecutCustomUpdateCommand($connection, $command)
    {
        $connection->ExecSQL($command->GetSQL());
    }

    /**
     * @param EngConnection $connection
     * @param CustomDeleteCommand $command
     * @return void
     */
    public function ExecuteCustomDeleteCommand($connection, $command)
    {
        $connection->ExecSQL($command->GetSQL());
    }

    /**
     * @param EngConnection $connection
     * @param DeleteCommand $command
     * @return void
     */
    public function ExecuteDeleteCommand($connection, $command)
    {
        $connection->ExecSQL($command->GetSQL());
    }

    public function SupportsDefaultValue()
    {
        return true;
    }

    /**
     * @param string $selectSQL
     * @param int $limitNumber
     * @param int $limitOffset
     * @param boolean $hasSorting
     * @return string
     */
    public function getSelectSQLWithLimitation($selectSQL, $limitNumber, $limitOffset, $hasSorting) {
        return $selectSQL;
    }
}

class EngCommand
{
    private $engCommandImp;

    public function __construct(EngCommandImp $engCommandImp)
    {
        $this->engCommandImp = $engCommandImp;
    }

    protected function GetCommandImp()
    {
        return $this->engCommandImp;
    }

    public function Execute(EngConnection $connection)
    { }
}
