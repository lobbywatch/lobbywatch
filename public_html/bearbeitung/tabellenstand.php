<?php
// Processed by afterburner.sh


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *                                   ATTENTION!
 * If you see this message in your browser (Internet Explorer, Mozilla Firefox, Google Chrome, etc.)
 * this means that PHP is not properly installed on your web server. Please refer to the PHP manual
 * for more details: http://php.net/manual/install.php 
 *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 */

    include_once dirname(__FILE__) . '/components/startup.php';
    include_once dirname(__FILE__) . '/components/application.php';
    include_once dirname(__FILE__) . '/' . 'authorization.php';


    include_once dirname(__FILE__) . '/' . 'database_engine/mysql_engine.php';
    include_once dirname(__FILE__) . '/' . 'components/page/page_includes.php';

    function GetConnectionOptions()
    {
        $result = GetGlobalConnectionOptions();
        $result['client_encoding'] = 'utf8';
        GetApplication()->GetUserAuthentication()->applyIdentityToConnectionOptions($result);
        return $result;
    }

    // OnGlobalBeforePageExecute event handler
    globalOnBeforePageExecute();
    
    
    // OnBeforePageExecute event handler
    
    
    
    class q_last_updated_tablesPage extends Page
    {
        protected function DoBeforeCreate()
        {
            $selectQuery = 'SELECT tn as table_name, n as name, ne as anzahl_eintraege, lv as last_visa, ld as last_updated, lid as last_updated_id
            FROM (SELECT * FROM (
            (SELECT \'branche\' tn, \'Branche\' n, (select count(*) from `branche`) ne, t.`updated_visa` AS lv, t.`updated_date` ld, t.id lid FROM  `branche` t ORDER BY t.`updated_date` DESC LIMIT 1)
            UNION (SELECT \'interessenbindung\' tn, \'Interessenbindung\' n, (select count(*) from `interessenbindung`) ne, t.`updated_visa` AS lv, t.`updated_date` ld, t.id lid FROM  `interessenbindung` t ORDER BY t.`updated_date` DESC LIMIT 1)
            UNION (SELECT \'interessenbindung_jahr\' tn, \'Interessenbindungsverguetung\' n, (select count(*) from `interessenbindung_jahr`) ne, t.`updated_visa` AS lv, t.`updated_date` ld, t.id lid FROM  `interessenbindung_jahr` t ORDER BY t.`updated_date` DESC LIMIT 1)
            UNION (SELECT \'interessengruppe\' tn, \'Lobbygruppe\' n, (select count(*) from `interessengruppe`) ne, t.`updated_visa` AS lv, t.`updated_date` ld, t.id lid FROM  `interessengruppe` t ORDER BY t.`updated_date` DESC LIMIT 1)
            UNION (SELECT \'in_kommission\' tn, \'In Kommission\' n, (select count(*) from `in_kommission`) ne, t.`updated_visa` AS lv, t.`updated_date` ld, t.id lid FROM  `in_kommission` t ORDER BY t.`updated_date` DESC LIMIT 1)
            UNION (SELECT \'kommission\' tn, \'Kommission\' n, (select count(*) from `kommission`) ne, t.`updated_visa` AS lv, t.`updated_date` ld, t.id lid FROM  `kommission` t ORDER BY t.`updated_date` DESC LIMIT 1)
            UNION (SELECT \'mandat\' tn, \'Mandat\' n, (select count(*) from `mandat`) ne, t.`updated_visa` AS lv, t.`updated_date` ld, t.id lid FROM  `mandat` t ORDER BY t.`updated_date` DESC LIMIT 1)
            UNION (SELECT \'mandat_jahr\' tn, \'Mandatsverguetung\' n, (select count(*) from `mandat_jahr`) ne, t.`updated_visa` AS lv, t.`updated_date` ld, t.id lid FROM  `mandat_jahr` t ORDER BY t.`updated_date` DESC LIMIT 1)
            UNION (SELECT \'organisation\' tn, \'Organisation\' n, (select count(*) from `organisation`) ne, t.`updated_visa` AS lv, t.`updated_date` ld, t.id lid FROM  `organisation` t ORDER BY t.`updated_date` DESC LIMIT 1)
            UNION (SELECT \'organisation_beziehung\' tn, \'Organisation Beziehung\' n, (select count(*) from `organisation_beziehung`) ne, t.`updated_visa` AS lv, t.`updated_date` ld, t.id lid FROM  `organisation_beziehung` t ORDER BY t.`updated_date` DESC LIMIT 1)
            UNION (SELECT \'organisation_jahr\' tn, \'Organisationsjahr\' n, (select count(*) from `organisation_jahr`) ne, t.`updated_visa` AS lv, t.`updated_date` ld, t.id lid FROM  `organisation_jahr` t ORDER BY t.`updated_date` DESC LIMIT 1)
            UNION (SELECT \'parlamentarier\' tn, \'Parlamentarier\' n, (select count(*) from `parlamentarier`) ne, t.`updated_visa` AS lv, t.`updated_date` ld, t.id lid FROM  `parlamentarier` t ORDER BY t.`updated_date` DESC LIMIT 1)
            UNION (SELECT \'partei\' tn, \'Partei\' n, (select count(*) from `partei`) ne, t.`updated_visa` AS lv, t.`updated_date` ld, t.id lid FROM  `partei` t ORDER BY t.`updated_date` DESC LIMIT 1)
            UNION (SELECT \'fraktion\' tn, \'Fraktion\' n, (select count(*) from `fraktion`) ne, t.`updated_visa` AS lv, t.`updated_date` ld, t.id lid FROM  `fraktion` t ORDER BY t.`updated_date` DESC LIMIT 1)
            UNION (SELECT \'rat\' tn, \'Rat\' n, (select count(*) from `rat`) ne, t.`updated_visa` AS lv, t.`updated_date` ld, t.id lid FROM  `rat` t ORDER BY t.`updated_date` DESC LIMIT 1)
            UNION (SELECT \'kanton\' tn, \'Kanton\' n, (select count(*) from `kanton`) ne, t.`updated_visa` AS lv, t.`updated_date` ld, t.id lid FROM  `kanton` t ORDER BY t.`updated_date` DESC LIMIT 1)
            UNION (SELECT \'kanton_jahr\' tn, \'Kantonjahr\' n, (select count(*) from `kanton_jahr`) ne, t.`updated_visa` AS lv, t.`updated_date` ld, t.id lid FROM  `kanton_jahr` t ORDER BY t.`updated_date` DESC LIMIT 1)
            UNION (SELECT \'zutrittsberechtigung\' tn, \'Zutrittsberechtigung\' n, (select count(*) from `zutrittsberechtigung`) ne, t.`updated_visa` AS lv, t.`updated_date` ld, t.id lid FROM  `zutrittsberechtigung` t ORDER BY t.`updated_date` DESC LIMIT 1)
            UNION (SELECT \'person\' tn, \'Person\' n, (select count(*) from `person`) ne, t.`updated_visa` AS lv, t.`updated_date` ld, t.id lid FROM  `person` t ORDER BY t.`updated_date` DESC LIMIT 1)
            UNION (SELECT \'parlamentarier_anhang\' tn, \'Parlamentarieranhang\' n, (select count(*) from `parlamentarier_anhang`) ne, t.`updated_visa` AS lv, t.`updated_date` ld, t.id lid FROM  `parlamentarier_anhang` t ORDER BY t.`updated_date` DESC LIMIT 1)
            UNION (SELECT \'organisation_anhang\' tn, \'Organisationsanhang\' n, (select count(*) from `organisation_anhang`) ne, t.`updated_visa` AS lv, t.`updated_date` ld, t.id lid FROM  `organisation_anhang` t ORDER BY t.`updated_date` DESC LIMIT 1)
            UNION (SELECT \'person_anhang\' tn, \'Personenanhang\' n, (select count(*) from `person_anhang`) ne, t.`updated_visa` AS lv, t.`updated_date` ld, t.id lid FROM  `person_anhang` t ORDER BY t.`updated_date` DESC LIMIT 1)
            UNION (SELECT \'settings\' tn, \'Einstellungen\' n, (select count(*) from `settings`) ne, t.`updated_visa` AS lv, t.`updated_date` ld, t.id lid FROM  `settings` t ORDER BY t.`updated_date` DESC LIMIT 1)
            UNION (SELECT \'settings_category\' tn, \'Einstellungskategorien\' n, (select count(*) from `settings_category`) ne, t.`updated_visa` AS lv, t.`updated_date` ld, t.id lid FROM  `settings_category` t ORDER BY t.`updated_date` DESC LIMIT 1)
            ) uq) c ORDER BY c.lid DESC';
            $insertQuery = array();
            $updateQuery = array();
            $deleteQuery = array();
            $this->dataset = new QueryDataset(
              MyPDOConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'q_last_updated_tables');
            $this->dataset->addFields(
                array(
                    new StringField('table_name', false, true),
                    new StringField('name'),
                    new StringField('anzahl_eintraege'),
                    new StringField('last_visa'),
                    new DateTimeField('last_updated'),
                    new IntegerField('last_updated_id')
                )
            );
        }
    
        protected function DoPrepare() {
            globalOnPreparePage($this);
        }
    
        protected function CreatePageNavigator()
        {
            return null;
        }
    
        protected function CreateRssGenerator() {
            return setupRSS($this, $this->dataset); /*afterburner*/ 
        }
    
        protected function setupCharts()
        {
    
        }
    
        protected function getFiltersColumns()
        {
            return array(
                new FilterColumn($this->dataset, 'name', 'name', 'Name'),
                new FilterColumn($this->dataset, 'table_name', 'table_name', 'Table Name'),
                new FilterColumn($this->dataset, 'anzahl_eintraege', 'anzahl_eintraege', 'Anzahl Einträge'),
                new FilterColumn($this->dataset, 'last_updated', 'last_updated', 'Last Updated'),
                new FilterColumn($this->dataset, 'last_visa', 'last_visa', 'Last Visa'),
                new FilterColumn($this->dataset, 'last_updated_id', 'last_updated_id', 'Last Updated Id')
            );
        }
    
        protected function setupQuickFilter(QuickFilter $quickFilter, FixedKeysArray $columns)
        {
            $quickFilter
                ->addColumn($columns['name'])
                ->addColumn($columns['table_name'])
                ->addColumn($columns['anzahl_eintraege'])
                ->addColumn($columns['last_updated'])
                ->addColumn($columns['last_visa'])
                ->addColumn($columns['last_updated_id']);
        }
    
        protected function setupColumnFilter(ColumnFilter $columnFilter)
        {
            $columnFilter
                ->setOptionsFor('last_updated');
        }
    
        protected function setupFilterBuilder(FilterBuilder $filterBuilder, FixedKeysArray $columns)
        {
            $main_editor = new TextEdit('name_edit');
            
            $filterBuilder->addColumn(
                $columns['name'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $main_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $main_editor,
                    FilterConditionOperator::BEGINS_WITH => $main_editor,
                    FilterConditionOperator::ENDS_WITH => $main_editor,
                    FilterConditionOperator::IS_LIKE => $main_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new TextEdit('table_name_edit');
            
            $filterBuilder->addColumn(
                $columns['table_name'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $main_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $main_editor,
                    FilterConditionOperator::BEGINS_WITH => $main_editor,
                    FilterConditionOperator::ENDS_WITH => $main_editor,
                    FilterConditionOperator::IS_LIKE => $main_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new TextEdit('anzahl_eintraege_edit');
            
            $filterBuilder->addColumn(
                $columns['anzahl_eintraege'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $main_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $main_editor,
                    FilterConditionOperator::BEGINS_WITH => $main_editor,
                    FilterConditionOperator::ENDS_WITH => $main_editor,
                    FilterConditionOperator::IS_LIKE => $main_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new DateTimeEdit('last_updated_edit', false, 'Y-m-d H:i:s');
            
            $filterBuilder->addColumn(
                $columns['last_updated'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::DATE_EQUALS => $main_editor,
                    FilterConditionOperator::DATE_DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::TODAY => null,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new TextEdit('last_visa_edit');
            
            $filterBuilder->addColumn(
                $columns['last_visa'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $main_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $main_editor,
                    FilterConditionOperator::BEGINS_WITH => $main_editor,
                    FilterConditionOperator::ENDS_WITH => $main_editor,
                    FilterConditionOperator::IS_LIKE => $main_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new TextEdit('last_updated_id_edit');
            
            $filterBuilder->addColumn(
                $columns['last_updated_id'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
        }
    
        protected function AddOperationsColumns(Grid $grid)
        {
    
        }
    
        protected function AddFieldColumns(Grid $grid, $withDetails = true)
        {
            //
            // View column for name field
            //
            $column = new TextViewColumn('name', 'name', 'Name', $this->dataset);
            $column->SetOrderable(true);
            $column->setHrefTemplate('%table_name%.php?order=dupdated_date');
            $column->setTarget('');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('Name der Tabelle');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for table_name field
            //
            $column = new TextViewColumn('table_name', 'table_name', 'Table Name', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('Technischer Tabellenname in der Datenbank');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for anzahl_eintraege field
            //
            $column = new NumberViewColumn('anzahl_eintraege', 'anzahl_eintraege', 'Anzahl Einträge', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator('\'');
            $column->setDecimalSeparator('.');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('Anzahl Einträge in der Tabelle');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for last_updated field
            //
            $column = new DateTimeViewColumn('last_updated', 'last_updated', 'Last Updated', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('Zuletzt abgeändert am');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for last_visa field
            //
            $column = new TextViewColumn('last_visa', 'last_visa', 'Last Visa', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('Zuletzt abgeändert von');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for last_updated_id field
            //
            $column = new TextViewColumn('last_updated_id', 'last_updated_id', 'Last Updated Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('ID des zuletzt abgeänderten Eintrages');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
        }
    
        protected function AddSingleRecordViewColumns(Grid $grid)
        {
            //
            // View column for name field
            //
            $column = new TextViewColumn('name', 'name', 'Name', $this->dataset);
            $column->SetOrderable(true);
            $column->setHrefTemplate('%table_name%.php?order=dupdated_date');
            $column->setTarget('');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for table_name field
            //
            $column = new TextViewColumn('table_name', 'table_name', 'Table Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for anzahl_eintraege field
            //
            $column = new NumberViewColumn('anzahl_eintraege', 'anzahl_eintraege', 'Anzahl Einträge', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator('\'');
            $column->setDecimalSeparator('.');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for last_updated field
            //
            $column = new DateTimeViewColumn('last_updated', 'last_updated', 'Last Updated', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for last_visa field
            //
            $column = new TextViewColumn('last_visa', 'last_visa', 'Last Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for last_updated_id field
            //
            $column = new TextViewColumn('last_updated_id', 'last_updated_id', 'Last Updated Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
        }
    
        protected function AddEditColumns(Grid $grid)
        {
            //
            // Edit column for name field
            //
            $editor = new TextEdit('name_edit');
            $editColumn = new CustomEditColumn('Name', 'name', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for table_name field
            //
            $editor = new TextEdit('table_name_edit');
            $editColumn = new CustomEditColumn('Table Name', 'table_name', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for anzahl_eintraege field
            //
            $editor = new TextEdit('anzahl_eintraege_edit');
            $editColumn = new CustomEditColumn('Anzahl Einträge', 'anzahl_eintraege', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for last_updated field
            //
            $editor = new DateTimeEdit('last_updated_edit', false, 'Y-m-d H:i:s');
            $editColumn = new CustomEditColumn('Last Updated', 'last_updated', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for last_visa field
            //
            $editor = new TextEdit('last_visa_edit');
            $editColumn = new CustomEditColumn('Last Visa', 'last_visa', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for last_updated_id field
            //
            $editor = new TextEdit('last_updated_id_edit');
            $editColumn = new CustomEditColumn('Last Updated Id', 'last_updated_id', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
        }
    
        protected function AddMultiEditColumns(Grid $grid)
        {
            //
            // Edit column for name field
            //
            $editor = new TextEdit('name_edit');
            $editColumn = new CustomEditColumn('Name', 'name', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for anzahl_eintraege field
            //
            $editor = new TextEdit('anzahl_eintraege_edit');
            $editColumn = new CustomEditColumn('Anzahl Einträge', 'anzahl_eintraege', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for last_updated field
            //
            $editor = new DateTimeEdit('last_updated_edit', false, 'Y-m-d H:i:s');
            $editColumn = new CustomEditColumn('Last Updated', 'last_updated', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for last_visa field
            //
            $editor = new TextEdit('last_visa_edit');
            $editColumn = new CustomEditColumn('Last Visa', 'last_visa', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for last_updated_id field
            //
            $editor = new TextEdit('last_updated_id_edit');
            $editColumn = new CustomEditColumn('Last Updated Id', 'last_updated_id', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
        }
    
        protected function AddInsertColumns(Grid $grid)
        {
            //
            // Edit column for name field
            //
            $editor = new TextEdit('name_edit');
            $editColumn = new CustomEditColumn('Name', 'name', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for table_name field
            //
            $editor = new TextEdit('table_name_edit');
            $editColumn = new CustomEditColumn('Table Name', 'table_name', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for anzahl_eintraege field
            //
            $editor = new TextEdit('anzahl_eintraege_edit');
            $editColumn = new CustomEditColumn('Anzahl Einträge', 'anzahl_eintraege', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for last_updated field
            //
            $editor = new DateTimeEdit('last_updated_edit', false, 'Y-m-d H:i:s');
            $editColumn = new CustomEditColumn('Last Updated', 'last_updated', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for last_visa field
            //
            $editor = new TextEdit('last_visa_edit');
            $editColumn = new CustomEditColumn('Last Visa', 'last_visa', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for last_updated_id field
            //
            $editor = new TextEdit('last_updated_id_edit');
            $editColumn = new CustomEditColumn('Last Updated Id', 'last_updated_id', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            $grid->SetShowAddButton(false && $this->GetSecurityInfo()->HasAddGrant());
        }
    
        private function AddMultiUploadColumn(Grid $grid)
        {
    
        }
    
        protected function AddPrintColumns(Grid $grid)
        {
            //
            // View column for name field
            //
            $column = new TextViewColumn('name', 'name', 'Name', $this->dataset);
            $column->SetOrderable(true);
            $column->setHrefTemplate('%table_name%.php?order=dupdated_date');
            $column->setTarget('');
            $grid->AddPrintColumn($column);
            
            //
            // View column for table_name field
            //
            $column = new TextViewColumn('table_name', 'table_name', 'Table Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for anzahl_eintraege field
            //
            $column = new NumberViewColumn('anzahl_eintraege', 'anzahl_eintraege', 'Anzahl Einträge', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator('\'');
            $column->setDecimalSeparator('.');
            $grid->AddPrintColumn($column);
            
            //
            // View column for last_updated field
            //
            $column = new DateTimeViewColumn('last_updated', 'last_updated', 'Last Updated', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $grid->AddPrintColumn($column);
            
            //
            // View column for last_visa field
            //
            $column = new TextViewColumn('last_visa', 'last_visa', 'Last Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for last_updated_id field
            //
            $column = new TextViewColumn('last_updated_id', 'last_updated_id', 'Last Updated Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
        }
    
        protected function AddExportColumns(Grid $grid)
        {
            //
            // View column for name field
            //
            $column = new TextViewColumn('name', 'name', 'Name', $this->dataset);
            $column->SetOrderable(true);
            $column->setHrefTemplate('%table_name%.php?order=dupdated_date');
            $column->setTarget('');
            $grid->AddExportColumn($column);
            
            //
            // View column for table_name field
            //
            $column = new TextViewColumn('table_name', 'table_name', 'Table Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for anzahl_eintraege field
            //
            $column = new NumberViewColumn('anzahl_eintraege', 'anzahl_eintraege', 'Anzahl Einträge', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator('\'');
            $column->setDecimalSeparator('.');
            $grid->AddExportColumn($column);
            
            //
            // View column for last_updated field
            //
            $column = new DateTimeViewColumn('last_updated', 'last_updated', 'Last Updated', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $grid->AddExportColumn($column);
            
            //
            // View column for last_visa field
            //
            $column = new TextViewColumn('last_visa', 'last_visa', 'Last Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for last_updated_id field
            //
            $column = new TextViewColumn('last_updated_id', 'last_updated_id', 'Last Updated Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
        }
    
        private function AddCompareColumns(Grid $grid)
        {
            //
            // View column for name field
            //
            $column = new TextViewColumn('name', 'name', 'Name', $this->dataset);
            $column->SetOrderable(true);
            $column->setHrefTemplate('%table_name%.php?order=dupdated_date');
            $column->setTarget('');
            $grid->AddCompareColumn($column);
            
            //
            // View column for table_name field
            //
            $column = new TextViewColumn('table_name', 'table_name', 'Table Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for anzahl_eintraege field
            //
            $column = new NumberViewColumn('anzahl_eintraege', 'anzahl_eintraege', 'Anzahl Einträge', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator('\'');
            $column->setDecimalSeparator('.');
            $grid->AddCompareColumn($column);
            
            //
            // View column for last_updated field
            //
            $column = new DateTimeViewColumn('last_updated', 'last_updated', 'Last Updated', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $grid->AddCompareColumn($column);
            
            //
            // View column for last_visa field
            //
            $column = new TextViewColumn('last_visa', 'last_visa', 'Last Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for last_updated_id field
            //
            $column = new TextViewColumn('last_updated_id', 'last_updated_id', 'Last Updated Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
        }
    
        private function AddCompareHeaderColumns(Grid $grid)
        {
    
        }
    
        public function GetPageDirection()
        {
            return null;
        }
    
        public function isFilterConditionRequired()
        {
            return false;
        }
    
        protected function ApplyCommonColumnEditProperties(CustomEditColumn $column)
        {
            $column->SetDisplaySetToNullCheckBox(false);
            $column->SetDisplaySetToDefaultCheckBox(false);
    		$column->SetVariableContainer($this->GetColumnVariableContainer());
        }
    
        function GetCustomClientScript()
        {
            return ;
        }
        
        function GetOnPageLoadedClientScript()
        {
            return ;
        }
    
        protected function CreateGrid()
        {
            $result = new Grid($this, $this->dataset);
            if ($this->GetSecurityInfo()->HasDeleteGrant())
               $result->SetAllowDeleteSelected(false);
            else
               $result->SetAllowDeleteSelected(false);   
            
            ApplyCommonPageSettings($this, $result);
            
            $result->SetUseImagesForActions(true);
            $defaultSortedColumns = array();
            $defaultSortedColumns[] = new SortColumn('last_updated', 'DESC');
            $result->setDefaultOrdering($defaultSortedColumns);
            $result->SetUseFixedHeader(true);
            $result->SetShowLineNumbers(true);
            $result->SetViewMode(ViewMode::TABLE);
            $result->setEnableRuntimeCustomization(true);
            $result->setAllowCompare(true);
            $this->AddCompareHeaderColumns($result);
            $this->AddCompareColumns($result);
            $result->setMultiEditAllowed($this->GetSecurityInfo()->HasEditGrant() && false);
            $result->setTableBordered(false);
            $result->setTableCondensed(false);
            $result->SetTotal('anzahl_eintraege', PredefinedAggregate::$Sum);
            
            $result->SetHighlightRowAtHover(true);
            $result->SetWidth('');
            $this->AddOperationsColumns($result);
            $this->AddFieldColumns($result);
            $this->AddSingleRecordViewColumns($result);
            $this->AddEditColumns($result);
            $this->AddMultiEditColumns($result);
            $this->AddInsertColumns($result);
            $this->AddPrintColumns($result);
            $this->AddExportColumns($result);
            $this->AddMultiUploadColumn($result);
    
    
            $this->SetShowPageList(true);
            $this->SetShowTopPageNavigator(false);
            $this->SetShowBottomPageNavigator(false);
            $this->setPrintListAvailable(true);
            $this->setPrintListRecordAvailable(false);
            $this->setPrintOneRecordAvailable(true);
            $this->setAllowPrintSelectedRecords(true);
            $this->setExportListAvailable(array('excel', 'word', 'xml', 'csv'));
            $this->setExportSelectedRecordsAvailable(array('pdf', 'excel', 'word', 'xml', 'csv'));
            $this->setExportListRecordAvailable(array());
            $this->setExportOneRecordAvailable(array('excel', 'word', 'xml', 'csv'));
            $this->setDescription('' . $GLOBALS["edit_header_message"] /*afterburner*/  . '
            
            <div class="wiki-table-help">
            <p>Zeigt die letzten Änderungen der Tabellen an.
            </p>
            </div>
            
            ' . $GLOBALS["edit_general_hint"] /*afterburner*/  . '');
    
            return $result;
        }
     
        protected function setClientSideEvents(Grid $grid) {
    
        }
    
        protected function doRegisterHandlers() {
            
            
        }
       
        protected function doCustomRenderColumn($fieldName, $fieldData, $rowData, &$customText, &$handled)
        { 
    
        }
    
        protected function doCustomRenderPrintColumn($fieldName, $fieldData, $rowData, &$customText, &$handled)
        { 
    
        }
    
        protected function doCustomRenderExportColumn($exportType, $fieldName, $fieldData, $rowData, &$customText, &$handled)
        { 
    
        }
    
        protected function doCustomDrawRow($rowData, &$cellFontColor, &$cellFontSize, &$cellBgColor, &$cellItalicAttr, &$cellBoldAttr)
        {
    
        }
    
        protected function doExtendedCustomDrawRow($rowData, &$rowCellStyles, &$rowStyles, &$rowClasses, &$cellClasses)
        {
    
        }
    
        protected function doCustomRenderTotal($totalValue, $aggregate, $columnName, &$customText, &$handled)
        {
    
        }
    
        protected function doCustomDefaultValues(&$values, &$handled) 
        {
    
        }
    
        protected function doCustomCompareColumn($columnName, $valueA, $valueB, &$result)
        {
    
        }
    
        protected function doBeforeInsertRecord($page, &$rowData, $tableName, &$cancel, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doBeforeUpdateRecord($page, $oldRowData, &$rowData, $tableName, &$cancel, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doBeforeDeleteRecord($page, &$rowData, $tableName, &$cancel, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doAfterInsertRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doAfterUpdateRecord($page, $oldRowData, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doAfterDeleteRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doCustomHTMLHeader($page, &$customHtmlHeaderText)
        { 
    
        }
    
        protected function doGetCustomTemplate($type, $part, $mode, &$result, &$params)
        {
    
        }
    
        protected function doGetCustomExportOptions(Page $page, $exportType, $rowData, &$options)
        {
    
        }
    
        protected function doFileUpload($fieldName, $rowData, &$result, &$accept, $originalFileName, $originalFileExtension, $fileSize, $tempFileName)
        {
    
        }
    
        protected function doPrepareChart(Chart $chart)
        {
    
        }
    
        protected function doPrepareColumnFilter(ColumnFilter $columnFilter)
        {
    
        }
    
        protected function doPrepareFilterBuilder(FilterBuilder $filterBuilder, FixedKeysArray $columns)
        {
    
        }
    
        protected function doGetSelectionFilters(FixedKeysArray $columns, &$result)
        {
    
        }
    
        protected function doGetCustomFormLayout($mode, FixedKeysArray $columns, FormLayout $layout)
        {
    
        }
    
        protected function doGetCustomColumnGroup(FixedKeysArray $columns, ViewColumnGroup $columnGroup)
        {
    
        }
    
        protected function doPageLoaded()
        {
    
        }
    
        protected function doCalculateFields($rowData, $fieldName, &$value)
        {
    
        }
    
        protected function doGetCustomPagePermissions(Page $page, PermissionSet &$permissions, &$handled)
        {
    
        }
    
        protected function doGetCustomRecordPermissions(Page $page, &$usingCondition, $rowData, &$allowEdit, &$allowDelete, &$mergeWithDefault, &$handled)
        {
    
        }
    
    }

    SetUpUserAuthorization();

    try
    {
        $Page = new q_last_updated_tablesPage("q_last_updated_tables", "tabellenstand.php", GetCurrentUserPermissionSetForDataSource("q_last_updated_tables"), 'UTF-8');
        $Page->SetTitle('Tabellenstand');
        $Page->SetMenuLabel('<span class="view">Tabellenstand</span>');
        $Page->SetHeader(GetPagesHeader());
        $Page->SetFooter(GetPagesFooter());
        $Page->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource("q_last_updated_tables"));
        GetApplication()->SetMainPage($Page);
        before_render($Page); /*afterburner*/ 
        GetApplication()->Run();
    }
    catch(Exception $e)
    {
        ShowErrorPage($e);
    }
