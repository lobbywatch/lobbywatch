<?php

// require_once 'engine.php';
// require_once 'commands.php';

include_once dirname(__FILE__) . '/' . 'engine.php';
include_once dirname(__FILE__) . '/' . 'commands.php';

class MultiStatementInsertCommand extends EngCommand
{
    /** @var CustomInsertCommand[] */
    private $insertCommands;

    /**
     * @param array $statements
     * @param EngCommandImp $engCommandImp
     */
    public function __construct($statements, EngCommandImp $engCommandImp)
    {
        parent::__construct($engCommandImp);
        $this->insertCommands = array();
        foreach($statements as $statement)
            $this->insertCommands[] = new CustomInsertCommand($statement, $engCommandImp);
    }

    public function AddField($paramName, $paramType)
    {
        foreach($this->insertCommands as $insertCommand)
            $insertCommand->AddField($paramName, $paramType);
    }

    public function SetParameterValue($fieldName, $value, $setToDefault = false)
    {
        foreach($this->insertCommands as $insertCommand)
            $insertCommand->SetParameterValue($fieldName, $value, $setToDefault);
    }

    public function GetSQL()
    {
        $result = '';
        foreach($this->insertCommands as $insertCommand)
            AddStr($result, $insertCommand->GetSQL(), ' ');
        return $result;
    }

    public function Execute(EngConnection $connection)
    {
        foreach($this->insertCommands as $insertCommand)
            $this->GetCommandImp()->ExecuteCustomInsertCommand($connection, $insertCommand);
    }

    public function SetAutoincrementInsertion($value)
    {
        foreach($this->insertCommands as $insertCommand)
            $insertCommand->SetAutoincrementInsertion($value);
    }

    public function GetAutoincrementInsertion()
    {
        foreach($this->insertCommands as $insertCommand)
            return $insertCommand->GetAutoincrementInsertion();
        return false;
    }
}

class CustomInsertCommand extends EngCommand
{
    private $sql;
    private $params; // Dictionary< paramIndex => paramName >
    private $fieldValues;
    private $setToDefaultFields;
    private $autoincrementInsertion;
    /** @var FieldInfo[] */
    private $fields;


    public function __construct($sql, EngCommandImp $engCommandImp)
    {
        parent::__construct($engCommandImp);
        $this->sql = $sql;
        $this->params = array();
        $this->fieldValues = array();
        $this->setToDefaultFields = array();
        $this->fields = array();
    }

    public function AddField($paramName, $paramType)
    {
        $this->fields[] = new FieldInfo('', $paramName, $paramType, '');
    }

    public function SetParameterValue($fieldName, $value, $setToDefault = false)
    {
        if ($setToDefault)
            $this->setToDefaultFields[$fieldName] = true;
        $this->fieldValues[$fieldName] = $value;
    }

    public function GetSQL()
    {
        $result = $this->sql;
        foreach($this->fields as $fieldInfo) {
            if (array_key_exists($fieldInfo->Name, $this->fieldValues)) {
                $result = ReplaceFirst($result, ':' . $fieldInfo->Name,
                    $this->GetCommandImp()->GetFieldValueForInsert(
                    $fieldInfo,
                    $this->fieldValues[$fieldInfo->Name],
                    isset($this->setToDefaultFields[$fieldInfo->Name])));
            }
            else {
                $result = ReplaceFirst($result, ':' . $fieldInfo->Name, 'NULL');
            }
        }
        return $result;
    }

    public function Execute(EngConnection $connection)
    {
        $this->GetCommandImp()->SetServerVersion($connection->GetServerVersion());
        $this->GetCommandImp()->ExecuteCustomInsertCommand($connection, $this);
    }

    public function SetAutoincrementInsertion($value)
    { $this->autoincrementInsertion = $value; }
    public function GetAutoincrementInsertion()
    { return $this->autoincrementInsertion; }
}

class InsertCommand extends EngCommand
{
    private $tableName;

    private $fields;
    private $fieldValues;

    private $setToDefaultFields;
    private $autoincrementInsertion;

    public function __construct(EngCommandImp $engCommandImp)
    {
        parent::__construct($engCommandImp);
        $this->fields = array();
        $this->fieldValues = array();
        $this->setToDefaultFields = array();
        $this->autoincrementInsertion = false;
    }

    public function SetAutoincrementInsertion($value)
    { $this->autoincrementInsertion = $value; }
    public function GetAutoincrementInsertion()
    { return $this->autoincrementInsertion; }

    public function GetFields()
    { return $this->fields; }
    public function GetFieldValues()
    { return $this->fieldValues; }

    public function SetTableName($value)
    { $this->tableName = $value; }
    public function GetTableName()
    { return $this->tableName; }

    public function AddField($fieldName, $fieldType)
    {
        $newFieldInfo = new FieldInfo('', $fieldName, $fieldType, '');
        $this->fields[] = $newFieldInfo;
    }

    public function SetParameterValue($fieldName, $value, $setToDefault = false)
    {
        if ($setToDefault)
            $this->setToDefaultFields[$fieldName] = true;
        $this->fieldValues[$fieldName] = $value;
    }

    //<Query Building>

    private function GetFieldByName($name)
    {
        foreach($this->fields as $field)
            if ($field->Name == $name)
                return $field;
        return null;
    }

    public function IsFieldValueSettedToDefault($fieldName)
    {
        return isset($this->setToDefaultFields[$fieldName]);
    }

    private function GetValues()
    {
        $result = '';
        foreach($this->fieldValues as $fieldName => $value)
        {
            if ($this->IsFieldValueSettedToDefault($fieldName) & !$this->GetCommandImp()->SupportsDefaultValue())
                continue;
            AddStr(
                $result,
                $this->GetCommandImp()->GetFieldValueForInsert(
                    $this->GetFieldByName($fieldName), $value, $this->IsFieldValueSettedToDefault($fieldName)
                ),
                ', '
            );
        }
        return $result;
    }

    private function GetFieldNames()
    {
        $result = '';
        foreach($this->fieldValues as $fieldName => $value)
        {
            if ($this->IsFieldValueSettedToDefault($fieldName) & !$this->GetCommandImp()->SupportsDefaultValue())
                continue;
            AddStr($result, $this->GetCommandImp()->GetFieldFullName($this->GetFieldByName($fieldName)), ', ');
        }
        return $result;
    }

    //</Query Building>

    public function GetSQL()
    {
        return sprintf(
        'INSERT INTO %s(%s) VALUES(%s)',
        $this->GetCommandImp()->QuoteTableIdentifier($this->tableName),
        $this->GetFieldNames(),
        $this->GetValues()
        );
    }

    public function Execute(EngConnection $connection)
    {
        $this->GetCommandImp()->SetServerVersion($connection->GetServerVersion());
        $this->GetCommandImp()->ExecuteInsertCommand($connection, $this);
    }
}
