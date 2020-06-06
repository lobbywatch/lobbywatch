<?php

include_once dirname(__FILE__) . '/' . 'engine.php';

abstract class PDOConnection extends EngConnection
{
    /**
     * @var PDO
     */
    private $connection;

    private $connectionError = '';

    protected abstract function CreatePDOConnection();

    protected abstract function DoAfterConnect();

    protected function DoConnect()
    {
        try
        {
            $this->connection = @$this->CreatePDOConnection();
            $this->DoAfterConnect();
            return true;
        }
        catch (PDOException $e)
        {
            $this->connectionError = $e->getMessage();
            return false;
        }
    }

    protected function DoDisconnect()
    { }

    protected function DoCreateDataReader($sql)
    {
        return new PDODataReader($this, $sql);
    }

    public function GetConnectionHandle()
    {
        return $this->connection;
    }

    public function IsDriverSupported() {
        return class_exists('PDO', false);
    }

    protected function DoExecSQL($sql)
    {
        return !($this->connection->exec($sql) === false);
    }

    public function GetLastInsertId()
    {
        return $this->connection->lastInsertId();
    }

    public function ExecScalarSQL($sql) {
        $this->logQuery($sql);
        if ($queryHandle = $this->connection->query($sql)) {
            $row = $queryHandle->fetch(PDO::FETCH_NUM);
            if ($row === false) {
                $this->raiseSQLStatementReturnsNoRowsException($sql);
            } else {
                return $row[0];
            }
        } else {
            $this->raiseSQLExecutionException($sql);
        }
    }

    protected  function doExecQueryToArray($sql, &$array)
    {
        if ($queryHandle = $this->connection->query($sql)) {
            while ($row = $queryHandle->fetch(PDO::FETCH_ASSOC)) {
                $array[] = $row;
            }
            return true;
        }
        return false;
    }

    public function DoLastError()
    {
        if ($this->connection)
        {
                $pdoErrorInfo = $this->connection->errorInfo();
                return $pdoErrorInfo[2];
        }
        else
        {
            return $this->connectionError;
        }
    }

    public function SupportsLastInsertId()
    {
        return true;
    }

    public function commitTransaction() {
        $this->connection->commit();
    }

    protected function doGetQuotedString($value) {
        return $this->GetConnectionHandle()->quote($value);
    }

}

class PDODataReader extends EngDataReader
{
    # private

    /**
     * @var PDOStatement
     */
    private $statement;
    private $lastFetchedRow;
    private $nativeTypes;

    /**
     * @var PDOException
     */
    private $lastException;
    /**
     * @var PDOConnection
     */
    private $pdoConnection;

    # protected

    protected function GetColumnNativeType($fieldName)
    {
        if (isset($this->nativeTypes[$fieldName]))
            return $this->nativeTypes[$fieldName];
        else
            return '';
    }

    protected function FetchField() { echo "not supported"; }

    protected function FetchFields()
    {
        for($i = 0; $i < $this->statement->columnCount(); $i++)
        {
            $columnMetadata = $this->statement->getColumnMeta($i);
            $this->AddField($columnMetadata['name']);
            if (isset($columnMetadata['native_type']))
                $this->nativeTypes[$columnMetadata['name']] = $columnMetadata['native_type'];
        }
    }

    protected function DoOpen()
    {
        try
        {
            $this->statement = $this->pdoConnection->GetConnectionHandle()->query($this->GetSQL());
            if (!$this->statement)
                return false;
            return true;
        }
        catch(PDOException $e)
        {
            $this->lastException = $e;
            return false;
        }
    }

    public function __construct($connection, $sql)
    {
        parent::__construct($connection, $sql);
        $this->statement = null;
        $this->nativeTypes = array();
        $this->pdoConnection = $connection;
    }

    public function Opened()
    {
        return $this->statement ? true : false;
    }

    public function Seek($rowIndex)
    { }

    public function Next()
    {
        try
        {
            $this->lastFetchedRow = $this->statement->fetch();
            if($this->lastFetchedRow)
            {
                $this->TransformFetchedValues();
                return true;
            }
            else
                return false;
        }
        catch(PDOException $e)
        {
            $this->lastException = $e;
            return false;
        }
    }

    protected function DoTransformFetchedValue($fieldName, &$fetchedValue)
    {
        return $fetchedValue;
    }

    public function TransformFetchedValues()
    {
        for($i = 0; $i < $this->FieldCount(); $i++)
            $this->lastFetchedRow[$this->GetField($i)] =
                $this->DoTransformFetchedValue($this->GetField($i), $this->lastFetchedRow[$this->GetField($i)]);
    }

    public function GetFieldValueByName($fieldName)
    {
        if ($this->lastFetchedRow) {
            return $this->GetActualFieldValue($fieldName, $this->lastFetchedRow[$fieldName]);
        } else {
            return null;
        }
    }

    protected function LastError()
    {
        if (isset($this->lastException))
        {
            return $this->lastException->getMessage();
        }
        else
            return parent::LastError();
    }    

    public function CursorPosition(){}
    public function Prev(){}
    public function First(){}
    public function Last(){}
}
