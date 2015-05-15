<?php
include_once dirname(__FILE__) . '/' . '../../database_engine/engine.php';
include_once dirname(__FILE__) . '/' . '../../database_engine/filterable.php';
include_once dirname(__FILE__) . '/' . '../../database_engine/insert_command.php';
include_once dirname(__FILE__) . '/' . '../../database_engine/delete_command.php';
include_once dirname(__FILE__) . '/' . '../../database_engine/update_command.php';
include_once dirname(__FILE__) . '/' . '../common_utils.php';
include_once dirname(__FILE__) . '/' . '../utils/array_utils.php';
include_once dirname(__FILE__) . '/' . '../utils/system_utils.php';

abstract class Field {
    private $name;
    private $alias;
    private $sourceTable;
    private $isAutoincrement;
    protected $connectionFactory;

    private $readOnly;
    private $defaultValue;
    private $isNotNull;

    public function __construct($name, $alias = null, $sourceTable = null, $isAutoincrement = false) {
        $this->name = $name;
        $this->alias = $alias;
        $this->sourceTable = $sourceTable;

        $this->readOnly = false;
        $this->defaultValue = null;
        $this->isAutoincrement = $isAutoincrement;
        $this->isNotNull = false;
    }

    public function SetIsNotNull($value) {
        $this->isNotNull = $value;
    }

    public function GetIsNotNull() {
        return $this->isNotNull;
    }

    public function SetReadOnly($readOnly, $defaultValue = null) {
        $this->readOnly = $readOnly;
        $this->defaultValue = $defaultValue;
    }

    public function GetIsAutoincrement() {
        return $this->isAutoincrement;
    }

    public function GetReadOnly() {
        return $this->readOnly;
    }

    public function GetDefaultValue() {
        return $this->defaultValue;
    }

    public function GetSourceTable() {
        return $this->sourceTable;
    }

    public function SetSourceTable($value) {
        $this->sourceTable = $value;
    }

    public function GetAlias() {
        return $this->alias;
    }

    public function GetName() {
        return $this->name;
    }

    public function GetNameInDataset() {
        return $this->GetAlias() == '' ? $this->GetName() : $this->GetAlias();
    }

    /**
     * @return boolean
     */
    public function SupportsLastInsertId() {
        return false;
    }

    public function SetConnectionFactory($value) {
        $this->connectionFactory = $value;
    }

    public function DoGetDisplayValue($sqlValue) {
        return $sqlValue;
    }

    public function GetDisplayValue($sqlValue) {
        return !isset($sqlValue) ? null : $this->DoGetDisplayValue($sqlValue);
    }

    /**
     * @param mixed $sqlValue
     * @return SMDateTime
     */
    public function GetDisplayValueAsDateTime($sqlValue) {
        assert(false);
    }

    protected function GetEmptyValue() {
        return '';
    }

    protected function DoGetValueForSql($value) {
        return $value;
    }

    public function GetValueForSql($value) {
        return is_null($value) ?
            ($this->GetIsNotNull() ? $this->GetEmptyValue() : $value) :
            $this->DoGetValueForSql($value);
    }

    abstract function GetEngFieldType();
}

class BooleanField extends Field {
    function GetEngFieldType() {
        return ftBoolean;
    }
}

class IntegerField extends Field {
    function GetEngFieldType() {
        return ftNumber;
    }

    /**
     * @return bool
     */
    public function SupportsLastInsertId() {
        return true;
    }
}

class StringField extends Field {
    function GetEngFieldType() {
        return ftString;
    }
}

class BlobField extends Field {
    function GetEngFieldType() {
        return ftBlob;
    }

    public function GetDisplayValue($sqlValue) {
        return !isset($sqlValue) ? null : $this->DoGetDisplayValue($sqlValue);
    }
}

class DateField extends Field {
    private $dateFormat;

    public function __construct($name, $dateFormat = 'Y-m-d', $alias = null, $sourceTable = null, $isAutoincrement = false) {
        parent::__construct($name, $alias, $sourceTable, $isAutoincrement);
        $this->dateFormat = $dateFormat;
    }

