<?php

include_once dirname(__FILE__) . '/' . 'table_dataset.php';

class ViewBasedDataset extends TableDataset {
    /** @var string[] */
    private $insertSql = array();
    /** @var string[] */
    private $updateSql = array();
    /** @var string[] */
    private $deleteSql = array();

    /**
     * @param ConnectionFactory $connectionFactory
     * @param array $connectionParams
     * @param string $tableName
     * @param string[] $insertSql
     * @param string[] $updateSql
     * @param string[] $deleteSql
     */
    function __construct($connectionFactory, $connectionParams, $tableName,
        $insertSql = array(), $updateSql = array(), $deleteSql = array())
    {
        parent::__construct($connectionFactory, $connectionParams, $tableName);
        $this->insertSql = $insertSql;
        $this->updateSql = $updateSql;
        $this->deleteSql = $deleteSql;
    }

    /** @return string[] */
    public function getInsertSQL() {
        return $this->insertSql;
    }

    /** @var string[] $value */
    public function setInsertSQL($value) {
        $this->insertSql = $value;
    }

    /** @inheritdoc */
    protected function DoCreateInsertCommand() {
        if (empty($this->insertSql)) {
            return parent::DoCreateInsertCommand();
        } else {
            $result = $this->GetConnectionFactory()->CreateCustomInsertCommand($this->insertSql);
            foreach($this->GetFields() as $field)
                $result->AddField($field->GetName(), $field->GetEngFieldType());
            return $result;
        }
    }

    /** @return string[] */
    public function getUpdateSQL() {
        return $this->updateSql;
    }

    /** @var string[] $value */
    public function setUpdateSQL($value) {
        $this->updateSql = $value;
    }

    /** @inheritdoc */
    protected function DoCreateUpdateCommand() {
        if (empty($this->updateSql)) {
            return parent::DoCreateUpdateCommand();
        } else {
            $result = $this->GetConnectionFactory()->CreateCustomUpdateCommand($this->updateSql);
            foreach ($this->GetFields() as $field)
                $result->AddField($field->GetName(), $field->GetEngFieldType(), $this->IsFieldPrimaryKey($field->GetName()));
            return $result;
        }
    }

    /** @return string[] */
    public function getDeleteSQL() {
        return $this->deleteSql;
    }

    /** @var string[] $value */
    public function setDeleteSQL($value) {
        $this->deleteSql = $value;
    }

    /** @inheritdoc */
    protected function DoCreateDeleteCommand() {
        if (empty($this->deleteSql)) {
            return parent::DoCreateDeleteCommand();
        } else {
            $result = $this->GetConnectionFactory()->CreateCustomDeleteCommand($this->deleteSql);
            foreach ($this->GetFields() as $field)
                if ($this->IsFieldPrimaryKey($field->GetName()))
                    $result->AddField($field->GetName(), $field->GetEngFieldType());
            return $result;
        }
    }

}
