<?php

include_once dirname(__FILE__) . '/' . '../components/utils/event.php';
include_once dirname(__FILE__) . '/' . '../components/utils/sm_datetime.php';
include_once dirname(__FILE__) . '/' . 'commands.php';
include_once dirname(__FILE__) . '/' . 'select_command.php';

class SMSQLException extends Exception {
    public function __construct($message) {
        parent::__construct($message);
    }
}

class SMQueryReturnsNoRowsException extends Exception {
    public function __construct($message) {
        parent::__construct($message);
    }
}

/** @var IEngConnection[] $connectionPool  */
$connectionPool = array();

register_shutdown_function('FinalizeConnectionPool');

function FinalizeConnectionPool() {

    global $connectionPool;

    foreach ($connectionPool as $hash => $connection)
        $connection->Disconnect();
}

interface IEngDataReader {

    /**
     * @throws SMSQLException
     */
    function Open();
    /**
     * @return bool
     */
    function Next();
    function Close();
    function FieldCount();
    function GetField($index);

    function AddFieldInfo(FieldInfo $fieldInfo);

    /**
     * @param string $fieldName
     * @return mixed
     */
    function GetFieldValueByName($fieldName);
}

interface IEngConnection {
    /**
     * @param string $sql
     * @return IEngDataReader
     */
    public function CreateDataReader($sql);

    /**
     * @return bool
     */
    public function Connected();

    /**
     * @param string $sql
     * @return void
     */
    public function ExecSQL($sql);

    /**
     * @param string $sql
     * @return mixed
     * @throws SMSQLException
     */
    public function ExecScalarSQL($sql);

    /**
     * @param string $sql
     * @param array $array
     * @throws SMSQLException
     */
    public function ExecQueryToArray($sql, &$array);

    /**
     * @return void
     */
    public function Connect();

    /**
     * @return void
     */
    public function Disconnect();

    /**
     * @return bool
     */
    public function SupportsLastInsertId();

    /**
     * @return mixed
     */
    public function GetLastInsertId();

    /**
     * @return string
     */
    public function LastError();

    /**
     * @return void
     */
    public function commitTransaction();

    /**
     * @return SMVersion
     */
    public function GetServerVersion();
}

abstract class ConnectionFactory
{
    /**
     * @var ConnectionFactory[]
     */
    private static $instances = array();

    protected function __construct()
    {
    }

    /**
     * @return ConnectionFactory
     */
    public static function getInstance()
    {
        $className = get_called_class();
        if (!array_key_exists($className, self::$instances)) {
            self::$instances[$className] = new $className();
        }

        return self::$instances[$className];
    }

    /** @var EngConnection */
    private $masterConnection;

    private function GetConnectionParamsHash($connectionParams) {
        $result = '';
        foreach ($connectionParams as $value) {
            $result .= $value;
        }
        $result .= get_class($this);
        if (function_exists('md5'))
            return md5($result);
        else
            return $result;
    }

    /**
     * @param $connectionParams
     * @return EngConnection
     */
    public final function CreateConnection($connectionParams) {
        global $connectionPool;

        $paramsHash = $this->GetConnectionParamsHash($connectionParams);
        if (!isset($connectionPool[$paramsHash])) {
            $connectionPool[$paramsHash] = $this->DoCreateConnection($connectionParams);
        }

        $this->masterConnection = $connectionPool[$paramsHash];

        return $connectionPool[$paramsHash];
    }

    /**
     * @param array $connectionParams
     * @return EngConnection
     */
    public abstract function DoCreateConnection($connectionParams);

    /**
     * @abstract
     * @param IEngConnection $connection
     * @param string $sql
     * @return EngDataReader
     */
    abstract function CreateDataReader(IEngConnection $connection, $sql);

    /**
     * @return EngCommandImp
     */
    public abstract function CreateEngCommandImp();

    public function CreateSelectCommand() {
        return new SelectCommand($this->CreateEngCommandImp());
    }