    /**
     * @param SMDateTime|string $sqlValue
     * @return null
     */
    public function DoGetDisplayValue($sqlValue) {
        if (empty($sqlValue))
            return null;
        else
            return $sqlValue->ToString($this->dateFormat);
    }

    public function GetDisplayValueAsDateTime($sqlValue) {
        return isset($sqlValue) ? $sqlValue : null;
    }

    protected function DoGetValueForSql($value) {
        return SMDateTime::Parse($value, $this->dateFormat);
    }

    function GetEngFieldType() {
        return ftDate;
    }
}

class TimeField extends Field {
    private $timeFormat;

    public function __construct($name, $timeFormat = 'H:i:s', $alias = null, $sourceTable = null, $isAutoincrement = false) {
        parent::__construct($name, $alias, $sourceTable, $isAutoincrement);
        $this->timeFormat = $timeFormat;
    }

    /**
     * @param SMDateTime|string $sqlValue
     * @return null
     */
    public function DoGetDisplayValue($sqlValue) {
        if (empty($sqlValue))
            return null;
        else
            return $sqlValue->ToString($this->timeFormat);
    }

    public function GetDisplayValueAsDateTime($sqlValue) {
        return isset($sqlValue) ? $sqlValue : null;
    }

    protected function DoGetValueForSql($value) {
        return SMDateTime::Parse($value, $this->timeFormat);
    }

    function GetEngFieldType() {
        return ftTime;
    }
}

class DateTimeField extends Field {
    private $dateTimeFormat;

    public function __construct($name, $dateTimeFormat = 'Y-m-d H:i:s', $alias = null, $sourceTable = null, $isAutoincrement = false) {
        parent::__construct($name, $alias, $sourceTable, $isAutoincrement);
        $this->dateTimeFormat = $dateTimeFormat;
    }

    /**
     * @param SMDateTime|string $sqlValue
     * @return null
     */
    public function DoGetDisplayValue($sqlValue) {
        if (empty($sqlValue))
            return null;
        else
            return $sqlValue->ToString($this->dateTimeFormat);
    }

    protected function DoGetValueForSql($value) {
        return SMDateTime::Parse($value, $this->dateTimeFormat);
    }

    public function GetDisplayValueAsDateTime($sqlValue) {
        return isset($sqlValue) ? $sqlValue : null;
    }

    function GetEngFieldType() {
        return ftDateTime;
    }
}

interface IDataset {
    /**
     * @return bool
     */
    function Next();

    /**
     * @return void
     */
    function Open();

    /**
     * @return void
     */
    function Close();

    /**
     * @param string $fieldName
     * @return string
     */
    function GetFieldValueByName($fieldName);

    /**
     * @param string $fieldName
     * @param string $value
     * @param bool $setToDefault
     * @return void
     */
    function SetFieldValueByName($fieldName, $value, $setToDefault = false);

    /** @return void */
    public function Edit();

    /** @return void */
    public function Insert();

    /** @return void */
    public function Delete();

    /** @return void */
    public function Post();



    function SetSingleRecordState($primaryKeyValues);

    function SetAllRecordsState();

    function GetCurrentFieldValues();

    function SetOrderBy($fieldName, $orderType);

    function GetName();

    function GetPrimaryKeyValues();

    function GetFieldValues();

    function GetPrimaryKeyValuesAfterEdit();

    function GetInsertFieldValues();

    /**
     * @param string $fieldName
     * @return Field
     */
    function GetFieldByName($fieldName);

    /**
     * @return EngConnection
     */
    function GetConnection();

    /**
     * @return BaseSelectCommand
     */
    function GetSelectCommand();

    /**
     * @return EngCommandImp
     */
    function GetCommandImp();

    /**
     * @param $fieldName
     * @return bool
     */
    function IsLookupField($fieldName);
    /**
     * @param $fieldName
     * @return bool
     */
    function IsLookupFieldNameByDisplayFieldName($fieldName);

