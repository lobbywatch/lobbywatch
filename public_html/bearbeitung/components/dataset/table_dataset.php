<?php

include_once dirname(__FILE__) . '/' . 'dataset.php';

define('KEEP_IMAGE_ACTION', 'Keep');
define('REMOVE_IMAGE_ACTION', 'Remove');
define('REPLACE_IMAGE_ACTION', 'Replace');

class TableDataset extends Dataset
{
    /** @var string */
    private $tableName;

    /**
     * @param ConnectionFactory $ConnectionFactory
     * @param array $ConnectionParams
     * @param $tableName
     * @return \TableDataset
     */
    function __construct($ConnectionFactory, $ConnectionParams, $tableName)
    {
        parent::__construct($ConnectionFactory, $ConnectionParams);
        $this->SetTableName($tableName);
    }

    /**
     * @return string
     */
    public function GetName()
    {
        return $this->tableName;
    }

    #region Commands

    protected function CreateSelectCommand()
    {
        return $this->GetConnectionFactory()->CreateSelectCommand();
    }

    protected function DoCreateUpdateCommand()
    {
        $result = $this->GetConnectionFactory()->CreateUpdateCommand();;
        $result->SetTableName($this->tableName);
        foreach($this->GetFields() as $field)
            $result->AddField($field->GetName(), $field->GetEngFieldType(), in_array($field->GetName(), $this->GetPrimaryKeyFields()));
        return $result;
    }

    protected function DoCreateInsertCommand()
    {
        $result = $this->GetConnectionFactory()->CreateInsertCommand();
        $result->SetTableName($this->tableName);
        foreach($this->GetFields() as $field)
            $result->AddField($field->GetName(), $field->GetEngFieldType());
        return $result;
    }

    protected function DoCreateDeleteCommand()
    {
        $result = $this->GetConnectionFactory()->CreateDeleteCommand();
        $result->SetTableName($this->tableName);
        foreach($this->GetFields() as $field)
            if ($this->IsFieldPrimaryKey($field->GetName()))
                $result->AddField($field->GetName(), $field->GetEngFieldType());
        return $result;
    }

    #endregion

    /**
     * @param string $tableName
     * @return void
     */
    public function SetTableName($tableName)
    {
        $this->tableName = $tableName;
        $this->GetSelectCommand()->SetSourceTableName($tableName, null);
    }

    /**
     * @return string
     */
    public function GetTableName()
    {
        return $this->tableName;
    }

    /**
     * @param Field $field
     * @return void
     */
    protected function DoAddField($field)
    {
        $sourceTable = $field->GetSourceTable();
        if (!isset($sourceTable) || $sourceTable == '')
            $sourceTable = $this->tableName;
        $this->GetSelectCommand()->AddField(
            $sourceTable, $field->GetName(), 
            $field->GetEngFieldType(), $field->GetAlias());
    }

    /**
     * @param string $fieldName
     * @param string $lookUpTable
     * @param Field $lookUpLinkField
     * @param Field $lookupDisplayField
     * @param string $lookUpTableAlias
     * @return void
     */
    public function AddLookupField($fieldName, $lookUpTable, $lookUpLinkField, $lookupDisplayField, $lookUpTableAlias )
    {
        parent::AddLookupField($fieldName, $lookUpTable, $lookUpLinkField, $lookupDisplayField, $lookUpTableAlias);

        $sourceTable = $lookupDisplayField->GetSourceTable();
        if (!isset($sourceTable) || $sourceTable == '')
            $sourceTable = $this->tableName;
        else
            $sourceTable = $this->GetCommandImp()->QuoteIdentifier($sourceTable);
        $lookupDisplayField->SetSourceTable($sourceTable);
        
        $this->AddField($lookupDisplayField);

        $this->GetSelectCommand()->AddJoin(jkLeftOuter,
            $lookUpTable,
            $fieldName,
            $lookUpLinkField->GetName(),
            $lookUpTableAlias
        );
    }
}    


