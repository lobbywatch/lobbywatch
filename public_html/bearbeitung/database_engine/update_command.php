<?php

include_once dirname(__FILE__) . '/' . 'engine.php';
include_once dirname(__FILE__) . '/' . 'commands.php';
include_once dirname(__FILE__) . '/' . '../components/utils/string_utils.php';

class MultiStatementUpdateCommand extends EngCommand
{
    private $updateCommands;

    public function __construct($statements, EngCommandImp $engCommandImp)
    {
        parent::__construct($engCommandImp);
        $this->updateCommands = array();
        foreach($statements as $statement)
            $this->updateCommands[] = new CustomUpdateCommand($statement, $engCommandImp);
    }

    #region Fields

    public function AddField($field, $fieldType, $isKeyField = false)
    {
        foreach($this->updateCommands as $updateCommand)
            $updateCommand->AddField($field, $fieldType, $isKeyField);
    }

    #endregion

    #region Values

    public function SetParameterValue($fieldName, $value, $setToDefault = false)
    {
        foreach($this->updateCommands as $updateCommand)
            $updateCommand->SetParameterValue($fieldName, $value, $setToDefault);
    }

    public function SetKeyFieldValue($keyFieldName, $value)
    {
        foreach($this->updateCommands as $updateCommand)
            $updateCommand->SetKeyFieldValue($keyFieldName, $value);
    }

    #endregion

    #region Command building result

    public function GetSQL()
    {
        $result = '';
        foreach($this->updateCommands as $updateCommand)
            AddStr($result, $updateCommand->GetSQL(), ' ');
        return $result;
    }

    public function Execute(EngConnection $connection)
    {
        foreach($this->updateCommands as $updateCommand)
            $this->GetCommandImp()->ExecutCustomUpdateCommand($connection, $updateCommand);
    }

    #endregion
}

class BaseUpdateCommand extends EngCommand
{
    private $fields;
    private $keyFields;

    private $fieldValues;
    private $keyFieldValues;
    private $setToDefaultFields;

    public function __construct(EngCommandImp $engCommandImp)
    {
        parent::__construct($engCommandImp);
        $this->fields = array();
        $this->keyFields = array();
        $this->fieldValues = array();
        $this->keyFieldValues = array();
        $this->setToDefaultFields = array();
    }

    #region Fields

    protected function GetFieldByName($name)
    {
        foreach($this->fields as $field)
            if ($field->Name == $name)
                return $field;
        return null;
    }

    public function AddField($field, $fieldType, $isKeyField = false)
    {
        $fieldInfo = new FieldInfo('', $field, $fieldType, '');
        $this->fields[] = $fieldInfo;
        if ($isKeyField)
            $this->keyFields[] = $fieldInfo;
    }

    public function GetFields()
    { 
        return $this->fields; 
    }

    #endregion

    #region Values

    protected function HasFieldValueFor($fieldName)
    {
        return array_key_exists($fieldName, $this->fieldValues);
    }

    protected function GetFieldValueByName($fieldName)
    {
        return $this->fieldValues[$fieldName];
    }

    protected function IsSetFieldValueToDefault($fieldName)
    {
        return isset($this->setToDefaultFields[$fieldName]);
    }

    public function SetParameterValue($fieldName, $value, $setToDefault = false)
    {
        if ($setToDefault)
            $this->setToDefaultFields[$fieldName] = true;
        $this->fieldValues[$fieldName] = $value;
    }

    protected function GetKeyFieldValues()
    {
        return $this->keyFieldValues;
    }

    protected function HasKeyFieldValueFor($keyFieldName)
    { 
        return isset($this->keyFieldValues[$keyFieldName]); 
    }
    
    protected function HasKeyFieldValues()
    { 
        return count($this->keyFieldValues) > 0; 
    }
    
    protected function GetKeyFieldValueByName($keyFieldName)
    { 
        return $this->keyFieldValues[$keyFieldName]; 
    }