    /**
     * @return string[]
     */
    function GetPrimaryKeyFieldNames();

    /**
     * @return string[]
     */
    function GetPrimaryKeyValuesMap();
}

abstract class Dataset implements IFilterable, IDataset {
    /** @var ConnectionFactory */
    private $connectionFactory;

    /** @var array */
    private $connectionParams;

    /** @var EngDataReader */
    private $dataReader;

    /** @var EngConnection */
    private $connection;

    /** @var EngCommandImp */
    private $commandImp;

    /** @var SelectCommand */
    private $selectCommand;

    /** @var Field[] */
    private $fields;
    private $fieldMap;

    /** @var string[] */
    private $primaryKeyFields;
    protected $fieldFilters;
    //
    private $editFieldValues;
    private $editFieldSetToDefault;
    //
    private $insertFieldValues;
    private $insertFieldSetToDefault;
    private $clientEncoding;

    #region Events
    /** @var \Event */
    public $OnNextRecord;
    /** @var \Event */
    public $OnAfterConnect;
    /** @var \Event */
    public $OnBeforeOpen;
    /** @var \Event */
    public $OnBeforePost;
    #endregion

    private $editMode;
    private $insertMode;
    private $insertedMode = false;
    private $lookupFields = array();
    private $masterFieldValue = array();
    private $defaultFieldValues;
    private $singleRecordFilters = array();
    private $rowIndex;


    /**
     * @param ConnectionFactory $connectionFactory
     * @param array $connectionParams
     */
    function __construct($connectionFactory, $connectionParams) {
        $this->connectionFactory = $connectionFactory;
        $this->connectionParams = $connectionParams;

        $this->OnAfterConnect = new Event();
        $this->OnNextRecord = new Event();
        $this->OnBeforeOpen = new Event();
        $this->OnBeforePost = new Event();

        $this->selectCommand = $this->CreateSelectCommand();

        $this->fields = array();
        $this->fieldMap = array();
        $this->primaryKeyFields = array();
        $this->fieldFilters = array();

        $this->editFieldValues = array();
        $this->insertFieldValues = array();
        $this->editFieldSetToDefault = array();
        $this->insertFieldSetToDefault = array();

        $this->clientEncoding = null;
        $this->defaultFieldValues = array();
    }

    protected function DoOnNextRecord() {
        $this->OnNextRecord->Fire(array($this));
    }

    protected function DoBeforeOpen() {
        $this->OnBeforeOpen->Fire(array($this));
    }

    protected function DoBeforePost() {
        $this->OnBeforePost->Fire(array($this));
    }

    public function DoNotRewriteUnchangedValues() {
        return true;
    }

    public function SetClientEncoding($value) {
        $this->clientEncoding = $value;
    }

    public function GetPrimaryKeyFields() {
        return $this->primaryKeyFields;
    }

    /**
     * @abstract
     * @return SelectCommand
     */
    protected abstract function CreateSelectCommand();

    /**
     * @return SelectCommand
     */
    public function GetSelectCommand() {
        return $this->selectCommand;
    }

/*    /**
     * @abstract
     * @return string

    public abstract function GetName();*/

    /**
     * @abstract
     * @return UpdateCommand
     */
    protected abstract function DoCreateUpdateCommand();

    /**
     * @abstract
     * @return InsertCommand
     */
    protected abstract function DoCreateInsertCommand();

    /**
     * @abstract
     * @return DeleteCommand
     */
    protected abstract function DoCreateDeleteCommand();

    /**
     * @return UpdateCommand
     */
    private function CreateUpdateCommand() {
        $result = $this->DoCreateUpdateCommand();
        return $result;
    }

    /**
     * @return InsertCommand
     */
    private function CreateInsertCommand() {
        $result = $this->DoCreateInsertCommand();
        return $result;
    }

    /**
     * @return DeleteCommand
     */
    private function CreateDeleteCommand() {
        $result = $this->DoCreateDeleteCommand();
        return $result;
    }

