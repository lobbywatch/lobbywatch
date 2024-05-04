<?php
// Processed by afterburner.sh



include_once dirname(__FILE__) . '/' . 'engine.php';
include_once dirname(__FILE__) . '/' . 'pdo_engine.php';

class MyConnectionFactory extends ConnectionFactory {
    public function DoCreateConnection($connectionParams) {
        return new MyConnection($connectionParams);
    }

    public function CreateDataReader(IEngConnection $connection, $sql) {
        return new MyDataReader($connection, $sql);
    }

    function CreateEngCommandImp() {
        return new MyCommandImp($this);
    }

}

class MySqlIConnectionFactory extends ConnectionFactory {
    public function DoCreateConnection($connectionParams) {
        return new MySqlIConnection($connectionParams);
    }

    public function CreateDataReader(IEngConnection $connection, $sql) {
        return new MySqlIDataReader($connection, $sql);
    }

    function CreateEngCommandImp() {
        return new MyCommandImp($this);
    }

    function CreateCustomUpdateCommand($sql) {
        if (is_array($sql))
            return new MultiStatementUpdateCommand($sql, $this->CreateEngCommandImp());
        else
            return parent::CreateCustomUpdateCommand($sql);
    }

    function CreateCustomInsertCommand($sql) {
        if (is_array($sql))
            return new MultiStatementInsertCommand($sql, $this->CreateEngCommandImp());
        else
            return parent::CreateCustomInsertCommand($sql);
    }

    function CreateCustomDeleteCommand($sql) {
        if (is_array($sql))
            return new MultiStatementDeleteCommand($sql, $this->CreateEngCommandImp());
        else
            return parent::CreateCustomDeleteCommand($sql);
    }

}

class MyPDOConnectionFactory extends ConnectionFactory {
    public function DoCreateConnection($connectionParams) {
        return new MyPDOConnection($connectionParams);
    }

    public function CreateDataReader(IEngConnection $connection, $sql) {
        return new PDODataReader($connection, $sql);
    }

    function CreateEngCommandImp() {
        return new MyCommandImp($this);
    }

    function CreateCustomUpdateCommand($sql) {
        if (is_array($sql))
            return new MultiStatementUpdateCommand($sql, $this->CreateEngCommandImp());
        else
            return parent::CreateCustomUpdateCommand($sql);
    }

    function CreateCustomInsertCommand($sql) {
        if (is_array($sql))
            return new MultiStatementInsertCommand($sql, $this->CreateEngCommandImp());
        else
            return parent::CreateCustomInsertCommand($sql);
    }

    function CreateCustomDeleteCommand($sql) {
        if (is_array($sql))
            return new MultiStatementDeleteCommand($sql, $this->CreateEngCommandImp());
        else
            return parent::CreateCustomDeleteCommand($sql);
    }

}

class MyCommandImp extends EngCommandImp {
    public function GetFirstQuoteChar() {
        return '`';
    }

    public function GetLastQuoteChar() {
        return '`';
    }

    protected function GetDateTimeFieldAsSQLForSelect($fieldInfo) {
        $result = sprintf('DATE_FORMAT(%s, \'%s\')', $this->GetFieldFullName($fieldInfo), '%Y-%m-%d %H:%i:%S');
        return $result;
    }

    protected function CreateCaseSensitiveLikeExpression($left, $right) {
        return sprintf('BINARY(%s) LIKE BINARY(%s)', $left, $right);
    }

    public function EscapeString($string) {
        // return mysql_escape_string($string);
        // mysql_real_escape_string requires the connection
        if (is_null($string)) {
            return '';
        }
        $replacements = array(
            "\x00" => '\x00',
            "\n" => '\n',
            "\r" => '\r',
            "\\" => '\\\\',
            "'" => "\'",
            '"' => '\"',
            // We need an additional variation: the underscore symbol should be escaped only in LIKE operator but
            // must not be escaped on using the = operator i.e.
            // (upper(CAST(`SM_SOURCE_SQL`.`Name` AS CHAR)) LIKE upper('%\_%')) but
            //   `SM_SOURCE_SQL`.`Name` = 'film_list' (not = 'film\_list')
            // '_' => '\_',
            "\x1a" => '\x1a'
        );
        return strtr($string, $replacements);
    }

    public function QuoteIdentifier($identifier) {
        return '`' . $identifier . '`';
    }

    /**
     * @param mixed $value
     * @return string
     */
    protected function GetBlobFieldValueAsSQL($value) {
        return $this->GetConnectionFactory()->GetMasterConnection()->getQuotedString($value);
    }

    /** @inheritdoc */
    public function getSelectSQLWithLimitation($selectSQL, $limitNumber, $limitOffset, $hasSorting) {
        return $selectSQL . ' ' . sprintf('LIMIT %d, %d', $limitOffset, $limitNumber);
    }
}

class MyConnection extends EngConnection {
    private $connectionHandle;

