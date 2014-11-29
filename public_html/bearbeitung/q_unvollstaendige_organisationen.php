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
    
    
    
    class q_unvollstaendige_organisationenPage extends Page
    {
        protected function DoBeforeCreate()
        {
            $selectQuery = 'select * from organisation where
            rechtsform is null OR rechtsform = \'\' OR
            interessengruppe_id = null OR
            branche_id = null';
            $insertQuery = array();
            $updateQuery = array();
            $deleteQuery = array();
            $this->dataset = new QueryDataset(
              new MyPDOConnectionFactory(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'q_unvollstaendige_organisationen');
            $field = new IntegerField('id');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, true);
            $field = new StringField('name_de');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('name_fr');
            $this->dataset->AddField($field, false);
            $field = new StringField('name_it');
            $this->dataset->AddField($field, false);
            $field = new StringField('uid');
            $this->dataset->AddField($field, false);
            $field = new StringField('ort');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('land_id');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('interessenraum_id');
            $this->dataset->AddField($field, false);
            $field = new StringField('rechtsform');
            $this->dataset->AddField($field, false);
            $field = new StringField('typ');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('vernehmlassung');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new IntegerField('interessengruppe_id');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('interessengruppe2_id');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('interessengruppe3_id');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('branche_id');
            $this->dataset->AddField($field, false);
            $field = new StringField('homepage');
            $this->dataset->AddField($field, false);
            $field = new StringField('handelsregister_url');
            $this->dataset->AddField($field, false);
            $field = new StringField('twitter_name');
            $this->dataset->AddField($field, false);
            $field = new StringField('beschreibung');
            $this->dataset->AddField($field, false);
            $field = new StringField('adresse_strasse');
            $this->dataset->AddField($field, false);
            $field = new StringField('adresse_zusatz');
            $this->dataset->AddField($field, false);
            $field = new StringField('adresse_plz');
            $this->dataset->AddField($field, false);
            $field = new StringField('notizen');
            $this->dataset->AddField($field, false);
            $field = new StringField('eingabe_abgeschlossen_visa');
            $this->dataset->AddField($field, false);
            $field = new DateTimeField('eingabe_abgeschlossen_datum');
            $this->dataset->AddField($field, false);
            $field = new StringField('kontrolliert_visa');
            $this->dataset->AddField($field, false);
            $field = new DateTimeField('kontrolliert_datum');
            $this->dataset->AddField($field, false);
            $field = new StringField('freigabe_visa');
            $this->dataset->AddField($field, false);
            $field = new DateTimeField('freigabe_datum');
            $this->dataset->AddField($field, false);
            $field = new StringField('created_visa');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new DateTimeField('created_date');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('updated_visa');
            $this->dataset->AddField($field, false);
            $field = new DateTimeField('updated_date');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $this->dataset->AddLookupField('interessengruppe_id', 'v_interessengruppe_simple', new IntegerField('id'), new StringField('name', 'interessengruppe_id_name', 'interessengruppe_id_name_v_interessengruppe_simple'), 'interessengruppe_id_name_v_interessengruppe_simple');
            $this->dataset->AddLookupField('branche_id', 'v_branche_simple', new IntegerField('id'), new StringField('name', 'branche_id_name', 'branche_id_name_v_branche_simple'), 'branche_id_name_v_branche_simple');
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
            $result->AddGroup('Default');
            if (GetCurrentUserGrantForDataSource('organisation')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('<span class="entity important-entity">Organisation</span>'), 'organisation.php', $this->RenderText('Organisation'), $currentPageCaption == $this->RenderText('<span class="entity important-entity">Organisation</span>'), false, 'Default'));
            if (GetCurrentUserGrantForDataSource('parlamentarier')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('<span class="entity important-entity">Parlamentarier</span>'), 'parlamentarier.php', $this->RenderText('Parlamentarier'), $currentPageCaption == $this->RenderText('<span class="entity important-entity">Parlamentarier</span>'), false, 'Default'));
            if (GetCurrentUserGrantForDataSource('zutrittsberechtigung')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('<span class="entity">Zutrittsberechtigter</span>'), 'zutrittsberechtigung.php', $this->RenderText('Zutrittsberechtigter'), $currentPageCaption == $this->RenderText('<span class="entity">Zutrittsberechtigter</span>'), false, 'Default'));
            if (GetCurrentUserGrantForDataSource('interessenbindung')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('<span class="relation" title="Interessenbindungen der Parlamentarier">Intereressenbind. (von NR/SR)</span>'), 'interessenbindung.php', $this->RenderText('Interessenbindung'), $currentPageCaption == $this->RenderText('<span class="relation" title="Interessenbindungen der Parlamentarier">Intereressenbind. (von NR/SR)</span>'), false, 'Default'));
            if (GetCurrentUserGrantForDataSource('mandat')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('<span class="relation" title="Mandate der Zutrittsberechtigten">Mandate (von Zutrittsber.)</span>'), 'mandat.php', $this->RenderText('Mandat'), $currentPageCaption == $this->RenderText('<span class="relation" title="Mandate der Zutrittsberechtigten">Mandate (von Zutrittsber.)</span>'), false, 'Default'));
            if (GetCurrentUserGrantForDataSource('in_kommission')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('<span class="relation">In Kommission</span>'), 'in_kommission.php', $this->RenderText('In Kommission'), $currentPageCaption == $this->RenderText('<span class="relation">In Kommission</span>'), false, 'Default'));
            if (GetCurrentUserGrantForDataSource('organisation_beziehung')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('<span class="relation">Organisation Beziehung</span>'), 'organisation_beziehung.php', $this->RenderText('Organisation Beziehung'), $currentPageCaption == $this->RenderText('<span class="relation">Organisation Beziehung</span>'), false, 'Default'));
            if (GetCurrentUserGrantForDataSource('branche')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('<span class="entity">Branche</span>'), 'branche.php', $this->RenderText('Branche'), $currentPageCaption == $this->RenderText('<span class="entity">Branche</span>'), false, 'Default'));
            if (GetCurrentUserGrantForDataSource('interessengruppe')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('<span class="entity">Lobbygruppe</span>'), 'interessengruppe.php', $this->RenderText('Lobbygruppe'), $currentPageCaption == $this->RenderText('<span class="entity">Lobbygruppe</span>'), false, 'Default'));
            if (GetCurrentUserGrantForDataSource('kommission')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('<span class="entity">Kommission</span>'), 'kommission.php', $this->RenderText('Kommission'), $currentPageCaption == $this->RenderText('<span class="entity">Kommission</span>'), false, 'Default'));
            if (GetCurrentUserGrantForDataSource('partei')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('<span class="entity">Partei</span>'), 'partei.php', $this->RenderText('Partei'), $currentPageCaption == $this->RenderText('<span class="entity">Partei</span>'), false, 'Default'));
            if (GetCurrentUserGrantForDataSource('fraktion')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('<span class="entity">Fraktion</span>'), 'fraktion.php', $this->RenderText('Fraktion'), $currentPageCaption == $this->RenderText('<span class="entity">Fraktion</span>'), false, 'Default'));
            if (GetCurrentUserGrantForDataSource('kanton')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('<span class="entity">Kanton</span>'), 'kanton.php', $this->RenderText('Kanton'), $currentPageCaption == $this->RenderText('<span class="entity">Kanton</span>'), false, 'Default'));
            if (GetCurrentUserGrantForDataSource('settings')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('<span class="settings">Settings</span>'), 'settings.php', $this->RenderText('Settings'), $currentPageCaption == $this->RenderText('<span class="settings">Settings</span>'), false, 'Default'));
            if (GetCurrentUserGrantForDataSource('settings_category')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('<span class="settings">Settings Category</span>'), 'settings_category.php', $this->RenderText('Settings Category'), $currentPageCaption == $this->RenderText('<span class="settings">Settings Category</span>'), false, 'Default'));
            if (GetCurrentUserGrantForDataSource('user')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('<span class="settings">User</span>'), 'user.php', $this->RenderText('User'), $currentPageCaption == $this->RenderText('<span class="settings">User</span>'), false, 'Default'));
            if (GetCurrentUserGrantForDataSource('q_unvollstaendige_parlamentarier')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('<span class="view">Unvollständige Parlamentarier</span>'), 'q_unvollstaendige_parlamentarier.php', $this->RenderText('Unvollständige Parlamentarier'), $currentPageCaption == $this->RenderText('<span class="view">Unvollständige Parlamentarier</span>'), false, 'Default'));
            if (GetCurrentUserGrantForDataSource('q_unvollstaendige_zutrittsberechtigte')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('<span class="view">Unvollständige Zutrittsberechtigte</span>'), 'q_unvollstaendige_zutrittsberechtigte.php', $this->RenderText('Unvollständige Zutrittsberechtigte'), $currentPageCaption == $this->RenderText('<span class="view">Unvollständige Zutrittsberechtigte</span>'), false, 'Default'));
            if (GetCurrentUserGrantForDataSource('q_unvollstaendige_organisationen')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('<span class="view">Unvollständige Organisationen</span>'), 'q_unvollstaendige_organisationen.php', $this->RenderText('Unvollständige Organisationen'), $currentPageCaption == $this->RenderText('<span class="view">Unvollständige Organisationen</span>'), false, 'Default'));
            if (GetCurrentUserGrantForDataSource('q_last_updated_tables')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('<span class="view">Tabellenstand</span>'), 'tabellenstand.php', $this->RenderText('Tabellenstand'), $currentPageCaption == $this->RenderText('<span class="view">Tabellenstand</span>'), false, 'Default'));
            
            if ( HasAdminPage() && GetApplication()->HasAdminGrantForCurrentUser() ) {
              $result->AddGroup('Admin area');
              $result->AddPage(new PageLink($this->GetLocalizerCaptions()->GetMessageString('AdminPage'), 'phpgen_admin.php', $this->GetLocalizerCaptions()->GetMessageString('AdminPage'), false, false, 'Admin area'));
            }
            return $result;
        }
    
        protected function CreateRssGenerator() {
            return setupRSS($this, $this->dataset); /*afterburner*/ 
        }
    
        protected function CreateGridSearchControl(Grid $grid)
        {
            $grid->UseFilter = true;
            $grid->SearchControl = new SimpleSearch('q_unvollstaendige_organisationenssearch', $this->dataset,
                array('id', 'name_de', 'name_fr', 'name_it', 'ort', 'rechtsform', 'vernehmlassung', 'interessengruppe_id_name', 'branche_id_name', 'homepage', 'created_visa', 'created_date', 'updated_visa', 'updated_date'),
                array($this->RenderText('Id'), $this->RenderText('Name De'), $this->RenderText('Name Fr'), $this->RenderText('Name It'), $this->RenderText('Ort'), $this->RenderText('Rechtsform'), $this->RenderText('Vernehmlassung'), $this->RenderText('Interessengruppe'), $this->RenderText('Branche'), $this->RenderText('Homepage'), $this->RenderText('Created Visa'), $this->RenderText('Created Date'), $this->RenderText('Updated Visa'), $this->RenderText('Updated Date')),
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
            $this->AdvancedSearchControl = new AdvancedSearchControl('q_unvollstaendige_organisationenasearch', $this->dataset, $this->GetLocalizerCaptions(), $this->GetColumnVariableContainer(), $this->CreateLinkBuilder());
            $this->AdvancedSearchControl->setTimerInterval(1000);
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('id', $this->RenderText('Id')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('name_de', $this->RenderText('Name De')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('name_fr', $this->RenderText('Name Fr')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('name_it', $this->RenderText('Name It')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('ort', $this->RenderText('Ort')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('rechtsform', $this->RenderText('Rechtsform')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('vernehmlassung', $this->RenderText('Vernehmlassung')));
            
            $lookupDataset = new TableDataset(
                new MyPDOConnectionFactory(),
                GetConnectionOptions(),
                '`v_interessengruppe_simple`');
            $field = new StringField('anzeige_name');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('id');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('name');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('name_fr');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('branche_id');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('alias_namen');
            $lookupDataset->AddField($field, false);
            $field = new StringField('alias_namen_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('notizen');
            $lookupDataset->AddField($field, false);
            $field = new StringField('eingabe_abgeschlossen_visa');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('eingabe_abgeschlossen_datum');
            $lookupDataset->AddField($field, false);
            $field = new StringField('kontrolliert_visa');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('kontrolliert_datum');
            $lookupDataset->AddField($field, false);
            $field = new StringField('freigabe_visa');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('freigabe_datum');
            $lookupDataset->AddField($field, false);
            $field = new StringField('created_visa');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('created_date');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('updated_visa');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('updated_date');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('created_date_unix');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('updated_date_unix');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('eingabe_abgeschlossen_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('kontrolliert_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('freigabe_datum_unix');
            $lookupDataset->AddField($field, false);
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateLookupSearchInput('interessengruppe_id', $this->RenderText('Interessengruppe'), $lookupDataset, 'id', 'name', false));
            
            $lookupDataset = new TableDataset(
                new MyPDOConnectionFactory(),
                GetConnectionOptions(),
                '`v_branche_simple`');
            $field = new StringField('anzeige_name');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('id');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('name');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('name_fr');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('kommission_id');
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('angaben');
            $lookupDataset->AddField($field, false);
            $field = new StringField('angaben_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('farbcode');
            $lookupDataset->AddField($field, false);
            $field = new StringField('symbol_abs');
            $lookupDataset->AddField($field, false);
            $field = new StringField('symbol_rel');
            $lookupDataset->AddField($field, false);
            $field = new StringField('symbol_klein_rel');
            $lookupDataset->AddField($field, false);
            $field = new StringField('symbol_dateiname_wo_ext');
            $lookupDataset->AddField($field, false);
            $field = new StringField('symbol_dateierweiterung');
            $lookupDataset->AddField($field, false);
            $field = new StringField('symbol_dateiname');
            $lookupDataset->AddField($field, false);
            $field = new StringField('symbol_mime_type');
            $lookupDataset->AddField($field, false);
            $field = new StringField('notizen');
            $lookupDataset->AddField($field, false);
            $field = new StringField('eingabe_abgeschlossen_visa');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('eingabe_abgeschlossen_datum');
            $lookupDataset->AddField($field, false);
            $field = new StringField('kontrolliert_visa');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('kontrolliert_datum');
            $lookupDataset->AddField($field, false);
            $field = new StringField('freigabe_visa');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('freigabe_datum');
            $lookupDataset->AddField($field, false);
            $field = new StringField('created_visa');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('created_date');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('updated_visa');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('updated_date');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('created_date_unix');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('updated_date_unix');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('eingabe_abgeschlossen_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('kontrolliert_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('freigabe_datum_unix');
            $lookupDataset->AddField($field, false);
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateLookupSearchInput('branche_id', $this->RenderText('Branche'), $lookupDataset, 'id', 'name', false));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('homepage', $this->RenderText('Homepage')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('created_visa', $this->RenderText('Created Visa')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateDateTimeSearchInput('created_date', $this->RenderText('Created Date')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('updated_visa', $this->RenderText('Updated Visa')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateDateTimeSearchInput('updated_date', $this->RenderText('Updated Date')));
        }
    
        protected function AddOperationsColumns(Grid $grid)
        {
    
        }
    
        protected function AddFieldColumns(Grid $grid)
        {
            //
            // View column for id field
            //
            $column = new TextViewColumn('id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for name_de field
            //
            $column = new TextViewColumn('name_de', 'Name De', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'organisation.php?operation=edit&amp;pk0=%id%&amp;t=0' , '_self');
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for name_fr field
            //
            $column = new TextViewColumn('name_fr', 'Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for name_it field
            //
            $column = new TextViewColumn('name_it', 'Name It', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for ort field
            //
            $column = new TextViewColumn('ort', 'Ort', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for rechtsform field
            //
            $column = new TextViewColumn('rechtsform', 'Rechtsform', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for vernehmlassung field
            //
            $column = new TextViewColumn('vernehmlassung', 'Vernehmlassung', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('interessengruppe_id_name', 'Interessengruppe', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'interessengruppe.php?operation=edit&pk0=%interessengruppe_id%' , '_self');
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('branche_id_name', 'Branche', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'branche.php?operation=edit&pk0=%branche_id%' , '_self');
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for homepage field
            //
            $column = new TextViewColumn('homepage', 'Homepage', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, '%homepage%' , '_blank');
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for created_visa field
            //
            $column = new TextViewColumn('created_visa', 'Created Visa', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for created_date field
            //
            $column = new DateTimeViewColumn('created_date', 'Created Date', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for updated_visa field
            //
            $column = new TextViewColumn('updated_visa', 'Updated Visa', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for updated_date field
            //
            $column = new DateTimeViewColumn('updated_date', 'Updated Date', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
        }
    
        protected function AddSingleRecordViewColumns(Grid $grid)
        {
            //
            // View column for id field
            //
            $column = new TextViewColumn('id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for name_de field
            //
            $column = new TextViewColumn('name_de', 'Name De', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'organisation.php?operation=edit&amp;pk0=%id%&amp;t=0' , '_self');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for name_fr field
            //
            $column = new TextViewColumn('name_fr', 'Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for name_it field
            //
            $column = new TextViewColumn('name_it', 'Name It', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for ort field
            //
            $column = new TextViewColumn('ort', 'Ort', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for rechtsform field
            //
            $column = new TextViewColumn('rechtsform', 'Rechtsform', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for vernehmlassung field
            //
            $column = new TextViewColumn('vernehmlassung', 'Vernehmlassung', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('interessengruppe_id_name', 'Interessengruppe', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'interessengruppe.php?operation=edit&pk0=%interessengruppe_id%' , '_self');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('branche_id_name', 'Branche', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'branche.php?operation=edit&pk0=%branche_id%' , '_self');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for homepage field
            //
            $column = new TextViewColumn('homepage', 'Homepage', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, '%homepage%' , '_blank');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for created_visa field
            //
            $column = new TextViewColumn('created_visa', 'Created Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for created_date field
            //
            $column = new DateTimeViewColumn('created_date', 'Created Date', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for updated_visa field
            //
            $column = new TextViewColumn('updated_visa', 'Updated Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for updated_date field
            //
            $column = new DateTimeViewColumn('updated_date', 'Updated Date', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
        }
    
        protected function AddEditColumns(Grid $grid)
        {
            //
            // Edit column for id field
            //
            $editor = new SpinEdit('id_edit');
            $editColumn = new CustomEditColumn('Id', 'id', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for name_de field
            //
            $editor = new TextEdit('name_de_edit');
            $editColumn = new CustomEditColumn('Name De', 'name_de', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for name_fr field
            //
            $editor = new TextEdit('name_fr_edit');
            $editColumn = new CustomEditColumn('Name Fr', 'name_fr', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for name_it field
            //
            $editor = new TextEdit('name_it_edit');
            $editColumn = new CustomEditColumn('Name It', 'name_it', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for ort field
            //
            $editor = new TextEdit('ort_edit');
            $editColumn = new CustomEditColumn('Ort', 'ort', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for rechtsform field
            //
            $editor = new TextEdit('rechtsform_edit');
            $editColumn = new CustomEditColumn('Rechtsform', 'rechtsform', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for vernehmlassung field
            //
            $editor = new TextEdit('vernehmlassung_edit');
            $editColumn = new CustomEditColumn('Vernehmlassung', 'vernehmlassung', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for interessengruppe_id field
            //
            $editor = new ComboBox('interessengruppe_id_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                new MyPDOConnectionFactory(),
                GetConnectionOptions(),
                '`v_interessengruppe_simple`');
            $field = new StringField('anzeige_name');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('id');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('name');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('name_fr');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('branche_id');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('alias_namen');
            $lookupDataset->AddField($field, false);
            $field = new StringField('alias_namen_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('notizen');
            $lookupDataset->AddField($field, false);
            $field = new StringField('eingabe_abgeschlossen_visa');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('eingabe_abgeschlossen_datum');
            $lookupDataset->AddField($field, false);
            $field = new StringField('kontrolliert_visa');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('kontrolliert_datum');
            $lookupDataset->AddField($field, false);
            $field = new StringField('freigabe_visa');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('freigabe_datum');
            $lookupDataset->AddField($field, false);
            $field = new StringField('created_visa');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('created_date');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('updated_visa');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('updated_date');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('created_date_unix');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('updated_date_unix');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('eingabe_abgeschlossen_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('kontrolliert_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('freigabe_datum_unix');
            $lookupDataset->AddField($field, false);
            $lookupDataset->SetOrderBy('name', GetOrderTypeAsSQL(otAscending));
            $editColumn = new LookUpEditColumn(
                'Interessengruppe', 
                'interessengruppe_id', 
                $editor, 
                $this->dataset, 'id', 'name', $lookupDataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for branche_id field
            //
            $editor = new ComboBox('branche_id_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                new MyPDOConnectionFactory(),
                GetConnectionOptions(),
                '`v_branche_simple`');
            $field = new StringField('anzeige_name');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('id');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('name');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('name_fr');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('kommission_id');
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('angaben');
            $lookupDataset->AddField($field, false);
            $field = new StringField('angaben_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('farbcode');
            $lookupDataset->AddField($field, false);
            $field = new StringField('symbol_abs');
            $lookupDataset->AddField($field, false);
            $field = new StringField('symbol_rel');
            $lookupDataset->AddField($field, false);
            $field = new StringField('symbol_klein_rel');
            $lookupDataset->AddField($field, false);
            $field = new StringField('symbol_dateiname_wo_ext');
            $lookupDataset->AddField($field, false);
            $field = new StringField('symbol_dateierweiterung');
            $lookupDataset->AddField($field, false);
            $field = new StringField('symbol_dateiname');
            $lookupDataset->AddField($field, false);
            $field = new StringField('symbol_mime_type');
            $lookupDataset->AddField($field, false);
            $field = new StringField('notizen');
            $lookupDataset->AddField($field, false);
            $field = new StringField('eingabe_abgeschlossen_visa');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('eingabe_abgeschlossen_datum');
            $lookupDataset->AddField($field, false);
            $field = new StringField('kontrolliert_visa');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('kontrolliert_datum');
            $lookupDataset->AddField($field, false);
            $field = new StringField('freigabe_visa');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('freigabe_datum');
            $lookupDataset->AddField($field, false);
            $field = new StringField('created_visa');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('created_date');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('updated_visa');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('updated_date');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('created_date_unix');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('updated_date_unix');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('eingabe_abgeschlossen_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('kontrolliert_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('freigabe_datum_unix');
            $lookupDataset->AddField($field, false);
            $lookupDataset->SetOrderBy('name', GetOrderTypeAsSQL(otAscending));
            $editColumn = new LookUpEditColumn(
                'Branche', 
                'branche_id', 
                $editor, 
                $this->dataset, 'id', 'name', $lookupDataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for homepage field
            //
            $editor = new TextEdit('homepage_edit');
            $editColumn = new CustomEditColumn('Homepage', 'homepage', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for created_visa field
            //
            $editor = new TextEdit('created_visa_edit');
            $editColumn = new CustomEditColumn('Created Visa', 'created_visa', $editor, $this->dataset);
            $editColumn->SetReadOnly(true);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for created_date field
            //
            $editor = new DateTimeEdit('created_date_edit', true, 'Y-m-d H:i:s', GetFirstDayOfWeek());
            $editColumn = new CustomEditColumn('Created Date', 'created_date', $editor, $this->dataset);
            $editColumn->SetReadOnly(true);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for updated_visa field
            //
            $editor = new TextEdit('updated_visa_edit');
            $editColumn = new CustomEditColumn('Updated Visa', 'updated_visa', $editor, $this->dataset);
            $editColumn->SetReadOnly(true);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for updated_date field
            //
            $editor = new DateTimeEdit('updated_date_edit', true, 'Y-m-d H:i:s', GetFirstDayOfWeek());
            $editColumn = new CustomEditColumn('Updated Date', 'updated_date', $editor, $this->dataset);
            $editColumn->SetReadOnly(true);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
        }
    
        protected function AddInsertColumns(Grid $grid)
        {
            //
            // Edit column for id field
            //
            $editor = new SpinEdit('id_edit');
            $editColumn = new CustomEditColumn('Id', 'id', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for name_de field
            //
            $editor = new TextEdit('name_de_edit');
            $editColumn = new CustomEditColumn('Name De', 'name_de', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for name_fr field
            //
            $editor = new TextEdit('name_fr_edit');
            $editColumn = new CustomEditColumn('Name Fr', 'name_fr', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for name_it field
            //
            $editor = new TextEdit('name_it_edit');
            $editColumn = new CustomEditColumn('Name It', 'name_it', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for ort field
            //
            $editor = new TextEdit('ort_edit');
            $editColumn = new CustomEditColumn('Ort', 'ort', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for rechtsform field
            //
            $editor = new TextEdit('rechtsform_edit');
            $editColumn = new CustomEditColumn('Rechtsform', 'rechtsform', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for vernehmlassung field
            //
            $editor = new TextEdit('vernehmlassung_edit');
            $editColumn = new CustomEditColumn('Vernehmlassung', 'vernehmlassung', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for interessengruppe_id field
            //
            $editor = new ComboBox('interessengruppe_id_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                new MyPDOConnectionFactory(),
                GetConnectionOptions(),
                '`v_interessengruppe_simple`');
            $field = new StringField('anzeige_name');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('id');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('name');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('name_fr');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('branche_id');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('alias_namen');
            $lookupDataset->AddField($field, false);
            $field = new StringField('alias_namen_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('notizen');
            $lookupDataset->AddField($field, false);
            $field = new StringField('eingabe_abgeschlossen_visa');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('eingabe_abgeschlossen_datum');
            $lookupDataset->AddField($field, false);
            $field = new StringField('kontrolliert_visa');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('kontrolliert_datum');
            $lookupDataset->AddField($field, false);
            $field = new StringField('freigabe_visa');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('freigabe_datum');
            $lookupDataset->AddField($field, false);
            $field = new StringField('created_visa');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('created_date');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('updated_visa');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('updated_date');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('created_date_unix');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('updated_date_unix');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('eingabe_abgeschlossen_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('kontrolliert_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('freigabe_datum_unix');
            $lookupDataset->AddField($field, false);
            $lookupDataset->SetOrderBy('name', GetOrderTypeAsSQL(otAscending));
            $editColumn = new LookUpEditColumn(
                'Interessengruppe', 
                'interessengruppe_id', 
                $editor, 
                $this->dataset, 'id', 'name', $lookupDataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for branche_id field
            //
            $editor = new ComboBox('branche_id_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                new MyPDOConnectionFactory(),
                GetConnectionOptions(),
                '`v_branche_simple`');
            $field = new StringField('anzeige_name');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('id');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('name');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('name_fr');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('kommission_id');
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('angaben');
            $lookupDataset->AddField($field, false);
            $field = new StringField('angaben_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('farbcode');
            $lookupDataset->AddField($field, false);
            $field = new StringField('symbol_abs');
            $lookupDataset->AddField($field, false);
            $field = new StringField('symbol_rel');
            $lookupDataset->AddField($field, false);
            $field = new StringField('symbol_klein_rel');
            $lookupDataset->AddField($field, false);
            $field = new StringField('symbol_dateiname_wo_ext');
            $lookupDataset->AddField($field, false);
            $field = new StringField('symbol_dateierweiterung');
            $lookupDataset->AddField($field, false);
            $field = new StringField('symbol_dateiname');
            $lookupDataset->AddField($field, false);
            $field = new StringField('symbol_mime_type');
            $lookupDataset->AddField($field, false);
            $field = new StringField('notizen');
            $lookupDataset->AddField($field, false);
            $field = new StringField('eingabe_abgeschlossen_visa');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('eingabe_abgeschlossen_datum');
            $lookupDataset->AddField($field, false);
            $field = new StringField('kontrolliert_visa');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('kontrolliert_datum');
            $lookupDataset->AddField($field, false);
            $field = new StringField('freigabe_visa');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('freigabe_datum');
            $lookupDataset->AddField($field, false);
            $field = new StringField('created_visa');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('created_date');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('updated_visa');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('updated_date');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('created_date_unix');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('updated_date_unix');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('eingabe_abgeschlossen_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('kontrolliert_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('freigabe_datum_unix');
            $lookupDataset->AddField($field, false);
            $lookupDataset->SetOrderBy('name', GetOrderTypeAsSQL(otAscending));
            $editColumn = new LookUpEditColumn(
                'Branche', 
                'branche_id', 
                $editor, 
                $this->dataset, 'id', 'name', $lookupDataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for homepage field
            //
            $editor = new TextEdit('homepage_edit');
            $editColumn = new CustomEditColumn('Homepage', 'homepage', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for created_visa field
            //
            $editor = new TextEdit('created_visa_edit');
            $editColumn = new CustomEditColumn('Created Visa', 'created_visa', $editor, $this->dataset);
            $editColumn->SetReadOnly(true);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for created_date field
            //
            $editor = new DateTimeEdit('created_date_edit', true, 'Y-m-d H:i:s', GetFirstDayOfWeek());
            $editColumn = new CustomEditColumn('Created Date', 'created_date', $editor, $this->dataset);
            $editColumn->SetReadOnly(true);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for updated_visa field
            //
            $editor = new TextEdit('updated_visa_edit');
            $editColumn = new CustomEditColumn('Updated Visa', 'updated_visa', $editor, $this->dataset);
            $editColumn->SetReadOnly(true);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for updated_date field
            //
            $editor = new DateTimeEdit('updated_date_edit', true, 'Y-m-d H:i:s', GetFirstDayOfWeek());
            $editColumn = new CustomEditColumn('Updated Date', 'updated_date', $editor, $this->dataset);
            $editColumn->SetReadOnly(true);
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
            // View column for id field
            //
            $column = new TextViewColumn('id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for name_de field
            //
            $column = new TextViewColumn('name_de', 'Name De', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'organisation.php?operation=edit&amp;pk0=%id%&amp;t=0' , '_self');
            $grid->AddPrintColumn($column);
            
            //
            // View column for name_fr field
            //
            $column = new TextViewColumn('name_fr', 'Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for name_it field
            //
            $column = new TextViewColumn('name_it', 'Name It', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for ort field
            //
            $column = new TextViewColumn('ort', 'Ort', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for rechtsform field
            //
            $column = new TextViewColumn('rechtsform', 'Rechtsform', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for vernehmlassung field
            //
            $column = new TextViewColumn('vernehmlassung', 'Vernehmlassung', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('interessengruppe_id_name', 'Interessengruppe', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'interessengruppe.php?operation=edit&pk0=%interessengruppe_id%' , '_self');
            $grid->AddPrintColumn($column);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('branche_id_name', 'Branche', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'branche.php?operation=edit&pk0=%branche_id%' , '_self');
            $grid->AddPrintColumn($column);
            
            //
            // View column for homepage field
            //
            $column = new TextViewColumn('homepage', 'Homepage', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, '%homepage%' , '_blank');
            $grid->AddPrintColumn($column);
            
            //
            // View column for created_visa field
            //
            $column = new TextViewColumn('created_visa', 'Created Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for created_date field
            //
            $column = new DateTimeViewColumn('created_date', 'Created Date', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for updated_visa field
            //
            $column = new TextViewColumn('updated_visa', 'Updated Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for updated_date field
            //
            $column = new DateTimeViewColumn('updated_date', 'Updated Date', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
        }
    
        protected function AddExportColumns(Grid $grid)
        {
            //
            // View column for id field
            //
            $column = new TextViewColumn('id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for name_de field
            //
            $column = new TextViewColumn('name_de', 'Name De', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'organisation.php?operation=edit&amp;pk0=%id%&amp;t=0' , '_self');
            $grid->AddExportColumn($column);
            
            //
            // View column for name_fr field
            //
            $column = new TextViewColumn('name_fr', 'Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for name_it field
            //
            $column = new TextViewColumn('name_it', 'Name It', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for ort field
            //
            $column = new TextViewColumn('ort', 'Ort', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for rechtsform field
            //
            $column = new TextViewColumn('rechtsform', 'Rechtsform', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for vernehmlassung field
            //
            $column = new TextViewColumn('vernehmlassung', 'Vernehmlassung', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('interessengruppe_id_name', 'Interessengruppe', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'interessengruppe.php?operation=edit&pk0=%interessengruppe_id%' , '_self');
            $grid->AddExportColumn($column);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('branche_id_name', 'Branche', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'branche.php?operation=edit&pk0=%branche_id%' , '_self');
            $grid->AddExportColumn($column);
            
            //
            // View column for homepage field
            //
            $column = new TextViewColumn('homepage', 'Homepage', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, '%homepage%' , '_blank');
            $grid->AddExportColumn($column);
            
            //
            // View column for created_visa field
            //
            $column = new TextViewColumn('created_visa', 'Created Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for created_date field
            //
            $column = new DateTimeViewColumn('created_date', 'Created Date', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for updated_visa field
            //
            $column = new TextViewColumn('updated_visa', 'Updated Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for updated_date field
            //
            $column = new DateTimeViewColumn('updated_date', 'Updated Date', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
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
        public function q_unvollstaendige_organisationenGrid_OnGetCustomTemplate($part, $mode, &$result, &$params)
        {
        defaultOnGetCustomTemplate($this, $part, $mode, $result, $params);
        }
        public function q_unvollstaendige_organisationenGrid_OnCustomDrawRow($rowData, &$rowCellStyles, &$rowStyles)
        {
        customDrawRow('organisation', $rowData, $rowCellStyles, $rowStyles);
        }
    
        protected function CreateGrid()
        {
            $result = new Grid($this, $this->dataset, 'q_unvollstaendige_organisationenGrid');
            if ($this->GetSecurityInfo()->HasDeleteGrant())
               $result->SetAllowDeleteSelected(false);
            else
               $result->SetAllowDeleteSelected(false);   
            
            ApplyCommonPageSettings($this, $result);
            
            $result->SetUseImagesForActions(true);
            $result->SetUseFixedHeader(true);
            $result->SetShowLineNumbers(true);
            
            $result->SetHighlightRowAtHover(false);
            $result->SetWidth('');
            $this->OnGetCustomTemplate->AddListener('q_unvollstaendige_organisationenGrid' . '_OnGetCustomTemplate', $this);
            $result->OnCustomDrawCell->AddListener('q_unvollstaendige_organisationenGrid' . '_OnCustomDrawRow', $this);
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
    
    <p>Auflistung der Organisationen mit unvollständigen Angaben.</p>
    
    <p>Eine Organisation erscheint hier wenn
    <ul>
    <li>keine Rechtsform eingetragen ist,
    <li>die Interessengruppe fehlt.
    </ul>
    </p>
    
    <p>Diese Kritieren werden nach Bedarf angepasst.</p>
    
    ' . $GLOBALS["edit_general_hint"] /*afterburner*/  . '';
        }
    }

    SetUpUserAuthorization(GetApplication());

    try
    {
        $Page = new q_unvollstaendige_organisationenPage("q_unvollstaendige_organisationen.php", "q_unvollstaendige_organisationen", GetCurrentUserGrantForDataSource("q_unvollstaendige_organisationen"), 'UTF-8');
        $Page->SetShortCaption('<span class="view">Unvollständige Organisationen</span>');
        $Page->SetHeader(GetPagesHeader());
        $Page->SetFooter(GetPagesFooter());
        $Page->SetCaption('Unvollständige Organisationen');
        $Page->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource("q_unvollstaendige_organisationen"));
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