    /**
     * @return ConnectionFactory
     */
    public function GetConnectionFactory() {
        return $this->connectionFactory;
    }

    /**
     * @return EngConnection
     */
    public function GetConnection() {
        return $this->connection;
    }

    /**
     * @return EngCommandImp
     */
    public function GetCommandImp() {
        if (!isset($this->commandImp))
            $this->commandImp = $this->connectionFactory->CreateEngCommandImp();
        return $this->commandImp;
    }

    public function Connect() {
        if (!isset($this->connection)) {
            $this->connection = $this->connectionFactory->CreateConnection($this->connectionParams);
            $this->GetConnection()->OnAfterConnect->AddListener('AfterConnectHandler', $this);
            $this->GetConnection()->Connect();
        }
    }

    public function AfterConnectHandler($connection) {
        $this->OnAfterConnect->Fire(array(&$connection));
    }

    public function Open() {
        $this->Connect();
        if (DebugUtils::GetDebugLevel() == 1)
            echo $this->selectCommand->GetSQL() . '<br>';
        $this->DoBeforeOpen();
        $this->dataReader = $this->selectCommand->Execute($this->GetConnection());
        $this->rowIndex = 0;
        $this->insertedMode = false;
    }

    public function Next() {
        $this->rowIndex++;
        $result = $this->dataReader->Next();
        $this->DoOnNextRecord();
        return $result;
    }

    public function GetCurrentRowIndex() {
        return $this->rowIndex;
    }

    public function Close() {
        $this->SetAllRecordsState();
        $this->rowIndex = 0;
        if ($this->dataReader)
          $this->dataReader->Close();
        $this->DoAfterClose();
    }

    protected function DoAfterClose() {
    }

    // <editing>

    public function Edit() {
        $this->editMode = true;
    }

    public function Insert() {
        $this->insertedMode = false;
        $this->insertMode = true;
    }

    public function Delete() {
        $deleteCommand = $this->CreateDeleteCommand();
        $primaryKeyValues = $this->GetPrimaryKeyValuesMap();

        foreach ($primaryKeyValues as $keyFieldName => $value) {
            $deleteCommand->SetKeyFieldValue($keyFieldName, $value);
        }

        if (DebugUtils::GetDebugLevel() == 1)
            echo $deleteCommand->GetSQL() . '<br>';
        $deleteCommand->Execute($this->GetConnection());
    }

    public function Post() {
        $this->DoBeforePost();
        if ($this->editMode) {
            if (count($this->editFieldValues) > 0 || count($this->editFieldSetToDefault) > 0) {
                $updateCommand = $this->CreateUpdateCommand();

                $primaryKeyValues = $this->GetOldPrimaryKeyValuesMap();

                foreach ($primaryKeyValues as $keyFieldName => $value)
                    $updateCommand->SetKeyFieldValue($keyFieldName, $value);

                foreach ($this->editFieldValues as $fieldName => $value) {
                    if (in_array($fieldName, $this->editFieldSetToDefault))
                        $updateCommand->SetParameterValue($fieldName, null, true);
                    else
                        $updateCommand->SetParameterValue($fieldName, $value);
                }

                foreach ($this->GetFields() as $field)
                    if ($field->GetReadOnly())
                        $updateCommand->SetParameterValue($field->GetNameInDataset(), $field->GetDefaultValue());

                if (DebugUtils::GetDebugLevel() >= 1)
                    echo $updateCommand->GetSQL() . '<br>';

                $updateCommand->Execute($this->GetConnection());
            }
            $this->editMode = false;
        } elseif ($this->insertMode) {
            $hasValuesForAutoIncrement = false;
            $insertCommand = $this->CreateInsertCommand() or RaiseError('Could not create InsertCommand');

            $this->Connect();

            foreach ($this->masterFieldValue as $fieldName => $value)
                $insertCommand->SetParameterValue($fieldName, $value);

            foreach ($this->insertFieldValues as $fieldName => $value) {
                if (in_array($fieldName, $this->insertFieldSetToDefault)) {
                    if (!$this->GetFieldByName($fieldName)->GetIsAutoincrement())
                        $insertCommand->SetParameterValue($fieldName, null, true);
                } else {
                    if ($this->GetFieldByName($fieldName)->GetIsAutoincrement())
                        $hasValuesForAutoIncrement = true;
                    $insertCommand->SetParameterValue($fieldName, $value);
                }
            }

            foreach ($this->GetFields() as $field)
                if ($field->GetReadOnly())
                    $insertCommand->SetParameterValue($field->GetNameInDataset(), $field->GetDefaultValue());

            foreach ($this->defaultFieldValues as $fieldName => $value)
                $insertCommand->SetParameterValue($fieldName, $value);

            $insertCommand->SetAutoincrementInsertion($hasValuesForAutoIncrement);

            if (DebugUtils::GetDebugLevel() == 1)
                echo $insertCommand->GetSQL() . '<br>';
            $insertCommand->Execute($this->GetConnection());
            $this->UpdatePrimaryKeyAfterInserting();
            $this->insertMode = false;
            $this->insertedMode = true;
        }
    }

