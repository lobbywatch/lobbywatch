<?php

include_once dirname(__FILE__) . '/' . 'dataset.php';

class QueryDataset extends Dataset
{
    /** @var string */
    private $sql;

    /** @var string[] */
    private $insertSql;

    /** @var string[] */
    private $updateSql;

    /** @var string[] */
    private $deleteSql;

    /** @var string */
    private $name;

    /**
     * @param ConnectionFactory $connectionFactory
     * @param array $connectionParams
     * @param string $sql
     * @param string[] $insertSql
     * @param string[] $updateSql
     * @param string[] $deleteSql
     * @param string $name
     */
    function __construct($connectionFactory, $connectionParams, $sql, $insertSql, $updateSql, $deleteSql, $name)
    {
        $this->sql = $sql;
        $this->insertSql = $insertSql;
        $this->updateSql = $updateSql;
        $this->deleteSql = $deleteSql;
        $this->name = $name;
        parent::__construct($connectionFactory, $connectionParams);
    }

    public function DoNotRewriteUnchangedValues() {
        return false;
    }

    /**
     * @return string
     */
    public function GetName()
    {
        return $this->name;
    }

    #region Commands

    /**
     * @return CustomSelectCommand
     */
    function CreateSelectCommand()
    {
        return $this->GetConnectionFactory()->CreateCustomSelectCommand($this->sql);
    }

    protected function DoCreateUpdateCommand()
    {
        $result = $this->GetConnectionFactory()->CreateCustomUpdateCommand($this->updateSql);
        foreach($this->GetFields() as $field)
            $result->AddField($field->GetName(), $field->GetEngFieldType(), $this->IsFieldPrimaryKey($field->GetName()));
        return $result;
    }

    protected function DoCreateInsertCommand()
    {
        $result = $this->GetConnectionFactory()->CreateCustomInsertCommand($this->insertSql);
        foreach($this->GetFields() as $field)
            $result->AddField($field->GetName(), $field->GetEngFieldType());
        return $result;
    }

    protected function DoCreateDeleteCommand()
    {
        $result = $this->GetConnectionFactory()->CreateCustomDeleteCommand($this->deleteSql);
        foreach($this->GetFields() as $field)
            if ($this->IsFieldPrimaryKey($field->GetName()))
                $result->AddField($field->GetName(), $field->GetEngFieldType());
        return $result;
    }

    #endregion

    /**
     * @param Field $field
     * @return void
     */
    protected function DoAddField($field)
    {
        $this->GetSelectCommand()->AddField(
            $field->GetSourceTable(), $field->GetName(), 
            $field->GetEngFieldType(), $field->GetAlias()
            );
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
        if (!(!isset($sourceTable) || $sourceTable == ''))
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