    public function IsDriverSupported() {
        return function_exists('mysql_connect');
    }

    protected function DoGetDBMSName() {
        return 'MySQL';
    }

    protected function DoGetDriverExtensionName() {
        return 'mysql';
    }

    protected function DoGetDriverInstallationLink() {
        return 'http://www.php.net/manual/en/mysql.installation.php';
    }

    protected function DoConnect() {
        $this->connectionHandle = @mysql_pconnect(
            $this->ConnectionParam('server') . ':' . $this->ConnectionParam('port'),
            $this->ConnectionParam('username'),
            $this->ConnectionParam('password'));

        if ($this->connectionHandle)
            if (@mysql_select_db($this->ConnectionParam('database'))) {
                if ($this->ConnectionParam('client_encoding') != '') {
                    try {
                        $this->ExecSQL('SET NAMES \'' . $this->ConnectionParam('client_encoding') . '\'');
                    } catch (Exception $e) {
                    }
                }
                return true;
            }
        return false;
    }

    protected function DoDisconnect() {
        @mysql_close($this->connectionHandle);
    }

    public function SupportsLastInsertId() {
        return true;
    }

    public function GetLastInsertId() {
        $result = @mysql_insert_id($this->GetConnectionHandle());
        if ($result === 0)
            return null;
        else
            return $result;
    }

    protected function DoCreateDataReader($sql) {
        return new MyDataReader($this, $sql);
    }

    public function GetConnectionHandle() {
        return $this->connectionHandle;
    }

    protected function DoExecSQL($sql) {
        if (@mysql_query($sql, $this->GetConnectionHandle()))
            return true;
        else
            return false;
    }

    protected  function doExecScalarSQL($sql) {
        if ($queryHandle = @mysql_query($sql, $this->GetConnectionHandle())) {
            $queryResult = @mysql_fetch_array($queryHandle, MYSQL_NUM);
            @mysql_free_result($queryHandle);
            return $queryResult[0];
        }
        return false;
    }

    protected function doExecQueryToArray($sql, &$array) {
        $this->logQuery($sql);
        if ($queryHandle = @mysql_query($sql, $this->GetConnectionHandle())) {
            while ($row = @mysql_fetch_array($queryHandle, MYSQL_BOTH)) {
                $array[] = $row;
            }
            @mysql_free_result($queryHandle);
            return true;
        }
        return false;
    }

    public function DoLastError() {
        if ($this->connectionHandle)
            return mysql_error($this->connectionHandle);
        else
            return mysql_error();
    }

    protected function doGetQuotedString($value) {
        $result = mysql_real_escape_string($value);
        return '\'' . $result . '\'';
    }
}

class MyDataReader extends EngDataReader {
    private $queryResult;
    private $lastFetchedRow;
    /**
     * @var MyConnection
     */
    private $myConnection;

    protected function FetchField() {
        $Field = mysql_fetch_field($this->queryResult);
        if ($Field)
            return $Field->name;
        else
            return null;
    }

    protected function DoOpen() {
        $this->queryResult = mysql_query($this->GetSQL(), $this->myConnection->GetConnectionHandle());
        if ($this->queryResult)
            return true;
        else
            return false;
    }

    /**
     * @param IEngConnection $connection
     * @param string $sql
     */
    public function __construct(IEngConnection $connection, $sql) {
        parent::__construct($connection, $sql);
        $this->queryResult = null;
        $this->myConnection = $connection;
    }

    public function Opened() {
        return $this->queryResult ? true : false;
    }

    public function Seek($ARowIndex) {
        mysql_data_seek($this->queryResult, $ARowIndex);
    }

    public function Next() {
        $this->lastFetchedRow = mysql_fetch_array($this->queryResult);
        return $this->lastFetchedRow ? true : false;
    }

    public function GetFieldValueByName($AFieldName) {
        return $this->GetActualFieldValue($AFieldName, $this->lastFetchedRow[$AFieldName]);
    }

}

class MySqlIConnection extends EngConnection {
    private $connectionHandle;

    protected function DoConnect() {
        if ($this->HasConnectionParam('port')) {
            $this->connectionHandle = @mysqli_connect(
                $this->ConnectionParam('server'),
                $this->ConnectionParam('username'),
                $this->ConnectionParam('password'),
                $this->ConnectionParam('database'),
                $this->ConnectionParam('port')
            );
        } else {
            $this->connectionHandle = @mysqli_connect(
                $this->ConnectionParam('server'),
                $this->ConnectionParam('username'),
                $this->ConnectionParam('password'),
                $this->ConnectionParam('database')
            );
        }

        if ($this->connectionHandle) {
            if ($this->ConnectionParam('client_encoding') != '') {
                try {
                    $this->ExecSQL('SET NAMES \'' . $this->ConnectionParam('client_encoding') . '\'');
                } catch (Exception $e) {
                }
            }
            return true;
        }
        return false;
    }

    protected function DoDisconnect() {
        @mysqli_close($this->connectionHandle);
    }