    private function UpdatePrimaryKeyAfterInserting() {
        $primaryKeyColumns = $this->GetPrimaryKeyFieldNames();
        if (count($primaryKeyColumns) == 1) {
            if ($this->GetConnection()->SupportsLastInsertId()) {
                $field = $this->GetFieldByName($primaryKeyColumns[0]);
                if ($field != null && $field->SupportsLastInsertId()) {
                    $lastInsertId = $this->GetConnection()->GetLastInsertId();
                    if ($lastInsertId != null)
                        $this->insertFieldValues[$primaryKeyColumns[0]] = $lastInsertId;
                }
            }
        }
    }

    // </editing>

    public function GetFieldValueAsSQLByNameForInsert($fieldName, $value) {
        return $this->GetFieldByName($fieldName)->GetValueForSql($value);
    }

    public function GetFieldValueAsSQLByNameForUpdate($fieldName, $value) {
        return $this->GetFieldByName($fieldName)->GetValueForSql($value);
    }

    public function GetCurrentFieldValues() {
        if ($this->editMode)
            return $this->GetEditFieldValues();
        elseif ($this->insertMode || $this->insertedMode)
            return $this->GetInsertFieldValues(); else
            return $this->GetFieldValues();
    }

    private function GetEditFieldValues() {
        return $this->editFieldValues;
    }

    public function GetInsertFieldValues() {
        return $this->insertFieldValues;
    }

    public function SetFieldValueByName($fieldName, $value, $setToDefault = false) {
        if ($this->editMode) {
            $valueForSql = $this->GetFieldValueAsSQLByNameForUpdate($fieldName, $value);
            $this->editFieldValues[$fieldName] = $valueForSql;

            if ($setToDefault) {
                $this->editFieldValues[$fieldName] = null;
                $this->editFieldSetToDefault[] = $fieldName;
            }
        } else if ($this->insertMode || $this->insertedMode) {
            $valueForSql = $this->GetFieldValueAsSQLByNameForInsert($fieldName, $value);
            $this->insertFieldValues[$fieldName] = $valueForSql;
            if ($setToDefault) {
                $this->insertFieldValues[$fieldName] = null;
                $this->insertFieldSetToDefault[] = $fieldName;
            }
        }
    }

    public function SetFieldValueAsFileNameByName($fieldName, $value, $setToDefault = false) {
        $this->SetFieldValueByName($fieldName, array($value), $setToDefault);
    }

    public function AddDefaultFieldValue($fieldName, $value) {
        $this->defaultFieldValues[$fieldName] = $value;
    }

