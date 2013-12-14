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


    include_once dirname(__FILE__) . '/' . 'components/utils/check_utils.php';
    CheckPHPVersion();
    CheckTemplatesCacheFolderIsExistsAndWritable();


    include_once dirname(__FILE__) . '/' . 'phpgen_settings.php';
    include_once dirname(__FILE__) . '/' . 'database_engine/mysql_engine.php';
    include_once dirname(__FILE__) . '/' . 'components/page.php';
    include_once dirname(__FILE__) . '/' . 'authorization.php';

    function GetConnectionOptions()
    {
        $result = GetGlobalConnectionOptions();
        $result['client_encoding'] = 'utf8';
        GetApplication()->GetUserAuthorizationStrategy()->ApplyIdentityToConnectionOptions($result);
        return $result;
    }

    
    
    // OnBeforePageExecute event handler
    
    
    
    class q_last_updated_tablesPage extends Page
    {
        protected function DoBeforeCreate()
        {
            $selectQuery = 'SELECT * FROM (
            SELECT *
            FROM ((SELECT
              \'branche\' table_name,
              \'Branche\' name,
              (select count(*) from `branche`) anzahl_eintraege,
              t.`updated_visa` AS last_visa,
              t.`updated_date` last_updated,
              t.id last_updated_id
              FROM
              `branche` t
              ORDER BY t.`updated_date` DESC
              LIMIT 1
              )
            UNION
            (SELECT
              \'interessenbindung\' table_name,
              \'Interessenbindung\' name,
              (select count(*) from `interessenbindung`) anzahl_eintraege,
              t.`updated_visa` AS last_visa,
              t.`updated_date` last_updated,
              t.id last_updated_id
              FROM
              `interessenbindung` t
              ORDER BY t.`updated_date` DESC
              LIMIT 1
              )
            UNION
            (SELECT
              \'interessengruppe\' table_name,
              \'Interessengruppe\' name,
              (select count(*) from `interessengruppe`) anzahl_eintraege,
              t.`updated_visa` AS last_visa,
              t.`updated_date` last_updated,
              t.id last_updated_id
              FROM
              `interessengruppe` t
              ORDER BY t.`updated_date` DESC
              LIMIT 1
              )
            UNION
            (SELECT
              \'in_kommission\' table_name,
              \'In Kommission\' name,
              (select count(*) from `in_kommission`) anzahl_eintraege,
              t.`updated_visa` AS last_visa,
              t.`updated_date` last_updated,
              t.id last_updated_id
              FROM
              `in_kommission` t
              ORDER BY t.`updated_date` DESC
              LIMIT 1
              )
            UNION
            (SELECT
              \'kommission\' table_name,
              \'Kommission\' name,
              (select count(*) from `kommission`) anzahl_eintraege,
              t.`updated_visa` AS last_visa,
              t.`updated_date` last_updated,
              t.id last_updated_id
              FROM
              `kommission` t
              ORDER BY t.`updated_date` DESC
              LIMIT 1
              )
            UNION
            (SELECT
              \'mandat\' table_name,
              \'Mandat\' name,
              (select count(*) from `mandat`) anzahl_eintraege,
              t.`updated_visa` AS last_visa,
              t.`updated_date` last_updated,
              t.id last_updated_id
              FROM
              `mandat` t
              ORDER BY t.`updated_date` DESC
              LIMIT 1
              )
            UNION
            (SELECT
              \'organisation\' table_name,
              \'Organisation\' name,
              (select count(*) from `organisation`) anzahl_eintraege,
              t.`updated_visa` AS last_visa,
              t.`updated_date` last_updated,
              t.id last_updated_id
              FROM
              `organisation` t
              ORDER BY t.`updated_date` DESC
              LIMIT 1
              )
            UNION
            (SELECT
              \'organisation_beziehung\' table_name,
              \'Organisation Beziehung\' name,
              (select count(*) from `organisation_beziehung`) anzahl_eintraege,
              t.`updated_visa` AS last_visa,
              t.`updated_date` last_updated,
              t.id last_updated_id
              FROM
              `organisation_beziehung` t
              ORDER BY t.`updated_date` DESC
              LIMIT 1
              )
            UNION
            (SELECT
              \'parlamentarier\' table_name,
              \'Parlamentarier\' name,
              (select count(*) from `parlamentarier`) anzahl_eintraege,
              t.`updated_visa` AS last_visa,
              t.`updated_date` last_updated,
              t.id last_updated_id
              FROM
              `parlamentarier` t
              ORDER BY t.`updated_date` DESC
              LIMIT 1
              )
            UNION
            (SELECT
              \'parlamentarier_anhang\' table_name,
              \'Parlamentarieranhang\' name,
              (select count(*) from `parlamentarier_anhang`) anzahl_eintraege,
              t.`updated_visa` AS last_visa,
              t.`updated_date` last_updated,
              t.id last_updated_id
              FROM
              `parlamentarier_anhang` t
              ORDER BY t.`updated_date` DESC
              LIMIT 1
              )
            UNION
            (SELECT
              \'partei\' table_name,
              \'Partei\' name,
              (select count(*) from `partei`) anzahl_eintraege,
              t.`updated_visa` AS last_visa,
              t.`updated_date` last_updated,
              t.id last_updated_id
              FROM
              `partei` t
              ORDER BY t.`updated_date` DESC
              LIMIT 1
              )
            UNION
            (SELECT
              \'zugangsberechtigung\' table_name,
              \'Zugangsberechtigung\' name,
              (select count(*) from `zugangsberechtigung`) anzahl_eintraege,
              t.`updated_visa` AS last_visa,
              t.`updated_date` last_updated,
              t.id last_updated_id
              FROM
              `zugangsberechtigung` t
              ORDER BY t.`updated_date` DESC
              LIMIT 1
              )) union_query
            ) complete
            ORDER BY complete.last_updated DESC';
            $insertQuery = array();
            $updateQuery = array();
            $deleteQuery = array();
            $this->dataset = new QueryDataset(
              new MyPDOConnectionFactory(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'q_last_updated_tables');
            $field = new StringField('table_name');
            $this->dataset->AddField($field, true);
            $field = new StringField('name');
            $this->dataset->AddField($field, false);
            $field = new StringField('anzahl_eintraege');
            $this->dataset->AddField($field, false);
            $field = new StringField('last_visa');
            $this->dataset->AddField($field, false);
            $field = new DateTimeField('last_updated');
            $this->dataset->AddField($field, true);
            $field = new IntegerField('last_updated_id');
            $this->dataset->AddField($field, false);
        }
    
        protected function CreatePageNavigator()
        {
            $result = new CompositePageNavigator($this);
            
            $partitionNavigator = new PageNavigator('pnav', $this, $this->dataset);
            $partitionNavigator->SetRowsPerPage(100);
            $result->AddPageNavigator($partitionNavigator);
            
            return $result;
        }
    
        public function GetPageList()
        {
            $currentPageCaption = $this->GetShortCaption();
            $result = new PageList($this);
            if (GetCurrentUserGrantForDataSource('kommission')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('Kommission'), 'kommission.php', $this->RenderText('Kommission'), $currentPageCaption == $this->RenderText('Kommission')));
            if (GetCurrentUserGrantForDataSource('organisation')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('Organisation'), 'organisation.php', $this->RenderText('Organisation'), $currentPageCaption == $this->RenderText('Organisation')));
            if (GetCurrentUserGrantForDataSource('parlamentarier')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('Parlamentarier'), 'parlamentarier.php', $this->RenderText('Parlamentarier'), $currentPageCaption == $this->RenderText('Parlamentarier')));
            if (GetCurrentUserGrantForDataSource('in_kommission')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('In Kommission'), 'in_kommission.php', $this->RenderText('In Kommission'), $currentPageCaption == $this->RenderText('In Kommission')));
            if (GetCurrentUserGrantForDataSource('interessenbindung')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('Interessenbindung'), 'interessenbindung.php', $this->RenderText('Interessenbindung'), $currentPageCaption == $this->RenderText('Interessenbindung')));
            if (GetCurrentUserGrantForDataSource('zugangsberechtigung')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('Zugangsberechtigung'), 'zugangsberechtigung.php', $this->RenderText('Zugangsberechtigung'), $currentPageCaption == $this->RenderText('Zugangsberechtigung')));
            if (GetCurrentUserGrantForDataSource('mandat')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('Mandat'), 'mandat.php', $this->RenderText('Mandat'), $currentPageCaption == $this->RenderText('Mandat')));
            if (GetCurrentUserGrantForDataSource('organisation_beziehung')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('Organisation Beziehung'), 'organisation_beziehung.php', $this->RenderText('Organisation Beziehung'), $currentPageCaption == $this->RenderText('Organisation Beziehung')));
            if (GetCurrentUserGrantForDataSource('branche')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('Branche'), 'branche.php', $this->RenderText('Branche'), $currentPageCaption == $this->RenderText('Branche')));
            if (GetCurrentUserGrantForDataSource('partei')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('Partei'), 'partei.php', $this->RenderText('Partei'), $currentPageCaption == $this->RenderText('Partei')));
            if (GetCurrentUserGrantForDataSource('interessengruppe')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('Interessengruppe'), 'interessengruppe.php', $this->RenderText('Interessengruppe'), $currentPageCaption == $this->RenderText('Interessengruppe')));
            if (GetCurrentUserGrantForDataSource('q_last_updated_tables')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('Tabellenstand'), 'tabellenstand.php', $this->RenderText('Tabellenstand'), $currentPageCaption == $this->RenderText('Tabellenstand')));
            
            if ( HasAdminPage() && GetApplication()->HasAdminGrantForCurrentUser() )
              $result->AddPage(new PageLink($this->GetLocalizerCaptions()->GetMessageString('AdminPage'), 'phpgen_admin.php', $this->GetLocalizerCaptions()->GetMessageString('AdminPage'), false, true));
            return $result;
        }
    
        protected function CreateRssGenerator() {
            return setupRSS($this, $this->dataset);
        }
    
        protected function CreateGridSearchControl(Grid $grid)
        {
            $grid->UseFilter = true;
            $grid->SearchControl = new SimpleSearch('q_last_updated_tablesssearch', $this->dataset,
                array('name', 'table_name', 'anzahl_eintraege', 'last_updated', 'last_visa', 'last_updated_id'),
                array($this->RenderText('Name'), $this->RenderText('Table Name'), $this->RenderText('Anzahl Eintraege'), $this->RenderText('Last Updated'), $this->RenderText('Last Visa'), $this->RenderText('Last Updated Id')),
                array(
                    '=' => $this->GetLocalizerCaptions()->GetMessageString('equals'),
                    '<>' => $this->GetLocalizerCaptions()->GetMessageString('doesNotEquals'),
                    '<' => $this->GetLocalizerCaptions()->GetMessageString('isLessThan'),
                    '<=' => $this->GetLocalizerCaptions()->GetMessageString('isLessThanOrEqualsTo'),
                    '>' => $this->GetLocalizerCaptions()->GetMessageString('isGreaterThan'),
                    '>=' => $this->GetLocalizerCaptions()->GetMessageString('isGreaterThanOrEqualsTo'),
                    'ILIKE' => $this->GetLocalizerCaptions()->GetMessageString('Like'),
                    'STARTS' => $this->GetLocalizerCaptions()->GetMessageString('StartsWith'),
                    'ENDS' => $this->GetLocalizerCaptions()->GetMessageString('EndsWith'),
                    'CONTAINS' => $this->GetLocalizerCaptions()->GetMessageString('Contains')
                    ), $this->GetLocalizerCaptions(), $this, 'CONTAINS'
                );
        }
    
        protected function CreateGridAdvancedSearchControl(Grid $grid)
        {
            $this->AdvancedSearchControl = new AdvancedSearchControl('q_last_updated_tablesasearch', $this->dataset, $this->GetLocalizerCaptions(), $this->GetColumnVariableContainer(), $this->CreateLinkBuilder());
            $this->AdvancedSearchControl->setTimerInterval(1000);
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('name', $this->RenderText('Name')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('table_name', $this->RenderText('Table Name')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('anzahl_eintraege', $this->RenderText('Anzahl Eintraege')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateDateTimeSearchInput('last_updated', $this->RenderText('Last Updated')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('last_visa', $this->RenderText('Last Visa')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('last_updated_id', $this->RenderText('Last Updated Id')));
        }
    
        protected function AddOperationsColumns(Grid $grid)
        {
    
        }
    
        protected function AddFieldColumns(Grid $grid)
        {
            //
            // View column for name field
            //
            $column = new TextViewColumn('name', 'Name', $this->dataset);
            $column->SetOrderable(true);
            
            /* <inline edit column> */
            //
            // Edit column for name field
            //
            $editor = new TextEdit('name_edit');
            $editColumn = new CustomEditColumn('Name', 'name', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetEditOperationColumn($editColumn);
            /* </inline edit column> */
            
            /* <inline insert column> */
            //
            // Edit column for name field
            //
            $editor = new TextEdit('name_edit');
            $editColumn = new CustomEditColumn('Name', 'name', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetInsertOperationColumn($editColumn);
            /* </inline insert column> */
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, '%table_name%.php?order=dupdated_date' , '');
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for table_name field
            //
            $column = new TextViewColumn('table_name', 'Table Name', $this->dataset);
            $column->SetOrderable(true);
            
            /* <inline edit column> */
            //
            // Edit column for table_name field
            //
            $editor = new TextEdit('table_name_edit');
            $editColumn = new CustomEditColumn('Table Name', 'table_name', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetEditOperationColumn($editColumn);
            /* </inline edit column> */
            
            /* <inline insert column> */
            //
            // Edit column for table_name field
            //
            $editor = new TextEdit('table_name_edit');
            $editColumn = new CustomEditColumn('Table Name', 'table_name', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetInsertOperationColumn($editColumn);
            /* </inline insert column> */
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for anzahl_eintraege field
            //
            $column = new TextViewColumn('anzahl_eintraege', 'Anzahl Eintraege', $this->dataset);
            $column->SetOrderable(true);
            
            /* <inline edit column> */
            //
            // Edit column for anzahl_eintraege field
            //
            $editor = new TextEdit('anzahl_eintraege_edit');
            $editColumn = new CustomEditColumn('Anzahl Eintraege', 'anzahl_eintraege', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetEditOperationColumn($editColumn);
            /* </inline edit column> */
            
            /* <inline insert column> */
            //
            // Edit column for anzahl_eintraege field
            //
            $editor = new TextEdit('anzahl_eintraege_edit');
            $editColumn = new CustomEditColumn('Anzahl Eintraege', 'anzahl_eintraege', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetInsertOperationColumn($editColumn);
            /* </inline insert column> */
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for last_updated field
            //
            $column = new DateTimeViewColumn('last_updated', 'Last Updated', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            
            /* <inline edit column> */
            //
            // Edit column for last_updated field
            //
            $editor = new DateTimeEdit('last_updated_edit', true, 'Y-m-d H:i:s', GetFirstDayOfWeek());
            $editColumn = new CustomEditColumn('Last Updated', 'last_updated', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetEditOperationColumn($editColumn);
            /* </inline edit column> */
            
            /* <inline insert column> */
            //
            // Edit column for last_updated field
            //
            $editor = new DateTimeEdit('last_updated_edit', true, 'Y-m-d H:i:s', GetFirstDayOfWeek());
            $editColumn = new CustomEditColumn('Last Updated', 'last_updated', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetInsertOperationColumn($editColumn);
            /* </inline insert column> */
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for last_visa field
            //
            $column = new TextViewColumn('last_visa', 'Last Visa', $this->dataset);
            $column->SetOrderable(true);
            
            /* <inline edit column> */
            //
            // Edit column for last_visa field
            //
            $editor = new TextEdit('last_visa_edit');
            $editColumn = new CustomEditColumn('Last Visa', 'last_visa', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetEditOperationColumn($editColumn);
            /* </inline edit column> */
            
            /* <inline insert column> */
            //
            // Edit column for last_visa field
            //
            $editor = new TextEdit('last_visa_edit');
            $editColumn = new CustomEditColumn('Last Visa', 'last_visa', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetInsertOperationColumn($editColumn);
            /* </inline insert column> */
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for last_updated_id field
            //
            $column = new TextViewColumn('last_updated_id', 'Last Updated Id', $this->dataset);
            $column->SetOrderable(true);
            
            /* <inline edit column> */
            //
            // Edit column for last_updated_id field
            //
            $editor = new SpinEdit('last_updated_id_edit');
            $editColumn = new CustomEditColumn('Last Updated Id', 'last_updated_id', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetEditOperationColumn($editColumn);
            /* </inline edit column> */
            
            /* <inline insert column> */
            //
            // Edit column for last_updated_id field
            //
            $editor = new SpinEdit('last_updated_id_edit');
            $editColumn = new CustomEditColumn('Last Updated Id', 'last_updated_id', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetInsertOperationColumn($editColumn);
            /* </inline insert column> */
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
        }
    
        protected function AddSingleRecordViewColumns(Grid $grid)
        {
            //
            // View column for name field
            //
            $column = new TextViewColumn('name', 'Name', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, '%table_name%.php?order=dupdated_date' , '');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for table_name field
            //
            $column = new TextViewColumn('table_name', 'Table Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for anzahl_eintraege field
            //
            $column = new TextViewColumn('anzahl_eintraege', 'Anzahl Eintraege', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for last_updated field
            //
            $column = new DateTimeViewColumn('last_updated', 'Last Updated', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for last_visa field
            //
            $column = new TextViewColumn('last_visa', 'Last Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for last_updated_id field
            //
            $column = new TextViewColumn('last_updated_id', 'Last Updated Id', $this->dataset);
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
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for table_name field
            //
            $editor = new TextEdit('table_name_edit');
            $editColumn = new CustomEditColumn('Table Name', 'table_name', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for anzahl_eintraege field
            //
            $editor = new TextEdit('anzahl_eintraege_edit');
            $editColumn = new CustomEditColumn('Anzahl Eintraege', 'anzahl_eintraege', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for last_updated field
            //
            $editor = new DateTimeEdit('last_updated_edit', true, 'Y-m-d H:i:s', GetFirstDayOfWeek());
            $editColumn = new CustomEditColumn('Last Updated', 'last_updated', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for last_visa field
            //
            $editor = new TextEdit('last_visa_edit');
            $editColumn = new CustomEditColumn('Last Visa', 'last_visa', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for last_updated_id field
            //
            $editor = new SpinEdit('last_updated_id_edit');
            $editColumn = new CustomEditColumn('Last Updated Id', 'last_updated_id', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
        }
    
        protected function AddInsertColumns(Grid $grid)
        {
            //
            // Edit column for name field
            //
            $editor = new TextEdit('name_edit');
            $editColumn = new CustomEditColumn('Name', 'name', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for table_name field
            //
            $editor = new TextEdit('table_name_edit');
            $editColumn = new CustomEditColumn('Table Name', 'table_name', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for anzahl_eintraege field
            //
            $editor = new TextEdit('anzahl_eintraege_edit');
            $editColumn = new CustomEditColumn('Anzahl Eintraege', 'anzahl_eintraege', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for last_updated field
            //
            $editor = new DateTimeEdit('last_updated_edit', true, 'Y-m-d H:i:s', GetFirstDayOfWeek());
            $editColumn = new CustomEditColumn('Last Updated', 'last_updated', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for last_visa field
            //
            $editor = new TextEdit('last_visa_edit');
            $editColumn = new CustomEditColumn('Last Visa', 'last_visa', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for last_updated_id field
            //
            $editor = new SpinEdit('last_updated_id_edit');
            $editColumn = new CustomEditColumn('Last Updated Id', 'last_updated_id', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            if ($this->GetSecurityInfo()->HasAddGrant())
            {
                $grid->SetShowAddButton(false);
                $grid->SetShowInlineAddButton(false);
            }
            else
            {
                $grid->SetShowInlineAddButton(false);
                $grid->SetShowAddButton(false);
            }
        }
    
        protected function AddPrintColumns(Grid $grid)
        {
            //
            // View column for name field
            //
            $column = new TextViewColumn('name', 'Name', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, '%table_name%.php?order=dupdated_date' , '');
            $grid->AddPrintColumn($column);
            
            //
            // View column for table_name field
            //
            $column = new TextViewColumn('table_name', 'Table Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for anzahl_eintraege field
            //
            $column = new TextViewColumn('anzahl_eintraege', 'Anzahl Eintraege', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for last_updated field
            //
            $column = new DateTimeViewColumn('last_updated', 'Last Updated', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for last_visa field
            //
            $column = new TextViewColumn('last_visa', 'Last Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for last_updated_id field
            //
            $column = new TextViewColumn('last_updated_id', 'Last Updated Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
        }
    
        protected function AddExportColumns(Grid $grid)
        {
            //
            // View column for name field
            //
            $column = new TextViewColumn('name', 'Name', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, '%table_name%.php?order=dupdated_date' , '');
            $grid->AddExportColumn($column);
            
            //
            // View column for table_name field
            //
            $column = new TextViewColumn('table_name', 'Table Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for anzahl_eintraege field
            //
            $column = new TextViewColumn('anzahl_eintraege', 'Anzahl Eintraege', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for last_updated field
            //
            $column = new DateTimeViewColumn('last_updated', 'Last Updated', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for last_visa field
            //
            $column = new TextViewColumn('last_visa', 'Last Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for last_updated_id field
            //
            $column = new TextViewColumn('last_updated_id', 'Last Updated Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
        }
    
        public function GetPageDirection()
        {
            return null;
        }
    
        protected function ApplyCommonColumnEditProperties(CustomEditColumn $column)
        {
            $column->SetShowSetToNullCheckBox(true);
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
            $result = new Grid($this, $this->dataset, 'q_last_updated_tablesGrid');
            if ($this->GetSecurityInfo()->HasDeleteGrant())
               $result->SetAllowDeleteSelected(false);
            else
               $result->SetAllowDeleteSelected(false);   
            
            ApplyCommonPageSettings($this, $result);
            
            $result->SetUseImagesForActions(true);
            $result->SetUseFixedHeader(false);
            
            $result->SetShowLineNumbers(true);
            
            $result->SetHighlightRowAtHover(true);
            $result->SetWidth('');
            $this->CreateGridSearchControl($result);
            $this->CreateGridAdvancedSearchControl($result);
            $this->AddOperationsColumns($result);
            $this->AddFieldColumns($result);
            $this->AddSingleRecordViewColumns($result);
            $this->AddEditColumns($result);
            $this->AddInsertColumns($result);
            $this->AddPrintColumns($result);
            $this->AddExportColumns($result);
    
            $this->SetShowPageList(true);
            $this->SetHidePageListByDefault(false);
            $this->SetExportToExcelAvailable(true);
            $this->SetExportToWordAvailable(true);
            $this->SetExportToXmlAvailable(true);
            $this->SetExportToCsvAvailable(true);
            $this->SetExportToPdfAvailable(false);
            $this->SetPrinterFriendlyAvailable(true);
            $this->SetSimpleSearchAvailable(true);
            $this->SetAdvancedSearchAvailable(true);
            $this->SetFilterRowAvailable(true);
            $this->SetVisualEffectsEnabled(true);
            $this->SetShowTopPageNavigator(true);
            $this->SetShowBottomPageNavigator(true);
    
            //
            // Http Handlers
            //
    
            return $result;
        }
        
        public function OpenAdvancedSearchByDefault()
        {
            return false;
        }
    
        protected function DoGetGridHeader()
        {
            return 'Zeigt die letzten Änderungen der Tabellen an.';
        }
    }

    SetUpUserAuthorization(GetApplication());

    try
    {
        $Page = new q_last_updated_tablesPage("tabellenstand.php", "q_last_updated_tables", GetCurrentUserGrantForDataSource("q_last_updated_tables"), 'UTF-8');
        $Page->SetShortCaption('Tabellenstand');
        $Page->SetHeader(GetPagesHeader());
        $Page->SetFooter(GetPagesFooter());
        $Page->SetCaption('Tabellenstand');
        $Page->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource("q_last_updated_tables"));
        GetApplication()->SetEnableLessRunTimeCompile(GetEnableLessFilesRunTimeCompilation());
        GetApplication()->SetCanUserChangeOwnPassword(
            !function_exists('CanUserChangeOwnPassword') || CanUserChangeOwnPassword());
        GetApplication()->SetMainPage($Page);
        before_render($Page);
        GetApplication()->Run();
    }
    catch(Exception $e)
    {
        ShowErrorPage($e->getMessage());
    }