    public function SupportsLastInsertId() {
        return true;
    }

    public function GetLastInsertId() {
        return @mysqli_insert_id($this->GetConnectionHandle());
    }

    protected function DoCreateDataReader($sql) {
        return new MySqlIDataReader($this, $sql);
    }

    /**
     * @return mysqli
     */
    public function GetConnectionHandle() {
        return $this->connectionHandle;
    }

    public function DoExecSQL($sql) {
        $queryHandle = @mysqli_query($this->GetConnectionHandle(), $sql);
        $result = $queryHandle ? true : false;
        if ($result && ($queryHandle instanceof mysqli_result))
            @mysqli_free_result($queryHandle);
        return $result;
    }

    public function ExecScalarSQL($sql) {
        $this->logQuery($sql);
        if ($queryHandle = @mysqli_query($this->GetConnectionHandle(), $sql)) {
            $queryResult = @mysqli_fetch_array($queryHandle, MYSQLI_NUM);
            @mysqli_free_result($queryHandle);
            if (isset($queryResult)) {
                return $queryResult[0];
            } else {
                $this->raiseSQLStatementReturnsNoRowsException($sql);
            }
        } else {
            $this->raiseSQLExecutionException($sql);
        }
    }

    protected function doExecQueryToArray($sql, &$array) {
        $this->logQuery($sql);
        if ($queryHandle = @mysqli_query($this->GetConnectionHandle(), $sql)) {
            while ($row = @mysqli_fetch_array($queryHandle, MYSQLI_BOTH)) {
                $array[] = $row;
            }
            @mysqli_free_result($queryHandle);
            return true;
        }
        return false;
    }

    public function nextResult() {
        @mysqli_next_result($this->GetConnectionHandle());
    }

    public function IsDriverSupported() {
        return function_exists('mysqli_connect');
    }

    protected function DoGetDBMSName() {
        return 'MySQL';
    }

    protected function DoGetDriverExtensionName() {
        return 'mysqli';
    }

    protected function DoGetDriverInstallationLink() {
        return 'http://www.php.net/manual/en/mysqli.installation.php';
    }

    public function DoLastError() {
        if ($this->connectionHandle)
            return mysqli_error($this->connectionHandle);
        else
            return 'mysqli_connect failed';
    }

    protected function doGetQuotedString($value) {
        $result = mysqli_real_escape_string($this->connectionHandle, $value);
        return '\'' . $result . '\'';
    }
}

class MySqlIDataReader extends EngDataReader {
    private $queryResult;
    private $lastFetchedRow;
    /**
     * @var MySqlIConnection
     */
    private $mysqliConnection;

    protected function FetchField() {
        $field = @mysqli_fetch_field($this->queryResult);
        if ($field)
            return $field->name;
        else
            return null;
    }

    protected function DoOpen() {
        $this->queryResult = mysqli_query($this->mysqliConnection->GetConnectionHandle(), $this->GetSQL());
        if ($this->queryResult)
            return true;
        else
            return false;
    }

    /**
     * @param IEngConnection $connection
     * @param string $sql
     */
    public function __construct(IEngConnection $connection, $sql) {
        parent::__construct($connection, $sql);
        $this->queryResult = null;
        $this->mysqliConnection = $connection;
    }

    public function Opened() {
        return $this->queryResult ? true : false;
    }

    public function Seek($rowIndex) {
        mysqli_data_seek($this->queryResult, $rowIndex);
    }

    public function Next() {
        $this->lastFetchedRow = mysqli_fetch_array($this->queryResult);
        return $this->lastFetchedRow ? true : false;
    }

    public function GetFieldValueByName($fieldName) {
        if ($this->lastFetchedRow) {
            return $this->GetActualFieldValue($fieldName, $this->lastFetchedRow[$fieldName]);
        } else {
            return null;
        }
    }
}

class MyPDOConnection extends PDOConnection {
    protected function CreatePDOConnection() {
        return new PDO(
            sprintf('mysql:host=%s;port=%s;dbname=%s',
                $this->ConnectionParam('server'),
                $this->ConnectionParam('port'),
                $this->ConnectionParam('database')),
            $this->ConnectionParam('username'),
            $this->ConnectionParam('password'));
    }

    public function IsDriverSupported() {
        return defined('PDO::MYSQL_ATTR_INIT_COMMAND');
    }

    protected function DoGetDBMSName() {
        return 'MySQL';
    }

    protected function DoGetDriverExtensionName() {
        return 'pdo_mysql';
    }

    protected function DoGetDriverInstallationLink() {
        return 'http://php.net/manual/en/ref.pdo-mysql.php';
    }

    protected function DoAfterConnect() {
        if ($this->ConnectionParam('client_encoding') != '') {
            try {
                $this->ExecSQL('SET NAMES \'' . $this->ConnectionParam('client_encoding') . '\'');
            } catch (Exception $e) {
            }
        }
    }

}