    public function SetKeyFieldValue($keyFieldName, $value)
    { 
        $this->keyFieldValues[$keyFieldName] = $value; 
    }
    
    public function GetValues()
    { 
        return $this->fieldValues; 
    }
    
    #endregion
}

class CustomUpdateCommand extends BaseUpdateCommand
{
    private $sql;

    public function __construct($sql, EngCommandImp $engCommandImp)
    {
        parent::__construct($engCommandImp);
        $this->sql = $sql;
    }

    public function GetCustomSql()
    {
        return $this->sql;
    }

    public function SetCustomSql($value)
    {
        $this->sql = $value;
    }

    #region Command building result

    public function GetSQL()
    {
        $result = $this->sql;
        
        foreach($this->GetKeyFieldValues() as $fieldName => $value)
        {
            $fieldInfo = $this->GetFieldByName($fieldName);
            if (isset($fieldInfo) && $this->HasKeyFieldValueFor($fieldInfo->Name))
            {
                $result = ReplaceFirst($result,
                    ':OLD_' . $fieldName,
                    $this->GetCommandImp()->GetFieldValueAsSQLForUpdate(
                            $fieldInfo,
                            $this->GetKeyFieldValueByName($fieldInfo->Name),
                            $this->IsSetFieldValueToDefault($fieldName)
                            )
                        );
            }
        }

        foreach($this->GetFields() as $fieldInfo)
        {
            if ($this->HasFieldValueFor($fieldInfo->Name))
                $result = ReplaceFirst($result, ':' . $fieldInfo->Name,
                    $this->GetCommandImp()->GetFieldValueAsSQLForUpdate(
                            $fieldInfo,
                            $this->GetFieldValueByName($fieldInfo->Name),
                            $this->IsSetFieldValueToDefault($fieldName)));
        }
        return $result;
    }

    public function Execute(EngConnection $connection)
    {
        $this->GetCommandImp()->SetServerVersion($connection->GetServerVersion());
        $this->GetCommandImp()->ExecutCustomUpdateCommand($connection, $this);
    }

    #endregion
}

class UpdateCommand extends BaseUpdateCommand
{
    private $tableName;

    public function __construct(EngCommandImp $engCommandImp)
    {
        parent::__construct($engCommandImp);
    }

    public function SetTableName($value)
    { 
        $this->tableName = $value; 
    }
    
    public function GetTableName()
    { 
        return $this->tableName; 
    }
    
    #region Command building result

    private function GetSetFieldValueClause($fieldName)
    {
        $fieldInfo = $this->GetFieldByName($fieldName);
        if (isset($fieldInfo))
        {
            return $this->GetCommandImp()->GetSetFieldValueClause(
                $this->GetFieldByName($fieldName),
                $this->GetFieldValueByName($fieldName),
                $this->IsSetFieldValueToDefault($fieldName)
                );
        }
        return '';
    }

    private function GetSetClause()
    {
        $result = '';
        foreach($this->GetValues() as $fieldName => $value)
            AddStr($result, $this->GetSetFieldValueClause($fieldName), ', ');
        return $result;
    }

    private function GetKeyFieldCondition()
    {
        $result = '';
        foreach($this->GetKeyFieldValues() as $fieldName => $value)
            AddStr($result,
                $this->GetCommandImp()->GetFilterConditionGenerator()->CreateCondition(
                        new FieldFilter($value, '='), $this->GetFieldByName($fieldName)
                        ), ' AND ');
        return $result;
    }

    public function GetSQL()
    {
        assert($this->HasKeyFieldValues());

        $result = sprintf(
            'UPDATE %s SET %s WHERE %s',
            $this->GetCommandImp()->QuoteTableIdentifier($this->tableName),
            $this->GetSetClause(),
            $this->GetKeyFieldCondition()
            );
        return $result;
    }

    public function Execute(EngConnection $connection)
    {
        $this->GetCommandImp()->SetServerVersion($connection->GetServerVersion());
        $this->GetCommandImp()->ExecuteUpdateCommand($connection, $this);
    }

    #endregion
}