    public function GetFieldValueByName($fieldName) {
        if ($this->insertMode || $this->insertedMode) {
            if (array_key_exists($fieldName, $this->insertFieldValues))
                return $this->GetFieldByName($fieldName)->GetDisplayValue($this->insertFieldValues[$fieldName]);
            else
                return '';
        } else if ($this->editMode) {
            if (array_key_exists($fieldName, $this->editFieldValues))
                return $this->GetFieldByName($fieldName)->GetDisplayValue($this->editFieldValues[$fieldName]);
            else if (in_array($fieldName, $this->defaultFieldValues))
                return $this->defaultFieldValues[$fieldName];
            else {
                return $this->GetFieldByName($fieldName)->GetDisplayValue($this->dataReader->GetFieldValueByName($fieldName));
            }

        } else {
            if (in_array($fieldName, $this->defaultFieldValues))
                return $this->defaultFieldValues[$fieldName];
            else {
                return $this->GetFieldByName($fieldName)->GetDisplayValue($this->dataReader->GetFieldValueByName($fieldName));
            }
        }
    }

    /**
     * @param  $fieldName
     * @return null|SMDateTime
     */
    public function GetFieldValueByNameAsDateTime($fieldName) {
        if ($this->insertMode || $this->insertedMode) {
            if (array_key_exists($fieldName, $this->insertFieldValues))
                return $this->GetFieldByName($fieldName)->GetDisplayValueAsDateTime($this->insertFieldValues[$fieldName]);
            else
                return null;
        } else {
            if (in_array($fieldName, $this->defaultFieldValues))
                return $this->defaultFieldValues[$fieldName];
            else {
                return $this->GetFieldByName($fieldName)->GetDisplayValueAsDateTime($this->dataReader->GetFieldValueByName($fieldName));
            }
        }

    }

    public function GetFieldValues() {
        $result = array();
        foreach ($this->GetFields() as $field)
            $result[$field->GetNameInDataset()] = $field->GetDisplayValue($this->dataReader->GetFieldValueByName($field->GetNameInDataset()));
        return $result;
    }

    #region Fields

    /**
     * @return Field[]
     */
    public function GetFields() {
        return $this->fields;
    }

    /**
     * @param string $fieldName
     * @return null|Field
     */
    public function GetFieldByName($fieldName) {
        if (isset($this->fieldMap[$fieldName]))
            return $this->fieldMap[$fieldName];
        else
            return null;
    }

    public function GetFieldInfoByName($fieldName) {
        return $this->GetSelectCommand()->GetFieldByName($fieldName);
    }

    /**
     * @param Field $field
     * @return void
     */
    protected function DoAddField($field) {
        $this->selectCommand->AddField($field->GetSourceTable(), $field->GetName(), $field->GetEngFieldType(), $field->GetAlias());
    }

    /**
     * @param Field $field
     * @param bool $isPrimaryKeyField
     * @return void
     */
    public function AddField($field, $isPrimaryKeyField = false) {
        $field->SetConnectionFactory($this->connectionFactory);
        $this->fields[] = $field;
        $this->fieldMap[$field->GetNameInDataset()] = $field;
        $this->DoAddField($field);
        if ($isPrimaryKeyField)
            $this->primaryKeyFields[] = $field->GetName();
    }

    public function IsFieldPrimaryKey($fieldName) {
        return in_array($fieldName, $this->primaryKeyFields);
    }

    public function GetPrimaryKeyFieldNames() {
        return $this->primaryKeyFields;
    }

    public function GetPrimaryKeyValues() {
        $result = array();
        foreach ($this->primaryKeyFields as $primaryKeyField)
            $result[] = $this->GetFieldValueByName($primaryKeyField);
        return $result;
    }

    public function GetPrimaryKeyValuesAfterEdit() {
        $this->editMode = true;
        $result = array();
        foreach ($this->primaryKeyFields as $primaryKeyField)
            $result[] = $this->GetFieldValueByName($primaryKeyField);
        $this->editMode = false;
        return $result;
    }

    public function GetPrimaryKeyValuesAfterInsert() {
        $this->insertedMode = true;
        $result = array();
        foreach ($this->primaryKeyFields as $primaryKeyField)
            $result[] = $this->GetFieldValueByName($primaryKeyField);
        $this->insertedMode = false;
        return $result;
    }

