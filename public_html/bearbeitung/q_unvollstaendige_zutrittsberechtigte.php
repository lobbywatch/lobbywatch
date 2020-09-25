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
    
    
    
    class q_unvollstaendige_zutrittsberechtigtePage extends Page
    {
        protected function DoBeforeCreate()
        {
            $this->SetTitle('Unvollständige Zutrittsberechtigte');
            $this->SetMenuLabel('<span class="view">Unvollständige Zutrittsberechtigte</span>');
    
            $selectQuery = 'select zutrittsberechtigung.*, parlamentarier.kommissionen from
            v_zutrittsberechtigung_simple_compat zutrittsberechtigung
            LEFT JOIN v_parlamentarier parlamentarier
            ON zutrittsberechtigung.parlamentarier_id = parlamentarier.id
            where
            zutrittsberechtigung.geschlecht is null OR
            zutrittsberechtigung.email is null OR trim(zutrittsberechtigung.email)=\'\' OR
            zutrittsberechtigung.beruf is null OR trim(zutrittsberechtigung.beruf)=\'\' OR
            zutrittsberechtigung.funktion is null OR trim(zutrittsberechtigung.funktion)=\'\'';
            $insertQuery = array();
            $updateQuery = array();
            $deleteQuery = array();
            $this->dataset = new QueryDataset(
              MyPDOConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'q_unvollstaendige_zutrittsberechtigte');
            $this->dataset->addFields(
                array(
                    new StringField('anzeige_name'),
                    new StringField('anzeige_name_de'),
                    new StringField('anzeige_name_fr'),
                    new StringField('name'),
                    new StringField('name_de'),
                    new StringField('name_fr'),
                    new IntegerField('id', true, true),
                    new StringField('nachname', true),
                    new StringField('vorname', true),
                    new StringField('zweiter_vorname'),
                    new StringField('beschreibung_de'),
                    new StringField('beschreibung_fr'),
                    new StringField('parlamentarier_kommissionen'),
                    new StringField('parlamentarier_kommissionen_zutrittsberechtigung'),
                    new StringField('beruf'),
                    new StringField('beruf_fr'),
                    new IntegerField('beruf_interessengruppe_id'),
                    new IntegerField('partei_id'),
                    new StringField('geschlecht'),
                    new StringField('arbeitssprache'),
                    new StringField('email'),
                    new StringField('homepage'),
                    new StringField('twitter_name'),
                    new StringField('linkedin_profil_url'),
                    new StringField('xing_profil_name'),
                    new StringField('facebook_name'),
                    new StringField('telephon_1'),
                    new StringField('telephon_2'),
                    new StringField('erfasst'),
                    new StringField('notizen'),
                    new StringField('eingabe_abgeschlossen_visa_person'),
                    new DateTimeField('eingabe_abgeschlossen_datum_person'),
                    new StringField('kontrolliert_visa_person'),
                    new DateTimeField('kontrolliert_datum_person'),
                    new StringField('autorisierung_verschickt_visa'),
                    new DateTimeField('autorisierung_verschickt_datum'),
                    new StringField('autorisiert_visa'),
                    new DateField('autorisiert_datum'),
                    new StringField('freigabe_visa_person'),
                    new DateTimeField('freigabe_datum_person'),
                    new StringField('created_visa_person'),
                    new DateTimeField('created_date_person'),
                    new StringField('updated_visa_person'),
                    new DateTimeField('updated_date_person'),
                    new IntegerField('published_person'),
                    new IntegerField('created_date_unix_person'),
                    new IntegerField('updated_date_unix_person'),
                    new IntegerField('eingabe_abgeschlossen_datum_unix_person'),
                    new IntegerField('kontrolliert_datum_unix_person'),
                    new IntegerField('freigabe_datum_unix_person'),
                    new IntegerField('parlamentarier_id', true),
                    new IntegerField('person_id', true),
                    new IntegerField('zutrittsberechtigung_id'),
                    new StringField('funktion'),
                    new StringField('funktion_fr'),
                    new DateField('von'),
                    new DateField('bis'),
                    new StringField('eingabe_abgeschlossen_visa'),
                    new DateTimeField('eingabe_abgeschlossen_datum'),
                    new StringField('kontrolliert_visa'),
                    new DateTimeField('kontrolliert_datum'),
                    new StringField('freigabe_visa'),
                    new DateTimeField('freigabe_datum'),
                    new StringField('created_visa', true),
                    new DateTimeField('created_date', true),
                    new StringField('updated_visa'),
                    new DateTimeField('updated_date', true),
                    new IntegerField('published'),
                    new IntegerField('bis_unix'),
                    new IntegerField('von_unix'),
                    new IntegerField('created_date_unix'),
                    new IntegerField('updated_date_unix'),
                    new IntegerField('eingabe_abgeschlossen_datum_unix'),
                    new IntegerField('kontrolliert_datum_unix'),
                    new IntegerField('freigabe_datum_unix'),
                    new StringField('kommissionen')
                )
            );
            $this->dataset->AddLookupField('parlamentarier_id', 'v_parlamentarier_simple', new IntegerField('id'), new StringField('anzeige_name', false, false, false, false, 'parlamentarier_id_anzeige_name', 'parlamentarier_id_anzeige_name_v_parlamentarier_simple'), 'parlamentarier_id_anzeige_name_v_parlamentarier_simple');
            $this->dataset->AddLookupField('beruf_interessengruppe_id', 'v_interessengruppe_simple', new IntegerField('id'), new StringField('anzeige_name', false, false, false, false, 'beruf_interessengruppe_id_anzeige_name', 'beruf_interessengruppe_id_anzeige_name_v_interessengruppe_simple'), 'beruf_interessengruppe_id_anzeige_name_v_interessengruppe_simple');
            $this->dataset->AddLookupField('partei_id', 'v_partei', new IntegerField('id'), new StringField('abkuerzung', false, false, false, false, 'partei_id_abkuerzung', 'partei_id_abkuerzung_v_partei'), 'partei_id_abkuerzung_v_partei');
        }
    
        protected function DoPrepare() {
            globalOnPreparePage($this);
        }
    
        protected function CreatePageNavigator()
        {
            $result = new CompositePageNavigator($this);
            
            $partitionNavigator = new PageNavigator('pnav', $this, $this->dataset);
            $partitionNavigator->SetRowsPerPage(5);
            $result->AddPageNavigator($partitionNavigator);
            
            return $result;
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
                new FilterColumn($this->dataset, 'id', 'id', 'Id'),
                new FilterColumn($this->dataset, 'parlamentarier_id', 'parlamentarier_id_anzeige_name', 'Parlamentarier'),
                new FilterColumn($this->dataset, 'kommissionen', 'kommissionen', 'Kommissionen'),
                new FilterColumn($this->dataset, 'nachname', 'nachname', 'Nachname'),
                new FilterColumn($this->dataset, 'vorname', 'vorname', 'Vorname'),
                new FilterColumn($this->dataset, 'zweiter_vorname', 'zweiter_vorname', 'Zweiter Vorname'),
                new FilterColumn($this->dataset, 'funktion', 'funktion', 'Funktion'),
                new FilterColumn($this->dataset, 'beruf', 'beruf', 'Beruf'),
                new FilterColumn($this->dataset, 'beruf_interessengruppe_id', 'beruf_interessengruppe_id_anzeige_name', 'Beruf Interessengruppe'),
                new FilterColumn($this->dataset, 'partei_id', 'partei_id_abkuerzung', 'Partei'),
                new FilterColumn($this->dataset, 'geschlecht', 'geschlecht', 'Geschlecht'),
                new FilterColumn($this->dataset, 'email', 'email', 'Email'),
                new FilterColumn($this->dataset, 'homepage', 'homepage', 'Homepage'),
                new FilterColumn($this->dataset, 'twitter_name', 'twitter_name', 'Twitter Name'),
                new FilterColumn($this->dataset, 'linkedin_profil_url', 'linkedin_profil_url', 'Linkedin Profil Url'),
                new FilterColumn($this->dataset, 'xing_profil_name', 'xing_profil_name', 'Xing Profil Name'),
                new FilterColumn($this->dataset, 'facebook_name', 'facebook_name', 'Facebook Name'),
                new FilterColumn($this->dataset, 'notizen', 'notizen', 'Notizen'),
                new FilterColumn($this->dataset, 'created_visa', 'created_visa', 'Created Visa'),
                new FilterColumn($this->dataset, 'created_date', 'created_date', 'Created Date'),
                new FilterColumn($this->dataset, 'updated_visa', 'updated_visa', 'Updated Visa'),
                new FilterColumn($this->dataset, 'updated_date', 'updated_date', 'Updated Date'),
                new FilterColumn($this->dataset, 'von', 'von', 'Von'),
                new FilterColumn($this->dataset, 'bis', 'bis', 'Bis'),
                new FilterColumn($this->dataset, 'eingabe_abgeschlossen_visa', 'eingabe_abgeschlossen_visa', 'Eingabe Abgeschlossen Visa'),
                new FilterColumn($this->dataset, 'eingabe_abgeschlossen_datum', 'eingabe_abgeschlossen_datum', 'Eingabe Abgeschlossen Datum'),
                new FilterColumn($this->dataset, 'kontrolliert_visa', 'kontrolliert_visa', 'Kontrolliert Visa'),
                new FilterColumn($this->dataset, 'kontrolliert_datum', 'kontrolliert_datum', 'Kontrolliert Datum'),
                new FilterColumn($this->dataset, 'autorisierung_verschickt_visa', 'autorisierung_verschickt_visa', 'Autorisierung Verschickt Visa'),
                new FilterColumn($this->dataset, 'autorisierung_verschickt_datum', 'autorisierung_verschickt_datum', 'Autorisierung Verschickt Datum'),
                new FilterColumn($this->dataset, 'autorisiert_visa', 'autorisiert_visa', 'Autorisiert Visa'),
                new FilterColumn($this->dataset, 'autorisiert_datum', 'autorisiert_datum', 'Autorisiert Datum'),
                new FilterColumn($this->dataset, 'freigabe_visa', 'freigabe_visa', 'Freigabe Visa'),
                new FilterColumn($this->dataset, 'freigabe_datum', 'freigabe_datum', 'Freigabe Datum'),
                new FilterColumn($this->dataset, 'anzeige_name', 'anzeige_name', 'Anzeige Name'),
                new FilterColumn($this->dataset, 'anzeige_name_de', 'anzeige_name_de', 'Anzeige Name De'),
                new FilterColumn($this->dataset, 'anzeige_name_fr', 'anzeige_name_fr', 'Anzeige Name Fr'),
                new FilterColumn($this->dataset, 'name', 'name', 'Name'),
                new FilterColumn($this->dataset, 'name_de', 'name_de', 'Name De'),
                new FilterColumn($this->dataset, 'name_fr', 'name_fr', 'Name Fr'),
                new FilterColumn($this->dataset, 'beschreibung_de', 'beschreibung_de', 'Beschreibung De'),
                new FilterColumn($this->dataset, 'beschreibung_fr', 'beschreibung_fr', 'Beschreibung Fr'),
                new FilterColumn($this->dataset, 'parlamentarier_kommissionen', 'parlamentarier_kommissionen', 'Parlamentarier Kommissionen'),
                new FilterColumn($this->dataset, 'parlamentarier_kommissionen_zutrittsberechtigung', 'parlamentarier_kommissionen_zutrittsberechtigung', 'Parlamentarier Kommissionen Zutrittsberechtigung'),
                new FilterColumn($this->dataset, 'beruf_fr', 'beruf_fr', 'Beruf Fr'),
                new FilterColumn($this->dataset, 'arbeitssprache', 'arbeitssprache', 'Arbeitssprache'),
                new FilterColumn($this->dataset, 'telephon_1', 'telephon_1', 'Telephon 1'),
                new FilterColumn($this->dataset, 'telephon_2', 'telephon_2', 'Telephon 2'),
                new FilterColumn($this->dataset, 'erfasst', 'erfasst', 'Erfasst'),
                new FilterColumn($this->dataset, 'eingabe_abgeschlossen_visa_person', 'eingabe_abgeschlossen_visa_person', 'Eingabe Abgeschlossen Visa Person'),
                new FilterColumn($this->dataset, 'eingabe_abgeschlossen_datum_person', 'eingabe_abgeschlossen_datum_person', 'Eingabe Abgeschlossen Datum Person'),
                new FilterColumn($this->dataset, 'kontrolliert_visa_person', 'kontrolliert_visa_person', 'Kontrolliert Visa Person'),
                new FilterColumn($this->dataset, 'kontrolliert_datum_person', 'kontrolliert_datum_person', 'Kontrolliert Datum Person'),
                new FilterColumn($this->dataset, 'freigabe_visa_person', 'freigabe_visa_person', 'Freigabe Visa Person'),
                new FilterColumn($this->dataset, 'freigabe_datum_person', 'freigabe_datum_person', 'Freigabe Datum Person'),
                new FilterColumn($this->dataset, 'created_visa_person', 'created_visa_person', 'Created Visa Person'),
                new FilterColumn($this->dataset, 'created_date_person', 'created_date_person', 'Created Date Person'),
                new FilterColumn($this->dataset, 'updated_visa_person', 'updated_visa_person', 'Updated Visa Person'),
                new FilterColumn($this->dataset, 'updated_date_person', 'updated_date_person', 'Updated Date Person'),
                new FilterColumn($this->dataset, 'created_date_unix_person', 'created_date_unix_person', 'Created Date Unix Person'),
                new FilterColumn($this->dataset, 'updated_date_unix_person', 'updated_date_unix_person', 'Updated Date Unix Person'),
                new FilterColumn($this->dataset, 'eingabe_abgeschlossen_datum_unix_person', 'eingabe_abgeschlossen_datum_unix_person', 'Eingabe Abgeschlossen Datum Unix Person'),
                new FilterColumn($this->dataset, 'kontrolliert_datum_unix_person', 'kontrolliert_datum_unix_person', 'Kontrolliert Datum Unix Person'),
                new FilterColumn($this->dataset, 'freigabe_datum_unix_person', 'freigabe_datum_unix_person', 'Freigabe Datum Unix Person'),
                new FilterColumn($this->dataset, 'person_id', 'person_id', 'Person Id'),
                new FilterColumn($this->dataset, 'zutrittsberechtigung_id', 'zutrittsberechtigung_id', 'Zutrittsberechtigung Id'),
                new FilterColumn($this->dataset, 'funktion_fr', 'funktion_fr', 'Funktion Fr'),
                new FilterColumn($this->dataset, 'bis_unix', 'bis_unix', 'Bis Unix'),
                new FilterColumn($this->dataset, 'von_unix', 'von_unix', 'Von Unix'),
                new FilterColumn($this->dataset, 'created_date_unix', 'created_date_unix', 'Created Date Unix'),
                new FilterColumn($this->dataset, 'updated_date_unix', 'updated_date_unix', 'Updated Date Unix'),
                new FilterColumn($this->dataset, 'eingabe_abgeschlossen_datum_unix', 'eingabe_abgeschlossen_datum_unix', 'Eingabe Abgeschlossen Datum Unix'),
                new FilterColumn($this->dataset, 'kontrolliert_datum_unix', 'kontrolliert_datum_unix', 'Kontrolliert Datum Unix'),
                new FilterColumn($this->dataset, 'freigabe_datum_unix', 'freigabe_datum_unix', 'Freigabe Datum Unix'),
                new FilterColumn($this->dataset, 'published_person', 'published_person', 'Published Person'),
                new FilterColumn($this->dataset, 'published', 'published', 'Published')
            );
        }
    
        protected function setupQuickFilter(QuickFilter $quickFilter, FixedKeysArray $columns)
        {
            $quickFilter
                ->addColumn($columns['id'])
                ->addColumn($columns['parlamentarier_id'])
                ->addColumn($columns['kommissionen'])
                ->addColumn($columns['nachname'])
                ->addColumn($columns['vorname'])
                ->addColumn($columns['zweiter_vorname'])
                ->addColumn($columns['funktion'])
                ->addColumn($columns['beruf'])
                ->addColumn($columns['beruf_interessengruppe_id'])
                ->addColumn($columns['partei_id'])
                ->addColumn($columns['geschlecht'])
                ->addColumn($columns['email'])
                ->addColumn($columns['homepage'])
                ->addColumn($columns['twitter_name'])
                ->addColumn($columns['linkedin_profil_url'])
                ->addColumn($columns['xing_profil_name'])
                ->addColumn($columns['facebook_name'])
                ->addColumn($columns['notizen'])
                ->addColumn($columns['created_visa'])
                ->addColumn($columns['created_date'])
                ->addColumn($columns['updated_visa'])
                ->addColumn($columns['updated_date'])
                ->addColumn($columns['anzeige_name'])
                ->addColumn($columns['anzeige_name_de'])
                ->addColumn($columns['anzeige_name_fr'])
                ->addColumn($columns['name'])
                ->addColumn($columns['name_de'])
                ->addColumn($columns['name_fr'])
                ->addColumn($columns['beschreibung_de'])
                ->addColumn($columns['beschreibung_fr'])
                ->addColumn($columns['parlamentarier_kommissionen'])
                ->addColumn($columns['parlamentarier_kommissionen_zutrittsberechtigung'])
                ->addColumn($columns['beruf_fr'])
                ->addColumn($columns['arbeitssprache'])
                ->addColumn($columns['telephon_1'])
                ->addColumn($columns['telephon_2'])
                ->addColumn($columns['erfasst'])
                ->addColumn($columns['eingabe_abgeschlossen_visa_person'])
                ->addColumn($columns['eingabe_abgeschlossen_datum_person'])
                ->addColumn($columns['kontrolliert_visa_person'])
                ->addColumn($columns['kontrolliert_datum_person'])
                ->addColumn($columns['freigabe_visa_person'])
                ->addColumn($columns['freigabe_datum_person'])
                ->addColumn($columns['created_visa_person'])
                ->addColumn($columns['created_date_person'])
                ->addColumn($columns['updated_visa_person'])
                ->addColumn($columns['updated_date_person'])
                ->addColumn($columns['created_date_unix_person'])
                ->addColumn($columns['updated_date_unix_person'])
                ->addColumn($columns['eingabe_abgeschlossen_datum_unix_person'])
                ->addColumn($columns['kontrolliert_datum_unix_person'])
                ->addColumn($columns['freigabe_datum_unix_person'])
                ->addColumn($columns['person_id'])
                ->addColumn($columns['zutrittsberechtigung_id'])
                ->addColumn($columns['funktion_fr'])
                ->addColumn($columns['bis_unix'])
                ->addColumn($columns['von_unix'])
                ->addColumn($columns['created_date_unix'])
                ->addColumn($columns['updated_date_unix'])
                ->addColumn($columns['eingabe_abgeschlossen_datum_unix'])
                ->addColumn($columns['kontrolliert_datum_unix'])
                ->addColumn($columns['freigabe_datum_unix'])
                ->addColumn($columns['published_person'])
                ->addColumn($columns['published']);
        }
    
        protected function setupColumnFilter(ColumnFilter $columnFilter)
        {
            $columnFilter
                ->setOptionsFor('parlamentarier_id')
                ->setOptionsFor('beruf_interessengruppe_id')
                ->setOptionsFor('partei_id')
                ->setOptionsFor('created_date')
                ->setOptionsFor('updated_date')
                ->setOptionsFor('eingabe_abgeschlossen_datum_person')
                ->setOptionsFor('kontrolliert_datum_person')
                ->setOptionsFor('freigabe_datum_person')
                ->setOptionsFor('created_date_person')
                ->setOptionsFor('updated_date_person');
        }
    
        protected function setupFilterBuilder(FilterBuilder $filterBuilder, FixedKeysArray $columns)
        {
            $main_editor = new SpinEdit('id_edit');
            
            $filterBuilder->addColumn(
                $columns['id'],
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
            
            $main_editor = new DynamicCombobox('parlamentarier_id_edit', $this->CreateLinkBuilder());
            $main_editor->setAllowClear(true);
            $main_editor->setMinimumInputLength(0);
            $main_editor->SetAllowNullValue(false);
            $main_editor->SetHandlerName('filter_builder_q_unvollstaendige_zutrittsberechtigte_parlamentarier_id_search');
            
            $multi_value_select_editor = new RemoteMultiValueSelect('parlamentarier_id', $this->CreateLinkBuilder());
            $multi_value_select_editor->SetHandlerName('filter_builder_q_unvollstaendige_zutrittsberechtigte_parlamentarier_id_search');
            
            $text_editor = new TextEdit('parlamentarier_id');
            
            $filterBuilder->addColumn(
                $columns['parlamentarier_id'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $text_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $text_editor,
                    FilterConditionOperator::BEGINS_WITH => $text_editor,
                    FilterConditionOperator::ENDS_WITH => $text_editor,
                    FilterConditionOperator::IS_LIKE => $text_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $text_editor,
                    FilterConditionOperator::IN => $multi_value_select_editor,
                    FilterConditionOperator::NOT_IN => $multi_value_select_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new TextEdit('kommissionen_edit');
            
            $filterBuilder->addColumn(
                $columns['kommissionen'],
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
            
            $main_editor = new TextEdit('nachname_edit');
            
            $filterBuilder->addColumn(
                $columns['nachname'],
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
            
            $main_editor = new TextEdit('vorname_edit');
            
            $filterBuilder->addColumn(
                $columns['vorname'],
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
            
            $main_editor = new TextEdit('zweiter_vorname_edit');
            
            $filterBuilder->addColumn(
                $columns['zweiter_vorname'],
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
            
            $main_editor = new TextEdit('funktion_edit');
            
            $filterBuilder->addColumn(
                $columns['funktion'],
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
            
            $main_editor = new TextEdit('beruf_edit');
            
            $filterBuilder->addColumn(
                $columns['beruf'],
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
            
            $main_editor = new DynamicCombobox('beruf_interessengruppe_id_edit', $this->CreateLinkBuilder());
            $main_editor->setAllowClear(true);
            $main_editor->setMinimumInputLength(0);
            $main_editor->SetAllowNullValue(false);
            $main_editor->SetHandlerName('filter_builder_q_unvollstaendige_zutrittsberechtigte_beruf_interessengruppe_id_search');
            
            $multi_value_select_editor = new RemoteMultiValueSelect('beruf_interessengruppe_id', $this->CreateLinkBuilder());
            $multi_value_select_editor->SetHandlerName('filter_builder_q_unvollstaendige_zutrittsberechtigte_beruf_interessengruppe_id_search');
            
            $text_editor = new TextEdit('beruf_interessengruppe_id');
            
            $filterBuilder->addColumn(
                $columns['beruf_interessengruppe_id'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $text_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $text_editor,
                    FilterConditionOperator::BEGINS_WITH => $text_editor,
                    FilterConditionOperator::ENDS_WITH => $text_editor,
                    FilterConditionOperator::IS_LIKE => $text_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $text_editor,
                    FilterConditionOperator::IN => $multi_value_select_editor,
                    FilterConditionOperator::NOT_IN => $multi_value_select_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new DynamicCombobox('partei_id_edit', $this->CreateLinkBuilder());
            $main_editor->setAllowClear(true);
            $main_editor->setMinimumInputLength(0);
            $main_editor->SetAllowNullValue(false);
            $main_editor->SetHandlerName('filter_builder_q_unvollstaendige_zutrittsberechtigte_partei_id_search');
            
            $multi_value_select_editor = new RemoteMultiValueSelect('partei_id', $this->CreateLinkBuilder());
            $multi_value_select_editor->SetHandlerName('filter_builder_q_unvollstaendige_zutrittsberechtigte_partei_id_search');
            
            $text_editor = new TextEdit('partei_id');
            
            $filterBuilder->addColumn(
                $columns['partei_id'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $text_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $text_editor,
                    FilterConditionOperator::BEGINS_WITH => $text_editor,
                    FilterConditionOperator::ENDS_WITH => $text_editor,
                    FilterConditionOperator::IS_LIKE => $text_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $text_editor,
                    FilterConditionOperator::IN => $multi_value_select_editor,
                    FilterConditionOperator::NOT_IN => $multi_value_select_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new ComboBox('geschlecht_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $main_editor->addChoice('M', 'M');
            $main_editor->addChoice('F', 'F');
            $main_editor->SetAllowNullValue(false);
            
            $multi_value_select_editor = new MultiValueSelect('geschlecht');
            $multi_value_select_editor->setChoices($main_editor->getChoices());
            
            $text_editor = new TextEdit('geschlecht');
            
            $filterBuilder->addColumn(
                $columns['geschlecht'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $text_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $text_editor,
                    FilterConditionOperator::BEGINS_WITH => $text_editor,
                    FilterConditionOperator::ENDS_WITH => $text_editor,
                    FilterConditionOperator::IS_LIKE => $text_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $text_editor,
                    FilterConditionOperator::IN => $multi_value_select_editor,
                    FilterConditionOperator::NOT_IN => $multi_value_select_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new TextEdit('email_edit');
            
            $filterBuilder->addColumn(
                $columns['email'],
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
            
            $main_editor = new TextEdit('homepage_edit');
            
            $filterBuilder->addColumn(
                $columns['homepage'],
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
            
            $main_editor = new TextEdit('twitter_name_edit');
            
            $filterBuilder->addColumn(
                $columns['twitter_name'],
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
            
            $main_editor = new TextEdit('linkedin_profil_url_edit');
            
            $filterBuilder->addColumn(
                $columns['linkedin_profil_url'],
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
            
            $main_editor = new TextEdit('xing_profil_name_edit');
            
            $filterBuilder->addColumn(
                $columns['xing_profil_name'],
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
            
            $main_editor = new TextEdit('facebook_name_edit');
            
            $filterBuilder->addColumn(
                $columns['facebook_name'],
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
            
            $main_editor = new TextEdit('notizen_edit');
            
            $filterBuilder->addColumn(
                $columns['notizen'],
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
            
            $main_editor = new TextEdit('created_visa_edit');
            
            $filterBuilder->addColumn(
                $columns['created_visa'],
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
            
            $main_editor = new DateTimeEdit('created_date_edit', false, 'Y-m-d H:i:s');
            
            $filterBuilder->addColumn(
                $columns['created_date'],
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
            
            $main_editor = new TextEdit('updated_visa_edit');
            
            $filterBuilder->addColumn(
                $columns['updated_visa'],
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
            
            $main_editor = new DateTimeEdit('updated_date_edit', false, 'Y-m-d H:i:s');
            
            $filterBuilder->addColumn(
                $columns['updated_date'],
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
            
            $main_editor = new TextEdit('anzeige_name_edit');
            
            $filterBuilder->addColumn(
                $columns['anzeige_name'],
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
            
            $main_editor = new TextEdit('anzeige_name_de_edit');
            
            $filterBuilder->addColumn(
                $columns['anzeige_name_de'],
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
            
            $main_editor = new TextEdit('anzeige_name_fr_edit');
            
            $filterBuilder->addColumn(
                $columns['anzeige_name_fr'],
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
            
            $main_editor = new TextEdit('name_de_edit');
            
            $filterBuilder->addColumn(
                $columns['name_de'],
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
            
            $main_editor = new TextEdit('name_fr_edit');
            
            $filterBuilder->addColumn(
                $columns['name_fr'],
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
            
            $main_editor = new TextEdit('beschreibung_de_edit');
            
            $filterBuilder->addColumn(
                $columns['beschreibung_de'],
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
            
            $main_editor = new TextEdit('beschreibung_fr_edit');
            
            $filterBuilder->addColumn(
                $columns['beschreibung_fr'],
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
            
            $main_editor = new TextEdit('parlamentarier_kommissionen_edit');
            
            $filterBuilder->addColumn(
                $columns['parlamentarier_kommissionen'],
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
            
            $main_editor = new TextEdit('parlamentarier_kommissionen_zutrittsberechtigung_edit');
            
            $filterBuilder->addColumn(
                $columns['parlamentarier_kommissionen_zutrittsberechtigung'],
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
            
            $main_editor = new TextEdit('beruf_fr_edit');
            
            $filterBuilder->addColumn(
                $columns['beruf_fr'],
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
            
            $main_editor = new TextEdit('arbeitssprache_edit');
            
            $filterBuilder->addColumn(
                $columns['arbeitssprache'],
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
            
            $main_editor = new TextEdit('telephon_1_edit');
            
            $filterBuilder->addColumn(
                $columns['telephon_1'],
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
            
            $main_editor = new TextEdit('telephon_2_edit');
            
            $filterBuilder->addColumn(
                $columns['telephon_2'],
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
            
            $main_editor = new TextEdit('erfasst_edit');
            
            $filterBuilder->addColumn(
                $columns['erfasst'],
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
            
            $main_editor = new TextEdit('eingabe_abgeschlossen_visa_person_edit');
            
            $filterBuilder->addColumn(
                $columns['eingabe_abgeschlossen_visa_person'],
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
            
            $main_editor = new DateTimeEdit('eingabe_abgeschlossen_datum_person_edit', false, 'd.m.Y H:i:s');
            
            $filterBuilder->addColumn(
                $columns['eingabe_abgeschlossen_datum_person'],
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
            
            $main_editor = new TextEdit('kontrolliert_visa_person_edit');
            
            $filterBuilder->addColumn(
                $columns['kontrolliert_visa_person'],
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
            
            $main_editor = new DateTimeEdit('kontrolliert_datum_person_edit', false, 'd.m.Y H:i:s');
            
            $filterBuilder->addColumn(
                $columns['kontrolliert_datum_person'],
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
            
            $main_editor = new TextEdit('freigabe_visa_person_edit');
            
            $filterBuilder->addColumn(
                $columns['freigabe_visa_person'],
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
            
            $main_editor = new DateTimeEdit('freigabe_datum_person_edit', false, 'd.m.Y H:i:s');
            
            $filterBuilder->addColumn(
                $columns['freigabe_datum_person'],
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
            
            $main_editor = new TextEdit('created_visa_person_edit');
            
            $filterBuilder->addColumn(
                $columns['created_visa_person'],
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
            
            $main_editor = new DateTimeEdit('created_date_person_edit', false, 'd.m.Y H:i:s');
            
            $filterBuilder->addColumn(
                $columns['created_date_person'],
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
            
            $main_editor = new TextEdit('updated_visa_person_edit');
            
            $filterBuilder->addColumn(
                $columns['updated_visa_person'],
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
            
            $main_editor = new DateTimeEdit('updated_date_person_edit', false, 'd.m.Y H:i:s');
            
            $filterBuilder->addColumn(
                $columns['updated_date_person'],
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
            
            $main_editor = new TextEdit('created_date_unix_person_edit');
            
            $filterBuilder->addColumn(
                $columns['created_date_unix_person'],
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
            
            $main_editor = new TextEdit('updated_date_unix_person_edit');
            
            $filterBuilder->addColumn(
                $columns['updated_date_unix_person'],
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
            
            $main_editor = new TextEdit('eingabe_abgeschlossen_datum_unix_person_edit');
            
            $filterBuilder->addColumn(
                $columns['eingabe_abgeschlossen_datum_unix_person'],
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
            
            $main_editor = new TextEdit('kontrolliert_datum_unix_person_edit');
            
            $filterBuilder->addColumn(
                $columns['kontrolliert_datum_unix_person'],
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
            
            $main_editor = new TextEdit('freigabe_datum_unix_person_edit');
            
            $filterBuilder->addColumn(
                $columns['freigabe_datum_unix_person'],
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
            
            $main_editor = new SpinEdit('person_id_edit');
            
            $filterBuilder->addColumn(
                $columns['person_id'],
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
            
            $main_editor = new SpinEdit('zutrittsberechtigung_id_edit');
            
            $filterBuilder->addColumn(
                $columns['zutrittsberechtigung_id'],
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
            
            $main_editor = new TextEdit('funktion_fr_edit');
            
            $filterBuilder->addColumn(
                $columns['funktion_fr'],
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
            
            $main_editor = new TextEdit('bis_unix_edit');
            
            $filterBuilder->addColumn(
                $columns['bis_unix'],
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
            
            $main_editor = new TextEdit('von_unix_edit');
            
            $filterBuilder->addColumn(
                $columns['von_unix'],
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
            
            $main_editor = new TextEdit('created_date_unix_edit');
            
            $filterBuilder->addColumn(
                $columns['created_date_unix'],
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
            
            $main_editor = new TextEdit('updated_date_unix_edit');
            
            $filterBuilder->addColumn(
                $columns['updated_date_unix'],
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
            
            $main_editor = new TextEdit('eingabe_abgeschlossen_datum_unix_edit');
            
            $filterBuilder->addColumn(
                $columns['eingabe_abgeschlossen_datum_unix'],
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
            
            $main_editor = new TextEdit('kontrolliert_datum_unix_edit');
            
            $filterBuilder->addColumn(
                $columns['kontrolliert_datum_unix'],
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
            
            $main_editor = new TextEdit('freigabe_datum_unix_edit');
            
            $filterBuilder->addColumn(
                $columns['freigabe_datum_unix'],
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
            
            $main_editor = new TextEdit('published_person_edit');
            
            $filterBuilder->addColumn(
                $columns['published_person'],
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
            
            $main_editor = new TextEdit('published_edit');
            
            $filterBuilder->addColumn(
                $columns['published'],
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
        }
    
        protected function AddOperationsColumns(Grid $grid)
        {
    
        }
    
        protected function AddFieldColumns(Grid $grid, $withDetails = true)
        {
            //
            // View column for id field
            //
            $column = new TextViewColumn('id', 'id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for anzeige_name field
            //
            $column = new TextViewColumn('parlamentarier_id', 'parlamentarier_id_anzeige_name', 'Parlamentarier', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for kommissionen field
            //
            $column = new TextViewColumn('kommissionen', 'kommissionen', 'Kommissionen', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for nachname field
            //
            $column = new TextViewColumn('nachname', 'nachname', 'Nachname', $this->dataset);
            $column->SetOrderable(true);
            $column->setHrefTemplate('person.php?operation=edit&amp;pk0=%id%&amp;t=0');
            $column->setTarget('');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for vorname field
            //
            $column = new TextViewColumn('vorname', 'vorname', 'Vorname', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for zweiter_vorname field
            //
            $column = new TextViewColumn('zweiter_vorname', 'zweiter_vorname', 'Zweiter Vorname', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for funktion field
            //
            $column = new TextViewColumn('funktion', 'funktion', 'Funktion', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for beruf field
            //
            $column = new TextViewColumn('beruf', 'beruf', 'Beruf', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for anzeige_name field
            //
            $column = new TextViewColumn('beruf_interessengruppe_id', 'beruf_interessengruppe_id_anzeige_name', 'Beruf Interessengruppe', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for abkuerzung field
            //
            $column = new TextViewColumn('partei_id', 'partei_id_abkuerzung', 'Partei', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for geschlecht field
            //
            $column = new TextViewColumn('geschlecht', 'geschlecht', 'Geschlecht', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for email field
            //
            $column = new TextViewColumn('email', 'email', 'Email', $this->dataset);
            $column->SetOrderable(true);
            $column->setHrefTemplate('mailtto:%email%');
            $column->setTarget('_blank');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for homepage field
            //
            $column = new TextViewColumn('homepage', 'homepage', 'Homepage', $this->dataset);
            $column->SetOrderable(true);
            $column->setHrefTemplate('%homepage%');
            $column->setTarget('');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for twitter_name field
            //
            $column = new TextViewColumn('twitter_name', 'twitter_name', 'Twitter Name', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for linkedin_profil_url field
            //
            $column = new TextViewColumn('linkedin_profil_url', 'linkedin_profil_url', 'Linkedin Profil Url', $this->dataset);
            $column->SetOrderable(true);
            $column->setHrefTemplate('%linkedin_profil_url%');
            $column->setTarget('');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for xing_profil_name field
            //
            $column = new TextViewColumn('xing_profil_name', 'xing_profil_name', 'Xing Profil Name', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for facebook_name field
            //
            $column = new TextViewColumn('facebook_name', 'facebook_name', 'Facebook Name', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for created_visa field
            //
            $column = new TextViewColumn('created_visa', 'created_visa', 'Created Visa', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for created_date field
            //
            $column = new DateTimeViewColumn('created_date', 'created_date', 'Created Date', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for updated_visa field
            //
            $column = new TextViewColumn('updated_visa', 'updated_visa', 'Updated Visa', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for updated_date field
            //
            $column = new DateTimeViewColumn('updated_date', 'updated_date', 'Updated Date', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for anzeige_name field
            //
            $column = new TextViewColumn('anzeige_name', 'anzeige_name', 'Anzeige Name', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for anzeige_name_de field
            //
            $column = new TextViewColumn('anzeige_name_de', 'anzeige_name_de', 'Anzeige Name De', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for anzeige_name_fr field
            //
            $column = new TextViewColumn('anzeige_name_fr', 'anzeige_name_fr', 'Anzeige Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('name', 'name', 'Name', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for name_de field
            //
            $column = new TextViewColumn('name_de', 'name_de', 'Name De', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for name_fr field
            //
            $column = new TextViewColumn('name_fr', 'name_fr', 'Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for beschreibung_de field
            //
            $column = new TextViewColumn('beschreibung_de', 'beschreibung_de', 'Beschreibung De', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for beschreibung_fr field
            //
            $column = new TextViewColumn('beschreibung_fr', 'beschreibung_fr', 'Beschreibung Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for parlamentarier_kommissionen field
            //
            $column = new TextViewColumn('parlamentarier_kommissionen', 'parlamentarier_kommissionen', 'Parlamentarier Kommissionen', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for parlamentarier_kommissionen_zutrittsberechtigung field
            //
            $column = new TextViewColumn('parlamentarier_kommissionen_zutrittsberechtigung', 'parlamentarier_kommissionen_zutrittsberechtigung', 'Parlamentarier Kommissionen Zutrittsberechtigung', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for beruf_fr field
            //
            $column = new TextViewColumn('beruf_fr', 'beruf_fr', 'Beruf Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for arbeitssprache field
            //
            $column = new TextViewColumn('arbeitssprache', 'arbeitssprache', 'Arbeitssprache', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for telephon_1 field
            //
            $column = new TextViewColumn('telephon_1', 'telephon_1', 'Telephon 1', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for telephon_2 field
            //
            $column = new TextViewColumn('telephon_2', 'telephon_2', 'Telephon 2', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for erfasst field
            //
            $column = new TextViewColumn('erfasst', 'erfasst', 'Erfasst', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_visa_person field
            //
            $column = new TextViewColumn('eingabe_abgeschlossen_visa_person', 'eingabe_abgeschlossen_visa_person', 'Eingabe Abgeschlossen Visa Person', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_datum_person field
            //
            $column = new DateTimeViewColumn('eingabe_abgeschlossen_datum_person', 'eingabe_abgeschlossen_datum_person', 'Eingabe Abgeschlossen Datum Person', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for kontrolliert_visa_person field
            //
            $column = new TextViewColumn('kontrolliert_visa_person', 'kontrolliert_visa_person', 'Kontrolliert Visa Person', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for kontrolliert_datum_person field
            //
            $column = new DateTimeViewColumn('kontrolliert_datum_person', 'kontrolliert_datum_person', 'Kontrolliert Datum Person', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for freigabe_visa_person field
            //
            $column = new TextViewColumn('freigabe_visa_person', 'freigabe_visa_person', 'Freigabe Visa Person', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for freigabe_datum_person field
            //
            $column = new DateTimeViewColumn('freigabe_datum_person', 'freigabe_datum_person', 'Freigabe Datum Person', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for created_visa_person field
            //
            $column = new TextViewColumn('created_visa_person', 'created_visa_person', 'Created Visa Person', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for created_date_person field
            //
            $column = new DateTimeViewColumn('created_date_person', 'created_date_person', 'Created Date Person', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for updated_visa_person field
            //
            $column = new TextViewColumn('updated_visa_person', 'updated_visa_person', 'Updated Visa Person', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for updated_date_person field
            //
            $column = new DateTimeViewColumn('updated_date_person', 'updated_date_person', 'Updated Date Person', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for created_date_unix_person field
            //
            $column = new TextViewColumn('created_date_unix_person', 'created_date_unix_person', 'Created Date Unix Person', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for updated_date_unix_person field
            //
            $column = new TextViewColumn('updated_date_unix_person', 'updated_date_unix_person', 'Updated Date Unix Person', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_datum_unix_person field
            //
            $column = new TextViewColumn('eingabe_abgeschlossen_datum_unix_person', 'eingabe_abgeschlossen_datum_unix_person', 'Eingabe Abgeschlossen Datum Unix Person', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for kontrolliert_datum_unix_person field
            //
            $column = new TextViewColumn('kontrolliert_datum_unix_person', 'kontrolliert_datum_unix_person', 'Kontrolliert Datum Unix Person', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for freigabe_datum_unix_person field
            //
            $column = new TextViewColumn('freigabe_datum_unix_person', 'freigabe_datum_unix_person', 'Freigabe Datum Unix Person', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for person_id field
            //
            $column = new NumberViewColumn('person_id', 'person_id', 'Person Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator('\'');
            $column->setDecimalSeparator('');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for zutrittsberechtigung_id field
            //
            $column = new NumberViewColumn('zutrittsberechtigung_id', 'zutrittsberechtigung_id', 'Zutrittsberechtigung Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator('\'');
            $column->setDecimalSeparator('');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for funktion_fr field
            //
            $column = new TextViewColumn('funktion_fr', 'funktion_fr', 'Funktion Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for bis_unix field
            //
            $column = new TextViewColumn('bis_unix', 'bis_unix', 'Bis Unix', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for von_unix field
            //
            $column = new TextViewColumn('von_unix', 'von_unix', 'Von Unix', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for created_date_unix field
            //
            $column = new TextViewColumn('created_date_unix', 'created_date_unix', 'Created Date Unix', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for updated_date_unix field
            //
            $column = new TextViewColumn('updated_date_unix', 'updated_date_unix', 'Updated Date Unix', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_datum_unix field
            //
            $column = new TextViewColumn('eingabe_abgeschlossen_datum_unix', 'eingabe_abgeschlossen_datum_unix', 'Eingabe Abgeschlossen Datum Unix', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for kontrolliert_datum_unix field
            //
            $column = new TextViewColumn('kontrolliert_datum_unix', 'kontrolliert_datum_unix', 'Kontrolliert Datum Unix', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for freigabe_datum_unix field
            //
            $column = new TextViewColumn('freigabe_datum_unix', 'freigabe_datum_unix', 'Freigabe Datum Unix', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for published_person field
            //
            $column = new TextViewColumn('published_person', 'published_person', 'Published Person', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for published field
            //
            $column = new TextViewColumn('published', 'published', 'Published', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
        }
    
        protected function AddSingleRecordViewColumns(Grid $grid)
        {
            //
            // View column for id field
            //
            $column = new TextViewColumn('id', 'id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for anzeige_name field
            //
            $column = new TextViewColumn('parlamentarier_id', 'parlamentarier_id_anzeige_name', 'Parlamentarier', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for kommissionen field
            //
            $column = new TextViewColumn('kommissionen', 'kommissionen', 'Kommissionen', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for nachname field
            //
            $column = new TextViewColumn('nachname', 'nachname', 'Nachname', $this->dataset);
            $column->SetOrderable(true);
            $column->setHrefTemplate('person.php?operation=edit&amp;pk0=%id%&amp;t=0');
            $column->setTarget('');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for vorname field
            //
            $column = new TextViewColumn('vorname', 'vorname', 'Vorname', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for zweiter_vorname field
            //
            $column = new TextViewColumn('zweiter_vorname', 'zweiter_vorname', 'Zweiter Vorname', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for funktion field
            //
            $column = new TextViewColumn('funktion', 'funktion', 'Funktion', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for beruf field
            //
            $column = new TextViewColumn('beruf', 'beruf', 'Beruf', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for anzeige_name field
            //
            $column = new TextViewColumn('beruf_interessengruppe_id', 'beruf_interessengruppe_id_anzeige_name', 'Beruf Interessengruppe', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for abkuerzung field
            //
            $column = new TextViewColumn('partei_id', 'partei_id_abkuerzung', 'Partei', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for geschlecht field
            //
            $column = new TextViewColumn('geschlecht', 'geschlecht', 'Geschlecht', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for email field
            //
            $column = new TextViewColumn('email', 'email', 'Email', $this->dataset);
            $column->SetOrderable(true);
            $column->setHrefTemplate('mailtto:%email%');
            $column->setTarget('_blank');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for homepage field
            //
            $column = new TextViewColumn('homepage', 'homepage', 'Homepage', $this->dataset);
            $column->SetOrderable(true);
            $column->setHrefTemplate('%homepage%');
            $column->setTarget('');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for twitter_name field
            //
            $column = new TextViewColumn('twitter_name', 'twitter_name', 'Twitter Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for linkedin_profil_url field
            //
            $column = new TextViewColumn('linkedin_profil_url', 'linkedin_profil_url', 'Linkedin Profil Url', $this->dataset);
            $column->SetOrderable(true);
            $column->setHrefTemplate('%linkedin_profil_url%');
            $column->setTarget('');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for xing_profil_name field
            //
            $column = new TextViewColumn('xing_profil_name', 'xing_profil_name', 'Xing Profil Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for facebook_name field
            //
            $column = new TextViewColumn('facebook_name', 'facebook_name', 'Facebook Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for created_visa field
            //
            $column = new TextViewColumn('created_visa', 'created_visa', 'Created Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for created_date field
            //
            $column = new DateTimeViewColumn('created_date', 'created_date', 'Created Date', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for updated_visa field
            //
            $column = new TextViewColumn('updated_visa', 'updated_visa', 'Updated Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for updated_date field
            //
            $column = new DateTimeViewColumn('updated_date', 'updated_date', 'Updated Date', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for anzeige_name field
            //
            $column = new TextViewColumn('anzeige_name', 'anzeige_name', 'Anzeige Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for anzeige_name_de field
            //
            $column = new TextViewColumn('anzeige_name_de', 'anzeige_name_de', 'Anzeige Name De', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for anzeige_name_fr field
            //
            $column = new TextViewColumn('anzeige_name_fr', 'anzeige_name_fr', 'Anzeige Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('name', 'name', 'Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for name_de field
            //
            $column = new TextViewColumn('name_de', 'name_de', 'Name De', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for name_fr field
            //
            $column = new TextViewColumn('name_fr', 'name_fr', 'Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for beschreibung_de field
            //
            $column = new TextViewColumn('beschreibung_de', 'beschreibung_de', 'Beschreibung De', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for beschreibung_fr field
            //
            $column = new TextViewColumn('beschreibung_fr', 'beschreibung_fr', 'Beschreibung Fr', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for parlamentarier_kommissionen field
            //
            $column = new TextViewColumn('parlamentarier_kommissionen', 'parlamentarier_kommissionen', 'Parlamentarier Kommissionen', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for parlamentarier_kommissionen_zutrittsberechtigung field
            //
            $column = new TextViewColumn('parlamentarier_kommissionen_zutrittsberechtigung', 'parlamentarier_kommissionen_zutrittsberechtigung', 'Parlamentarier Kommissionen Zutrittsberechtigung', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for beruf_fr field
            //
            $column = new TextViewColumn('beruf_fr', 'beruf_fr', 'Beruf Fr', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for arbeitssprache field
            //
            $column = new TextViewColumn('arbeitssprache', 'arbeitssprache', 'Arbeitssprache', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for telephon_1 field
            //
            $column = new TextViewColumn('telephon_1', 'telephon_1', 'Telephon 1', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for telephon_2 field
            //
            $column = new TextViewColumn('telephon_2', 'telephon_2', 'Telephon 2', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for erfasst field
            //
            $column = new TextViewColumn('erfasst', 'erfasst', 'Erfasst', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_visa_person field
            //
            $column = new TextViewColumn('eingabe_abgeschlossen_visa_person', 'eingabe_abgeschlossen_visa_person', 'Eingabe Abgeschlossen Visa Person', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_datum_person field
            //
            $column = new DateTimeViewColumn('eingabe_abgeschlossen_datum_person', 'eingabe_abgeschlossen_datum_person', 'Eingabe Abgeschlossen Datum Person', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for kontrolliert_visa_person field
            //
            $column = new TextViewColumn('kontrolliert_visa_person', 'kontrolliert_visa_person', 'Kontrolliert Visa Person', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for kontrolliert_datum_person field
            //
            $column = new DateTimeViewColumn('kontrolliert_datum_person', 'kontrolliert_datum_person', 'Kontrolliert Datum Person', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for freigabe_visa_person field
            //
            $column = new TextViewColumn('freigabe_visa_person', 'freigabe_visa_person', 'Freigabe Visa Person', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for freigabe_datum_person field
            //
            $column = new DateTimeViewColumn('freigabe_datum_person', 'freigabe_datum_person', 'Freigabe Datum Person', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for created_visa_person field
            //
            $column = new TextViewColumn('created_visa_person', 'created_visa_person', 'Created Visa Person', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for created_date_person field
            //
            $column = new DateTimeViewColumn('created_date_person', 'created_date_person', 'Created Date Person', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for updated_visa_person field
            //
            $column = new TextViewColumn('updated_visa_person', 'updated_visa_person', 'Updated Visa Person', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for updated_date_person field
            //
            $column = new DateTimeViewColumn('updated_date_person', 'updated_date_person', 'Updated Date Person', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for created_date_unix_person field
            //
            $column = new TextViewColumn('created_date_unix_person', 'created_date_unix_person', 'Created Date Unix Person', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for updated_date_unix_person field
            //
            $column = new TextViewColumn('updated_date_unix_person', 'updated_date_unix_person', 'Updated Date Unix Person', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_datum_unix_person field
            //
            $column = new TextViewColumn('eingabe_abgeschlossen_datum_unix_person', 'eingabe_abgeschlossen_datum_unix_person', 'Eingabe Abgeschlossen Datum Unix Person', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for kontrolliert_datum_unix_person field
            //
            $column = new TextViewColumn('kontrolliert_datum_unix_person', 'kontrolliert_datum_unix_person', 'Kontrolliert Datum Unix Person', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for freigabe_datum_unix_person field
            //
            $column = new TextViewColumn('freigabe_datum_unix_person', 'freigabe_datum_unix_person', 'Freigabe Datum Unix Person', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for person_id field
            //
            $column = new NumberViewColumn('person_id', 'person_id', 'Person Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator('\'');
            $column->setDecimalSeparator('');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for zutrittsberechtigung_id field
            //
            $column = new NumberViewColumn('zutrittsberechtigung_id', 'zutrittsberechtigung_id', 'Zutrittsberechtigung Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator('\'');
            $column->setDecimalSeparator('');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for funktion_fr field
            //
            $column = new TextViewColumn('funktion_fr', 'funktion_fr', 'Funktion Fr', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for bis_unix field
            //
            $column = new TextViewColumn('bis_unix', 'bis_unix', 'Bis Unix', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for von_unix field
            //
            $column = new TextViewColumn('von_unix', 'von_unix', 'Von Unix', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for created_date_unix field
            //
            $column = new TextViewColumn('created_date_unix', 'created_date_unix', 'Created Date Unix', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for updated_date_unix field
            //
            $column = new TextViewColumn('updated_date_unix', 'updated_date_unix', 'Updated Date Unix', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_datum_unix field
            //
            $column = new TextViewColumn('eingabe_abgeschlossen_datum_unix', 'eingabe_abgeschlossen_datum_unix', 'Eingabe Abgeschlossen Datum Unix', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for kontrolliert_datum_unix field
            //
            $column = new TextViewColumn('kontrolliert_datum_unix', 'kontrolliert_datum_unix', 'Kontrolliert Datum Unix', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for freigabe_datum_unix field
            //
            $column = new TextViewColumn('freigabe_datum_unix', 'freigabe_datum_unix', 'Freigabe Datum Unix', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for published_person field
            //
            $column = new TextViewColumn('published_person', 'published_person', 'Published Person', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for published field
            //
            $column = new TextViewColumn('published', 'published', 'Published', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
        }
    
        protected function AddEditColumns(Grid $grid)
        {
            //
            // Edit column for kommissionen field
            //
            $editor = new TextEdit('kommissionen_edit');
            $editColumn = new CustomEditColumn('Kommissionen', 'kommissionen', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for nachname field
            //
            $editor = new TextEdit('nachname_edit');
            $editColumn = new CustomEditColumn('Nachname', 'nachname', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for vorname field
            //
            $editor = new TextEdit('vorname_edit');
            $editColumn = new CustomEditColumn('Vorname', 'vorname', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for zweiter_vorname field
            //
            $editor = new TextEdit('zweiter_vorname_edit');
            $editColumn = new CustomEditColumn('Zweiter Vorname', 'zweiter_vorname', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for funktion field
            //
            $editor = new TextEdit('funktion_edit');
            $editColumn = new CustomEditColumn('Funktion', 'funktion', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for beruf field
            //
            $editor = new TextEdit('beruf_edit');
            $editColumn = new CustomEditColumn('Beruf', 'beruf', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for beruf_interessengruppe_id field
            //
            $editor = new ComboBox('beruf_interessengruppe_id_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`v_interessengruppe_simple`');
            $lookupDataset->addFields(
                array(
                    new StringField('anzeige_name'),
                    new StringField('anzeige_name_de'),
                    new StringField('anzeige_name_fr'),
                    new StringField('anzeige_name_mixed'),
                    new IntegerField('id', true),
                    new StringField('name', true),
                    new StringField('name_fr'),
                    new IntegerField('branche_id', true),
                    new StringField('beschreibung', true),
                    new StringField('beschreibung_fr'),
                    new StringField('alias_namen'),
                    new StringField('alias_namen_fr'),
                    new StringField('notizen'),
                    new StringField('eingabe_abgeschlossen_visa'),
                    new DateTimeField('eingabe_abgeschlossen_datum'),
                    new StringField('kontrolliert_visa'),
                    new DateTimeField('kontrolliert_datum'),
                    new StringField('freigabe_visa'),
                    new DateTimeField('freigabe_datum'),
                    new StringField('created_visa', true),
                    new DateTimeField('created_date', true),
                    new StringField('updated_visa'),
                    new DateTimeField('updated_date', true),
                    new StringField('name_de', true),
                    new StringField('beschreibung_de', true),
                    new StringField('alias_namen_de'),
                    new IntegerField('published', true),
                    new IntegerField('created_date_unix', true),
                    new IntegerField('updated_date_unix', true),
                    new IntegerField('eingabe_abgeschlossen_datum_unix'),
                    new IntegerField('kontrolliert_datum_unix'),
                    new IntegerField('freigabe_datum_unix')
                )
            );
            $lookupDataset->setOrderByField('anzeige_name', 'ASC');
            $editColumn = new LookUpEditColumn(
                'Beruf Interessengruppe', 
                'beruf_interessengruppe_id', 
                $editor, 
                $this->dataset, 'id', 'anzeige_name', $lookupDataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for partei_id field
            //
            $editor = new ComboBox('partei_id_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`v_partei`');
            $lookupDataset->addFields(
                array(
                    new StringField('anzeige_name'),
                    new StringField('anzeige_name_de'),
                    new StringField('anzeige_name_fr'),
                    new StringField('anzeige_name_mixed'),
                    new StringField('abkuerzung_mixed'),
                    new IntegerField('id', true),
                    new StringField('abkuerzung', true),
                    new StringField('abkuerzung_fr'),
                    new StringField('name'),
                    new StringField('name_fr'),
                    new IntegerField('fraktion_id'),
                    new DateField('gruendung'),
                    new StringField('position'),
                    new StringField('farbcode'),
                    new StringField('homepage'),
                    new StringField('homepage_fr'),
                    new StringField('email'),
                    new StringField('email_fr'),
                    new StringField('twitter_name'),
                    new StringField('twitter_name_fr'),
                    new StringField('beschreibung'),
                    new StringField('beschreibung_fr'),
                    new StringField('notizen'),
                    new StringField('eingabe_abgeschlossen_visa'),
                    new DateTimeField('eingabe_abgeschlossen_datum'),
                    new StringField('kontrolliert_visa'),
                    new DateTimeField('kontrolliert_datum'),
                    new StringField('freigabe_visa'),
                    new DateTimeField('freigabe_datum'),
                    new StringField('created_visa', true),
                    new DateTimeField('created_date', true),
                    new StringField('updated_visa'),
                    new DateTimeField('updated_date', true),
                    new StringField('name_de'),
                    new StringField('abkuerzung_de', true),
                    new StringField('beschreibung_de'),
                    new StringField('homepage_de'),
                    new StringField('twitter_name_de'),
                    new StringField('email_de'),
                    new IntegerField('published', true),
                    new IntegerField('created_date_unix', true),
                    new IntegerField('updated_date_unix', true),
                    new IntegerField('eingabe_abgeschlossen_datum_unix'),
                    new IntegerField('kontrolliert_datum_unix'),
                    new IntegerField('freigabe_datum_unix')
                )
            );
            $lookupDataset->setOrderByField('abkuerzung', 'ASC');
            $editColumn = new LookUpEditColumn(
                'Partei', 
                'partei_id', 
                $editor, 
                $this->dataset, 'id', 'abkuerzung', $lookupDataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for geschlecht field
            //
            $editor = new ComboBox('geschlecht_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $editor->addChoice('M', 'M');
            $editor->addChoice('F', 'F');
            $editColumn = new CustomEditColumn('Geschlecht', 'geschlecht', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for email field
            //
            $editor = new TextEdit('email_edit');
            $editColumn = new CustomEditColumn('Email', 'email', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for homepage field
            //
            $editor = new TextEdit('homepage_edit');
            $editColumn = new CustomEditColumn('Homepage', 'homepage', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for twitter_name field
            //
            $editor = new TextEdit('twitter_name_edit');
            $editColumn = new CustomEditColumn('Twitter Name', 'twitter_name', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for linkedin_profil_url field
            //
            $editor = new TextEdit('linkedin_profil_url_edit');
            $editColumn = new CustomEditColumn('Linkedin Profil Url', 'linkedin_profil_url', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for xing_profil_name field
            //
            $editor = new TextEdit('xing_profil_name_edit');
            $editColumn = new CustomEditColumn('Xing Profil Name', 'xing_profil_name', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for facebook_name field
            //
            $editor = new TextEdit('facebook_name_edit');
            $editColumn = new CustomEditColumn('Facebook Name', 'facebook_name', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for notizen field
            //
            $editor = new TextEdit('notizen_edit');
            $editColumn = new CustomEditColumn('Notizen', 'notizen', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for created_visa field
            //
            $editor = new TextEdit('created_visa_edit');
            $editColumn = new CustomEditColumn('Created Visa', 'created_visa', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for created_date field
            //
            $editor = new DateTimeEdit('created_date_edit', false, 'Y-m-d H:i:s');
            $editColumn = new CustomEditColumn('Created Date', 'created_date', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for updated_visa field
            //
            $editor = new TextEdit('updated_visa_edit');
            $editColumn = new CustomEditColumn('Updated Visa', 'updated_visa', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for updated_date field
            //
            $editor = new DateTimeEdit('updated_date_edit', false, 'Y-m-d H:i:s');
            $editColumn = new CustomEditColumn('Updated Date', 'updated_date', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for anzeige_name field
            //
            $editor = new TextEdit('anzeige_name_edit');
            $editColumn = new CustomEditColumn('Anzeige Name', 'anzeige_name', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for anzeige_name_de field
            //
            $editor = new TextEdit('anzeige_name_de_edit');
            $editColumn = new CustomEditColumn('Anzeige Name De', 'anzeige_name_de', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for anzeige_name_fr field
            //
            $editor = new TextEdit('anzeige_name_fr_edit');
            $editColumn = new CustomEditColumn('Anzeige Name Fr', 'anzeige_name_fr', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
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
            // Edit column for name_de field
            //
            $editor = new TextEdit('name_de_edit');
            $editColumn = new CustomEditColumn('Name De', 'name_de', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for name_fr field
            //
            $editor = new TextEdit('name_fr_edit');
            $editColumn = new CustomEditColumn('Name Fr', 'name_fr', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for beschreibung_de field
            //
            $editor = new TextEdit('beschreibung_de_edit');
            $editColumn = new CustomEditColumn('Beschreibung De', 'beschreibung_de', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for beschreibung_fr field
            //
            $editor = new TextEdit('beschreibung_fr_edit');
            $editColumn = new CustomEditColumn('Beschreibung Fr', 'beschreibung_fr', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for parlamentarier_kommissionen field
            //
            $editor = new TextEdit('parlamentarier_kommissionen_edit');
            $editColumn = new CustomEditColumn('Parlamentarier Kommissionen', 'parlamentarier_kommissionen', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for parlamentarier_kommissionen_zutrittsberechtigung field
            //
            $editor = new TextEdit('parlamentarier_kommissionen_zutrittsberechtigung_edit');
            $editColumn = new CustomEditColumn('Parlamentarier Kommissionen Zutrittsberechtigung', 'parlamentarier_kommissionen_zutrittsberechtigung', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for beruf_fr field
            //
            $editor = new TextEdit('beruf_fr_edit');
            $editColumn = new CustomEditColumn('Beruf Fr', 'beruf_fr', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for arbeitssprache field
            //
            $editor = new TextEdit('arbeitssprache_edit');
            $editColumn = new CustomEditColumn('Arbeitssprache', 'arbeitssprache', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for telephon_1 field
            //
            $editor = new TextEdit('telephon_1_edit');
            $editColumn = new CustomEditColumn('Telephon 1', 'telephon_1', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for telephon_2 field
            //
            $editor = new TextEdit('telephon_2_edit');
            $editColumn = new CustomEditColumn('Telephon 2', 'telephon_2', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for erfasst field
            //
            $editor = new TextEdit('erfasst_edit');
            $editColumn = new CustomEditColumn('Erfasst', 'erfasst', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for eingabe_abgeschlossen_visa_person field
            //
            $editor = new TextEdit('eingabe_abgeschlossen_visa_person_edit');
            $editColumn = new CustomEditColumn('Eingabe Abgeschlossen Visa Person', 'eingabe_abgeschlossen_visa_person', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for eingabe_abgeschlossen_datum_person field
            //
            $editor = new DateTimeEdit('eingabe_abgeschlossen_datum_person_edit', false, 'd.m.Y H:i:s');
            $editColumn = new CustomEditColumn('Eingabe Abgeschlossen Datum Person', 'eingabe_abgeschlossen_datum_person', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for kontrolliert_visa_person field
            //
            $editor = new TextEdit('kontrolliert_visa_person_edit');
            $editColumn = new CustomEditColumn('Kontrolliert Visa Person', 'kontrolliert_visa_person', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for kontrolliert_datum_person field
            //
            $editor = new DateTimeEdit('kontrolliert_datum_person_edit', false, 'd.m.Y H:i:s');
            $editColumn = new CustomEditColumn('Kontrolliert Datum Person', 'kontrolliert_datum_person', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for freigabe_visa_person field
            //
            $editor = new TextEdit('freigabe_visa_person_edit');
            $editColumn = new CustomEditColumn('Freigabe Visa Person', 'freigabe_visa_person', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for freigabe_datum_person field
            //
            $editor = new DateTimeEdit('freigabe_datum_person_edit', false, 'd.m.Y H:i:s');
            $editColumn = new CustomEditColumn('Freigabe Datum Person', 'freigabe_datum_person', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for created_visa_person field
            //
            $editor = new TextEdit('created_visa_person_edit');
            $editColumn = new CustomEditColumn('Created Visa Person', 'created_visa_person', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for created_date_person field
            //
            $editor = new DateTimeEdit('created_date_person_edit', false, 'd.m.Y H:i:s');
            $editColumn = new CustomEditColumn('Created Date Person', 'created_date_person', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for updated_visa_person field
            //
            $editor = new TextEdit('updated_visa_person_edit');
            $editColumn = new CustomEditColumn('Updated Visa Person', 'updated_visa_person', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for updated_date_person field
            //
            $editor = new DateTimeEdit('updated_date_person_edit', false, 'd.m.Y H:i:s');
            $editColumn = new CustomEditColumn('Updated Date Person', 'updated_date_person', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for created_date_unix_person field
            //
            $editor = new TextEdit('created_date_unix_person_edit');
            $editColumn = new CustomEditColumn('Created Date Unix Person', 'created_date_unix_person', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for updated_date_unix_person field
            //
            $editor = new TextEdit('updated_date_unix_person_edit');
            $editColumn = new CustomEditColumn('Updated Date Unix Person', 'updated_date_unix_person', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for eingabe_abgeschlossen_datum_unix_person field
            //
            $editor = new TextEdit('eingabe_abgeschlossen_datum_unix_person_edit');
            $editColumn = new CustomEditColumn('Eingabe Abgeschlossen Datum Unix Person', 'eingabe_abgeschlossen_datum_unix_person', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for kontrolliert_datum_unix_person field
            //
            $editor = new TextEdit('kontrolliert_datum_unix_person_edit');
            $editColumn = new CustomEditColumn('Kontrolliert Datum Unix Person', 'kontrolliert_datum_unix_person', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for freigabe_datum_unix_person field
            //
            $editor = new TextEdit('freigabe_datum_unix_person_edit');
            $editColumn = new CustomEditColumn('Freigabe Datum Unix Person', 'freigabe_datum_unix_person', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for person_id field
            //
            $editor = new SpinEdit('person_id_edit');
            $editColumn = new CustomEditColumn('Person Id', 'person_id', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for zutrittsberechtigung_id field
            //
            $editor = new SpinEdit('zutrittsberechtigung_id_edit');
            $editColumn = new CustomEditColumn('Zutrittsberechtigung Id', 'zutrittsberechtigung_id', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for funktion_fr field
            //
            $editor = new TextEdit('funktion_fr_edit');
            $editColumn = new CustomEditColumn('Funktion Fr', 'funktion_fr', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for bis_unix field
            //
            $editor = new TextEdit('bis_unix_edit');
            $editColumn = new CustomEditColumn('Bis Unix', 'bis_unix', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for von_unix field
            //
            $editor = new TextEdit('von_unix_edit');
            $editColumn = new CustomEditColumn('Von Unix', 'von_unix', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for created_date_unix field
            //
            $editor = new TextEdit('created_date_unix_edit');
            $editColumn = new CustomEditColumn('Created Date Unix', 'created_date_unix', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for updated_date_unix field
            //
            $editor = new TextEdit('updated_date_unix_edit');
            $editColumn = new CustomEditColumn('Updated Date Unix', 'updated_date_unix', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for eingabe_abgeschlossen_datum_unix field
            //
            $editor = new TextEdit('eingabe_abgeschlossen_datum_unix_edit');
            $editColumn = new CustomEditColumn('Eingabe Abgeschlossen Datum Unix', 'eingabe_abgeschlossen_datum_unix', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for kontrolliert_datum_unix field
            //
            $editor = new TextEdit('kontrolliert_datum_unix_edit');
            $editColumn = new CustomEditColumn('Kontrolliert Datum Unix', 'kontrolliert_datum_unix', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for freigabe_datum_unix field
            //
            $editor = new TextEdit('freigabe_datum_unix_edit');
            $editColumn = new CustomEditColumn('Freigabe Datum Unix', 'freigabe_datum_unix', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for published_person field
            //
            $editor = new TextEdit('published_person_edit');
            $editColumn = new CustomEditColumn('Published Person', 'published_person', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for published field
            //
            $editor = new TextEdit('published_edit');
            $editColumn = new CustomEditColumn('Published', 'published', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
        }
    
        protected function AddMultiEditColumns(Grid $grid)
        {
            //
            // Edit column for parlamentarier_id field
            //
            $editor = new ComboBox('parlamentarier_id_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`v_parlamentarier_simple`');
            $lookupDataset->addFields(
                array(
                    new StringField('anzeige_name'),
                    new StringField('anzeige_name_de'),
                    new StringField('anzeige_name_fr'),
                    new StringField('name'),
                    new StringField('name_de'),
                    new StringField('name_fr'),
                    new IntegerField('id', true),
                    new StringField('nachname', true),
                    new StringField('vorname', true),
                    new StringField('vorname_kurz'),
                    new StringField('zweiter_vorname'),
                    new IntegerField('rat_id', true),
                    new IntegerField('kanton_id', true),
                    new StringField('kommissionen'),
                    new IntegerField('partei_id'),
                    new StringField('parteifunktion', true),
                    new IntegerField('fraktion_id'),
                    new StringField('fraktionsfunktion'),
                    new DateField('im_rat_seit', true),
                    new DateField('im_rat_bis'),
                    new DateField('ratswechsel'),
                    new DateField('ratsunterbruch_von'),
                    new DateField('ratsunterbruch_bis'),
                    new StringField('beruf'),
                    new StringField('beruf_fr'),
                    new IntegerField('beruf_interessengruppe_id'),
                    new StringField('titel'),
                    new StringField('aemter'),
                    new StringField('weitere_aemter'),
                    new StringField('zivilstand'),
                    new IntegerField('anzahl_kinder'),
                    new IntegerField('militaerischer_grad_id'),
                    new StringField('geschlecht'),
                    new DateField('geburtstag'),
                    new StringField('photo'),
                    new StringField('photo_dateiname'),
                    new StringField('photo_dateierweiterung'),
                    new StringField('photo_dateiname_voll'),
                    new StringField('photo_mime_type'),
                    new StringField('kleinbild'),
                    new IntegerField('sitzplatz'),
                    new StringField('email'),
                    new StringField('homepage'),
                    new StringField('homepage_2'),
                    new IntegerField('parlament_biografie_id'),
                    new IntegerField('parlament_number'),
                    new StringField('parlament_interessenbindungen'),
                    new StringField('parlament_interessenbindungen_json'),
                    new DateTimeField('parlament_interessenbindungen_updated'),
                    new StringField('twitter_name'),
                    new StringField('linkedin_profil_url'),
                    new StringField('xing_profil_name'),
                    new StringField('facebook_name'),
                    new StringField('wikipedia'),
                    new StringField('sprache'),
                    new StringField('arbeitssprache'),
                    new StringField('adresse_firma'),
                    new StringField('adresse_strasse'),
                    new StringField('adresse_zusatz'),
                    new StringField('adresse_plz'),
                    new StringField('adresse_ort'),
                    new StringField('telephon_1'),
                    new StringField('telephon_2'),
                    new StringField('erfasst'),
                    new StringField('notizen'),
                    new StringField('eingabe_abgeschlossen_visa'),
                    new DateTimeField('eingabe_abgeschlossen_datum'),
                    new StringField('kontrolliert_visa'),
                    new DateTimeField('kontrolliert_datum'),
                    new StringField('autorisierung_verschickt_visa'),
                    new DateTimeField('autorisierung_verschickt_datum'),
                    new StringField('autorisiert_visa'),
                    new DateField('autorisiert_datum'),
                    new StringField('freigabe_visa'),
                    new DateTimeField('freigabe_datum'),
                    new StringField('created_visa', true),
                    new DateTimeField('created_date', true),
                    new StringField('updated_visa'),
                    new DateTimeField('updated_date', true),
                    new StringField('beruf_de'),
                    new DateField('von', true),
                    new DateField('bis'),
                    new IntegerField('aktiv'),
                    new IntegerField('published', true),
                    new IntegerField('geburtstag_unix'),
                    new IntegerField('im_rat_seit_unix', true),
                    new IntegerField('im_rat_bis_unix'),
                    new IntegerField('created_date_unix', true),
                    new IntegerField('updated_date_unix', true),
                    new IntegerField('eingabe_abgeschlossen_datum_unix'),
                    new IntegerField('kontrolliert_datum_unix'),
                    new IntegerField('freigabe_datum_unix'),
                    new IntegerField('von_unix', true),
                    new IntegerField('bis_unix')
                )
            );
            $lookupDataset->setOrderByField('anzeige_name', 'ASC');
            $editColumn = new LookUpEditColumn(
                'Parlamentarier', 
                'parlamentarier_id', 
                $editor, 
                $this->dataset, 'id', 'anzeige_name', $lookupDataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for kommissionen field
            //
            $editor = new TextEdit('kommissionen_edit');
            $editColumn = new CustomEditColumn('Kommissionen', 'kommissionen', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for nachname field
            //
            $editor = new TextEdit('nachname_edit');
            $editColumn = new CustomEditColumn('Nachname', 'nachname', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for vorname field
            //
            $editor = new TextEdit('vorname_edit');
            $editColumn = new CustomEditColumn('Vorname', 'vorname', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for zweiter_vorname field
            //
            $editor = new TextEdit('zweiter_vorname_edit');
            $editColumn = new CustomEditColumn('Zweiter Vorname', 'zweiter_vorname', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for funktion field
            //
            $editor = new TextEdit('funktion_edit');
            $editColumn = new CustomEditColumn('Funktion', 'funktion', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for beruf field
            //
            $editor = new TextEdit('beruf_edit');
            $editColumn = new CustomEditColumn('Beruf', 'beruf', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for beruf_interessengruppe_id field
            //
            $editor = new ComboBox('beruf_interessengruppe_id_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`v_interessengruppe_simple`');
            $lookupDataset->addFields(
                array(
                    new StringField('anzeige_name'),
                    new StringField('anzeige_name_de'),
                    new StringField('anzeige_name_fr'),
                    new StringField('anzeige_name_mixed'),
                    new IntegerField('id', true),
                    new StringField('name', true),
                    new StringField('name_fr'),
                    new IntegerField('branche_id', true),
                    new StringField('beschreibung', true),
                    new StringField('beschreibung_fr'),
                    new StringField('alias_namen'),
                    new StringField('alias_namen_fr'),
                    new StringField('notizen'),
                    new StringField('eingabe_abgeschlossen_visa'),
                    new DateTimeField('eingabe_abgeschlossen_datum'),
                    new StringField('kontrolliert_visa'),
                    new DateTimeField('kontrolliert_datum'),
                    new StringField('freigabe_visa'),
                    new DateTimeField('freigabe_datum'),
                    new StringField('created_visa', true),
                    new DateTimeField('created_date', true),
                    new StringField('updated_visa'),
                    new DateTimeField('updated_date', true),
                    new StringField('name_de', true),
                    new StringField('beschreibung_de', true),
                    new StringField('alias_namen_de'),
                    new IntegerField('published', true),
                    new IntegerField('created_date_unix', true),
                    new IntegerField('updated_date_unix', true),
                    new IntegerField('eingabe_abgeschlossen_datum_unix'),
                    new IntegerField('kontrolliert_datum_unix'),
                    new IntegerField('freigabe_datum_unix')
                )
            );
            $lookupDataset->setOrderByField('anzeige_name', 'ASC');
            $editColumn = new LookUpEditColumn(
                'Beruf Interessengruppe', 
                'beruf_interessengruppe_id', 
                $editor, 
                $this->dataset, 'id', 'anzeige_name', $lookupDataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for partei_id field
            //
            $editor = new ComboBox('partei_id_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`v_partei`');
            $lookupDataset->addFields(
                array(
                    new StringField('anzeige_name'),
                    new StringField('anzeige_name_de'),
                    new StringField('anzeige_name_fr'),
                    new StringField('anzeige_name_mixed'),
                    new StringField('abkuerzung_mixed'),
                    new IntegerField('id', true),
                    new StringField('abkuerzung', true),
                    new StringField('abkuerzung_fr'),
                    new StringField('name'),
                    new StringField('name_fr'),
                    new IntegerField('fraktion_id'),
                    new DateField('gruendung'),
                    new StringField('position'),
                    new StringField('farbcode'),
                    new StringField('homepage'),
                    new StringField('homepage_fr'),
                    new StringField('email'),
                    new StringField('email_fr'),
                    new StringField('twitter_name'),
                    new StringField('twitter_name_fr'),
                    new StringField('beschreibung'),
                    new StringField('beschreibung_fr'),
                    new StringField('notizen'),
                    new StringField('eingabe_abgeschlossen_visa'),
                    new DateTimeField('eingabe_abgeschlossen_datum'),
                    new StringField('kontrolliert_visa'),
                    new DateTimeField('kontrolliert_datum'),
                    new StringField('freigabe_visa'),
                    new DateTimeField('freigabe_datum'),
                    new StringField('created_visa', true),
                    new DateTimeField('created_date', true),
                    new StringField('updated_visa'),
                    new DateTimeField('updated_date', true),
                    new StringField('name_de'),
                    new StringField('abkuerzung_de', true),
                    new StringField('beschreibung_de'),
                    new StringField('homepage_de'),
                    new StringField('twitter_name_de'),
                    new StringField('email_de'),
                    new IntegerField('published', true),
                    new IntegerField('created_date_unix', true),
                    new IntegerField('updated_date_unix', true),
                    new IntegerField('eingabe_abgeschlossen_datum_unix'),
                    new IntegerField('kontrolliert_datum_unix'),
                    new IntegerField('freigabe_datum_unix')
                )
            );
            $lookupDataset->setOrderByField('abkuerzung', 'ASC');
            $editColumn = new LookUpEditColumn(
                'Partei', 
                'partei_id', 
                $editor, 
                $this->dataset, 'id', 'abkuerzung', $lookupDataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for geschlecht field
            //
            $editor = new ComboBox('geschlecht_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $editor->addChoice('M', 'M');
            $editor->addChoice('F', 'F');
            $editColumn = new CustomEditColumn('Geschlecht', 'geschlecht', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for email field
            //
            $editor = new TextEdit('email_edit');
            $editColumn = new CustomEditColumn('Email', 'email', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for homepage field
            //
            $editor = new TextEdit('homepage_edit');
            $editColumn = new CustomEditColumn('Homepage', 'homepage', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for twitter_name field
            //
            $editor = new TextEdit('twitter_name_edit');
            $editColumn = new CustomEditColumn('Twitter Name', 'twitter_name', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for linkedin_profil_url field
            //
            $editor = new TextEdit('linkedin_profil_url_edit');
            $editColumn = new CustomEditColumn('Linkedin Profil Url', 'linkedin_profil_url', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for xing_profil_name field
            //
            $editor = new TextEdit('xing_profil_name_edit');
            $editColumn = new CustomEditColumn('Xing Profil Name', 'xing_profil_name', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for facebook_name field
            //
            $editor = new TextEdit('facebook_name_edit');
            $editColumn = new CustomEditColumn('Facebook Name', 'facebook_name', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for notizen field
            //
            $editor = new TextEdit('notizen_edit');
            $editColumn = new CustomEditColumn('Notizen', 'notizen', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for created_visa field
            //
            $editor = new TextEdit('created_visa_edit');
            $editColumn = new CustomEditColumn('Created Visa', 'created_visa', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for created_date field
            //
            $editor = new DateTimeEdit('created_date_edit', false, 'Y-m-d H:i:s');
            $editColumn = new CustomEditColumn('Created Date', 'created_date', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for updated_visa field
            //
            $editor = new TextEdit('updated_visa_edit');
            $editColumn = new CustomEditColumn('Updated Visa', 'updated_visa', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for updated_date field
            //
            $editor = new DateTimeEdit('updated_date_edit', false, 'Y-m-d H:i:s');
            $editColumn = new CustomEditColumn('Updated Date', 'updated_date', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for von field
            //
            $editor = new DateTimeEdit('von_edit', false, 'Y-m-d H:i:s');
            $editColumn = new CustomEditColumn('Von', 'von', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for bis field
            //
            $editor = new DateTimeEdit('bis_edit', false, 'Y-m-d H:i:s');
            $editColumn = new CustomEditColumn('Bis', 'bis', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for eingabe_abgeschlossen_visa field
            //
            $editor = new TextEdit('eingabe_abgeschlossen_visa_edit');
            $editColumn = new CustomEditColumn('Eingabe Abgeschlossen Visa', 'eingabe_abgeschlossen_visa', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for eingabe_abgeschlossen_datum field
            //
            $editor = new DateTimeEdit('eingabe_abgeschlossen_datum_edit', false, 'Y-m-d H:i:s');
            $editColumn = new CustomEditColumn('Eingabe Abgeschlossen Datum', 'eingabe_abgeschlossen_datum', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for kontrolliert_visa field
            //
            $editor = new TextEdit('kontrolliert_visa_edit');
            $editColumn = new CustomEditColumn('Kontrolliert Visa', 'kontrolliert_visa', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for kontrolliert_datum field
            //
            $editor = new DateTimeEdit('kontrolliert_datum_edit', false, 'Y-m-d H:i:s');
            $editColumn = new CustomEditColumn('Kontrolliert Datum', 'kontrolliert_datum', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for autorisierung_verschickt_visa field
            //
            $editor = new TextEdit('autorisierung_verschickt_visa_edit');
            $editColumn = new CustomEditColumn('Autorisierung Verschickt Visa', 'autorisierung_verschickt_visa', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for autorisierung_verschickt_datum field
            //
            $editor = new DateTimeEdit('autorisierung_verschickt_datum_edit', false, 'Y-m-d H:i:s');
            $editColumn = new CustomEditColumn('Autorisierung Verschickt Datum', 'autorisierung_verschickt_datum', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for autorisiert_visa field
            //
            $editor = new TextEdit('autorisiert_visa_edit');
            $editColumn = new CustomEditColumn('Autorisiert Visa', 'autorisiert_visa', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for autorisiert_datum field
            //
            $editor = new DateTimeEdit('autorisiert_datum_edit', false, 'Y-m-d H:i:s');
            $editColumn = new CustomEditColumn('Autorisiert Datum', 'autorisiert_datum', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for freigabe_visa field
            //
            $editor = new TextEdit('freigabe_visa_edit');
            $editColumn = new CustomEditColumn('Freigabe Visa', 'freigabe_visa', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for freigabe_datum field
            //
            $editor = new DateTimeEdit('freigabe_datum_edit', false, 'Y-m-d H:i:s');
            $editColumn = new CustomEditColumn('Freigabe Datum', 'freigabe_datum', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for anzeige_name field
            //
            $editor = new TextEdit('anzeige_name_edit');
            $editColumn = new CustomEditColumn('Anzeige Name', 'anzeige_name', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for anzeige_name_de field
            //
            $editor = new TextEdit('anzeige_name_de_edit');
            $editColumn = new CustomEditColumn('Anzeige Name De', 'anzeige_name_de', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for anzeige_name_fr field
            //
            $editor = new TextEdit('anzeige_name_fr_edit');
            $editColumn = new CustomEditColumn('Anzeige Name Fr', 'anzeige_name_fr', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
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
            // Edit column for name_de field
            //
            $editor = new TextEdit('name_de_edit');
            $editColumn = new CustomEditColumn('Name De', 'name_de', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for name_fr field
            //
            $editor = new TextEdit('name_fr_edit');
            $editColumn = new CustomEditColumn('Name Fr', 'name_fr', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for beschreibung_de field
            //
            $editor = new TextEdit('beschreibung_de_edit');
            $editColumn = new CustomEditColumn('Beschreibung De', 'beschreibung_de', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for beschreibung_fr field
            //
            $editor = new TextEdit('beschreibung_fr_edit');
            $editColumn = new CustomEditColumn('Beschreibung Fr', 'beschreibung_fr', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for parlamentarier_kommissionen field
            //
            $editor = new TextEdit('parlamentarier_kommissionen_edit');
            $editColumn = new CustomEditColumn('Parlamentarier Kommissionen', 'parlamentarier_kommissionen', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for parlamentarier_kommissionen_zutrittsberechtigung field
            //
            $editor = new TextEdit('parlamentarier_kommissionen_zutrittsberechtigung_edit');
            $editColumn = new CustomEditColumn('Parlamentarier Kommissionen Zutrittsberechtigung', 'parlamentarier_kommissionen_zutrittsberechtigung', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for beruf_fr field
            //
            $editor = new TextEdit('beruf_fr_edit');
            $editColumn = new CustomEditColumn('Beruf Fr', 'beruf_fr', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for arbeitssprache field
            //
            $editor = new TextEdit('arbeitssprache_edit');
            $editColumn = new CustomEditColumn('Arbeitssprache', 'arbeitssprache', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for telephon_1 field
            //
            $editor = new TextEdit('telephon_1_edit');
            $editColumn = new CustomEditColumn('Telephon 1', 'telephon_1', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for telephon_2 field
            //
            $editor = new TextEdit('telephon_2_edit');
            $editColumn = new CustomEditColumn('Telephon 2', 'telephon_2', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for erfasst field
            //
            $editor = new TextEdit('erfasst_edit');
            $editColumn = new CustomEditColumn('Erfasst', 'erfasst', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for eingabe_abgeschlossen_visa_person field
            //
            $editor = new TextEdit('eingabe_abgeschlossen_visa_person_edit');
            $editColumn = new CustomEditColumn('Eingabe Abgeschlossen Visa Person', 'eingabe_abgeschlossen_visa_person', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for eingabe_abgeschlossen_datum_person field
            //
            $editor = new DateTimeEdit('eingabe_abgeschlossen_datum_person_edit', false, 'd.m.Y H:i:s');
            $editColumn = new CustomEditColumn('Eingabe Abgeschlossen Datum Person', 'eingabe_abgeschlossen_datum_person', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for kontrolliert_visa_person field
            //
            $editor = new TextEdit('kontrolliert_visa_person_edit');
            $editColumn = new CustomEditColumn('Kontrolliert Visa Person', 'kontrolliert_visa_person', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for kontrolliert_datum_person field
            //
            $editor = new DateTimeEdit('kontrolliert_datum_person_edit', false, 'd.m.Y H:i:s');
            $editColumn = new CustomEditColumn('Kontrolliert Datum Person', 'kontrolliert_datum_person', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for freigabe_visa_person field
            //
            $editor = new TextEdit('freigabe_visa_person_edit');
            $editColumn = new CustomEditColumn('Freigabe Visa Person', 'freigabe_visa_person', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for freigabe_datum_person field
            //
            $editor = new DateTimeEdit('freigabe_datum_person_edit', false, 'd.m.Y H:i:s');
            $editColumn = new CustomEditColumn('Freigabe Datum Person', 'freigabe_datum_person', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for created_visa_person field
            //
            $editor = new TextEdit('created_visa_person_edit');
            $editColumn = new CustomEditColumn('Created Visa Person', 'created_visa_person', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for created_date_person field
            //
            $editor = new DateTimeEdit('created_date_person_edit', false, 'd.m.Y H:i:s');
            $editColumn = new CustomEditColumn('Created Date Person', 'created_date_person', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for updated_visa_person field
            //
            $editor = new TextEdit('updated_visa_person_edit');
            $editColumn = new CustomEditColumn('Updated Visa Person', 'updated_visa_person', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for updated_date_person field
            //
            $editor = new DateTimeEdit('updated_date_person_edit', false, 'd.m.Y H:i:s');
            $editColumn = new CustomEditColumn('Updated Date Person', 'updated_date_person', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for created_date_unix_person field
            //
            $editor = new TextEdit('created_date_unix_person_edit');
            $editColumn = new CustomEditColumn('Created Date Unix Person', 'created_date_unix_person', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for updated_date_unix_person field
            //
            $editor = new TextEdit('updated_date_unix_person_edit');
            $editColumn = new CustomEditColumn('Updated Date Unix Person', 'updated_date_unix_person', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for eingabe_abgeschlossen_datum_unix_person field
            //
            $editor = new TextEdit('eingabe_abgeschlossen_datum_unix_person_edit');
            $editColumn = new CustomEditColumn('Eingabe Abgeschlossen Datum Unix Person', 'eingabe_abgeschlossen_datum_unix_person', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for kontrolliert_datum_unix_person field
            //
            $editor = new TextEdit('kontrolliert_datum_unix_person_edit');
            $editColumn = new CustomEditColumn('Kontrolliert Datum Unix Person', 'kontrolliert_datum_unix_person', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for freigabe_datum_unix_person field
            //
            $editor = new TextEdit('freigabe_datum_unix_person_edit');
            $editColumn = new CustomEditColumn('Freigabe Datum Unix Person', 'freigabe_datum_unix_person', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for person_id field
            //
            $editor = new SpinEdit('person_id_edit');
            $editColumn = new CustomEditColumn('Person Id', 'person_id', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for zutrittsberechtigung_id field
            //
            $editor = new SpinEdit('zutrittsberechtigung_id_edit');
            $editColumn = new CustomEditColumn('Zutrittsberechtigung Id', 'zutrittsberechtigung_id', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for funktion_fr field
            //
            $editor = new TextEdit('funktion_fr_edit');
            $editColumn = new CustomEditColumn('Funktion Fr', 'funktion_fr', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for bis_unix field
            //
            $editor = new TextEdit('bis_unix_edit');
            $editColumn = new CustomEditColumn('Bis Unix', 'bis_unix', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for von_unix field
            //
            $editor = new TextEdit('von_unix_edit');
            $editColumn = new CustomEditColumn('Von Unix', 'von_unix', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for created_date_unix field
            //
            $editor = new TextEdit('created_date_unix_edit');
            $editColumn = new CustomEditColumn('Created Date Unix', 'created_date_unix', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for updated_date_unix field
            //
            $editor = new TextEdit('updated_date_unix_edit');
            $editColumn = new CustomEditColumn('Updated Date Unix', 'updated_date_unix', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for eingabe_abgeschlossen_datum_unix field
            //
            $editor = new TextEdit('eingabe_abgeschlossen_datum_unix_edit');
            $editColumn = new CustomEditColumn('Eingabe Abgeschlossen Datum Unix', 'eingabe_abgeschlossen_datum_unix', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for kontrolliert_datum_unix field
            //
            $editor = new TextEdit('kontrolliert_datum_unix_edit');
            $editColumn = new CustomEditColumn('Kontrolliert Datum Unix', 'kontrolliert_datum_unix', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for freigabe_datum_unix field
            //
            $editor = new TextEdit('freigabe_datum_unix_edit');
            $editColumn = new CustomEditColumn('Freigabe Datum Unix', 'freigabe_datum_unix', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for published_person field
            //
            $editor = new TextEdit('published_person_edit');
            $editColumn = new CustomEditColumn('Published Person', 'published_person', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for published field
            //
            $editor = new TextEdit('published_edit');
            $editColumn = new CustomEditColumn('Published', 'published', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
        }
    
        protected function AddInsertColumns(Grid $grid)
        {
            //
            // Edit column for kommissionen field
            //
            $editor = new TextEdit('kommissionen_edit');
            $editColumn = new CustomEditColumn('Kommissionen', 'kommissionen', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for nachname field
            //
            $editor = new TextEdit('nachname_edit');
            $editColumn = new CustomEditColumn('Nachname', 'nachname', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for vorname field
            //
            $editor = new TextEdit('vorname_edit');
            $editColumn = new CustomEditColumn('Vorname', 'vorname', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for zweiter_vorname field
            //
            $editor = new TextEdit('zweiter_vorname_edit');
            $editColumn = new CustomEditColumn('Zweiter Vorname', 'zweiter_vorname', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for funktion field
            //
            $editor = new TextEdit('funktion_edit');
            $editColumn = new CustomEditColumn('Funktion', 'funktion', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for beruf field
            //
            $editor = new TextEdit('beruf_edit');
            $editColumn = new CustomEditColumn('Beruf', 'beruf', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for beruf_interessengruppe_id field
            //
            $editor = new ComboBox('beruf_interessengruppe_id_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`v_interessengruppe_simple`');
            $lookupDataset->addFields(
                array(
                    new StringField('anzeige_name'),
                    new StringField('anzeige_name_de'),
                    new StringField('anzeige_name_fr'),
                    new StringField('anzeige_name_mixed'),
                    new IntegerField('id', true),
                    new StringField('name', true),
                    new StringField('name_fr'),
                    new IntegerField('branche_id', true),
                    new StringField('beschreibung', true),
                    new StringField('beschreibung_fr'),
                    new StringField('alias_namen'),
                    new StringField('alias_namen_fr'),
                    new StringField('notizen'),
                    new StringField('eingabe_abgeschlossen_visa'),
                    new DateTimeField('eingabe_abgeschlossen_datum'),
                    new StringField('kontrolliert_visa'),
                    new DateTimeField('kontrolliert_datum'),
                    new StringField('freigabe_visa'),
                    new DateTimeField('freigabe_datum'),
                    new StringField('created_visa', true),
                    new DateTimeField('created_date', true),
                    new StringField('updated_visa'),
                    new DateTimeField('updated_date', true),
                    new StringField('name_de', true),
                    new StringField('beschreibung_de', true),
                    new StringField('alias_namen_de'),
                    new IntegerField('published', true),
                    new IntegerField('created_date_unix', true),
                    new IntegerField('updated_date_unix', true),
                    new IntegerField('eingabe_abgeschlossen_datum_unix'),
                    new IntegerField('kontrolliert_datum_unix'),
                    new IntegerField('freigabe_datum_unix')
                )
            );
            $lookupDataset->setOrderByField('anzeige_name', 'ASC');
            $editColumn = new LookUpEditColumn(
                'Beruf Interessengruppe', 
                'beruf_interessengruppe_id', 
                $editor, 
                $this->dataset, 'id', 'anzeige_name', $lookupDataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for partei_id field
            //
            $editor = new ComboBox('partei_id_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`v_partei`');
            $lookupDataset->addFields(
                array(
                    new StringField('anzeige_name'),
                    new StringField('anzeige_name_de'),
                    new StringField('anzeige_name_fr'),
                    new StringField('anzeige_name_mixed'),
                    new StringField('abkuerzung_mixed'),
                    new IntegerField('id', true),
                    new StringField('abkuerzung', true),
                    new StringField('abkuerzung_fr'),
                    new StringField('name'),
                    new StringField('name_fr'),
                    new IntegerField('fraktion_id'),
                    new DateField('gruendung'),
                    new StringField('position'),
                    new StringField('farbcode'),
                    new StringField('homepage'),
                    new StringField('homepage_fr'),
                    new StringField('email'),
                    new StringField('email_fr'),
                    new StringField('twitter_name'),
                    new StringField('twitter_name_fr'),
                    new StringField('beschreibung'),
                    new StringField('beschreibung_fr'),
                    new StringField('notizen'),
                    new StringField('eingabe_abgeschlossen_visa'),
                    new DateTimeField('eingabe_abgeschlossen_datum'),
                    new StringField('kontrolliert_visa'),
                    new DateTimeField('kontrolliert_datum'),
                    new StringField('freigabe_visa'),
                    new DateTimeField('freigabe_datum'),
                    new StringField('created_visa', true),
                    new DateTimeField('created_date', true),
                    new StringField('updated_visa'),
                    new DateTimeField('updated_date', true),
                    new StringField('name_de'),
                    new StringField('abkuerzung_de', true),
                    new StringField('beschreibung_de'),
                    new StringField('homepage_de'),
                    new StringField('twitter_name_de'),
                    new StringField('email_de'),
                    new IntegerField('published', true),
                    new IntegerField('created_date_unix', true),
                    new IntegerField('updated_date_unix', true),
                    new IntegerField('eingabe_abgeschlossen_datum_unix'),
                    new IntegerField('kontrolliert_datum_unix'),
                    new IntegerField('freigabe_datum_unix')
                )
            );
            $lookupDataset->setOrderByField('abkuerzung', 'ASC');
            $editColumn = new LookUpEditColumn(
                'Partei', 
                'partei_id', 
                $editor, 
                $this->dataset, 'id', 'abkuerzung', $lookupDataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for geschlecht field
            //
            $editor = new ComboBox('geschlecht_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $editor->addChoice('M', 'M');
            $editor->addChoice('F', 'F');
            $editColumn = new CustomEditColumn('Geschlecht', 'geschlecht', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for email field
            //
            $editor = new TextEdit('email_edit');
            $editColumn = new CustomEditColumn('Email', 'email', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for homepage field
            //
            $editor = new TextEdit('homepage_edit');
            $editColumn = new CustomEditColumn('Homepage', 'homepage', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for twitter_name field
            //
            $editor = new TextEdit('twitter_name_edit');
            $editColumn = new CustomEditColumn('Twitter Name', 'twitter_name', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for linkedin_profil_url field
            //
            $editor = new TextEdit('linkedin_profil_url_edit');
            $editColumn = new CustomEditColumn('Linkedin Profil Url', 'linkedin_profil_url', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for xing_profil_name field
            //
            $editor = new TextEdit('xing_profil_name_edit');
            $editColumn = new CustomEditColumn('Xing Profil Name', 'xing_profil_name', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for facebook_name field
            //
            $editor = new TextEdit('facebook_name_edit');
            $editColumn = new CustomEditColumn('Facebook Name', 'facebook_name', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for notizen field
            //
            $editor = new TextEdit('notizen_edit');
            $editColumn = new CustomEditColumn('Notizen', 'notizen', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for created_visa field
            //
            $editor = new TextEdit('created_visa_edit');
            $editColumn = new CustomEditColumn('Created Visa', 'created_visa', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for created_date field
            //
            $editor = new DateTimeEdit('created_date_edit', false, 'Y-m-d H:i:s');
            $editColumn = new CustomEditColumn('Created Date', 'created_date', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for updated_visa field
            //
            $editor = new TextEdit('updated_visa_edit');
            $editColumn = new CustomEditColumn('Updated Visa', 'updated_visa', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for updated_date field
            //
            $editor = new DateTimeEdit('updated_date_edit', false, 'Y-m-d H:i:s');
            $editColumn = new CustomEditColumn('Updated Date', 'updated_date', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for anzeige_name field
            //
            $editor = new TextEdit('anzeige_name_edit');
            $editColumn = new CustomEditColumn('Anzeige Name', 'anzeige_name', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for anzeige_name_de field
            //
            $editor = new TextEdit('anzeige_name_de_edit');
            $editColumn = new CustomEditColumn('Anzeige Name De', 'anzeige_name_de', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for anzeige_name_fr field
            //
            $editor = new TextEdit('anzeige_name_fr_edit');
            $editColumn = new CustomEditColumn('Anzeige Name Fr', 'anzeige_name_fr', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
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
            // Edit column for name_de field
            //
            $editor = new TextEdit('name_de_edit');
            $editColumn = new CustomEditColumn('Name De', 'name_de', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for name_fr field
            //
            $editor = new TextEdit('name_fr_edit');
            $editColumn = new CustomEditColumn('Name Fr', 'name_fr', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for beschreibung_de field
            //
            $editor = new TextEdit('beschreibung_de_edit');
            $editColumn = new CustomEditColumn('Beschreibung De', 'beschreibung_de', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for beschreibung_fr field
            //
            $editor = new TextEdit('beschreibung_fr_edit');
            $editColumn = new CustomEditColumn('Beschreibung Fr', 'beschreibung_fr', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for parlamentarier_kommissionen field
            //
            $editor = new TextEdit('parlamentarier_kommissionen_edit');
            $editColumn = new CustomEditColumn('Parlamentarier Kommissionen', 'parlamentarier_kommissionen', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for parlamentarier_kommissionen_zutrittsberechtigung field
            //
            $editor = new TextEdit('parlamentarier_kommissionen_zutrittsberechtigung_edit');
            $editColumn = new CustomEditColumn('Parlamentarier Kommissionen Zutrittsberechtigung', 'parlamentarier_kommissionen_zutrittsberechtigung', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for beruf_fr field
            //
            $editor = new TextEdit('beruf_fr_edit');
            $editColumn = new CustomEditColumn('Beruf Fr', 'beruf_fr', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for arbeitssprache field
            //
            $editor = new TextEdit('arbeitssprache_edit');
            $editColumn = new CustomEditColumn('Arbeitssprache', 'arbeitssprache', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for telephon_1 field
            //
            $editor = new TextEdit('telephon_1_edit');
            $editColumn = new CustomEditColumn('Telephon 1', 'telephon_1', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for telephon_2 field
            //
            $editor = new TextEdit('telephon_2_edit');
            $editColumn = new CustomEditColumn('Telephon 2', 'telephon_2', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for erfasst field
            //
            $editor = new TextEdit('erfasst_edit');
            $editColumn = new CustomEditColumn('Erfasst', 'erfasst', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for eingabe_abgeschlossen_visa_person field
            //
            $editor = new TextEdit('eingabe_abgeschlossen_visa_person_edit');
            $editColumn = new CustomEditColumn('Eingabe Abgeschlossen Visa Person', 'eingabe_abgeschlossen_visa_person', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for eingabe_abgeschlossen_datum_person field
            //
            $editor = new DateTimeEdit('eingabe_abgeschlossen_datum_person_edit', false, 'd.m.Y H:i:s');
            $editColumn = new CustomEditColumn('Eingabe Abgeschlossen Datum Person', 'eingabe_abgeschlossen_datum_person', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for kontrolliert_visa_person field
            //
            $editor = new TextEdit('kontrolliert_visa_person_edit');
            $editColumn = new CustomEditColumn('Kontrolliert Visa Person', 'kontrolliert_visa_person', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for kontrolliert_datum_person field
            //
            $editor = new DateTimeEdit('kontrolliert_datum_person_edit', false, 'd.m.Y H:i:s');
            $editColumn = new CustomEditColumn('Kontrolliert Datum Person', 'kontrolliert_datum_person', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for freigabe_visa_person field
            //
            $editor = new TextEdit('freigabe_visa_person_edit');
            $editColumn = new CustomEditColumn('Freigabe Visa Person', 'freigabe_visa_person', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for freigabe_datum_person field
            //
            $editor = new DateTimeEdit('freigabe_datum_person_edit', false, 'd.m.Y H:i:s');
            $editColumn = new CustomEditColumn('Freigabe Datum Person', 'freigabe_datum_person', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for created_visa_person field
            //
            $editor = new TextEdit('created_visa_person_edit');
            $editColumn = new CustomEditColumn('Created Visa Person', 'created_visa_person', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for created_date_person field
            //
            $editor = new DateTimeEdit('created_date_person_edit', false, 'd.m.Y H:i:s');
            $editColumn = new CustomEditColumn('Created Date Person', 'created_date_person', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for updated_visa_person field
            //
            $editor = new TextEdit('updated_visa_person_edit');
            $editColumn = new CustomEditColumn('Updated Visa Person', 'updated_visa_person', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for updated_date_person field
            //
            $editor = new DateTimeEdit('updated_date_person_edit', false, 'd.m.Y H:i:s');
            $editColumn = new CustomEditColumn('Updated Date Person', 'updated_date_person', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for created_date_unix_person field
            //
            $editor = new TextEdit('created_date_unix_person_edit');
            $editColumn = new CustomEditColumn('Created Date Unix Person', 'created_date_unix_person', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for updated_date_unix_person field
            //
            $editor = new TextEdit('updated_date_unix_person_edit');
            $editColumn = new CustomEditColumn('Updated Date Unix Person', 'updated_date_unix_person', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for eingabe_abgeschlossen_datum_unix_person field
            //
            $editor = new TextEdit('eingabe_abgeschlossen_datum_unix_person_edit');
            $editColumn = new CustomEditColumn('Eingabe Abgeschlossen Datum Unix Person', 'eingabe_abgeschlossen_datum_unix_person', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for kontrolliert_datum_unix_person field
            //
            $editor = new TextEdit('kontrolliert_datum_unix_person_edit');
            $editColumn = new CustomEditColumn('Kontrolliert Datum Unix Person', 'kontrolliert_datum_unix_person', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for freigabe_datum_unix_person field
            //
            $editor = new TextEdit('freigabe_datum_unix_person_edit');
            $editColumn = new CustomEditColumn('Freigabe Datum Unix Person', 'freigabe_datum_unix_person', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for person_id field
            //
            $editor = new SpinEdit('person_id_edit');
            $editColumn = new CustomEditColumn('Person Id', 'person_id', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for zutrittsberechtigung_id field
            //
            $editor = new SpinEdit('zutrittsberechtigung_id_edit');
            $editColumn = new CustomEditColumn('Zutrittsberechtigung Id', 'zutrittsberechtigung_id', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for funktion_fr field
            //
            $editor = new TextEdit('funktion_fr_edit');
            $editColumn = new CustomEditColumn('Funktion Fr', 'funktion_fr', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for bis_unix field
            //
            $editor = new TextEdit('bis_unix_edit');
            $editColumn = new CustomEditColumn('Bis Unix', 'bis_unix', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for von_unix field
            //
            $editor = new TextEdit('von_unix_edit');
            $editColumn = new CustomEditColumn('Von Unix', 'von_unix', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for created_date_unix field
            //
            $editor = new TextEdit('created_date_unix_edit');
            $editColumn = new CustomEditColumn('Created Date Unix', 'created_date_unix', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for updated_date_unix field
            //
            $editor = new TextEdit('updated_date_unix_edit');
            $editColumn = new CustomEditColumn('Updated Date Unix', 'updated_date_unix', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for eingabe_abgeschlossen_datum_unix field
            //
            $editor = new TextEdit('eingabe_abgeschlossen_datum_unix_edit');
            $editColumn = new CustomEditColumn('Eingabe Abgeschlossen Datum Unix', 'eingabe_abgeschlossen_datum_unix', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for kontrolliert_datum_unix field
            //
            $editor = new TextEdit('kontrolliert_datum_unix_edit');
            $editColumn = new CustomEditColumn('Kontrolliert Datum Unix', 'kontrolliert_datum_unix', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for freigabe_datum_unix field
            //
            $editor = new TextEdit('freigabe_datum_unix_edit');
            $editColumn = new CustomEditColumn('Freigabe Datum Unix', 'freigabe_datum_unix', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for published_person field
            //
            $editor = new TextEdit('published_person_edit');
            $editColumn = new CustomEditColumn('Published Person', 'published_person', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for published field
            //
            $editor = new TextEdit('published_edit');
            $editColumn = new CustomEditColumn('Published', 'published', $editor, $this->dataset);
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
            // View column for id field
            //
            $column = new TextViewColumn('id', 'id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for anzeige_name field
            //
            $column = new TextViewColumn('parlamentarier_id', 'parlamentarier_id_anzeige_name', 'Parlamentarier', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for kommissionen field
            //
            $column = new TextViewColumn('kommissionen', 'kommissionen', 'Kommissionen', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for nachname field
            //
            $column = new TextViewColumn('nachname', 'nachname', 'Nachname', $this->dataset);
            $column->SetOrderable(true);
            $column->setHrefTemplate('person.php?operation=edit&amp;pk0=%id%&amp;t=0');
            $column->setTarget('');
            $grid->AddPrintColumn($column);
            
            //
            // View column for vorname field
            //
            $column = new TextViewColumn('vorname', 'vorname', 'Vorname', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for zweiter_vorname field
            //
            $column = new TextViewColumn('zweiter_vorname', 'zweiter_vorname', 'Zweiter Vorname', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for funktion field
            //
            $column = new TextViewColumn('funktion', 'funktion', 'Funktion', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for beruf field
            //
            $column = new TextViewColumn('beruf', 'beruf', 'Beruf', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for anzeige_name field
            //
            $column = new TextViewColumn('beruf_interessengruppe_id', 'beruf_interessengruppe_id_anzeige_name', 'Beruf Interessengruppe', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for abkuerzung field
            //
            $column = new TextViewColumn('partei_id', 'partei_id_abkuerzung', 'Partei', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for geschlecht field
            //
            $column = new TextViewColumn('geschlecht', 'geschlecht', 'Geschlecht', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for email field
            //
            $column = new TextViewColumn('email', 'email', 'Email', $this->dataset);
            $column->SetOrderable(true);
            $column->setHrefTemplate('mailtto:%email%');
            $column->setTarget('_blank');
            $grid->AddPrintColumn($column);
            
            //
            // View column for homepage field
            //
            $column = new TextViewColumn('homepage', 'homepage', 'Homepage', $this->dataset);
            $column->SetOrderable(true);
            $column->setHrefTemplate('%homepage%');
            $column->setTarget('');
            $grid->AddPrintColumn($column);
            
            //
            // View column for twitter_name field
            //
            $column = new TextViewColumn('twitter_name', 'twitter_name', 'Twitter Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for linkedin_profil_url field
            //
            $column = new TextViewColumn('linkedin_profil_url', 'linkedin_profil_url', 'Linkedin Profil Url', $this->dataset);
            $column->SetOrderable(true);
            $column->setHrefTemplate('%linkedin_profil_url%');
            $column->setTarget('');
            $grid->AddPrintColumn($column);
            
            //
            // View column for xing_profil_name field
            //
            $column = new TextViewColumn('xing_profil_name', 'xing_profil_name', 'Xing Profil Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for facebook_name field
            //
            $column = new TextViewColumn('facebook_name', 'facebook_name', 'Facebook Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for created_visa field
            //
            $column = new TextViewColumn('created_visa', 'created_visa', 'Created Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for created_date field
            //
            $column = new DateTimeViewColumn('created_date', 'created_date', 'Created Date', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $grid->AddPrintColumn($column);
            
            //
            // View column for updated_visa field
            //
            $column = new TextViewColumn('updated_visa', 'updated_visa', 'Updated Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for updated_date field
            //
            $column = new DateTimeViewColumn('updated_date', 'updated_date', 'Updated Date', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $grid->AddPrintColumn($column);
            
            //
            // View column for anzeige_name field
            //
            $column = new TextViewColumn('anzeige_name', 'anzeige_name', 'Anzeige Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for anzeige_name_de field
            //
            $column = new TextViewColumn('anzeige_name_de', 'anzeige_name_de', 'Anzeige Name De', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for anzeige_name_fr field
            //
            $column = new TextViewColumn('anzeige_name_fr', 'anzeige_name_fr', 'Anzeige Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('name', 'name', 'Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for name_de field
            //
            $column = new TextViewColumn('name_de', 'name_de', 'Name De', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for name_fr field
            //
            $column = new TextViewColumn('name_fr', 'name_fr', 'Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for beschreibung_de field
            //
            $column = new TextViewColumn('beschreibung_de', 'beschreibung_de', 'Beschreibung De', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for beschreibung_fr field
            //
            $column = new TextViewColumn('beschreibung_fr', 'beschreibung_fr', 'Beschreibung Fr', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for parlamentarier_kommissionen field
            //
            $column = new TextViewColumn('parlamentarier_kommissionen', 'parlamentarier_kommissionen', 'Parlamentarier Kommissionen', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for parlamentarier_kommissionen_zutrittsberechtigung field
            //
            $column = new TextViewColumn('parlamentarier_kommissionen_zutrittsberechtigung', 'parlamentarier_kommissionen_zutrittsberechtigung', 'Parlamentarier Kommissionen Zutrittsberechtigung', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for beruf_fr field
            //
            $column = new TextViewColumn('beruf_fr', 'beruf_fr', 'Beruf Fr', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for arbeitssprache field
            //
            $column = new TextViewColumn('arbeitssprache', 'arbeitssprache', 'Arbeitssprache', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for telephon_1 field
            //
            $column = new TextViewColumn('telephon_1', 'telephon_1', 'Telephon 1', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for telephon_2 field
            //
            $column = new TextViewColumn('telephon_2', 'telephon_2', 'Telephon 2', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for erfasst field
            //
            $column = new TextViewColumn('erfasst', 'erfasst', 'Erfasst', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_visa_person field
            //
            $column = new TextViewColumn('eingabe_abgeschlossen_visa_person', 'eingabe_abgeschlossen_visa_person', 'Eingabe Abgeschlossen Visa Person', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_datum_person field
            //
            $column = new DateTimeViewColumn('eingabe_abgeschlossen_datum_person', 'eingabe_abgeschlossen_datum_person', 'Eingabe Abgeschlossen Datum Person', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $grid->AddPrintColumn($column);
            
            //
            // View column for kontrolliert_visa_person field
            //
            $column = new TextViewColumn('kontrolliert_visa_person', 'kontrolliert_visa_person', 'Kontrolliert Visa Person', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for kontrolliert_datum_person field
            //
            $column = new DateTimeViewColumn('kontrolliert_datum_person', 'kontrolliert_datum_person', 'Kontrolliert Datum Person', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $grid->AddPrintColumn($column);
            
            //
            // View column for freigabe_visa_person field
            //
            $column = new TextViewColumn('freigabe_visa_person', 'freigabe_visa_person', 'Freigabe Visa Person', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for freigabe_datum_person field
            //
            $column = new DateTimeViewColumn('freigabe_datum_person', 'freigabe_datum_person', 'Freigabe Datum Person', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $grid->AddPrintColumn($column);
            
            //
            // View column for created_visa_person field
            //
            $column = new TextViewColumn('created_visa_person', 'created_visa_person', 'Created Visa Person', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for created_date_person field
            //
            $column = new DateTimeViewColumn('created_date_person', 'created_date_person', 'Created Date Person', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $grid->AddPrintColumn($column);
            
            //
            // View column for updated_visa_person field
            //
            $column = new TextViewColumn('updated_visa_person', 'updated_visa_person', 'Updated Visa Person', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for updated_date_person field
            //
            $column = new DateTimeViewColumn('updated_date_person', 'updated_date_person', 'Updated Date Person', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $grid->AddPrintColumn($column);
            
            //
            // View column for created_date_unix_person field
            //
            $column = new TextViewColumn('created_date_unix_person', 'created_date_unix_person', 'Created Date Unix Person', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for updated_date_unix_person field
            //
            $column = new TextViewColumn('updated_date_unix_person', 'updated_date_unix_person', 'Updated Date Unix Person', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_datum_unix_person field
            //
            $column = new TextViewColumn('eingabe_abgeschlossen_datum_unix_person', 'eingabe_abgeschlossen_datum_unix_person', 'Eingabe Abgeschlossen Datum Unix Person', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for kontrolliert_datum_unix_person field
            //
            $column = new TextViewColumn('kontrolliert_datum_unix_person', 'kontrolliert_datum_unix_person', 'Kontrolliert Datum Unix Person', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for freigabe_datum_unix_person field
            //
            $column = new TextViewColumn('freigabe_datum_unix_person', 'freigabe_datum_unix_person', 'Freigabe Datum Unix Person', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for person_id field
            //
            $column = new NumberViewColumn('person_id', 'person_id', 'Person Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator('\'');
            $column->setDecimalSeparator('');
            $grid->AddPrintColumn($column);
            
            //
            // View column for zutrittsberechtigung_id field
            //
            $column = new NumberViewColumn('zutrittsberechtigung_id', 'zutrittsberechtigung_id', 'Zutrittsberechtigung Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator('\'');
            $column->setDecimalSeparator('');
            $grid->AddPrintColumn($column);
            
            //
            // View column for funktion_fr field
            //
            $column = new TextViewColumn('funktion_fr', 'funktion_fr', 'Funktion Fr', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for bis_unix field
            //
            $column = new TextViewColumn('bis_unix', 'bis_unix', 'Bis Unix', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for von_unix field
            //
            $column = new TextViewColumn('von_unix', 'von_unix', 'Von Unix', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for created_date_unix field
            //
            $column = new TextViewColumn('created_date_unix', 'created_date_unix', 'Created Date Unix', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for updated_date_unix field
            //
            $column = new TextViewColumn('updated_date_unix', 'updated_date_unix', 'Updated Date Unix', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_datum_unix field
            //
            $column = new TextViewColumn('eingabe_abgeschlossen_datum_unix', 'eingabe_abgeschlossen_datum_unix', 'Eingabe Abgeschlossen Datum Unix', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for kontrolliert_datum_unix field
            //
            $column = new TextViewColumn('kontrolliert_datum_unix', 'kontrolliert_datum_unix', 'Kontrolliert Datum Unix', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for freigabe_datum_unix field
            //
            $column = new TextViewColumn('freigabe_datum_unix', 'freigabe_datum_unix', 'Freigabe Datum Unix', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for published_person field
            //
            $column = new TextViewColumn('published_person', 'published_person', 'Published Person', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for published field
            //
            $column = new TextViewColumn('published', 'published', 'Published', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
        }
    
        protected function AddExportColumns(Grid $grid)
        {
            //
            // View column for id field
            //
            $column = new TextViewColumn('id', 'id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for anzeige_name field
            //
            $column = new TextViewColumn('parlamentarier_id', 'parlamentarier_id_anzeige_name', 'Parlamentarier', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for kommissionen field
            //
            $column = new TextViewColumn('kommissionen', 'kommissionen', 'Kommissionen', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for nachname field
            //
            $column = new TextViewColumn('nachname', 'nachname', 'Nachname', $this->dataset);
            $column->SetOrderable(true);
            $column->setHrefTemplate('person.php?operation=edit&amp;pk0=%id%&amp;t=0');
            $column->setTarget('');
            $grid->AddExportColumn($column);
            
            //
            // View column for vorname field
            //
            $column = new TextViewColumn('vorname', 'vorname', 'Vorname', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for zweiter_vorname field
            //
            $column = new TextViewColumn('zweiter_vorname', 'zweiter_vorname', 'Zweiter Vorname', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for funktion field
            //
            $column = new TextViewColumn('funktion', 'funktion', 'Funktion', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for beruf field
            //
            $column = new TextViewColumn('beruf', 'beruf', 'Beruf', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for anzeige_name field
            //
            $column = new TextViewColumn('beruf_interessengruppe_id', 'beruf_interessengruppe_id_anzeige_name', 'Beruf Interessengruppe', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for abkuerzung field
            //
            $column = new TextViewColumn('partei_id', 'partei_id_abkuerzung', 'Partei', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for geschlecht field
            //
            $column = new TextViewColumn('geschlecht', 'geschlecht', 'Geschlecht', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for email field
            //
            $column = new TextViewColumn('email', 'email', 'Email', $this->dataset);
            $column->SetOrderable(true);
            $column->setHrefTemplate('mailtto:%email%');
            $column->setTarget('_blank');
            $grid->AddExportColumn($column);
            
            //
            // View column for homepage field
            //
            $column = new TextViewColumn('homepage', 'homepage', 'Homepage', $this->dataset);
            $column->SetOrderable(true);
            $column->setHrefTemplate('%homepage%');
            $column->setTarget('');
            $grid->AddExportColumn($column);
            
            //
            // View column for twitter_name field
            //
            $column = new TextViewColumn('twitter_name', 'twitter_name', 'Twitter Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for linkedin_profil_url field
            //
            $column = new TextViewColumn('linkedin_profil_url', 'linkedin_profil_url', 'Linkedin Profil Url', $this->dataset);
            $column->SetOrderable(true);
            $column->setHrefTemplate('%linkedin_profil_url%');
            $column->setTarget('');
            $grid->AddExportColumn($column);
            
            //
            // View column for xing_profil_name field
            //
            $column = new TextViewColumn('xing_profil_name', 'xing_profil_name', 'Xing Profil Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for facebook_name field
            //
            $column = new TextViewColumn('facebook_name', 'facebook_name', 'Facebook Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for created_visa field
            //
            $column = new TextViewColumn('created_visa', 'created_visa', 'Created Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for created_date field
            //
            $column = new DateTimeViewColumn('created_date', 'created_date', 'Created Date', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $grid->AddExportColumn($column);
            
            //
            // View column for updated_visa field
            //
            $column = new TextViewColumn('updated_visa', 'updated_visa', 'Updated Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for updated_date field
            //
            $column = new DateTimeViewColumn('updated_date', 'updated_date', 'Updated Date', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $grid->AddExportColumn($column);
            
            //
            // View column for anzeige_name field
            //
            $column = new TextViewColumn('anzeige_name', 'anzeige_name', 'Anzeige Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for anzeige_name_de field
            //
            $column = new TextViewColumn('anzeige_name_de', 'anzeige_name_de', 'Anzeige Name De', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for anzeige_name_fr field
            //
            $column = new TextViewColumn('anzeige_name_fr', 'anzeige_name_fr', 'Anzeige Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('name', 'name', 'Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for name_de field
            //
            $column = new TextViewColumn('name_de', 'name_de', 'Name De', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for name_fr field
            //
            $column = new TextViewColumn('name_fr', 'name_fr', 'Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for beschreibung_de field
            //
            $column = new TextViewColumn('beschreibung_de', 'beschreibung_de', 'Beschreibung De', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for beschreibung_fr field
            //
            $column = new TextViewColumn('beschreibung_fr', 'beschreibung_fr', 'Beschreibung Fr', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for parlamentarier_kommissionen field
            //
            $column = new TextViewColumn('parlamentarier_kommissionen', 'parlamentarier_kommissionen', 'Parlamentarier Kommissionen', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for parlamentarier_kommissionen_zutrittsberechtigung field
            //
            $column = new TextViewColumn('parlamentarier_kommissionen_zutrittsberechtigung', 'parlamentarier_kommissionen_zutrittsberechtigung', 'Parlamentarier Kommissionen Zutrittsberechtigung', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for beruf_fr field
            //
            $column = new TextViewColumn('beruf_fr', 'beruf_fr', 'Beruf Fr', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for arbeitssprache field
            //
            $column = new TextViewColumn('arbeitssprache', 'arbeitssprache', 'Arbeitssprache', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for telephon_1 field
            //
            $column = new TextViewColumn('telephon_1', 'telephon_1', 'Telephon 1', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for telephon_2 field
            //
            $column = new TextViewColumn('telephon_2', 'telephon_2', 'Telephon 2', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for erfasst field
            //
            $column = new TextViewColumn('erfasst', 'erfasst', 'Erfasst', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_visa_person field
            //
            $column = new TextViewColumn('eingabe_abgeschlossen_visa_person', 'eingabe_abgeschlossen_visa_person', 'Eingabe Abgeschlossen Visa Person', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_datum_person field
            //
            $column = new DateTimeViewColumn('eingabe_abgeschlossen_datum_person', 'eingabe_abgeschlossen_datum_person', 'Eingabe Abgeschlossen Datum Person', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $grid->AddExportColumn($column);
            
            //
            // View column for kontrolliert_visa_person field
            //
            $column = new TextViewColumn('kontrolliert_visa_person', 'kontrolliert_visa_person', 'Kontrolliert Visa Person', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for kontrolliert_datum_person field
            //
            $column = new DateTimeViewColumn('kontrolliert_datum_person', 'kontrolliert_datum_person', 'Kontrolliert Datum Person', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $grid->AddExportColumn($column);
            
            //
            // View column for freigabe_visa_person field
            //
            $column = new TextViewColumn('freigabe_visa_person', 'freigabe_visa_person', 'Freigabe Visa Person', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for freigabe_datum_person field
            //
            $column = new DateTimeViewColumn('freigabe_datum_person', 'freigabe_datum_person', 'Freigabe Datum Person', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $grid->AddExportColumn($column);
            
            //
            // View column for created_visa_person field
            //
            $column = new TextViewColumn('created_visa_person', 'created_visa_person', 'Created Visa Person', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for created_date_person field
            //
            $column = new DateTimeViewColumn('created_date_person', 'created_date_person', 'Created Date Person', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $grid->AddExportColumn($column);
            
            //
            // View column for updated_visa_person field
            //
            $column = new TextViewColumn('updated_visa_person', 'updated_visa_person', 'Updated Visa Person', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for updated_date_person field
            //
            $column = new DateTimeViewColumn('updated_date_person', 'updated_date_person', 'Updated Date Person', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $grid->AddExportColumn($column);
            
            //
            // View column for created_date_unix_person field
            //
            $column = new TextViewColumn('created_date_unix_person', 'created_date_unix_person', 'Created Date Unix Person', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for updated_date_unix_person field
            //
            $column = new TextViewColumn('updated_date_unix_person', 'updated_date_unix_person', 'Updated Date Unix Person', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_datum_unix_person field
            //
            $column = new TextViewColumn('eingabe_abgeschlossen_datum_unix_person', 'eingabe_abgeschlossen_datum_unix_person', 'Eingabe Abgeschlossen Datum Unix Person', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for kontrolliert_datum_unix_person field
            //
            $column = new TextViewColumn('kontrolliert_datum_unix_person', 'kontrolliert_datum_unix_person', 'Kontrolliert Datum Unix Person', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for freigabe_datum_unix_person field
            //
            $column = new TextViewColumn('freigabe_datum_unix_person', 'freigabe_datum_unix_person', 'Freigabe Datum Unix Person', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for person_id field
            //
            $column = new NumberViewColumn('person_id', 'person_id', 'Person Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator('\'');
            $column->setDecimalSeparator('');
            $grid->AddExportColumn($column);
            
            //
            // View column for zutrittsberechtigung_id field
            //
            $column = new NumberViewColumn('zutrittsberechtigung_id', 'zutrittsberechtigung_id', 'Zutrittsberechtigung Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator('\'');
            $column->setDecimalSeparator('');
            $grid->AddExportColumn($column);
            
            //
            // View column for funktion_fr field
            //
            $column = new TextViewColumn('funktion_fr', 'funktion_fr', 'Funktion Fr', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for bis_unix field
            //
            $column = new TextViewColumn('bis_unix', 'bis_unix', 'Bis Unix', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for von_unix field
            //
            $column = new TextViewColumn('von_unix', 'von_unix', 'Von Unix', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for created_date_unix field
            //
            $column = new TextViewColumn('created_date_unix', 'created_date_unix', 'Created Date Unix', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for updated_date_unix field
            //
            $column = new TextViewColumn('updated_date_unix', 'updated_date_unix', 'Updated Date Unix', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_datum_unix field
            //
            $column = new TextViewColumn('eingabe_abgeschlossen_datum_unix', 'eingabe_abgeschlossen_datum_unix', 'Eingabe Abgeschlossen Datum Unix', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for kontrolliert_datum_unix field
            //
            $column = new TextViewColumn('kontrolliert_datum_unix', 'kontrolliert_datum_unix', 'Kontrolliert Datum Unix', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for freigabe_datum_unix field
            //
            $column = new TextViewColumn('freigabe_datum_unix', 'freigabe_datum_unix', 'Freigabe Datum Unix', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for published_person field
            //
            $column = new TextViewColumn('published_person', 'published_person', 'Published Person', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for published field
            //
            $column = new TextViewColumn('published', 'published', 'Published', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
        }
    
        private function AddCompareColumns(Grid $grid)
        {
            //
            // View column for id field
            //
            $column = new TextViewColumn('id', 'id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for anzeige_name field
            //
            $column = new TextViewColumn('parlamentarier_id', 'parlamentarier_id_anzeige_name', 'Parlamentarier', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for kommissionen field
            //
            $column = new TextViewColumn('kommissionen', 'kommissionen', 'Kommissionen', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for nachname field
            //
            $column = new TextViewColumn('nachname', 'nachname', 'Nachname', $this->dataset);
            $column->SetOrderable(true);
            $column->setHrefTemplate('person.php?operation=edit&amp;pk0=%id%&amp;t=0');
            $column->setTarget('');
            $grid->AddCompareColumn($column);
            
            //
            // View column for vorname field
            //
            $column = new TextViewColumn('vorname', 'vorname', 'Vorname', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for zweiter_vorname field
            //
            $column = new TextViewColumn('zweiter_vorname', 'zweiter_vorname', 'Zweiter Vorname', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for funktion field
            //
            $column = new TextViewColumn('funktion', 'funktion', 'Funktion', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for beruf field
            //
            $column = new TextViewColumn('beruf', 'beruf', 'Beruf', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for anzeige_name field
            //
            $column = new TextViewColumn('beruf_interessengruppe_id', 'beruf_interessengruppe_id_anzeige_name', 'Beruf Interessengruppe', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for abkuerzung field
            //
            $column = new TextViewColumn('partei_id', 'partei_id_abkuerzung', 'Partei', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for geschlecht field
            //
            $column = new TextViewColumn('geschlecht', 'geschlecht', 'Geschlecht', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for email field
            //
            $column = new TextViewColumn('email', 'email', 'Email', $this->dataset);
            $column->SetOrderable(true);
            $column->setHrefTemplate('mailtto:%email%');
            $column->setTarget('_blank');
            $grid->AddCompareColumn($column);
            
            //
            // View column for homepage field
            //
            $column = new TextViewColumn('homepage', 'homepage', 'Homepage', $this->dataset);
            $column->SetOrderable(true);
            $column->setHrefTemplate('%homepage%');
            $column->setTarget('');
            $grid->AddCompareColumn($column);
            
            //
            // View column for twitter_name field
            //
            $column = new TextViewColumn('twitter_name', 'twitter_name', 'Twitter Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for linkedin_profil_url field
            //
            $column = new TextViewColumn('linkedin_profil_url', 'linkedin_profil_url', 'Linkedin Profil Url', $this->dataset);
            $column->SetOrderable(true);
            $column->setHrefTemplate('%linkedin_profil_url%');
            $column->setTarget('');
            $grid->AddCompareColumn($column);
            
            //
            // View column for xing_profil_name field
            //
            $column = new TextViewColumn('xing_profil_name', 'xing_profil_name', 'Xing Profil Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for facebook_name field
            //
            $column = new TextViewColumn('facebook_name', 'facebook_name', 'Facebook Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for created_visa field
            //
            $column = new TextViewColumn('created_visa', 'created_visa', 'Created Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for created_date field
            //
            $column = new DateTimeViewColumn('created_date', 'created_date', 'Created Date', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $grid->AddCompareColumn($column);
            
            //
            // View column for updated_visa field
            //
            $column = new TextViewColumn('updated_visa', 'updated_visa', 'Updated Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for updated_date field
            //
            $column = new DateTimeViewColumn('updated_date', 'updated_date', 'Updated Date', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $grid->AddCompareColumn($column);
            
            //
            // View column for von field
            //
            $column = new DateTimeViewColumn('von', 'von', 'Von', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('Y-m-d');
            $grid->AddCompareColumn($column);
            
            //
            // View column for bis field
            //
            $column = new DateTimeViewColumn('bis', 'bis', 'Bis', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('Y-m-d');
            $grid->AddCompareColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_visa field
            //
            $column = new TextViewColumn('eingabe_abgeschlossen_visa', 'eingabe_abgeschlossen_visa', 'Eingabe Abgeschlossen Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_datum field
            //
            $column = new DateTimeViewColumn('eingabe_abgeschlossen_datum', 'eingabe_abgeschlossen_datum', 'Eingabe Abgeschlossen Datum', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $grid->AddCompareColumn($column);
            
            //
            // View column for kontrolliert_visa field
            //
            $column = new TextViewColumn('kontrolliert_visa', 'kontrolliert_visa', 'Kontrolliert Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for kontrolliert_datum field
            //
            $column = new DateTimeViewColumn('kontrolliert_datum', 'kontrolliert_datum', 'Kontrolliert Datum', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $grid->AddCompareColumn($column);
            
            //
            // View column for autorisierung_verschickt_visa field
            //
            $column = new TextViewColumn('autorisierung_verschickt_visa', 'autorisierung_verschickt_visa', 'Autorisierung Verschickt Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for autorisierung_verschickt_datum field
            //
            $column = new DateTimeViewColumn('autorisierung_verschickt_datum', 'autorisierung_verschickt_datum', 'Autorisierung Verschickt Datum', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $grid->AddCompareColumn($column);
            
            //
            // View column for autorisiert_visa field
            //
            $column = new TextViewColumn('autorisiert_visa', 'autorisiert_visa', 'Autorisiert Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for autorisiert_datum field
            //
            $column = new DateTimeViewColumn('autorisiert_datum', 'autorisiert_datum', 'Autorisiert Datum', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('Y-m-d');
            $grid->AddCompareColumn($column);
            
            //
            // View column for freigabe_visa field
            //
            $column = new TextViewColumn('freigabe_visa', 'freigabe_visa', 'Freigabe Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for freigabe_datum field
            //
            $column = new DateTimeViewColumn('freigabe_datum', 'freigabe_datum', 'Freigabe Datum', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $grid->AddCompareColumn($column);
            
            //
            // View column for anzeige_name field
            //
            $column = new TextViewColumn('anzeige_name', 'anzeige_name', 'Anzeige Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for anzeige_name_de field
            //
            $column = new TextViewColumn('anzeige_name_de', 'anzeige_name_de', 'Anzeige Name De', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for anzeige_name_fr field
            //
            $column = new TextViewColumn('anzeige_name_fr', 'anzeige_name_fr', 'Anzeige Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('name', 'name', 'Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for name_de field
            //
            $column = new TextViewColumn('name_de', 'name_de', 'Name De', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for name_fr field
            //
            $column = new TextViewColumn('name_fr', 'name_fr', 'Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for beschreibung_de field
            //
            $column = new TextViewColumn('beschreibung_de', 'beschreibung_de', 'Beschreibung De', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for beschreibung_fr field
            //
            $column = new TextViewColumn('beschreibung_fr', 'beschreibung_fr', 'Beschreibung Fr', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for parlamentarier_kommissionen field
            //
            $column = new TextViewColumn('parlamentarier_kommissionen', 'parlamentarier_kommissionen', 'Parlamentarier Kommissionen', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for parlamentarier_kommissionen_zutrittsberechtigung field
            //
            $column = new TextViewColumn('parlamentarier_kommissionen_zutrittsberechtigung', 'parlamentarier_kommissionen_zutrittsberechtigung', 'Parlamentarier Kommissionen Zutrittsberechtigung', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for beruf_fr field
            //
            $column = new TextViewColumn('beruf_fr', 'beruf_fr', 'Beruf Fr', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for arbeitssprache field
            //
            $column = new TextViewColumn('arbeitssprache', 'arbeitssprache', 'Arbeitssprache', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for telephon_1 field
            //
            $column = new TextViewColumn('telephon_1', 'telephon_1', 'Telephon 1', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for telephon_2 field
            //
            $column = new TextViewColumn('telephon_2', 'telephon_2', 'Telephon 2', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for erfasst field
            //
            $column = new TextViewColumn('erfasst', 'erfasst', 'Erfasst', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_visa_person field
            //
            $column = new TextViewColumn('eingabe_abgeschlossen_visa_person', 'eingabe_abgeschlossen_visa_person', 'Eingabe Abgeschlossen Visa Person', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_datum_person field
            //
            $column = new DateTimeViewColumn('eingabe_abgeschlossen_datum_person', 'eingabe_abgeschlossen_datum_person', 'Eingabe Abgeschlossen Datum Person', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $grid->AddCompareColumn($column);
            
            //
            // View column for kontrolliert_visa_person field
            //
            $column = new TextViewColumn('kontrolliert_visa_person', 'kontrolliert_visa_person', 'Kontrolliert Visa Person', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for kontrolliert_datum_person field
            //
            $column = new DateTimeViewColumn('kontrolliert_datum_person', 'kontrolliert_datum_person', 'Kontrolliert Datum Person', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $grid->AddCompareColumn($column);
            
            //
            // View column for freigabe_visa_person field
            //
            $column = new TextViewColumn('freigabe_visa_person', 'freigabe_visa_person', 'Freigabe Visa Person', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for freigabe_datum_person field
            //
            $column = new DateTimeViewColumn('freigabe_datum_person', 'freigabe_datum_person', 'Freigabe Datum Person', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $grid->AddCompareColumn($column);
            
            //
            // View column for created_visa_person field
            //
            $column = new TextViewColumn('created_visa_person', 'created_visa_person', 'Created Visa Person', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for created_date_person field
            //
            $column = new DateTimeViewColumn('created_date_person', 'created_date_person', 'Created Date Person', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $grid->AddCompareColumn($column);
            
            //
            // View column for updated_visa_person field
            //
            $column = new TextViewColumn('updated_visa_person', 'updated_visa_person', 'Updated Visa Person', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for updated_date_person field
            //
            $column = new DateTimeViewColumn('updated_date_person', 'updated_date_person', 'Updated Date Person', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $grid->AddCompareColumn($column);
            
            //
            // View column for created_date_unix_person field
            //
            $column = new TextViewColumn('created_date_unix_person', 'created_date_unix_person', 'Created Date Unix Person', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for updated_date_unix_person field
            //
            $column = new TextViewColumn('updated_date_unix_person', 'updated_date_unix_person', 'Updated Date Unix Person', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_datum_unix_person field
            //
            $column = new TextViewColumn('eingabe_abgeschlossen_datum_unix_person', 'eingabe_abgeschlossen_datum_unix_person', 'Eingabe Abgeschlossen Datum Unix Person', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for kontrolliert_datum_unix_person field
            //
            $column = new TextViewColumn('kontrolliert_datum_unix_person', 'kontrolliert_datum_unix_person', 'Kontrolliert Datum Unix Person', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for freigabe_datum_unix_person field
            //
            $column = new TextViewColumn('freigabe_datum_unix_person', 'freigabe_datum_unix_person', 'Freigabe Datum Unix Person', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for person_id field
            //
            $column = new NumberViewColumn('person_id', 'person_id', 'Person Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator('\'');
            $column->setDecimalSeparator('');
            $grid->AddCompareColumn($column);
            
            //
            // View column for zutrittsberechtigung_id field
            //
            $column = new NumberViewColumn('zutrittsberechtigung_id', 'zutrittsberechtigung_id', 'Zutrittsberechtigung Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator('\'');
            $column->setDecimalSeparator('');
            $grid->AddCompareColumn($column);
            
            //
            // View column for funktion_fr field
            //
            $column = new TextViewColumn('funktion_fr', 'funktion_fr', 'Funktion Fr', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for bis_unix field
            //
            $column = new TextViewColumn('bis_unix', 'bis_unix', 'Bis Unix', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for von_unix field
            //
            $column = new TextViewColumn('von_unix', 'von_unix', 'Von Unix', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for created_date_unix field
            //
            $column = new TextViewColumn('created_date_unix', 'created_date_unix', 'Created Date Unix', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for updated_date_unix field
            //
            $column = new TextViewColumn('updated_date_unix', 'updated_date_unix', 'Updated Date Unix', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_datum_unix field
            //
            $column = new TextViewColumn('eingabe_abgeschlossen_datum_unix', 'eingabe_abgeschlossen_datum_unix', 'Eingabe Abgeschlossen Datum Unix', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for kontrolliert_datum_unix field
            //
            $column = new TextViewColumn('kontrolliert_datum_unix', 'kontrolliert_datum_unix', 'Kontrolliert Datum Unix', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for freigabe_datum_unix field
            //
            $column = new TextViewColumn('freigabe_datum_unix', 'freigabe_datum_unix', 'Freigabe Datum Unix', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for published_person field
            //
            $column = new TextViewColumn('published_person', 'published_person', 'Published Person', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for published field
            //
            $column = new TextViewColumn('published', 'published', 'Published', $this->dataset);
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
            
            $result->SetHighlightRowAtHover(false);
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
            $this->SetShowTopPageNavigator(true);
            $this->SetShowBottomPageNavigator(true);
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
            <p>Für Auswertungen müssen die Daten in genügender Qualität vorhanden sein. Nicht alle für die Auswertung benötigten Felder sind obligatorisch. Daten müssen deshalb nacherfasst werden. Eine Auflistung der Zutrittsberechtigten mit unvollständigen Angaben.
            </p>
            
            <p>Eine Person ist unvollständig, wenn
            </p>
            <ul>
            <li>das Geschlecht fehlt,
            </li><li>die E-Mail-Adresse fehlt,
            </li><li>der Beruf fehlt,
            </li><li>die Funktion fehlt.
            </li></ul><p>Diese Kriterien werden nach Bedarf angepasst.
            </p>
            </div>
            
            ' . $GLOBALS["edit_general_hint"] /*afterburner*/  . '');
    
            return $result;
        }
     
        protected function setClientSideEvents(Grid $grid) {
    
        }
    
        protected function doRegisterHandlers() {
            
            
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`v_parlamentarier_simple`');
            $lookupDataset->addFields(
                array(
                    new StringField('anzeige_name'),
                    new StringField('anzeige_name_de'),
                    new StringField('anzeige_name_fr'),
                    new StringField('name'),
                    new StringField('name_de'),
                    new StringField('name_fr'),
                    new IntegerField('id', true),
                    new StringField('nachname', true),
                    new StringField('vorname', true),
                    new StringField('vorname_kurz'),
                    new StringField('zweiter_vorname'),
                    new IntegerField('rat_id', true),
                    new IntegerField('kanton_id', true),
                    new StringField('kommissionen'),
                    new IntegerField('partei_id'),
                    new StringField('parteifunktion', true),
                    new IntegerField('fraktion_id'),
                    new StringField('fraktionsfunktion'),
                    new DateField('im_rat_seit', true),
                    new DateField('im_rat_bis'),
                    new DateField('ratswechsel'),
                    new DateField('ratsunterbruch_von'),
                    new DateField('ratsunterbruch_bis'),
                    new StringField('beruf'),
                    new StringField('beruf_fr'),
                    new IntegerField('beruf_interessengruppe_id'),
                    new StringField('titel'),
                    new StringField('aemter'),
                    new StringField('weitere_aemter'),
                    new StringField('zivilstand'),
                    new IntegerField('anzahl_kinder'),
                    new IntegerField('militaerischer_grad_id'),
                    new StringField('geschlecht'),
                    new DateField('geburtstag'),
                    new StringField('photo'),
                    new StringField('photo_dateiname'),
                    new StringField('photo_dateierweiterung'),
                    new StringField('photo_dateiname_voll'),
                    new StringField('photo_mime_type'),
                    new StringField('kleinbild'),
                    new IntegerField('sitzplatz'),
                    new StringField('email'),
                    new StringField('homepage'),
                    new StringField('homepage_2'),
                    new IntegerField('parlament_biografie_id'),
                    new IntegerField('parlament_number'),
                    new StringField('parlament_interessenbindungen'),
                    new StringField('parlament_interessenbindungen_json'),
                    new DateTimeField('parlament_interessenbindungen_updated'),
                    new StringField('twitter_name'),
                    new StringField('linkedin_profil_url'),
                    new StringField('xing_profil_name'),
                    new StringField('facebook_name'),
                    new StringField('wikipedia'),
                    new StringField('sprache'),
                    new StringField('arbeitssprache'),
                    new StringField('adresse_firma'),
                    new StringField('adresse_strasse'),
                    new StringField('adresse_zusatz'),
                    new StringField('adresse_plz'),
                    new StringField('adresse_ort'),
                    new StringField('telephon_1'),
                    new StringField('telephon_2'),
                    new StringField('erfasst'),
                    new StringField('notizen'),
                    new StringField('eingabe_abgeschlossen_visa'),
                    new DateTimeField('eingabe_abgeschlossen_datum'),
                    new StringField('kontrolliert_visa'),
                    new DateTimeField('kontrolliert_datum'),
                    new StringField('autorisierung_verschickt_visa'),
                    new DateTimeField('autorisierung_verschickt_datum'),
                    new StringField('autorisiert_visa'),
                    new DateField('autorisiert_datum'),
                    new StringField('freigabe_visa'),
                    new DateTimeField('freigabe_datum'),
                    new StringField('created_visa', true),
                    new DateTimeField('created_date', true),
                    new StringField('updated_visa'),
                    new DateTimeField('updated_date', true),
                    new StringField('beruf_de'),
                    new DateField('von', true),
                    new DateField('bis'),
                    new IntegerField('aktiv'),
                    new IntegerField('published', true),
                    new IntegerField('geburtstag_unix'),
                    new IntegerField('im_rat_seit_unix', true),
                    new IntegerField('im_rat_bis_unix'),
                    new IntegerField('created_date_unix', true),
                    new IntegerField('updated_date_unix', true),
                    new IntegerField('eingabe_abgeschlossen_datum_unix'),
                    new IntegerField('kontrolliert_datum_unix'),
                    new IntegerField('freigabe_datum_unix'),
                    new IntegerField('von_unix', true),
                    new IntegerField('bis_unix')
                )
            );
            $lookupDataset->setOrderByField('anzeige_name', 'ASC');
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_q_unvollstaendige_zutrittsberechtigte_parlamentarier_id_search', 'id', 'anzeige_name', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`v_interessengruppe_simple`');
            $lookupDataset->addFields(
                array(
                    new StringField('anzeige_name'),
                    new StringField('anzeige_name_de'),
                    new StringField('anzeige_name_fr'),
                    new StringField('anzeige_name_mixed'),
                    new IntegerField('id', true),
                    new StringField('name', true),
                    new StringField('name_fr'),
                    new IntegerField('branche_id', true),
                    new StringField('beschreibung', true),
                    new StringField('beschreibung_fr'),
                    new StringField('alias_namen'),
                    new StringField('alias_namen_fr'),
                    new StringField('notizen'),
                    new StringField('eingabe_abgeschlossen_visa'),
                    new DateTimeField('eingabe_abgeschlossen_datum'),
                    new StringField('kontrolliert_visa'),
                    new DateTimeField('kontrolliert_datum'),
                    new StringField('freigabe_visa'),
                    new DateTimeField('freigabe_datum'),
                    new StringField('created_visa', true),
                    new DateTimeField('created_date', true),
                    new StringField('updated_visa'),
                    new DateTimeField('updated_date', true),
                    new StringField('name_de', true),
                    new StringField('beschreibung_de', true),
                    new StringField('alias_namen_de'),
                    new IntegerField('published', true),
                    new IntegerField('created_date_unix', true),
                    new IntegerField('updated_date_unix', true),
                    new IntegerField('eingabe_abgeschlossen_datum_unix'),
                    new IntegerField('kontrolliert_datum_unix'),
                    new IntegerField('freigabe_datum_unix')
                )
            );
            $lookupDataset->setOrderByField('anzeige_name', 'ASC');
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_q_unvollstaendige_zutrittsberechtigte_beruf_interessengruppe_id_search', 'id', 'anzeige_name', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`v_partei`');
            $lookupDataset->addFields(
                array(
                    new StringField('anzeige_name'),
                    new StringField('anzeige_name_de'),
                    new StringField('anzeige_name_fr'),
                    new StringField('anzeige_name_mixed'),
                    new StringField('abkuerzung_mixed'),
                    new IntegerField('id', true),
                    new StringField('abkuerzung', true),
                    new StringField('abkuerzung_fr'),
                    new StringField('name'),
                    new StringField('name_fr'),
                    new IntegerField('fraktion_id'),
                    new DateField('gruendung'),
                    new StringField('position'),
                    new StringField('farbcode'),
                    new StringField('homepage'),
                    new StringField('homepage_fr'),
                    new StringField('email'),
                    new StringField('email_fr'),
                    new StringField('twitter_name'),
                    new StringField('twitter_name_fr'),
                    new StringField('beschreibung'),
                    new StringField('beschreibung_fr'),
                    new StringField('notizen'),
                    new StringField('eingabe_abgeschlossen_visa'),
                    new DateTimeField('eingabe_abgeschlossen_datum'),
                    new StringField('kontrolliert_visa'),
                    new DateTimeField('kontrolliert_datum'),
                    new StringField('freigabe_visa'),
                    new DateTimeField('freigabe_datum'),
                    new StringField('created_visa', true),
                    new DateTimeField('created_date', true),
                    new StringField('updated_visa'),
                    new DateTimeField('updated_date', true),
                    new StringField('name_de'),
                    new StringField('abkuerzung_de', true),
                    new StringField('beschreibung_de'),
                    new StringField('homepage_de'),
                    new StringField('twitter_name_de'),
                    new StringField('email_de'),
                    new IntegerField('published', true),
                    new IntegerField('created_date_unix', true),
                    new IntegerField('updated_date_unix', true),
                    new IntegerField('eingabe_abgeschlossen_datum_unix'),
                    new IntegerField('kontrolliert_datum_unix'),
                    new IntegerField('freigabe_datum_unix')
                )
            );
            $lookupDataset->setOrderByField('abkuerzung', 'ASC');
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_q_unvollstaendige_zutrittsberechtigte_partei_id_search', 'id', 'abkuerzung', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
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
            customDrawRow('person', $rowData, $rowCellStyles, $rowStyles);
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
            defaultOnGetCustomTemplate($this, $part, $mode, $result, $params);
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
    
        protected function doGetCustomRecordPermissions(Page $page, &$usingCondition, $rowData, &$allowEdit, &$allowDelete, &$mergeWithDefault, &$handled)
        {
    
        }
    
        protected function doAddEnvironmentVariables(Page $page, &$variables)
        {
    
        }
    
    }

    SetUpUserAuthorization();

    try
    {
        $Page = new q_unvollstaendige_zutrittsberechtigtePage("q_unvollstaendige_zutrittsberechtigte", "q_unvollstaendige_zutrittsberechtigte.php", GetCurrentUserPermissionsForPage("q_unvollstaendige_zutrittsberechtigte"), 'UTF-8');
        $Page->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource("q_unvollstaendige_zutrittsberechtigte"));
        GetApplication()->SetMainPage($Page);
        before_render($Page); /*afterburner*/ 
        GetApplication()->Run();
    }
    catch(Exception $e)
    {
        ShowErrorPage($e);
    }
