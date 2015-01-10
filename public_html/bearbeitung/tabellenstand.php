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

    
    // OnGlobalBeforePageExecute event handler
    
    
    // OnBeforePageExecute event handler
    
    
    
    class q_last_updated_tablesPage extends Page
    {
        protected function DoBeforeCreate()
        {
            $selectQuery = 'SELECT tn as table_name, n as name, ne as anzahl_eintraege, lv as last_visa, ld as last_updated, lid as last_updated_id
            FROM (SELECT * FROM (
            (SELECT \'branche\' tn, \'Branche\' n, (select count(*) from `branche`) ne, t.`updated_visa` AS lv, t.`updated_date` ld, t.id lid FROM  `branche` t ORDER BY t.`updated_date` DESC LIMIT 1)
            UNION (SELECT \'interessenbindung\' tn, \'Interessenbindung\' n, (select count(*) from `interessenbindung`) ne, t.`updated_visa` AS lv, t.`updated_date` ld, t.id lid FROM  `interessenbindung` t ORDER BY t.`updated_date` DESC LIMIT 1)
            UNION (SELECT \'interessengruppe\' tn, \'Lobbygruppe\' n, (select count(*) from `interessengruppe`) ne, t.`updated_visa` AS lv, t.`updated_date` ld, t.id lid FROM  `interessengruppe` t ORDER BY t.`updated_date` DESC LIMIT 1)
            UNION (SELECT \'in_kommission\' tn, \'In Kommission\' n, (select count(*) from `in_kommission`) ne, t.`updated_visa` AS lv, t.`updated_date` ld, t.id lid FROM  `in_kommission` t ORDER BY t.`updated_date` DESC LIMIT 1)
            UNION (SELECT \'kommission\' tn, \'Kommission\' n, (select count(*) from `kommission`) ne, t.`updated_visa` AS lv, t.`updated_date` ld, t.id lid FROM  `kommission` t ORDER BY t.`updated_date` DESC LIMIT 1)
            UNION (SELECT \'mandat\' tn, \'Mandat\' n, (select count(*) from `mandat`) ne, t.`updated_visa` AS lv, t.`updated_date` ld, t.id lid FROM  `mandat` t ORDER BY t.`updated_date` DESC LIMIT 1)
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
            $this->dataset->AddField($field, false);
            $field = new IntegerField('last_updated_id');
            $this->dataset->AddField($field, false);
        }
    
        protected function DoPrepare() {
    
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
            $result->AddGroup($this->RenderText('Subjektdaten'));
            $result->AddGroup($this->RenderText('Verbindungen'));
            $result->AddGroup($this->RenderText('Stammdaten'));
            $result->AddGroup($this->RenderText('Metadaten'));
            $result->AddGroup($this->RenderText('Misc'));
            if (GetCurrentUserGrantForDataSource('organisation')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('<span class="entity important-entity">Organisation</span>'), 'organisation.php', $this->RenderText('Organisation'), $currentPageCaption == $this->RenderText('<span class="entity important-entity">Organisation</span>'), false, $this->RenderText('Subjektdaten')));
            if (GetCurrentUserGrantForDataSource('parlamentarier')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('<span class="entity important-entity">Parlamentarier</span>'), 'parlamentarier.php', $this->RenderText('Parlamentarier'), $currentPageCaption == $this->RenderText('<span class="entity important-entity">Parlamentarier</span>'), false, $this->RenderText('Subjektdaten')));
            if (GetCurrentUserGrantForDataSource('person')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('<span class="entity important-entity">Person</span>'), 'person.php', $this->RenderText('Person'), $currentPageCaption == $this->RenderText('<span class="entity important-entity">Person</span>'), false, $this->RenderText('Subjektdaten')));
            if (GetCurrentUserGrantForDataSource('interessenbindung')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('<span class="relation" title="Interessenbindungen der Parlamentarier">Intereressenbind. von NR/SR</span>'), 'interessenbindung.php', $this->RenderText('Interessenbindung'), $currentPageCaption == $this->RenderText('<span class="relation" title="Interessenbindungen der Parlamentarier">Intereressenbind. von NR/SR</span>'), false, $this->RenderText('Verbindungen')));
            if (GetCurrentUserGrantForDataSource('zutrittsberechtigung')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('<span class="relation" title="Zutrittsberechtigungen für Gäse ins Bundeshaus">Zutrittsberechtigung</span>'), 'zutrittsberechtigung.php', $this->RenderText('Zutrittsberechtigung'), $currentPageCaption == $this->RenderText('<span class="relation" title="Zutrittsberechtigungen für Gäse ins Bundeshaus">Zutrittsberechtigung</span>'), false, $this->RenderText('Verbindungen')));
            if (GetCurrentUserGrantForDataSource('mandat')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('<span class="relation" title="Mandate der Zutrittsberechtigten">Mandate von Pers.</span>'), 'mandat.php', $this->RenderText('Mandat'), $currentPageCaption == $this->RenderText('<span class="relation" title="Mandate der Zutrittsberechtigten">Mandate von Pers.</span>'), false, $this->RenderText('Verbindungen')));
            if (GetCurrentUserGrantForDataSource('in_kommission')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('<span class="relation">In Kommission</span>'), 'in_kommission.php', $this->RenderText('In Kommission'), $currentPageCaption == $this->RenderText('<span class="relation">In Kommission</span>'), false, $this->RenderText('Verbindungen')));
            if (GetCurrentUserGrantForDataSource('organisation_beziehung')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('<span class="relation">Organisation Beziehung</span>'), 'organisation_beziehung.php', $this->RenderText('Organisation Beziehung'), $currentPageCaption == $this->RenderText('<span class="relation">Organisation Beziehung</span>'), false, $this->RenderText('Verbindungen')));
            if (GetCurrentUserGrantForDataSource('branche')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('<span class="entity">Branche</span>'), 'branche.php', $this->RenderText('Branche'), $currentPageCaption == $this->RenderText('<span class="entity">Branche</span>'), false, $this->RenderText('Stammdaten')));
            if (GetCurrentUserGrantForDataSource('interessengruppe')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('<span class="entity">Lobbygruppe</span>'), 'interessengruppe.php', $this->RenderText('Lobbygruppe'), $currentPageCaption == $this->RenderText('<span class="entity">Lobbygruppe</span>'), false, $this->RenderText('Stammdaten')));
            if (GetCurrentUserGrantForDataSource('kommission')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('<span class="entity">Kommission</span>'), 'kommission.php', $this->RenderText('Kommission'), $currentPageCaption == $this->RenderText('<span class="entity">Kommission</span>'), false, $this->RenderText('Stammdaten')));
            if (GetCurrentUserGrantForDataSource('partei')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('<span class="entity">Partei</span>'), 'partei.php', $this->RenderText('Partei'), $currentPageCaption == $this->RenderText('<span class="entity">Partei</span>'), false, $this->RenderText('Stammdaten')));
            if (GetCurrentUserGrantForDataSource('fraktion')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('<span class="entity">Fraktion</span>'), 'fraktion.php', $this->RenderText('Fraktion'), $currentPageCaption == $this->RenderText('<span class="entity">Fraktion</span>'), false, $this->RenderText('Stammdaten')));
            if (GetCurrentUserGrantForDataSource('kanton')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('<span class="entity">Kanton</span>'), 'kanton.php', $this->RenderText('Kanton'), $currentPageCaption == $this->RenderText('<span class="entity">Kanton</span>'), false, $this->RenderText('Stammdaten')));
            if (GetCurrentUserGrantForDataSource('settings')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('<span class="settings">Settings</span>'), 'settings.php', $this->RenderText('Settings'), $currentPageCaption == $this->RenderText('<span class="settings">Settings</span>'), false, $this->RenderText('Metadaten')));
            if (GetCurrentUserGrantForDataSource('settings_category')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('<span class="settings">Settings Category</span>'), 'settings_category.php', $this->RenderText('Settings Category'), $currentPageCaption == $this->RenderText('<span class="settings">Settings Category</span>'), false, $this->RenderText('Metadaten')));
            if (GetCurrentUserGrantForDataSource('translation_source')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('<span class="settings">Translation Source</span>'), 'translation_source.php', $this->RenderText('Translation Source'), $currentPageCaption == $this->RenderText('<span class="settings">Translation Source</span>'), false, $this->RenderText('Metadaten')));
            if (GetCurrentUserGrantForDataSource('translation_target')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('<span class="settings">Translation Target</span>'), 'translation_target.php', $this->RenderText('Translation Target'), $currentPageCaption == $this->RenderText('<span class="settings">Translation Target</span>'), false, $this->RenderText('Metadaten')));
            if (GetCurrentUserGrantForDataSource('user')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('<span class="settings">User</span>'), 'user.php', $this->RenderText('User'), $currentPageCaption == $this->RenderText('<span class="settings">User</span>'), false, $this->RenderText('Metadaten')));
            if (GetCurrentUserGrantForDataSource('q_unvollstaendige_parlamentarier')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('<span class="view">Unvollständige Parlamentarier</span>'), 'q_unvollstaendige_parlamentarier.php', $this->RenderText('Unvollständige Parlamentarier'), $currentPageCaption == $this->RenderText('<span class="view">Unvollständige Parlamentarier</span>'), false, $this->RenderText('Misc')));
            if (GetCurrentUserGrantForDataSource('q_unvollstaendige_zutrittsberechtigte')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('<span class="view">Unvollständige Zutrittsberechtigte</span>'), 'q_unvollstaendige_zutrittsberechtigte.php', $this->RenderText('Unvollständige Zutrittsberechtigte'), $currentPageCaption == $this->RenderText('<span class="view">Unvollständige Zutrittsberechtigte</span>'), false, $this->RenderText('Misc')));
            if (GetCurrentUserGrantForDataSource('q_unvollstaendige_organisationen')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('<span class="view">Unvollständige Organisationen</span>'), 'q_unvollstaendige_organisationen.php', $this->RenderText('Unvollständige Organisationen'), $currentPageCaption == $this->RenderText('<span class="view">Unvollständige Organisationen</span>'), false, $this->RenderText('Misc')));
            if (GetCurrentUserGrantForDataSource('q_last_updated_tables')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('<span class="view">Tabellenstand</span>'), 'tabellenstand.php', $this->RenderText('Tabellenstand'), $currentPageCaption == $this->RenderText('<span class="view">Tabellenstand</span>'), false, $this->RenderText('Misc')));
            
            if ( HasAdminPage() && GetApplication()->HasAdminGrantForCurrentUser() ) {
              $result->AddGroup('Admin area');
              $result->AddPage(new PageLink($this->GetLocalizerCaptions()->GetMessageString('AdminPage'), 'phpgen_admin.php', $this->GetLocalizerCaptions()->GetMessageString('AdminPage'), false, false, 'Admin area'));
              }

            add_more_navigation_links($result); // Afterburned
              {
            }
            return $result;
        }
    
        protected function CreateRssGenerator() {
            return setupRSS($this, $this->dataset); /*afterburner*/ 
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
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateDateTimeSearchInput('last_updated', $this->RenderText('Last Updated'), 'd.m.Y H:i:s'));
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
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, '%table_name%.php?order=dupdated_date' , '');
            $column->SetDescription($this->RenderText('Name der Tabelle'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for table_name field
            //
            $column = new TextViewColumn('table_name', 'Table Name', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Technischer Tabellenname in der Datenbank'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for anzahl_eintraege field
            //
            $column = new TextViewColumn('anzahl_eintraege', 'Anzahl Eintraege', $this->dataset);
            $grid->SetTotal($column, PredefinedAggregate::$Sum);
            $column->SetOrderable(true);
            $column = new NumberFormatValueViewColumnDecorator($column, 0, '\'', '.');
            $column->SetDescription($this->RenderText('Anzahl Einträge in der Tabelle'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for last_updated field
            //
            $column = new DateTimeViewColumn('last_updated', 'Last Updated', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Zuletzt abgeändert am'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for last_visa field
            //
            $column = new TextViewColumn('last_visa', 'Last Visa', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Zuletzt abgeändert von'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for last_updated_id field
            //
            $column = new TextViewColumn('last_updated_id', 'Last Updated Id', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('ID des zuletzt abgeänderten Eintrages'));
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
            $column = new NumberFormatValueViewColumnDecorator($column, 0, '\'', '.');
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
            $editor = new TextEdit('last_updated_id_edit');
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
            $editor = new TextEdit('last_updated_id_edit');
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
            $column = new NumberFormatValueViewColumnDecorator($column, 0, '\'', '.');
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
            $column = new NumberFormatValueViewColumnDecorator($column, 0, '\'', '.');
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
            $column->SetDisplaySetToNullCheckBox(true);
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
        public function q_last_updated_tablesGrid_OnGetCustomTemplate($part, $mode, &$result, &$params)
        {
        if ($part == PagePart::VerticalGrid && $mode == PageMode::Edit) {
          $result = 'edit/grid.tpl';
        } else if ($part == PagePart::VerticalGrid && $mode == PageMode::Insert) {
          $result = 'insert/grid.tpl';
        } else if ($part == PagePart::RecordCard && $mode == PageMode::View) {
          $result = 'view/grid.tpl';
        } else if ($part == PagePart::Grid && $mode == PageMode::ViewAll) {
          $result = 'list/grid.tpl';
        } else if ($part == PagePart::PageList) {
          $result = 'page_list.tpl';
        }
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
            $result->SetDefaultOrdering('last_updated', otDescending);
            $result->SetUseFixedHeader(false);
            $result->SetShowLineNumbers(true);
            
            $result->SetHighlightRowAtHover(true);
            $result->SetWidth('');
            $this->OnGetCustomTemplate->AddListener('q_last_updated_tablesGrid' . '_OnGetCustomTemplate', $this);
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
            return '' . $GLOBALS["edit_header_message"] /*afterburner*/  . '
    
    <div class="wiki-table-help">
    <p>Zeigt die letzten Änderungen der Tabellen an.
    </p>
    </div>
    
    ' . $GLOBALS["edit_general_hint"] /*afterburner*/  . '';
        }
    }

    SetUpUserAuthorization(GetApplication());

    try
    {
        $Page = new q_last_updated_tablesPage("tabellenstand.php", "q_last_updated_tables", GetCurrentUserGrantForDataSource("q_last_updated_tables"), 'UTF-8');
        $Page->SetShortCaption('<span class="view">Tabellenstand</span>');
        $Page->SetHeader(GetPagesHeader());
        $Page->SetFooter(GetPagesFooter());
        $Page->SetCaption('Tabellenstand');
        $Page->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource("q_last_updated_tables"));
        GetApplication()->SetEnableLessRunTimeCompile(GetEnableLessFilesRunTimeCompilation());
        GetApplication()->SetCanUserChangeOwnPassword(
            !function_exists('CanUserChangeOwnPassword') || CanUserChangeOwnPassword());
        GetApplication()->SetMainPage($Page);
        before_render($Page); /*afterburner*/ 
        GetApplication()->Run();
    }
    catch(Exception $e)
    {
        ShowErrorPage($e->getMessage());
    }