    public function CreateUpdateCommand() {
        return new UpdateCommand($this->CreateEngCommandImp());
    }

    public function CreateInsertCommand() {
        return new InsertCommand($this->CreateEngCommandImp());
    }

    public function CreateDeleteCommand() {
        return new DeleteCommand($this->CreateEngCommandImp());
    }

    public function CreateCustomSelectCommand($sql) {
        return new CustomSelectCommand($this->CreateEngCommandImp(), $sql);
    }

    public function CreateCustomUpdateCommand($sql) {
        if (is_array($sql))
            return new MultiStatementUpdateCommand($sql, $this->CreateEngCommandImp());
        else
            return new CustomUpdateCommand($sql, $this->CreateEngCommandImp());
    }

    public function CreateCustomInsertCommand($sql) {
        if (is_array($sql))
            return new MultiStatementInsertCommand($sql, $this->CreateEngCommandImp());
        else
            return new CustomInsertCommand($sql, $this->CreateEngCommandImp());
    }

    public function CreateCustomDeleteCommand($sql) {
        if (is_array($sql))
            return new MultiStatementDeleteCommand($sql, $this->CreateEngCommandImp());
        else
            return new CustomDeleteCommand($sql, $this->CreateEngCommandImp());
    }

    public function GetMasterConnection() {
        return $this->masterConnection;
    }
}

abstract class EngConnection implements IEngConnection {

    /** @var array */
    private $connectionParams;

    /** @var bool */
    private $connected;

    /** @var string */
    private $clientEncoding;

    /**
     * @var string[]
     */
    private $queryLog = array();

    /** @var \Event */
    public $OnAfterConnect;

    /**
     * @return bool
     */
    protected abstract function DoConnect();

    protected abstract function DoDisconnect();

    /**
     * @param string $sql
     * @return IEngDataReader
     */
    protected abstract function DoCreateDataReader($sql);


    /**
     * @param $paramName
     * @return string
     */
    public function ConnectionParam($paramName) {
        return isset($this->connectionParams[$paramName]) ? $this->connectionParams[$paramName] : '';
    }

    /**
     * @param string $paramName
     * @return bool
     */
    public function HasConnectionParam($paramName) {
        return isset($this->connectionParams[$paramName]);
    }

    /**
     * @return string
     */
    protected function FormatConnectionParams() {
        return $this->ConnectionParam('server');
    }

    /**
     * @param array $connectionParams
     */
    public function __construct($connectionParams) {
        $this->connectionParams = $connectionParams;
        $this->OnAfterConnect = new Event();
        $this->serverVersion = new SMVersion(0, 0);
    }

    /**
     * {@inheritdoc}
     */
    public function CreateDataReader($sql) {
        return $this->DoCreateDataReader($sql);
    }

    /**
     * @return null|resource
     */
    public function GetConnectionHandle() {
        return null;
    }

    /**
     * @return bool
     */
    public function IsDriverSupported() {
        return true;
    }

    /**
     * @return string
     */
    protected function DoGetDBMSName() {
        return '';
    }

    /**
     * @return string
     */
    protected function DoGetDriverExtensionName() {
        return 'database';
    }

    /**
     * @return string
     */
    protected function DoGetDriverInstallationLink() {
        return 'http://www.php.net';
    }

    /**
     * @return string
     */
    public function GetDriverNotSupportedMessage() {
        return sprintf(
            'We were unable to use the %s database because the %s extension for PHP is not installed. ' .
                'Check your PHP.ini to see how you can enable it. ' .
                '<a href="%s">Check out the documentation</a> to see how to install the extension.',
            $this->DoGetDBMSName(),
            $this->DoGetDriverExtensionName(),
            $this->DoGetDriverInstallationLink()
        );
    }

    public function GetClientEncoding() {
        return $this->clientEncoding;
    }

    public function SetClientEncoding($value) {
        $this->clientEncoding = $value;
    }

