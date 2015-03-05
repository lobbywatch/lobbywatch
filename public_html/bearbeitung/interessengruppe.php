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
    
    
    
    class organisationDetailView0interessengruppePage extends DetailPage
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
            $column->SetFullTextWindowHandlerName('organisationDetailViewGrid0interessengruppe_name_de_handler_list');
            $column->SetDescription($this->RenderText('Name der Organisation. Sollte nur juristischem Namen entsprechen, ohne Zusätze, wie Adresse.'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for name_fr field
            //
            $column = new TextViewColumn('name_fr', 'Name Fr', $this->dataset);
            $column->SetOrderable(false);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('organisationDetailViewGrid0interessengruppe_name_fr_handler_list');
            $column->SetDescription($this->RenderText('Französischer Name'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for name_it field
            //
            $column = new TextViewColumn('name_it', 'Name It', $this->dataset);
            $column->SetOrderable(false);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('organisationDetailViewGrid0interessengruppe_name_it_handler_list');
            $column->SetDescription($this->RenderText('Italienischer Name'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for ort field
            //
            $column = new TextViewColumn('ort', 'Ort', $this->dataset);
            $column->SetOrderable(false);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('organisationDetailViewGrid0interessengruppe_ort_handler_list');
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
            $column->SetDescription($this->RenderText('Fremdschlüssel Branche.'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for beschreibung field
            //
            $column = new TextViewColumn('beschreibung', 'Beschreibung', $this->dataset);
            $column->SetOrderable(false);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('organisationDetailViewGrid0interessengruppe_beschreibung_handler_list');
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
            $column->SetFullTextWindowHandlerName('organisationDetailViewGrid0interessengruppe_homepage_handler_list');
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
            $column->SetFullTextWindowHandlerName('organisationDetailViewGrid0interessengruppe_handelsregister_url_handler_list');
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
            $column->SetFullTextWindowHandlerName('organisationDetailViewGrid0interessengruppe_notizen_handler_list');
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
            $result = new Grid($this, $this->dataset, 'organisationDetailViewGrid0interessengruppe');
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
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'organisationDetailViewGrid0interessengruppe_name_de_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for name_fr field
            //
            $column = new TextViewColumn('name_fr', 'Name Fr', $this->dataset);
            $column->SetOrderable(false);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'organisationDetailViewGrid0interessengruppe_name_fr_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for name_it field
            //
            $column = new TextViewColumn('name_it', 'Name It', $this->dataset);
            $column->SetOrderable(false);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'organisationDetailViewGrid0interessengruppe_name_it_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for ort field
            //
            $column = new TextViewColumn('ort', 'Ort', $this->dataset);
            $column->SetOrderable(false);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'organisationDetailViewGrid0interessengruppe_ort_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for beschreibung field
            //
            $column = new TextViewColumn('beschreibung', 'Beschreibung', $this->dataset);
            $column->SetOrderable(false);
            $column->SetReplaceLFByBR(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'organisationDetailViewGrid0interessengruppe_beschreibung_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for homepage field
            //
            $column = new TextViewColumn('homepage', 'Homepage', $this->dataset);
            $column->SetOrderable(false);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, '%homepage%' , '_blank');
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'organisationDetailViewGrid0interessengruppe_homepage_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for handelsregister_url field
            //
            $column = new TextViewColumn('handelsregister_url', 'Handelsregister Url', $this->dataset);
            $column->SetOrderable(false);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, '%handelsregister_url%' , '_blank');
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'organisationDetailViewGrid0interessengruppe_handelsregister_url_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(false);
            $column->SetReplaceLFByBR(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'organisationDetailViewGrid0interessengruppe_notizen_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            return $result;
        }
    }
    
    
    
    // OnBeforePageExecute event handler
    
    
    
    class organisationDetailEdit0interessengruppePage extends DetailPageEdit
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
            $grid->SearchControl = new SimpleSearch('organisationDetailEdit0interessengruppessearch', $this->dataset,
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
            $this->AdvancedSearchControl = new AdvancedSearchControl('organisationDetailEdit0interessengruppeasearch', $this->dataset, $this->GetLocalizerCaptions(), $this->GetColumnVariableContainer(), $this->CreateLinkBuilder());
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
            $column->SetFullTextWindowHandlerName('organisationDetailEditGrid0interessengruppe_name_de_handler_list');
            $column->SetDescription($this->RenderText('Name der Organisation. Sollte nur juristischem Namen entsprechen, ohne Zusätze, wie Adresse.'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for name_fr field
            //
            $column = new TextViewColumn('name_fr', 'Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('organisationDetailEditGrid0interessengruppe_name_fr_handler_list');
            $column->SetDescription($this->RenderText('Französischer Name'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for name_it field
            //
            $column = new TextViewColumn('name_it', 'Name It', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('organisationDetailEditGrid0interessengruppe_name_it_handler_list');
            $column->SetDescription($this->RenderText('Italienischer Name'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for ort field
            //
            $column = new TextViewColumn('ort', 'Ort', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('organisationDetailEditGrid0interessengruppe_ort_handler_list');
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
            $column->SetDescription($this->RenderText('Fremdschlüssel Branche.'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for beschreibung field
            //
            $column = new TextViewColumn('beschreibung', 'Beschreibung', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('organisationDetailEditGrid0interessengruppe_beschreibung_handler_list');
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
            $column->SetFullTextWindowHandlerName('organisationDetailEditGrid0interessengruppe_homepage_handler_list');
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
            $column->SetFullTextWindowHandlerName('organisationDetailEditGrid0interessengruppe_handelsregister_url_handler_list');
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
            $column->SetFullTextWindowHandlerName('organisationDetailEditGrid0interessengruppe_notizen_handler_list');
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
            $column->SetFullTextWindowHandlerName('organisationDetailEditGrid0interessengruppe_name_de_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for name_fr field
            //
            $column = new TextViewColumn('name_fr', 'Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('organisationDetailEditGrid0interessengruppe_name_fr_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for name_it field
            //
            $column = new TextViewColumn('name_it', 'Name It', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('organisationDetailEditGrid0interessengruppe_name_it_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for ort field
            //
            $column = new TextViewColumn('ort', 'Ort', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('organisationDetailEditGrid0interessengruppe_ort_handler_view');
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
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for beschreibung field
            //
            $column = new TextViewColumn('beschreibung', 'Beschreibung', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('organisationDetailEditGrid0interessengruppe_beschreibung_handler_view');
            $column->SetReplaceLFByBR(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for homepage field
            //
            $column = new TextViewColumn('homepage', 'Homepage', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('organisationDetailEditGrid0interessengruppe_homepage_handler_view');
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, '%homepage%' , '_blank');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for handelsregister_url field
            //
            $column = new TextViewColumn('handelsregister_url', 'Handelsregister Url', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('organisationDetailEditGrid0interessengruppe_handelsregister_url_handler_view');
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, '%handelsregister_url%' , '_blank');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('organisationDetailEditGrid0interessengruppe_notizen_handler_view');
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
            $result = new Grid($this, $this->dataset, 'organisationDetailEditGrid0interessengruppe');
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
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'organisationDetailEditGrid0interessengruppe_name_de_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for name_fr field
            //
            $column = new TextViewColumn('name_fr', 'Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'organisationDetailEditGrid0interessengruppe_name_fr_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for name_it field
            //
            $column = new TextViewColumn('name_it', 'Name It', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'organisationDetailEditGrid0interessengruppe_name_it_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for ort field
            //
            $column = new TextViewColumn('ort', 'Ort', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'organisationDetailEditGrid0interessengruppe_ort_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for beschreibung field
            //
            $column = new TextViewColumn('beschreibung', 'Beschreibung', $this->dataset);
            $column->SetOrderable(true);
            $column->SetReplaceLFByBR(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'organisationDetailEditGrid0interessengruppe_beschreibung_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for homepage field
            //
            $column = new TextViewColumn('homepage', 'Homepage', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, '%homepage%' , '_blank');
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'organisationDetailEditGrid0interessengruppe_homepage_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for handelsregister_url field
            //
            $column = new TextViewColumn('handelsregister_url', 'Handelsregister Url', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, '%handelsregister_url%' , '_blank');
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'organisationDetailEditGrid0interessengruppe_handelsregister_url_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $column->SetReplaceLFByBR(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'organisationDetailEditGrid0interessengruppe_notizen_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for name_de field
            //
            $column = new TextViewColumn('name_de', 'Name De', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'organisationDetailEditGrid0interessengruppe_name_de_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for name_fr field
            //
            $column = new TextViewColumn('name_fr', 'Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'organisationDetailEditGrid0interessengruppe_name_fr_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for name_it field
            //
            $column = new TextViewColumn('name_it', 'Name It', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'organisationDetailEditGrid0interessengruppe_name_it_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for ort field
            //
            $column = new TextViewColumn('ort', 'Ort', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'organisationDetailEditGrid0interessengruppe_ort_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for beschreibung field
            //
            $column = new TextViewColumn('beschreibung', 'Beschreibung', $this->dataset);
            $column->SetOrderable(true);
            $column->SetReplaceLFByBR(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'organisationDetailEditGrid0interessengruppe_beschreibung_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for homepage field
            //
            $column = new TextViewColumn('homepage', 'Homepage', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, '%homepage%' , '_blank');
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'organisationDetailEditGrid0interessengruppe_homepage_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for handelsregister_url field
            //
            $column = new TextViewColumn('handelsregister_url', 'Handelsregister Url', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, '%handelsregister_url%' , '_blank');
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'organisationDetailEditGrid0interessengruppe_handelsregister_url_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $column->SetReplaceLFByBR(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'organisationDetailEditGrid0interessengruppe_notizen_handler_view', $column);
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
    
    
    
    class parlamentarierDetailView1interessengruppePage extends DetailPage
    {
        protected function DoBeforeCreate()
        {
            $this->dataset = new TableDataset(
                new MyPDOConnectionFactory(),
                GetConnectionOptions(),
                '`parlamentarier`');
            $field = new IntegerField('id', null, null, true);
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, true);
            $field = new StringField('nachname');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('vorname');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('zweiter_vorname');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('rat_id');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new IntegerField('kanton_id');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('kommissionen');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('partei_id');
            $this->dataset->AddField($field, false);
            $field = new StringField('parteifunktion');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new IntegerField('fraktion_id');
            $this->dataset->AddField($field, false);
            $field = new StringField('fraktionsfunktion');
            $this->dataset->AddField($field, false);
            $field = new DateField('im_rat_seit');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new DateField('im_rat_bis');
            $this->dataset->AddField($field, false);
            $field = new DateField('ratswechsel');
            $this->dataset->AddField($field, false);
            $field = new DateField('ratsunterbruch_von');
            $this->dataset->AddField($field, false);
            $field = new DateField('ratsunterbruch_bis');
            $this->dataset->AddField($field, false);
            $field = new StringField('beruf');
            $this->dataset->AddField($field, false);
            $field = new StringField('beruf_fr');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('beruf_interessengruppe_id');
            $this->dataset->AddField($field, false);
            $field = new StringField('zivilstand');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('anzahl_kinder');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('militaerischer_grad_id');
            $this->dataset->AddField($field, false);
            $field = new StringField('geschlecht');
            $this->dataset->AddField($field, false);
            $field = new DateField('geburtstag');
            $this->dataset->AddField($field, false);
            $field = new StringField('photo');
            $this->dataset->AddField($field, false);
            $field = new StringField('photo_dateiname');
            $this->dataset->AddField($field, false);
            $field = new StringField('photo_dateierweiterung');
            $this->dataset->AddField($field, false);
            $field = new StringField('photo_dateiname_voll');
            $this->dataset->AddField($field, false);
            $field = new StringField('photo_mime_type');
            $this->dataset->AddField($field, false);
            $field = new StringField('kleinbild');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('sitzplatz');
            $this->dataset->AddField($field, false);
            $field = new StringField('email');
            $this->dataset->AddField($field, false);
            $field = new StringField('homepage');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('parlament_biografie_id');
            $this->dataset->AddField($field, false);
            $field = new StringField('twitter_name');
            $this->dataset->AddField($field, false);
            $field = new StringField('linkedin_profil_url');
            $this->dataset->AddField($field, false);
            $field = new StringField('xing_profil_name');
            $this->dataset->AddField($field, false);
            $field = new StringField('facebook_name');
            $this->dataset->AddField($field, false);
            $field = new StringField('arbeitssprache');
            $this->dataset->AddField($field, false);
            $field = new StringField('adresse_firma');
            $this->dataset->AddField($field, false);
            $field = new StringField('adresse_strasse');
            $this->dataset->AddField($field, false);
            $field = new StringField('adresse_zusatz');
            $this->dataset->AddField($field, false);
            $field = new StringField('adresse_plz');
            $this->dataset->AddField($field, false);
            $field = new StringField('adresse_ort');
            $this->dataset->AddField($field, false);
            $field = new StringField('telephon_1');
            $this->dataset->AddField($field, false);
            $field = new StringField('telephon_2');
            $this->dataset->AddField($field, false);
            $field = new StringField('erfasst');
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
            $field = new StringField('autorisierung_verschickt_visa');
            $this->dataset->AddField($field, false);
            $field = new DateTimeField('autorisierung_verschickt_datum');
            $this->dataset->AddField($field, false);
            $field = new StringField('autorisiert_visa');
            $this->dataset->AddField($field, false);
            $field = new DateField('autorisiert_datum');
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
            $this->dataset->AddLookupField('rat_id', 'rat', new IntegerField('id', null, null, true), new StringField('abkuerzung', 'rat_id_abkuerzung', 'rat_id_abkuerzung_rat'), 'rat_id_abkuerzung_rat');
            $this->dataset->AddLookupField('kanton_id', 'kanton', new IntegerField('id', null, null, true), new StringField('abkuerzung', 'kanton_id_abkuerzung', 'kanton_id_abkuerzung_kanton'), 'kanton_id_abkuerzung_kanton');
            $this->dataset->AddLookupField('partei_id', 'v_partei', new IntegerField('id'), new StringField('abkuerzung', 'partei_id_abkuerzung', 'partei_id_abkuerzung_v_partei'), 'partei_id_abkuerzung_v_partei');
            $this->dataset->AddLookupField('fraktion_id', 'v_fraktion', new IntegerField('id'), new StringField('abkuerzung', 'fraktion_id_abkuerzung', 'fraktion_id_abkuerzung_v_fraktion'), 'fraktion_id_abkuerzung_v_fraktion');
            $this->dataset->AddLookupField('beruf_interessengruppe_id', 'v_interessengruppe_simple', new IntegerField('id'), new StringField('anzeige_name', 'beruf_interessengruppe_id_anzeige_name', 'beruf_interessengruppe_id_anzeige_name_v_interessengruppe_simple'), 'beruf_interessengruppe_id_anzeige_name_v_interessengruppe_simple');
            $this->dataset->AddLookupField('militaerischer_grad_id', 'mil_grad', new IntegerField('id', null, null, true), new StringField('name', 'militaerischer_grad_id_name', 'militaerischer_grad_id_name_mil_grad'), 'militaerischer_grad_id_name_mil_grad');
        }
    
        protected function AddFieldColumns(Grid $grid)
        {
            //
            // View column for id field
            //
            $column = new TextViewColumn('id', 'Id', $this->dataset);
            $column->SetOrderable(false);
            $column->SetDescription($this->RenderText('Technischer Schlüssel des Parlamentariers'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for nachname field
            //
            $column = new TextViewColumn('nachname', 'Nachname', $this->dataset);
            $column->SetOrderable(false);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('parlamentarierDetailViewGrid1interessengruppe_nachname_handler_list');
            $column->SetDescription($this->RenderText('Nachname des Parlamentariers'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for vorname field
            //
            $column = new TextViewColumn('vorname', 'Vorname', $this->dataset);
            $column->SetOrderable(false);
            $column->SetDescription($this->RenderText('Vornahme des Parlamentariers'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for zweiter_vorname field
            //
            $column = new TextViewColumn('zweiter_vorname', 'Zweiter Vorname', $this->dataset);
            $column->SetOrderable(false);
            $column->SetDescription($this->RenderText('Zweiter Vorname des Parlamentariers'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for abkuerzung field
            //
            $column = new TextViewColumn('rat_id_abkuerzung', 'Rat', $this->dataset);
            $column->SetOrderable(false);
            $column->SetDescription($this->RenderText('Ratszugehörigkeit; Fremdschlüssel des Rates'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for abkuerzung field
            //
            $column = new TextViewColumn('kanton_id_abkuerzung', 'Kanton', $this->dataset);
            $column->SetOrderable(false);
            $column->SetDescription($this->RenderText('Kantonszugehörigkeit; Fremdschlüssel des Kantons'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for kommissionen field
            //
            $column = new TextViewColumn('kommissionen', 'Kommissionen', $this->dataset);
            $column->SetOrderable(false);
            $column->SetDescription($this->RenderText('Abkürzungen der Kommissionen des Parlamentariers (automatisch erzeugt [in_Kommission Trigger])'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for abkuerzung field
            //
            $column = new TextViewColumn('partei_id_abkuerzung', 'Partei', $this->dataset);
            $column->SetOrderable(false);
            $column->SetDescription($this->RenderText('Fremdschlüssel Partei. Leer bedeutet parteilos.'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for parteifunktion field
            //
            $column = new TextViewColumn('parteifunktion', 'Parteifunktion', $this->dataset);
            $column->SetOrderable(false);
            $column->SetDescription($this->RenderText('Funktion des Parlamentariers in der Partei'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for abkuerzung field
            //
            $column = new TextViewColumn('fraktion_id_abkuerzung', 'Fraktion', $this->dataset);
            $column->SetOrderable(false);
            $column->SetDescription($this->RenderText('Fraktionszugehörigkeit im nationalen Parlament. Fremdschlüssel.'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for fraktionsfunktion field
            //
            $column = new TextViewColumn('fraktionsfunktion', 'Fraktionsfunktion', $this->dataset);
            $column->SetOrderable(false);
            $column->SetDescription($this->RenderText('Funktion des Parlamentariers in der Fraktion'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for im_rat_seit field
            //
            $column = new DateTimeViewColumn('im_rat_seit', 'Im Rat Seit', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y');
            $column->SetOrderable(false);
            $column->SetDescription($this->RenderText('Jahr der Zugehörigkeit zum Parlament'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for im_rat_bis field
            //
            $column = new DateTimeViewColumn('im_rat_bis', 'Im Rat Bis', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y');
            $column->SetOrderable(false);
            $column->SetDescription($this->RenderText('Austrittsdatum aus dem Parlament. Leer (NULL) = aktuell im Rat, nicht leer = historischer Eintrag'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for ratsunterbruch_von field
            //
            $column = new DateTimeViewColumn('ratsunterbruch_von', 'Ratsunterbruch Von', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y');
            $column->SetOrderable(false);
            $column->SetDescription($this->RenderText('Unterbruch in der Ratstätigkeit von, leer (NULL) = kein Unterbruch'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for ratsunterbruch_bis field
            //
            $column = new DateTimeViewColumn('ratsunterbruch_bis', 'Ratsunterbruch Bis', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y');
            $column->SetOrderable(false);
            $column->SetDescription($this->RenderText('Unterbruch in der Ratstätigkeit bis, leer (NULL) = kein Unterbruch'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for beruf field
            //
            $column = new TextViewColumn('beruf', 'Beruf', $this->dataset);
            $column->SetOrderable(false);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('parlamentarierDetailViewGrid1interessengruppe_beruf_handler_list');
            $column->SetDescription($this->RenderText('Beruf des Parlamentariers'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for anzeige_name field
            //
            $column = new TextViewColumn('beruf_interessengruppe_id_anzeige_name', 'Beruf Interessengruppe Id', $this->dataset);
            $column->SetOrderable(false);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'interessengruppe.php?operation=view&pk0=%beruf_interessengruppe_id%' , '_self');
            $column->SetDescription($this->RenderText('Zuordnung (Fremdschlüssel) zu Interessengruppe für den Beruf des Parlamentariers'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for zivilstand field
            //
            $column = new TextViewColumn('zivilstand', 'Zivilstand', $this->dataset);
            $column->SetOrderable(false);
            $column->SetDescription($this->RenderText('Zivilstand'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for geschlecht field
            //
            $column = new TextViewColumn('geschlecht', 'Geschlecht', $this->dataset);
            $column->SetOrderable(false);
            $column->SetDescription($this->RenderText('Geschlecht des Parlamentariers, M=Mann, F=Frau'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for photo field
            //
            $column = new TextViewColumn('photo', 'Photo', $this->dataset);
            $column->SetOrderable(false);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('parlamentarierDetailViewGrid1interessengruppe_photo_handler_list');
            $column->SetDescription($this->RenderText('Photo des Parlamentariers (JPEG/jpg)'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for kleinbild field
            //
            $column = new TextViewColumn('kleinbild', 'Kleinbild', $this->dataset);
            $column->SetOrderable(false);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('parlamentarierDetailViewGrid1interessengruppe_kleinbild_handler_list');
            $column->SetDescription($this->RenderText('Bild 44x62 px oder leer.png'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for sitzplatz field
            //
            $column = new TextViewColumn('sitzplatz', 'Sitzplatz', $this->dataset);
            $column->SetOrderable(false);
            $column->SetDescription($this->RenderText('Sitzplatznr im Parlament. Siehe Sitzordnung auf parlament.ch'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for email field
            //
            $column = new TextViewColumn('email', 'Email', $this->dataset);
            $column->SetOrderable(false);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('parlamentarierDetailViewGrid1interessengruppe_email_handler_list');
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'mailto:%email%' , '_blank');
            $column->SetDescription($this->RenderText('E-Mail-Adresse des Parlamentariers'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for homepage field
            //
            $column = new TextViewColumn('homepage', 'Homepage', $this->dataset);
            $column->SetOrderable(false);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('parlamentarierDetailViewGrid1interessengruppe_homepage_handler_list');
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, '%homepage%' , '_blank');
            $column->SetDescription($this->RenderText('Homepage des Parlamentariers'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for geburtstag field
            //
            $column = new DateTimeViewColumn('geburtstag', 'Geburtstag', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y');
            $column->SetOrderable(false);
            $column->SetDescription($this->RenderText('Geburtstag des Parlamentariers'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for anzahl_kinder field
            //
            $column = new TextViewColumn('anzahl_kinder', 'Anzahl Kinder', $this->dataset);
            $column->SetOrderable(false);
            $column->SetDescription($this->RenderText('Anzahl der Kinder'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('militaerischer_grad_id_name', 'Militaerischer Grad Id', $this->dataset);
            $column->SetOrderable(false);
            $column->SetDescription($this->RenderText('Militärischer Grad, leer (NULL) = kein Militärdienst'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for parlament_biografie_id field
            //
            $column = new TextViewColumn('parlament_biografie_id', 'Parlament Biografie', $this->dataset);
            $column->SetOrderable(false);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'http://www.parlament.ch/d/suche/seiten/biografie.aspx?biografie_id=%parlament_biografie_id%' , '_blank');
            $column->SetDescription($this->RenderText('Biographie ID auf Parlament.ch; Dient zur Herstellung eines Links auf die Parlament.ch Seite des Parlamenteriers. Zudem kann die ID für die automatische Verarbeitung gebraucht werden.'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(false);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('parlamentarierDetailViewGrid1interessengruppe_notizen_handler_list');
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
            // View column for autorisierung_verschickt_visa field
            //
            $column = new TextViewColumn('autorisierung_verschickt_visa', 'Autorisierung Verschickt Visa', $this->dataset);
            $column->SetOrderable(false);
            $column->SetDescription($this->RenderText('Autorisierungsanfrage verschickt durch'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for autorisierung_verschickt_datum field
            //
            $column = new DateTimeViewColumn('autorisierung_verschickt_datum', 'Autorisierung Verschickt Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(false);
            $column->SetDescription($this->RenderText('Autorisierungsanfrage verschickt am. (Leer/NULL bedeutet noch keine Anfrage verschickt.)'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for autorisiert_visa field
            //
            $column = new TextViewColumn('autorisiert_visa', 'Autorisiert Visa', $this->dataset);
            $column->SetOrderable(false);
            $column->SetDescription($this->RenderText('Autorisiert durch. Sonstige Angaben als Notiz erfassen.'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for autorisiert_datum field
            //
            $column = new DateTimeViewColumn('autorisiert_datum', 'Autorisiert Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y');
            $column->SetOrderable(false);
            $column->SetDescription($this->RenderText('Autorisiert am. Leer/NULL bedeutet noch nicht autorisiert. Ein Datum bedeutet, dass die Interessenbindungen und Zutrittsberechtigten vom Parlamentarier autorisiert wurden.'));
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
            
            //
            // View column for photo_dateiname field
            //
            $column = new TextViewColumn('photo_dateiname', 'Photo Dateiname', $this->dataset);
            $column->SetOrderable(false);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('parlamentarierDetailViewGrid1interessengruppe_photo_dateiname_handler_list');
            $column->SetDescription($this->RenderText('Photodateiname ohne Erweiterung'));
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
            $result = new Grid($this, $this->dataset, 'parlamentarierDetailViewGrid1interessengruppe');
            $result->SetAllowDeleteSelected(false);
            $result->SetUseFixedHeader(true);
            $result->SetShowLineNumbers(true);
            
            $result->SetHighlightRowAtHover(false);
            $result->SetWidth('');
            $this->AddFieldColumns($result);
            //
            // View column for nachname field
            //
            $column = new TextViewColumn('nachname', 'Nachname', $this->dataset);
            $column->SetOrderable(false);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'parlamentarierDetailViewGrid1interessengruppe_nachname_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for beruf field
            //
            $column = new TextViewColumn('beruf', 'Beruf', $this->dataset);
            $column->SetOrderable(false);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'parlamentarierDetailViewGrid1interessengruppe_beruf_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for photo field
            //
            $column = new TextViewColumn('photo', 'Photo', $this->dataset);
            $column->SetOrderable(false);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'parlamentarierDetailViewGrid1interessengruppe_photo_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for kleinbild field
            //
            $column = new TextViewColumn('kleinbild', 'Kleinbild', $this->dataset);
            $column->SetOrderable(false);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'parlamentarierDetailViewGrid1interessengruppe_kleinbild_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for email field
            //
            $column = new TextViewColumn('email', 'Email', $this->dataset);
            $column->SetOrderable(false);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'mailto:%email%' , '_blank');
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'parlamentarierDetailViewGrid1interessengruppe_email_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for homepage field
            //
            $column = new TextViewColumn('homepage', 'Homepage', $this->dataset);
            $column->SetOrderable(false);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, '%homepage%' , '_blank');
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'parlamentarierDetailViewGrid1interessengruppe_homepage_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(false);
            $column->SetReplaceLFByBR(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'parlamentarierDetailViewGrid1interessengruppe_notizen_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for photo_dateiname field
            //
            $column = new TextViewColumn('photo_dateiname', 'Photo Dateiname', $this->dataset);
            $column->SetOrderable(false);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'parlamentarierDetailViewGrid1interessengruppe_photo_dateiname_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            return $result;
        }
    }
    
    
    
    // OnBeforePageExecute event handler
    
    
    
    class parlamentarierDetailEdit1interessengruppePage extends DetailPageEdit
    {
        protected function DoBeforeCreate()
        {
            $this->dataset = new TableDataset(
                new MyPDOConnectionFactory(),
                GetConnectionOptions(),
                '`parlamentarier`');
            $field = new IntegerField('id', null, null, true);
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, true);
            $field = new StringField('nachname');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('vorname');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('zweiter_vorname');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('rat_id');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new IntegerField('kanton_id');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('kommissionen');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('partei_id');
            $this->dataset->AddField($field, false);
            $field = new StringField('parteifunktion');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new IntegerField('fraktion_id');
            $this->dataset->AddField($field, false);
            $field = new StringField('fraktionsfunktion');
            $this->dataset->AddField($field, false);
            $field = new DateField('im_rat_seit');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new DateField('im_rat_bis');
            $this->dataset->AddField($field, false);
            $field = new DateField('ratswechsel');
            $this->dataset->AddField($field, false);
            $field = new DateField('ratsunterbruch_von');
            $this->dataset->AddField($field, false);
            $field = new DateField('ratsunterbruch_bis');
            $this->dataset->AddField($field, false);
            $field = new StringField('beruf');
            $this->dataset->AddField($field, false);
            $field = new StringField('beruf_fr');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('beruf_interessengruppe_id');
            $this->dataset->AddField($field, false);
            $field = new StringField('zivilstand');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('anzahl_kinder');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('militaerischer_grad_id');
            $this->dataset->AddField($field, false);
            $field = new StringField('geschlecht');
            $this->dataset->AddField($field, false);
            $field = new DateField('geburtstag');
            $this->dataset->AddField($field, false);
            $field = new StringField('photo');
            $this->dataset->AddField($field, false);
            $field = new StringField('photo_dateiname');
            $this->dataset->AddField($field, false);
            $field = new StringField('photo_dateierweiterung');
            $this->dataset->AddField($field, false);
            $field = new StringField('photo_dateiname_voll');
            $this->dataset->AddField($field, false);
            $field = new StringField('photo_mime_type');
            $this->dataset->AddField($field, false);
            $field = new StringField('kleinbild');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('sitzplatz');
            $this->dataset->AddField($field, false);
            $field = new StringField('email');
            $this->dataset->AddField($field, false);
            $field = new StringField('homepage');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('parlament_biografie_id');
            $this->dataset->AddField($field, false);
            $field = new StringField('twitter_name');
            $this->dataset->AddField($field, false);
            $field = new StringField('linkedin_profil_url');
            $this->dataset->AddField($field, false);
            $field = new StringField('xing_profil_name');
            $this->dataset->AddField($field, false);
            $field = new StringField('facebook_name');
            $this->dataset->AddField($field, false);
            $field = new StringField('arbeitssprache');
            $this->dataset->AddField($field, false);
            $field = new StringField('adresse_firma');
            $this->dataset->AddField($field, false);
            $field = new StringField('adresse_strasse');
            $this->dataset->AddField($field, false);
            $field = new StringField('adresse_zusatz');
            $this->dataset->AddField($field, false);
            $field = new StringField('adresse_plz');
            $this->dataset->AddField($field, false);
            $field = new StringField('adresse_ort');
            $this->dataset->AddField($field, false);
            $field = new StringField('telephon_1');
            $this->dataset->AddField($field, false);
            $field = new StringField('telephon_2');
            $this->dataset->AddField($field, false);
            $field = new StringField('erfasst');
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
            $field = new StringField('autorisierung_verschickt_visa');
            $this->dataset->AddField($field, false);
            $field = new DateTimeField('autorisierung_verschickt_datum');
            $this->dataset->AddField($field, false);
            $field = new StringField('autorisiert_visa');
            $this->dataset->AddField($field, false);
            $field = new DateField('autorisiert_datum');
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
            $this->dataset->AddLookupField('rat_id', 'rat', new IntegerField('id', null, null, true), new StringField('abkuerzung', 'rat_id_abkuerzung', 'rat_id_abkuerzung_rat'), 'rat_id_abkuerzung_rat');
            $this->dataset->AddLookupField('kanton_id', 'kanton', new IntegerField('id', null, null, true), new StringField('abkuerzung', 'kanton_id_abkuerzung', 'kanton_id_abkuerzung_kanton'), 'kanton_id_abkuerzung_kanton');
            $this->dataset->AddLookupField('partei_id', 'v_partei', new IntegerField('id'), new StringField('abkuerzung', 'partei_id_abkuerzung', 'partei_id_abkuerzung_v_partei'), 'partei_id_abkuerzung_v_partei');
            $this->dataset->AddLookupField('fraktion_id', 'v_fraktion', new IntegerField('id'), new StringField('abkuerzung', 'fraktion_id_abkuerzung', 'fraktion_id_abkuerzung_v_fraktion'), 'fraktion_id_abkuerzung_v_fraktion');
            $this->dataset->AddLookupField('beruf_interessengruppe_id', 'v_interessengruppe_simple', new IntegerField('id'), new StringField('anzeige_name', 'beruf_interessengruppe_id_anzeige_name', 'beruf_interessengruppe_id_anzeige_name_v_interessengruppe_simple'), 'beruf_interessengruppe_id_anzeige_name_v_interessengruppe_simple');
            $this->dataset->AddLookupField('militaerischer_grad_id', 'mil_grad', new IntegerField('id', null, null, true), new StringField('name', 'militaerischer_grad_id_name', 'militaerischer_grad_id_name_mil_grad'), 'militaerischer_grad_id_name_mil_grad');
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
            $grid->SearchControl = new SimpleSearch('parlamentarierDetailEdit1interessengruppessearch', $this->dataset,
                array('id', 'nachname', 'vorname', 'zweiter_vorname', 'rat_id_abkuerzung', 'kanton_id_abkuerzung', 'kommissionen', 'partei_id_abkuerzung', 'parteifunktion', 'fraktion_id_abkuerzung', 'fraktionsfunktion', 'im_rat_seit', 'im_rat_bis', 'ratsunterbruch_von', 'ratsunterbruch_bis', 'beruf', 'beruf_interessengruppe_id_anzeige_name', 'zivilstand', 'geschlecht', 'photo', 'kleinbild', 'sitzplatz', 'email', 'homepage', 'geburtstag', 'anzahl_kinder', 'militaerischer_grad_id_name', 'parlament_biografie_id', 'notizen', 'eingabe_abgeschlossen_visa', 'eingabe_abgeschlossen_datum', 'kontrolliert_visa', 'kontrolliert_datum', 'autorisierung_verschickt_visa', 'autorisierung_verschickt_datum', 'autorisiert_visa', 'autorisiert_datum', 'freigabe_visa', 'freigabe_datum', 'created_visa', 'created_date', 'updated_visa', 'updated_date', 'photo_dateiname'),
                array($this->RenderText('Id'), $this->RenderText('Nachname'), $this->RenderText('Vorname'), $this->RenderText('Zweiter Vorname'), $this->RenderText('Rat'), $this->RenderText('Kanton'), $this->RenderText('Kommissionen'), $this->RenderText('Partei'), $this->RenderText('Parteifunktion'), $this->RenderText('Fraktion'), $this->RenderText('Fraktionsfunktion'), $this->RenderText('Im Rat Seit'), $this->RenderText('Im Rat Bis'), $this->RenderText('Ratsunterbruch Von'), $this->RenderText('Ratsunterbruch Bis'), $this->RenderText('Beruf'), $this->RenderText('Beruf Interessengruppe Id'), $this->RenderText('Zivilstand'), $this->RenderText('Geschlecht'), $this->RenderText('Photo'), $this->RenderText('Kleinbild'), $this->RenderText('Sitzplatz'), $this->RenderText('Email'), $this->RenderText('Homepage'), $this->RenderText('Geburtstag'), $this->RenderText('Anzahl Kinder'), $this->RenderText('Militaerischer Grad Id'), $this->RenderText('Parlament Biografie'), $this->RenderText('Notizen'), $this->RenderText('Eingabe Abgeschlossen Visa'), $this->RenderText('Eingabe Abgeschlossen Datum'), $this->RenderText('Kontrolliert Visa'), $this->RenderText('Kontrolliert Datum'), $this->RenderText('Autorisierung Verschickt Visa'), $this->RenderText('Autorisierung Verschickt Datum'), $this->RenderText('Autorisiert Visa'), $this->RenderText('Autorisiert Datum'), $this->RenderText('Freigabe Visa'), $this->RenderText('Freigabe Datum'), $this->RenderText('Created Visa'), $this->RenderText('Created Date'), $this->RenderText('Updated Visa'), $this->RenderText('Updated Date'), $this->RenderText('Photo Dateiname')),
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
            $this->AdvancedSearchControl = new AdvancedSearchControl('parlamentarierDetailEdit1interessengruppeasearch', $this->dataset, $this->GetLocalizerCaptions(), $this->GetColumnVariableContainer(), $this->CreateLinkBuilder());
            $this->AdvancedSearchControl->setTimerInterval(1000);
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('id', $this->RenderText('Id')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('nachname', $this->RenderText('Nachname')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('vorname', $this->RenderText('Vorname')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('zweiter_vorname', $this->RenderText('Zweiter Vorname')));
            
            $lookupDataset = new TableDataset(
                new MyPDOConnectionFactory(),
                GetConnectionOptions(),
                '`rat`');
            $field = new IntegerField('id', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('abkuerzung');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('abkuerzung_fr');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('name_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('name_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('name_it');
            $lookupDataset->AddField($field, false);
            $field = new StringField('name_en');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_mitglieder');
            $lookupDataset->AddField($field, false);
            $field = new StringField('typ');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('interessenraum_id');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzeigestufe');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('gewicht');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung');
            $lookupDataset->AddField($field, false);
            $field = new StringField('homepage_de');
            $lookupDataset->AddField($field, false);
            $field = new StringField('homepage_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('homepage_it');
            $lookupDataset->AddField($field, false);
            $field = new StringField('homepage_en');
            $lookupDataset->AddField($field, false);
            $field = new StringField('mitglied_bezeichnung_maennlich_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('mitglied_bezeichnung_weiblich_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('mitglied_bezeichnung_maennlich_fr');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('mitglied_bezeichnung_weiblich_fr');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('parlament_id');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('parlament_type');
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
            $lookupDataset->SetOrderBy('abkuerzung', GetOrderTypeAsSQL(otAscending));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateLookupSearchInput('rat_id', $this->RenderText('Rat'), $lookupDataset, 'id', 'abkuerzung', false, 8));
            
            $lookupDataset = new TableDataset(
                new MyPDOConnectionFactory(),
                GetConnectionOptions(),
                '`kanton`');
            $field = new IntegerField('id', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('abkuerzung');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('kantonsnr');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('name_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('name_fr');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('name_it');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_staenderaete');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('amtssprache');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('hauptort_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('hauptort_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('hauptort_it');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('flaeche_km2');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('beitrittsjahr');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('wappen_klein');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('wappen');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('lagebild');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('homepage');
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung');
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
            $lookupDataset->SetOrderBy('abkuerzung', GetOrderTypeAsSQL(otAscending));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateLookupSearchInput('kanton_id', $this->RenderText('Kanton'), $lookupDataset, 'id', 'abkuerzung', false, 8));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('kommissionen', $this->RenderText('Kommissionen')));
            
            $lookupDataset = new TableDataset(
                new MyPDOConnectionFactory(),
                GetConnectionOptions(),
                '`v_partei`');
            $field = new StringField('anzeige_name');
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_de');
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_fr');
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
            $lookupDataset->AddField($field, false);
            $field = new StringField('name_fr');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('fraktion_id');
            $lookupDataset->AddField($field, false);
            $field = new DateField('gruendung');
            $lookupDataset->AddField($field, false);
            $field = new StringField('position');
            $lookupDataset->AddField($field, false);
            $field = new StringField('farbcode');
            $lookupDataset->AddField($field, false);
            $field = new StringField('homepage');
            $lookupDataset->AddField($field, false);
            $field = new StringField('homepage_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('email');
            $lookupDataset->AddField($field, false);
            $field = new StringField('email_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('twitter_name');
            $lookupDataset->AddField($field, false);
            $field = new StringField('twitter_name_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung');
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung_fr');
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
            $lookupDataset->AddField($field, false);
            $field = new StringField('abkuerzung_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung_de');
            $lookupDataset->AddField($field, false);
            $field = new StringField('homepage_de');
            $lookupDataset->AddField($field, false);
            $field = new StringField('twitter_name_de');
            $lookupDataset->AddField($field, false);
            $field = new StringField('email_de');
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
            $lookupDataset->SetOrderBy('abkuerzung', GetOrderTypeAsSQL(otAscending));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateLookupSearchInput('partei_id', $this->RenderText('Partei'), $lookupDataset, 'id', 'abkuerzung', false, 8));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('parteifunktion', $this->RenderText('Parteifunktion')));
            
            $lookupDataset = new TableDataset(
                new MyPDOConnectionFactory(),
                GetConnectionOptions(),
                '`v_fraktion`');
            $field = new StringField('anzeige_name');
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_de');
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_fr');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('id');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('abkuerzung');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('name');
            $lookupDataset->AddField($field, false);
            $field = new StringField('name_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('position');
            $lookupDataset->AddField($field, false);
            $field = new StringField('farbcode');
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung');
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung_fr');
            $lookupDataset->AddField($field, false);
            $field = new DateField('von');
            $lookupDataset->AddField($field, false);
            $field = new DateField('bis');
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
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung_de');
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
            $lookupDataset->SetOrderBy('abkuerzung', GetOrderTypeAsSQL(otAscending));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateLookupSearchInput('fraktion_id', $this->RenderText('Fraktion'), $lookupDataset, 'id', 'abkuerzung', false, 8));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('fraktionsfunktion', $this->RenderText('Fraktionsfunktion')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateDateTimeSearchInput('im_rat_seit', $this->RenderText('Im Rat Seit'), 'd.m.Y'));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateDateTimeSearchInput('im_rat_bis', $this->RenderText('Im Rat Bis'), 'd.m.Y'));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateDateTimeSearchInput('ratsunterbruch_von', $this->RenderText('Ratsunterbruch Von'), 'd.m.Y'));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateDateTimeSearchInput('ratsunterbruch_bis', $this->RenderText('Ratsunterbruch Bis'), 'd.m.Y'));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('beruf', $this->RenderText('Beruf')));
            
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
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateLookupSearchInput('beruf_interessengruppe_id', $this->RenderText('Beruf Interessengruppe Id'), $lookupDataset, 'id', 'anzeige_name', false, 8));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('zivilstand', $this->RenderText('Zivilstand')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('geschlecht', $this->RenderText('Geschlecht')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('photo', $this->RenderText('Photo')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('kleinbild', $this->RenderText('Kleinbild')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('sitzplatz', $this->RenderText('Sitzplatz')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('email', $this->RenderText('Email')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('homepage', $this->RenderText('Homepage')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateDateTimeSearchInput('geburtstag', $this->RenderText('Geburtstag'), 'd.m.Y'));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('anzahl_kinder', $this->RenderText('Anzahl Kinder')));
            
            $lookupDataset = new TableDataset(
                new MyPDOConnectionFactory(),
                GetConnectionOptions(),
                '`mil_grad`');
            $field = new IntegerField('id', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('name');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('name_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('abkuerzung');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('abkuerzung_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('typ');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('ranghoehe');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzeigestufe');
            $field->SetIsNotNull(true);
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
            $lookupDataset->SetOrderBy('name', GetOrderTypeAsSQL(otAscending));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateLookupSearchInput('militaerischer_grad_id', $this->RenderText('Militaerischer Grad Id'), $lookupDataset, 'id', 'name', false, 8));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('parlament_biografie_id', $this->RenderText('Parlament Biografie')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('notizen', $this->RenderText('Notizen')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('eingabe_abgeschlossen_visa', $this->RenderText('Eingabe Abgeschlossen Visa')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateDateTimeSearchInput('eingabe_abgeschlossen_datum', $this->RenderText('Eingabe Abgeschlossen Datum'), 'd.m.Y H:i:s'));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('kontrolliert_visa', $this->RenderText('Kontrolliert Visa')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateDateTimeSearchInput('kontrolliert_datum', $this->RenderText('Kontrolliert Datum'), 'd.m.Y H:i:s'));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('autorisierung_verschickt_visa', $this->RenderText('Autorisierung Verschickt Visa')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateDateTimeSearchInput('autorisierung_verschickt_datum', $this->RenderText('Autorisierung Verschickt Datum'), 'd.m.Y H:i:s'));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('autorisiert_visa', $this->RenderText('Autorisiert Visa')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateDateTimeSearchInput('autorisiert_datum', $this->RenderText('Autorisiert Datum'), 'd.m.Y'));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('freigabe_visa', $this->RenderText('Freigabe Visa')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateDateTimeSearchInput('freigabe_datum', $this->RenderText('Freigabe Datum'), 'd.m.Y H:i:s'));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('created_visa', $this->RenderText('Created Visa')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateDateTimeSearchInput('created_date', $this->RenderText('Created Date'), 'd.m.Y H:i:s'));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('updated_visa', $this->RenderText('Updated Visa')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateDateTimeSearchInput('updated_date', $this->RenderText('Updated Date'), 'd.m.Y H:i:s'));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('photo_dateiname', $this->RenderText('Photo Dateiname')));
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
            $column->SetDescription($this->RenderText('Technischer Schlüssel des Parlamentariers'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for nachname field
            //
            $column = new TextViewColumn('nachname', 'Nachname', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('parlamentarierDetailEditGrid1interessengruppe_nachname_handler_list');
            $column->SetDescription($this->RenderText('Nachname des Parlamentariers'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for vorname field
            //
            $column = new TextViewColumn('vorname', 'Vorname', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Vornahme des Parlamentariers'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for zweiter_vorname field
            //
            $column = new TextViewColumn('zweiter_vorname', 'Zweiter Vorname', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Zweiter Vorname des Parlamentariers'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for abkuerzung field
            //
            $column = new TextViewColumn('rat_id_abkuerzung', 'Rat', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Ratszugehörigkeit; Fremdschlüssel des Rates'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for abkuerzung field
            //
            $column = new TextViewColumn('kanton_id_abkuerzung', 'Kanton', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Kantonszugehörigkeit; Fremdschlüssel des Kantons'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for kommissionen field
            //
            $column = new TextViewColumn('kommissionen', 'Kommissionen', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Abkürzungen der Kommissionen des Parlamentariers (automatisch erzeugt [in_Kommission Trigger])'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for abkuerzung field
            //
            $column = new TextViewColumn('partei_id_abkuerzung', 'Partei', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Fremdschlüssel Partei. Leer bedeutet parteilos.'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for parteifunktion field
            //
            $column = new TextViewColumn('parteifunktion', 'Parteifunktion', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Funktion des Parlamentariers in der Partei'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for abkuerzung field
            //
            $column = new TextViewColumn('fraktion_id_abkuerzung', 'Fraktion', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Fraktionszugehörigkeit im nationalen Parlament. Fremdschlüssel.'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for fraktionsfunktion field
            //
            $column = new TextViewColumn('fraktionsfunktion', 'Fraktionsfunktion', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Funktion des Parlamentariers in der Fraktion'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for im_rat_seit field
            //
            $column = new DateTimeViewColumn('im_rat_seit', 'Im Rat Seit', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y');
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Jahr der Zugehörigkeit zum Parlament'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for im_rat_bis field
            //
            $column = new DateTimeViewColumn('im_rat_bis', 'Im Rat Bis', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y');
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Austrittsdatum aus dem Parlament. Leer (NULL) = aktuell im Rat, nicht leer = historischer Eintrag'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for ratsunterbruch_von field
            //
            $column = new DateTimeViewColumn('ratsunterbruch_von', 'Ratsunterbruch Von', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y');
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Unterbruch in der Ratstätigkeit von, leer (NULL) = kein Unterbruch'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for ratsunterbruch_bis field
            //
            $column = new DateTimeViewColumn('ratsunterbruch_bis', 'Ratsunterbruch Bis', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y');
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Unterbruch in der Ratstätigkeit bis, leer (NULL) = kein Unterbruch'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for beruf field
            //
            $column = new TextViewColumn('beruf', 'Beruf', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('parlamentarierDetailEditGrid1interessengruppe_beruf_handler_list');
            $column->SetDescription($this->RenderText('Beruf des Parlamentariers'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for anzeige_name field
            //
            $column = new TextViewColumn('beruf_interessengruppe_id_anzeige_name', 'Beruf Interessengruppe Id', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'interessengruppe.php?operation=view&pk0=%beruf_interessengruppe_id%' , '_self');
            $column->SetDescription($this->RenderText('Zuordnung (Fremdschlüssel) zu Interessengruppe für den Beruf des Parlamentariers'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for zivilstand field
            //
            $column = new TextViewColumn('zivilstand', 'Zivilstand', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Zivilstand'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for geschlecht field
            //
            $column = new TextViewColumn('geschlecht', 'Geschlecht', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Geschlecht des Parlamentariers, M=Mann, F=Frau'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for photo field
            //
            $column = new TextViewColumn('photo', 'Photo', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('parlamentarierDetailEditGrid1interessengruppe_photo_handler_list');
            $column->SetDescription($this->RenderText('Photo des Parlamentariers (JPEG/jpg)'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for kleinbild field
            //
            $column = new TextViewColumn('kleinbild', 'Kleinbild', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('parlamentarierDetailEditGrid1interessengruppe_kleinbild_handler_list');
            $column->SetDescription($this->RenderText('Bild 44x62 px oder leer.png'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for sitzplatz field
            //
            $column = new TextViewColumn('sitzplatz', 'Sitzplatz', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Sitzplatznr im Parlament. Siehe Sitzordnung auf parlament.ch'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for email field
            //
            $column = new TextViewColumn('email', 'Email', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('parlamentarierDetailEditGrid1interessengruppe_email_handler_list');
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'mailto:%email%' , '_blank');
            $column->SetDescription($this->RenderText('E-Mail-Adresse des Parlamentariers'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for homepage field
            //
            $column = new TextViewColumn('homepage', 'Homepage', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('parlamentarierDetailEditGrid1interessengruppe_homepage_handler_list');
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, '%homepage%' , '_blank');
            $column->SetDescription($this->RenderText('Homepage des Parlamentariers'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for geburtstag field
            //
            $column = new DateTimeViewColumn('geburtstag', 'Geburtstag', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y');
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Geburtstag des Parlamentariers'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for anzahl_kinder field
            //
            $column = new TextViewColumn('anzahl_kinder', 'Anzahl Kinder', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Anzahl der Kinder'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('militaerischer_grad_id_name', 'Militaerischer Grad Id', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Militärischer Grad, leer (NULL) = kein Militärdienst'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for parlament_biografie_id field
            //
            $column = new TextViewColumn('parlament_biografie_id', 'Parlament Biografie', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'http://www.parlament.ch/d/suche/seiten/biografie.aspx?biografie_id=%parlament_biografie_id%' , '_blank');
            $column->SetDescription($this->RenderText('Biographie ID auf Parlament.ch; Dient zur Herstellung eines Links auf die Parlament.ch Seite des Parlamenteriers. Zudem kann die ID für die automatische Verarbeitung gebraucht werden.'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('parlamentarierDetailEditGrid1interessengruppe_notizen_handler_list');
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
            // View column for autorisierung_verschickt_visa field
            //
            $column = new TextViewColumn('autorisierung_verschickt_visa', 'Autorisierung Verschickt Visa', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Autorisierungsanfrage verschickt durch'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for autorisierung_verschickt_datum field
            //
            $column = new DateTimeViewColumn('autorisierung_verschickt_datum', 'Autorisierung Verschickt Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Autorisierungsanfrage verschickt am. (Leer/NULL bedeutet noch keine Anfrage verschickt.)'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for autorisiert_visa field
            //
            $column = new TextViewColumn('autorisiert_visa', 'Autorisiert Visa', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Autorisiert durch. Sonstige Angaben als Notiz erfassen.'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for autorisiert_datum field
            //
            $column = new DateTimeViewColumn('autorisiert_datum', 'Autorisiert Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y');
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText('Autorisiert am. Leer/NULL bedeutet noch nicht autorisiert. Ein Datum bedeutet, dass die Interessenbindungen und Zutrittsberechtigten vom Parlamentarier autorisiert wurden.'));
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
            
            //
            // View column for photo_dateiname field
            //
            $column = new TextViewColumn('photo_dateiname', 'Photo Dateiname', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('parlamentarierDetailEditGrid1interessengruppe_photo_dateiname_handler_list');
            $column->SetDescription($this->RenderText('Photodateiname ohne Erweiterung'));
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
            // View column for nachname field
            //
            $column = new TextViewColumn('nachname', 'Nachname', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('parlamentarierDetailEditGrid1interessengruppe_nachname_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for vorname field
            //
            $column = new TextViewColumn('vorname', 'Vorname', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for zweiter_vorname field
            //
            $column = new TextViewColumn('zweiter_vorname', 'Zweiter Vorname', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for abkuerzung field
            //
            $column = new TextViewColumn('rat_id_abkuerzung', 'Rat', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for abkuerzung field
            //
            $column = new TextViewColumn('kanton_id_abkuerzung', 'Kanton', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for kommissionen field
            //
            $column = new TextViewColumn('kommissionen', 'Kommissionen', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for abkuerzung field
            //
            $column = new TextViewColumn('partei_id_abkuerzung', 'Partei', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for parteifunktion field
            //
            $column = new TextViewColumn('parteifunktion', 'Parteifunktion', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for abkuerzung field
            //
            $column = new TextViewColumn('fraktion_id_abkuerzung', 'Fraktion', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for fraktionsfunktion field
            //
            $column = new TextViewColumn('fraktionsfunktion', 'Fraktionsfunktion', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for im_rat_seit field
            //
            $column = new DateTimeViewColumn('im_rat_seit', 'Im Rat Seit', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for im_rat_bis field
            //
            $column = new DateTimeViewColumn('im_rat_bis', 'Im Rat Bis', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for ratsunterbruch_von field
            //
            $column = new DateTimeViewColumn('ratsunterbruch_von', 'Ratsunterbruch Von', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for ratsunterbruch_bis field
            //
            $column = new DateTimeViewColumn('ratsunterbruch_bis', 'Ratsunterbruch Bis', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for beruf field
            //
            $column = new TextViewColumn('beruf', 'Beruf', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('parlamentarierDetailEditGrid1interessengruppe_beruf_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for anzeige_name field
            //
            $column = new TextViewColumn('beruf_interessengruppe_id_anzeige_name', 'Beruf Interessengruppe Id', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'interessengruppe.php?operation=view&pk0=%beruf_interessengruppe_id%' , '_self');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for zivilstand field
            //
            $column = new TextViewColumn('zivilstand', 'Zivilstand', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for geschlecht field
            //
            $column = new TextViewColumn('geschlecht', 'Geschlecht', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for photo field
            //
            $column = new TextViewColumn('photo', 'Photo', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('parlamentarierDetailEditGrid1interessengruppe_photo_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for kleinbild field
            //
            $column = new TextViewColumn('kleinbild', 'Kleinbild', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('parlamentarierDetailEditGrid1interessengruppe_kleinbild_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for sitzplatz field
            //
            $column = new TextViewColumn('sitzplatz', 'Sitzplatz', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for email field
            //
            $column = new TextViewColumn('email', 'Email', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('parlamentarierDetailEditGrid1interessengruppe_email_handler_view');
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'mailto:%email%' , '_blank');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for homepage field
            //
            $column = new TextViewColumn('homepage', 'Homepage', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('parlamentarierDetailEditGrid1interessengruppe_homepage_handler_view');
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, '%homepage%' , '_blank');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for geburtstag field
            //
            $column = new DateTimeViewColumn('geburtstag', 'Geburtstag', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for anzahl_kinder field
            //
            $column = new TextViewColumn('anzahl_kinder', 'Anzahl Kinder', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('militaerischer_grad_id_name', 'Militaerischer Grad Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for parlament_biografie_id field
            //
            $column = new TextViewColumn('parlament_biografie_id', 'Parlament Biografie', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'http://www.parlament.ch/d/suche/seiten/biografie.aspx?biografie_id=%parlament_biografie_id%' , '_blank');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('parlamentarierDetailEditGrid1interessengruppe_notizen_handler_view');
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
            // View column for autorisierung_verschickt_visa field
            //
            $column = new TextViewColumn('autorisierung_verschickt_visa', 'Autorisierung Verschickt Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for autorisierung_verschickt_datum field
            //
            $column = new DateTimeViewColumn('autorisierung_verschickt_datum', 'Autorisierung Verschickt Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for autorisiert_visa field
            //
            $column = new TextViewColumn('autorisiert_visa', 'Autorisiert Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for autorisiert_datum field
            //
            $column = new DateTimeViewColumn('autorisiert_datum', 'Autorisiert Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y');
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
            // View column for photo_dateiname field
            //
            $column = new TextViewColumn('photo_dateiname', 'Photo Dateiname', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('parlamentarierDetailEditGrid1interessengruppe_photo_dateiname_handler_view');
            $grid->AddSingleRecordViewColumn($column);
        }
    
        protected function AddEditColumns(Grid $grid)
        {
            //
            // Edit column for nachname field
            //
            $editor = new TextEdit('nachname_edit');
            $editor->SetSize(100);
            $editor->SetMaxLength(100);
            $editColumn = new CustomEditColumn('Nachname', 'nachname', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for vorname field
            //
            $editor = new TextEdit('vorname_edit');
            $editor->SetSize(50);
            $editor->SetMaxLength(50);
            $editColumn = new CustomEditColumn('Vorname', 'vorname', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for zweiter_vorname field
            //
            $editor = new TextEdit('zweiter_vorname_edit');
            $editor->SetSize(50);
            $editor->SetMaxLength(50);
            $editColumn = new CustomEditColumn('Zweiter Vorname', 'zweiter_vorname', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for rat_id field
            //
            $editor = new ComboBox('rat_id_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                new MyPDOConnectionFactory(),
                GetConnectionOptions(),
                '`rat`');
            $field = new IntegerField('id', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('abkuerzung');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('abkuerzung_fr');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('name_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('name_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('name_it');
            $lookupDataset->AddField($field, false);
            $field = new StringField('name_en');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_mitglieder');
            $lookupDataset->AddField($field, false);
            $field = new StringField('typ');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('interessenraum_id');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzeigestufe');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('gewicht');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung');
            $lookupDataset->AddField($field, false);
            $field = new StringField('homepage_de');
            $lookupDataset->AddField($field, false);
            $field = new StringField('homepage_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('homepage_it');
            $lookupDataset->AddField($field, false);
            $field = new StringField('homepage_en');
            $lookupDataset->AddField($field, false);
            $field = new StringField('mitglied_bezeichnung_maennlich_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('mitglied_bezeichnung_weiblich_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('mitglied_bezeichnung_maennlich_fr');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('mitglied_bezeichnung_weiblich_fr');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('parlament_id');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('parlament_type');
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
            $lookupDataset->SetOrderBy('abkuerzung', GetOrderTypeAsSQL(otAscending));
            $editColumn = new LookUpEditColumn(
                'Rat', 
                'rat_id', 
                $editor, 
                $this->dataset, 'id', 'abkuerzung', $lookupDataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for kanton_id field
            //
            $editor = new ComboBox('kanton_id_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                new MyPDOConnectionFactory(),
                GetConnectionOptions(),
                '`kanton`');
            $field = new IntegerField('id', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('abkuerzung');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('kantonsnr');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('name_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('name_fr');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('name_it');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_staenderaete');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('amtssprache');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('hauptort_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('hauptort_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('hauptort_it');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('flaeche_km2');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('beitrittsjahr');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('wappen_klein');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('wappen');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('lagebild');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('homepage');
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung');
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
            $lookupDataset->SetOrderBy('abkuerzung', GetOrderTypeAsSQL(otAscending));
            $editColumn = new LookUpEditColumn(
                'Kanton', 
                'kanton_id', 
                $editor, 
                $this->dataset, 'id', 'abkuerzung', $lookupDataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for kommissionen field
            //
            $editor = new TextEdit('kommissionen_edit');
            $editor->SetSize(75);
            $editor->SetMaxLength(75);
            $editColumn = new CustomEditColumn('Kommissionen', 'kommissionen', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for partei_id field
            //
            $editor = new ComboBox('partei_id_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                new MyPDOConnectionFactory(),
                GetConnectionOptions(),
                '`v_partei`');
            $field = new StringField('anzeige_name');
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_de');
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_fr');
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
            $lookupDataset->AddField($field, false);
            $field = new StringField('name_fr');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('fraktion_id');
            $lookupDataset->AddField($field, false);
            $field = new DateField('gruendung');
            $lookupDataset->AddField($field, false);
            $field = new StringField('position');
            $lookupDataset->AddField($field, false);
            $field = new StringField('farbcode');
            $lookupDataset->AddField($field, false);
            $field = new StringField('homepage');
            $lookupDataset->AddField($field, false);
            $field = new StringField('homepage_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('email');
            $lookupDataset->AddField($field, false);
            $field = new StringField('email_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('twitter_name');
            $lookupDataset->AddField($field, false);
            $field = new StringField('twitter_name_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung');
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung_fr');
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
            $lookupDataset->AddField($field, false);
            $field = new StringField('abkuerzung_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung_de');
            $lookupDataset->AddField($field, false);
            $field = new StringField('homepage_de');
            $lookupDataset->AddField($field, false);
            $field = new StringField('twitter_name_de');
            $lookupDataset->AddField($field, false);
            $field = new StringField('email_de');
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
            $lookupDataset->SetOrderBy('abkuerzung', GetOrderTypeAsSQL(otAscending));
            $editColumn = new LookUpEditColumn(
                'Partei', 
                'partei_id', 
                $editor, 
                $this->dataset, 'id', 'abkuerzung', $lookupDataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for parteifunktion field
            //
            $editor = new CheckBoxGroup('parteifunktion_edit');
            $editor->SetDisplayMode(CheckBoxGroup::StackedMode);
            $editor->AddValue('mitglied', $this->RenderText('mitglied'));
            $editor->AddValue('praesident', $this->RenderText('praesident'));
            $editor->AddValue('vizepraesident', $this->RenderText('vizepraesident'));
            $editor->AddValue('fraktionschef', $this->RenderText('fraktionschef'));
            $editColumn = new CustomEditColumn('Parteifunktion', 'parteifunktion', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for fraktion_id field
            //
            $editor = new ComboBox('fraktion_id_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                new MyPDOConnectionFactory(),
                GetConnectionOptions(),
                '`v_fraktion`');
            $field = new StringField('anzeige_name');
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_de');
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_fr');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('id');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('abkuerzung');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('name');
            $lookupDataset->AddField($field, false);
            $field = new StringField('name_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('position');
            $lookupDataset->AddField($field, false);
            $field = new StringField('farbcode');
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung');
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung_fr');
            $lookupDataset->AddField($field, false);
            $field = new DateField('von');
            $lookupDataset->AddField($field, false);
            $field = new DateField('bis');
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
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung_de');
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
            $lookupDataset->SetOrderBy('abkuerzung', GetOrderTypeAsSQL(otAscending));
            $editColumn = new LookUpEditColumn(
                'Fraktion', 
                'fraktion_id', 
                $editor, 
                $this->dataset, 'id', 'abkuerzung', $lookupDataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for fraktionsfunktion field
            //
            $editor = new ComboBox('fraktionsfunktion_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $editor->AddValue('mitglied', $this->RenderText('mitglied'));
            $editor->AddValue('praesident', $this->RenderText('praesident'));
            $editor->AddValue('vizepraesident', $this->RenderText('vizepraesident'));
            $editColumn = new CustomEditColumn('Fraktionsfunktion', 'fraktionsfunktion', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for im_rat_seit field
            //
            $editor = new DateTimeEdit('im_rat_seit_edit', true, 'Y-m-d H:i:s', GetFirstDayOfWeek());
            $editColumn = new CustomEditColumn('Im Rat Seit', 'im_rat_seit', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for im_rat_bis field
            //
            $editor = new DateTimeEdit('im_rat_bis_edit', true, 'Y-m-d H:i:s', GetFirstDayOfWeek());
            $editColumn = new CustomEditColumn('Im Rat Bis', 'im_rat_bis', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for ratsunterbruch_von field
            //
            $editor = new DateTimeEdit('ratsunterbruch_von_edit', true, 'Y-m-d H:i:s', GetFirstDayOfWeek());
            $editColumn = new CustomEditColumn('Ratsunterbruch Von', 'ratsunterbruch_von', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for ratsunterbruch_bis field
            //
            $editor = new DateTimeEdit('ratsunterbruch_bis_edit', true, 'Y-m-d H:i:s', GetFirstDayOfWeek());
            $editColumn = new CustomEditColumn('Ratsunterbruch Bis', 'ratsunterbruch_bis', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for beruf field
            //
            $editor = new TextAreaEdit('beruf_edit', 50, 8);
            $editColumn = new CustomEditColumn('Beruf', 'beruf', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for beruf_interessengruppe_id field
            //
            $editor = new ComboBox('beruf_interessengruppe_id_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
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
                'Beruf Interessengruppe Id', 
                'beruf_interessengruppe_id', 
                $editor, 
                $this->dataset, 'id', 'anzeige_name', $lookupDataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for zivilstand field
            //
            $editor = new ComboBox('zivilstand_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $editor->AddValue('ledig', $this->RenderText('ledig'));
            $editor->AddValue('verheirated', $this->RenderText('verheirated'));
            $editor->AddValue('geschieden', $this->RenderText('geschieden'));
            $editor->AddValue('eingetragene partnerschaft', $this->RenderText('eingetragene partnerschaft'));
            $editColumn = new CustomEditColumn('Zivilstand', 'zivilstand', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for geschlecht field
            //
            $editor = new ComboBox('geschlecht_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $editor->AddValue('M', $this->RenderText('M'));
            $editor->AddValue('F', $this->RenderText('F'));
            $editColumn = new CustomEditColumn('Geschlecht', 'geschlecht', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for photo field
            //
            $editor = new TextEdit('photo_edit');
            $editor->SetSize(100);
            $editor->SetMaxLength(100);
            $editColumn = new CustomEditColumn('Photo', 'photo', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for kleinbild field
            //
            $editor = new TextEdit('kleinbild_edit');
            $editor->SetSize(80);
            $editor->SetMaxLength(80);
            $editColumn = new CustomEditColumn('Kleinbild', 'kleinbild', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for sitzplatz field
            //
            $editor = new TextEdit('sitzplatz_edit');
            $editColumn = new CustomEditColumn('Sitzplatz', 'sitzplatz', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for email field
            //
            $editor = new TextEdit('email_edit');
            $editor->SetSize(100);
            $editor->SetMaxLength(100);
            $editColumn = new CustomEditColumn('Email', 'email', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
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
            // Edit column for geburtstag field
            //
            $editor = new DateTimeEdit('geburtstag_edit', true, 'Y-m-d H:i:s', GetFirstDayOfWeek());
            $editColumn = new CustomEditColumn('Geburtstag', 'geburtstag', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for anzahl_kinder field
            //
            $editor = new TextEdit('anzahl_kinder_edit');
            $editColumn = new CustomEditColumn('Anzahl Kinder', 'anzahl_kinder', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for militaerischer_grad_id field
            //
            $editor = new ComboBox('militaerischer_grad_id_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                new MyPDOConnectionFactory(),
                GetConnectionOptions(),
                '`mil_grad`');
            $field = new IntegerField('id', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('name');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('name_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('abkuerzung');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('abkuerzung_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('typ');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('ranghoehe');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzeigestufe');
            $field->SetIsNotNull(true);
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
            $lookupDataset->SetOrderBy('name', GetOrderTypeAsSQL(otAscending));
            $editColumn = new LookUpEditColumn(
                'Militaerischer Grad Id', 
                'militaerischer_grad_id', 
                $editor, 
                $this->dataset, 'id', 'name', $lookupDataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for parlament_biografie_id field
            //
            $editor = new TextEdit('parlament_biografie_id_edit');
            $editColumn = new CustomEditColumn('Parlament Biografie', 'parlament_biografie_id', $editor, $this->dataset);
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
            // Edit column for autorisierung_verschickt_visa field
            //
            $editor = new TextEdit('autorisierung_verschickt_visa_edit');
            $editor->SetSize(10);
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Autorisierung Verschickt Visa', 'autorisierung_verschickt_visa', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for autorisierung_verschickt_datum field
            //
            $editor = new DateTimeEdit('autorisierung_verschickt_datum_edit', true, 'Y-m-d H:i:s', GetFirstDayOfWeek());
            $editColumn = new CustomEditColumn('Autorisierung Verschickt Datum', 'autorisierung_verschickt_datum', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for autorisiert_visa field
            //
            $editor = new TextEdit('autorisiert_visa_edit');
            $editor->SetSize(10);
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Autorisiert Visa', 'autorisiert_visa', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for autorisiert_datum field
            //
            $editor = new DateTimeEdit('autorisiert_datum_edit', true, 'Y-m-d H:i:s', GetFirstDayOfWeek());
            $editColumn = new CustomEditColumn('Autorisiert Datum', 'autorisiert_datum', $editor, $this->dataset);
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
            
            //
            // Edit column for photo_dateiname field
            //
            $editor = new TextAreaEdit('photo_dateiname_edit', 50, 8);
            $editColumn = new CustomEditColumn('Photo Dateiname', 'photo_dateiname', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
        }
    
        protected function AddInsertColumns(Grid $grid)
        {
            //
            // Edit column for nachname field
            //
            $editor = new TextEdit('nachname_edit');
            $editor->SetSize(100);
            $editor->SetMaxLength(100);
            $editColumn = new CustomEditColumn('Nachname', 'nachname', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for vorname field
            //
            $editor = new TextEdit('vorname_edit');
            $editor->SetSize(50);
            $editor->SetMaxLength(50);
            $editColumn = new CustomEditColumn('Vorname', 'vorname', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for zweiter_vorname field
            //
            $editor = new TextEdit('zweiter_vorname_edit');
            $editor->SetSize(50);
            $editor->SetMaxLength(50);
            $editColumn = new CustomEditColumn('Zweiter Vorname', 'zweiter_vorname', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for rat_id field
            //
            $editor = new ComboBox('rat_id_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                new MyPDOConnectionFactory(),
                GetConnectionOptions(),
                '`rat`');
            $field = new IntegerField('id', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('abkuerzung');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('abkuerzung_fr');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('name_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('name_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('name_it');
            $lookupDataset->AddField($field, false);
            $field = new StringField('name_en');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_mitglieder');
            $lookupDataset->AddField($field, false);
            $field = new StringField('typ');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('interessenraum_id');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzeigestufe');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('gewicht');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung');
            $lookupDataset->AddField($field, false);
            $field = new StringField('homepage_de');
            $lookupDataset->AddField($field, false);
            $field = new StringField('homepage_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('homepage_it');
            $lookupDataset->AddField($field, false);
            $field = new StringField('homepage_en');
            $lookupDataset->AddField($field, false);
            $field = new StringField('mitglied_bezeichnung_maennlich_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('mitglied_bezeichnung_weiblich_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('mitglied_bezeichnung_maennlich_fr');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('mitglied_bezeichnung_weiblich_fr');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('parlament_id');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('parlament_type');
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
            $lookupDataset->SetOrderBy('abkuerzung', GetOrderTypeAsSQL(otAscending));
            $editColumn = new LookUpEditColumn(
                'Rat', 
                'rat_id', 
                $editor, 
                $this->dataset, 'id', 'abkuerzung', $lookupDataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for kanton_id field
            //
            $editor = new ComboBox('kanton_id_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                new MyPDOConnectionFactory(),
                GetConnectionOptions(),
                '`kanton`');
            $field = new IntegerField('id', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('abkuerzung');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('kantonsnr');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('name_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('name_fr');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('name_it');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_staenderaete');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('amtssprache');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('hauptort_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('hauptort_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('hauptort_it');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('flaeche_km2');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('beitrittsjahr');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('wappen_klein');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('wappen');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('lagebild');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('homepage');
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung');
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
            $lookupDataset->SetOrderBy('abkuerzung', GetOrderTypeAsSQL(otAscending));
            $editColumn = new LookUpEditColumn(
                'Kanton', 
                'kanton_id', 
                $editor, 
                $this->dataset, 'id', 'abkuerzung', $lookupDataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for kommissionen field
            //
            $editor = new TextEdit('kommissionen_edit');
            $editor->SetSize(75);
            $editor->SetMaxLength(75);
            $editColumn = new CustomEditColumn('Kommissionen', 'kommissionen', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for partei_id field
            //
            $editor = new ComboBox('partei_id_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                new MyPDOConnectionFactory(),
                GetConnectionOptions(),
                '`v_partei`');
            $field = new StringField('anzeige_name');
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_de');
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_fr');
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
            $lookupDataset->AddField($field, false);
            $field = new StringField('name_fr');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('fraktion_id');
            $lookupDataset->AddField($field, false);
            $field = new DateField('gruendung');
            $lookupDataset->AddField($field, false);
            $field = new StringField('position');
            $lookupDataset->AddField($field, false);
            $field = new StringField('farbcode');
            $lookupDataset->AddField($field, false);
            $field = new StringField('homepage');
            $lookupDataset->AddField($field, false);
            $field = new StringField('homepage_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('email');
            $lookupDataset->AddField($field, false);
            $field = new StringField('email_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('twitter_name');
            $lookupDataset->AddField($field, false);
            $field = new StringField('twitter_name_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung');
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung_fr');
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
            $lookupDataset->AddField($field, false);
            $field = new StringField('abkuerzung_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung_de');
            $lookupDataset->AddField($field, false);
            $field = new StringField('homepage_de');
            $lookupDataset->AddField($field, false);
            $field = new StringField('twitter_name_de');
            $lookupDataset->AddField($field, false);
            $field = new StringField('email_de');
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
            $lookupDataset->SetOrderBy('abkuerzung', GetOrderTypeAsSQL(otAscending));
            $editColumn = new LookUpEditColumn(
                'Partei', 
                'partei_id', 
                $editor, 
                $this->dataset, 'id', 'abkuerzung', $lookupDataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for parteifunktion field
            //
            $editor = new CheckBoxGroup('parteifunktion_edit');
            $editor->SetDisplayMode(CheckBoxGroup::StackedMode);
            $editor->AddValue('mitglied', $this->RenderText('mitglied'));
            $editor->AddValue('praesident', $this->RenderText('praesident'));
            $editor->AddValue('vizepraesident', $this->RenderText('vizepraesident'));
            $editor->AddValue('fraktionschef', $this->RenderText('fraktionschef'));
            $editColumn = new CustomEditColumn('Parteifunktion', 'parteifunktion', $editor, $this->dataset);
            $editColumn->SetAllowSetToDefault(false); /*afterburner*/ 
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for fraktion_id field
            //
            $editor = new ComboBox('fraktion_id_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                new MyPDOConnectionFactory(),
                GetConnectionOptions(),
                '`v_fraktion`');
            $field = new StringField('anzeige_name');
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_de');
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_fr');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('id');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('abkuerzung');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('name');
            $lookupDataset->AddField($field, false);
            $field = new StringField('name_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('position');
            $lookupDataset->AddField($field, false);
            $field = new StringField('farbcode');
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung');
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung_fr');
            $lookupDataset->AddField($field, false);
            $field = new DateField('von');
            $lookupDataset->AddField($field, false);
            $field = new DateField('bis');
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
            $lookupDataset->AddField($field, false);
            $field = new StringField('beschreibung_de');
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
            $lookupDataset->SetOrderBy('abkuerzung', GetOrderTypeAsSQL(otAscending));
            $editColumn = new LookUpEditColumn(
                'Fraktion', 
                'fraktion_id', 
                $editor, 
                $this->dataset, 'id', 'abkuerzung', $lookupDataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for fraktionsfunktion field
            //
            $editor = new ComboBox('fraktionsfunktion_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $editor->AddValue('mitglied', $this->RenderText('mitglied'));
            $editor->AddValue('praesident', $this->RenderText('praesident'));
            $editor->AddValue('vizepraesident', $this->RenderText('vizepraesident'));
            $editColumn = new CustomEditColumn('Fraktionsfunktion', 'fraktionsfunktion', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for im_rat_seit field
            //
            $editor = new DateTimeEdit('im_rat_seit_edit', true, 'Y-m-d H:i:s', GetFirstDayOfWeek());
            $editColumn = new CustomEditColumn('Im Rat Seit', 'im_rat_seit', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for im_rat_bis field
            //
            $editor = new DateTimeEdit('im_rat_bis_edit', true, 'Y-m-d H:i:s', GetFirstDayOfWeek());
            $editColumn = new CustomEditColumn('Im Rat Bis', 'im_rat_bis', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for ratsunterbruch_von field
            //
            $editor = new DateTimeEdit('ratsunterbruch_von_edit', true, 'Y-m-d H:i:s', GetFirstDayOfWeek());
            $editColumn = new CustomEditColumn('Ratsunterbruch Von', 'ratsunterbruch_von', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for ratsunterbruch_bis field
            //
            $editor = new DateTimeEdit('ratsunterbruch_bis_edit', true, 'Y-m-d H:i:s', GetFirstDayOfWeek());
            $editColumn = new CustomEditColumn('Ratsunterbruch Bis', 'ratsunterbruch_bis', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for beruf field
            //
            $editor = new TextAreaEdit('beruf_edit', 50, 8);
            $editColumn = new CustomEditColumn('Beruf', 'beruf', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for beruf_interessengruppe_id field
            //
            $editor = new ComboBox('beruf_interessengruppe_id_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
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
                'Beruf Interessengruppe Id', 
                'beruf_interessengruppe_id', 
                $editor, 
                $this->dataset, 'id', 'anzeige_name', $lookupDataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for zivilstand field
            //
            $editor = new ComboBox('zivilstand_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $editor->AddValue('ledig', $this->RenderText('ledig'));
            $editor->AddValue('verheirated', $this->RenderText('verheirated'));
            $editor->AddValue('geschieden', $this->RenderText('geschieden'));
            $editor->AddValue('eingetragene partnerschaft', $this->RenderText('eingetragene partnerschaft'));
            $editColumn = new CustomEditColumn('Zivilstand', 'zivilstand', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for geschlecht field
            //
            $editor = new ComboBox('geschlecht_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $editor->AddValue('M', $this->RenderText('M'));
            $editor->AddValue('F', $this->RenderText('F'));
            $editColumn = new CustomEditColumn('Geschlecht', 'geschlecht', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for photo field
            //
            $editor = new TextEdit('photo_edit');
            $editor->SetSize(100);
            $editor->SetMaxLength(100);
            $editColumn = new CustomEditColumn('Photo', 'photo', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for kleinbild field
            //
            $editor = new TextEdit('kleinbild_edit');
            $editor->SetSize(80);
            $editor->SetMaxLength(80);
            $editColumn = new CustomEditColumn('Kleinbild', 'kleinbild', $editor, $this->dataset);
            $editColumn->SetAllowSetToDefault(false); /*afterburner*/ 
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for sitzplatz field
            //
            $editor = new TextEdit('sitzplatz_edit');
            $editColumn = new CustomEditColumn('Sitzplatz', 'sitzplatz', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for email field
            //
            $editor = new TextEdit('email_edit');
            $editor->SetSize(100);
            $editor->SetMaxLength(100);
            $editColumn = new CustomEditColumn('Email', 'email', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
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
            // Edit column for geburtstag field
            //
            $editor = new DateTimeEdit('geburtstag_edit', true, 'Y-m-d H:i:s', GetFirstDayOfWeek());
            $editColumn = new CustomEditColumn('Geburtstag', 'geburtstag', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for anzahl_kinder field
            //
            $editor = new TextEdit('anzahl_kinder_edit');
            $editColumn = new CustomEditColumn('Anzahl Kinder', 'anzahl_kinder', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for militaerischer_grad_id field
            //
            $editor = new ComboBox('militaerischer_grad_id_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                new MyPDOConnectionFactory(),
                GetConnectionOptions(),
                '`mil_grad`');
            $field = new IntegerField('id', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('name');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('name_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('abkuerzung');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('abkuerzung_fr');
            $lookupDataset->AddField($field, false);
            $field = new StringField('typ');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('ranghoehe');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzeigestufe');
            $field->SetIsNotNull(true);
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
            $lookupDataset->SetOrderBy('name', GetOrderTypeAsSQL(otAscending));
            $editColumn = new LookUpEditColumn(
                'Militaerischer Grad Id', 
                'militaerischer_grad_id', 
                $editor, 
                $this->dataset, 'id', 'name', $lookupDataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for parlament_biografie_id field
            //
            $editor = new TextEdit('parlament_biografie_id_edit');
            $editColumn = new CustomEditColumn('Parlament Biografie', 'parlament_biografie_id', $editor, $this->dataset);
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
            // Edit column for autorisierung_verschickt_visa field
            //
            $editor = new TextEdit('autorisierung_verschickt_visa_edit');
            $editor->SetSize(10);
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Autorisierung Verschickt Visa', 'autorisierung_verschickt_visa', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for autorisierung_verschickt_datum field
            //
            $editor = new DateTimeEdit('autorisierung_verschickt_datum_edit', true, 'Y-m-d H:i:s', GetFirstDayOfWeek());
            $editColumn = new CustomEditColumn('Autorisierung Verschickt Datum', 'autorisierung_verschickt_datum', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for autorisiert_visa field
            //
            $editor = new TextEdit('autorisiert_visa_edit');
            $editor->SetSize(10);
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Autorisiert Visa', 'autorisiert_visa', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for autorisiert_datum field
            //
            $editor = new DateTimeEdit('autorisiert_datum_edit', true, 'Y-m-d H:i:s', GetFirstDayOfWeek());
            $editColumn = new CustomEditColumn('Autorisiert Datum', 'autorisiert_datum', $editor, $this->dataset);
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
            
            //
            // Edit column for photo_dateiname field
            //
            $editor = new TextAreaEdit('photo_dateiname_edit', 50, 8);
            $editColumn = new CustomEditColumn('Photo Dateiname', 'photo_dateiname', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
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
            // View column for nachname field
            //
            $column = new TextViewColumn('nachname', 'Nachname', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for vorname field
            //
            $column = new TextViewColumn('vorname', 'Vorname', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for zweiter_vorname field
            //
            $column = new TextViewColumn('zweiter_vorname', 'Zweiter Vorname', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for abkuerzung field
            //
            $column = new TextViewColumn('rat_id_abkuerzung', 'Rat', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for abkuerzung field
            //
            $column = new TextViewColumn('kanton_id_abkuerzung', 'Kanton', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for kommissionen field
            //
            $column = new TextViewColumn('kommissionen', 'Kommissionen', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for abkuerzung field
            //
            $column = new TextViewColumn('partei_id_abkuerzung', 'Partei', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for parteifunktion field
            //
            $column = new TextViewColumn('parteifunktion', 'Parteifunktion', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for abkuerzung field
            //
            $column = new TextViewColumn('fraktion_id_abkuerzung', 'Fraktion', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for fraktionsfunktion field
            //
            $column = new TextViewColumn('fraktionsfunktion', 'Fraktionsfunktion', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for im_rat_seit field
            //
            $column = new DateTimeViewColumn('im_rat_seit', 'Im Rat Seit', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y');
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for im_rat_bis field
            //
            $column = new DateTimeViewColumn('im_rat_bis', 'Im Rat Bis', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y');
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for ratsunterbruch_von field
            //
            $column = new DateTimeViewColumn('ratsunterbruch_von', 'Ratsunterbruch Von', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y');
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for ratsunterbruch_bis field
            //
            $column = new DateTimeViewColumn('ratsunterbruch_bis', 'Ratsunterbruch Bis', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y');
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for beruf field
            //
            $column = new TextViewColumn('beruf', 'Beruf', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for anzeige_name field
            //
            $column = new TextViewColumn('beruf_interessengruppe_id_anzeige_name', 'Beruf Interessengruppe Id', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'interessengruppe.php?operation=view&pk0=%beruf_interessengruppe_id%' , '_self');
            $grid->AddPrintColumn($column);
            
            //
            // View column for zivilstand field
            //
            $column = new TextViewColumn('zivilstand', 'Zivilstand', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for geschlecht field
            //
            $column = new TextViewColumn('geschlecht', 'Geschlecht', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for photo field
            //
            $column = new TextViewColumn('photo', 'Photo', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for kleinbild field
            //
            $column = new TextViewColumn('kleinbild', 'Kleinbild', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for sitzplatz field
            //
            $column = new TextViewColumn('sitzplatz', 'Sitzplatz', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for email field
            //
            $column = new TextViewColumn('email', 'Email', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for homepage field
            //
            $column = new TextViewColumn('homepage', 'Homepage', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for geburtstag field
            //
            $column = new DateTimeViewColumn('geburtstag', 'Geburtstag', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y');
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for anzahl_kinder field
            //
            $column = new TextViewColumn('anzahl_kinder', 'Anzahl Kinder', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('militaerischer_grad_id_name', 'Militaerischer Grad Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for parlament_biografie_id field
            //
            $column = new TextViewColumn('parlament_biografie_id', 'Parlament Biografie', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'http://www.parlament.ch/d/suche/seiten/biografie.aspx?biografie_id=%parlament_biografie_id%' , '_blank');
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
            // View column for autorisierung_verschickt_visa field
            //
            $column = new TextViewColumn('autorisierung_verschickt_visa', 'Autorisierung Verschickt Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for autorisierung_verschickt_datum field
            //
            $column = new DateTimeViewColumn('autorisierung_verschickt_datum', 'Autorisierung Verschickt Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for autorisiert_visa field
            //
            $column = new TextViewColumn('autorisiert_visa', 'Autorisiert Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for autorisiert_datum field
            //
            $column = new DateTimeViewColumn('autorisiert_datum', 'Autorisiert Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y');
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
            
            //
            // View column for photo_dateiname field
            //
            $column = new TextViewColumn('photo_dateiname', 'Photo Dateiname', $this->dataset);
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
            // View column for nachname field
            //
            $column = new TextViewColumn('nachname', 'Nachname', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for vorname field
            //
            $column = new TextViewColumn('vorname', 'Vorname', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for zweiter_vorname field
            //
            $column = new TextViewColumn('zweiter_vorname', 'Zweiter Vorname', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for abkuerzung field
            //
            $column = new TextViewColumn('rat_id_abkuerzung', 'Rat', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for abkuerzung field
            //
            $column = new TextViewColumn('kanton_id_abkuerzung', 'Kanton', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for kommissionen field
            //
            $column = new TextViewColumn('kommissionen', 'Kommissionen', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for abkuerzung field
            //
            $column = new TextViewColumn('partei_id_abkuerzung', 'Partei', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for parteifunktion field
            //
            $column = new TextViewColumn('parteifunktion', 'Parteifunktion', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for abkuerzung field
            //
            $column = new TextViewColumn('fraktion_id_abkuerzung', 'Fraktion', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for fraktionsfunktion field
            //
            $column = new TextViewColumn('fraktionsfunktion', 'Fraktionsfunktion', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for im_rat_seit field
            //
            $column = new DateTimeViewColumn('im_rat_seit', 'Im Rat Seit', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y');
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for im_rat_bis field
            //
            $column = new DateTimeViewColumn('im_rat_bis', 'Im Rat Bis', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y');
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for ratsunterbruch_von field
            //
            $column = new DateTimeViewColumn('ratsunterbruch_von', 'Ratsunterbruch Von', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y');
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for ratsunterbruch_bis field
            //
            $column = new DateTimeViewColumn('ratsunterbruch_bis', 'Ratsunterbruch Bis', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y');
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for beruf field
            //
            $column = new TextViewColumn('beruf', 'Beruf', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for anzeige_name field
            //
            $column = new TextViewColumn('beruf_interessengruppe_id_anzeige_name', 'Beruf Interessengruppe Id', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'interessengruppe.php?operation=view&pk0=%beruf_interessengruppe_id%' , '_self');
            $grid->AddExportColumn($column);
            
            //
            // View column for zivilstand field
            //
            $column = new TextViewColumn('zivilstand', 'Zivilstand', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for geschlecht field
            //
            $column = new TextViewColumn('geschlecht', 'Geschlecht', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for photo field
            //
            $column = new TextViewColumn('photo', 'Photo', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for kleinbild field
            //
            $column = new TextViewColumn('kleinbild', 'Kleinbild', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for sitzplatz field
            //
            $column = new TextViewColumn('sitzplatz', 'Sitzplatz', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for email field
            //
            $column = new TextViewColumn('email', 'Email', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for homepage field
            //
            $column = new TextViewColumn('homepage', 'Homepage', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for geburtstag field
            //
            $column = new DateTimeViewColumn('geburtstag', 'Geburtstag', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y');
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for anzahl_kinder field
            //
            $column = new TextViewColumn('anzahl_kinder', 'Anzahl Kinder', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('militaerischer_grad_id_name', 'Militaerischer Grad Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for parlament_biografie_id field
            //
            $column = new TextViewColumn('parlament_biografie_id', 'Parlament Biografie', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'http://www.parlament.ch/d/suche/seiten/biografie.aspx?biografie_id=%parlament_biografie_id%' , '_blank');
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
            // View column for autorisierung_verschickt_visa field
            //
            $column = new TextViewColumn('autorisierung_verschickt_visa', 'Autorisierung Verschickt Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for autorisierung_verschickt_datum field
            //
            $column = new DateTimeViewColumn('autorisierung_verschickt_datum', 'Autorisierung Verschickt Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for autorisiert_visa field
            //
            $column = new TextViewColumn('autorisiert_visa', 'Autorisiert Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for autorisiert_datum field
            //
            $column = new DateTimeViewColumn('autorisiert_datum', 'Autorisiert Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y');
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
            
            //
            // View column for photo_dateiname field
            //
            $column = new TextViewColumn('photo_dateiname', 'Photo Dateiname', $this->dataset);
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
            $result = new Grid($this, $this->dataset, 'parlamentarierDetailEditGrid1interessengruppe');
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
            // View column for nachname field
            //
            $column = new TextViewColumn('nachname', 'Nachname', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'parlamentarierDetailEditGrid1interessengruppe_nachname_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for beruf field
            //
            $column = new TextViewColumn('beruf', 'Beruf', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'parlamentarierDetailEditGrid1interessengruppe_beruf_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for photo field
            //
            $column = new TextViewColumn('photo', 'Photo', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'parlamentarierDetailEditGrid1interessengruppe_photo_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for kleinbild field
            //
            $column = new TextViewColumn('kleinbild', 'Kleinbild', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'parlamentarierDetailEditGrid1interessengruppe_kleinbild_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for email field
            //
            $column = new TextViewColumn('email', 'Email', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'mailto:%email%' , '_blank');
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'parlamentarierDetailEditGrid1interessengruppe_email_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for homepage field
            //
            $column = new TextViewColumn('homepage', 'Homepage', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, '%homepage%' , '_blank');
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'parlamentarierDetailEditGrid1interessengruppe_homepage_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $column->SetReplaceLFByBR(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'parlamentarierDetailEditGrid1interessengruppe_notizen_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for photo_dateiname field
            //
            $column = new TextViewColumn('photo_dateiname', 'Photo Dateiname', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'parlamentarierDetailEditGrid1interessengruppe_photo_dateiname_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for nachname field
            //
            $column = new TextViewColumn('nachname', 'Nachname', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'parlamentarierDetailEditGrid1interessengruppe_nachname_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for beruf field
            //
            $column = new TextViewColumn('beruf', 'Beruf', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'parlamentarierDetailEditGrid1interessengruppe_beruf_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for photo field
            //
            $column = new TextViewColumn('photo', 'Photo', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'parlamentarierDetailEditGrid1interessengruppe_photo_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for kleinbild field
            //
            $column = new TextViewColumn('kleinbild', 'Kleinbild', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'parlamentarierDetailEditGrid1interessengruppe_kleinbild_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for email field
            //
            $column = new TextViewColumn('email', 'Email', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'mailto:%email%' , '_blank');
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'parlamentarierDetailEditGrid1interessengruppe_email_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for homepage field
            //
            $column = new TextViewColumn('homepage', 'Homepage', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, '%homepage%' , '_blank');
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'parlamentarierDetailEditGrid1interessengruppe_homepage_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $column->SetReplaceLFByBR(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'parlamentarierDetailEditGrid1interessengruppe_notizen_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for photo_dateiname field
            //
            $column = new TextViewColumn('photo_dateiname', 'Photo Dateiname', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'parlamentarierDetailEditGrid1interessengruppe_photo_dateiname_handler_view', $column);
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
    
    
    
    class interessengruppePage extends Page
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
            $this->dataset->AddLookupField('branche_id', 'v_branche_simple', new IntegerField('id'), new StringField('anzeige_name', 'branche_id_anzeige_name', 'branche_id_anzeige_name_v_branche_simple'), 'branche_id_anzeige_name_v_branche_simple');
        }
    
        protected function DoPrepare() {
    
        }
    
        protected function CreatePageNavigator()
        {
            $result = new CompositePageNavigator($this);
            
            $partitionNavigator = new CustomPageNavigator('partition', $this, $this->GetDataset(), $this->RenderText('Name beginnt mit'), $result, 'partition');
            $partitionNavigator->OnGetPartitions->AddListener('partition_OnGetPartitions', $this);
            $partitionNavigator->OnGetPartitionCondition->AddListener('partition_OnGetPartitionCondition', $this);
            $partitionNavigator->SetAllowViewAllRecords(true);
            $partitionNavigator->SetNavigationStyle(NS_LIST);
            $result->AddPageNavigator($partitionNavigator);
            
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
            $grid->SearchControl = new SimpleSearch('interessengruppessearch', $this->dataset,
                array('id', 'name', 'name_fr', 'branche_id_anzeige_name', 'beschreibung', 'beschreibung_fr', 'alias_namen', 'alias_namen_fr', 'notizen'),
                array($this->RenderText('Id'), $this->RenderText('Name'), $this->RenderText('Name Fr'), $this->RenderText('Branche'), $this->RenderText('Beschreibung'), $this->RenderText('Beschreibung Fr'), $this->RenderText('Alias Namen'), $this->RenderText('Alias Namen Fr'), $this->RenderText('Notizen')),
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
            $this->AdvancedSearchControl = new AdvancedSearchControl('interessengruppeasearch', $this->dataset, $this->GetLocalizerCaptions(), $this->GetColumnVariableContainer(), $this->CreateLinkBuilder());
            $this->AdvancedSearchControl->setTimerInterval(1000);
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('id', $this->RenderText('Id')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('name', $this->RenderText('Name')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('name_fr', $this->RenderText('Name Fr')));
            
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
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('beschreibung_fr', $this->RenderText('Beschreibung Fr')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('alias_namen', $this->RenderText('Alias Namen')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('alias_namen_fr', $this->RenderText('Alias Namen Fr')));
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
            if (GetCurrentUserGrantForDataSource('interessengruppe.organisation')->HasViewGrant())
            {
              //
            // View column for organisationDetailView0interessengruppe detail
            //
            $column = new DetailColumn(array('id'), 'detail0interessengruppe', 'organisationDetailEdit0interessengruppe_handler', 'organisationDetailView0interessengruppe_handler', $this->dataset, 'Organisation', $this->RenderText('Organisation'));
              $grid->AddViewColumn($column);
            }
            
            if (GetCurrentUserGrantForDataSource('interessengruppe.parlamentarier')->HasViewGrant())
            {
              //
            // View column for parlamentarierDetailView1interessengruppe detail
            //
            $column = new DetailColumn(array('id'), 'detail1interessengruppe', 'parlamentarierDetailEdit1interessengruppe_handler', 'parlamentarierDetailView1interessengruppe_handler', $this->dataset, 'Parlamentarier', $this->RenderText('Parlamentarier'));
              $grid->AddViewColumn($column);
            }
            
            //
            // View column for id field
            //
            $column = new TextViewColumn('id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'http://lobbywatch.ch/de/daten/lobbygruppe/%id%' , '_blank');
            $column->SetDescription($this->RenderText('Technischer Schlüssel der Lobbygruppe (Interessengruppe).  Der Link zeigt auf den Eintrag in der Lobbywatch.ch Webseite.'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('name', 'Name', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('interessengruppeGrid_name_handler_list');
            $column = new DivTagViewColumnDecorator($column);
            $column->Bold = true;
            $column->SetDescription($this->RenderText('Bezeichnung der Interessengruppe'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for name_fr field
            //
            $column = new TextViewColumn('name_fr', 'Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('interessengruppeGrid_name_fr_handler_list');
            $column->SetDescription($this->RenderText('Französische Bezeichnung der Interessengruppe'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for anzeige_name field
            //
            $column = new TextViewColumn('branche_id_anzeige_name', 'Branche', $this->dataset);
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
            $column->SetFullTextWindowHandlerName('interessengruppeGrid_beschreibung_handler_list');
            $column->SetReplaceLFByBR(true);
            $column->SetDescription($this->RenderText('Eingrenzung und Beschreibung zur Interessengruppe'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for beschreibung_fr field
            //
            $column = new TextViewColumn('beschreibung_fr', 'Beschreibung Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('interessengruppeGrid_beschreibung_fr_handler_list');
            $column->SetDescription($this->RenderText('Eingrenzung und Beschreibung zur Interessengruppe auf französisch'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for alias_namen field
            //
            $column = new TextViewColumn('alias_namen', 'Alias Namen', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('interessengruppeGrid_alias_namen_handler_list');
            $column->SetDescription($this->RenderText('Strichpunkt-getrennte Aufzählung von alternative Namen für die Lobbygruppe; bei der Suche werden für ein einfacheres Finden auch in den Alias-Namen gesucht.'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for alias_namen_fr field
            //
            $column = new TextViewColumn('alias_namen_fr', 'Alias Namen Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('interessengruppeGrid_alias_namen_fr_handler_list');
            $column->SetDescription($this->RenderText('Strichpunkt-getrennte Aufzählung von alternativen französischen Namen für die Lobbygruppe; bei der Suche werden für ein einfacheres Finden auch in den Alias-Namen gesucht.'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('interessengruppeGrid_notizen_handler_list');
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
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'http://lobbywatch.ch/de/daten/lobbygruppe/%id%' , '_blank');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('name', 'Name', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('interessengruppeGrid_name_handler_view');
            $column = new DivTagViewColumnDecorator($column);
            $column->Bold = true;
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for name_fr field
            //
            $column = new TextViewColumn('name_fr', 'Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('interessengruppeGrid_name_fr_handler_view');
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
            $column->SetFullTextWindowHandlerName('interessengruppeGrid_beschreibung_handler_view');
            $column->SetReplaceLFByBR(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for beschreibung_fr field
            //
            $column = new TextViewColumn('beschreibung_fr', 'Beschreibung Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('interessengruppeGrid_beschreibung_fr_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for alias_namen field
            //
            $column = new TextViewColumn('alias_namen', 'Alias Namen', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('interessengruppeGrid_alias_namen_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for alias_namen_fr field
            //
            $column = new TextViewColumn('alias_namen_fr', 'Alias Namen Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('interessengruppeGrid_alias_namen_fr_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('interessengruppeGrid_notizen_handler_view');
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
            $editor = new TextEdit('name_edit');
            $editor->SetSize(80);
            $editor->SetMaxLength(150);
            $editColumn = new CustomEditColumn('Name', 'name', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for name_fr field
            //
            $editor = new TextEdit('name_fr_edit');
            $editor->SetSize(80);
            $editor->SetMaxLength(150);
            $editColumn = new CustomEditColumn('Name Fr', 'name_fr', $editor, $this->dataset);
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
            // Edit column for beschreibung_fr field
            //
            $editor = new TextAreaEdit('beschreibung_fr_edit', 50, 8);
            $editColumn = new CustomEditColumn('Beschreibung Fr', 'beschreibung_fr', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for alias_namen field
            //
            $editor = new TextAreaEdit('alias_namen_edit', 80, 2);
            $editColumn = new CustomEditColumn('Alias Namen', 'alias_namen', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for alias_namen_fr field
            //
            $editor = new TextAreaEdit('alias_namen_fr_edit', 80, 2);
            $editColumn = new CustomEditColumn('Alias Namen Fr', 'alias_namen_fr', $editor, $this->dataset);
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
        }
    
        protected function AddInsertColumns(Grid $grid)
        {
            //
            // Edit column for name field
            //
            $editor = new TextEdit('name_edit');
            $editor->SetSize(80);
            $editor->SetMaxLength(150);
            $editColumn = new CustomEditColumn('Name', 'name', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for name_fr field
            //
            $editor = new TextEdit('name_fr_edit');
            $editor->SetSize(80);
            $editor->SetMaxLength(150);
            $editColumn = new CustomEditColumn('Name Fr', 'name_fr', $editor, $this->dataset);
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
            // Edit column for beschreibung_fr field
            //
            $editor = new TextAreaEdit('beschreibung_fr_edit', 50, 8);
            $editColumn = new CustomEditColumn('Beschreibung Fr', 'beschreibung_fr', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for alias_namen field
            //
            $editor = new TextAreaEdit('alias_namen_edit', 80, 2);
            $editColumn = new CustomEditColumn('Alias Namen', 'alias_namen', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for alias_namen_fr field
            //
            $editor = new TextAreaEdit('alias_namen_fr_edit', 80, 2);
            $editColumn = new CustomEditColumn('Alias Namen Fr', 'alias_namen_fr', $editor, $this->dataset);
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
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'http://lobbywatch.ch/de/daten/lobbygruppe/%id%' , '_blank');
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
            // View column for beschreibung_fr field
            //
            $column = new TextViewColumn('beschreibung_fr', 'Beschreibung Fr', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for alias_namen field
            //
            $column = new TextViewColumn('alias_namen', 'Alias Namen', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for alias_namen_fr field
            //
            $column = new TextViewColumn('alias_namen_fr', 'Alias Namen Fr', $this->dataset);
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
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'http://lobbywatch.ch/de/daten/lobbygruppe/%id%' , '_blank');
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
            // View column for beschreibung_fr field
            //
            $column = new TextViewColumn('beschreibung_fr', 'Beschreibung Fr', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for alias_namen field
            //
            $column = new TextViewColumn('alias_namen', 'Alias Namen', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for alias_namen_fr field
            //
            $column = new TextViewColumn('alias_namen_fr', 'Alias Namen Fr', $this->dataset);
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
    
        function CreateMasterDetailRecordGridFororganisationDetailEdit0interessengruppeGrid()
        {
            $result = new Grid($this, $this->dataset, 'MasterDetailRecordGridFororganisationDetailEdit0interessengruppe');
            $result->SetAllowDeleteSelected(false);
            $result->OnCustomDrawCell->AddListener('MasterDetailRecordGridFororganisationDetailEdit0interessengruppe' . '_OnCustomDrawRow', $this);
            $result->OnCustomRenderColumn->AddListener('MasterDetailRecordGridFororganisationDetailEdit0interessengruppe' . '_' . 'OnCustomRenderColumn', $this);
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
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'http://lobbywatch.ch/de/daten/lobbygruppe/%id%' , '_blank');
            $column->SetDescription($this->RenderText('Technischer Schlüssel der Lobbygruppe (Interessengruppe).  Der Link zeigt auf den Eintrag in der Lobbywatch.ch Webseite.'));
            $column->SetFixedWidth(null);
            $result->AddViewColumn($column);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('name', 'Name', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('interessengruppeGrid_name_handler_list');
            $column = new DivTagViewColumnDecorator($column);
            $column->Bold = true;
            $column->SetDescription($this->RenderText('Bezeichnung der Interessengruppe'));
            $column->SetFixedWidth(null);
            $result->AddViewColumn($column);
            
            //
            // View column for name_fr field
            //
            $column = new TextViewColumn('name_fr', 'Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('interessengruppeGrid_name_fr_handler_list');
            $column->SetDescription($this->RenderText('Französische Bezeichnung der Interessengruppe'));
            $column->SetFixedWidth(null);
            $result->AddViewColumn($column);
            
            //
            // View column for anzeige_name field
            //
            $column = new TextViewColumn('branche_id_anzeige_name', 'Branche', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'branche.php?operation=view&pk0=%branche_id%' , '_self');
            $column->SetDescription($this->RenderText('Fremdschlüssel Branche'));
            $column->SetFixedWidth(null);
            $result->AddViewColumn($column);
            
            //
            // View column for beschreibung field
            //
            $column = new TextViewColumn('beschreibung', 'Beschreibung', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('interessengruppeGrid_beschreibung_handler_list');
            $column->SetReplaceLFByBR(true);
            $column->SetDescription($this->RenderText('Eingrenzung und Beschreibung zur Interessengruppe'));
            $column->SetFixedWidth(null);
            $result->AddViewColumn($column);
            
            //
            // View column for beschreibung_fr field
            //
            $column = new TextViewColumn('beschreibung_fr', 'Beschreibung Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('interessengruppeGrid_beschreibung_fr_handler_list');
            $column->SetDescription($this->RenderText('Eingrenzung und Beschreibung zur Interessengruppe auf französisch'));
            $column->SetFixedWidth(null);
            $result->AddViewColumn($column);
            
            //
            // View column for alias_namen field
            //
            $column = new TextViewColumn('alias_namen', 'Alias Namen', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('interessengruppeGrid_alias_namen_handler_list');
            $column->SetDescription($this->RenderText('Strichpunkt-getrennte Aufzählung von alternative Namen für die Lobbygruppe; bei der Suche werden für ein einfacheres Finden auch in den Alias-Namen gesucht.'));
            $column->SetFixedWidth(null);
            $result->AddViewColumn($column);
            
            //
            // View column for alias_namen_fr field
            //
            $column = new TextViewColumn('alias_namen_fr', 'Alias Namen Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('interessengruppeGrid_alias_namen_fr_handler_list');
            $column->SetDescription($this->RenderText('Strichpunkt-getrennte Aufzählung von alternativen französischen Namen für die Lobbygruppe; bei der Suche werden für ein einfacheres Finden auch in den Alias-Namen gesucht.'));
            $column->SetFixedWidth(null);
            $result->AddViewColumn($column);
            
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('interessengruppeGrid_notizen_handler_list');
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
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'http://lobbywatch.ch/de/daten/lobbygruppe/%id%' , '_blank');
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
            // View column for anzeige_name field
            //
            $column = new TextViewColumn('branche_id_anzeige_name', 'Branche', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'branche.php?operation=view&pk0=%branche_id%' , '_self');
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
            // View column for alias_namen field
            //
            $column = new TextViewColumn('alias_namen', 'Alias Namen', $this->dataset);
            $column->SetOrderable(true);
            $result->AddPrintColumn($column);
            
            //
            // View column for alias_namen_fr field
            //
            $column = new TextViewColumn('alias_namen_fr', 'Alias Namen Fr', $this->dataset);
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
        
        public function MasterDetailRecordGridFororganisationDetailEdit0interessengruppe_OnCustomDrawRow($rowData, &$rowCellStyles, &$rowStyles)
        {
        customDrawRow('interessengruppe', $rowData, $rowCellStyles, $rowStyles);
        }
        function MasterDetailRecordGridFororganisationDetailEdit0interessengruppe_OnCustomRenderColumn($fieldName, $fieldData, $rowData, &$customText, &$handled)
        {
            customOnCustomRenderColumn('interessengruppe', $fieldName, $fieldData, $rowData, $customText, $handled);
        }
        function CreateMasterDetailRecordGridForparlamentarierDetailEdit1interessengruppeGrid()
        {
            $result = new Grid($this, $this->dataset, 'MasterDetailRecordGridForparlamentarierDetailEdit1interessengruppe');
            $result->SetAllowDeleteSelected(false);
            $result->OnCustomDrawCell->AddListener('MasterDetailRecordGridForparlamentarierDetailEdit1interessengruppe' . '_OnCustomDrawRow', $this);
            $result->OnCustomRenderColumn->AddListener('MasterDetailRecordGridForparlamentarierDetailEdit1interessengruppe' . '_' . 'OnCustomRenderColumn', $this);
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
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'http://lobbywatch.ch/de/daten/lobbygruppe/%id%' , '_blank');
            $column->SetDescription($this->RenderText('Technischer Schlüssel der Lobbygruppe (Interessengruppe).  Der Link zeigt auf den Eintrag in der Lobbywatch.ch Webseite.'));
            $column->SetFixedWidth(null);
            $result->AddViewColumn($column);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('name', 'Name', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('interessengruppeGrid_name_handler_list');
            $column = new DivTagViewColumnDecorator($column);
            $column->Bold = true;
            $column->SetDescription($this->RenderText('Bezeichnung der Interessengruppe'));
            $column->SetFixedWidth(null);
            $result->AddViewColumn($column);
            
            //
            // View column for name_fr field
            //
            $column = new TextViewColumn('name_fr', 'Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('interessengruppeGrid_name_fr_handler_list');
            $column->SetDescription($this->RenderText('Französische Bezeichnung der Interessengruppe'));
            $column->SetFixedWidth(null);
            $result->AddViewColumn($column);
            
            //
            // View column for anzeige_name field
            //
            $column = new TextViewColumn('branche_id_anzeige_name', 'Branche', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'branche.php?operation=view&pk0=%branche_id%' , '_self');
            $column->SetDescription($this->RenderText('Fremdschlüssel Branche'));
            $column->SetFixedWidth(null);
            $result->AddViewColumn($column);
            
            //
            // View column for beschreibung field
            //
            $column = new TextViewColumn('beschreibung', 'Beschreibung', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('interessengruppeGrid_beschreibung_handler_list');
            $column->SetReplaceLFByBR(true);
            $column->SetDescription($this->RenderText('Eingrenzung und Beschreibung zur Interessengruppe'));
            $column->SetFixedWidth(null);
            $result->AddViewColumn($column);
            
            //
            // View column for beschreibung_fr field
            //
            $column = new TextViewColumn('beschreibung_fr', 'Beschreibung Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('interessengruppeGrid_beschreibung_fr_handler_list');
            $column->SetDescription($this->RenderText('Eingrenzung und Beschreibung zur Interessengruppe auf französisch'));
            $column->SetFixedWidth(null);
            $result->AddViewColumn($column);
            
            //
            // View column for alias_namen field
            //
            $column = new TextViewColumn('alias_namen', 'Alias Namen', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('interessengruppeGrid_alias_namen_handler_list');
            $column->SetDescription($this->RenderText('Strichpunkt-getrennte Aufzählung von alternative Namen für die Lobbygruppe; bei der Suche werden für ein einfacheres Finden auch in den Alias-Namen gesucht.'));
            $column->SetFixedWidth(null);
            $result->AddViewColumn($column);
            
            //
            // View column for alias_namen_fr field
            //
            $column = new TextViewColumn('alias_namen_fr', 'Alias Namen Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('interessengruppeGrid_alias_namen_fr_handler_list');
            $column->SetDescription($this->RenderText('Strichpunkt-getrennte Aufzählung von alternativen französischen Namen für die Lobbygruppe; bei der Suche werden für ein einfacheres Finden auch in den Alias-Namen gesucht.'));
            $column->SetFixedWidth(null);
            $result->AddViewColumn($column);
            
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('interessengruppeGrid_notizen_handler_list');
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
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'http://lobbywatch.ch/de/daten/lobbygruppe/%id%' , '_blank');
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
            // View column for anzeige_name field
            //
            $column = new TextViewColumn('branche_id_anzeige_name', 'Branche', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'branche.php?operation=view&pk0=%branche_id%' , '_self');
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
            // View column for alias_namen field
            //
            $column = new TextViewColumn('alias_namen', 'Alias Namen', $this->dataset);
            $column->SetOrderable(true);
            $result->AddPrintColumn($column);
            
            //
            // View column for alias_namen_fr field
            //
            $column = new TextViewColumn('alias_namen_fr', 'Alias Namen Fr', $this->dataset);
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
        
        public function MasterDetailRecordGridForparlamentarierDetailEdit1interessengruppe_OnCustomDrawRow($rowData, &$rowCellStyles, &$rowStyles)
        {
        customDrawRow('interessengruppe', $rowData, $rowCellStyles, $rowStyles);
        }
        function MasterDetailRecordGridForparlamentarierDetailEdit1interessengruppe_OnCustomRenderColumn($fieldName, $fieldData, $rowData, &$customText, &$handled)
        {
            customOnCustomRenderColumn('interessengruppe', $fieldName, $fieldData, $rowData, $customText, $handled);
        }
        
        function GetCustomClientScript()
        {
            return ;
        }
        
        function GetOnPageLoadedClientScript()
        {
            return ;
        }
        public function interessengruppeGrid_OnGetCustomTemplate($part, $mode, &$result, &$params)
        {
        defaultOnGetCustomTemplate($this, $part, $mode, $result, $params);
        }
        public function interessengruppeGrid_OnCustomDrawRow($rowData, &$rowCellStyles, &$rowStyles)
        {
        customDrawRow('interessengruppe', $rowData, $rowCellStyles, $rowStyles);
        }
        function interessengruppeGrid_OnCustomRenderColumn($fieldName, $fieldData, $rowData, &$customText, &$handled)
        {
            customOnCustomRenderColumn('interessengruppe', $fieldName, $fieldData, $rowData, $customText, $handled);
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
        
        public function GetModalGridDeleteHandler() { return 'interessengruppe_modal_delete'; }
        protected function GetEnableModalGridDelete() { return true; }
        
        function partition_OnGetPartitions(&$partitions)
        {
            $tmp = array();
            $this->GetConnection()->ExecQueryToArray("
            SELECT DISTINCT
            left(i.name, 1) as first_letter
            FROM interessengruppe i
            ORDER BY first_letter", $tmp
            );
            
            foreach($tmp as $letter) {
              $partitions[$letter['first_letter']] = convert_ansi(strtoupper($letter['first_letter']));
            }
        }
        
        function partition_OnGetPartitionCondition($partitionKey, &$condition)
        {
            $condition = "left(interessengruppe.name, 1) = '$partitionKey'";
        }
    
        protected function CreateGrid()
        {
            $result = new Grid($this, $this->dataset, 'interessengruppeGrid');
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
            $this->OnGetCustomTemplate->AddListener('interessengruppeGrid' . '_OnGetCustomTemplate', $this);
            $result->OnCustomDrawCell->AddListener('interessengruppeGrid' . '_OnCustomDrawRow', $this);
            $result->OnCustomRenderColumn->AddListener('interessengruppeGrid' . '_' . 'OnCustomRenderColumn', $this);
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
            $pageView = new organisationDetailView0interessengruppePage($this, 'Organisation', 'Organisation', array('interessengruppe_id'), GetCurrentUserGrantForDataSource('interessengruppe.organisation'), 'UTF-8', 20, 'organisationDetailEdit0interessengruppe_handler');
            
            $pageView->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource('interessengruppe.organisation'));
            $handler = new PageHTTPHandler('organisationDetailView0interessengruppe_handler', $pageView);
            GetApplication()->RegisterHTTPHandler($handler);
            $pageEdit = new organisationDetailEdit0interessengruppePage($this, array('interessengruppe_id'), array('id'), $this->GetForeingKeyFields(), $this->CreateMasterDetailRecordGridFororganisationDetailEdit0interessengruppeGrid(), $this->dataset, GetCurrentUserGrantForDataSource('interessengruppe.organisation'), 'UTF-8');
            
            $pageEdit->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource('interessengruppe.organisation'));
            $pageEdit->SetShortCaption('Organisation');
            $pageEdit->SetHeader(GetPagesHeader());
            $pageEdit->SetFooter(GetPagesFooter());
            $pageEdit->SetCaption('Organisation');
            $pageEdit->SetHttpHandlerName('organisationDetailEdit0interessengruppe_handler');
            $handler = new PageHTTPHandler('organisationDetailEdit0interessengruppe_handler', $pageEdit);
            GetApplication()->RegisterHTTPHandler($handler);
            $pageView = new parlamentarierDetailView1interessengruppePage($this, 'Parlamentarier', 'Parlamentarier', array('beruf_interessengruppe_id'), GetCurrentUserGrantForDataSource('interessengruppe.parlamentarier'), 'UTF-8', 20, 'parlamentarierDetailEdit1interessengruppe_handler');
            
            $pageView->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource('interessengruppe.parlamentarier'));
            $handler = new PageHTTPHandler('parlamentarierDetailView1interessengruppe_handler', $pageView);
            GetApplication()->RegisterHTTPHandler($handler);
            $pageEdit = new parlamentarierDetailEdit1interessengruppePage($this, array('beruf_interessengruppe_id'), array('id'), $this->GetForeingKeyFields(), $this->CreateMasterDetailRecordGridForparlamentarierDetailEdit1interessengruppeGrid(), $this->dataset, GetCurrentUserGrantForDataSource('interessengruppe.parlamentarier'), 'UTF-8');
            
            $pageEdit->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource('interessengruppe.parlamentarier'));
            $pageEdit->SetShortCaption('Parlamentarier');
            $pageEdit->SetHeader(GetPagesHeader());
            $pageEdit->SetFooter(GetPagesFooter());
            $pageEdit->SetCaption('Parlamentarier');
            $pageEdit->SetHttpHandlerName('parlamentarierDetailEdit1interessengruppe_handler');
            $handler = new PageHTTPHandler('parlamentarierDetailEdit1interessengruppe_handler', $pageEdit);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for name field
            //
            $column = new TextViewColumn('name', 'Name', $this->dataset);
            $column->SetOrderable(true);
            $column = new DivTagViewColumnDecorator($column);
            $column->Bold = true;
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'interessengruppeGrid_name_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for name_fr field
            //
            $column = new TextViewColumn('name_fr', 'Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'interessengruppeGrid_name_fr_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for beschreibung field
            //
            $column = new TextViewColumn('beschreibung', 'Beschreibung', $this->dataset);
            $column->SetOrderable(true);
            $column->SetReplaceLFByBR(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'interessengruppeGrid_beschreibung_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for beschreibung_fr field
            //
            $column = new TextViewColumn('beschreibung_fr', 'Beschreibung Fr', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'interessengruppeGrid_beschreibung_fr_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for alias_namen field
            //
            $column = new TextViewColumn('alias_namen', 'Alias Namen', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'interessengruppeGrid_alias_namen_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for alias_namen_fr field
            //
            $column = new TextViewColumn('alias_namen_fr', 'Alias Namen Fr', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'interessengruppeGrid_alias_namen_fr_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $column->SetReplaceLFByBR(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'interessengruppeGrid_notizen_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for name field
            //
            $column = new TextViewColumn('name', 'Name', $this->dataset);
            $column->SetOrderable(true);
            $column = new DivTagViewColumnDecorator($column);
            $column->Bold = true;
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'interessengruppeGrid_name_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for name_fr field
            //
            $column = new TextViewColumn('name_fr', 'Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'interessengruppeGrid_name_fr_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for beschreibung field
            //
            $column = new TextViewColumn('beschreibung', 'Beschreibung', $this->dataset);
            $column->SetOrderable(true);
            $column->SetReplaceLFByBR(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'interessengruppeGrid_beschreibung_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for beschreibung_fr field
            //
            $column = new TextViewColumn('beschreibung_fr', 'Beschreibung Fr', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'interessengruppeGrid_beschreibung_fr_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for alias_namen field
            //
            $column = new TextViewColumn('alias_namen', 'Alias Namen', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'interessengruppeGrid_alias_namen_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for alias_namen_fr field
            //
            $column = new TextViewColumn('alias_namen_fr', 'Alias Namen Fr', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'interessengruppeGrid_alias_namen_fr_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $column->SetReplaceLFByBR(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'interessengruppeGrid_notizen_handler_view', $column);
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
    <p>Liste der Interessengruppen. Innerhalb einer Branche gibt es normalerweise verschiedene Interessengruppen.
    </p>
    
    <p>Beispiel: Das Gesundheitswesen hat die Interessengruppen Pharmaindustrie, Ärzte, Pflegeberufe, Patienten, Spitäler, Krankenkassen, …
    </p>
    
    <p>Interessengruppen versuchen die Politik in ihrem Interesse zu beeinflussen.
    </p>
    </div>
    
    ' . $GLOBALS["edit_general_hint"] /*afterburner*/  . '';
        }
    }

    SetUpUserAuthorization(GetApplication());

    try
    {
        $Page = new interessengruppePage("interessengruppe.php", "interessengruppe", GetCurrentUserGrantForDataSource("interessengruppe"), 'UTF-8');
        $Page->SetShortCaption('<span class="entity">Lobbygruppe</span>');
        $Page->SetHeader(GetPagesHeader());
        $Page->SetFooter(GetPagesFooter());
        $Page->SetCaption('Lobbygruppe');
        $Page->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource("interessengruppe"));
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