    public function GetOldPrimaryKeyValuesMap() {
        $this->editMode = false;
        $result = array();
        foreach ($this->primaryKeyFields as $primaryKeyField)
            $result[$primaryKeyField] = $this->GetFieldValueByName($primaryKeyField);
        $this->editMode = true;
        return $result;
    }

    public function GetPrimaryKeyValuesMap() {
        $result = array();
        foreach ($this->primaryKeyFields as $primaryKeyField)
            $result[$primaryKeyField] = $this->GetFieldValueByName($primaryKeyField);
        return $result;
    }

    #endregion

    #region Data navigation

    public function SetSingleRecordState($primaryKeyValues) {
        $primaryKeyFields = $this->GetPrimaryKeyFieldNames();
        for ($i = 0; $i < count($primaryKeyFields); $i++) {
            $filter = new FieldFilter($primaryKeyValues[$i], '=');
            $this->singleRecordFilters[$primaryKeyFields[$i]] = $filter;

            $this->GetSelectCommand()->AddFieldFilter(
                $primaryKeyFields[$i],
                $filter);
        }
    }

    public function SetAllRecordsState() {
        foreach ($this->singleRecordFilters as $keyFieldName => $filter)
            $this->GetSelectCommand()->RemoveFieldFilter($keyFieldName, $filter);
    }

    public function SetUpLimit($upLimit) {
        $this->GetSelectCommand()->SetUpLimit($upLimit);
    }

    public function SetLimit($limit) {
        $this->GetSelectCommand()->SetLimitCount($limit);
    }

    protected function CheckConnect() {
        $this->Connect();
    }

    public function GetTotalRowCount() {
        $this->CheckConnect();
        $result = $this->GetSelectCommand()->GetSelectRecordCountSQL();
        return $this->GetConnection()->ExecScalarSQL($result);
    }

    #region IFilterable implementation

    public function AddFieldFilter($fieldName, $fieldFilter) {
        $this->GetSelectCommand()->AddFieldFilter($fieldName, $fieldFilter);
    }

    public function RemoveFieldFilter($fieldName, $fieldFilter) {
        $this->GetSelectCommand()->RemoveFieldFilter($fieldName, $fieldFilter);
    }

    public function ClearFieldFilters() {
        $this->GetSelectCommand()->ClearFieldFilters();
    }

    public function AddCompositeFieldFilter($filterLinkType, $fieldNames, $fieldFilters) {
        $this->GetSelectCommand()->AddCompositeFieldFilter(
            $filterLinkType, $fieldNames, $fieldFilters);
    }

    public function AddCustomCondition($condition) {
        $this->GetSelectCommand()->AddCustomCondition($condition);
    }

    #endregion

    public function SetOrderBy($fieldName, $orderType) {
        $this->GetSelectCommand()->SetOrderBy($fieldName, $orderType);
    }

    #endregion

    public function SetMasterFieldValue($fieldName, $value) {
        $this->masterFieldValue[$fieldName] = $value;
    }

    public function GetMasterFieldValueByName($fieldName) {
        return ArrayUtils::GetArrayValueDef($this->masterFieldValue, $fieldName);
    }

    /**
     * @param string $fieldName
     * @param string $lookUpTable
     * @param Field $lookUpLinkField
     * @param Field $lookupDisplayField
     * @param string $lookUpTableAlias
     * @return void
     */
    public function AddLookupField($fieldName, $lookUpTable, $lookUpLinkField, $lookupDisplayField, $lookUpTableAlias) {
        $this->lookupFields[$lookupDisplayField->GetAlias()] = $fieldName;
    }

    public function IsLookupField($fieldName) {
        return array_key_exists($fieldName, $this->lookupFields);
    }

    public function IsLookupFieldByPrimaryName($fieldName) {
        foreach ($this->lookupFields as $displayName => $primaryName) {
            if ($primaryName == $fieldName)
                return $displayName;
        }
        return null;
    }

    public function IsLookupFieldNameByDisplayFieldName($displayFieldName) {
        return $this->lookupFields[$displayFieldName];
    }
}