    public function Connected() {
        return $this->connected;
    }

    /**
     * @param string $sql
     * @return bool
     */
    protected function DoExecSQL($sql) {
    }

    public function ExecSQL($sql) {
        $this->logQuery($sql);
        if (!$this->DoExecSQL($sql)) {
            $this->raiseSQLExecutionException($sql);
        }
    }

    /**
     * @param string $sql
     * @param array $array
     * @throws SMSQLException
     */
    public final function ExecQueryToArray($sql, &$array) {
        if (!$this->doExecQueryToArray($sql, $array)) {
            $this->raiseSQLExecutionException($sql);
        }
    }

    /**
     * @param string $sql
     * @return array
     * @throws SMSQLException
     */
    public function fetchAll($sql) {
        $result = array();
        $this->ExecQueryToArray($sql, $result);

        return $result;
    }

    protected function doExecQueryToArray($sql, &$array) {
        $dataReader = $this->CreateDataReader($sql);
        try {
            $dataReader->Open();
        }
        catch (Exception $e) {
            return false;
        }
        while ($dataReader->Next()) {
            $row = array();
            for ($i = 0; $i < $dataReader->FieldCount(); $i++) {
                $row[$dataReader->GetField($i)] =
                    $dataReader->GetFieldValueByName($dataReader->GetField($i));
            }
            $array[] = $row;
        }
        $dataReader->Close();
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function ExecScalarSQL($sql) {
        $this->logQuery($sql);
        $result = $this->doExecScalarSQL($sql);
        if ($result === false) {
            $this->raiseSQLExecutionException($sql);
        }
        return $result;
    }

    protected function doExecScalarSQL($sql) {
        return false;
    }

    protected function raiseSQLExecutionException($sql) {
        throw new SMSQLException('Cannot execute SQL statement: ' /*. $sql . "\n"*/ . $this->LastError());
    }

    protected function raiseSQLStatementReturnsNoRowsException($sql) {
        throw new SMQueryReturnsNoRowsException("SQL statement '$sql' returns no rows");
    }

    private function CheckDriverSupported() {
        if (!$this->IsDriverSupported()) {
            $this->raiseCannotConnectException();
        }
    }

    private function raiseCannotConnectException() {
        throw new SMSQLException(sprintf('Could not connect to %s: %s',
            $this->FormatConnectionParams(),
            $this->LastError()
        ));
    }

    public function SupportsLastInsertId() {
        return false;
    }

    public function GetLastInsertId() {
        return 0;
    }

    public function Connect() {
        if (!$this->Connected()) {
            $this->CheckDriverSupported();

            $this->connected = $this->DoConnect();
            if (!$this->Connected()) {
                $this->raiseCannotConnectException();
            } else {
                $this->OnAfterConnect->Fire(array(&$this));
            }
        }
    }

    public function Disconnect() {
        if ($this->Connected()) {
            $this->DoDisconnect();
            $this->connected = false;
        }
    }

    /**
     * @return string
     */
    public function DoLastError() {
        return '';
    }

    public function LastError() {
        if (!$this->IsDriverSupported())
            return $this->GetDriverNotSupportedMessage();
        else
            return $this->DoLastError();
    }

    /**
     * @return SMVersion
     */
    public function GetServerVersion() {
        return $this->serverVersion;
    }

    public function commitTransaction() {/* nothing here */}

    /** @param mixed $value
     *  @return string */
    public final function getQuotedString($value) {
        if (is_array($value))
            return $this->doGetQuotedString(file_get_contents($value[0]));
        else
            return $this->doGetQuotedString($value);
    }

    /**
     * @param string $value
     * @return string|null
     */
    protected function doGetQuotedString($value) {
        return null;
    }

    public function getEscapedString($value) {
        return str_replace('\'', '\'\'', $value);
    }

    /**
     * @param string $sql
     */
    public final function logQuery($sql) {
        if (DebugUtils::GetDebugLevel() > 0) {
            $this->queryLog[] = $sql;
        }
    }

    /**
     * @return array
     */
    public final function getQueryLog()
    {
        return $this->queryLog;
    }
}

abstract class EngDataReader implements IEngDataReader {
    /** @var string */
    private $sql;
    /** @var \IEngConnection */
    private $connection;
    /** @var string[] */
    private $fieldList;

