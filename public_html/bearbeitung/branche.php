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
    
    
    
    class interessengruppeDetailView0branchePage extends DetailPage
    {
        protected function DoBeforeCreate()
        {
            $this->dataset = new TableDataset(
                new MyPDOConnectionFactory(),
                GetConnectionOptions(),
                '`interessengruppe`');
            $field = new IntegerField('id', null, null, true);
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, true);
            $field = new StringField('name');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('name_fr');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('branche_id');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('beschreibung');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('beschreibung_fr');
            $this->dataset->AddField($field, false);
            $field = new StringField('alias_namen');
            $this->dataset->AddField($field, false);
            $field = new StringField('alias_namen_fr');
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
            $this->dataset->AddLookupField('branche_id', 'v_branche_simple', new IntegerField('id'), new StringField('name', 'branche_id_name', 'branche_id_name_v_branche_simple'), 'branche_id_name_v_branche_simple');
        }
    
        protected function DoPrepare() {
    
        }
    
        protected function AddFieldColumns(Grid $grid)
        {
            //
            // View column for id field
            //
            $column = new TextViewColumn('id', 'Id', $this->dataset);
            $column->SetOrderable(false);
            $column->SetDescription($this->RenderText('Technischer Schlüssel der Interessengruppe'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('name', 'Name', $this->dataset);
            $column->SetOrderable(false);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('interessengruppeDetailViewGrid0branche_name_handler_list');
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'interessengruppe.php?operation=view&pk0=%id%' , '_self');
            $column->SetDescription($this->RenderText('Bezeichnung der Interessengruppe'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('branche_id_name', 'Branche', $this->dataset);
            $column->SetOrderable(false);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'branche.php?operation=view&pk0=%branche_id%' , '_self');
            $column->SetDescription($this->RenderText('Fremdschlüssel Branche'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for beschreibung field
            //
            $column = new TextViewColumn('beschreibung', 'Beschreibung', $this->dataset);
            $column->SetOrderable(false);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('interessengruppeDetailViewGrid0branche_beschreibung_handler_list');
            $column->SetReplaceLFByBR(true);
            $column->SetDescription($this->RenderText('Eingrenzung und Beschreibung zur Interessengruppe'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(false);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('interessengruppeDetailViewGrid0branche_notizen_handler_list');
            $column->SetReplaceLFByBR(true);
            $column->SetDescription($this->RenderText('Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_visa field
            //
            $column = new TextViewColumn('eingabe_abgeschlossen_visa', 'Eingabe Abgeschlossen Visa', $this->dataset);
            $column->SetOrderable(false);
            $column->SetDescription($this->RenderText('Kürzel der Person, welche die Eingabe abgeschlossen hat.'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_datum field
            //
            $column = new DateTimeViewColumn('eingabe_abgeschlossen_datum', 'Eingabe Abgeschlossen Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(false);
            $column->SetDescription($this->RenderText('Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for kontrolliert_visa field
            //
            $column = new TextViewColumn('kontrolliert_visa', 'Kontrolliert Visa', $this->dataset);
            $column->SetOrderable(false);
            $column->SetDescription($this->RenderText('Kürzel der Person, welche die Eingabe kontrolliert hat.'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for kontrolliert_datum field
            //
            $column = new DateTimeViewColumn('kontrolliert_datum', 'Kontrolliert Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(false);
            $column->SetDescription($this->RenderText('Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for freigabe_visa field
            //
            $column = new TextViewColumn('freigabe_visa', 'Freigabe Visa', $this->dataset);
            $column->SetOrderable(false);
            $column->SetDescription($this->RenderText('Freigabe von wem? (Freigabe = Daten sind fertig)'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for freigabe_datum field
            //
            $column = new DateTimeViewColumn('freigabe_datum', 'Freigabe Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(false);
            $column->SetDescription($this->RenderText('Freigabedatum (Freigabe = Daten sind fertig)'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for created_visa field
            //
            $column = new TextViewColumn('created_visa', 'Created Visa', $this->dataset);
            $column->SetOrderable(false);
            $column->SetDescription($this->RenderText('Erstellt von'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for created_date field
            //
            $column = new DateTimeViewColumn('created_date', 'Created Date', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(false);
            $column->SetDescription($this->RenderText('Erstellt am'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for updated_visa field
            //
            $column = new TextViewColumn('updated_visa', 'Updated Visa', $this->dataset);
            $column->SetOrderable(false);
            $column->SetDescription($this->RenderText('Abgeändert von'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for updated_date field
            //
            $column = new DateTimeViewColumn('updated_date', 'Updated Date', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(false);
            $column->SetDescription($this->RenderText('Abgeändert am'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
        }
        
        function GetCustomClientScript()
        {
            return ;
        }
        
        function GetOnPageLoadedClientScript()
        {
            return ;
        }
    
        public function GetPageDirection()
        {
            return null;
        }
    
        protected function ApplyCommonColumnEditProperties(CustomEditColumn $column)
        {
            $column->SetDisplaySetToNullCheckBox(false);
            $column->SetDisplaySetToDefaultCheckBox(false);
        }
    
        protected function CreateGrid()
        {
            $result = new Grid($this, $this->dataset, 'interessengruppeDetailViewGrid0branche');
            $result->SetAllowDeleteSelected(false);
            $result->SetUseFixedHeader(true);
            $result->SetShowLineNumbers(true);
            
            $result->SetHighlightRowAtHover(false);
            $result->SetWidth('');
            $this->AddFieldColumns($result);
            //
            // View column for name field
            //
            $column = new TextViewColumn('name', 'Name', $this->dataset);
            $column->SetOrderable(false);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'interessengruppe.php?operation=view&pk0=%id%' , '_self');
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'interessengruppeDetailViewGrid0branche_name_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for beschreibung field
            //
            $column = new TextViewColumn('beschreibung', 'Beschreibung', $this->dataset);
            $column->SetOrderable(false);
            $column->SetReplaceLFByBR(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'interessengruppeDetailViewGrid0branche_beschreibung_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(false);
            $column->SetReplaceLFByBR(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'interessengruppeDetailViewGrid0branche_notizen_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            return $result;
        }
    }
    
    
    
    // OnBeforePageExecute event handler
    
    
    
    class interessengruppeDetailEdit0branchePage extends DetailPageEdit
    {
        protected function DoBeforeCreate()
        {
            $this->dataset = new TableDataset(
                new MyPDOConnectionFactory(),
                GetConnectionOptions(),
                '`interessengruppe`');
            $field = new IntegerField('id', null, null, true);
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, true);
            $field = new StringField('name');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('name_fr');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('branche_id');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('beschreibung');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('beschreibung_fr');
            $this->dataset->AddField($field, false);
            $field = new StringField('alias_namen');
            $this->dataset->AddField($field, false);
            $field = new StringField('alias_namen_fr');
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
            $this->dataset->AddLookupField('branche_id', 'v_branche_simple', new IntegerField('id'), new StringField('name', 'branche_id_name', 'branche_id_name_v_branche_simple'), 'branche_id_name_v_branche_simple');
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
            return null;
        }
    
        protected function CreateRssGenerator() {
            return setupRSS($this, $this->dataset); /*afterburner*/ 
        }
    
        protected function CreateGridSearchControl(Grid $grid)
        {
            $grid->UseFilter = true;
            $grid->SearchControl = new SimpleSearch('interessengruppeDetailEdit0branchessearch', $this->dataset,
                array('id', 'name', 'branche_id_name', 'beschreibung', 'notizen', 'eingabe_abgeschlossen_visa', 'eingabe_abgeschlossen_datum', 'kontrolliert_visa', 'kontrolliert_datum', 'freigabe_visa', 'freigabe_datum', 'created_visa', 'created_date', 'updated_visa', 'updated_date'),
                array($this->RenderText('Id'), $this->RenderText('Name'), $this->RenderText('Branche'), $this->RenderText('Beschreibung'), $this->RenderText('Notizen'), $this->RenderText('Eingabe Abgeschlossen Visa'), $this->RenderText('Eingabe Abgeschlossen Datum'), $this->RenderText('Kontrolliert Visa'), $this->RenderText('Kontrolliert Datum'), $this->RenderText('Freigabe Visa'), $this->RenderText('Freigabe Datum'), $this->RenderText('Created Visa'), $this->RenderText('Created Date'), $this->RenderText('Updated Visa'), $this->RenderText('Updated Date')),
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
            $this->AdvancedSearchControl = new AdvancedSearchControl('interessengruppeDetailEdit0brancheasearch', $this->dataset, $this->GetLocalizerCaptions(), $this->GetColumnVariableContainer(), $this->CreateLinkBuilder());
            $this->AdvancedSearchControl->setTimerInterval(1000);
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('id', $this->RenderText('Id')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('name', $this->RenderText('Name')));
            
            $lookupDataset = new TableDataset(
                new MyPDOConnectionFactory(),
                GetConnectionOptions(),
                '`v_branche_simple`');
            $field = new StringField('anzeige_name');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_mixed');
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
            $field = new StringField('technischer_name');
            $field->SetIsNotNull(true);
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
            $field = new StringField('name_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('angaben_de');
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
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateLookupSearchInput('branche_id', $this->RenderText('Branche'), $lookupDataset, 'id', 'name', false, 8));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('beschreibung', $this->RenderText('Beschreibung')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('notizen', $this->RenderText('Notizen')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('eingabe_abgeschlossen_visa', $this->RenderText('Eingabe Abgeschlossen Visa')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateDateTimeSearchInput('eingabe_abgeschlossen_datum', $this->RenderText('Eingabe Abgeschlossen Datum'), 'd.m.Y H:i:s'));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('kontrolliert_visa', $this->RenderText('Kontrolliert Visa')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateDateTimeSearchInput('kontrolliert_datum', $this->RenderText('Kontrolliert Datum'), 'd.m.Y H:i:s'));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('freigabe_visa', $this->RenderText('Freigabe Visa')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateDateTimeSearchInput('freigabe_datum', $this->RenderText('Freigabe Datum'), 'd.m.Y H:i:s'));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('created_visa', $this->RenderText('Created Visa')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateDateTimeSearchInput('created_date', $this->RenderText('Created Date'), 'd.m.Y H:i:s'));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('updated_visa', $this->RenderText('Updated Visa')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateDateTimeSearchInput('updated_date', $this->RenderText('Updated Date'), 'd.m.Y H:i:s'));
        }
    
        public function GetPageDirection()
        {
            return null;
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
            $column->SetDescription($this->RenderText('Technischer Schlüssel der Interessengruppe'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('name', 'Name', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('interessengruppeDetailEditGrid0branche_name_handler_list');
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'interessengruppe.php?operation=view&pk0=%id%' , '_self');
            $column->SetDescription($this->RenderText('Bezeichnung der Interessengruppe'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('branche_id_name', 'Branche', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'branche.php?operation=view&pk0=%branche_id%' , '_self');
            $column->SetDescription($this->RenderText('Fremdschlüssel Branche'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for beschreibung field
            //
            $column = new TextViewColumn('beschreibung', 'Beschreibung', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('interessengruppeDetailEditGrid0branche_beschreibung_handler_list');
            $column->SetReplaceLFByBR(true);
            $column->SetDescription($this->RenderText('Eingrenzung und Beschreibung zur Interessengruppe'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('interessengruppeDetailEditGrid0branche_notizen_handler_list');
            $column->SetReplaceLFByBR(true);
            $column->SetDescription($this->RenderText('Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_visa field
            //
            $column = new TextViewColumn('eingabe_abgeschlossen_visa', 'Eingabe Abgeschlossen Visa', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Kürzel der Person, welche die Eingabe abgeschlossen hat.'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_datum field
            //
            $column = new DateTimeViewColumn('eingabe_abgeschlossen_datum', 'Eingabe Abgeschlossen Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for kontrolliert_visa field
            //
            $column = new TextViewColumn('kontrolliert_visa', 'Kontrolliert Visa', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Kürzel der Person, welche die Eingabe kontrolliert hat.'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for kontrolliert_datum field
            //
            $column = new DateTimeViewColumn('kontrolliert_datum', 'Kontrolliert Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for freigabe_visa field
            //
            $column = new TextViewColumn('freigabe_visa', 'Freigabe Visa', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Freigabe von wem? (Freigabe = Daten sind fertig)'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for freigabe_datum field
            //
            $column = new DateTimeViewColumn('freigabe_datum', 'Freigabe Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Freigabedatum (Freigabe = Daten sind fertig)'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for created_visa field
            //
            $column = new TextViewColumn('created_visa', 'Created Visa', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Erstellt von'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for created_date field
            //
            $column = new DateTimeViewColumn('created_date', 'Created Date', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Erstellt am'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for updated_visa field
            //
            $column = new TextViewColumn('updated_visa', 'Updated Visa', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Abgeändert von'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for updated_date field
            //
            $column = new DateTimeViewColumn('updated_date', 'Updated Date', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Abgeändert am'));
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
            // View column for name field
            //
            $column = new TextViewColumn('name', 'Name', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('interessengruppeDetailEditGrid0branche_name_handler_view');
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'interessengruppe.php?operation=view&pk0=%id%' , '_self');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('branche_id_name', 'Branche', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'branche.php?operation=view&pk0=%branche_id%' , '_self');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for beschreibung field
            //
            $column = new TextViewColumn('beschreibung', 'Beschreibung', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('interessengruppeDetailEditGrid0branche_beschreibung_handler_view');
            $column->SetReplaceLFByBR(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('interessengruppeDetailEditGrid0branche_notizen_handler_view');
            $column->SetReplaceLFByBR(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_visa field
            //
            $column = new TextViewColumn('eingabe_abgeschlossen_visa', 'Eingabe Abgeschlossen Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_datum field
            //
            $column = new DateTimeViewColumn('eingabe_abgeschlossen_datum', 'Eingabe Abgeschlossen Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for kontrolliert_visa field
            //
            $column = new TextViewColumn('kontrolliert_visa', 'Kontrolliert Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for kontrolliert_datum field
            //
            $column = new DateTimeViewColumn('kontrolliert_datum', 'Kontrolliert Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for freigabe_visa field
            //
            $column = new TextViewColumn('freigabe_visa', 'Freigabe Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for freigabe_datum field
            //
            $column = new DateTimeViewColumn('freigabe_datum', 'Freigabe Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
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
            // Edit column for name field
            //
            $editor = new TextAreaEdit('name_edit', 50, 8);
            $editColumn = new CustomEditColumn('Name', 'name', $editor, $this->dataset);
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
            $field = new StringField('anzeige_name_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_mixed');
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
            $field = new StringField('technischer_name');
            $field->SetIsNotNull(true);
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
            $field = new StringField('name_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('angaben_de');
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
            // Edit column for beschreibung field
            //
            $editor = new TextAreaEdit('beschreibung_edit', 50, 8);
            $editColumn = new CustomEditColumn('Beschreibung', 'beschreibung', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for notizen field
            //
            $editor = new TextAreaEdit('notizen_edit', 50, 8);
            $editColumn = new CustomEditColumn('Notizen', 'notizen', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for eingabe_abgeschlossen_visa field
            //
            $editor = new TextEdit('eingabe_abgeschlossen_visa_edit');
            $editor->SetSize(10);
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Eingabe Abgeschlossen Visa', 'eingabe_abgeschlossen_visa', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for eingabe_abgeschlossen_datum field
            //
            $editor = new DateTimeEdit('eingabe_abgeschlossen_datum_edit', true, 'Y-m-d H:i:s', GetFirstDayOfWeek());
            $editColumn = new CustomEditColumn('Eingabe Abgeschlossen Datum', 'eingabe_abgeschlossen_datum', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for kontrolliert_visa field
            //
            $editor = new TextEdit('kontrolliert_visa_edit');
            $editor->SetSize(10);
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Kontrolliert Visa', 'kontrolliert_visa', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for kontrolliert_datum field
            //
            $editor = new DateTimeEdit('kontrolliert_datum_edit', true, 'Y-m-d H:i:s', GetFirstDayOfWeek());
            $editColumn = new CustomEditColumn('Kontrolliert Datum', 'kontrolliert_datum', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for freigabe_visa field
            //
            $editor = new TextEdit('freigabe_visa_edit');
            $editor->SetSize(10);
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Freigabe Visa', 'freigabe_visa', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for freigabe_datum field
            //
            $editor = new DateTimeEdit('freigabe_datum_edit', true, 'Y-m-d H:i:s', GetFirstDayOfWeek());
            $editColumn = new CustomEditColumn('Freigabe Datum', 'freigabe_datum', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for created_visa field
            //
            $editor = new TextEdit('created_visa_edit');
            $editor->SetSize(10);
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Created Visa', 'created_visa', $editor, $this->dataset);
            $editColumn->SetReadOnly(true);
            $editColumn->SetAllowSetToNull(true);
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
            $editor->SetSize(10);
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Updated Visa', 'updated_visa', $editor, $this->dataset);
            $editColumn->SetReadOnly(true);
            $editColumn->SetAllowSetToNull(true);
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
            // Edit column for name field
            //
            $editor = new TextAreaEdit('name_edit', 50, 8);
            $editColumn = new CustomEditColumn('Name', 'name', $editor, $this->dataset);
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
            $field = new StringField('anzeige_name_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_mixed');
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
            $field = new StringField('technischer_name');
            $field->SetIsNotNull(true);
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
            $field = new StringField('name_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('angaben_de');
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
            // Edit column for beschreibung field
            //
            $editor = new TextAreaEdit('beschreibung_edit', 50, 8);
            $editColumn = new CustomEditColumn('Beschreibung', 'beschreibung', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for notizen field
            //
            $editor = new TextAreaEdit('notizen_edit', 50, 8);
            $editColumn = new CustomEditColumn('Notizen', 'notizen', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for eingabe_abgeschlossen_visa field
            //
            $editor = new TextEdit('eingabe_abgeschlossen_visa_edit');
            $editor->SetSize(10);
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Eingabe Abgeschlossen Visa', 'eingabe_abgeschlossen_visa', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for eingabe_abgeschlossen_datum field
            //
            $editor = new DateTimeEdit('eingabe_abgeschlossen_datum_edit', true, 'Y-m-d H:i:s', GetFirstDayOfWeek());
            $editColumn = new CustomEditColumn('Eingabe Abgeschlossen Datum', 'eingabe_abgeschlossen_datum', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for kontrolliert_visa field
            //
            $editor = new TextEdit('kontrolliert_visa_edit');
            $editor->SetSize(10);
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Kontrolliert Visa', 'kontrolliert_visa', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for kontrolliert_datum field
            //
            $editor = new DateTimeEdit('kontrolliert_datum_edit', true, 'Y-m-d H:i:s', GetFirstDayOfWeek());
            $editColumn = new CustomEditColumn('Kontrolliert Datum', 'kontrolliert_datum', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for freigabe_visa field
            //
            $editor = new TextEdit('freigabe_visa_edit');
            $editor->SetSize(10);
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Freigabe Visa', 'freigabe_visa', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for freigabe_datum field
            //
            $editor = new DateTimeEdit('freigabe_datum_edit', true, 'Y-m-d H:i:s', GetFirstDayOfWeek());
            $editColumn = new CustomEditColumn('Freigabe Datum', 'freigabe_datum', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for created_visa field
            //
            $editor = new TextEdit('created_visa_edit');
            $editor->SetSize(10);
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Created Visa', 'created_visa', $editor, $this->dataset);
            $editColumn->SetReadOnly(true);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for created_date field
            //
            $editor = new DateTimeEdit('created_date_edit', true, 'Y-m-d H:i:s', GetFirstDayOfWeek());
            $editColumn = new CustomEditColumn('Created Date', 'created_date', $editor, $this->dataset);
            $editColumn->SetReadOnly(true);
            $editColumn->SetAllowSetToDefault(false); /*afterburner*/ 
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for updated_visa field
            //
            $editor = new TextEdit('updated_visa_edit');
            $editor->SetSize(10);
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Updated Visa', 'updated_visa', $editor, $this->dataset);
            $editColumn->SetReadOnly(true);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for updated_date field
            //
            $editor = new DateTimeEdit('updated_date_edit', true, 'Y-m-d H:i:s', GetFirstDayOfWeek());
            $editColumn = new CustomEditColumn('Updated Date', 'updated_date', $editor, $this->dataset);
            $editColumn->SetReadOnly(true);
            $editColumn->SetAllowSetToDefault(false); /*afterburner*/ 
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
            // View column for name field
            //
            $column = new TextViewColumn('name', 'Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('branche_id_name', 'Branche', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'branche.php?operation=view&pk0=%branche_id%' , '_self');
            $grid->AddPrintColumn($column);
            
            //
            // View column for beschreibung field
            //
            $column = new TextViewColumn('beschreibung', 'Beschreibung', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_visa field
            //
            $column = new TextViewColumn('eingabe_abgeschlossen_visa', 'Eingabe Abgeschlossen Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_datum field
            //
            $column = new DateTimeViewColumn('eingabe_abgeschlossen_datum', 'Eingabe Abgeschlossen Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for kontrolliert_visa field
            //
            $column = new TextViewColumn('kontrolliert_visa', 'Kontrolliert Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for kontrolliert_datum field
            //
            $column = new DateTimeViewColumn('kontrolliert_datum', 'Kontrolliert Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for freigabe_visa field
            //
            $column = new TextViewColumn('freigabe_visa', 'Freigabe Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for freigabe_datum field
            //
            $column = new DateTimeViewColumn('freigabe_datum', 'Freigabe Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
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
            // View column for name field
            //
            $column = new TextViewColumn('name', 'Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('branche_id_name', 'Branche', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'branche.php?operation=view&pk0=%branche_id%' , '_self');
            $grid->AddExportColumn($column);
            
            //
            // View column for beschreibung field
            //
            $column = new TextViewColumn('beschreibung', 'Beschreibung', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_visa field
            //
            $column = new TextViewColumn('eingabe_abgeschlossen_visa', 'Eingabe Abgeschlossen Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_datum field
            //
            $column = new DateTimeViewColumn('eingabe_abgeschlossen_datum', 'Eingabe Abgeschlossen Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for kontrolliert_visa field
            //
            $column = new TextViewColumn('kontrolliert_visa', 'Kontrolliert Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for kontrolliert_datum field
            //
            $column = new DateTimeViewColumn('kontrolliert_datum', 'Kontrolliert Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for freigabe_visa field
            //
            $column = new TextViewColumn('freigabe_visa', 'Freigabe Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for freigabe_datum field
            //
            $column = new DateTimeViewColumn('freigabe_datum', 'Freigabe Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
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
            $result = new Grid($this, $this->dataset, 'interessengruppeDetailEditGrid0branche');
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
            //
            // View column for name field
            //
            $column = new TextViewColumn('name', 'Name', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'interessengruppe.php?operation=view&pk0=%id%' , '_self');
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'interessengruppeDetailEditGrid0branche_name_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for beschreibung field
            //
            $column = new TextViewColumn('beschreibung', 'Beschreibung', $this->dataset);
            $column->SetOrderable(true);
            $column->SetReplaceLFByBR(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'interessengruppeDetailEditGrid0branche_beschreibung_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $column->SetReplaceLFByBR(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'interessengruppeDetailEditGrid0branche_notizen_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for name field
            //
            $column = new TextViewColumn('name', 'Name', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'interessengruppe.php?operation=view&pk0=%id%' , '_self');
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'interessengruppeDetailEditGrid0branche_name_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for beschreibung field
            //
            $column = new TextViewColumn('beschreibung', 'Beschreibung', $this->dataset);
            $column->SetOrderable(true);
            $column->SetReplaceLFByBR(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'interessengruppeDetailEditGrid0branche_beschreibung_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $column->SetReplaceLFByBR(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'interessengruppeDetailEditGrid0branche_notizen_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            return $result;
        }
        
        public function OpenAdvancedSearchByDefault()
        {
            return false;
        }
    
        protected function DoGetGridHeader()
        {
            return '';
        }    
    }
    
    
    
    // OnBeforePageExecute event handler
    
    
    
    class organisationDetailView1branchePage extends DetailPage
    {
        protected function DoBeforeCreate()
        {
            $this->dataset = new TableDataset(
                new MyPDOConnectionFactory(),
                GetConnectionOptions(),
                '`organisation`');
            $field = new IntegerField('id', null, null, true);
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
            $field = new StringField('beschreibung_fr');
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
            $this->dataset->AddLookupField('interessengruppe_id', 'v_interessengruppe_simple', new IntegerField('id'), new StringField('anzeige_name', 'interessengruppe_id_anzeige_name', 'interessengruppe_id_anzeige_name_v_interessengruppe_simple'), 'interessengruppe_id_anzeige_name_v_interessengruppe_simple');
            $this->dataset->AddLookupField('interessengruppe2_id', 'v_interessengruppe_simple', new IntegerField('id'), new StringField('anzeige_name', 'interessengruppe2_id_anzeige_name', 'interessengruppe2_id_anzeige_name_v_interessengruppe_simple'), 'interessengruppe2_id_anzeige_name_v_interessengruppe_simple');
            $this->dataset->AddLookupField('interessengruppe3_id', 'v_interessengruppe_simple', new IntegerField('id'), new StringField('anzeige_name', 'interessengruppe3_id_anzeige_name', 'interessengruppe3_id_anzeige_name_v_interessengruppe_simple'), 'interessengruppe3_id_anzeige_name_v_interessengruppe_simple');
            $this->dataset->AddLookupField('branche_id', 'v_branche_simple', new IntegerField('id'), new StringField('anzeige_name', 'branche_id_anzeige_name', 'branche_id_anzeige_name_v_branche_simple'), 'branche_id_anzeige_name_v_branche_simple');
        }
    
        protected function DoPrepare() {
    
        }
    
        protected function AddFieldColumns(Grid $grid)
        {
            //
            // View column for id field
            //
            $column = new TextViewColumn('id', 'Id', $this->dataset);
            $column->SetOrderable(false);
            $column->SetDescription($this->RenderText('Technischer Schlüssel der Lobbyorganisation'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for name_de field
            //
            $column = new TextViewColumn('name_de', 'Name De', $this->dataset);
            $column->SetOrderable(false);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('organisationDetailViewGrid1branche_name_de_handler_list');
            $column->SetDescription($this->RenderText('Name der Organisation. Sollte nur juristischem Namen entsprechen, ohne Zusätze, wie Adresse.'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for name_fr field
            //
            $column = new TextViewColumn('name_fr', 'Name Fr', $this->dataset);
            $column->SetOrderable(false);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('organisationDetailViewGrid1branche_name_fr_handler_list');
            $column->SetDescription($this->RenderText('Französischer Name'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for name_it field
            //
            $column = new TextViewColumn('name_it', 'Name It', $this->dataset);
            $column->SetOrderable(false);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('organisationDetailViewGrid1branche_name_it_handler_list');
            $column->SetDescription($this->RenderText('Italienischer Name'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for ort field
            //
            $column = new TextViewColumn('ort', 'Ort', $this->dataset);
            $column->SetOrderable(false);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('organisationDetailViewGrid1branche_ort_handler_list');
            $column->SetDescription($this->RenderText('Ort der Organisation'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for rechtsform field
            //
            $column = new TextViewColumn('rechtsform', 'Rechtsform', $this->dataset);
            $column->SetOrderable(false);
            $column->SetDescription($this->RenderText('Rechtsform der Organisation'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for typ field
            //
            $column = new TextViewColumn('typ', 'Typ', $this->dataset);
            $column->SetOrderable(false);
            $column->SetDescription($this->RenderText('Typ der Organisation. Beziehungen können über Organisation_Beziehung eingegeben werden.'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for vernehmlassung field
            //
            $column = new TextViewColumn('vernehmlassung', 'Vernehmlassung', $this->dataset);
            $column->SetOrderable(false);
            $column->SetDescription($this->RenderText('Häufigkeit der Vernehmlassungsteilnahme'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for anzeige_name field
            //
            $column = new TextViewColumn('interessengruppe_id_anzeige_name', 'Interessengruppe', $this->dataset);
            $column->SetOrderable(false);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'interessengruppe.php?operation=view&pk0=%interessengruppe_id%' , '_self');
            $column->SetDescription($this->RenderText('Fremdschlüssel Interessengruppe. Über die Interessengruppe wird eine Branche zugeordnet.'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for anzeige_name field
            //
            $column = new TextViewColumn('interessengruppe2_id_anzeige_name', '2. Interessengruppe', $this->dataset);
            $column->SetOrderable(false);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'interessengruppe.php?operation=view&pk0=%interessengruppe2_id%' , '_self');
            $column->SetDescription($this->RenderText('Fremdschlüssel Interessengruppe. 2. Interessengruppe der Organisation.'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for anzeige_name field
            //
            $column = new TextViewColumn('interessengruppe3_id_anzeige_name', '3. Interessengruppe', $this->dataset);
            $column->SetOrderable(false);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'interessengruppe.php?operation=view&pk0=%interessengruppe3_id%' , '_self');
            $column->SetDescription($this->RenderText('Fremdschlüssel Interessengruppe. 3. Interessengruppe der Organisation.'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for anzeige_name field
            //
            $column = new TextViewColumn('branche_id_anzeige_name', 'Branche', $this->dataset);
            $column->SetOrderable(false);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'branche.php?operation=view&pk0=%branche_id%' , '_self');
            $column->SetDescription($this->RenderText('Fremdschlüssel Branche.'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for beschreibung field
            //
            $column = new TextViewColumn('beschreibung', 'Beschreibung', $this->dataset);
            $column->SetOrderable(false);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('organisationDetailViewGrid1branche_beschreibung_handler_list');
            $column->SetReplaceLFByBR(true);
            $column->SetDescription($this->RenderText('Beschreibung der Lobbyorganisation'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for homepage field
            //
            $column = new TextViewColumn('homepage', 'Homepage', $this->dataset);
            $column->SetOrderable(false);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('organisationDetailViewGrid1branche_homepage_handler_list');
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, '%homepage%' , '_blank');
            $column->SetDescription($this->RenderText('Link zur Webseite'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for handelsregister_url field
            //
            $column = new TextViewColumn('handelsregister_url', 'Handelsregister Url', $this->dataset);
            $column->SetOrderable(false);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('organisationDetailViewGrid1branche_handelsregister_url_handler_list');
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, '%handelsregister_url%' , '_blank');
            $column->SetDescription($this->RenderText('Link zum Eintrag im Handelsregister'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(false);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('organisationDetailViewGrid1branche_notizen_handler_list');
            $column->SetReplaceLFByBR(true);
            $column->SetDescription($this->RenderText('Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_visa field
            //
            $column = new TextViewColumn('eingabe_abgeschlossen_visa', 'Eingabe Abgeschlossen Visa', $this->dataset);
            $column->SetOrderable(false);
            $column->SetDescription($this->RenderText('Kürzel der Person, welche die Eingabe abgeschlossen hat.'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_datum field
            //
            $column = new DateTimeViewColumn('eingabe_abgeschlossen_datum', 'Eingabe Abgeschlossen Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(false);
            $column->SetDescription($this->RenderText('Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for kontrolliert_visa field
            //
            $column = new TextViewColumn('kontrolliert_visa', 'Kontrolliert Visa', $this->dataset);
            $column->SetOrderable(false);
            $column->SetDescription($this->RenderText('Kürzel der Person, welche die Eingabe kontrolliert hat.'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for kontrolliert_datum field
            //
            $column = new DateTimeViewColumn('kontrolliert_datum', 'Kontrolliert Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(false);
            $column->SetDescription($this->RenderText('Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for freigabe_visa field
            //
            $column = new TextViewColumn('freigabe_visa', 'Freigabe Visa', $this->dataset);
            $column->SetOrderable(false);
            $column->SetDescription($this->RenderText('Freigabe von wem? (Freigabe = Daten sind fertig)'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for freigabe_datum field
            //
            $column = new DateTimeViewColumn('freigabe_datum', 'Freigabe Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(false);
            $column->SetDescription($this->RenderText('Freigabedatum (Freigabe = Daten sind fertig)'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for created_visa field
            //
            $column = new TextViewColumn('created_visa', 'Created Visa', $this->dataset);
            $column->SetOrderable(false);
            $column->SetDescription($this->RenderText('Erstellt von'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for created_date field
            //
            $column = new DateTimeViewColumn('created_date', 'Created Date', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(false);
            $column->SetDescription($this->RenderText('Erstellt am'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for updated_visa field
            //
            $column = new TextViewColumn('updated_visa', 'Updated Visa', $this->dataset);
            $column->SetOrderable(false);
            $column->SetDescription($this->RenderText('Abgeändert von'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for updated_date field
            //
            $column = new DateTimeViewColumn('updated_date', 'Updated Date', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(false);
            $column->SetDescription($this->RenderText('Abgeändert am'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
        }
        
        function GetCustomClientScript()
        {
            return ;
        }
        
        function GetOnPageLoadedClientScript()
        {
            return ;
        }
    
        public function GetPageDirection()
        {
            return null;
        }
    
        protected function ApplyCommonColumnEditProperties(CustomEditColumn $column)
        {
            $column->SetDisplaySetToNullCheckBox(false);
            $column->SetDisplaySetToDefaultCheckBox(false);
        }
    
        protected function CreateGrid()
        {
            $result = new Grid($this, $this->dataset, 'organisationDetailViewGrid1branche');
            $result->SetAllowDeleteSelected(false);
            $result->SetUseFixedHeader(true);
            $result->SetShowLineNumbers(true);
            
            $result->SetHighlightRowAtHover(false);
            $result->SetWidth('');
            $this->AddFieldColumns($result);
            //
            // View column for name_de field
            //
            $column = new TextViewColumn('name_de', 'Name De', $this->dataset);
            $column->SetOrderable(false);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'organisationDetailViewGrid1branche_name_de_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for name_fr field
            //
            $column = new TextViewColumn('name_fr', 'Name Fr', $this->dataset);
            $column->SetOrderable(false);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'organisationDetailViewGrid1branche_name_fr_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for name_it field
            //
            $column = new TextViewColumn('name_it', 'Name It', $this->dataset);
            $column->SetOrderable(false);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'organisationDetailViewGrid1branche_name_it_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for ort field
            //
            $column = new TextViewColumn('ort', 'Ort', $this->dataset);
            $column->SetOrderable(false);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'organisationDetailViewGrid1branche_ort_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for beschreibung field
            //
            $column = new TextViewColumn('beschreibung', 'Beschreibung', $this->dataset);
            $column->SetOrderable(false);
            $column->SetReplaceLFByBR(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'organisationDetailViewGrid1branche_beschreibung_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for homepage field
            //
            $column = new TextViewColumn('homepage', 'Homepage', $this->dataset);
            $column->SetOrderable(false);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, '%homepage%' , '_blank');
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'organisationDetailViewGrid1branche_homepage_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for handelsregister_url field
            //
            $column = new TextViewColumn('handelsregister_url', 'Handelsregister Url', $this->dataset);
            $column->SetOrderable(false);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, '%handelsregister_url%' , '_blank');
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'organisationDetailViewGrid1branche_handelsregister_url_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(false);
            $column->SetReplaceLFByBR(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'organisationDetailViewGrid1branche_notizen_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            return $result;
        }
    }
    
    
    
    // OnBeforePageExecute event handler
    
    
    
    class organisationDetailEdit1branchePage extends DetailPageEdit
    {
        protected function DoBeforeCreate()
        {
            $this->dataset = new TableDataset(
                new MyPDOConnectionFactory(),
                GetConnectionOptions(),
                '`organisation`');
            $field = new IntegerField('id', null, null, true);
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
            $field = new StringField('beschreibung_fr');
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
            $this->dataset->AddLookupField('interessengruppe_id', 'v_interessengruppe_simple', new IntegerField('id'), new StringField('anzeige_name', 'interessengruppe_id_anzeige_name', 'interessengruppe_id_anzeige_name_v_interessengruppe_simple'), 'interessengruppe_id_anzeige_name_v_interessengruppe_simple');
            $this->dataset->AddLookupField('interessengruppe2_id', 'v_interessengruppe_simple', new IntegerField('id'), new StringField('anzeige_name', 'interessengruppe2_id_anzeige_name', 'interessengruppe2_id_anzeige_name_v_interessengruppe_simple'), 'interessengruppe2_id_anzeige_name_v_interessengruppe_simple');
            $this->dataset->AddLookupField('interessengruppe3_id', 'v_interessengruppe_simple', new IntegerField('id'), new StringField('anzeige_name', 'interessengruppe3_id_anzeige_name', 'interessengruppe3_id_anzeige_name_v_interessengruppe_simple'), 'interessengruppe3_id_anzeige_name_v_interessengruppe_simple');
            $this->dataset->AddLookupField('branche_id', 'v_branche_simple', new IntegerField('id'), new StringField('anzeige_name', 'branche_id_anzeige_name', 'branche_id_anzeige_name_v_branche_simple'), 'branche_id_anzeige_name_v_branche_simple');
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
            return null;
        }
    
        protected function CreateRssGenerator() {
            return setupRSS($this, $this->dataset); /*afterburner*/ 
        }
    
        protected function CreateGridSearchControl(Grid $grid)
        {
            $grid->UseFilter = true;
            $grid->SearchControl = new SimpleSearch('organisationDetailEdit1branchessearch', $this->dataset,
                array('id', 'name_de', 'name_fr', 'name_it', 'ort', 'rechtsform', 'typ', 'vernehmlassung', 'interessengruppe_id_anzeige_name', 'interessengruppe2_id_anzeige_name', 'interessengruppe3_id_anzeige_name', 'branche_id_anzeige_name', 'beschreibung', 'homepage', 'handelsregister_url', 'notizen', 'eingabe_abgeschlossen_visa', 'eingabe_abgeschlossen_datum', 'kontrolliert_visa', 'kontrolliert_datum', 'freigabe_visa', 'freigabe_datum', 'created_visa', 'created_date', 'updated_visa', 'updated_date'),
                array($this->RenderText('Id'), $this->RenderText('Name De'), $this->RenderText('Name Fr'), $this->RenderText('Name It'), $this->RenderText('Ort'), $this->RenderText('Rechtsform'), $this->RenderText('Typ'), $this->RenderText('Vernehmlassung'), $this->RenderText('Interessengruppe'), $this->RenderText('2. Interessengruppe'), $this->RenderText('3. Interessengruppe'), $this->RenderText('Branche'), $this->RenderText('Beschreibung'), $this->RenderText('Homepage'), $this->RenderText('Handelsregister Url'), $this->RenderText('Notizen'), $this->RenderText('Eingabe Abgeschlossen Visa'), $this->RenderText('Eingabe Abgeschlossen Datum'), $this->RenderText('Kontrolliert Visa'), $this->RenderText('Kontrolliert Datum'), $this->RenderText('Freigabe Visa'), $this->RenderText('Freigabe Datum'), $this->RenderText('Created Visa'), $this->RenderText('Created Date'), $this->RenderText('Updated Visa'), $this->RenderText('Updated Date')),
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
            $this->AdvancedSearchControl = new AdvancedSearchControl('organisationDetailEdit1brancheasearch', $this->dataset, $this->GetLocalizerCaptions(), $this->GetColumnVariableContainer(), $this->CreateLinkBuilder());
            $this->AdvancedSearchControl->setTimerInterval(1000);
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('id', $this->RenderText('Id')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('name_de', $this->RenderText('Name De')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('name_fr', $this->RenderText('Name Fr')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('name_it', $this->RenderText('Name It')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('ort', $this->RenderText('Ort')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('rechtsform', $this->RenderText('Rechtsform')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('typ', $this->RenderText('Typ')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('vernehmlassung', $this->RenderText('Vernehmlassung')));
            
            $lookupDataset = new TableDataset(
                new MyPDOConnectionFactory(),
                GetConnectionOptions(),
                '`v_interessengruppe_simple`');
            $field = new StringField('anzeige_name');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_mixed');
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
            $field = new StringField('name_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('alias_namen_de');
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
            $lookupDataset->SetOrderBy('anzeige_name', GetOrderTypeAsSQL(otAscending));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateLookupSearchInput('interessengruppe_id', $this->RenderText('Interessengruppe'), $lookupDataset, 'id', 'anzeige_name', false, 8));
            
            $lookupDataset = new TableDataset(
                new MyPDOConnectionFactory(),
                GetConnectionOptions(),
                '`v_interessengruppe_simple`');
            $field = new StringField('anzeige_name');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_mixed');
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
            $field = new StringField('name_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('alias_namen_de');
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
            $lookupDataset->SetOrderBy('anzeige_name', GetOrderTypeAsSQL(otAscending));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateLookupSearchInput('interessengruppe2_id', $this->RenderText('2. Interessengruppe'), $lookupDataset, 'id', 'anzeige_name', false, 8));
            
            $lookupDataset = new TableDataset(
                new MyPDOConnectionFactory(),
                GetConnectionOptions(),
                '`v_interessengruppe_simple`');
            $field = new StringField('anzeige_name');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_mixed');
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
            $field = new StringField('name_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('alias_namen_de');
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
            $lookupDataset->SetOrderBy('anzeige_name', GetOrderTypeAsSQL(otAscending));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateLookupSearchInput('interessengruppe3_id', $this->RenderText('3. Interessengruppe'), $lookupDataset, 'id', 'anzeige_name', false, 8));
            
            $lookupDataset = new TableDataset(
                new MyPDOConnectionFactory(),
                GetConnectionOptions(),
                '`v_branche_simple`');
            $field = new StringField('anzeige_name');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_mixed');
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
            $field = new StringField('technischer_name');
            $field->SetIsNotNull(true);
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
            $field = new StringField('name_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('angaben_de');
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
            $lookupDataset->SetOrderBy('anzeige_name', GetOrderTypeAsSQL(otAscending));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateLookupSearchInput('branche_id', $this->RenderText('Branche'), $lookupDataset, 'id', 'anzeige_name', false, 8));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('beschreibung', $this->RenderText('Beschreibung')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('homepage', $this->RenderText('Homepage')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('handelsregister_url', $this->RenderText('Handelsregister Url')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('notizen', $this->RenderText('Notizen')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('eingabe_abgeschlossen_visa', $this->RenderText('Eingabe Abgeschlossen Visa')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateDateTimeSearchInput('eingabe_abgeschlossen_datum', $this->RenderText('Eingabe Abgeschlossen Datum'), 'd.m.Y H:i:s'));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('kontrolliert_visa', $this->RenderText('Kontrolliert Visa')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateDateTimeSearchInput('kontrolliert_datum', $this->RenderText('Kontrolliert Datum'), 'd.m.Y H:i:s'));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('freigabe_visa', $this->RenderText('Freigabe Visa')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateDateTimeSearchInput('freigabe_datum', $this->RenderText('Freigabe Datum'), 'd.m.Y H:i:s'));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('created_visa', $this->RenderText('Created Visa')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateDateTimeSearchInput('created_date', $this->RenderText('Created Date'), 'd.m.Y H:i:s'));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('updated_visa', $this->RenderText('Updated Visa')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateDateTimeSearchInput('updated_date', $this->RenderText('Updated Date'), 'd.m.Y H:i:s'));
        }
    
        public function GetPageDirection()
        {
            return null;
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
            $column->SetDescription($this->RenderText('Technischer Schlüssel der Lobbyorganisation'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for name_de field
            //
            $column = new TextViewColumn('name_de', 'Name De', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('organisationDetailEditGrid1branche_name_de_handler_list');
            $column->SetDescription($this->RenderText('Name der Organisation. Sollte nur juristischem Namen entsprechen, ohne Zusätze, wie Adresse.'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for name_fr field
            //
            $column = new TextViewColumn('name_fr', 'Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('organisationDetailEditGrid1branche_name_fr_handler_list');
            $column->SetDescription($this->RenderText('Französischer Name'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for name_it field
            //
            $column = new TextViewColumn('name_it', 'Name It', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('organisationDetailEditGrid1branche_name_it_handler_list');
            $column->SetDescription($this->RenderText('Italienischer Name'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for ort field
            //
            $column = new TextViewColumn('ort', 'Ort', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('organisationDetailEditGrid1branche_ort_handler_list');
            $column->SetDescription($this->RenderText('Ort der Organisation'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for rechtsform field
            //
            $column = new TextViewColumn('rechtsform', 'Rechtsform', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Rechtsform der Organisation'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for typ field
            //
            $column = new TextViewColumn('typ', 'Typ', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Typ der Organisation. Beziehungen können über Organisation_Beziehung eingegeben werden.'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for vernehmlassung field
            //
            $column = new TextViewColumn('vernehmlassung', 'Vernehmlassung', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Häufigkeit der Vernehmlassungsteilnahme'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for anzeige_name field
            //
            $column = new TextViewColumn('interessengruppe_id_anzeige_name', 'Interessengruppe', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'interessengruppe.php?operation=view&pk0=%interessengruppe_id%' , '_self');
            $column->SetDescription($this->RenderText('Fremdschlüssel Interessengruppe. Über die Interessengruppe wird eine Branche zugeordnet.'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for anzeige_name field
            //
            $column = new TextViewColumn('interessengruppe2_id_anzeige_name', '2. Interessengruppe', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'interessengruppe.php?operation=view&pk0=%interessengruppe2_id%' , '_self');
            $column->SetDescription($this->RenderText('Fremdschlüssel Interessengruppe. 2. Interessengruppe der Organisation.'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for anzeige_name field
            //
            $column = new TextViewColumn('interessengruppe3_id_anzeige_name', '3. Interessengruppe', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'interessengruppe.php?operation=view&pk0=%interessengruppe3_id%' , '_self');
            $column->SetDescription($this->RenderText('Fremdschlüssel Interessengruppe. 3. Interessengruppe der Organisation.'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for anzeige_name field
            //
            $column = new TextViewColumn('branche_id_anzeige_name', 'Branche', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'branche.php?operation=view&pk0=%branche_id%' , '_self');
            $column->SetDescription($this->RenderText('Fremdschlüssel Branche.'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for beschreibung field
            //
            $column = new TextViewColumn('beschreibung', 'Beschreibung', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('organisationDetailEditGrid1branche_beschreibung_handler_list');
            $column->SetReplaceLFByBR(true);
            $column->SetDescription($this->RenderText('Beschreibung der Lobbyorganisation'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for homepage field
            //
            $column = new TextViewColumn('homepage', 'Homepage', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('organisationDetailEditGrid1branche_homepage_handler_list');
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, '%homepage%' , '_blank');
            $column->SetDescription($this->RenderText('Link zur Webseite'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for handelsregister_url field
            //
            $column = new TextViewColumn('handelsregister_url', 'Handelsregister Url', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('organisationDetailEditGrid1branche_handelsregister_url_handler_list');
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, '%handelsregister_url%' , '_blank');
            $column->SetDescription($this->RenderText('Link zum Eintrag im Handelsregister'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('organisationDetailEditGrid1branche_notizen_handler_list');
            $column->SetReplaceLFByBR(true);
            $column->SetDescription($this->RenderText('Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_visa field
            //
            $column = new TextViewColumn('eingabe_abgeschlossen_visa', 'Eingabe Abgeschlossen Visa', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Kürzel der Person, welche die Eingabe abgeschlossen hat.'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_datum field
            //
            $column = new DateTimeViewColumn('eingabe_abgeschlossen_datum', 'Eingabe Abgeschlossen Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for kontrolliert_visa field
            //
            $column = new TextViewColumn('kontrolliert_visa', 'Kontrolliert Visa', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Kürzel der Person, welche die Eingabe kontrolliert hat.'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for kontrolliert_datum field
            //
            $column = new DateTimeViewColumn('kontrolliert_datum', 'Kontrolliert Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for freigabe_visa field
            //
            $column = new TextViewColumn('freigabe_visa', 'Freigabe Visa', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Freigabe von wem? (Freigabe = Daten sind fertig)'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for freigabe_datum field
            //
            $column = new DateTimeViewColumn('freigabe_datum', 'Freigabe Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Freigabedatum (Freigabe = Daten sind fertig)'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for created_visa field
            //
            $column = new TextViewColumn('created_visa', 'Created Visa', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Erstellt von'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for created_date field
            //
            $column = new DateTimeViewColumn('created_date', 'Created Date', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Erstellt am'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for updated_visa field
            //
            $column = new TextViewColumn('updated_visa', 'Updated Visa', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Abgeändert von'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for updated_date field
            //
            $column = new DateTimeViewColumn('updated_date', 'Updated Date', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Abgeändert am'));
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
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('organisationDetailEditGrid1branche_name_de_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for name_fr field
            //
            $column = new TextViewColumn('name_fr', 'Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('organisationDetailEditGrid1branche_name_fr_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for name_it field
            //
            $column = new TextViewColumn('name_it', 'Name It', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('organisationDetailEditGrid1branche_name_it_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for ort field
            //
            $column = new TextViewColumn('ort', 'Ort', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('organisationDetailEditGrid1branche_ort_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for rechtsform field
            //
            $column = new TextViewColumn('rechtsform', 'Rechtsform', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for typ field
            //
            $column = new TextViewColumn('typ', 'Typ', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for vernehmlassung field
            //
            $column = new TextViewColumn('vernehmlassung', 'Vernehmlassung', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for anzeige_name field
            //
            $column = new TextViewColumn('interessengruppe_id_anzeige_name', 'Interessengruppe', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'interessengruppe.php?operation=view&pk0=%interessengruppe_id%' , '_self');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for anzeige_name field
            //
            $column = new TextViewColumn('interessengruppe2_id_anzeige_name', '2. Interessengruppe', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'interessengruppe.php?operation=view&pk0=%interessengruppe2_id%' , '_self');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for anzeige_name field
            //
            $column = new TextViewColumn('interessengruppe3_id_anzeige_name', '3. Interessengruppe', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'interessengruppe.php?operation=view&pk0=%interessengruppe3_id%' , '_self');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for anzeige_name field
            //
            $column = new TextViewColumn('branche_id_anzeige_name', 'Branche', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'branche.php?operation=view&pk0=%branche_id%' , '_self');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for beschreibung field
            //
            $column = new TextViewColumn('beschreibung', 'Beschreibung', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('organisationDetailEditGrid1branche_beschreibung_handler_view');
            $column->SetReplaceLFByBR(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for homepage field
            //
            $column = new TextViewColumn('homepage', 'Homepage', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('organisationDetailEditGrid1branche_homepage_handler_view');
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, '%homepage%' , '_blank');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for handelsregister_url field
            //
            $column = new TextViewColumn('handelsregister_url', 'Handelsregister Url', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('organisationDetailEditGrid1branche_handelsregister_url_handler_view');
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, '%handelsregister_url%' , '_blank');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('organisationDetailEditGrid1branche_notizen_handler_view');
            $column->SetReplaceLFByBR(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_visa field
            //
            $column = new TextViewColumn('eingabe_abgeschlossen_visa', 'Eingabe Abgeschlossen Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_datum field
            //
            $column = new DateTimeViewColumn('eingabe_abgeschlossen_datum', 'Eingabe Abgeschlossen Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for kontrolliert_visa field
            //
            $column = new TextViewColumn('kontrolliert_visa', 'Kontrolliert Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for kontrolliert_datum field
            //
            $column = new DateTimeViewColumn('kontrolliert_datum', 'Kontrolliert Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for freigabe_visa field
            //
            $column = new TextViewColumn('freigabe_visa', 'Freigabe Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for freigabe_datum field
            //
            $column = new DateTimeViewColumn('freigabe_datum', 'Freigabe Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
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
            // Edit column for name_de field
            //
            $editor = new TextAreaEdit('name_de_edit', 50, 8);
            $editColumn = new CustomEditColumn('Name De', 'name_de', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for name_fr field
            //
            $editor = new TextAreaEdit('name_fr_edit', 50, 8);
            $editColumn = new CustomEditColumn('Name Fr', 'name_fr', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for name_it field
            //
            $editor = new TextAreaEdit('name_it_edit', 50, 8);
            $editColumn = new CustomEditColumn('Name It', 'name_it', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for ort field
            //
            $editor = new TextAreaEdit('ort_edit', 50, 8);
            $editColumn = new CustomEditColumn('Ort', 'ort', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for rechtsform field
            //
            $editor = new ComboBox('rechtsform_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $editor->AddValue('AG', $this->RenderText('AG'));
            $editor->AddValue('GmbH', $this->RenderText('GmbH'));
            $editor->AddValue('Stiftung', $this->RenderText('Stiftung'));
            $editor->AddValue('Verein', $this->RenderText('Verein'));
            $editor->AddValue('Informelle Gruppe', $this->RenderText('Informelle Gruppe'));
            $editor->AddValue('Parlamentarische Gruppe', $this->RenderText('Parlamentarische Gruppe'));
            $editor->AddValue('Oeffentlich-rechtlich', $this->RenderText('Oeffentlich-rechtlich'));
            $editColumn = new CustomEditColumn('Rechtsform', 'rechtsform', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for typ field
            //
            $editor = new CheckBoxGroup('typ_edit');
            $editor->SetDisplayMode(CheckBoxGroup::StackedMode);
            $editor->AddValue('EinzelOrganisation', $this->RenderText('EinzelOrganisation'));
            $editor->AddValue('DachOrganisation', $this->RenderText('DachOrganisation'));
            $editor->AddValue('MitgliedsOrganisation', $this->RenderText('MitgliedsOrganisation'));
            $editor->AddValue('LeistungsErbringer', $this->RenderText('LeistungsErbringer'));
            $editor->AddValue('dezidierteLobby', $this->RenderText('dezidierteLobby'));
            $editColumn = new CustomEditColumn('Typ', 'typ', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for vernehmlassung field
            //
            $editor = new ComboBox('vernehmlassung_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $editor->AddValue('immer', $this->RenderText('immer'));
            $editor->AddValue('punktuell', $this->RenderText('punktuell'));
            $editor->AddValue('nie', $this->RenderText('nie'));
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
            $field = new StringField('anzeige_name_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_mixed');
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
            $field = new StringField('name_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('alias_namen_de');
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
            $lookupDataset->SetOrderBy('anzeige_name', GetOrderTypeAsSQL(otAscending));
            $editColumn = new LookUpEditColumn(
                'Interessengruppe', 
                'interessengruppe_id', 
                $editor, 
                $this->dataset, 'id', 'anzeige_name', $lookupDataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for interessengruppe2_id field
            //
            $editor = new ComboBox('interessengruppe2_id_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                new MyPDOConnectionFactory(),
                GetConnectionOptions(),
                '`v_interessengruppe_simple`');
            $field = new StringField('anzeige_name');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_mixed');
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
            $field = new StringField('name_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('alias_namen_de');
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
            $lookupDataset->SetOrderBy('anzeige_name', GetOrderTypeAsSQL(otAscending));
            $editColumn = new LookUpEditColumn(
                '2. Interessengruppe', 
                'interessengruppe2_id', 
                $editor, 
                $this->dataset, 'id', 'anzeige_name', $lookupDataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for interessengruppe3_id field
            //
            $editor = new ComboBox('interessengruppe3_id_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                new MyPDOConnectionFactory(),
                GetConnectionOptions(),
                '`v_interessengruppe_simple`');
            $field = new StringField('anzeige_name');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_mixed');
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
            $field = new StringField('name_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('alias_namen_de');
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
            $lookupDataset->SetOrderBy('anzeige_name', GetOrderTypeAsSQL(otAscending));
            $editColumn = new LookUpEditColumn(
                '3. Interessengruppe', 
                'interessengruppe3_id', 
                $editor, 
                $this->dataset, 'id', 'anzeige_name', $lookupDataset);
            $editColumn->SetAllowSetToNull(true);
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
            $field = new StringField('anzeige_name_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_mixed');
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
            $field = new StringField('technischer_name');
            $field->SetIsNotNull(true);
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
            $field = new StringField('name_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('angaben_de');
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
            $lookupDataset->SetOrderBy('anzeige_name', GetOrderTypeAsSQL(otAscending));
            $editColumn = new LookUpEditColumn(
                'Branche', 
                'branche_id', 
                $editor, 
                $this->dataset, 'id', 'anzeige_name', $lookupDataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for beschreibung field
            //
            $editor = new TextAreaEdit('beschreibung_edit', 50, 8);
            $editColumn = new CustomEditColumn('Beschreibung', 'beschreibung', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for homepage field
            //
            $editor = new TextAreaEdit('homepage_edit', 50, 8);
            $editColumn = new CustomEditColumn('Homepage', 'homepage', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for handelsregister_url field
            //
            $editor = new TextAreaEdit('handelsregister_url_edit', 50, 8);
            $editColumn = new CustomEditColumn('Handelsregister Url', 'handelsregister_url', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for notizen field
            //
            $editor = new TextAreaEdit('notizen_edit', 50, 8);
            $editColumn = new CustomEditColumn('Notizen', 'notizen', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for eingabe_abgeschlossen_visa field
            //
            $editor = new TextEdit('eingabe_abgeschlossen_visa_edit');
            $editor->SetSize(10);
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Eingabe Abgeschlossen Visa', 'eingabe_abgeschlossen_visa', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for eingabe_abgeschlossen_datum field
            //
            $editor = new DateTimeEdit('eingabe_abgeschlossen_datum_edit', true, 'Y-m-d H:i:s', GetFirstDayOfWeek());
            $editColumn = new CustomEditColumn('Eingabe Abgeschlossen Datum', 'eingabe_abgeschlossen_datum', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for kontrolliert_visa field
            //
            $editor = new TextEdit('kontrolliert_visa_edit');
            $editor->SetSize(10);
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Kontrolliert Visa', 'kontrolliert_visa', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for kontrolliert_datum field
            //
            $editor = new DateTimeEdit('kontrolliert_datum_edit', true, 'Y-m-d H:i:s', GetFirstDayOfWeek());
            $editColumn = new CustomEditColumn('Kontrolliert Datum', 'kontrolliert_datum', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for freigabe_visa field
            //
            $editor = new TextEdit('freigabe_visa_edit');
            $editor->SetSize(10);
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Freigabe Visa', 'freigabe_visa', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for freigabe_datum field
            //
            $editor = new DateTimeEdit('freigabe_datum_edit', true, 'Y-m-d H:i:s', GetFirstDayOfWeek());
            $editColumn = new CustomEditColumn('Freigabe Datum', 'freigabe_datum', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for created_visa field
            //
            $editor = new TextEdit('created_visa_edit');
            $editor->SetSize(10);
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Created Visa', 'created_visa', $editor, $this->dataset);
            $editColumn->SetReadOnly(true);
            $editColumn->SetAllowSetToNull(true);
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
            $editor->SetSize(10);
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Updated Visa', 'updated_visa', $editor, $this->dataset);
            $editColumn->SetReadOnly(true);
            $editColumn->SetAllowSetToNull(true);
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
            // Edit column for name_de field
            //
            $editor = new TextAreaEdit('name_de_edit', 50, 8);
            $editColumn = new CustomEditColumn('Name De', 'name_de', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for name_fr field
            //
            $editor = new TextAreaEdit('name_fr_edit', 50, 8);
            $editColumn = new CustomEditColumn('Name Fr', 'name_fr', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for name_it field
            //
            $editor = new TextAreaEdit('name_it_edit', 50, 8);
            $editColumn = new CustomEditColumn('Name It', 'name_it', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for ort field
            //
            $editor = new TextAreaEdit('ort_edit', 50, 8);
            $editColumn = new CustomEditColumn('Ort', 'ort', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for rechtsform field
            //
            $editor = new ComboBox('rechtsform_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $editor->AddValue('AG', $this->RenderText('AG'));
            $editor->AddValue('GmbH', $this->RenderText('GmbH'));
            $editor->AddValue('Stiftung', $this->RenderText('Stiftung'));
            $editor->AddValue('Verein', $this->RenderText('Verein'));
            $editor->AddValue('Informelle Gruppe', $this->RenderText('Informelle Gruppe'));
            $editor->AddValue('Parlamentarische Gruppe', $this->RenderText('Parlamentarische Gruppe'));
            $editor->AddValue('Oeffentlich-rechtlich', $this->RenderText('Oeffentlich-rechtlich'));
            $editColumn = new CustomEditColumn('Rechtsform', 'rechtsform', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for typ field
            //
            $editor = new CheckBoxGroup('typ_edit');
            $editor->SetDisplayMode(CheckBoxGroup::StackedMode);
            $editor->AddValue('EinzelOrganisation', $this->RenderText('EinzelOrganisation'));
            $editor->AddValue('DachOrganisation', $this->RenderText('DachOrganisation'));
            $editor->AddValue('MitgliedsOrganisation', $this->RenderText('MitgliedsOrganisation'));
            $editor->AddValue('LeistungsErbringer', $this->RenderText('LeistungsErbringer'));
            $editor->AddValue('dezidierteLobby', $this->RenderText('dezidierteLobby'));
            $editColumn = new CustomEditColumn('Typ', 'typ', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for vernehmlassung field
            //
            $editor = new ComboBox('vernehmlassung_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $editor->AddValue('immer', $this->RenderText('immer'));
            $editor->AddValue('punktuell', $this->RenderText('punktuell'));
            $editor->AddValue('nie', $this->RenderText('nie'));
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
            $field = new StringField('anzeige_name_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_mixed');
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
            $field = new StringField('name_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('alias_namen_de');
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
            $lookupDataset->SetOrderBy('anzeige_name', GetOrderTypeAsSQL(otAscending));
            $editColumn = new LookUpEditColumn(
                'Interessengruppe', 
                'interessengruppe_id', 
                $editor, 
                $this->dataset, 'id', 'anzeige_name', $lookupDataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for interessengruppe2_id field
            //
            $editor = new ComboBox('interessengruppe2_id_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                new MyPDOConnectionFactory(),
                GetConnectionOptions(),
                '`v_interessengruppe_simple`');
            $field = new StringField('anzeige_name');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_mixed');
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
            $field = new StringField('name_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('alias_namen_de');
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
            $lookupDataset->SetOrderBy('anzeige_name', GetOrderTypeAsSQL(otAscending));
            $editColumn = new LookUpEditColumn(
                '2. Interessengruppe', 
                'interessengruppe2_id', 
                $editor, 
                $this->dataset, 'id', 'anzeige_name', $lookupDataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for interessengruppe3_id field
            //
            $editor = new ComboBox('interessengruppe3_id_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                new MyPDOConnectionFactory(),
                GetConnectionOptions(),
                '`v_interessengruppe_simple`');
            $field = new StringField('anzeige_name');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_mixed');
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
            $field = new StringField('name_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('alias_namen_de');
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
            $lookupDataset->SetOrderBy('anzeige_name', GetOrderTypeAsSQL(otAscending));
            $editColumn = new LookUpEditColumn(
                '3. Interessengruppe', 
                'interessengruppe3_id', 
                $editor, 
                $this->dataset, 'id', 'anzeige_name', $lookupDataset);
            $editColumn->SetAllowSetToNull(true);
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
            $field = new StringField('anzeige_name_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_mixed');
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
            $field = new StringField('technischer_name');
            $field->SetIsNotNull(true);
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
            $field = new StringField('name_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('angaben_de');
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
            $lookupDataset->SetOrderBy('anzeige_name', GetOrderTypeAsSQL(otAscending));
            $editColumn = new LookUpEditColumn(
                'Branche', 
                'branche_id', 
                $editor, 
                $this->dataset, 'id', 'anzeige_name', $lookupDataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for beschreibung field
            //
            $editor = new TextAreaEdit('beschreibung_edit', 50, 8);
            $editColumn = new CustomEditColumn('Beschreibung', 'beschreibung', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for homepage field
            //
            $editor = new TextAreaEdit('homepage_edit', 50, 8);
            $editColumn = new CustomEditColumn('Homepage', 'homepage', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for handelsregister_url field
            //
            $editor = new TextAreaEdit('handelsregister_url_edit', 50, 8);
            $editColumn = new CustomEditColumn('Handelsregister Url', 'handelsregister_url', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for notizen field
            //
            $editor = new TextAreaEdit('notizen_edit', 50, 8);
            $editColumn = new CustomEditColumn('Notizen', 'notizen', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for eingabe_abgeschlossen_visa field
            //
            $editor = new TextEdit('eingabe_abgeschlossen_visa_edit');
            $editor->SetSize(10);
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Eingabe Abgeschlossen Visa', 'eingabe_abgeschlossen_visa', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for eingabe_abgeschlossen_datum field
            //
            $editor = new DateTimeEdit('eingabe_abgeschlossen_datum_edit', true, 'Y-m-d H:i:s', GetFirstDayOfWeek());
            $editColumn = new CustomEditColumn('Eingabe Abgeschlossen Datum', 'eingabe_abgeschlossen_datum', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for kontrolliert_visa field
            //
            $editor = new TextEdit('kontrolliert_visa_edit');
            $editor->SetSize(10);
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Kontrolliert Visa', 'kontrolliert_visa', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for kontrolliert_datum field
            //
            $editor = new DateTimeEdit('kontrolliert_datum_edit', true, 'Y-m-d H:i:s', GetFirstDayOfWeek());
            $editColumn = new CustomEditColumn('Kontrolliert Datum', 'kontrolliert_datum', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for freigabe_visa field
            //
            $editor = new TextEdit('freigabe_visa_edit');
            $editor->SetSize(10);
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Freigabe Visa', 'freigabe_visa', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for freigabe_datum field
            //
            $editor = new DateTimeEdit('freigabe_datum_edit', true, 'Y-m-d H:i:s', GetFirstDayOfWeek());
            $editColumn = new CustomEditColumn('Freigabe Datum', 'freigabe_datum', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for created_visa field
            //
            $editor = new TextEdit('created_visa_edit');
            $editor->SetSize(10);
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Created Visa', 'created_visa', $editor, $this->dataset);
            $editColumn->SetReadOnly(true);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for created_date field
            //
            $editor = new DateTimeEdit('created_date_edit', true, 'Y-m-d H:i:s', GetFirstDayOfWeek());
            $editColumn = new CustomEditColumn('Created Date', 'created_date', $editor, $this->dataset);
            $editColumn->SetReadOnly(true);
            $editColumn->SetAllowSetToDefault(false); /*afterburner*/ 
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for updated_visa field
            //
            $editor = new TextEdit('updated_visa_edit');
            $editor->SetSize(10);
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Updated Visa', 'updated_visa', $editor, $this->dataset);
            $editColumn->SetReadOnly(true);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for updated_date field
            //
            $editor = new DateTimeEdit('updated_date_edit', true, 'Y-m-d H:i:s', GetFirstDayOfWeek());
            $editColumn = new CustomEditColumn('Updated Date', 'updated_date', $editor, $this->dataset);
            $editColumn->SetReadOnly(true);
            $editColumn->SetAllowSetToDefault(false); /*afterburner*/ 
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
            // View column for typ field
            //
            $column = new TextViewColumn('typ', 'Typ', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for vernehmlassung field
            //
            $column = new TextViewColumn('vernehmlassung', 'Vernehmlassung', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for anzeige_name field
            //
            $column = new TextViewColumn('interessengruppe_id_anzeige_name', 'Interessengruppe', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'interessengruppe.php?operation=view&pk0=%interessengruppe_id%' , '_self');
            $grid->AddPrintColumn($column);
            
            //
            // View column for anzeige_name field
            //
            $column = new TextViewColumn('interessengruppe2_id_anzeige_name', '2. Interessengruppe', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'interessengruppe.php?operation=view&pk0=%interessengruppe2_id%' , '_self');
            $grid->AddPrintColumn($column);
            
            //
            // View column for anzeige_name field
            //
            $column = new TextViewColumn('interessengruppe3_id_anzeige_name', '3. Interessengruppe', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'interessengruppe.php?operation=view&pk0=%interessengruppe3_id%' , '_self');
            $grid->AddPrintColumn($column);
            
            //
            // View column for anzeige_name field
            //
            $column = new TextViewColumn('branche_id_anzeige_name', 'Branche', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'branche.php?operation=view&pk0=%branche_id%' , '_self');
            $grid->AddPrintColumn($column);
            
            //
            // View column for beschreibung field
            //
            $column = new TextViewColumn('beschreibung', 'Beschreibung', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for homepage field
            //
            $column = new TextViewColumn('homepage', 'Homepage', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for handelsregister_url field
            //
            $column = new TextViewColumn('handelsregister_url', 'Handelsregister Url', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_visa field
            //
            $column = new TextViewColumn('eingabe_abgeschlossen_visa', 'Eingabe Abgeschlossen Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_datum field
            //
            $column = new DateTimeViewColumn('eingabe_abgeschlossen_datum', 'Eingabe Abgeschlossen Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for kontrolliert_visa field
            //
            $column = new TextViewColumn('kontrolliert_visa', 'Kontrolliert Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for kontrolliert_datum field
            //
            $column = new DateTimeViewColumn('kontrolliert_datum', 'Kontrolliert Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for freigabe_visa field
            //
            $column = new TextViewColumn('freigabe_visa', 'Freigabe Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for freigabe_datum field
            //
            $column = new DateTimeViewColumn('freigabe_datum', 'Freigabe Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
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
            // View column for typ field
            //
            $column = new TextViewColumn('typ', 'Typ', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for vernehmlassung field
            //
            $column = new TextViewColumn('vernehmlassung', 'Vernehmlassung', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for anzeige_name field
            //
            $column = new TextViewColumn('interessengruppe_id_anzeige_name', 'Interessengruppe', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'interessengruppe.php?operation=view&pk0=%interessengruppe_id%' , '_self');
            $grid->AddExportColumn($column);
            
            //
            // View column for anzeige_name field
            //
            $column = new TextViewColumn('interessengruppe2_id_anzeige_name', '2. Interessengruppe', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'interessengruppe.php?operation=view&pk0=%interessengruppe2_id%' , '_self');
            $grid->AddExportColumn($column);
            
            //
            // View column for anzeige_name field
            //
            $column = new TextViewColumn('interessengruppe3_id_anzeige_name', '3. Interessengruppe', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'interessengruppe.php?operation=view&pk0=%interessengruppe3_id%' , '_self');
            $grid->AddExportColumn($column);
            
            //
            // View column for anzeige_name field
            //
            $column = new TextViewColumn('branche_id_anzeige_name', 'Branche', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'branche.php?operation=view&pk0=%branche_id%' , '_self');
            $grid->AddExportColumn($column);
            
            //
            // View column for beschreibung field
            //
            $column = new TextViewColumn('beschreibung', 'Beschreibung', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for homepage field
            //
            $column = new TextViewColumn('homepage', 'Homepage', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for handelsregister_url field
            //
            $column = new TextViewColumn('handelsregister_url', 'Handelsregister Url', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_visa field
            //
            $column = new TextViewColumn('eingabe_abgeschlossen_visa', 'Eingabe Abgeschlossen Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_datum field
            //
            $column = new DateTimeViewColumn('eingabe_abgeschlossen_datum', 'Eingabe Abgeschlossen Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for kontrolliert_visa field
            //
            $column = new TextViewColumn('kontrolliert_visa', 'Kontrolliert Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for kontrolliert_datum field
            //
            $column = new DateTimeViewColumn('kontrolliert_datum', 'Kontrolliert Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for freigabe_visa field
            //
            $column = new TextViewColumn('freigabe_visa', 'Freigabe Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for freigabe_datum field
            //
            $column = new DateTimeViewColumn('freigabe_datum', 'Freigabe Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
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
            $result = new Grid($this, $this->dataset, 'organisationDetailEditGrid1branche');
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
            //
            // View column for name_de field
            //
            $column = new TextViewColumn('name_de', 'Name De', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'organisationDetailEditGrid1branche_name_de_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for name_fr field
            //
            $column = new TextViewColumn('name_fr', 'Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'organisationDetailEditGrid1branche_name_fr_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for name_it field
            //
            $column = new TextViewColumn('name_it', 'Name It', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'organisationDetailEditGrid1branche_name_it_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for ort field
            //
            $column = new TextViewColumn('ort', 'Ort', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'organisationDetailEditGrid1branche_ort_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for beschreibung field
            //
            $column = new TextViewColumn('beschreibung', 'Beschreibung', $this->dataset);
            $column->SetOrderable(true);
            $column->SetReplaceLFByBR(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'organisationDetailEditGrid1branche_beschreibung_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for homepage field
            //
            $column = new TextViewColumn('homepage', 'Homepage', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, '%homepage%' , '_blank');
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'organisationDetailEditGrid1branche_homepage_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for handelsregister_url field
            //
            $column = new TextViewColumn('handelsregister_url', 'Handelsregister Url', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, '%handelsregister_url%' , '_blank');
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'organisationDetailEditGrid1branche_handelsregister_url_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $column->SetReplaceLFByBR(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'organisationDetailEditGrid1branche_notizen_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for name_de field
            //
            $column = new TextViewColumn('name_de', 'Name De', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'organisationDetailEditGrid1branche_name_de_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for name_fr field
            //
            $column = new TextViewColumn('name_fr', 'Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'organisationDetailEditGrid1branche_name_fr_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for name_it field
            //
            $column = new TextViewColumn('name_it', 'Name It', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'organisationDetailEditGrid1branche_name_it_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for ort field
            //
            $column = new TextViewColumn('ort', 'Ort', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'organisationDetailEditGrid1branche_ort_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for beschreibung field
            //
            $column = new TextViewColumn('beschreibung', 'Beschreibung', $this->dataset);
            $column->SetOrderable(true);
            $column->SetReplaceLFByBR(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'organisationDetailEditGrid1branche_beschreibung_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for homepage field
            //
            $column = new TextViewColumn('homepage', 'Homepage', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, '%homepage%' , '_blank');
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'organisationDetailEditGrid1branche_homepage_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for handelsregister_url field
            //
            $column = new TextViewColumn('handelsregister_url', 'Handelsregister Url', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, '%handelsregister_url%' , '_blank');
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'organisationDetailEditGrid1branche_handelsregister_url_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $column->SetReplaceLFByBR(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'organisationDetailEditGrid1branche_notizen_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            return $result;
        }
        
        public function OpenAdvancedSearchByDefault()
        {
            return false;
        }
    
        protected function DoGetGridHeader()
        {
            return '';
        }    
    }
    // OnGlobalBeforePageExecute event handler
    
    
    // OnBeforePageExecute event handler
    
    
    
    class branchePage extends Page
    {
        protected function DoBeforeCreate()
        {
            $this->dataset = new TableDataset(
                new MyPDOConnectionFactory(),
                GetConnectionOptions(),
                '`branche`');
            $field = new IntegerField('id', null, null, true);
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, true);
            $field = new StringField('name');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('name_fr');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('kommission_id');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('kommission2_id');
            $this->dataset->AddField($field, false);
            $field = new StringField('technischer_name');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('beschreibung');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('beschreibung_fr');
            $this->dataset->AddField($field, false);
            $field = new StringField('angaben');
            $this->dataset->AddField($field, false);
            $field = new StringField('angaben_fr');
            $this->dataset->AddField($field, false);
            $field = new StringField('farbcode');
            $this->dataset->AddField($field, false);
            $field = new StringField('symbol_abs');
            $this->dataset->AddField($field, false);
            $field = new StringField('symbol_rel');
            $this->dataset->AddField($field, false);
            $field = new StringField('symbol_klein_rel');
            $this->dataset->AddField($field, false);
            $field = new StringField('symbol_dateiname_wo_ext');
            $this->dataset->AddField($field, false);
            $field = new StringField('symbol_dateierweiterung');
            $this->dataset->AddField($field, false);
            $field = new StringField('symbol_dateiname');
            $this->dataset->AddField($field, false);
            $field = new StringField('symbol_mime_type');
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
            $this->dataset->AddLookupField('kommission_id', 'v_kommission', new IntegerField('id'), new StringField('anzeige_name_mixed', 'kommission_id_anzeige_name_mixed', 'kommission_id_anzeige_name_mixed_v_kommission'), 'kommission_id_anzeige_name_mixed_v_kommission');
            $this->dataset->AddLookupField('kommission2_id', 'v_kommission', new IntegerField('id'), new StringField('anzeige_name_mixed', 'kommission2_id_anzeige_name_mixed', 'kommission2_id_anzeige_name_mixed_v_kommission'), 'kommission2_id_anzeige_name_mixed_v_kommission');
        }
    
        protected function DoPrepare() {
    
        }
    
        protected function CreatePageNavigator()
        {
            $result = new CompositePageNavigator($this);
            
            $partitionNavigator = new PageNavigator('pnav', $this, $this->dataset);
            $partitionNavigator->SetRowsPerPage(5);
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
            $grid->SearchControl = new SimpleSearch('branchessearch', $this->dataset,
                array('id', 'name', 'name_fr', 'kommission_id_anzeige_name_mixed', 'kommission2_id_anzeige_name_mixed', 'technischer_name', 'beschreibung', 'beschreibung_fr', 'angaben', 'angaben_fr', 'notizen'),
                array($this->RenderText('Id'), $this->RenderText('Name'), $this->RenderText('Name Fr'), $this->RenderText('1. Kommission (NR)'), $this->RenderText('2. Kommission (SR)'), $this->RenderText('Technischer Name'), $this->RenderText('Beschreibung'), $this->RenderText('Beschreibung Fr'), $this->RenderText('Angaben'), $this->RenderText('Angaben Fr'), $this->RenderText('Notizen')),
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
            $this->AdvancedSearchControl = new AdvancedSearchControl('brancheasearch', $this->dataset, $this->GetLocalizerCaptions(), $this->GetColumnVariableContainer(), $this->CreateLinkBuilder());
            $this->AdvancedSearchControl->setTimerInterval(1000);
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('id', $this->RenderText('Id')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('name', $this->RenderText('Name')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('name_fr', $this->RenderText('Name Fr')));
            
            $lookupDataset = new TableDataset(
                new MyPDOConnectionFactory(),
                GetConnectionOptions(),
                '`v_kommission`');
            $field = new StringField('anzeige_name');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_mixed');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('id');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('abkuerzung');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('abkuerzung_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('name');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('name_fr');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('rat_id');
            $lookupDataset->AddField($field, false);
            $field = new StringField('typ');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('art');
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung');
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('sachbereiche');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('sachbereiche_fr');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_nationalraete');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_staenderaete');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('mutter_kommission_id');
            $lookupDataset->AddField($field, false);
            $field = new DateField('von');
            $lookupDataset->AddField($field, false);
            $field = new DateField('bis');
            $lookupDataset->AddField($field, false);
            $field = new StringField('parlament_url');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('parlament_id');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('parlament_committee_number');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('parlament_subcommittee_number');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('parlament_type_code');
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
            $field = new StringField('name_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('abkuerzung_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung_de');
            $lookupDataset->AddField($field, false);
            $field = new StringField('sachbereiche_de');
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
            $lookupDataset->SetOrderBy('anzeige_name_mixed', GetOrderTypeAsSQL(otAscending));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateLookupSearchInput('kommission_id', $this->RenderText('1. Kommission (NR)'), $lookupDataset, 'id', 'anzeige_name_mixed', false, 8));
            
            $lookupDataset = new TableDataset(
                new MyPDOConnectionFactory(),
                GetConnectionOptions(),
                '`v_kommission`');
            $field = new StringField('anzeige_name');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_mixed');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('id');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('abkuerzung');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('abkuerzung_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('name');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('name_fr');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('rat_id');
            $lookupDataset->AddField($field, false);
            $field = new StringField('typ');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('art');
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung');
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('sachbereiche');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('sachbereiche_fr');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_nationalraete');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_staenderaete');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('mutter_kommission_id');
            $lookupDataset->AddField($field, false);
            $field = new DateField('von');
            $lookupDataset->AddField($field, false);
            $field = new DateField('bis');
            $lookupDataset->AddField($field, false);
            $field = new StringField('parlament_url');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('parlament_id');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('parlament_committee_number');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('parlament_subcommittee_number');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('parlament_type_code');
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
            $field = new StringField('name_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('abkuerzung_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung_de');
            $lookupDataset->AddField($field, false);
            $field = new StringField('sachbereiche_de');
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
            $lookupDataset->SetOrderBy('anzeige_name_mixed', GetOrderTypeAsSQL(otAscending));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateLookupSearchInput('kommission2_id', $this->RenderText('2. Kommission (SR)'), $lookupDataset, 'id', 'anzeige_name_mixed', false, 8));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('technischer_name', $this->RenderText('Technischer Name')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('beschreibung', $this->RenderText('Beschreibung')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('beschreibung_fr', $this->RenderText('Beschreibung Fr')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('angaben', $this->RenderText('Angaben')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('angaben_fr', $this->RenderText('Angaben Fr')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('farbcode', $this->RenderText('Farbcode')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('notizen', $this->RenderText('Notizen')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('eingabe_abgeschlossen_visa', $this->RenderText('Eingabe Abgeschlossen Visa')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateDateTimeSearchInput('eingabe_abgeschlossen_datum', $this->RenderText('Eingabe Abgeschlossen Datum'), 'd.m.Y H:i:s'));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('kontrolliert_visa', $this->RenderText('Kontrolliert Visa')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateDateTimeSearchInput('kontrolliert_datum', $this->RenderText('Kontrolliert Datum'), 'd.m.Y H:i:s'));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('freigabe_visa', $this->RenderText('Freigabe Visa')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateDateTimeSearchInput('freigabe_datum', $this->RenderText('Freigabe Datum'), 'd.m.Y H:i:s'));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('created_visa', $this->RenderText('Created Visa')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateDateTimeSearchInput('created_date', $this->RenderText('Created Date'), 'd.m.Y H:i:s'));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('updated_visa', $this->RenderText('Updated Visa')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateDateTimeSearchInput('updated_date', $this->RenderText('Updated Date'), 'd.m.Y H:i:s'));
        }
    
        protected function AddOperationsColumns(Grid $grid)
        {
            $actionsBandName = 'actions';
            $grid->AddBandToBegin($actionsBandName, $this->GetLocalizerCaptions()->GetMessageString('Actions'), true);
            if ($this->GetSecurityInfo()->HasViewGrant())
            {
                $column = new RowOperationByLinkColumn($this->GetLocalizerCaptions()->GetMessageString('View'), OPERATION_VIEW, $this->dataset);
                $grid->AddViewColumn($column, $actionsBandName);
                $column->SetImagePath('images/view_action.png');
            }
            if ($this->GetSecurityInfo()->HasEditGrant())
            {
                $column = new RowOperationByLinkColumn($this->GetLocalizerCaptions()->GetMessageString('Edit'), OPERATION_EDIT, $this->dataset);
                $grid->AddViewColumn($column, $actionsBandName);
                $column->SetImagePath('images/edit_action.png');
                $column->OnShow->AddListener('ShowEditButtonHandler', $this);
            }
            if ($this->GetSecurityInfo()->HasDeleteGrant())
            {
                $column = new RowOperationByLinkColumn($this->GetLocalizerCaptions()->GetMessageString('Delete'), OPERATION_DELETE, $this->dataset);
                $grid->AddViewColumn($column, $actionsBandName);
                $column->SetImagePath('images/delete_action.png');
                $column->OnShow->AddListener('ShowDeleteButtonHandler', $this);
            $column->SetAdditionalAttribute("data-modal-delete", "true");
            $column->SetAdditionalAttribute("data-delete-handler-name", $this->GetModalGridDeleteHandler());
            }
            if ($this->GetSecurityInfo()->HasAddGrant())
            {
                $column = new RowOperationByLinkColumn($this->GetLocalizerCaptions()->GetMessageString('Copy'), OPERATION_COPY, $this->dataset);
                $grid->AddViewColumn($column, $actionsBandName);
                $column->SetImagePath('images/copy_action.png');
            }
        }
    
        protected function AddFieldColumns(Grid $grid)
        {
            if (GetCurrentUserGrantForDataSource('branche.interessengruppe')->HasViewGrant())
            {
              //
            // View column for interessengruppeDetailView0branche detail
            //
            $column = new DetailColumn(array('id'), 'detail0branche', 'interessengruppeDetailEdit0branche_handler', 'interessengruppeDetailView0branche_handler', $this->dataset, 'Interessengruppe', $this->RenderText('Interessengruppe'));
              $grid->AddViewColumn($column);
            }
            
            if (GetCurrentUserGrantForDataSource('branche.organisation')->HasViewGrant())
            {
              //
            // View column for organisationDetailView1branche detail
            //
            $column = new DetailColumn(array('id'), 'detail1branche', 'organisationDetailEdit1branche_handler', 'organisationDetailView1branche_handler', $this->dataset, 'Organisation', $this->RenderText('Organisation'));
              $grid->AddViewColumn($column);
            }
            
            //
            // View column for id field
            //
            $column = new TextViewColumn('id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'http://lobbywatch.ch/de/daten/branche/%id%' , '_blank');
            $column->SetDescription($this->RenderText('Technischer Schlüssel der Branche.  Der Link zeigt auf den Eintrag in der Lobbywatch.ch Webseite.'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('name', 'Name', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('brancheGrid_name_handler_list');
            $column = new DivTagViewColumnDecorator($column);
            $column->Bold = true;
            $column->SetDescription($this->RenderText('Name der Branche, z.B. Gesundheit, Energie'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for name_fr field
            //
            $column = new TextViewColumn('name_fr', 'Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('brancheGrid_name_fr_handler_list');
            $column->SetDescription($this->RenderText('Französischer Name der Branche, z.B. Gesundheit, Energie'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for anzeige_name_mixed field
            //
            $column = new TextViewColumn('kommission_id_anzeige_name_mixed', '1. Kommission (NR)', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'kommission.php?operation=view&pk0=%kommission_id%' , '_self');
            $column->SetDescription($this->RenderText('Zuständige Kommission im Parlament'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for anzeige_name_mixed field
            //
            $column = new TextViewColumn('kommission2_id_anzeige_name_mixed', '2. Kommission (SR)', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'kommission.php?operation=view&pk0=%kommission2_id%' , '');
            $column->SetDescription($this->RenderText('Zuständige Kommission im Ständerat'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for technischer_name field
            //
            $column = new TextViewColumn('technischer_name', 'Technischer Name', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Technischer Name für Branche. Keine Sonderzeichen sind erlaubt, nur Kleinbuchstaben, "_" und "-". Wird z.B. für das finden des Branchensymboles gebraucht.'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for beschreibung field
            //
            $column = new TextViewColumn('beschreibung', 'Beschreibung', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('brancheGrid_beschreibung_handler_list');
            $column->SetDescription($this->RenderText('Beschreibung der Branche'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for beschreibung_fr field
            //
            $column = new TextViewColumn('beschreibung_fr', 'Beschreibung Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('brancheGrid_beschreibung_fr_handler_list');
            $column->SetDescription($this->RenderText('Französische Beschreibung der Branche'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for angaben field
            //
            $column = new TextViewColumn('angaben', 'Angaben', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('brancheGrid_angaben_handler_list');
            $column->SetDescription($this->RenderText('Angaben zur Branche'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for angaben_fr field
            //
            $column = new TextViewColumn('angaben_fr', 'Angaben Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('brancheGrid_angaben_fr_handler_list');
            $column->SetDescription($this->RenderText('Angaben zur Branche auf Französisch'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for farbcode field
            //
            $column = new TextViewColumn('farbcode', 'Farbcode', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('HTML-Farbcode, z.B. red oder #23FF23'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for symbol_klein_rel field
            //
            $column = new ExternalImageColumn('symbol_klein_rel', 'Symbol klein', $this->dataset, '%symbol_dateiname%');
            $column->SetSourcePrefix('' . $GLOBALS["rel_files_url"] /*afterburner*/  . '/');
            $column->SetSourceSuffix('');
            $column->SetDescription($this->RenderText('Kleines Symbolbild (Icon) der Branche, relativer Pfad, kann mit ' . $GLOBALS["rel_files_url"] /*afterburner*/  . '/ zu URL ergänzt werden'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('brancheGrid_notizen_handler_list');
            $column->SetReplaceLFByBR(true);
            $column->SetDescription($this->RenderText('Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_visa field
            //
            $column = new TextViewColumn('eingabe_abgeschlossen_visa', 'Eingabe Abgeschlossen Visa', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Kürzel der Person, welche die Eingabe abgeschlossen hat.'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_datum field
            //
            $column = new DateTimeViewColumn('eingabe_abgeschlossen_datum', 'Eingabe Abgeschlossen Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for kontrolliert_visa field
            //
            $column = new TextViewColumn('kontrolliert_visa', 'Kontrolliert Visa', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Kürzel der Person, welche die Eingabe kontrolliert hat.'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for kontrolliert_datum field
            //
            $column = new DateTimeViewColumn('kontrolliert_datum', 'Kontrolliert Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for freigabe_visa field
            //
            $column = new TextViewColumn('freigabe_visa', 'Freigabe Visa', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Freigabe von (Freigabe = Daten sind fertig)'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for freigabe_datum field
            //
            $column = new DateTimeViewColumn('freigabe_datum', 'Freigabe Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Freigabedatum (Freigabe = Daten sind fertig)'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for created_visa field
            //
            $column = new TextViewColumn('created_visa', 'Created Visa', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Erstellt von'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for created_date field
            //
            $column = new DateTimeViewColumn('created_date', 'Created Date', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Erstellt am'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for updated_visa field
            //
            $column = new TextViewColumn('updated_visa', 'Updated Visa', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Abgeändert von'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for updated_date field
            //
            $column = new DateTimeViewColumn('updated_date', 'Updated Date', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Abgeändert am'));
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
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'http://lobbywatch.ch/de/daten/branche/%id%' , '_blank');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('name', 'Name', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('brancheGrid_name_handler_view');
            $column = new DivTagViewColumnDecorator($column);
            $column->Bold = true;
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for name_fr field
            //
            $column = new TextViewColumn('name_fr', 'Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('brancheGrid_name_fr_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for anzeige_name_mixed field
            //
            $column = new TextViewColumn('kommission_id_anzeige_name_mixed', '1. Kommission (NR)', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'kommission.php?operation=view&pk0=%kommission_id%' , '_self');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for anzeige_name_mixed field
            //
            $column = new TextViewColumn('kommission2_id_anzeige_name_mixed', '2. Kommission (SR)', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'kommission.php?operation=view&pk0=%kommission2_id%' , '');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for technischer_name field
            //
            $column = new TextViewColumn('technischer_name', 'Technischer Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for beschreibung field
            //
            $column = new TextViewColumn('beschreibung', 'Beschreibung', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('brancheGrid_beschreibung_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for beschreibung_fr field
            //
            $column = new TextViewColumn('beschreibung_fr', 'Beschreibung Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('brancheGrid_beschreibung_fr_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for angaben field
            //
            $column = new TextViewColumn('angaben', 'Angaben', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('brancheGrid_angaben_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for angaben_fr field
            //
            $column = new TextViewColumn('angaben_fr', 'Angaben Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('brancheGrid_angaben_fr_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for farbcode field
            //
            $column = new TextViewColumn('farbcode', 'Farbcode', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for symbol_klein_rel field
            //
            $column = new ExternalImageColumn('symbol_klein_rel', 'Symbol klein', $this->dataset, '%symbol_dateiname%');
            $column->SetSourcePrefix('' . $GLOBALS["rel_files_url"] /*afterburner*/  . '/');
            $column->SetSourceSuffix('');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('brancheGrid_notizen_handler_view');
            $column->SetReplaceLFByBR(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_visa field
            //
            $column = new TextViewColumn('eingabe_abgeschlossen_visa', 'Eingabe Abgeschlossen Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_datum field
            //
            $column = new DateTimeViewColumn('eingabe_abgeschlossen_datum', 'Eingabe Abgeschlossen Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for kontrolliert_visa field
            //
            $column = new TextViewColumn('kontrolliert_visa', 'Kontrolliert Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for kontrolliert_datum field
            //
            $column = new DateTimeViewColumn('kontrolliert_datum', 'Kontrolliert Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for freigabe_visa field
            //
            $column = new TextViewColumn('freigabe_visa', 'Freigabe Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for freigabe_datum field
            //
            $column = new DateTimeViewColumn('freigabe_datum', 'Freigabe Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
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
            
            //
            // View column for symbol_abs field
            //
            $column = new ExternalImageColumn('symbol_abs', 'Symbol', $this->dataset, '%symbol_dateiname%');
            $column->SetSourcePrefix('');
            $column->SetSourceSuffix('');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for symbol_rel field
            //
            $column = new ExternalImageColumn('symbol_rel', 'Symbol', $this->dataset, '%symbol_dateiname%');
            $column->SetSourcePrefix('' . $GLOBALS["rel_files_url"] /*afterburner*/  . '/');
            $column->SetSourceSuffix('');
            $grid->AddSingleRecordViewColumn($column);
        }
    
        protected function AddEditColumns(Grid $grid)
        {
            //
            // Edit column for name field
            //
            $editor = new TextEdit('name_edit');
            $editor->SetSize(100);
            $editor->SetMaxLength(100);
            $editColumn = new CustomEditColumn('Name', 'name', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for name_fr field
            //
            $editor = new TextEdit('name_fr_edit');
            $editor->SetSize(100);
            $editor->SetMaxLength(100);
            $editColumn = new CustomEditColumn('Name Fr', 'name_fr', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for kommission_id field
            //
            $editor = new ComboBox('kommission_id_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                new MyPDOConnectionFactory(),
                GetConnectionOptions(),
                '`v_kommission`');
            $field = new StringField('anzeige_name');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_mixed');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('id');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('abkuerzung');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('abkuerzung_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('name');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('name_fr');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('rat_id');
            $lookupDataset->AddField($field, false);
            $field = new StringField('typ');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('art');
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung');
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('sachbereiche');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('sachbereiche_fr');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_nationalraete');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_staenderaete');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('mutter_kommission_id');
            $lookupDataset->AddField($field, false);
            $field = new DateField('von');
            $lookupDataset->AddField($field, false);
            $field = new DateField('bis');
            $lookupDataset->AddField($field, false);
            $field = new StringField('parlament_url');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('parlament_id');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('parlament_committee_number');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('parlament_subcommittee_number');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('parlament_type_code');
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
            $field = new StringField('name_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('abkuerzung_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung_de');
            $lookupDataset->AddField($field, false);
            $field = new StringField('sachbereiche_de');
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
            $lookupDataset->SetOrderBy('anzeige_name_mixed', GetOrderTypeAsSQL(otAscending));
            $editColumn = new LookUpEditColumn(
                '1. Kommission (NR)', 
                'kommission_id', 
                $editor, 
                $this->dataset, 'id', 'anzeige_name_mixed', $lookupDataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for kommission2_id field
            //
            $editor = new ComboBox('kommission2_id_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                new MyPDOConnectionFactory(),
                GetConnectionOptions(),
                '`v_kommission`');
            $field = new StringField('anzeige_name');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_mixed');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('id');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('abkuerzung');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('abkuerzung_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('name');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('name_fr');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('rat_id');
            $lookupDataset->AddField($field, false);
            $field = new StringField('typ');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('art');
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung');
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('sachbereiche');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('sachbereiche_fr');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_nationalraete');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_staenderaete');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('mutter_kommission_id');
            $lookupDataset->AddField($field, false);
            $field = new DateField('von');
            $lookupDataset->AddField($field, false);
            $field = new DateField('bis');
            $lookupDataset->AddField($field, false);
            $field = new StringField('parlament_url');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('parlament_id');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('parlament_committee_number');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('parlament_subcommittee_number');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('parlament_type_code');
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
            $field = new StringField('name_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('abkuerzung_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung_de');
            $lookupDataset->AddField($field, false);
            $field = new StringField('sachbereiche_de');
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
            $lookupDataset->SetOrderBy('anzeige_name_mixed', GetOrderTypeAsSQL(otAscending));
            $editColumn = new LookUpEditColumn(
                '2. Kommission (SR)', 
                'kommission2_id', 
                $editor, 
                $this->dataset, 'id', 'anzeige_name_mixed', $lookupDataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for technischer_name field
            //
            $editor = new TextEdit('technischer_name_edit');
            $editor->SetSize(30);
            $editor->SetMaxLength(30);
            $editColumn = new CustomEditColumn('Technischer Name', 'technischer_name', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new CustomRegExpValidator('/[a-z1-9_\-]/', StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RegExpValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for beschreibung field
            //
            $editor = new TextAreaEdit('beschreibung_edit', 50, 8);
            $editColumn = new CustomEditColumn('Beschreibung', 'beschreibung', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for beschreibung_fr field
            //
            $editor = new TextAreaEdit('beschreibung_fr_edit', 50, 8);
            $editColumn = new CustomEditColumn('Beschreibung Fr', 'beschreibung_fr', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for angaben field
            //
            $editor = new TextAreaEdit('angaben_edit', 50, 8);
            $editColumn = new CustomEditColumn('Angaben', 'angaben', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for angaben_fr field
            //
            $editor = new TextAreaEdit('angaben_fr_edit', 50, 8);
            $editColumn = new CustomEditColumn('Angaben Fr', 'angaben_fr', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for farbcode field
            //
            $editor = new TextEdit('farbcode_edit');
            $editor->SetSize(15);
            $editor->SetMaxLength(15);
            $editColumn = new CustomEditColumn('Farbcode', 'farbcode', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for symbol_klein_rel field
            //
            $editor = new ImageUploader('symbol_klein_rel_edit');
            $editor->SetShowImage(true);
            $editColumn = new UploadFileToFolderColumn('Symbol klein', 'symbol_klein_rel', $editor, $this->dataset, false, false, '' . $GLOBALS["public_files_dir_abs"] /*afterburner*/  . '/branche_symbole/%id%');
            $editColumn->OnCustomFileName->AddListener('symbol_klein_rel_GenerateFileName_edit', $this);
            $editColumn->SetReplaceUploadedFileIfExist(true);
            $editColumn->SetReadOnly(true);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for notizen field
            //
            $editor = new TextAreaEdit('notizen_edit', 50, 8);
            $editColumn = new CustomEditColumn('Notizen', 'notizen', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for eingabe_abgeschlossen_visa field
            //
            $editor = new TextEdit('eingabe_abgeschlossen_visa_edit');
            $editor->SetSize(10);
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Eingabe Abgeschlossen Visa', 'eingabe_abgeschlossen_visa', $editor, $this->dataset);
            $editColumn->SetReadOnly(true);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for eingabe_abgeschlossen_datum field
            //
            $editor = new DateTimeEdit('eingabe_abgeschlossen_datum_edit', true, 'd.m.Y H:i:s', GetFirstDayOfWeek());
            $editColumn = new CustomEditColumn('Eingabe Abgeschlossen Datum', 'eingabe_abgeschlossen_datum', $editor, $this->dataset);
            $editColumn->SetReadOnly(true);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for kontrolliert_visa field
            //
            $editor = new TextEdit('kontrolliert_visa_edit');
            $editor->SetSize(10);
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Kontrolliert Visa', 'kontrolliert_visa', $editor, $this->dataset);
            $editColumn->SetReadOnly(true);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for kontrolliert_datum field
            //
            $editor = new DateTimeEdit('kontrolliert_datum_edit', true, 'd.m.Y H:i:s', GetFirstDayOfWeek());
            $editColumn = new CustomEditColumn('Kontrolliert Datum', 'kontrolliert_datum', $editor, $this->dataset);
            $editColumn->SetReadOnly(true);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for freigabe_visa field
            //
            $editor = new TextEdit('freigabe_visa_edit');
            $editor->SetSize(10);
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Freigabe Visa', 'freigabe_visa', $editor, $this->dataset);
            $editColumn->SetReadOnly(true);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for freigabe_datum field
            //
            $editor = new DateTimeEdit('freigabe_datum_edit', true, 'd.m.Y H:i:s', GetFirstDayOfWeek());
            $editColumn = new CustomEditColumn('Freigabe Datum', 'freigabe_datum', $editor, $this->dataset);
            $editColumn->SetReadOnly(true);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for created_visa field
            //
            $editor = new TextEdit('created_visa_edit');
            $editor->SetSize(10);
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Created Visa', 'created_visa', $editor, $this->dataset);
            $editColumn->SetReadOnly(true);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for created_date field
            //
            $editor = new DateTimeEdit('created_date_edit', true, 'd.m.Y H:i:s', GetFirstDayOfWeek());
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
            $editor->SetSize(10);
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Updated Visa', 'updated_visa', $editor, $this->dataset);
            $editColumn->SetReadOnly(true);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for updated_date field
            //
            $editor = new DateTimeEdit('updated_date_edit', true, 'd.m.Y H:i:s', GetFirstDayOfWeek());
            $editColumn = new CustomEditColumn('Updated Date', 'updated_date', $editor, $this->dataset);
            $editColumn->SetReadOnly(true);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for symbol_abs field
            //
            $editor = new ImageUploader('symbol_abs_edit');
            $editor->SetShowImage(true);
            $editColumn = new UploadFileToFolderColumn('Symbol', 'symbol_abs', $editor, $this->dataset, false, false, '' . $GLOBALS["public_files_dir_abs"] /*afterburner*/  . '/branche_symbole/%id%');
            $editColumn->OnCustomFileName->AddListener('symbol_abs_GenerateFileName_edit', $this);
            $editColumn->SetReplaceUploadedFileIfExist(true);
            $editColumn->SetGenerationImageThumbnails(
                'symbol_klein_rel',
                '' . $GLOBALS["public_files_dir_abs"] /*afterburner*/  . '/branche_symbole/%id%',
                Delegate::CreateFromMethod($this, 'symbol_abs_Thumbnail_GenerateFileName_edit'),
                new ImageFitByHeightResizeFilter(100)
            );
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for symbol_rel field
            //
            $editor = new ImageUploader('symbol_rel_edit');
            $editor->SetShowImage(true);
            $editColumn = new UploadFileToFolderColumn('Symbol', 'symbol_rel', $editor, $this->dataset, false, false, '' . $GLOBALS["public_files_dir_abs"] /*afterburner*/  . '/branche_symbole/%id%');
            $editColumn->OnCustomFileName->AddListener('symbol_rel_GenerateFileName_edit', $this);
            $editColumn->SetReplaceUploadedFileIfExist(true);
            $editColumn->SetReadOnly(true);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
        }
    
        protected function AddInsertColumns(Grid $grid)
        {
            //
            // Edit column for name field
            //
            $editor = new TextEdit('name_edit');
            $editor->SetSize(100);
            $editor->SetMaxLength(100);
            $editColumn = new CustomEditColumn('Name', 'name', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for name_fr field
            //
            $editor = new TextEdit('name_fr_edit');
            $editor->SetSize(100);
            $editor->SetMaxLength(100);
            $editColumn = new CustomEditColumn('Name Fr', 'name_fr', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for kommission_id field
            //
            $editor = new ComboBox('kommission_id_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                new MyPDOConnectionFactory(),
                GetConnectionOptions(),
                '`v_kommission`');
            $field = new StringField('anzeige_name');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_mixed');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('id');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('abkuerzung');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('abkuerzung_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('name');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('name_fr');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('rat_id');
            $lookupDataset->AddField($field, false);
            $field = new StringField('typ');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('art');
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung');
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('sachbereiche');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('sachbereiche_fr');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_nationalraete');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_staenderaete');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('mutter_kommission_id');
            $lookupDataset->AddField($field, false);
            $field = new DateField('von');
            $lookupDataset->AddField($field, false);
            $field = new DateField('bis');
            $lookupDataset->AddField($field, false);
            $field = new StringField('parlament_url');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('parlament_id');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('parlament_committee_number');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('parlament_subcommittee_number');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('parlament_type_code');
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
            $field = new StringField('name_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('abkuerzung_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung_de');
            $lookupDataset->AddField($field, false);
            $field = new StringField('sachbereiche_de');
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
            $lookupDataset->SetOrderBy('anzeige_name_mixed', GetOrderTypeAsSQL(otAscending));
            $editColumn = new LookUpEditColumn(
                '1. Kommission (NR)', 
                'kommission_id', 
                $editor, 
                $this->dataset, 'id', 'anzeige_name_mixed', $lookupDataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for kommission2_id field
            //
            $editor = new ComboBox('kommission2_id_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                new MyPDOConnectionFactory(),
                GetConnectionOptions(),
                '`v_kommission`');
            $field = new StringField('anzeige_name');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_mixed');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('id');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('abkuerzung');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('abkuerzung_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('name');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('name_fr');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('rat_id');
            $lookupDataset->AddField($field, false);
            $field = new StringField('typ');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('art');
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung');
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('sachbereiche');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('sachbereiche_fr');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_nationalraete');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_staenderaete');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('mutter_kommission_id');
            $lookupDataset->AddField($field, false);
            $field = new DateField('von');
            $lookupDataset->AddField($field, false);
            $field = new DateField('bis');
            $lookupDataset->AddField($field, false);
            $field = new StringField('parlament_url');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('parlament_id');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('parlament_committee_number');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('parlament_subcommittee_number');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('parlament_type_code');
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
            $field = new StringField('name_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('abkuerzung_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung_de');
            $lookupDataset->AddField($field, false);
            $field = new StringField('sachbereiche_de');
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
            $lookupDataset->SetOrderBy('anzeige_name_mixed', GetOrderTypeAsSQL(otAscending));
            $editColumn = new LookUpEditColumn(
                '2. Kommission (SR)', 
                'kommission2_id', 
                $editor, 
                $this->dataset, 'id', 'anzeige_name_mixed', $lookupDataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for technischer_name field
            //
            $editor = new TextEdit('technischer_name_edit');
            $editor->SetSize(30);
            $editor->SetMaxLength(30);
            $editColumn = new CustomEditColumn('Technischer Name', 'technischer_name', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new CustomRegExpValidator('/[a-z1-9_\-]/', StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RegExpValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for beschreibung field
            //
            $editor = new TextAreaEdit('beschreibung_edit', 50, 8);
            $editColumn = new CustomEditColumn('Beschreibung', 'beschreibung', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for beschreibung_fr field
            //
            $editor = new TextAreaEdit('beschreibung_fr_edit', 50, 8);
            $editColumn = new CustomEditColumn('Beschreibung Fr', 'beschreibung_fr', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for angaben field
            //
            $editor = new TextAreaEdit('angaben_edit', 50, 8);
            $editColumn = new CustomEditColumn('Angaben', 'angaben', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for angaben_fr field
            //
            $editor = new TextAreaEdit('angaben_fr_edit', 50, 8);
            $editColumn = new CustomEditColumn('Angaben Fr', 'angaben_fr', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for farbcode field
            //
            $editor = new TextEdit('farbcode_edit');
            $editor->SetSize(15);
            $editor->SetMaxLength(15);
            $editColumn = new CustomEditColumn('Farbcode', 'farbcode', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for notizen field
            //
            $editor = new TextAreaEdit('notizen_edit', 50, 8);
            $editColumn = new CustomEditColumn('Notizen', 'notizen', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for symbol_abs field
            //
            $editor = new ImageUploader('symbol_abs_edit');
            $editor->SetShowImage(true);
            $editColumn = new UploadFileToFolderColumn('Symbol', 'symbol_abs', $editor, $this->dataset, false, false, '' . $GLOBALS["public_files_dir_abs"] /*afterburner*/  . '/branche_symbole/%id%');
            $editColumn->OnCustomFileName->AddListener('symbol_abs_GenerateFileName_insert', $this);
            $editColumn->SetReplaceUploadedFileIfExist(true);
            $editColumn->SetGenerationImageThumbnails(
                'symbol_klein_rel',
                '' . $GLOBALS["public_files_dir_abs"] /*afterburner*/  . '/branche_symbole/%id%',
                Delegate::CreateFromMethod($this, 'symbol_abs_Thumbnail_GenerateFileName_insert'),
                new ImageFitByHeightResizeFilter(100)
            );
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            if ($this->GetSecurityInfo()->HasAddGrant())
            {
                $grid->SetShowAddButton(true);
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
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'http://lobbywatch.ch/de/daten/branche/%id%' , '_blank');
            $grid->AddPrintColumn($column);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('name', 'Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for name_fr field
            //
            $column = new TextViewColumn('name_fr', 'Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for anzeige_name_mixed field
            //
            $column = new TextViewColumn('kommission_id_anzeige_name_mixed', '1. Kommission (NR)', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'kommission.php?operation=view&pk0=%kommission_id%' , '_self');
            $grid->AddPrintColumn($column);
            
            //
            // View column for anzeige_name_mixed field
            //
            $column = new TextViewColumn('kommission2_id_anzeige_name_mixed', '2. Kommission (SR)', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'kommission.php?operation=view&pk0=%kommission2_id%' , '');
            $grid->AddPrintColumn($column);
            
            //
            // View column for technischer_name field
            //
            $column = new TextViewColumn('technischer_name', 'Technischer Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for beschreibung field
            //
            $column = new TextViewColumn('beschreibung', 'Beschreibung', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for beschreibung_fr field
            //
            $column = new TextViewColumn('beschreibung_fr', 'Beschreibung Fr', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for angaben field
            //
            $column = new TextViewColumn('angaben', 'Angaben', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for angaben_fr field
            //
            $column = new TextViewColumn('angaben_fr', 'Angaben Fr', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for farbcode field
            //
            $column = new TextViewColumn('farbcode', 'Farbcode', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_visa field
            //
            $column = new TextViewColumn('eingabe_abgeschlossen_visa', 'Eingabe Abgeschlossen Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_datum field
            //
            $column = new DateTimeViewColumn('eingabe_abgeschlossen_datum', 'Eingabe Abgeschlossen Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for kontrolliert_visa field
            //
            $column = new TextViewColumn('kontrolliert_visa', 'Kontrolliert Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for kontrolliert_datum field
            //
            $column = new DateTimeViewColumn('kontrolliert_datum', 'Kontrolliert Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for freigabe_visa field
            //
            $column = new TextViewColumn('freigabe_visa', 'Freigabe Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for freigabe_datum field
            //
            $column = new DateTimeViewColumn('freigabe_datum', 'Freigabe Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
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
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'http://lobbywatch.ch/de/daten/branche/%id%' , '_blank');
            $grid->AddExportColumn($column);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('name', 'Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for name_fr field
            //
            $column = new TextViewColumn('name_fr', 'Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for anzeige_name_mixed field
            //
            $column = new TextViewColumn('kommission_id_anzeige_name_mixed', '1. Kommission (NR)', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'kommission.php?operation=view&pk0=%kommission_id%' , '_self');
            $grid->AddExportColumn($column);
            
            //
            // View column for anzeige_name_mixed field
            //
            $column = new TextViewColumn('kommission2_id_anzeige_name_mixed', '2. Kommission (SR)', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'kommission.php?operation=view&pk0=%kommission2_id%' , '');
            $grid->AddExportColumn($column);
            
            //
            // View column for technischer_name field
            //
            $column = new TextViewColumn('technischer_name', 'Technischer Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for beschreibung field
            //
            $column = new TextViewColumn('beschreibung', 'Beschreibung', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for beschreibung_fr field
            //
            $column = new TextViewColumn('beschreibung_fr', 'Beschreibung Fr', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for angaben field
            //
            $column = new TextViewColumn('angaben', 'Angaben', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for angaben_fr field
            //
            $column = new TextViewColumn('angaben_fr', 'Angaben Fr', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for farbcode field
            //
            $column = new TextViewColumn('farbcode', 'Farbcode', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_visa field
            //
            $column = new TextViewColumn('eingabe_abgeschlossen_visa', 'Eingabe Abgeschlossen Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_datum field
            //
            $column = new DateTimeViewColumn('eingabe_abgeschlossen_datum', 'Eingabe Abgeschlossen Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for kontrolliert_visa field
            //
            $column = new TextViewColumn('kontrolliert_visa', 'Kontrolliert Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for kontrolliert_datum field
            //
            $column = new DateTimeViewColumn('kontrolliert_datum', 'Kontrolliert Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for freigabe_visa field
            //
            $column = new TextViewColumn('freigabe_visa', 'Freigabe Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for freigabe_datum field
            //
            $column = new DateTimeViewColumn('freigabe_datum', 'Freigabe Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
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
            $column->SetDisplaySetToNullCheckBox(false);
            $column->SetDisplaySetToDefaultCheckBox(false);
    		$column->SetVariableContainer($this->GetColumnVariableContainer());
        }
    
        function CreateMasterDetailRecordGridForinteressengruppeDetailEdit0brancheGrid()
        {
            $result = new Grid($this, $this->dataset, 'MasterDetailRecordGridForinteressengruppeDetailEdit0branche');
            $result->SetAllowDeleteSelected(false);
            $result->OnCustomDrawCell->AddListener('MasterDetailRecordGridForinteressengruppeDetailEdit0branche' . '_OnCustomDrawRow', $this);
            $result->OnCustomRenderColumn->AddListener('MasterDetailRecordGridForinteressengruppeDetailEdit0branche' . '_' . 'OnCustomRenderColumn', $this);
            $result->SetShowFilterBuilder(false);
            $result->SetAdvancedSearchAvailable(false);
            $result->SetFilterRowAvailable(false);
            $result->SetShowUpdateLink(false);
            $result->SetEnabledInlineEditing(false);
            $result->SetShowKeyColumnsImagesInHeader(false);
            $result->SetName('master_grid');
            //
            // View column for id field
            //
            $column = new TextViewColumn('id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'http://lobbywatch.ch/de/daten/branche/%id%' , '_blank');
            $column->SetDescription($this->RenderText('Technischer Schlüssel der Branche.  Der Link zeigt auf den Eintrag in der Lobbywatch.ch Webseite.'));
            $column->SetFixedWidth(null);
            $result->AddViewColumn($column);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('name', 'Name', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('brancheGrid_name_handler_list');
            $column = new DivTagViewColumnDecorator($column);
            $column->Bold = true;
            $column->SetDescription($this->RenderText('Name der Branche, z.B. Gesundheit, Energie'));
            $column->SetFixedWidth(null);
            $result->AddViewColumn($column);
            
            //
            // View column for name_fr field
            //
            $column = new TextViewColumn('name_fr', 'Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('brancheGrid_name_fr_handler_list');
            $column->SetDescription($this->RenderText('Französischer Name der Branche, z.B. Gesundheit, Energie'));
            $column->SetFixedWidth(null);
            $result->AddViewColumn($column);
            
            //
            // View column for anzeige_name_mixed field
            //
            $column = new TextViewColumn('kommission_id_anzeige_name_mixed', '1. Kommission (NR)', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'kommission.php?operation=view&pk0=%kommission_id%' , '_self');
            $column->SetDescription($this->RenderText('Zuständige Kommission im Parlament'));
            $column->SetFixedWidth(null);
            $result->AddViewColumn($column);
            
            //
            // View column for anzeige_name_mixed field
            //
            $column = new TextViewColumn('kommission2_id_anzeige_name_mixed', '2. Kommission (SR)', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'kommission.php?operation=view&pk0=%kommission2_id%' , '');
            $column->SetDescription($this->RenderText('Zuständige Kommission im Ständerat'));
            $column->SetFixedWidth(null);
            $result->AddViewColumn($column);
            
            //
            // View column for technischer_name field
            //
            $column = new TextViewColumn('technischer_name', 'Technischer Name', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Technischer Name für Branche. Keine Sonderzeichen sind erlaubt, nur Kleinbuchstaben, "_" und "-". Wird z.B. für das finden des Branchensymboles gebraucht.'));
            $column->SetFixedWidth(null);
            $result->AddViewColumn($column);
            
            //
            // View column for beschreibung field
            //
            $column = new TextViewColumn('beschreibung', 'Beschreibung', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('brancheGrid_beschreibung_handler_list');
            $column->SetDescription($this->RenderText('Beschreibung der Branche'));
            $column->SetFixedWidth(null);
            $result->AddViewColumn($column);
            
            //
            // View column for beschreibung_fr field
            //
            $column = new TextViewColumn('beschreibung_fr', 'Beschreibung Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('brancheGrid_beschreibung_fr_handler_list');
            $column->SetDescription($this->RenderText('Französische Beschreibung der Branche'));
            $column->SetFixedWidth(null);
            $result->AddViewColumn($column);
            
            //
            // View column for angaben field
            //
            $column = new TextViewColumn('angaben', 'Angaben', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('brancheGrid_angaben_handler_list');
            $column->SetDescription($this->RenderText('Angaben zur Branche'));
            $column->SetFixedWidth(null);
            $result->AddViewColumn($column);
            
            //
            // View column for angaben_fr field
            //
            $column = new TextViewColumn('angaben_fr', 'Angaben Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('brancheGrid_angaben_fr_handler_list');
            $column->SetDescription($this->RenderText('Angaben zur Branche auf Französisch'));
            $column->SetFixedWidth(null);
            $result->AddViewColumn($column);
            
            //
            // View column for farbcode field
            //
            $column = new TextViewColumn('farbcode', 'Farbcode', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('HTML-Farbcode, z.B. red oder #23FF23'));
            $column->SetFixedWidth(null);
            $result->AddViewColumn($column);
            
            //
            // View column for symbol_klein_rel field
            //
            $column = new ExternalImageColumn('symbol_klein_rel', 'Symbol klein', $this->dataset, '%symbol_dateiname%');
            $column->SetSourcePrefix('' . $GLOBALS["rel_files_url"] /*afterburner*/  . '/');
            $column->SetSourceSuffix('');
            $column->SetDescription($this->RenderText('Kleines Symbolbild (Icon) der Branche, relativer Pfad, kann mit ' . $GLOBALS["rel_files_url"] /*afterburner*/  . '/ zu URL ergänzt werden'));
            $column->SetFixedWidth(null);
            $result->AddViewColumn($column);
            
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('brancheGrid_notizen_handler_list');
            $column->SetReplaceLFByBR(true);
            $column->SetDescription($this->RenderText('Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.'));
            $column->SetFixedWidth(null);
            $result->AddViewColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_visa field
            //
            $column = new TextViewColumn('eingabe_abgeschlossen_visa', 'Eingabe Abgeschlossen Visa', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Kürzel der Person, welche die Eingabe abgeschlossen hat.'));
            $column->SetFixedWidth(null);
            $result->AddViewColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_datum field
            //
            $column = new DateTimeViewColumn('eingabe_abgeschlossen_datum', 'Eingabe Abgeschlossen Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)'));
            $column->SetFixedWidth(null);
            $result->AddViewColumn($column);
            
            //
            // View column for kontrolliert_visa field
            //
            $column = new TextViewColumn('kontrolliert_visa', 'Kontrolliert Visa', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Kürzel der Person, welche die Eingabe kontrolliert hat.'));
            $column->SetFixedWidth(null);
            $result->AddViewColumn($column);
            
            //
            // View column for kontrolliert_datum field
            //
            $column = new DateTimeViewColumn('kontrolliert_datum', 'Kontrolliert Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)'));
            $column->SetFixedWidth(null);
            $result->AddViewColumn($column);
            
            //
            // View column for freigabe_visa field
            //
            $column = new TextViewColumn('freigabe_visa', 'Freigabe Visa', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Freigabe von (Freigabe = Daten sind fertig)'));
            $column->SetFixedWidth(null);
            $result->AddViewColumn($column);
            
            //
            // View column for freigabe_datum field
            //
            $column = new DateTimeViewColumn('freigabe_datum', 'Freigabe Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Freigabedatum (Freigabe = Daten sind fertig)'));
            $column->SetFixedWidth(null);
            $result->AddViewColumn($column);
            
            //
            // View column for created_visa field
            //
            $column = new TextViewColumn('created_visa', 'Created Visa', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Erstellt von'));
            $column->SetFixedWidth(null);
            $result->AddViewColumn($column);
            
            //
            // View column for created_date field
            //
            $column = new DateTimeViewColumn('created_date', 'Created Date', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Erstellt am'));
            $column->SetFixedWidth(null);
            $result->AddViewColumn($column);
            
            //
            // View column for updated_visa field
            //
            $column = new TextViewColumn('updated_visa', 'Updated Visa', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Abgeändert von'));
            $column->SetFixedWidth(null);
            $result->AddViewColumn($column);
            
            //
            // View column for updated_date field
            //
            $column = new DateTimeViewColumn('updated_date', 'Updated Date', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Abgeändert am'));
            $column->SetFixedWidth(null);
            $result->AddViewColumn($column);
            
            //
            // View column for id field
            //
            $column = new TextViewColumn('id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'http://lobbywatch.ch/de/daten/branche/%id%' , '_blank');
            $result->AddPrintColumn($column);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('name', 'Name', $this->dataset);
            $column->SetOrderable(true);
            $result->AddPrintColumn($column);
            
            //
            // View column for name_fr field
            //
            $column = new TextViewColumn('name_fr', 'Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $result->AddPrintColumn($column);
            
            //
            // View column for anzeige_name_mixed field
            //
            $column = new TextViewColumn('kommission_id_anzeige_name_mixed', '1. Kommission (NR)', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'kommission.php?operation=view&pk0=%kommission_id%' , '_self');
            $result->AddPrintColumn($column);
            
            //
            // View column for anzeige_name_mixed field
            //
            $column = new TextViewColumn('kommission2_id_anzeige_name_mixed', '2. Kommission (SR)', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'kommission.php?operation=view&pk0=%kommission2_id%' , '');
            $result->AddPrintColumn($column);
            
            //
            // View column for technischer_name field
            //
            $column = new TextViewColumn('technischer_name', 'Technischer Name', $this->dataset);
            $column->SetOrderable(true);
            $result->AddPrintColumn($column);
            
            //
            // View column for beschreibung field
            //
            $column = new TextViewColumn('beschreibung', 'Beschreibung', $this->dataset);
            $column->SetOrderable(true);
            $result->AddPrintColumn($column);
            
            //
            // View column for beschreibung_fr field
            //
            $column = new TextViewColumn('beschreibung_fr', 'Beschreibung Fr', $this->dataset);
            $column->SetOrderable(true);
            $result->AddPrintColumn($column);
            
            //
            // View column for angaben field
            //
            $column = new TextViewColumn('angaben', 'Angaben', $this->dataset);
            $column->SetOrderable(true);
            $result->AddPrintColumn($column);
            
            //
            // View column for angaben_fr field
            //
            $column = new TextViewColumn('angaben_fr', 'Angaben Fr', $this->dataset);
            $column->SetOrderable(true);
            $result->AddPrintColumn($column);
            
            //
            // View column for farbcode field
            //
            $column = new TextViewColumn('farbcode', 'Farbcode', $this->dataset);
            $column->SetOrderable(true);
            $result->AddPrintColumn($column);
            
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $result->AddPrintColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_visa field
            //
            $column = new TextViewColumn('eingabe_abgeschlossen_visa', 'Eingabe Abgeschlossen Visa', $this->dataset);
            $column->SetOrderable(true);
            $result->AddPrintColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_datum field
            //
            $column = new DateTimeViewColumn('eingabe_abgeschlossen_datum', 'Eingabe Abgeschlossen Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $result->AddPrintColumn($column);
            
            //
            // View column for kontrolliert_visa field
            //
            $column = new TextViewColumn('kontrolliert_visa', 'Kontrolliert Visa', $this->dataset);
            $column->SetOrderable(true);
            $result->AddPrintColumn($column);
            
            //
            // View column for kontrolliert_datum field
            //
            $column = new DateTimeViewColumn('kontrolliert_datum', 'Kontrolliert Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $result->AddPrintColumn($column);
            
            //
            // View column for freigabe_visa field
            //
            $column = new TextViewColumn('freigabe_visa', 'Freigabe Visa', $this->dataset);
            $column->SetOrderable(true);
            $result->AddPrintColumn($column);
            
            //
            // View column for freigabe_datum field
            //
            $column = new DateTimeViewColumn('freigabe_datum', 'Freigabe Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $result->AddPrintColumn($column);
            
            //
            // View column for created_visa field
            //
            $column = new TextViewColumn('created_visa', 'Created Visa', $this->dataset);
            $column->SetOrderable(true);
            $result->AddPrintColumn($column);
            
            //
            // View column for created_date field
            //
            $column = new DateTimeViewColumn('created_date', 'Created Date', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $result->AddPrintColumn($column);
            
            //
            // View column for updated_visa field
            //
            $column = new TextViewColumn('updated_visa', 'Updated Visa', $this->dataset);
            $column->SetOrderable(true);
            $result->AddPrintColumn($column);
            
            //
            // View column for updated_date field
            //
            $column = new DateTimeViewColumn('updated_date', 'Updated Date', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $result->AddPrintColumn($column);
            
            return $result;
        }
        
        public function MasterDetailRecordGridForinteressengruppeDetailEdit0branche_OnCustomDrawRow($rowData, &$rowCellStyles, &$rowStyles)
        {
        customDrawRow('branche', $rowData, $rowCellStyles, $rowStyles);
        }
        function MasterDetailRecordGridForinteressengruppeDetailEdit0branche_OnCustomRenderColumn($fieldName, $fieldData, $rowData, &$customText, &$handled)
        {
            customOnCustomRenderColumn('branche', $fieldName, $fieldData, $rowData, $customText, $handled);
        }
        function CreateMasterDetailRecordGridFororganisationDetailEdit1brancheGrid()
        {
            $result = new Grid($this, $this->dataset, 'MasterDetailRecordGridFororganisationDetailEdit1branche');
            $result->SetAllowDeleteSelected(false);
            $result->OnCustomDrawCell->AddListener('MasterDetailRecordGridFororganisationDetailEdit1branche' . '_OnCustomDrawRow', $this);
            $result->OnCustomRenderColumn->AddListener('MasterDetailRecordGridFororganisationDetailEdit1branche' . '_' . 'OnCustomRenderColumn', $this);
            $result->SetShowFilterBuilder(false);
            $result->SetAdvancedSearchAvailable(false);
            $result->SetFilterRowAvailable(false);
            $result->SetShowUpdateLink(false);
            $result->SetEnabledInlineEditing(false);
            $result->SetShowKeyColumnsImagesInHeader(false);
            $result->SetName('master_grid');
            //
            // View column for id field
            //
            $column = new TextViewColumn('id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'http://lobbywatch.ch/de/daten/branche/%id%' , '_blank');
            $column->SetDescription($this->RenderText('Technischer Schlüssel der Branche.  Der Link zeigt auf den Eintrag in der Lobbywatch.ch Webseite.'));
            $column->SetFixedWidth(null);
            $result->AddViewColumn($column);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('name', 'Name', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('brancheGrid_name_handler_list');
            $column = new DivTagViewColumnDecorator($column);
            $column->Bold = true;
            $column->SetDescription($this->RenderText('Name der Branche, z.B. Gesundheit, Energie'));
            $column->SetFixedWidth(null);
            $result->AddViewColumn($column);
            
            //
            // View column for name_fr field
            //
            $column = new TextViewColumn('name_fr', 'Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('brancheGrid_name_fr_handler_list');
            $column->SetDescription($this->RenderText('Französischer Name der Branche, z.B. Gesundheit, Energie'));
            $column->SetFixedWidth(null);
            $result->AddViewColumn($column);
            
            //
            // View column for anzeige_name_mixed field
            //
            $column = new TextViewColumn('kommission_id_anzeige_name_mixed', '1. Kommission (NR)', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'kommission.php?operation=view&pk0=%kommission_id%' , '_self');
            $column->SetDescription($this->RenderText('Zuständige Kommission im Parlament'));
            $column->SetFixedWidth(null);
            $result->AddViewColumn($column);
            
            //
            // View column for anzeige_name_mixed field
            //
            $column = new TextViewColumn('kommission2_id_anzeige_name_mixed', '2. Kommission (SR)', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'kommission.php?operation=view&pk0=%kommission2_id%' , '');
            $column->SetDescription($this->RenderText('Zuständige Kommission im Ständerat'));
            $column->SetFixedWidth(null);
            $result->AddViewColumn($column);
            
            //
            // View column for technischer_name field
            //
            $column = new TextViewColumn('technischer_name', 'Technischer Name', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Technischer Name für Branche. Keine Sonderzeichen sind erlaubt, nur Kleinbuchstaben, "_" und "-". Wird z.B. für das finden des Branchensymboles gebraucht.'));
            $column->SetFixedWidth(null);
            $result->AddViewColumn($column);
            
            //
            // View column for beschreibung field
            //
            $column = new TextViewColumn('beschreibung', 'Beschreibung', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('brancheGrid_beschreibung_handler_list');
            $column->SetDescription($this->RenderText('Beschreibung der Branche'));
            $column->SetFixedWidth(null);
            $result->AddViewColumn($column);
            
            //
            // View column for beschreibung_fr field
            //
            $column = new TextViewColumn('beschreibung_fr', 'Beschreibung Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('brancheGrid_beschreibung_fr_handler_list');
            $column->SetDescription($this->RenderText('Französische Beschreibung der Branche'));
            $column->SetFixedWidth(null);
            $result->AddViewColumn($column);
            
            //
            // View column for angaben field
            //
            $column = new TextViewColumn('angaben', 'Angaben', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('brancheGrid_angaben_handler_list');
            $column->SetDescription($this->RenderText('Angaben zur Branche'));
            $column->SetFixedWidth(null);
            $result->AddViewColumn($column);
            
            //
            // View column for angaben_fr field
            //
            $column = new TextViewColumn('angaben_fr', 'Angaben Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('brancheGrid_angaben_fr_handler_list');
            $column->SetDescription($this->RenderText('Angaben zur Branche auf Französisch'));
            $column->SetFixedWidth(null);
            $result->AddViewColumn($column);
            
            //
            // View column for farbcode field
            //
            $column = new TextViewColumn('farbcode', 'Farbcode', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('HTML-Farbcode, z.B. red oder #23FF23'));
            $column->SetFixedWidth(null);
            $result->AddViewColumn($column);
            
            //
            // View column for symbol_klein_rel field
            //
            $column = new ExternalImageColumn('symbol_klein_rel', 'Symbol klein', $this->dataset, '%symbol_dateiname%');
            $column->SetSourcePrefix('' . $GLOBALS["rel_files_url"] /*afterburner*/  . '/');
            $column->SetSourceSuffix('');
            $column->SetDescription($this->RenderText('Kleines Symbolbild (Icon) der Branche, relativer Pfad, kann mit ' . $GLOBALS["rel_files_url"] /*afterburner*/  . '/ zu URL ergänzt werden'));
            $column->SetFixedWidth(null);
            $result->AddViewColumn($column);
            
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('brancheGrid_notizen_handler_list');
            $column->SetReplaceLFByBR(true);
            $column->SetDescription($this->RenderText('Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.'));
            $column->SetFixedWidth(null);
            $result->AddViewColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_visa field
            //
            $column = new TextViewColumn('eingabe_abgeschlossen_visa', 'Eingabe Abgeschlossen Visa', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Kürzel der Person, welche die Eingabe abgeschlossen hat.'));
            $column->SetFixedWidth(null);
            $result->AddViewColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_datum field
            //
            $column = new DateTimeViewColumn('eingabe_abgeschlossen_datum', 'Eingabe Abgeschlossen Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)'));
            $column->SetFixedWidth(null);
            $result->AddViewColumn($column);
            
            //
            // View column for kontrolliert_visa field
            //
            $column = new TextViewColumn('kontrolliert_visa', 'Kontrolliert Visa', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Kürzel der Person, welche die Eingabe kontrolliert hat.'));
            $column->SetFixedWidth(null);
            $result->AddViewColumn($column);
            
            //
            // View column for kontrolliert_datum field
            //
            $column = new DateTimeViewColumn('kontrolliert_datum', 'Kontrolliert Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)'));
            $column->SetFixedWidth(null);
            $result->AddViewColumn($column);
            
            //
            // View column for freigabe_visa field
            //
            $column = new TextViewColumn('freigabe_visa', 'Freigabe Visa', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Freigabe von (Freigabe = Daten sind fertig)'));
            $column->SetFixedWidth(null);
            $result->AddViewColumn($column);
            
            //
            // View column for freigabe_datum field
            //
            $column = new DateTimeViewColumn('freigabe_datum', 'Freigabe Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Freigabedatum (Freigabe = Daten sind fertig)'));
            $column->SetFixedWidth(null);
            $result->AddViewColumn($column);
            
            //
            // View column for created_visa field
            //
            $column = new TextViewColumn('created_visa', 'Created Visa', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Erstellt von'));
            $column->SetFixedWidth(null);
            $result->AddViewColumn($column);
            
            //
            // View column for created_date field
            //
            $column = new DateTimeViewColumn('created_date', 'Created Date', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Erstellt am'));
            $column->SetFixedWidth(null);
            $result->AddViewColumn($column);
            
            //
            // View column for updated_visa field
            //
            $column = new TextViewColumn('updated_visa', 'Updated Visa', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Abgeändert von'));
            $column->SetFixedWidth(null);
            $result->AddViewColumn($column);
            
            //
            // View column for updated_date field
            //
            $column = new DateTimeViewColumn('updated_date', 'Updated Date', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Abgeändert am'));
            $column->SetFixedWidth(null);
            $result->AddViewColumn($column);
            
            //
            // View column for id field
            //
            $column = new TextViewColumn('id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'http://lobbywatch.ch/de/daten/branche/%id%' , '_blank');
            $result->AddPrintColumn($column);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('name', 'Name', $this->dataset);
            $column->SetOrderable(true);
            $result->AddPrintColumn($column);
            
            //
            // View column for name_fr field
            //
            $column = new TextViewColumn('name_fr', 'Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $result->AddPrintColumn($column);
            
            //
            // View column for anzeige_name_mixed field
            //
            $column = new TextViewColumn('kommission_id_anzeige_name_mixed', '1. Kommission (NR)', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'kommission.php?operation=view&pk0=%kommission_id%' , '_self');
            $result->AddPrintColumn($column);
            
            //
            // View column for anzeige_name_mixed field
            //
            $column = new TextViewColumn('kommission2_id_anzeige_name_mixed', '2. Kommission (SR)', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'kommission.php?operation=view&pk0=%kommission2_id%' , '');
            $result->AddPrintColumn($column);
            
            //
            // View column for technischer_name field
            //
            $column = new TextViewColumn('technischer_name', 'Technischer Name', $this->dataset);
            $column->SetOrderable(true);
            $result->AddPrintColumn($column);
            
            //
            // View column for beschreibung field
            //
            $column = new TextViewColumn('beschreibung', 'Beschreibung', $this->dataset);
            $column->SetOrderable(true);
            $result->AddPrintColumn($column);
            
            //
            // View column for beschreibung_fr field
            //
            $column = new TextViewColumn('beschreibung_fr', 'Beschreibung Fr', $this->dataset);
            $column->SetOrderable(true);
            $result->AddPrintColumn($column);
            
            //
            // View column for angaben field
            //
            $column = new TextViewColumn('angaben', 'Angaben', $this->dataset);
            $column->SetOrderable(true);
            $result->AddPrintColumn($column);
            
            //
            // View column for angaben_fr field
            //
            $column = new TextViewColumn('angaben_fr', 'Angaben Fr', $this->dataset);
            $column->SetOrderable(true);
            $result->AddPrintColumn($column);
            
            //
            // View column for farbcode field
            //
            $column = new TextViewColumn('farbcode', 'Farbcode', $this->dataset);
            $column->SetOrderable(true);
            $result->AddPrintColumn($column);
            
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $result->AddPrintColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_visa field
            //
            $column = new TextViewColumn('eingabe_abgeschlossen_visa', 'Eingabe Abgeschlossen Visa', $this->dataset);
            $column->SetOrderable(true);
            $result->AddPrintColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_datum field
            //
            $column = new DateTimeViewColumn('eingabe_abgeschlossen_datum', 'Eingabe Abgeschlossen Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $result->AddPrintColumn($column);
            
            //
            // View column for kontrolliert_visa field
            //
            $column = new TextViewColumn('kontrolliert_visa', 'Kontrolliert Visa', $this->dataset);
            $column->SetOrderable(true);
            $result->AddPrintColumn($column);
            
            //
            // View column for kontrolliert_datum field
            //
            $column = new DateTimeViewColumn('kontrolliert_datum', 'Kontrolliert Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $result->AddPrintColumn($column);
            
            //
            // View column for freigabe_visa field
            //
            $column = new TextViewColumn('freigabe_visa', 'Freigabe Visa', $this->dataset);
            $column->SetOrderable(true);
            $result->AddPrintColumn($column);
            
            //
            // View column for freigabe_datum field
            //
            $column = new DateTimeViewColumn('freigabe_datum', 'Freigabe Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $result->AddPrintColumn($column);
            
            //
            // View column for created_visa field
            //
            $column = new TextViewColumn('created_visa', 'Created Visa', $this->dataset);
            $column->SetOrderable(true);
            $result->AddPrintColumn($column);
            
            //
            // View column for created_date field
            //
            $column = new DateTimeViewColumn('created_date', 'Created Date', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $result->AddPrintColumn($column);
            
            //
            // View column for updated_visa field
            //
            $column = new TextViewColumn('updated_visa', 'Updated Visa', $this->dataset);
            $column->SetOrderable(true);
            $result->AddPrintColumn($column);
            
            //
            // View column for updated_date field
            //
            $column = new DateTimeViewColumn('updated_date', 'Updated Date', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $result->AddPrintColumn($column);
            
            return $result;
        }
        
        public function MasterDetailRecordGridFororganisationDetailEdit1branche_OnCustomDrawRow($rowData, &$rowCellStyles, &$rowStyles)
        {
        customDrawRow('branche', $rowData, $rowCellStyles, $rowStyles);
        }
        function MasterDetailRecordGridFororganisationDetailEdit1branche_OnCustomRenderColumn($fieldName, $fieldData, $rowData, &$customText, &$handled)
        {
            customOnCustomRenderColumn('branche', $fieldName, $fieldData, $rowData, $customText, $handled);
        }
        
        function GetCustomClientScript()
        {
            return ;
        }
        
        function GetOnPageLoadedClientScript()
        {
            return ;
        }
        public function brancheGrid_OnGetCustomTemplate($part, $mode, &$result, &$params)
        {
        defaultOnGetCustomTemplate($this, $part, $mode, $result, $params);
        }
        public function brancheGrid_OnCustomDrawRow($rowData, &$rowCellStyles, &$rowStyles)
        {
        customDrawRow('branche', $rowData, $rowCellStyles, $rowStyles);
        }
        function brancheGrid_OnCustomRenderColumn($fieldName, $fieldData, $rowData, &$customText, &$handled)
        {
            customOnCustomRenderColumn('branche', $fieldName, $fieldData, $rowData, $customText, $handled);
        }
        function brancheGrid_BeforeUpdateRecord($page, &$rowData, &$cancel, &$message, $tableName)
        {
            symbol_remove_old($page, $rowData, $cancel, $message, $tableName);
            symbol_update_metadata($page, $rowData, $cancel, $message, $tableName);
        }
        function brancheGrid_BeforeDeleteRecord($page, &$rowData, &$cancel, &$message, $tableName)
        {
            symbol_remove($page, $rowData, $cancel, $message, $tableName);
        }
        function brancheGrid_BeforeInsertRecord($page, &$rowData, &$cancel, &$message, $tableName)
        {
            symbol_update_metadata($page, $rowData, $cancel, $message, $tableName);
        }
        public function symbol_klein_rel_GenerateFileName_edit(&$filepath, &$handled, $original_file_name, $original_file_extension, $file_size)
        {
        $targetFolder = FormatDatasetFieldsTemplate($this->GetDataset(), '' . $GLOBALS["public_files_dir_abs"] /*afterburner*/  . '/branche_symbole/%id%');
        FileUtils::ForceDirectories($targetFolder);
        
        $filename = ApplyVarablesMapToTemplate('%original_file_name%',
            array(
                'original_file_name' => $original_file_name,
                'original_file_extension' => $original_file_extension,
                'file_size' => $file_size
            )
        );
        $filepath = Path::Combine($targetFolder, $filename);
        
        $handled = true;
        }
        public function symbol_abs_Thumbnail_GenerateFileName_edit(&$filepath, &$handled, $original_file_name, $original_file_extension, $file_size)
        {
        $targetFolder = FormatDatasetFieldsTemplate($this->GetDataset(), '' . $GLOBALS["public_files_dir_abs"] /*afterburner*/  . '/branche_symbole/%id%');
        FileUtils::ForceDirectories($targetFolder);
        
        $filename = ApplyVarablesMapToTemplate('small_%original_file_name%',
            array(
                'original_file_name' => $original_file_name,
                'original_file_extension' => $original_file_extension,
                'file_size' => $file_size
            )
        );
        $filepath = Path::Combine($targetFolder, $filename);
        
        $handled = true;
        }
        
        public function symbol_abs_GenerateFileName_edit(&$filepath, &$handled, $original_file_name, $original_file_extension, $file_size)
        {
        $targetFolder = FormatDatasetFieldsTemplate($this->GetDataset(), '' . $GLOBALS["public_files_dir_abs"] /*afterburner*/  . '/branche_symbole/%id%');
        FileUtils::ForceDirectories($targetFolder);
        
        $filename = ApplyVarablesMapToTemplate('%original_file_name%',
            array(
                'original_file_name' => $original_file_name,
                'original_file_extension' => $original_file_extension,
                'file_size' => $file_size
            )
        );
        $filepath = Path::Combine($targetFolder, $filename);
        
        $handled = true;
        }
        public function symbol_rel_GenerateFileName_edit(&$filepath, &$handled, $original_file_name, $original_file_extension, $file_size)
        {
        $targetFolder = FormatDatasetFieldsTemplate($this->GetDataset(), '' . $GLOBALS["public_files_dir_abs"] /*afterburner*/  . '/branche_symbole/%id%');
        FileUtils::ForceDirectories($targetFolder);
        
        $filename = ApplyVarablesMapToTemplate('%original_file_name%',
            array(
                'original_file_name' => $original_file_name,
                'original_file_extension' => $original_file_extension,
                'file_size' => $file_size
            )
        );
        $filepath = Path::Combine($targetFolder, $filename);
        
        $handled = true;
        }
        public function symbol_abs_Thumbnail_GenerateFileName_insert(&$filepath, &$handled, $original_file_name, $original_file_extension, $file_size)
        {
        $targetFolder = FormatDatasetFieldsTemplate($this->GetDataset(), '' . $GLOBALS["public_files_dir_abs"] /*afterburner*/  . '/branche_symbole/%id%');
        FileUtils::ForceDirectories($targetFolder);
        
        $filename = ApplyVarablesMapToTemplate('small_%original_file_name%',
            array(
                'original_file_name' => $original_file_name,
                'original_file_extension' => $original_file_extension,
                'file_size' => $file_size
            )
        );
        $filepath = Path::Combine($targetFolder, $filename);
        
        $handled = true;
        }
        
        public function symbol_abs_GenerateFileName_insert(&$filepath, &$handled, $original_file_name, $original_file_extension, $file_size)
        {
        $targetFolder = FormatDatasetFieldsTemplate($this->GetDataset(), '' . $GLOBALS["public_files_dir_abs"] /*afterburner*/  . '/branche_symbole/%id%');
        FileUtils::ForceDirectories($targetFolder);
        
        $filename = ApplyVarablesMapToTemplate('%original_file_name%',
            array(
                'original_file_name' => $original_file_name,
                'original_file_extension' => $original_file_extension,
                'file_size' => $file_size
            )
        );
        $filepath = Path::Combine($targetFolder, $filename);
        
        $handled = true;
        }
        public function ShowEditButtonHandler(&$show)
        {
            if ($this->GetRecordPermission() != null)
                $show = $this->GetRecordPermission()->HasEditGrant($this->GetDataset());
        }
        public function ShowDeleteButtonHandler(&$show)
        {
            if ($this->GetRecordPermission() != null)
                $show = $this->GetRecordPermission()->HasDeleteGrant($this->GetDataset());
        }
        
        public function GetModalGridDeleteHandler() { return 'branche_modal_delete'; }
        protected function GetEnableModalGridDelete() { return true; }
    
        protected function CreateGrid()
        {
            $result = new Grid($this, $this->dataset, 'brancheGrid');
            if ($this->GetSecurityInfo()->HasDeleteGrant())
               $result->SetAllowDeleteSelected(true);
            else
               $result->SetAllowDeleteSelected(false);   
            
            ApplyCommonPageSettings($this, $result);
            
            $result->SetUseImagesForActions(true);
            $result->SetDefaultOrdering('name', otAscending);
            $result->SetUseFixedHeader(true);
            $result->SetShowLineNumbers(true);
            
            $result->SetHighlightRowAtHover(false);
            $result->SetWidth('');
            $this->OnGetCustomTemplate->AddListener('brancheGrid' . '_OnGetCustomTemplate', $this);
            $result->OnCustomDrawCell->AddListener('brancheGrid' . '_OnCustomDrawRow', $this);
            $result->OnCustomRenderColumn->AddListener('brancheGrid' . '_' . 'OnCustomRenderColumn', $this);
            $result->BeforeUpdateRecord->AddListener('brancheGrid' . '_' . 'BeforeUpdateRecord', $this);
            $result->BeforeDeleteRecord->AddListener('brancheGrid' . '_' . 'BeforeDeleteRecord', $this);
            $result->BeforeInsertRecord->AddListener('brancheGrid' . '_' . 'BeforeInsertRecord', $this);
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
            $pageView = new interessengruppeDetailView0branchePage($this, 'Interessengruppe', 'Interessengruppe', array('branche_id'), GetCurrentUserGrantForDataSource('branche.interessengruppe'), 'UTF-8', 20, 'interessengruppeDetailEdit0branche_handler');
            
            $pageView->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource('branche.interessengruppe'));
            $handler = new PageHTTPHandler('interessengruppeDetailView0branche_handler', $pageView);
            GetApplication()->RegisterHTTPHandler($handler);
            $pageEdit = new interessengruppeDetailEdit0branchePage($this, array('branche_id'), array('id'), $this->GetForeingKeyFields(), $this->CreateMasterDetailRecordGridForinteressengruppeDetailEdit0brancheGrid(), $this->dataset, GetCurrentUserGrantForDataSource('branche.interessengruppe'), 'UTF-8');
            
            $pageEdit->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource('branche.interessengruppe'));
            $pageEdit->SetShortCaption('Interessengruppe');
            $pageEdit->SetHeader(GetPagesHeader());
            $pageEdit->SetFooter(GetPagesFooter());
            $pageEdit->SetCaption('Interessengruppe');
            $pageEdit->SetHttpHandlerName('interessengruppeDetailEdit0branche_handler');
            $handler = new PageHTTPHandler('interessengruppeDetailEdit0branche_handler', $pageEdit);
            GetApplication()->RegisterHTTPHandler($handler);
            $pageView = new organisationDetailView1branchePage($this, 'Organisation', 'Organisation', array('branche_id'), GetCurrentUserGrantForDataSource('branche.organisation'), 'UTF-8', 20, 'organisationDetailEdit1branche_handler');
            
            $pageView->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource('branche.organisation'));
            $handler = new PageHTTPHandler('organisationDetailView1branche_handler', $pageView);
            GetApplication()->RegisterHTTPHandler($handler);
            $pageEdit = new organisationDetailEdit1branchePage($this, array('branche_id'), array('id'), $this->GetForeingKeyFields(), $this->CreateMasterDetailRecordGridFororganisationDetailEdit1brancheGrid(), $this->dataset, GetCurrentUserGrantForDataSource('branche.organisation'), 'UTF-8');
            
            $pageEdit->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource('branche.organisation'));
            $pageEdit->SetShortCaption('Organisation');
            $pageEdit->SetHeader(GetPagesHeader());
            $pageEdit->SetFooter(GetPagesFooter());
            $pageEdit->SetCaption('Organisation');
            $pageEdit->SetHttpHandlerName('organisationDetailEdit1branche_handler');
            $handler = new PageHTTPHandler('organisationDetailEdit1branche_handler', $pageEdit);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for name field
            //
            $column = new TextViewColumn('name', 'Name', $this->dataset);
            $column->SetOrderable(true);
            $column = new DivTagViewColumnDecorator($column);
            $column->Bold = true;
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'brancheGrid_name_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for name_fr field
            //
            $column = new TextViewColumn('name_fr', 'Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'brancheGrid_name_fr_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for beschreibung field
            //
            $column = new TextViewColumn('beschreibung', 'Beschreibung', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'brancheGrid_beschreibung_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for beschreibung_fr field
            //
            $column = new TextViewColumn('beschreibung_fr', 'Beschreibung Fr', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'brancheGrid_beschreibung_fr_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for angaben field
            //
            $column = new TextViewColumn('angaben', 'Angaben', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'brancheGrid_angaben_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for angaben_fr field
            //
            $column = new TextViewColumn('angaben_fr', 'Angaben Fr', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'brancheGrid_angaben_fr_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $column->SetReplaceLFByBR(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'brancheGrid_notizen_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for name field
            //
            $column = new TextViewColumn('name', 'Name', $this->dataset);
            $column->SetOrderable(true);
            $column = new DivTagViewColumnDecorator($column);
            $column->Bold = true;
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'brancheGrid_name_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for name_fr field
            //
            $column = new TextViewColumn('name_fr', 'Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'brancheGrid_name_fr_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for beschreibung field
            //
            $column = new TextViewColumn('beschreibung', 'Beschreibung', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'brancheGrid_beschreibung_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for beschreibung_fr field
            //
            $column = new TextViewColumn('beschreibung_fr', 'Beschreibung Fr', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'brancheGrid_beschreibung_fr_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for angaben field
            //
            $column = new TextViewColumn('angaben', 'Angaben', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'brancheGrid_angaben_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for angaben_fr field
            //
            $column = new TextViewColumn('angaben_fr', 'Angaben Fr', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'brancheGrid_angaben_fr_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $column->SetReplaceLFByBR(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'brancheGrid_notizen_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
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
    <p>Tabelle der Wirtschaftsbranchen
    </p>
    
    <div class="clearfix rbox note"><div class="rbox-title"><img src="' . util_data_uri('img/icons/information.png') . '" alt="Hinweis" title="Hinweis" class="icon" width="16" height="16"><span>Hinweis</span></div><div class="rbox-data">Branchen sollen einer zuständigen Kommission zugeordnet werden.</div></div>
    </div>
    
    ' . $GLOBALS["edit_general_hint"] /*afterburner*/  . '';
        }
    }

    SetUpUserAuthorization(GetApplication());

    try
    {
        $Page = new branchePage("branche.php", "branche", GetCurrentUserGrantForDataSource("branche"), 'UTF-8');
        $Page->SetShortCaption('<span class="entity">Branche</span>');
        $Page->SetHeader(GetPagesHeader());
        $Page->SetFooter(GetPagesFooter());
        $Page->SetCaption('Branche');
        $Page->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource("branche"));
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
