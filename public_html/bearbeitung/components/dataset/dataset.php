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
    /** @var string */
    private $name;
    /** @var bool */
    private $isNotNull;
    /** @var bool */
    private $isPrimaryKey;
    /** @var bool */
    private $isAutoincrement;
    /** @var bool */
    private $isCalculated;
    /** @var string */
    private $alias;
    /** @var string */
    private $sourceTable;

    /**
     * @param string $name
     * @param bool $isNotNull
     * @param bool $isPrimaryKey
     * @param bool $isAutoincrement
     * @param bool $isCalculated
     * @param string $alias
     * @param string $sourceTable
     */
    public function __construct($name, $isNotNull = false, $isPrimaryKey = false, $isAutoincrement = false, $isCalculated = false, $alias = null, $sourceTable = null) {
        $this->name = $name;
        $this->isNotNull = $isNotNull;
        $this->isPrimaryKey = $isPrimaryKey;
        $this->isAutoincrement = $isAutoincrement;
        $this->isCalculated = $isCalculated;
        $this->alias = $alias;
        $this->sourceTable = $sourceTable;
    }

    /** @return string */
    public function GetName() {
        return $this->name;
    }

    /** @param string $value */
    public function SetName($value) {
        $this->name = $value;
    }

    /** @return bool */
    public function GetIsNotNull() {
        return $this->isNotNull;
    }

    /** @param bool $value */
    public function SetIsNotNull($value) {
        $this->isNotNull = $value;
    }

    /** @return bool */
    public function GetIsPrimaryKey() {
        return $this->isPrimaryKey;
    }

    /** @param bool $value */
    public function SetIsPrimaryKey($value) {
        $this->isPrimaryKey = $value;
    }

    /** @return bool */
    public function GetIsAutoincrement() {
        return $this->isAutoincrement;
    }

    /** @param bool $value */
    public function SetIsAutoincrement($value) {
        $this->isAutoincrement = $value;
    }

    /** @return bool */
    public function getIsCalculated() {
        return $this->isCalculated;
    }

    /** @param bool */
    public function setIsCalculated($value) {
        $this->isCalculated = $value;
    }

    /** @return string|null */
    public function GetSourceTable() {
        return $this->sourceTable;
    }

    /** @param string $value */
    public function SetSourceTable($value) {
        $this->sourceTable = $value;
    }

    /** @return string|null */
    public function GetAlias() {
        return $this->alias;
    }

    /** @param string $value */
    public function SetAlias($value) {
        $this->alias = $value;
    }

    /** @return string */
    public function GetNameInDataset() {
        return empty($this->alias) ? $this->GetName() : $this->GetAlias();
    }

    /** @return boolean */
    public function SupportsLastInsertId() {
        return false;
    }

    /**
     * @param mixed $sqlValue
     * @return mixed
     */
    public function GetDisplayValue($sqlValue) {
        return !isset($sqlValue) ? null : $this->DoGetDisplayValue($sqlValue);
    }

    /**
     * @param mixed $sqlValue
     * @return mixed
     */
    protected function DoGetDisplayValue($sqlValue) {
        return $sqlValue;
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    public function GetValueForSql($value) {
        return is_null($value) ?
            ($this->GetIsNotNull() ? $this->GetEmptyValue() : $value) :
            $this->DoGetValueForSql($value);
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    protected function DoGetValueForSql($value) {
        return $value;
    }

    /** @return string */
    private function GetEmptyValue() {
        return '';
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

    /** @inheritdoc */
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
}

abstract class DateTimeBasedField extends Field {
    /** @return string */
    abstract public function getDefaultFormat();

    /** @inheritdoc */
    protected function DoGetDisplayValue($sqlValue) {
        if (empty($sqlValue))
            return null;
        else
            return $sqlValue->ToString($this->getDefaultFormat());
    }

    /** @inheritdoc */
    protected function DoGetValueForSql($value) {
        return SMDateTime::Parse($value, $this->getDefaultFormat());
    }
}

class DateTimeField extends DateTimeBasedField {

    /** @inheritdoc */
    public function getDefaultFormat() {
        return 'Y-m-d H:i:s';
    }

    /** @inheritdoc */
    function GetEngFieldType() {
        return ftDateTime;
    }
}

class DateField extends DateTimeBasedField {

    /** @inheritdoc */
    public function getDefaultFormat() {
        return 'Y-m-d';
    }

    /** @inheritdoc */
    function GetEngFieldType() {
        return ftDate;
    }
}

class TimeField extends DateTimeBasedField {

    /** @inheritdoc */
    public function getDefaultFormat() {
        return 'H:i:s';
    }

    /** @inheritdoc */
    function GetEngFieldType() {
        return ftTime;
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
    function Connect();

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

    function setOrderByField($fieldName, $orderType);

    function setOrderByFields($sortedColumns);

    function GetName();

    function GetPrimaryKeyValues();

    function GetFieldValues($skipBlobs = false);

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

    /**
     * @return ConnectionFactory
     */
    public function GetConnectionFactory();
}

class RlsPolicy {

    /** @var string */
    private $ownerFieldName;

    /** @var int */
    private $ownerId;

    /**
     * @param string $ownerFieldName
     * @param int $ownerId
     */
    function __construct($ownerFieldName, $ownerId) {
        $this->ownerFieldName = $ownerFieldName;
        $this->ownerId = $ownerId;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setOwnerFieldName($value)
    {
        $this->ownerFieldName = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getOwnerFieldName()
    {
        return $this->ownerFieldName;
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setOwnerId($value)
    {
        $this->ownerId = $value;
        return $this;
    }

    /**
     * @return int
     */
    public function getOwnerId()
    {
        return $this->ownerId;
    }
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

    protected $fieldFilters;
    //
    private $editFieldValues;
    private $editFieldSetToDefault;
    //
    private $insertFieldValues;
    private $insertFieldSetToDefault;
    private $clientEncoding;

    /** @var RlsPolicy */
    private $rlsPolicy;

    #region Events
    /** @var \Event */
    public $OnNextRecord;
    /** @var \Event */
    public $OnAfterConnect;
    /** @var \Event */
    public $OnBeforeOpen;
    /** @var \Event */
    public $OnBeforePost;
    /** @var \Event */
    public $OnGetFieldValue;
    /** @var \Event */
    public $OnCalculateFields;
    #endregion

    private $editMode;
    private $insertMode;
    private $insertedMode = false;
    private $lookupFields = array();
    private $masterFieldValue = array();
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
        $this->OnGetFieldValue = new Event();
        $this->OnCalculateFields = new Event();

        $this->selectCommand = $this->CreateSelectCommand();

        $this->fields = array();
        $this->fieldMap = array();
        $this->fieldFilters = array();

        $this->editFieldValues = array();
        $this->insertFieldValues = array();
        $this->editFieldSetToDefault = array();
        $this->insertFieldSetToDefault = array();

        $this->clientEncoding = null;
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
        $this->clearEditFieldValues();
    }

    private function clearEditFieldValues() {
        $this->editFieldValues = array();
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

                if ($this->rlsPolicyEnabled()) {
                    $updateCommand->SetParameterValue($this->rlsPolicy->getOwnerFieldName(), $this->rlsPolicy->getOwnerId());
                }

                $updateCommand->Execute($this->GetConnection());
            }
            $this->editMode = false;
        } elseif ($this->insertMode) {
            $hasValuesForAutoIncrement = false;
            $insertCommand = $this->CreateInsertCommand() or RaiseError('Could not create InsertCommand');

            $this->Connect();

            foreach ($this->masterFieldValue as $fieldName => $value) {
                $this->SetFieldValueByName($fieldName, $value);
                $insertCommand->SetParameterValue($fieldName, $value);
            }

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

            if ($this->rlsPolicyEnabled()) {
                $insertCommand->SetParameterValue($this->rlsPolicy->getOwnerFieldName(), $this->rlsPolicy->getOwnerId());
            }

            $insertCommand->SetAutoincrementInsertion($hasValuesForAutoIncrement);

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
                if ($field != null && $field->SupportsLastInsertId() && $field->GetIsAutoincrement()) {
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

    public function GetCurrentFieldValues($skipBlobs = true)
    {
        if ($this->editMode) {
            $fieldValues = $this->GetEditFieldValues($skipBlobs);
        } elseif ($this->insertMode || $this->insertedMode) {
            $fieldValues = $this->GetInsertFieldValues($skipBlobs);
        } else {
            $fieldValues = $this->GetFieldValues($skipBlobs);
        }

        if (!$skipBlobs) {
            return $fieldValues;
        }

        return array_diff_key($fieldValues, array_flip($this->getBlobFields()));
    }

    private function getBlobFields()
    {
        $blobFields = array();
        foreach ($this->getFields() as $field) {
            if ($field->GetEngFieldType() === ftBlob) {
                $blobFields[] = $field->GetNameInDataset();
            }
        }
        return $blobFields;
    }

    public function GetEditFieldValues() {
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

    public function GetFieldValueByName($fieldName) {

        if (!is_null($this->GetFieldByName($fieldName)) && $this->GetFieldByName($fieldName)->getIsCalculated()) {
            if ($this->insertMode || $this->insertedMode || $this->editMode) {
                return null;
            } else {
                return $this->getFieldDisplayValue($fieldName, $this->getCalculatedFieldValueByName($fieldName));
            }
        }

        if ($this->insertMode || $this->insertedMode) {
            if (array_key_exists($fieldName, $this->insertFieldValues)) {
                $value = $this->getFieldDisplayValue($fieldName, $this->insertFieldValues[$fieldName]);
                return $this->getTransformedValue($value, $fieldName);
            }

            return '';
        }

        if ($this->editMode) {
            if (array_key_exists($fieldName, $this->editFieldValues)) {
                $value = $this->getFieldDisplayValue($fieldName, $this->editFieldValues[$fieldName]);
                return $this->getTransformedValue($value, $fieldName);
            }

            $value = $this->getFieldDisplayValue($fieldName, $this->getDataReader()->GetFieldValueByName($fieldName));
            return $this->getTransformedValue($value, $fieldName);
        }

        $value = $this->getFieldDisplayValue($fieldName, $this->getDataReader()->GetFieldValueByName($fieldName));
        return $this->getTransformedValue($value, $fieldName);

    }

    private function getFieldDisplayValue($fieldName, $sqlValue)
    {
        return $this->GetFieldByName($fieldName)->GetDisplayValue($sqlValue);
    }

    private function getTransformedValue($value, $fieldName)
    {
        $this->OnGetFieldValue->Fire(array($fieldName, &$value, $this->getName()));

        return $value;
    }

    private function getDataReader() {
        $this->Connect();
        if (!($this->dataReader)) {
            $this->dataReader = $this->selectCommand->Execute($this->GetConnection());
        }
        return $this->dataReader;
    }

    public function GetFieldValues($skipBlobs = false) {
        $result = array();
        foreach ($this->GetFields() as $field) {
            if ($skipBlobs && $field->GetEngFieldType() === ftBlob) {
                continue;
            }

            if ($field->getIsCalculated()) {
                continue;
            }
            $result[$field->GetNameInDataset()] = $field->GetDisplayValue($this->dataReader->GetFieldValueByName($field->GetNameInDataset()));
        }
        return $result;
    }

    #region Fields

    /**
     * @return Field[]
     */
    public function GetFields() {
        return $this->fields;
    }

    public function getFieldCount() {
        return count($this->fields);
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
     * @return void
     */
    public function AddField($field) {
        $this->fields[] = $field;
        $this->fieldMap[$field->GetNameInDataset()] = $field;
        if (!$field->getIsCalculated()) {
            $this->DoAddField($field);
        }
    }

    /**
     * @param Field[] $fields
     * @return void
     */
    public function addFields($fields) {
        foreach ($fields as $field) {
            $this->AddField($field);
        }
    }

    /**
     * @param string $nameInDataset
     *
     * @return Field
     */
    public function getField($nameInDataset)
    {
        $fieldMap = new FixedKeysArray($this->fieldMap);
        return $fieldMap[$nameInDataset];
    }

    public function IsFieldPrimaryKey($fieldName) {
        return in_array($fieldName, $this->GetPrimaryKeyFieldNames());
    }

    public function GetPrimaryKeyFieldNames() {
        $result = array();
        foreach ($this->fields as $field) {
            if ($field->GetIsPrimaryKey()) {
                $result[] = $field->GetName();
            }
        }
        return $result;
    }

    public function GetPrimaryKeyValues() {
        $result = array();
        foreach ($this->GetPrimaryKeyFieldNames() as $primaryKeyField)
            $result[] = $this->GetFieldValueByName($primaryKeyField);
        return $result;
    }

    public function GetPrimaryKeyValuesAfterEdit() {
        $this->editMode = true;
        $result = array();
        foreach ($this->GetPrimaryKeyFieldNames() as $primaryKeyField)
            $result[] = $this->GetFieldValueByName($primaryKeyField);
        $this->editMode = false;
        return $result;
    }

    public function GetPrimaryKeyValuesAfterInsert() {
        $this->insertedMode = true;
        $result = array();
        foreach ($this->GetPrimaryKeyFieldNames() as $primaryKeyField)
            $result[] = $this->GetFieldValueByName($primaryKeyField);
        $this->insertedMode = false;
        return $result;
    }

    public function GetOldPrimaryKeyValuesMap() {
        $this->editMode = false;
        $result = array();
        foreach ($this->GetPrimaryKeyFieldNames() as $primaryKeyField)
            $result[$primaryKeyField] = $this->GetFieldValueByName($primaryKeyField);
        $this->editMode = true;
        return $result;
    }

    public function GetPrimaryKeyValuesMap() {
        $result = array();
        foreach ($this->GetPrimaryKeyFieldNames() as $primaryKeyField)
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

    public function ClearAllFilters() {
        $this->GetSelectCommand()->ClearAllFilters();
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

    /**
     * @param string $fieldName
     * @param string $orderType
     */
    function setOrderByField($fieldName, $orderType) {
        $sortedColumns = array(new SortColumn($fieldName, $orderType));
        $this->GetSelectCommand()->SetOrderBy($sortedColumns);
    }

    /**
     * @param SortColumn[] $sortedColumns
     */
    function setOrderByFields($sortedColumns) {
        $this->GetSelectCommand()->SetOrderBy($sortedColumns);
    }

    #endregion

    /**
     * @param string $fieldName
     */
    public function addDistinct($fieldName) {
        $this->GetSelectCommand()->addDistinct($fieldName);
    }

    public function SetMasterFieldValue($fieldName, $value) {
        $this->masterFieldValue[$fieldName] = $value;
    }

    public function GetMasterFieldValueByName($fieldName) {
        return ArrayUtils::GetArrayValueDef($this->masterFieldValue, $fieldName);
    }

    public function getMasterFieldValues() {
        return $this->masterFieldValue;
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

    /**
     * @param RlsPolicy $rlsSPolicy
     */
    public function setRlsPolicy($rlsSPolicy) {
        $this->rlsPolicy = $rlsSPolicy;
    }

    /**
     * @return RlsPolicy|null
     */
    public function getRlsPolicy() {
        return $this->rlsPolicy;
    }

    /**
     * @return bool
     */
    public function rlsPolicyEnabled() {
        return (!is_null($this->rlsPolicy) && !is_null($this->GetFieldByName($this->rlsPolicy->getOwnerFieldName())));
    }

    /**
     * @param string $fieldName
     * @return bool
     */
    public function isOwnerFieldName($fieldName) {
        return $this->rlsPolicyEnabled() && ($fieldName == $this->rlsPolicy->getOwnerFieldName());
    }

    /**
     * @return array $primaryKeyValuesSet
     *        Example for single primary key: array('keys[0]' => array(1, 'value 1'), 'keys[1]' => array(2, 'value 2'));
     *        Example for composite primary key: array('keys[0]' => array(1, 'value 1'), 'keys[1]' => array(2, 'value 2'));
     */
    public function fetchPrimaryKeyValues() {
        $result = array();
        $recordNumber = 0;

        $this->Open();
        while ($this->Next()) {
            $primaryKeyValues = $this->GetPrimaryKeyValues();
            foreach ($primaryKeyValues as $primaryKeyValue) {
                $result["keys[$recordNumber]"][] = $primaryKeyValue;
            }
            $recordNumber++;
        }
        $this->Close();

        return $result;
    }

    /**
     * @param array $primaryKeyValuesSet
     *        Example for single primary key: array('keys[0]' => array(1), 'keys[1]' => array(2), 'keys[1]' => array(3));
     *        Example for composite primary key: array('keys[0]' => array(1, 'value 1'), 'keys[1]' => array(2, 'value 2'));
     * @param bool $invertFilter
     */
    public function applyFilterBasedOnPrimaryKeyValuesSet($primaryKeyValuesSet, $invertFilter = false) {
        $primaryFields = array();
        foreach ($this->GetPrimaryKeyFieldNames() as $fieldName) {
            $primaryFields[] = $this->GetFieldInfoByName($fieldName);
        }

        if ($invertFilter) {
            $resultFilter = new CompositeFilter('AND');
        } else{
            $resultFilter = new CompositeFilter('OR');
        }

        foreach ($primaryKeyValuesSet as $primaryKeysValues) {
            if (count($primaryKeysValues) !== count($primaryFields)) {
                throw new LogicException('Wrong primary key values set to apply filter');
            }

            $recordFilter = new CompositeFilter('AND');

            foreach ($primaryKeysValues as $i => $value) {
                if ($invertFilter) {
                    $recordFilter->addFilter($primaryFields[$i], FieldFilter::DoesNotEqual($value));
                } else {
                    $recordFilter->addFilter($primaryFields[$i], FieldFilter::Equals($value));
                }
            }

            $resultFilter->addFilter(null, $recordFilter);
        }

        $this->getSelectCommand()->AddCompositeFilter($resultFilter);
    }

    /**
     * @param array $fieldNames
     * @return array
     */
    public function getIdenticalFieldsValues($fieldNames) {
        $result = array();

        $this->Open();
        $this->Next();
        foreach ($fieldNames as $value) {
            if (!is_null($this->GetFieldByName($value))) {
                $result[$value] = $this->GetFieldValueByName($value);
            }
        }

        while ($this->Next()) {
            foreach ($result as $key => &$value) {
                if ((!is_null($value)) && ($value !== $this->GetFieldValueByName($key)) ) {
                    $value = null;
                }
            }
            if (count(array_keys($result, null)) == count($result)) {
                break;
            }
        }

        $this->Close();

        return $result;
    }

    private function getCalculatedFieldValueByName($fieldName)
    {
        $value = null;
        $this->OnCalculateFields->Fire(array($this->GetFieldValues(), $fieldName, &$value));
        return $value;
    }

}