    /** @var int */
    private $rowLimit;

    /** @var array */
    private $fieldInfos;

    /**
     * @param IEngConnection $connection
     * @param string $sql
     */
    public function __construct(IEngConnection $connection, $sql) {
        $this->fieldInfos = array();
        $this->connection = $connection;
        $this->sql = $sql;
        $this->fieldList = array();
        $this->rowLimit = -1;
    }

    #region Internal field names management

    /**
     * @return string
     */
    protected function FetchField() {
    }

    protected function FetchFields() {
        $Field = $this->FetchField();
        while ($Field) {
            $this->AddField($Field);
            $Field = $this->FetchField();
        }
    }

    protected function GetFieldIndexByName($fieldName) {
        return array_search($fieldName, $this->fieldList);
    }

    protected function AddField($field) {
        $this->fieldList[] = $field;
    }

    protected function ClearFields() {
        $this->fieldList = array();
    }

    public function FieldCount() {
        return count($this->fieldList);
    }

    public function GetField($index) {
        return $this->fieldList[$index];
    }

    #endregion

    #region Field management

    /**
     * @param FieldInfo $fieldInfo
     */
    public function AddFieldInfo(FieldInfo $fieldInfo) {
        if (isset($fieldInfo->Alias))
            $this->fieldInfos[$fieldInfo->Alias] = $fieldInfo;
        else
            $this->fieldInfos[$fieldInfo->Name] = $fieldInfo;
    }

    /**
     * @param string $fieldName
     * @return FieldInfo
     */
    public function GetFieldInfoByFieldName($fieldName) {
        if (isset($this->fieldInfos[$fieldName]))
            return $this->fieldInfos[$fieldName];
        else
            return null;
    }

    #endregion

    /**
     * @return bool
     */
    protected abstract function DoOpen();

    /**
     * @return bool
     */
    public abstract function Opened();

    protected function DoClose() {
    }

    public function GetSQL() {
        return $this->sql;
    }

    public function SetSQL($sql) {
        $this->sql = $sql;
    }

    public function SetRowLimit($value) {
        $this->rowLimit = $value;
    }

    public function GetRowLimit() {
        return $this->rowLimit;
    }

    public function GetConnection() {
        return $this->connection;
    }

    public function Open() {
        if (!$this->Opened()) {
            $this->ClearFields();
            if (!$this->DoOpen()) {
                throw new SMSQLException($this->LastError());
            }
            if ($this->Opened()) {
                $this->connection->logQuery($this->sql);
                $this->FetchFields();
            }
        }
    }

    public function Close() {
        if ($this->Opened())
            $this->DoClose();
    }

    /**
     * @return boolean
     * @description We cannot declare it abstract due to PHP 5.2 limitations
     */
    public function Next() { return false; }

    protected function LastError() {
        return $this->GetConnection()->LastError();
    }

    /**
     * @param string $fieldName
     * @return mixed
     * @description We cannot declare it abstract due to PHP 5.2 limitations
     */
    public function GetFieldValueByName($fieldName) { return null; }

    protected function GetActualFieldValue(&$fieldName, $value) {
        $fieldInfo = $this->GetFieldInfoByFieldName($fieldName);
        if (!isset($fieldInfo))
            return $value;
        if ($fieldInfo->FieldType == ftDateTime)
            return AnsiSQLStringToDateTime($value);
        elseif ($fieldInfo->FieldType == ftDate)
            return AnsiSQLStringToDate($value);
        elseif ($fieldInfo->FieldType == ftTime)
            return AnsiSQLStringToTime($value);
        else {
            return $value;
        }
    }
}
