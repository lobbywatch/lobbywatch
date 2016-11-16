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


    include_once dirname(__FILE__) . '/' . 'database_engine/mysql_engine.php';
    include_once dirname(__FILE__) . '/' . 'components/page/page.php';
    include_once dirname(__FILE__) . '/' . 'components/page/detail_page.php';
    include_once dirname(__FILE__) . '/' . 'components/page/nested_form_page.php';
    include_once dirname(__FILE__) . '/' . 'authorization.php';

    function GetConnectionOptions()
    {
        $result = GetGlobalConnectionOptions();
        $result['client_encoding'] = 'utf8';
        GetApplication()->GetUserAuthorizationStrategy()->ApplyIdentityToConnectionOptions($result);
        return $result;
    }

    // OnGlobalBeforePageExecute event handler
    globalOnBeforePageExecute();
    
    
    
    // OnBeforePageExecute event handler
    
    
    
    class kommission_v_in_kommissionPage extends DetailPage
    {
        protected function DoBeforeCreate()
        {
            $this->dataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`v_in_kommission`');
            $field = new IntegerField('id');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, true);
            $field = new IntegerField('parlamentarier_id');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, true);
            $field = new IntegerField('kommission_id');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, true);
            $field = new StringField('funktion');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, true);
            $field = new IntegerField('parlament_committee_function');
            $this->dataset->AddField($field, true);
            $field = new StringField('parlament_committee_function_name');
            $this->dataset->AddField($field, true);
            $field = new DateField('von');
            $this->dataset->AddField($field, true);
            $field = new DateField('bis');
            $this->dataset->AddField($field, true);
            $field = new StringField('notizen');
            $this->dataset->AddField($field, false);
            $field = new StringField('eingabe_abgeschlossen_visa');
            $this->dataset->AddField($field, true);
            $field = new DateTimeField('eingabe_abgeschlossen_datum');
            $this->dataset->AddField($field, true);
            $field = new StringField('kontrolliert_visa');
            $this->dataset->AddField($field, true);
            $field = new DateTimeField('kontrolliert_datum');
            $this->dataset->AddField($field, true);
            $field = new StringField('freigabe_visa');
            $this->dataset->AddField($field, true);
            $field = new DateTimeField('freigabe_datum');
            $this->dataset->AddField($field, true);
            $field = new StringField('created_visa');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, true);
            $field = new DateTimeField('created_date');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, true);
            $field = new StringField('updated_visa');
            $this->dataset->AddField($field, true);
            $field = new DateTimeField('updated_date');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, true);
            $field = new IntegerField('bis_unix');
            $this->dataset->AddField($field, true);
            $field = new IntegerField('von_unix');
            $this->dataset->AddField($field, true);
            $field = new IntegerField('created_date_unix');
            $this->dataset->AddField($field, true);
            $field = new IntegerField('updated_date_unix');
            $this->dataset->AddField($field, true);
            $field = new IntegerField('eingabe_abgeschlossen_datum_unix');
            $this->dataset->AddField($field, true);
            $field = new IntegerField('kontrolliert_datum_unix');
            $this->dataset->AddField($field, true);
            $field = new IntegerField('freigabe_datum_unix');
            $this->dataset->AddField($field, true);
            $field = new StringField('rat');
            $this->dataset->AddField($field, true);
            $field = new StringField('rat_de');
            $this->dataset->AddField($field, true);
            $field = new StringField('rat_fr');
            $this->dataset->AddField($field, true);
            $field = new StringField('rat_mixed');
            $this->dataset->AddField($field, true);
            $field = new StringField('ratstyp');
            $this->dataset->AddField($field, true);
            $field = new StringField('kommission_abkuerzung');
            $this->dataset->AddField($field, true);
            $field = new StringField('kommission_name');
            $this->dataset->AddField($field, true);
            $field = new StringField('kommission_abkuerzung_de');
            $this->dataset->AddField($field, true);
            $field = new StringField('kommission_name_de');
            $this->dataset->AddField($field, true);
            $field = new StringField('kommission_abkuerzung_fr');
            $this->dataset->AddField($field, true);
            $field = new StringField('kommission_name_fr');
            $this->dataset->AddField($field, true);
            $field = new StringField('kommission_abkuerzung_mixed');
            $this->dataset->AddField($field, true);
            $field = new StringField('kommission_name_mixed');
            $this->dataset->AddField($field, false);
            $field = new StringField('kommission_art');
            $this->dataset->AddField($field, true);
            $field = new StringField('kommission_typ');
            $this->dataset->AddField($field, true);
            $field = new StringField('kommission_beschreibung');
            $this->dataset->AddField($field, false);
            $field = new StringField('kommission_sachbereiche');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('kommission_mutter_kommission_id');
            $this->dataset->AddField($field, true);
            $field = new StringField('kommission_parlament_url');
            $this->dataset->AddField($field, false);
            $this->dataset->AddLookupField('parlamentarier_id', 'v_parlamentarier_simple', new IntegerField('id'), new StringField('anzeige_name', 'parlamentarier_id_anzeige_name', 'parlamentarier_id_anzeige_name_v_parlamentarier_simple'), 'parlamentarier_id_anzeige_name_v_parlamentarier_simple');
            $this->dataset->AddLookupField('kommission_id', 'v_kommission', new IntegerField('id'), new StringField('anzeige_name', 'kommission_id_anzeige_name', 'kommission_id_anzeige_name_v_kommission'), 'kommission_id_anzeige_name_v_kommission');
        }
    
        protected function DoPrepare() {
            globalOnPreparePage($this);
        }
    
        protected function CreatePageNavigator()
        {
            $result = new CompositePageNavigator($this);
            
            $partitionNavigator = new PageNavigator('pnav', $this, $this->dataset);
            $partitionNavigator->SetRowsPerPage(100);
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
                new FilterColumn($this->dataset, 'id', 'id', $this->RenderText('Id')),
                new FilterColumn($this->dataset, 'parlamentarier_id', 'parlamentarier_id_anzeige_name', $this->RenderText('Parlamentarier')),
                new FilterColumn($this->dataset, 'kommission_id', 'kommission_id_anzeige_name', $this->RenderText('Kommission')),
                new FilterColumn($this->dataset, 'ratstyp', 'ratstyp', $this->RenderText('Ratstyp')),
                new FilterColumn($this->dataset, 'funktion', 'funktion', $this->RenderText('Funktion')),
                new FilterColumn($this->dataset, 'von', 'von', $this->RenderText('Von')),
                new FilterColumn($this->dataset, 'bis', 'bis', $this->RenderText('Bis')),
                new FilterColumn($this->dataset, 'notizen', 'notizen', $this->RenderText('Notizen')),
                new FilterColumn($this->dataset, 'eingabe_abgeschlossen_visa', 'eingabe_abgeschlossen_visa', $this->RenderText('Eingabe Abgeschlossen Visa')),
                new FilterColumn($this->dataset, 'eingabe_abgeschlossen_datum', 'eingabe_abgeschlossen_datum', $this->RenderText('Eingabe Abgeschlossen Datum')),
                new FilterColumn($this->dataset, 'kontrolliert_visa', 'kontrolliert_visa', $this->RenderText('Kontrolliert Visa')),
                new FilterColumn($this->dataset, 'kontrolliert_datum', 'kontrolliert_datum', $this->RenderText('Kontrolliert Datum')),
                new FilterColumn($this->dataset, 'freigabe_visa', 'freigabe_visa', $this->RenderText('Freigabe Visa')),
                new FilterColumn($this->dataset, 'freigabe_datum', 'freigabe_datum', $this->RenderText('Freigabe Datum')),
                new FilterColumn($this->dataset, 'created_visa', 'created_visa', $this->RenderText('Created Visa')),
                new FilterColumn($this->dataset, 'created_date', 'created_date', $this->RenderText('Created Date')),
                new FilterColumn($this->dataset, 'updated_visa', 'updated_visa', $this->RenderText('Updated Visa')),
                new FilterColumn($this->dataset, 'updated_date', 'updated_date', $this->RenderText('Updated Date')),
                new FilterColumn($this->dataset, 'rat', 'rat', $this->RenderText('Rat')),
                new FilterColumn($this->dataset, 'kommission_abkuerzung', 'kommission_abkuerzung', $this->RenderText('Kommission Abkuerzung')),
                new FilterColumn($this->dataset, 'kommission_name', 'kommission_name', $this->RenderText('Kommission Name')),
                new FilterColumn($this->dataset, 'kommission_art', 'kommission_art', $this->RenderText('Kommission Art')),
                new FilterColumn($this->dataset, 'kommission_typ', 'kommission_typ', $this->RenderText('Kommission Typ')),
                new FilterColumn($this->dataset, 'kommission_beschreibung', 'kommission_beschreibung', $this->RenderText('Kommission Beschreibung')),
                new FilterColumn($this->dataset, 'kommission_sachbereiche', 'kommission_sachbereiche', $this->RenderText('Kommission Sachbereiche')),
                new FilterColumn($this->dataset, 'kommission_mutter_kommission_id', 'kommission_mutter_kommission_id', $this->RenderText('Kommission Mutter Kommission Id')),
                new FilterColumn($this->dataset, 'kommission_parlament_url', 'kommission_parlament_url', $this->RenderText('Kommission Parlament Url')),
                new FilterColumn($this->dataset, 'bis_unix', 'bis_unix', $this->RenderText('Bis Unix')),
                new FilterColumn($this->dataset, 'von_unix', 'von_unix', $this->RenderText('Von Unix')),
                new FilterColumn($this->dataset, 'created_date_unix', 'created_date_unix', $this->RenderText('Created Date Unix')),
                new FilterColumn($this->dataset, 'updated_date_unix', 'updated_date_unix', $this->RenderText('Updated Date Unix')),
                new FilterColumn($this->dataset, 'eingabe_abgeschlossen_datum_unix', 'eingabe_abgeschlossen_datum_unix', $this->RenderText('Eingabe Abgeschlossen Datum Unix')),
                new FilterColumn($this->dataset, 'kontrolliert_datum_unix', 'kontrolliert_datum_unix', $this->RenderText('Kontrolliert Datum Unix')),
                new FilterColumn($this->dataset, 'freigabe_datum_unix', 'freigabe_datum_unix', $this->RenderText('Freigabe Datum Unix')),
                new FilterColumn($this->dataset, 'parlament_committee_function', 'parlament_committee_function', $this->RenderText('Parlament Committee Function')),
                new FilterColumn($this->dataset, 'parlament_committee_function_name', 'parlament_committee_function_name', $this->RenderText('Parlament Committee Function Name')),
                new FilterColumn($this->dataset, 'rat_de', 'rat_de', $this->RenderText('Rat De')),
                new FilterColumn($this->dataset, 'rat_fr', 'rat_fr', $this->RenderText('Rat Fr')),
                new FilterColumn($this->dataset, 'rat_mixed', 'rat_mixed', $this->RenderText('Rat Mixed')),
                new FilterColumn($this->dataset, 'kommission_abkuerzung_de', 'kommission_abkuerzung_de', $this->RenderText('Kommission Abkuerzung De')),
                new FilterColumn($this->dataset, 'kommission_name_de', 'kommission_name_de', $this->RenderText('Kommission Name De')),
                new FilterColumn($this->dataset, 'kommission_abkuerzung_fr', 'kommission_abkuerzung_fr', $this->RenderText('Kommission Abkuerzung Fr')),
                new FilterColumn($this->dataset, 'kommission_name_fr', 'kommission_name_fr', $this->RenderText('Kommission Name Fr')),
                new FilterColumn($this->dataset, 'kommission_abkuerzung_mixed', 'kommission_abkuerzung_mixed', $this->RenderText('Kommission Abkuerzung Mixed')),
                new FilterColumn($this->dataset, 'kommission_name_mixed', 'kommission_name_mixed', $this->RenderText('Kommission Name Mixed'))
            );
        }
    
        protected function setupQuickFilter(QuickFilter $quickFilter, FixedKeysArray $columns)
        {
            $quickFilter
                ->addColumn($columns['id'])
                ->addColumn($columns['parlamentarier_id'])
                ->addColumn($columns['kommission_id'])
                ->addColumn($columns['ratstyp'])
                ->addColumn($columns['funktion'])
                ->addColumn($columns['von'])
                ->addColumn($columns['bis'])
                ->addColumn($columns['notizen'])
                ->addColumn($columns['eingabe_abgeschlossen_visa'])
                ->addColumn($columns['eingabe_abgeschlossen_datum'])
                ->addColumn($columns['kontrolliert_visa'])
                ->addColumn($columns['kontrolliert_datum'])
                ->addColumn($columns['freigabe_visa'])
                ->addColumn($columns['freigabe_datum'])
                ->addColumn($columns['created_visa'])
                ->addColumn($columns['created_date'])
                ->addColumn($columns['updated_visa'])
                ->addColumn($columns['updated_date'])
                ->addColumn($columns['parlament_committee_function'])
                ->addColumn($columns['parlament_committee_function_name'])
                ->addColumn($columns['rat_de'])
                ->addColumn($columns['rat_fr'])
                ->addColumn($columns['rat_mixed'])
                ->addColumn($columns['kommission_abkuerzung_de'])
                ->addColumn($columns['kommission_name_de'])
                ->addColumn($columns['kommission_abkuerzung_fr'])
                ->addColumn($columns['kommission_name_fr'])
                ->addColumn($columns['kommission_abkuerzung_mixed'])
                ->addColumn($columns['kommission_name_mixed']);
        }
    
        protected function setupColumnFilter(ColumnFilter $columnFilter)
        {
            $columnFilter
                ->setOptionsFor('parlamentarier_id')
                ->setOptionsFor('kommission_id')
                ->setOptionsFor('funktion')
                ->setOptionsFor('von')
                ->setOptionsFor('bis')
                ->setOptionsFor('eingabe_abgeschlossen_datum')
                ->setOptionsFor('kontrolliert_datum')
                ->setOptionsFor('freigabe_datum')
                ->setOptionsFor('created_date')
                ->setOptionsFor('updated_date');
        }
    
        protected function setupFilterBuilder(FilterBuilder $filterBuilder, FixedKeysArray $columns)
        {
            $main_editor = new TextEdit('id_edit');
            
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
            
            $main_editor = new AutocompleteComboBox('parlamentarier_id_edit', $this->CreateLinkBuilder());
            $main_editor->setAllowClear(true);
            $main_editor->setMinimumInputLength(0);
            $main_editor->SetAllowNullValue(false);
            $main_editor->SetHandlerName('filter_builder_parlamentarier_id_anzeige_name_search');
            
            $multi_value_select_editor = new RemoteMultiValueSelect('parlamentarier_id', $this->CreateLinkBuilder());
            $multi_value_select_editor->SetHandlerName('filter_builder_parlamentarier_id_anzeige_name_search');
            
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
            
            $main_editor = new AutocompleteComboBox('kommission_id_edit', $this->CreateLinkBuilder());
            $main_editor->setAllowClear(true);
            $main_editor->setMinimumInputLength(0);
            $main_editor->SetAllowNullValue(false);
            $main_editor->SetHandlerName('filter_builder_kommission_id_anzeige_name_search');
            
            $multi_value_select_editor = new RemoteMultiValueSelect('kommission_id', $this->CreateLinkBuilder());
            $multi_value_select_editor->SetHandlerName('filter_builder_kommission_id_anzeige_name_search');
            
            $text_editor = new TextEdit('kommission_id');
            
            $filterBuilder->addColumn(
                $columns['kommission_id'],
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
            
            $main_editor = new ComboBox('ratstyp_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $main_editor->addChoice($this->RenderText('NR'), $this->RenderText('NR'));
            $main_editor->addChoice($this->RenderText('SR'), $this->RenderText('SR'));
            $main_editor->SetAllowNullValue(false);
            
            $multi_value_select_editor = new MultiValueSelect('ratstyp');
            $multi_value_select_editor->setChoices($main_editor->getChoices());
            
            $text_editor = new TextEdit('ratstyp');
            
            $filterBuilder->addColumn(
                $columns['ratstyp'],
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
            
            $main_editor = new ComboBox('funktion_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $main_editor->addChoice($this->RenderText('praesident'), $this->RenderText('praesident'));
            $main_editor->addChoice($this->RenderText('vizepraesident'), $this->RenderText('vizepraesident'));
            $main_editor->addChoice($this->RenderText('mitglied'), $this->RenderText('mitglied'));
            $main_editor->SetAllowNullValue(false);
            
            $multi_value_select_editor = new MultiValueSelect('funktion');
            $multi_value_select_editor->setChoices($main_editor->getChoices());
            
            $text_editor = new TextEdit('funktion');
            
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
            
            $main_editor = new DateTimeEdit('von_edit', false, 'Y-m-d H:i:s');
            
            $filterBuilder->addColumn(
                $columns['von'],
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
            
            $main_editor = new DateTimeEdit('bis_edit', false, 'Y-m-d H:i:s');
            
            $filterBuilder->addColumn(
                $columns['bis'],
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
            
            $main_editor = new TextEdit('notizen');
            
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
            
            $main_editor = new TextEdit('eingabe_abgeschlossen_visa_edit');
            $main_editor->SetMaxLength(10);
            
            $filterBuilder->addColumn(
                $columns['eingabe_abgeschlossen_visa'],
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
            
            $main_editor = new DateTimeEdit('eingabe_abgeschlossen_datum_edit', false, 'Y-m-d H:i:s');
            
            $filterBuilder->addColumn(
                $columns['eingabe_abgeschlossen_datum'],
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
            
            $main_editor = new TextEdit('kontrolliert_visa_edit');
            $main_editor->SetMaxLength(10);
            
            $filterBuilder->addColumn(
                $columns['kontrolliert_visa'],
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
            
            $main_editor = new DateTimeEdit('kontrolliert_datum_edit', false, 'Y-m-d H:i:s');
            
            $filterBuilder->addColumn(
                $columns['kontrolliert_datum'],
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
            
            $main_editor = new TextEdit('freigabe_visa_edit');
            $main_editor->SetMaxLength(10);
            
            $filterBuilder->addColumn(
                $columns['freigabe_visa'],
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
            
            $main_editor = new DateTimeEdit('freigabe_datum_edit', false, 'Y-m-d H:i:s');
            
            $filterBuilder->addColumn(
                $columns['freigabe_datum'],
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
            
            $main_editor = new TextEdit('created_visa_edit');
            $main_editor->SetMaxLength(10);
            
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
            $main_editor->SetMaxLength(10);
            
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
            
            $main_editor = new TextEdit('parlament_committee_function_edit');
            
            $filterBuilder->addColumn(
                $columns['parlament_committee_function'],
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
            
            $main_editor = new TextEdit('parlament_committee_function_name_edit');
            $main_editor->SetMaxLength(40);
            
            $filterBuilder->addColumn(
                $columns['parlament_committee_function_name'],
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
            
            $main_editor = new TextEdit('rat_de_edit');
            $main_editor->SetMaxLength(10);
            
            $filterBuilder->addColumn(
                $columns['rat_de'],
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
            
            $main_editor = new TextEdit('rat_fr_edit');
            $main_editor->SetMaxLength(10);
            
            $filterBuilder->addColumn(
                $columns['rat_fr'],
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
            
            $main_editor = new TextEdit('rat_mixed_edit');
            $main_editor->SetMaxLength(23);
            
            $filterBuilder->addColumn(
                $columns['rat_mixed'],
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
            
            $main_editor = new TextEdit('kommission_abkuerzung_de_edit');
            $main_editor->SetMaxLength(15);
            
            $filterBuilder->addColumn(
                $columns['kommission_abkuerzung_de'],
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
            
            $main_editor = new TextEdit('kommission_name_de_edit');
            $main_editor->SetMaxLength(100);
            
            $filterBuilder->addColumn(
                $columns['kommission_name_de'],
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
            
            $main_editor = new TextEdit('kommission_abkuerzung_fr_edit');
            $main_editor->SetMaxLength(15);
            
            $filterBuilder->addColumn(
                $columns['kommission_abkuerzung_fr'],
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
            
            $main_editor = new TextEdit('kommission_name_fr_edit');
            $main_editor->SetMaxLength(100);
            
            $filterBuilder->addColumn(
                $columns['kommission_name_fr'],
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
            
            $main_editor = new TextEdit('kommission_abkuerzung_mixed_edit');
            $main_editor->SetMaxLength(33);
            
            $filterBuilder->addColumn(
                $columns['kommission_abkuerzung_mixed'],
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
            
            $main_editor = new TextEdit('kommission_name_mixed');
            
            $filterBuilder->addColumn(
                $columns['kommission_name_mixed'],
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
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for anzeige_name field
            //
            $column = new TextViewColumn('parlamentarier_id', 'parlamentarier_id_anzeige_name', 'Parlamentarier', $this->dataset);
            $column->SetOrderable(true);
            $column->setHrefTemplate('parlamentarier.php?operation=view&pk0=%parlamentarier_id%');
            $column->setTarget('_self');
            $column->setBold(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for anzeige_name field
            //
            $column = new TextViewColumn('kommission_id', 'kommission_id_anzeige_name', 'Kommission', $this->dataset);
            $column->SetOrderable(true);
            $column->setHrefTemplate('parlamentarier.php?operation=view&pk0=%kommission_id%');
            $column->setTarget('_self');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for ratstyp field
            //
            $column = new TextViewColumn('ratstyp', 'ratstyp', 'Ratstyp', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for funktion field
            //
            $column = new TextViewColumn('funktion', 'funktion', 'Funktion', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for von field
            //
            $column = new DateTimeViewColumn('von', 'von', 'Von', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y');
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for bis field
            //
            $column = new DateTimeViewColumn('bis', 'bis', 'Bis', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y');
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.v_in_kommission_notizen_handler_list');
            $column->SetReplaceLFByBR(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_visa field
            //
            $column = new TextViewColumn('eingabe_abgeschlossen_visa', 'eingabe_abgeschlossen_visa', 'Eingabe Abgeschlossen Visa', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_datum field
            //
            $column = new DateTimeViewColumn('eingabe_abgeschlossen_datum', 'eingabe_abgeschlossen_datum', 'Eingabe Abgeschlossen Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for kontrolliert_visa field
            //
            $column = new TextViewColumn('kontrolliert_visa', 'kontrolliert_visa', 'Kontrolliert Visa', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for kontrolliert_datum field
            //
            $column = new DateTimeViewColumn('kontrolliert_datum', 'kontrolliert_datum', 'Kontrolliert Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for freigabe_visa field
            //
            $column = new TextViewColumn('freigabe_visa', 'freigabe_visa', 'Freigabe Visa', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for freigabe_datum field
            //
            $column = new DateTimeViewColumn('freigabe_datum', 'freigabe_datum', 'Freigabe Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for created_visa field
            //
            $column = new TextViewColumn('created_visa', 'created_visa', 'Created Visa', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for created_date field
            //
            $column = new DateTimeViewColumn('created_date', 'created_date', 'Created Date', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for updated_visa field
            //
            $column = new TextViewColumn('updated_visa', 'updated_visa', 'Updated Visa', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for updated_date field
            //
            $column = new DateTimeViewColumn('updated_date', 'updated_date', 'Updated Date', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for parlament_committee_function field
            //
            $column = new NumberViewColumn('parlament_committee_function', 'parlament_committee_function', 'Parlament Committee Function', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator('\'');
            $column->setDecimalSeparator('');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for parlament_committee_function_name field
            //
            $column = new TextViewColumn('parlament_committee_function_name', 'parlament_committee_function_name', 'Parlament Committee Function Name', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for rat_de field
            //
            $column = new TextViewColumn('rat_de', 'rat_de', 'Rat De', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for rat_fr field
            //
            $column = new TextViewColumn('rat_fr', 'rat_fr', 'Rat Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for rat_mixed field
            //
            $column = new TextViewColumn('rat_mixed', 'rat_mixed', 'Rat Mixed', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for kommission_abkuerzung_de field
            //
            $column = new TextViewColumn('kommission_abkuerzung_de', 'kommission_abkuerzung_de', 'Kommission Abkuerzung De', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for kommission_name_de field
            //
            $column = new TextViewColumn('kommission_name_de', 'kommission_name_de', 'Kommission Name De', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.v_in_kommission_kommission_name_de_handler_list');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for kommission_abkuerzung_fr field
            //
            $column = new TextViewColumn('kommission_abkuerzung_fr', 'kommission_abkuerzung_fr', 'Kommission Abkuerzung Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for kommission_name_fr field
            //
            $column = new TextViewColumn('kommission_name_fr', 'kommission_name_fr', 'Kommission Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.v_in_kommission_kommission_name_fr_handler_list');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for kommission_abkuerzung_mixed field
            //
            $column = new TextViewColumn('kommission_abkuerzung_mixed', 'kommission_abkuerzung_mixed', 'Kommission Abkuerzung Mixed', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for kommission_name_mixed field
            //
            $column = new TextViewColumn('kommission_name_mixed', 'kommission_name_mixed', 'Kommission Name Mixed', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.v_in_kommission_kommission_name_mixed_handler_list');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
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
            $column->setHrefTemplate('parlamentarier.php?operation=view&pk0=%parlamentarier_id%');
            $column->setTarget('_self');
            $column->setBold(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for anzeige_name field
            //
            $column = new TextViewColumn('kommission_id', 'kommission_id_anzeige_name', 'Kommission', $this->dataset);
            $column->SetOrderable(true);
            $column->setHrefTemplate('parlamentarier.php?operation=view&pk0=%kommission_id%');
            $column->setTarget('_self');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for ratstyp field
            //
            $column = new TextViewColumn('ratstyp', 'ratstyp', 'Ratstyp', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for funktion field
            //
            $column = new TextViewColumn('funktion', 'funktion', 'Funktion', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for von field
            //
            $column = new DateTimeViewColumn('von', 'von', 'Von', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for bis field
            //
            $column = new DateTimeViewColumn('bis', 'bis', 'Bis', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.v_in_kommission_notizen_handler_view');
            $column->SetReplaceLFByBR(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_visa field
            //
            $column = new TextViewColumn('eingabe_abgeschlossen_visa', 'eingabe_abgeschlossen_visa', 'Eingabe Abgeschlossen Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_datum field
            //
            $column = new DateTimeViewColumn('eingabe_abgeschlossen_datum', 'eingabe_abgeschlossen_datum', 'Eingabe Abgeschlossen Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for kontrolliert_visa field
            //
            $column = new TextViewColumn('kontrolliert_visa', 'kontrolliert_visa', 'Kontrolliert Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for kontrolliert_datum field
            //
            $column = new DateTimeViewColumn('kontrolliert_datum', 'kontrolliert_datum', 'Kontrolliert Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for freigabe_visa field
            //
            $column = new TextViewColumn('freigabe_visa', 'freigabe_visa', 'Freigabe Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for freigabe_datum field
            //
            $column = new DateTimeViewColumn('freigabe_datum', 'freigabe_datum', 'Freigabe Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
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
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
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
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for parlament_committee_function field
            //
            $column = new NumberViewColumn('parlament_committee_function', 'parlament_committee_function', 'Parlament Committee Function', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator('\'');
            $column->setDecimalSeparator('');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for parlament_committee_function_name field
            //
            $column = new TextViewColumn('parlament_committee_function_name', 'parlament_committee_function_name', 'Parlament Committee Function Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for rat_de field
            //
            $column = new TextViewColumn('rat_de', 'rat_de', 'Rat De', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for rat_fr field
            //
            $column = new TextViewColumn('rat_fr', 'rat_fr', 'Rat Fr', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for rat_mixed field
            //
            $column = new TextViewColumn('rat_mixed', 'rat_mixed', 'Rat Mixed', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for kommission_abkuerzung_de field
            //
            $column = new TextViewColumn('kommission_abkuerzung_de', 'kommission_abkuerzung_de', 'Kommission Abkuerzung De', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for kommission_name_de field
            //
            $column = new TextViewColumn('kommission_name_de', 'kommission_name_de', 'Kommission Name De', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.v_in_kommission_kommission_name_de_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for kommission_abkuerzung_fr field
            //
            $column = new TextViewColumn('kommission_abkuerzung_fr', 'kommission_abkuerzung_fr', 'Kommission Abkuerzung Fr', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for kommission_name_fr field
            //
            $column = new TextViewColumn('kommission_name_fr', 'kommission_name_fr', 'Kommission Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.v_in_kommission_kommission_name_fr_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for kommission_abkuerzung_mixed field
            //
            $column = new TextViewColumn('kommission_abkuerzung_mixed', 'kommission_abkuerzung_mixed', 'Kommission Abkuerzung Mixed', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for kommission_name_mixed field
            //
            $column = new TextViewColumn('kommission_name_mixed', 'kommission_name_mixed', 'Kommission Name Mixed', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.v_in_kommission_kommission_name_mixed_handler_view');
            $grid->AddSingleRecordViewColumn($column);
        }
    
        protected function AddEditColumns(Grid $grid)
        {
            //
            // Edit column for parlamentarier_id field
            //
            $editor = new ComboBox('parlamentarier_id_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`v_parlamentarier_simple`');
            $field = new StringField('anzeige_name');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_fr');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('name');
            $lookupDataset->AddField($field, false);
            $field = new StringField('name_de');
            $lookupDataset->AddField($field, false);
            $field = new StringField('name_fr');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('id');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('nachname');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('vorname');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('zweiter_vorname');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('rat_id');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('kanton_id');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('kommissionen');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('partei_id');
            $lookupDataset->AddField($field, false);
            $field = new StringField('parteifunktion');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('fraktion_id');
            $lookupDataset->AddField($field, false);
            $field = new StringField('fraktionsfunktion');
            $lookupDataset->AddField($field, false);
            $field = new DateField('im_rat_seit');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new DateField('im_rat_bis');
            $lookupDataset->AddField($field, false);
            $field = new DateField('ratswechsel');
            $lookupDataset->AddField($field, false);
            $field = new DateField('ratsunterbruch_von');
            $lookupDataset->AddField($field, false);
            $field = new DateField('ratsunterbruch_bis');
            $lookupDataset->AddField($field, false);
            $field = new StringField('beruf');
            $lookupDataset->AddField($field, false);
            $field = new StringField('beruf_fr');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('beruf_interessengruppe_id');
            $lookupDataset->AddField($field, false);
            $field = new StringField('titel');
            $lookupDataset->AddField($field, false);
            $field = new StringField('aemter');
            $lookupDataset->AddField($field, false);
            $field = new StringField('weitere_aemter');
            $lookupDataset->AddField($field, false);
            $field = new StringField('zivilstand');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_kinder');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('militaerischer_grad_id');
            $lookupDataset->AddField($field, false);
            $field = new StringField('geschlecht');
            $lookupDataset->AddField($field, false);
            $field = new DateField('geburtstag');
            $lookupDataset->AddField($field, false);
            $field = new StringField('photo');
            $lookupDataset->AddField($field, false);
            $field = new StringField('photo_dateiname');
            $lookupDataset->AddField($field, false);
            $field = new StringField('photo_dateierweiterung');
            $lookupDataset->AddField($field, false);
            $field = new StringField('photo_dateiname_voll');
            $lookupDataset->AddField($field, false);
            $field = new StringField('photo_mime_type');
            $lookupDataset->AddField($field, false);
            $field = new StringField('kleinbild');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('sitzplatz');
            $lookupDataset->AddField($field, false);
            $field = new StringField('email');
            $lookupDataset->AddField($field, false);
            $field = new StringField('homepage');
            $lookupDataset->AddField($field, false);
            $field = new StringField('homepage_2');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('parlament_biografie_id');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('parlament_number');
            $lookupDataset->AddField($field, false);
            $field = new StringField('parlament_interessenbindungen');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('parlament_interessenbindungen_updated');
            $lookupDataset->AddField($field, false);
            $field = new StringField('twitter_name');
            $lookupDataset->AddField($field, false);
            $field = new StringField('linkedin_profil_url');
            $lookupDataset->AddField($field, false);
            $field = new StringField('xing_profil_name');
            $lookupDataset->AddField($field, false);
            $field = new StringField('facebook_name');
            $lookupDataset->AddField($field, false);
            $field = new StringField('wikipedia');
            $lookupDataset->AddField($field, false);
            $field = new StringField('sprache');
            $lookupDataset->AddField($field, false);
            $field = new StringField('arbeitssprache');
            $lookupDataset->AddField($field, false);
            $field = new StringField('adresse_firma');
            $lookupDataset->AddField($field, false);
            $field = new StringField('adresse_strasse');
            $lookupDataset->AddField($field, false);
            $field = new StringField('adresse_zusatz');
            $lookupDataset->AddField($field, false);
            $field = new StringField('adresse_plz');
            $lookupDataset->AddField($field, false);
            $field = new StringField('adresse_ort');
            $lookupDataset->AddField($field, false);
            $field = new StringField('telephon_1');
            $lookupDataset->AddField($field, false);
            $field = new StringField('telephon_2');
            $lookupDataset->AddField($field, false);
            $field = new StringField('erfasst');
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
            $field = new StringField('autorisierung_verschickt_visa');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('autorisierung_verschickt_datum');
            $lookupDataset->AddField($field, false);
            $field = new StringField('autorisiert_visa');
            $lookupDataset->AddField($field, false);
            $field = new DateField('autorisiert_datum');
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
            $field = new StringField('beruf_de');
            $lookupDataset->AddField($field, false);
            $field = new DateField('von');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new DateField('bis');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('geburtstag_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('im_rat_seit_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('im_rat_bis_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('created_date_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('updated_date_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('eingabe_abgeschlossen_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('kontrolliert_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('freigabe_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('von_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('bis_unix');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('anzeige_name', GetOrderTypeAsSQL(otAscending));
            $editColumn = new LookUpEditColumn(
                'Parlamentarier', 
                'parlamentarier_id', 
                $editor, 
                $this->dataset, 'id', 'anzeige_name', $lookupDataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for kommission_id field
            //
            $editor = new ComboBox('kommission_id_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
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
            $field = new IntegerField('anzahl_mitglieder');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_nationalraete');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_staenderaete');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('mutter_kommission_id');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('zweitrat_kommission_id');
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
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('updated_date_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('eingabe_abgeschlossen_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('kontrolliert_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('freigabe_datum_unix');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('anzeige_name', GetOrderTypeAsSQL(otAscending));
            $editColumn = new LookUpEditColumn(
                'Kommission', 
                'kommission_id', 
                $editor, 
                $this->dataset, 'id', 'anzeige_name', $lookupDataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for ratstyp field
            //
            $editor = new ComboBox('ratstyp_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $editor->addChoice($this->RenderText('NR'), $this->RenderText('NR'));
            $editor->addChoice($this->RenderText('SR'), $this->RenderText('SR'));
            $editColumn = new CustomEditColumn('Ratstyp', 'ratstyp', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for funktion field
            //
            $editor = new ComboBox('funktion_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $editor->addChoice($this->RenderText('praesident'), $this->RenderText('praesident'));
            $editor->addChoice($this->RenderText('vizepraesident'), $this->RenderText('vizepraesident'));
            $editor->addChoice($this->RenderText('mitglied'), $this->RenderText('mitglied'));
            $editColumn = new CustomEditColumn('Funktion', 'funktion', $editor, $this->dataset);
            $editColumn->SetAllowSetToDefault(false); /*afterburner*/ 
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for von field
            //
            $editor = new DateTimeEdit('von_edit', false, 'Y-m-d H:i:s');
            $editColumn = new CustomEditColumn('Von', 'von', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for bis field
            //
            $editor = new DateTimeEdit('bis_edit', false, 'Y-m-d H:i:s');
            $editColumn = new CustomEditColumn('Bis', 'bis', $editor, $this->dataset);
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
            // Edit column for parlament_committee_function field
            //
            $editor = new TextEdit('parlament_committee_function_edit');
            $editColumn = new CustomEditColumn('Parlament Committee Function', 'parlament_committee_function', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for parlament_committee_function_name field
            //
            $editor = new TextEdit('parlament_committee_function_name_edit');
            $editor->SetMaxLength(40);
            $editColumn = new CustomEditColumn('Parlament Committee Function Name', 'parlament_committee_function_name', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for rat_de field
            //
            $editor = new TextEdit('rat_de_edit');
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Rat De', 'rat_de', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for rat_fr field
            //
            $editor = new TextEdit('rat_fr_edit');
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Rat Fr', 'rat_fr', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for rat_mixed field
            //
            $editor = new TextEdit('rat_mixed_edit');
            $editor->SetMaxLength(23);
            $editColumn = new CustomEditColumn('Rat Mixed', 'rat_mixed', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for kommission_abkuerzung_de field
            //
            $editor = new TextEdit('kommission_abkuerzung_de_edit');
            $editor->SetMaxLength(15);
            $editColumn = new CustomEditColumn('Kommission Abkuerzung De', 'kommission_abkuerzung_de', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for kommission_name_de field
            //
            $editor = new TextEdit('kommission_name_de_edit');
            $editor->SetMaxLength(100);
            $editColumn = new CustomEditColumn('Kommission Name De', 'kommission_name_de', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for kommission_abkuerzung_fr field
            //
            $editor = new TextEdit('kommission_abkuerzung_fr_edit');
            $editor->SetMaxLength(15);
            $editColumn = new CustomEditColumn('Kommission Abkuerzung Fr', 'kommission_abkuerzung_fr', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for kommission_name_fr field
            //
            $editor = new TextEdit('kommission_name_fr_edit');
            $editor->SetMaxLength(100);
            $editColumn = new CustomEditColumn('Kommission Name Fr', 'kommission_name_fr', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for kommission_abkuerzung_mixed field
            //
            $editor = new TextEdit('kommission_abkuerzung_mixed_edit');
            $editor->SetMaxLength(33);
            $editColumn = new CustomEditColumn('Kommission Abkuerzung Mixed', 'kommission_abkuerzung_mixed', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for kommission_name_mixed field
            //
            $editor = new TextAreaEdit('kommission_name_mixed_edit', 50, 8);
            $editColumn = new CustomEditColumn('Kommission Name Mixed', 'kommission_name_mixed', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
        }
    
        protected function AddInsertColumns(Grid $grid)
        {
            //
            // Edit column for parlamentarier_id field
            //
            $editor = new ComboBox('parlamentarier_id_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`v_parlamentarier_simple`');
            $field = new StringField('anzeige_name');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_fr');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('name');
            $lookupDataset->AddField($field, false);
            $field = new StringField('name_de');
            $lookupDataset->AddField($field, false);
            $field = new StringField('name_fr');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('id');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('nachname');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('vorname');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('zweiter_vorname');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('rat_id');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('kanton_id');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('kommissionen');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('partei_id');
            $lookupDataset->AddField($field, false);
            $field = new StringField('parteifunktion');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('fraktion_id');
            $lookupDataset->AddField($field, false);
            $field = new StringField('fraktionsfunktion');
            $lookupDataset->AddField($field, false);
            $field = new DateField('im_rat_seit');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new DateField('im_rat_bis');
            $lookupDataset->AddField($field, false);
            $field = new DateField('ratswechsel');
            $lookupDataset->AddField($field, false);
            $field = new DateField('ratsunterbruch_von');
            $lookupDataset->AddField($field, false);
            $field = new DateField('ratsunterbruch_bis');
            $lookupDataset->AddField($field, false);
            $field = new StringField('beruf');
            $lookupDataset->AddField($field, false);
            $field = new StringField('beruf_fr');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('beruf_interessengruppe_id');
            $lookupDataset->AddField($field, false);
            $field = new StringField('titel');
            $lookupDataset->AddField($field, false);
            $field = new StringField('aemter');
            $lookupDataset->AddField($field, false);
            $field = new StringField('weitere_aemter');
            $lookupDataset->AddField($field, false);
            $field = new StringField('zivilstand');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_kinder');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('militaerischer_grad_id');
            $lookupDataset->AddField($field, false);
            $field = new StringField('geschlecht');
            $lookupDataset->AddField($field, false);
            $field = new DateField('geburtstag');
            $lookupDataset->AddField($field, false);
            $field = new StringField('photo');
            $lookupDataset->AddField($field, false);
            $field = new StringField('photo_dateiname');
            $lookupDataset->AddField($field, false);
            $field = new StringField('photo_dateierweiterung');
            $lookupDataset->AddField($field, false);
            $field = new StringField('photo_dateiname_voll');
            $lookupDataset->AddField($field, false);
            $field = new StringField('photo_mime_type');
            $lookupDataset->AddField($field, false);
            $field = new StringField('kleinbild');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('sitzplatz');
            $lookupDataset->AddField($field, false);
            $field = new StringField('email');
            $lookupDataset->AddField($field, false);
            $field = new StringField('homepage');
            $lookupDataset->AddField($field, false);
            $field = new StringField('homepage_2');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('parlament_biografie_id');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('parlament_number');
            $lookupDataset->AddField($field, false);
            $field = new StringField('parlament_interessenbindungen');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('parlament_interessenbindungen_updated');
            $lookupDataset->AddField($field, false);
            $field = new StringField('twitter_name');
            $lookupDataset->AddField($field, false);
            $field = new StringField('linkedin_profil_url');
            $lookupDataset->AddField($field, false);
            $field = new StringField('xing_profil_name');
            $lookupDataset->AddField($field, false);
            $field = new StringField('facebook_name');
            $lookupDataset->AddField($field, false);
            $field = new StringField('wikipedia');
            $lookupDataset->AddField($field, false);
            $field = new StringField('sprache');
            $lookupDataset->AddField($field, false);
            $field = new StringField('arbeitssprache');
            $lookupDataset->AddField($field, false);
            $field = new StringField('adresse_firma');
            $lookupDataset->AddField($field, false);
            $field = new StringField('adresse_strasse');
            $lookupDataset->AddField($field, false);
            $field = new StringField('adresse_zusatz');
            $lookupDataset->AddField($field, false);
            $field = new StringField('adresse_plz');
            $lookupDataset->AddField($field, false);
            $field = new StringField('adresse_ort');
            $lookupDataset->AddField($field, false);
            $field = new StringField('telephon_1');
            $lookupDataset->AddField($field, false);
            $field = new StringField('telephon_2');
            $lookupDataset->AddField($field, false);
            $field = new StringField('erfasst');
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
            $field = new StringField('autorisierung_verschickt_visa');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('autorisierung_verschickt_datum');
            $lookupDataset->AddField($field, false);
            $field = new StringField('autorisiert_visa');
            $lookupDataset->AddField($field, false);
            $field = new DateField('autorisiert_datum');
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
            $field = new StringField('beruf_de');
            $lookupDataset->AddField($field, false);
            $field = new DateField('von');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new DateField('bis');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('geburtstag_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('im_rat_seit_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('im_rat_bis_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('created_date_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('updated_date_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('eingabe_abgeschlossen_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('kontrolliert_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('freigabe_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('von_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('bis_unix');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('anzeige_name', GetOrderTypeAsSQL(otAscending));
            $editColumn = new LookUpEditColumn(
                'Parlamentarier', 
                'parlamentarier_id', 
                $editor, 
                $this->dataset, 'id', 'anzeige_name', $lookupDataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for kommission_id field
            //
            $editor = new ComboBox('kommission_id_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
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
            $field = new IntegerField('anzahl_mitglieder');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_nationalraete');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_staenderaete');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('mutter_kommission_id');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('zweitrat_kommission_id');
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
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('updated_date_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('eingabe_abgeschlossen_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('kontrolliert_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('freigabe_datum_unix');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('anzeige_name', GetOrderTypeAsSQL(otAscending));
            $editColumn = new LookUpEditColumn(
                'Kommission', 
                'kommission_id', 
                $editor, 
                $this->dataset, 'id', 'anzeige_name', $lookupDataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for ratstyp field
            //
            $editor = new ComboBox('ratstyp_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $editor->addChoice($this->RenderText('NR'), $this->RenderText('NR'));
            $editor->addChoice($this->RenderText('SR'), $this->RenderText('SR'));
            $editColumn = new CustomEditColumn('Ratstyp', 'ratstyp', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for funktion field
            //
            $editor = new ComboBox('funktion_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $editor->addChoice($this->RenderText('praesident'), $this->RenderText('praesident'));
            $editor->addChoice($this->RenderText('vizepraesident'), $this->RenderText('vizepraesident'));
            $editor->addChoice($this->RenderText('mitglied'), $this->RenderText('mitglied'));
            $editColumn = new CustomEditColumn('Funktion', 'funktion', $editor, $this->dataset);
            $editColumn->SetAllowSetToDefault(false); /*afterburner*/ 
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for von field
            //
            $editor = new DateTimeEdit('von_edit', false, 'Y-m-d H:i:s');
            $editColumn = new CustomEditColumn('Von', 'von', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for bis field
            //
            $editor = new DateTimeEdit('bis_edit', false, 'Y-m-d H:i:s');
            $editColumn = new CustomEditColumn('Bis', 'bis', $editor, $this->dataset);
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
            // Edit column for parlament_committee_function field
            //
            $editor = new TextEdit('parlament_committee_function_edit');
            $editColumn = new CustomEditColumn('Parlament Committee Function', 'parlament_committee_function', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for parlament_committee_function_name field
            //
            $editor = new TextEdit('parlament_committee_function_name_edit');
            $editor->SetMaxLength(40);
            $editColumn = new CustomEditColumn('Parlament Committee Function Name', 'parlament_committee_function_name', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for rat_de field
            //
            $editor = new TextEdit('rat_de_edit');
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Rat De', 'rat_de', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for rat_fr field
            //
            $editor = new TextEdit('rat_fr_edit');
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Rat Fr', 'rat_fr', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for rat_mixed field
            //
            $editor = new TextEdit('rat_mixed_edit');
            $editor->SetMaxLength(23);
            $editColumn = new CustomEditColumn('Rat Mixed', 'rat_mixed', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for kommission_abkuerzung_de field
            //
            $editor = new TextEdit('kommission_abkuerzung_de_edit');
            $editor->SetMaxLength(15);
            $editColumn = new CustomEditColumn('Kommission Abkuerzung De', 'kommission_abkuerzung_de', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for kommission_name_de field
            //
            $editor = new TextEdit('kommission_name_de_edit');
            $editor->SetMaxLength(100);
            $editColumn = new CustomEditColumn('Kommission Name De', 'kommission_name_de', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for kommission_abkuerzung_fr field
            //
            $editor = new TextEdit('kommission_abkuerzung_fr_edit');
            $editor->SetMaxLength(15);
            $editColumn = new CustomEditColumn('Kommission Abkuerzung Fr', 'kommission_abkuerzung_fr', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for kommission_name_fr field
            //
            $editor = new TextEdit('kommission_name_fr_edit');
            $editor->SetMaxLength(100);
            $editColumn = new CustomEditColumn('Kommission Name Fr', 'kommission_name_fr', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for kommission_abkuerzung_mixed field
            //
            $editor = new TextEdit('kommission_abkuerzung_mixed_edit');
            $editor->SetMaxLength(33);
            $editColumn = new CustomEditColumn('Kommission Abkuerzung Mixed', 'kommission_abkuerzung_mixed', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for kommission_name_mixed field
            //
            $editor = new TextAreaEdit('kommission_name_mixed_edit', 50, 8);
            $editColumn = new CustomEditColumn('Kommission Name Mixed', 'kommission_name_mixed', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            $grid->SetShowAddButton(false && $this->GetSecurityInfo()->HasAddGrant());
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
            $column->setHrefTemplate('parlamentarier.php?operation=view&pk0=%parlamentarier_id%');
            $column->setTarget('_self');
            $column->setBold(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for anzeige_name field
            //
            $column = new TextViewColumn('kommission_id', 'kommission_id_anzeige_name', 'Kommission', $this->dataset);
            $column->SetOrderable(true);
            $column->setHrefTemplate('parlamentarier.php?operation=view&pk0=%kommission_id%');
            $column->setTarget('_self');
            $grid->AddPrintColumn($column);
            
            //
            // View column for ratstyp field
            //
            $column = new TextViewColumn('ratstyp', 'ratstyp', 'Ratstyp', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for funktion field
            //
            $column = new TextViewColumn('funktion', 'funktion', 'Funktion', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for von field
            //
            $column = new DateTimeViewColumn('von', 'von', 'Von', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y');
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for bis field
            //
            $column = new DateTimeViewColumn('bis', 'bis', 'Bis', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y');
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.v_in_kommission_notizen_handler_print');
            $column->SetReplaceLFByBR(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_visa field
            //
            $column = new TextViewColumn('eingabe_abgeschlossen_visa', 'eingabe_abgeschlossen_visa', 'Eingabe Abgeschlossen Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_datum field
            //
            $column = new DateTimeViewColumn('eingabe_abgeschlossen_datum', 'eingabe_abgeschlossen_datum', 'Eingabe Abgeschlossen Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for kontrolliert_visa field
            //
            $column = new TextViewColumn('kontrolliert_visa', 'kontrolliert_visa', 'Kontrolliert Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for kontrolliert_datum field
            //
            $column = new DateTimeViewColumn('kontrolliert_datum', 'kontrolliert_datum', 'Kontrolliert Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for freigabe_visa field
            //
            $column = new TextViewColumn('freigabe_visa', 'freigabe_visa', 'Freigabe Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for freigabe_datum field
            //
            $column = new DateTimeViewColumn('freigabe_datum', 'freigabe_datum', 'Freigabe Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
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
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
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
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for parlament_committee_function field
            //
            $column = new NumberViewColumn('parlament_committee_function', 'parlament_committee_function', 'Parlament Committee Function', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator('\'');
            $column->setDecimalSeparator('');
            $grid->AddPrintColumn($column);
            
            //
            // View column for parlament_committee_function_name field
            //
            $column = new TextViewColumn('parlament_committee_function_name', 'parlament_committee_function_name', 'Parlament Committee Function Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for rat_de field
            //
            $column = new TextViewColumn('rat_de', 'rat_de', 'Rat De', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for rat_fr field
            //
            $column = new TextViewColumn('rat_fr', 'rat_fr', 'Rat Fr', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for rat_mixed field
            //
            $column = new TextViewColumn('rat_mixed', 'rat_mixed', 'Rat Mixed', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for kommission_abkuerzung_de field
            //
            $column = new TextViewColumn('kommission_abkuerzung_de', 'kommission_abkuerzung_de', 'Kommission Abkuerzung De', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for kommission_name_de field
            //
            $column = new TextViewColumn('kommission_name_de', 'kommission_name_de', 'Kommission Name De', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.v_in_kommission_kommission_name_de_handler_print');
            $grid->AddPrintColumn($column);
            
            //
            // View column for kommission_abkuerzung_fr field
            //
            $column = new TextViewColumn('kommission_abkuerzung_fr', 'kommission_abkuerzung_fr', 'Kommission Abkuerzung Fr', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for kommission_name_fr field
            //
            $column = new TextViewColumn('kommission_name_fr', 'kommission_name_fr', 'Kommission Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.v_in_kommission_kommission_name_fr_handler_print');
            $grid->AddPrintColumn($column);
            
            //
            // View column for kommission_abkuerzung_mixed field
            //
            $column = new TextViewColumn('kommission_abkuerzung_mixed', 'kommission_abkuerzung_mixed', 'Kommission Abkuerzung Mixed', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for kommission_name_mixed field
            //
            $column = new TextViewColumn('kommission_name_mixed', 'kommission_name_mixed', 'Kommission Name Mixed', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.v_in_kommission_kommission_name_mixed_handler_print');
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
            $column->setHrefTemplate('parlamentarier.php?operation=view&pk0=%parlamentarier_id%');
            $column->setTarget('_self');
            $column->setBold(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for anzeige_name field
            //
            $column = new TextViewColumn('kommission_id', 'kommission_id_anzeige_name', 'Kommission', $this->dataset);
            $column->SetOrderable(true);
            $column->setHrefTemplate('parlamentarier.php?operation=view&pk0=%kommission_id%');
            $column->setTarget('_self');
            $grid->AddExportColumn($column);
            
            //
            // View column for ratstyp field
            //
            $column = new TextViewColumn('ratstyp', 'ratstyp', 'Ratstyp', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for funktion field
            //
            $column = new TextViewColumn('funktion', 'funktion', 'Funktion', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for von field
            //
            $column = new DateTimeViewColumn('von', 'von', 'Von', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y');
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for bis field
            //
            $column = new DateTimeViewColumn('bis', 'bis', 'Bis', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y');
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.v_in_kommission_notizen_handler_export');
            $column->SetReplaceLFByBR(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_visa field
            //
            $column = new TextViewColumn('eingabe_abgeschlossen_visa', 'eingabe_abgeschlossen_visa', 'Eingabe Abgeschlossen Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_datum field
            //
            $column = new DateTimeViewColumn('eingabe_abgeschlossen_datum', 'eingabe_abgeschlossen_datum', 'Eingabe Abgeschlossen Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for kontrolliert_visa field
            //
            $column = new TextViewColumn('kontrolliert_visa', 'kontrolliert_visa', 'Kontrolliert Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for kontrolliert_datum field
            //
            $column = new DateTimeViewColumn('kontrolliert_datum', 'kontrolliert_datum', 'Kontrolliert Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for freigabe_visa field
            //
            $column = new TextViewColumn('freigabe_visa', 'freigabe_visa', 'Freigabe Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for freigabe_datum field
            //
            $column = new DateTimeViewColumn('freigabe_datum', 'freigabe_datum', 'Freigabe Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
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
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
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
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for parlament_committee_function field
            //
            $column = new NumberViewColumn('parlament_committee_function', 'parlament_committee_function', 'Parlament Committee Function', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator('\'');
            $column->setDecimalSeparator('');
            $grid->AddExportColumn($column);
            
            //
            // View column for parlament_committee_function_name field
            //
            $column = new TextViewColumn('parlament_committee_function_name', 'parlament_committee_function_name', 'Parlament Committee Function Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for rat_de field
            //
            $column = new TextViewColumn('rat_de', 'rat_de', 'Rat De', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for rat_fr field
            //
            $column = new TextViewColumn('rat_fr', 'rat_fr', 'Rat Fr', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for rat_mixed field
            //
            $column = new TextViewColumn('rat_mixed', 'rat_mixed', 'Rat Mixed', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for kommission_abkuerzung_de field
            //
            $column = new TextViewColumn('kommission_abkuerzung_de', 'kommission_abkuerzung_de', 'Kommission Abkuerzung De', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for kommission_name_de field
            //
            $column = new TextViewColumn('kommission_name_de', 'kommission_name_de', 'Kommission Name De', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.v_in_kommission_kommission_name_de_handler_export');
            $grid->AddExportColumn($column);
            
            //
            // View column for kommission_abkuerzung_fr field
            //
            $column = new TextViewColumn('kommission_abkuerzung_fr', 'kommission_abkuerzung_fr', 'Kommission Abkuerzung Fr', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for kommission_name_fr field
            //
            $column = new TextViewColumn('kommission_name_fr', 'kommission_name_fr', 'Kommission Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.v_in_kommission_kommission_name_fr_handler_export');
            $grid->AddExportColumn($column);
            
            //
            // View column for kommission_abkuerzung_mixed field
            //
            $column = new TextViewColumn('kommission_abkuerzung_mixed', 'kommission_abkuerzung_mixed', 'Kommission Abkuerzung Mixed', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for kommission_name_mixed field
            //
            $column = new TextViewColumn('kommission_name_mixed', 'kommission_name_mixed', 'Kommission Name Mixed', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.v_in_kommission_kommission_name_mixed_handler_export');
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
            $column->setHrefTemplate('parlamentarier.php?operation=view&pk0=%parlamentarier_id%');
            $column->setTarget('_self');
            $column->setBold(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for anzeige_name field
            //
            $column = new TextViewColumn('kommission_id', 'kommission_id_anzeige_name', 'Kommission', $this->dataset);
            $column->SetOrderable(true);
            $column->setHrefTemplate('parlamentarier.php?operation=view&pk0=%kommission_id%');
            $column->setTarget('_self');
            $grid->AddCompareColumn($column);
            
            //
            // View column for ratstyp field
            //
            $column = new TextViewColumn('ratstyp', 'ratstyp', 'Ratstyp', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for funktion field
            //
            $column = new TextViewColumn('funktion', 'funktion', 'Funktion', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for von field
            //
            $column = new DateTimeViewColumn('von', 'von', 'Von', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y');
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for bis field
            //
            $column = new DateTimeViewColumn('bis', 'bis', 'Bis', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y');
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.v_in_kommission_notizen_handler_compare');
            $column->SetReplaceLFByBR(true);
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
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
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
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
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
            $column->SetDateTimeFormat('d.m.Y H:i:s');
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
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
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
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for rat field
            //
            $column = new TextViewColumn('rat', 'rat', 'Rat', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for kommission_abkuerzung field
            //
            $column = new TextViewColumn('kommission_abkuerzung', 'kommission_abkuerzung', 'Kommission Abkuerzung', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for kommission_name field
            //
            $column = new TextViewColumn('kommission_name', 'kommission_name', 'Kommission Name', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.v_in_kommission_kommission_name_handler_compare');
            $grid->AddCompareColumn($column);
            
            //
            // View column for kommission_art field
            //
            $column = new TextViewColumn('kommission_art', 'kommission_art', 'Kommission Art', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for kommission_typ field
            //
            $column = new TextViewColumn('kommission_typ', 'kommission_typ', 'Kommission Typ', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for kommission_beschreibung field
            //
            $column = new TextViewColumn('kommission_beschreibung', 'kommission_beschreibung', 'Kommission Beschreibung', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.v_in_kommission_kommission_beschreibung_handler_compare');
            $grid->AddCompareColumn($column);
            
            //
            // View column for kommission_sachbereiche field
            //
            $column = new TextViewColumn('kommission_sachbereiche', 'kommission_sachbereiche', 'Kommission Sachbereiche', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.v_in_kommission_kommission_sachbereiche_handler_compare');
            $grid->AddCompareColumn($column);
            
            //
            // View column for kommission_mutter_kommission_id field
            //
            $column = new TextViewColumn('kommission_mutter_kommission_id', 'kommission_mutter_kommission_id', 'Kommission Mutter Kommission Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for kommission_parlament_url field
            //
            $column = new TextViewColumn('kommission_parlament_url', 'kommission_parlament_url', 'Kommission Parlament Url', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.v_in_kommission_kommission_parlament_url_handler_compare');
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
            // View column for parlament_committee_function field
            //
            $column = new NumberViewColumn('parlament_committee_function', 'parlament_committee_function', 'Parlament Committee Function', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator('\'');
            $column->setDecimalSeparator('');
            $grid->AddCompareColumn($column);
            
            //
            // View column for parlament_committee_function_name field
            //
            $column = new TextViewColumn('parlament_committee_function_name', 'parlament_committee_function_name', 'Parlament Committee Function Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for rat_de field
            //
            $column = new TextViewColumn('rat_de', 'rat_de', 'Rat De', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for rat_fr field
            //
            $column = new TextViewColumn('rat_fr', 'rat_fr', 'Rat Fr', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for rat_mixed field
            //
            $column = new TextViewColumn('rat_mixed', 'rat_mixed', 'Rat Mixed', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for kommission_abkuerzung_de field
            //
            $column = new TextViewColumn('kommission_abkuerzung_de', 'kommission_abkuerzung_de', 'Kommission Abkuerzung De', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for kommission_name_de field
            //
            $column = new TextViewColumn('kommission_name_de', 'kommission_name_de', 'Kommission Name De', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.v_in_kommission_kommission_name_de_handler_compare');
            $grid->AddCompareColumn($column);
            
            //
            // View column for kommission_abkuerzung_fr field
            //
            $column = new TextViewColumn('kommission_abkuerzung_fr', 'kommission_abkuerzung_fr', 'Kommission Abkuerzung Fr', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for kommission_name_fr field
            //
            $column = new TextViewColumn('kommission_name_fr', 'kommission_name_fr', 'Kommission Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.v_in_kommission_kommission_name_fr_handler_compare');
            $grid->AddCompareColumn($column);
            
            //
            // View column for kommission_abkuerzung_mixed field
            //
            $column = new TextViewColumn('kommission_abkuerzung_mixed', 'kommission_abkuerzung_mixed', 'Kommission Abkuerzung Mixed', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for kommission_name_mixed field
            //
            $column = new TextViewColumn('kommission_name_mixed', 'kommission_name_mixed', 'Kommission Name Mixed', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.v_in_kommission_kommission_name_mixed_handler_compare');
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
            $defaultSortedColumns[] = new SortColumn('parlamentarier_id_anzeige_name', 'ASC');
            $result->setDefaultOrdering($defaultSortedColumns);
            $result->SetUseFixedHeader(true);
            $result->SetShowLineNumbers(true);
            $result->SetViewMode(ViewMode::TABLE);
            $result->setEnableRuntimeCustomization(true);
            $result->setAllowCompare(true);
            $this->AddCompareHeaderColumns($result);
            $this->AddCompareColumns($result);
            $result->setTableBordered(false);
            $result->setTableCondensed(false);
            
            $result->SetHighlightRowAtHover(false);
            $result->SetWidth('');
            $this->AddOperationsColumns($result);
            $this->AddFieldColumns($result);
            $this->AddSingleRecordViewColumns($result);
            $this->AddEditColumns($result);
            $this->AddInsertColumns($result);
            $this->AddPrintColumns($result);
            $this->AddExportColumns($result);
    
    
            $this->SetShowPageList(true);
            $this->SetShowTopPageNavigator(true);
            $this->SetShowBottomPageNavigator(true);
            $this->setPrintListAvailable(true);
            $this->setPrintListRecordAvailable(false);
            $this->setPrintOneRecordAvailable(true);
            $this->setExportListAvailable(array('excel','word','xml','csv'));
            $this->setExportListRecordAvailable(array());
            $this->setExportOneRecordAvailable(array('excel','word','xml','csv'));
    
            return $result;
        }
     
        protected function doRegisterHandlers() {
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $column->SetReplaceLFByBR(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridkommission.v_in_kommission_notizen_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for kommission_name_de field
            //
            $column = new TextViewColumn('kommission_name_de', 'kommission_name_de', 'Kommission Name De', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridkommission.v_in_kommission_kommission_name_de_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for kommission_name_fr field
            //
            $column = new TextViewColumn('kommission_name_fr', 'kommission_name_fr', 'Kommission Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridkommission.v_in_kommission_kommission_name_fr_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for kommission_name_mixed field
            //
            $column = new TextViewColumn('kommission_name_mixed', 'kommission_name_mixed', 'Kommission Name Mixed', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridkommission.v_in_kommission_kommission_name_mixed_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $column->SetReplaceLFByBR(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridkommission.v_in_kommission_notizen_handler_print', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for kommission_name_de field
            //
            $column = new TextViewColumn('kommission_name_de', 'kommission_name_de', 'Kommission Name De', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridkommission.v_in_kommission_kommission_name_de_handler_print', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for kommission_name_fr field
            //
            $column = new TextViewColumn('kommission_name_fr', 'kommission_name_fr', 'Kommission Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridkommission.v_in_kommission_kommission_name_fr_handler_print', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for kommission_name_mixed field
            //
            $column = new TextViewColumn('kommission_name_mixed', 'kommission_name_mixed', 'Kommission Name Mixed', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridkommission.v_in_kommission_kommission_name_mixed_handler_print', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $column->SetReplaceLFByBR(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridkommission.v_in_kommission_notizen_handler_compare', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for kommission_name field
            //
            $column = new TextViewColumn('kommission_name', 'kommission_name', 'Kommission Name', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridkommission.v_in_kommission_kommission_name_handler_compare', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for kommission_beschreibung field
            //
            $column = new TextViewColumn('kommission_beschreibung', 'kommission_beschreibung', 'Kommission Beschreibung', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridkommission.v_in_kommission_kommission_beschreibung_handler_compare', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for kommission_sachbereiche field
            //
            $column = new TextViewColumn('kommission_sachbereiche', 'kommission_sachbereiche', 'Kommission Sachbereiche', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridkommission.v_in_kommission_kommission_sachbereiche_handler_compare', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for kommission_parlament_url field
            //
            $column = new TextViewColumn('kommission_parlament_url', 'kommission_parlament_url', 'Kommission Parlament Url', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridkommission.v_in_kommission_kommission_parlament_url_handler_compare', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for kommission_name_de field
            //
            $column = new TextViewColumn('kommission_name_de', 'kommission_name_de', 'Kommission Name De', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridkommission.v_in_kommission_kommission_name_de_handler_compare', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for kommission_name_fr field
            //
            $column = new TextViewColumn('kommission_name_fr', 'kommission_name_fr', 'Kommission Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridkommission.v_in_kommission_kommission_name_fr_handler_compare', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for kommission_name_mixed field
            //
            $column = new TextViewColumn('kommission_name_mixed', 'kommission_name_mixed', 'Kommission Name Mixed', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridkommission.v_in_kommission_kommission_name_mixed_handler_compare', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`v_parlamentarier_simple`');
            $field = new StringField('anzeige_name');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_fr');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('name');
            $lookupDataset->AddField($field, false);
            $field = new StringField('name_de');
            $lookupDataset->AddField($field, false);
            $field = new StringField('name_fr');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('id');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('nachname');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('vorname');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('zweiter_vorname');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('rat_id');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('kanton_id');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('kommissionen');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('partei_id');
            $lookupDataset->AddField($field, false);
            $field = new StringField('parteifunktion');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('fraktion_id');
            $lookupDataset->AddField($field, false);
            $field = new StringField('fraktionsfunktion');
            $lookupDataset->AddField($field, false);
            $field = new DateField('im_rat_seit');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new DateField('im_rat_bis');
            $lookupDataset->AddField($field, false);
            $field = new DateField('ratswechsel');
            $lookupDataset->AddField($field, false);
            $field = new DateField('ratsunterbruch_von');
            $lookupDataset->AddField($field, false);
            $field = new DateField('ratsunterbruch_bis');
            $lookupDataset->AddField($field, false);
            $field = new StringField('beruf');
            $lookupDataset->AddField($field, false);
            $field = new StringField('beruf_fr');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('beruf_interessengruppe_id');
            $lookupDataset->AddField($field, false);
            $field = new StringField('titel');
            $lookupDataset->AddField($field, false);
            $field = new StringField('aemter');
            $lookupDataset->AddField($field, false);
            $field = new StringField('weitere_aemter');
            $lookupDataset->AddField($field, false);
            $field = new StringField('zivilstand');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_kinder');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('militaerischer_grad_id');
            $lookupDataset->AddField($field, false);
            $field = new StringField('geschlecht');
            $lookupDataset->AddField($field, false);
            $field = new DateField('geburtstag');
            $lookupDataset->AddField($field, false);
            $field = new StringField('photo');
            $lookupDataset->AddField($field, false);
            $field = new StringField('photo_dateiname');
            $lookupDataset->AddField($field, false);
            $field = new StringField('photo_dateierweiterung');
            $lookupDataset->AddField($field, false);
            $field = new StringField('photo_dateiname_voll');
            $lookupDataset->AddField($field, false);
            $field = new StringField('photo_mime_type');
            $lookupDataset->AddField($field, false);
            $field = new StringField('kleinbild');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('sitzplatz');
            $lookupDataset->AddField($field, false);
            $field = new StringField('email');
            $lookupDataset->AddField($field, false);
            $field = new StringField('homepage');
            $lookupDataset->AddField($field, false);
            $field = new StringField('homepage_2');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('parlament_biografie_id');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('parlament_number');
            $lookupDataset->AddField($field, false);
            $field = new StringField('parlament_interessenbindungen');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('parlament_interessenbindungen_updated');
            $lookupDataset->AddField($field, false);
            $field = new StringField('twitter_name');
            $lookupDataset->AddField($field, false);
            $field = new StringField('linkedin_profil_url');
            $lookupDataset->AddField($field, false);
            $field = new StringField('xing_profil_name');
            $lookupDataset->AddField($field, false);
            $field = new StringField('facebook_name');
            $lookupDataset->AddField($field, false);
            $field = new StringField('wikipedia');
            $lookupDataset->AddField($field, false);
            $field = new StringField('sprache');
            $lookupDataset->AddField($field, false);
            $field = new StringField('arbeitssprache');
            $lookupDataset->AddField($field, false);
            $field = new StringField('adresse_firma');
            $lookupDataset->AddField($field, false);
            $field = new StringField('adresse_strasse');
            $lookupDataset->AddField($field, false);
            $field = new StringField('adresse_zusatz');
            $lookupDataset->AddField($field, false);
            $field = new StringField('adresse_plz');
            $lookupDataset->AddField($field, false);
            $field = new StringField('adresse_ort');
            $lookupDataset->AddField($field, false);
            $field = new StringField('telephon_1');
            $lookupDataset->AddField($field, false);
            $field = new StringField('telephon_2');
            $lookupDataset->AddField($field, false);
            $field = new StringField('erfasst');
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
            $field = new StringField('autorisierung_verschickt_visa');
            $lookupDataset->AddField($field, false);
            $field = new DateTimeField('autorisierung_verschickt_datum');
            $lookupDataset->AddField($field, false);
            $field = new StringField('autorisiert_visa');
            $lookupDataset->AddField($field, false);
            $field = new DateField('autorisiert_datum');
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
            $field = new StringField('beruf_de');
            $lookupDataset->AddField($field, false);
            $field = new DateField('von');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new DateField('bis');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('geburtstag_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('im_rat_seit_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('im_rat_bis_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('created_date_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('updated_date_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('eingabe_abgeschlossen_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('kontrolliert_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('freigabe_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('von_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('bis_unix');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('anzeige_name', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_parlamentarier_id_anzeige_name_search', 'id', 'anzeige_name', null);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
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
            $field = new IntegerField('anzahl_mitglieder');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_nationalraete');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_staenderaete');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('mutter_kommission_id');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('zweitrat_kommission_id');
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
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('updated_date_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('eingabe_abgeschlossen_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('kontrolliert_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('freigabe_datum_unix');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('anzeige_name', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_kommission_id_anzeige_name_search', 'id', 'anzeige_name', null);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $column->SetReplaceLFByBR(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridkommission.v_in_kommission_notizen_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for kommission_name_de field
            //
            $column = new TextViewColumn('kommission_name_de', 'kommission_name_de', 'Kommission Name De', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridkommission.v_in_kommission_kommission_name_de_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for kommission_name_fr field
            //
            $column = new TextViewColumn('kommission_name_fr', 'kommission_name_fr', 'Kommission Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridkommission.v_in_kommission_kommission_name_fr_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for kommission_name_mixed field
            //
            $column = new TextViewColumn('kommission_name_mixed', 'kommission_name_mixed', 'Kommission Name Mixed', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridkommission.v_in_kommission_kommission_name_mixed_handler_view', $column);
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
    
        }
    
        protected function doCustomRenderTotal($totalValue, $aggregate, $columnName, &$customText, &$handled)
        {
    
        }
    
        protected function doCustomCompareColumn($columnName, $valueA, $valueB, &$result)
        {
    
        }
    
        protected function doBeforeInsertRecord($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName)
        {
    
        }
    
        protected function doBeforeUpdateRecord($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName)
        {
    
        }
    
        protected function doBeforeDeleteRecord($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName)
        {
    
        }
    
        protected function doAfterInsertRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doAfterUpdateRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
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
    
        protected function doGetCustomUploadFileName($fieldName, $rowData, &$result, &$handled, $originalFileName, $originalFileExtension, $fileSize)
        {
    
        }
    
        protected function doPrepareChart(Chart $chart)
        {
    
        }
    
    }
    
    
    
    
    // OnBeforePageExecute event handler
    
    
    
    class kommission_branchePage extends DetailPage
    {
        protected function DoBeforeCreate()
        {
            $this->dataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
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
            $this->dataset->AddLookupField('kommission_id', 'v_kommission', new IntegerField('id'), new StringField('anzeige_name', 'kommission_id_anzeige_name', 'kommission_id_anzeige_name_v_kommission'), 'kommission_id_anzeige_name_v_kommission');
            $this->dataset->AddLookupField('kommission2_id', 'kommission', new IntegerField('id', null, null, true), new StringField('abkuerzung', 'kommission2_id_abkuerzung', 'kommission2_id_abkuerzung_kommission'), 'kommission2_id_abkuerzung_kommission');
        }
    
        protected function DoPrepare() {
            globalOnPreparePage($this);
        }
    
        protected function CreatePageNavigator()
        {
            $result = new CompositePageNavigator($this);
            
            $partitionNavigator = new PageNavigator('pnav', $this, $this->dataset);
            $partitionNavigator->SetRowsPerPage(100);
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
                new FilterColumn($this->dataset, 'id', 'id', $this->RenderText('Id')),
                new FilterColumn($this->dataset, 'name', 'name', $this->RenderText('Name')),
                new FilterColumn($this->dataset, 'kommission_id', 'kommission_id_anzeige_name', $this->RenderText('Kommission')),
                new FilterColumn($this->dataset, 'beschreibung', 'beschreibung', $this->RenderText('Beschreibung')),
                new FilterColumn($this->dataset, 'angaben', 'angaben', $this->RenderText('Angaben')),
                new FilterColumn($this->dataset, 'notizen', 'notizen', $this->RenderText('Notizen')),
                new FilterColumn($this->dataset, 'eingabe_abgeschlossen_visa', 'eingabe_abgeschlossen_visa', $this->RenderText('Eingabe Abgeschlossen Visa')),
                new FilterColumn($this->dataset, 'eingabe_abgeschlossen_datum', 'eingabe_abgeschlossen_datum', $this->RenderText('Eingabe Abgeschlossen Datum')),
                new FilterColumn($this->dataset, 'kontrolliert_visa', 'kontrolliert_visa', $this->RenderText('Kontrolliert Visa')),
                new FilterColumn($this->dataset, 'kontrolliert_datum', 'kontrolliert_datum', $this->RenderText('Kontrolliert Datum')),
                new FilterColumn($this->dataset, 'freigabe_visa', 'freigabe_visa', $this->RenderText('Freigabe Visa')),
                new FilterColumn($this->dataset, 'freigabe_datum', 'freigabe_datum', $this->RenderText('Freigabe Datum')),
                new FilterColumn($this->dataset, 'created_visa', 'created_visa', $this->RenderText('Created Visa')),
                new FilterColumn($this->dataset, 'created_date', 'created_date', $this->RenderText('Created Date')),
                new FilterColumn($this->dataset, 'updated_visa', 'updated_visa', $this->RenderText('Updated Visa')),
                new FilterColumn($this->dataset, 'updated_date', 'updated_date', $this->RenderText('Updated Date')),
                new FilterColumn($this->dataset, 'farbcode', 'farbcode', $this->RenderText('Farbcode')),
                new FilterColumn($this->dataset, 'symbol_abs', 'symbol_abs', $this->RenderText('Symbol Abs')),
                new FilterColumn($this->dataset, 'symbol_rel', 'symbol_rel', $this->RenderText('Symbol Rel')),
                new FilterColumn($this->dataset, 'symbol_klein_rel', 'symbol_klein_rel', $this->RenderText('Symbol Klein Rel')),
                new FilterColumn($this->dataset, 'symbol_dateiname_wo_ext', 'symbol_dateiname_wo_ext', $this->RenderText('Symbol Dateiname Wo Ext')),
                new FilterColumn($this->dataset, 'symbol_dateierweiterung', 'symbol_dateierweiterung', $this->RenderText('Symbol Dateierweiterung')),
                new FilterColumn($this->dataset, 'symbol_dateiname', 'symbol_dateiname', $this->RenderText('Symbol Dateiname')),
                new FilterColumn($this->dataset, 'symbol_mime_type', 'symbol_mime_type', $this->RenderText('Symbol Mime Type')),
                new FilterColumn($this->dataset, 'name_fr', 'name_fr', $this->RenderText('Name Fr')),
                new FilterColumn($this->dataset, 'kommission2_id', 'kommission2_id_abkuerzung', $this->RenderText('Kommission2 Id')),
                new FilterColumn($this->dataset, 'technischer_name', 'technischer_name', $this->RenderText('Technischer Name')),
                new FilterColumn($this->dataset, 'beschreibung_fr', 'beschreibung_fr', $this->RenderText('Beschreibung Fr')),
                new FilterColumn($this->dataset, 'angaben_fr', 'angaben_fr', $this->RenderText('Angaben Fr'))
            );
        }
    
        protected function setupQuickFilter(QuickFilter $quickFilter, FixedKeysArray $columns)
        {
            $quickFilter
                ->addColumn($columns['id'])
                ->addColumn($columns['name'])
                ->addColumn($columns['kommission_id'])
                ->addColumn($columns['beschreibung'])
                ->addColumn($columns['angaben'])
                ->addColumn($columns['notizen'])
                ->addColumn($columns['eingabe_abgeschlossen_visa'])
                ->addColumn($columns['eingabe_abgeschlossen_datum'])
                ->addColumn($columns['kontrolliert_visa'])
                ->addColumn($columns['kontrolliert_datum'])
                ->addColumn($columns['freigabe_visa'])
                ->addColumn($columns['freigabe_datum'])
                ->addColumn($columns['created_visa'])
                ->addColumn($columns['created_date'])
                ->addColumn($columns['updated_visa'])
                ->addColumn($columns['updated_date'])
                ->addColumn($columns['name_fr'])
                ->addColumn($columns['kommission2_id'])
                ->addColumn($columns['technischer_name'])
                ->addColumn($columns['beschreibung_fr'])
                ->addColumn($columns['angaben_fr']);
        }
    
        protected function setupColumnFilter(ColumnFilter $columnFilter)
        {
            $columnFilter
                ->setOptionsFor('kommission_id')
                ->setOptionsFor('eingabe_abgeschlossen_datum')
                ->setOptionsFor('kontrolliert_datum')
                ->setOptionsFor('freigabe_datum')
                ->setOptionsFor('created_date')
                ->setOptionsFor('updated_date')
                ->setOptionsFor('kommission2_id');
        }
    
        protected function setupFilterBuilder(FilterBuilder $filterBuilder, FixedKeysArray $columns)
        {
            $main_editor = new TextEdit('id_edit');
            
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
            
            $main_editor = new TextEdit('name_edit');
            $main_editor->SetMaxLength(100);
            
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
            
            $main_editor = new AutocompleteComboBox('kommission_id_edit', $this->CreateLinkBuilder());
            $main_editor->setAllowClear(true);
            $main_editor->setMinimumInputLength(0);
            $main_editor->SetAllowNullValue(false);
            $main_editor->SetHandlerName('filter_builder_kommission_id_anzeige_name_search');
            
            $multi_value_select_editor = new RemoteMultiValueSelect('kommission_id', $this->CreateLinkBuilder());
            $multi_value_select_editor->SetHandlerName('filter_builder_kommission_id_anzeige_name_search');
            
            $text_editor = new TextEdit('kommission_id');
            
            $filterBuilder->addColumn(
                $columns['kommission_id'],
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
            
            $main_editor = new TextEdit('beschreibung');
            
            $filterBuilder->addColumn(
                $columns['beschreibung'],
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
            
            $main_editor = new TextEdit('angaben');
            
            $filterBuilder->addColumn(
                $columns['angaben'],
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
            
            $main_editor = new TextEdit('notizen');
            
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
            
            $main_editor = new TextEdit('eingabe_abgeschlossen_visa_edit');
            $main_editor->SetMaxLength(10);
            
            $filterBuilder->addColumn(
                $columns['eingabe_abgeschlossen_visa'],
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
            
            $main_editor = new DateTimeEdit('eingabe_abgeschlossen_datum_edit', false, 'Y-m-d H:i:s');
            
            $filterBuilder->addColumn(
                $columns['eingabe_abgeschlossen_datum'],
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
            
            $main_editor = new TextEdit('kontrolliert_visa_edit');
            $main_editor->SetMaxLength(10);
            
            $filterBuilder->addColumn(
                $columns['kontrolliert_visa'],
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
            
            $main_editor = new DateTimeEdit('kontrolliert_datum_edit', false, 'Y-m-d H:i:s');
            
            $filterBuilder->addColumn(
                $columns['kontrolliert_datum'],
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
            
            $main_editor = new TextEdit('freigabe_visa_edit');
            $main_editor->SetMaxLength(10);
            
            $filterBuilder->addColumn(
                $columns['freigabe_visa'],
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
            
            $main_editor = new DateTimeEdit('freigabe_datum_edit', false, 'Y-m-d H:i:s');
            
            $filterBuilder->addColumn(
                $columns['freigabe_datum'],
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
            
            $main_editor = new TextEdit('created_visa_edit');
            $main_editor->SetMaxLength(10);
            
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
            $main_editor->SetMaxLength(10);
            
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
            
            $main_editor = new TextEdit('name_fr_edit');
            $main_editor->SetMaxLength(100);
            
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
            
            $main_editor = new ComboBox('kommission2_id_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $main_editor->SetAllowNullValue(false);
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`kommission`');
            $field = new IntegerField('id', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
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
            $field = new IntegerField('anzahl_mitglieder');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_nationalraete');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_staenderaete');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('mutter_kommission_id');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('zweitrat_kommission_id');
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
            $lookupDataset->setOrderByField('abkuerzung', GetOrderTypeAsSQL(otAscending));
            $editColumn = new LookUpEditColumn(
                'Kommission2 Id', 
                'kommission2_id', 
                $main_editor, 
                $this->dataset, 'id', 'abkuerzung', $lookupDataset);
            
            $editColumn->PrepareEditorControl();
            
            $multi_value_select_editor = new MultiValueSelect('kommission2_id');
            $multi_value_select_editor->setChoices($main_editor->getChoices());
            
            $text_editor = new TextEdit('kommission2_id');
            
            $filterBuilder->addColumn(
                $columns['kommission2_id'],
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
            
            $main_editor = new TextEdit('technischer_name_edit');
            $main_editor->SetMaxLength(30);
            
            $filterBuilder->addColumn(
                $columns['technischer_name'],
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
            
            $main_editor = new TextEdit('beschreibung_fr');
            
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
            
            $main_editor = new TextEdit('angaben_fr');
            
            $filterBuilder->addColumn(
                $columns['angaben_fr'],
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
            $column->SetDescription($this->RenderText('Technischer Schlüssel der Branche'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('name', 'name', 'Name', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.branche_name_handler_list');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('Name der Branche, z.B. Gesundheit, Energie'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for anzeige_name field
            //
            $column = new TextViewColumn('kommission_id', 'kommission_id_anzeige_name', 'Kommission', $this->dataset);
            $column->SetOrderable(true);
            $column->setHrefTemplate('kommission.php?operation=view&pk0=%kommission_id%');
            $column->setTarget('_self');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('Zuständige Kommission im Parlament'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for beschreibung field
            //
            $column = new TextViewColumn('beschreibung', 'beschreibung', 'Beschreibung', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.branche_beschreibung_handler_list');
            $column->SetReplaceLFByBR(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('Beschreibung der Branche'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for angaben field
            //
            $column = new TextViewColumn('angaben', 'angaben', 'Angaben', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.branche_angaben_handler_list');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('Angaben zur Branche'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.branche_notizen_handler_list');
            $column->SetReplaceLFByBR(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_visa field
            //
            $column = new TextViewColumn('eingabe_abgeschlossen_visa', 'eingabe_abgeschlossen_visa', 'Eingabe Abgeschlossen Visa', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('Kürzel der Person, welche die Eingabe abgeschlossen hat.'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_datum field
            //
            $column = new DateTimeViewColumn('eingabe_abgeschlossen_datum', 'eingabe_abgeschlossen_datum', 'Eingabe Abgeschlossen Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for kontrolliert_visa field
            //
            $column = new TextViewColumn('kontrolliert_visa', 'kontrolliert_visa', 'Kontrolliert Visa', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('Kürzel der Person, welche die Eingabe kontrolliert hat.'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for kontrolliert_datum field
            //
            $column = new DateTimeViewColumn('kontrolliert_datum', 'kontrolliert_datum', 'Kontrolliert Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for freigabe_visa field
            //
            $column = new TextViewColumn('freigabe_visa', 'freigabe_visa', 'Freigabe Visa', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('Freigabe von wem? (Freigabe = Daten sind fertig)'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for freigabe_datum field
            //
            $column = new DateTimeViewColumn('freigabe_datum', 'freigabe_datum', 'Freigabe Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('Freigabedatum (Freigabe = Daten sind fertig)'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for created_visa field
            //
            $column = new TextViewColumn('created_visa', 'created_visa', 'Created Visa', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('Erstellt von'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for created_date field
            //
            $column = new DateTimeViewColumn('created_date', 'created_date', 'Created Date', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('Erstellt am'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for updated_visa field
            //
            $column = new TextViewColumn('updated_visa', 'updated_visa', 'Updated Visa', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('Abgeändert von'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for updated_date field
            //
            $column = new DateTimeViewColumn('updated_date', 'updated_date', 'Updated Date', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('Abgeändert am'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for name_fr field
            //
            $column = new TextViewColumn('name_fr', 'name_fr', 'Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.branche_name_fr_handler_list');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('Französischer Name der Branche, z.B. Gesundheit, Energie'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for abkuerzung field
            //
            $column = new TextViewColumn('kommission2_id', 'kommission2_id_abkuerzung', 'Kommission2 Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('Zuständige Kommission im Ständerat'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for technischer_name field
            //
            $column = new TextViewColumn('technischer_name', 'technischer_name', 'Technischer Name', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('Technischer Name für Branche. Keine Sonderzeichen sind erlaubt. Wird z.B. für das finden des Branchensymboles gebraucht.'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for beschreibung_fr field
            //
            $column = new TextViewColumn('beschreibung_fr', 'beschreibung_fr', 'Beschreibung Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.branche_beschreibung_fr_handler_list');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('Französische Beschreibung der Branche'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for angaben_fr field
            //
            $column = new TextViewColumn('angaben_fr', 'angaben_fr', 'Angaben Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.branche_angaben_fr_handler_list');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('Angaben zur Branche auf Französisch'));
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
            // View column for name field
            //
            $column = new TextViewColumn('name', 'name', 'Name', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.branche_name_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for anzeige_name field
            //
            $column = new TextViewColumn('kommission_id', 'kommission_id_anzeige_name', 'Kommission', $this->dataset);
            $column->SetOrderable(true);
            $column->setHrefTemplate('kommission.php?operation=view&pk0=%kommission_id%');
            $column->setTarget('_self');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for beschreibung field
            //
            $column = new TextViewColumn('beschreibung', 'beschreibung', 'Beschreibung', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.branche_beschreibung_handler_view');
            $column->SetReplaceLFByBR(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for angaben field
            //
            $column = new TextViewColumn('angaben', 'angaben', 'Angaben', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.branche_angaben_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.branche_notizen_handler_view');
            $column->SetReplaceLFByBR(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_visa field
            //
            $column = new TextViewColumn('eingabe_abgeschlossen_visa', 'eingabe_abgeschlossen_visa', 'Eingabe Abgeschlossen Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_datum field
            //
            $column = new DateTimeViewColumn('eingabe_abgeschlossen_datum', 'eingabe_abgeschlossen_datum', 'Eingabe Abgeschlossen Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for kontrolliert_visa field
            //
            $column = new TextViewColumn('kontrolliert_visa', 'kontrolliert_visa', 'Kontrolliert Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for kontrolliert_datum field
            //
            $column = new DateTimeViewColumn('kontrolliert_datum', 'kontrolliert_datum', 'Kontrolliert Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for freigabe_visa field
            //
            $column = new TextViewColumn('freigabe_visa', 'freigabe_visa', 'Freigabe Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for freigabe_datum field
            //
            $column = new DateTimeViewColumn('freigabe_datum', 'freigabe_datum', 'Freigabe Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
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
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
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
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for name_fr field
            //
            $column = new TextViewColumn('name_fr', 'name_fr', 'Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.branche_name_fr_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for abkuerzung field
            //
            $column = new TextViewColumn('kommission2_id', 'kommission2_id_abkuerzung', 'Kommission2 Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for technischer_name field
            //
            $column = new TextViewColumn('technischer_name', 'technischer_name', 'Technischer Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for beschreibung_fr field
            //
            $column = new TextViewColumn('beschreibung_fr', 'beschreibung_fr', 'Beschreibung Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.branche_beschreibung_fr_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for angaben_fr field
            //
            $column = new TextViewColumn('angaben_fr', 'angaben_fr', 'Angaben Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.branche_angaben_fr_handler_view');
            $grid->AddSingleRecordViewColumn($column);
        }
    
        protected function AddEditColumns(Grid $grid)
        {
            //
            // Edit column for name field
            //
            $editor = new TextEdit('name_edit');
            $editor->SetMaxLength(100);
            $editColumn = new CustomEditColumn('Name', 'name', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for kommission_id field
            //
            $editor = new ComboBox('kommission_id_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
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
            $field = new IntegerField('anzahl_mitglieder');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_nationalraete');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_staenderaete');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('mutter_kommission_id');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('zweitrat_kommission_id');
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
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('updated_date_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('eingabe_abgeschlossen_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('kontrolliert_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('freigabe_datum_unix');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('anzeige_name', GetOrderTypeAsSQL(otAscending));
            $editColumn = new LookUpEditColumn(
                'Kommission', 
                'kommission_id', 
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
            // Edit column for angaben field
            //
            $editor = new TextAreaEdit('angaben_edit', 50, 8);
            $editColumn = new CustomEditColumn('Angaben', 'angaben', $editor, $this->dataset);
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
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Eingabe Abgeschlossen Visa', 'eingabe_abgeschlossen_visa', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for eingabe_abgeschlossen_datum field
            //
            $editor = new DateTimeEdit('eingabe_abgeschlossen_datum_edit', false, 'Y-m-d H:i:s');
            $editColumn = new CustomEditColumn('Eingabe Abgeschlossen Datum', 'eingabe_abgeschlossen_datum', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for kontrolliert_visa field
            //
            $editor = new TextEdit('kontrolliert_visa_edit');
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Kontrolliert Visa', 'kontrolliert_visa', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for kontrolliert_datum field
            //
            $editor = new DateTimeEdit('kontrolliert_datum_edit', false, 'Y-m-d H:i:s');
            $editColumn = new CustomEditColumn('Kontrolliert Datum', 'kontrolliert_datum', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for freigabe_visa field
            //
            $editor = new TextEdit('freigabe_visa_edit');
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Freigabe Visa', 'freigabe_visa', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for freigabe_datum field
            //
            $editor = new DateTimeEdit('freigabe_datum_edit', false, 'Y-m-d H:i:s');
            $editColumn = new CustomEditColumn('Freigabe Datum', 'freigabe_datum', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for created_visa field
            //
            $editor = new TextEdit('created_visa_edit');
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Created Visa', 'created_visa', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for created_date field
            //
            $editor = new DateTimeEdit('created_date_edit', false, 'Y-m-d H:i:s');
            $editColumn = new CustomEditColumn('Created Date', 'created_date', $editor, $this->dataset);
            $editColumn->SetAllowSetToDefault(false); /*afterburner*/ 
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for updated_visa field
            //
            $editor = new TextEdit('updated_visa_edit');
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Updated Visa', 'updated_visa', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for updated_date field
            //
            $editor = new DateTimeEdit('updated_date_edit', false, 'Y-m-d H:i:s');
            $editColumn = new CustomEditColumn('Updated Date', 'updated_date', $editor, $this->dataset);
            $editColumn->SetAllowSetToDefault(false); /*afterburner*/ 
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for name_fr field
            //
            $editor = new TextEdit('name_fr_edit');
            $editor->SetMaxLength(100);
            $editColumn = new CustomEditColumn('Name Fr', 'name_fr', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for kommission2_id field
            //
            $editor = new ComboBox('kommission2_id_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`kommission`');
            $field = new IntegerField('id', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
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
            $field = new IntegerField('anzahl_mitglieder');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_nationalraete');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_staenderaete');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('mutter_kommission_id');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('zweitrat_kommission_id');
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
            $lookupDataset->setOrderByField('abkuerzung', GetOrderTypeAsSQL(otAscending));
            $editColumn = new LookUpEditColumn(
                'Kommission2 Id', 
                'kommission2_id', 
                $editor, 
                $this->dataset, 'id', 'abkuerzung', $lookupDataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for technischer_name field
            //
            $editor = new TextEdit('technischer_name_edit');
            $editor->SetMaxLength(30);
            $editColumn = new CustomEditColumn('Technischer Name', 'technischer_name', $editor, $this->dataset);
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
            // Edit column for angaben_fr field
            //
            $editor = new TextAreaEdit('angaben_fr_edit', 50, 8);
            $editColumn = new CustomEditColumn('Angaben Fr', 'angaben_fr', $editor, $this->dataset);
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
            $editor->SetMaxLength(100);
            $editColumn = new CustomEditColumn('Name', 'name', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for kommission_id field
            //
            $editor = new ComboBox('kommission_id_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
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
            $field = new IntegerField('anzahl_mitglieder');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_nationalraete');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_staenderaete');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('mutter_kommission_id');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('zweitrat_kommission_id');
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
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('updated_date_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('eingabe_abgeschlossen_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('kontrolliert_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('freigabe_datum_unix');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('anzeige_name', GetOrderTypeAsSQL(otAscending));
            $editColumn = new LookUpEditColumn(
                'Kommission', 
                'kommission_id', 
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
            // Edit column for angaben field
            //
            $editor = new TextAreaEdit('angaben_edit', 50, 8);
            $editColumn = new CustomEditColumn('Angaben', 'angaben', $editor, $this->dataset);
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
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Eingabe Abgeschlossen Visa', 'eingabe_abgeschlossen_visa', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for eingabe_abgeschlossen_datum field
            //
            $editor = new DateTimeEdit('eingabe_abgeschlossen_datum_edit', false, 'Y-m-d H:i:s');
            $editColumn = new CustomEditColumn('Eingabe Abgeschlossen Datum', 'eingabe_abgeschlossen_datum', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for kontrolliert_visa field
            //
            $editor = new TextEdit('kontrolliert_visa_edit');
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Kontrolliert Visa', 'kontrolliert_visa', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for kontrolliert_datum field
            //
            $editor = new DateTimeEdit('kontrolliert_datum_edit', false, 'Y-m-d H:i:s');
            $editColumn = new CustomEditColumn('Kontrolliert Datum', 'kontrolliert_datum', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for freigabe_visa field
            //
            $editor = new TextEdit('freigabe_visa_edit');
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Freigabe Visa', 'freigabe_visa', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for freigabe_datum field
            //
            $editor = new DateTimeEdit('freigabe_datum_edit', false, 'Y-m-d H:i:s');
            $editColumn = new CustomEditColumn('Freigabe Datum', 'freigabe_datum', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for created_visa field
            //
            $editor = new TextEdit('created_visa_edit');
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Created Visa', 'created_visa', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for created_date field
            //
            $editor = new DateTimeEdit('created_date_edit', false, 'Y-m-d H:i:s');
            $editColumn = new CustomEditColumn('Created Date', 'created_date', $editor, $this->dataset);
            $editColumn->SetAllowSetToDefault(false); /*afterburner*/ 
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for updated_visa field
            //
            $editor = new TextEdit('updated_visa_edit');
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Updated Visa', 'updated_visa', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for updated_date field
            //
            $editor = new DateTimeEdit('updated_date_edit', false, 'Y-m-d H:i:s');
            $editColumn = new CustomEditColumn('Updated Date', 'updated_date', $editor, $this->dataset);
            $editColumn->SetAllowSetToDefault(false); /*afterburner*/ 
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for name_fr field
            //
            $editor = new TextEdit('name_fr_edit');
            $editor->SetMaxLength(100);
            $editColumn = new CustomEditColumn('Name Fr', 'name_fr', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for kommission2_id field
            //
            $editor = new ComboBox('kommission2_id_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`kommission`');
            $field = new IntegerField('id', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
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
            $field = new IntegerField('anzahl_mitglieder');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_nationalraete');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_staenderaete');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('mutter_kommission_id');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('zweitrat_kommission_id');
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
            $lookupDataset->setOrderByField('abkuerzung', GetOrderTypeAsSQL(otAscending));
            $editColumn = new LookUpEditColumn(
                'Kommission2 Id', 
                'kommission2_id', 
                $editor, 
                $this->dataset, 'id', 'abkuerzung', $lookupDataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for technischer_name field
            //
            $editor = new TextEdit('technischer_name_edit');
            $editor->SetMaxLength(30);
            $editColumn = new CustomEditColumn('Technischer Name', 'technischer_name', $editor, $this->dataset);
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
            // Edit column for angaben_fr field
            //
            $editor = new TextAreaEdit('angaben_fr_edit', 50, 8);
            $editColumn = new CustomEditColumn('Angaben Fr', 'angaben_fr', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            $grid->SetShowAddButton(false && $this->GetSecurityInfo()->HasAddGrant());
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
            // View column for name field
            //
            $column = new TextViewColumn('name', 'name', 'Name', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.branche_name_handler_print');
            $grid->AddPrintColumn($column);
            
            //
            // View column for anzeige_name field
            //
            $column = new TextViewColumn('kommission_id', 'kommission_id_anzeige_name', 'Kommission', $this->dataset);
            $column->SetOrderable(true);
            $column->setHrefTemplate('kommission.php?operation=view&pk0=%kommission_id%');
            $column->setTarget('_self');
            $grid->AddPrintColumn($column);
            
            //
            // View column for beschreibung field
            //
            $column = new TextViewColumn('beschreibung', 'beschreibung', 'Beschreibung', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.branche_beschreibung_handler_print');
            $column->SetReplaceLFByBR(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for angaben field
            //
            $column = new TextViewColumn('angaben', 'angaben', 'Angaben', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.branche_angaben_handler_print');
            $grid->AddPrintColumn($column);
            
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.branche_notizen_handler_print');
            $column->SetReplaceLFByBR(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_visa field
            //
            $column = new TextViewColumn('eingabe_abgeschlossen_visa', 'eingabe_abgeschlossen_visa', 'Eingabe Abgeschlossen Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_datum field
            //
            $column = new DateTimeViewColumn('eingabe_abgeschlossen_datum', 'eingabe_abgeschlossen_datum', 'Eingabe Abgeschlossen Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for kontrolliert_visa field
            //
            $column = new TextViewColumn('kontrolliert_visa', 'kontrolliert_visa', 'Kontrolliert Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for kontrolliert_datum field
            //
            $column = new DateTimeViewColumn('kontrolliert_datum', 'kontrolliert_datum', 'Kontrolliert Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for freigabe_visa field
            //
            $column = new TextViewColumn('freigabe_visa', 'freigabe_visa', 'Freigabe Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for freigabe_datum field
            //
            $column = new DateTimeViewColumn('freigabe_datum', 'freigabe_datum', 'Freigabe Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
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
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
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
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for name_fr field
            //
            $column = new TextViewColumn('name_fr', 'name_fr', 'Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.branche_name_fr_handler_print');
            $grid->AddPrintColumn($column);
            
            //
            // View column for abkuerzung field
            //
            $column = new TextViewColumn('kommission2_id', 'kommission2_id_abkuerzung', 'Kommission2 Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for technischer_name field
            //
            $column = new TextViewColumn('technischer_name', 'technischer_name', 'Technischer Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for beschreibung_fr field
            //
            $column = new TextViewColumn('beschreibung_fr', 'beschreibung_fr', 'Beschreibung Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.branche_beschreibung_fr_handler_print');
            $grid->AddPrintColumn($column);
            
            //
            // View column for angaben_fr field
            //
            $column = new TextViewColumn('angaben_fr', 'angaben_fr', 'Angaben Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.branche_angaben_fr_handler_print');
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
            // View column for name field
            //
            $column = new TextViewColumn('name', 'name', 'Name', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.branche_name_handler_export');
            $grid->AddExportColumn($column);
            
            //
            // View column for anzeige_name field
            //
            $column = new TextViewColumn('kommission_id', 'kommission_id_anzeige_name', 'Kommission', $this->dataset);
            $column->SetOrderable(true);
            $column->setHrefTemplate('kommission.php?operation=view&pk0=%kommission_id%');
            $column->setTarget('_self');
            $grid->AddExportColumn($column);
            
            //
            // View column for beschreibung field
            //
            $column = new TextViewColumn('beschreibung', 'beschreibung', 'Beschreibung', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.branche_beschreibung_handler_export');
            $column->SetReplaceLFByBR(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for angaben field
            //
            $column = new TextViewColumn('angaben', 'angaben', 'Angaben', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.branche_angaben_handler_export');
            $grid->AddExportColumn($column);
            
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.branche_notizen_handler_export');
            $column->SetReplaceLFByBR(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_visa field
            //
            $column = new TextViewColumn('eingabe_abgeschlossen_visa', 'eingabe_abgeschlossen_visa', 'Eingabe Abgeschlossen Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_datum field
            //
            $column = new DateTimeViewColumn('eingabe_abgeschlossen_datum', 'eingabe_abgeschlossen_datum', 'Eingabe Abgeschlossen Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for kontrolliert_visa field
            //
            $column = new TextViewColumn('kontrolliert_visa', 'kontrolliert_visa', 'Kontrolliert Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for kontrolliert_datum field
            //
            $column = new DateTimeViewColumn('kontrolliert_datum', 'kontrolliert_datum', 'Kontrolliert Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for freigabe_visa field
            //
            $column = new TextViewColumn('freigabe_visa', 'freigabe_visa', 'Freigabe Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for freigabe_datum field
            //
            $column = new DateTimeViewColumn('freigabe_datum', 'freigabe_datum', 'Freigabe Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
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
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
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
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for name_fr field
            //
            $column = new TextViewColumn('name_fr', 'name_fr', 'Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.branche_name_fr_handler_export');
            $grid->AddExportColumn($column);
            
            //
            // View column for abkuerzung field
            //
            $column = new TextViewColumn('kommission2_id', 'kommission2_id_abkuerzung', 'Kommission2 Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for technischer_name field
            //
            $column = new TextViewColumn('technischer_name', 'technischer_name', 'Technischer Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for beschreibung_fr field
            //
            $column = new TextViewColumn('beschreibung_fr', 'beschreibung_fr', 'Beschreibung Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.branche_beschreibung_fr_handler_export');
            $grid->AddExportColumn($column);
            
            //
            // View column for angaben_fr field
            //
            $column = new TextViewColumn('angaben_fr', 'angaben_fr', 'Angaben Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.branche_angaben_fr_handler_export');
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
            // View column for name field
            //
            $column = new TextViewColumn('name', 'name', 'Name', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.branche_name_handler_compare');
            $grid->AddCompareColumn($column);
            
            //
            // View column for anzeige_name field
            //
            $column = new TextViewColumn('kommission_id', 'kommission_id_anzeige_name', 'Kommission', $this->dataset);
            $column->SetOrderable(true);
            $column->setHrefTemplate('kommission.php?operation=view&pk0=%kommission_id%');
            $column->setTarget('_self');
            $grid->AddCompareColumn($column);
            
            //
            // View column for beschreibung field
            //
            $column = new TextViewColumn('beschreibung', 'beschreibung', 'Beschreibung', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.branche_beschreibung_handler_compare');
            $column->SetReplaceLFByBR(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for angaben field
            //
            $column = new TextViewColumn('angaben', 'angaben', 'Angaben', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.branche_angaben_handler_compare');
            $grid->AddCompareColumn($column);
            
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.branche_notizen_handler_compare');
            $column->SetReplaceLFByBR(true);
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
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
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
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
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
            $column->SetDateTimeFormat('d.m.Y H:i:s');
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
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
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
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for farbcode field
            //
            $column = new TextViewColumn('farbcode', 'farbcode', 'Farbcode', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for symbol_abs field
            //
            $column = new TextViewColumn('symbol_abs', 'symbol_abs', 'Symbol Abs', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.branche_symbol_abs_handler_compare');
            $grid->AddCompareColumn($column);
            
            //
            // View column for symbol_rel field
            //
            $column = new TextViewColumn('symbol_rel', 'symbol_rel', 'Symbol Rel', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.branche_symbol_rel_handler_compare');
            $grid->AddCompareColumn($column);
            
            //
            // View column for symbol_klein_rel field
            //
            $column = new TextViewColumn('symbol_klein_rel', 'symbol_klein_rel', 'Symbol Klein Rel', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.branche_symbol_klein_rel_handler_compare');
            $grid->AddCompareColumn($column);
            
            //
            // View column for symbol_dateiname_wo_ext field
            //
            $column = new TextViewColumn('symbol_dateiname_wo_ext', 'symbol_dateiname_wo_ext', 'Symbol Dateiname Wo Ext', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.branche_symbol_dateiname_wo_ext_handler_compare');
            $grid->AddCompareColumn($column);
            
            //
            // View column for symbol_dateierweiterung field
            //
            $column = new TextViewColumn('symbol_dateierweiterung', 'symbol_dateierweiterung', 'Symbol Dateierweiterung', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for symbol_dateiname field
            //
            $column = new TextViewColumn('symbol_dateiname', 'symbol_dateiname', 'Symbol Dateiname', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.branche_symbol_dateiname_handler_compare');
            $grid->AddCompareColumn($column);
            
            //
            // View column for symbol_mime_type field
            //
            $column = new TextViewColumn('symbol_mime_type', 'symbol_mime_type', 'Symbol Mime Type', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.branche_symbol_mime_type_handler_compare');
            $grid->AddCompareColumn($column);
            
            //
            // View column for name_fr field
            //
            $column = new TextViewColumn('name_fr', 'name_fr', 'Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.branche_name_fr_handler_compare');
            $grid->AddCompareColumn($column);
            
            //
            // View column for abkuerzung field
            //
            $column = new TextViewColumn('kommission2_id', 'kommission2_id_abkuerzung', 'Kommission2 Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for technischer_name field
            //
            $column = new TextViewColumn('technischer_name', 'technischer_name', 'Technischer Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for beschreibung_fr field
            //
            $column = new TextViewColumn('beschreibung_fr', 'beschreibung_fr', 'Beschreibung Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.branche_beschreibung_fr_handler_compare');
            $grid->AddCompareColumn($column);
            
            //
            // View column for angaben_fr field
            //
            $column = new TextViewColumn('angaben_fr', 'angaben_fr', 'Angaben Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridkommission.branche_angaben_fr_handler_compare');
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
            $result->setTableBordered(false);
            $result->setTableCondensed(false);
            
            $result->SetHighlightRowAtHover(false);
            $result->SetWidth('');
            $this->AddOperationsColumns($result);
            $this->AddFieldColumns($result);
            $this->AddSingleRecordViewColumns($result);
            $this->AddEditColumns($result);
            $this->AddInsertColumns($result);
            $this->AddPrintColumns($result);
            $this->AddExportColumns($result);
    
    
            $this->SetShowPageList(true);
            $this->SetShowTopPageNavigator(true);
            $this->SetShowBottomPageNavigator(true);
            $this->setPrintListAvailable(true);
            $this->setPrintListRecordAvailable(false);
            $this->setPrintOneRecordAvailable(true);
            $this->setExportListAvailable(array('excel','word','xml','csv'));
            $this->setExportListRecordAvailable(array());
            $this->setExportOneRecordAvailable(array('excel','word','xml','csv'));
    
            return $result;
        }
     
        protected function doRegisterHandlers() {
            //
            // View column for name field
            //
            $column = new TextViewColumn('name', 'name', 'Name', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridkommission.branche_name_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for beschreibung field
            //
            $column = new TextViewColumn('beschreibung', 'beschreibung', 'Beschreibung', $this->dataset);
            $column->SetOrderable(true);
            $column->SetReplaceLFByBR(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridkommission.branche_beschreibung_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for angaben field
            //
            $column = new TextViewColumn('angaben', 'angaben', 'Angaben', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridkommission.branche_angaben_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $column->SetReplaceLFByBR(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridkommission.branche_notizen_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for name_fr field
            //
            $column = new TextViewColumn('name_fr', 'name_fr', 'Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridkommission.branche_name_fr_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for beschreibung_fr field
            //
            $column = new TextViewColumn('beschreibung_fr', 'beschreibung_fr', 'Beschreibung Fr', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridkommission.branche_beschreibung_fr_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for angaben_fr field
            //
            $column = new TextViewColumn('angaben_fr', 'angaben_fr', 'Angaben Fr', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridkommission.branche_angaben_fr_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('name', 'name', 'Name', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridkommission.branche_name_handler_print', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for beschreibung field
            //
            $column = new TextViewColumn('beschreibung', 'beschreibung', 'Beschreibung', $this->dataset);
            $column->SetOrderable(true);
            $column->SetReplaceLFByBR(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridkommission.branche_beschreibung_handler_print', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for angaben field
            //
            $column = new TextViewColumn('angaben', 'angaben', 'Angaben', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridkommission.branche_angaben_handler_print', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $column->SetReplaceLFByBR(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridkommission.branche_notizen_handler_print', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for name_fr field
            //
            $column = new TextViewColumn('name_fr', 'name_fr', 'Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridkommission.branche_name_fr_handler_print', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for beschreibung_fr field
            //
            $column = new TextViewColumn('beschreibung_fr', 'beschreibung_fr', 'Beschreibung Fr', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridkommission.branche_beschreibung_fr_handler_print', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for angaben_fr field
            //
            $column = new TextViewColumn('angaben_fr', 'angaben_fr', 'Angaben Fr', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridkommission.branche_angaben_fr_handler_print', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('name', 'name', 'Name', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridkommission.branche_name_handler_compare', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for beschreibung field
            //
            $column = new TextViewColumn('beschreibung', 'beschreibung', 'Beschreibung', $this->dataset);
            $column->SetOrderable(true);
            $column->SetReplaceLFByBR(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridkommission.branche_beschreibung_handler_compare', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for angaben field
            //
            $column = new TextViewColumn('angaben', 'angaben', 'Angaben', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridkommission.branche_angaben_handler_compare', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $column->SetReplaceLFByBR(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridkommission.branche_notizen_handler_compare', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for symbol_abs field
            //
            $column = new TextViewColumn('symbol_abs', 'symbol_abs', 'Symbol Abs', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridkommission.branche_symbol_abs_handler_compare', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for symbol_rel field
            //
            $column = new TextViewColumn('symbol_rel', 'symbol_rel', 'Symbol Rel', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridkommission.branche_symbol_rel_handler_compare', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for symbol_klein_rel field
            //
            $column = new TextViewColumn('symbol_klein_rel', 'symbol_klein_rel', 'Symbol Klein Rel', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridkommission.branche_symbol_klein_rel_handler_compare', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for symbol_dateiname_wo_ext field
            //
            $column = new TextViewColumn('symbol_dateiname_wo_ext', 'symbol_dateiname_wo_ext', 'Symbol Dateiname Wo Ext', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridkommission.branche_symbol_dateiname_wo_ext_handler_compare', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for symbol_dateiname field
            //
            $column = new TextViewColumn('symbol_dateiname', 'symbol_dateiname', 'Symbol Dateiname', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridkommission.branche_symbol_dateiname_handler_compare', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for symbol_mime_type field
            //
            $column = new TextViewColumn('symbol_mime_type', 'symbol_mime_type', 'Symbol Mime Type', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridkommission.branche_symbol_mime_type_handler_compare', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for name_fr field
            //
            $column = new TextViewColumn('name_fr', 'name_fr', 'Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridkommission.branche_name_fr_handler_compare', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for beschreibung_fr field
            //
            $column = new TextViewColumn('beschreibung_fr', 'beschreibung_fr', 'Beschreibung Fr', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridkommission.branche_beschreibung_fr_handler_compare', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for angaben_fr field
            //
            $column = new TextViewColumn('angaben_fr', 'angaben_fr', 'Angaben Fr', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridkommission.branche_angaben_fr_handler_compare', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
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
            $field = new IntegerField('anzahl_mitglieder');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_nationalraete');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_staenderaete');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('mutter_kommission_id');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('zweitrat_kommission_id');
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
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('updated_date_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('eingabe_abgeschlossen_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('kontrolliert_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('freigabe_datum_unix');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('anzeige_name', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_kommission_id_anzeige_name_search', 'id', 'anzeige_name', null);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
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
            $field = new IntegerField('anzahl_mitglieder');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_nationalraete');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_staenderaete');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('mutter_kommission_id');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('zweitrat_kommission_id');
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
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('updated_date_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('eingabe_abgeschlossen_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('kontrolliert_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('freigabe_datum_unix');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('anzeige_name', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_kommission_id_anzeige_name_search', 'id', 'anzeige_name', null);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
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
            $field = new IntegerField('anzahl_mitglieder');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_nationalraete');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_staenderaete');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('mutter_kommission_id');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('zweitrat_kommission_id');
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
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('updated_date_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('eingabe_abgeschlossen_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('kontrolliert_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('freigabe_datum_unix');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('anzeige_name', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_kommission_id_anzeige_name_search', 'id', 'anzeige_name', null);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
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
            $field = new IntegerField('anzahl_mitglieder');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_nationalraete');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_staenderaete');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('mutter_kommission_id');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('zweitrat_kommission_id');
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
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('updated_date_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('eingabe_abgeschlossen_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('kontrolliert_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('freigabe_datum_unix');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('anzeige_name', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_kommission_id_anzeige_name_search', 'id', 'anzeige_name', null);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('name', 'name', 'Name', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridkommission.branche_name_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for beschreibung field
            //
            $column = new TextViewColumn('beschreibung', 'beschreibung', 'Beschreibung', $this->dataset);
            $column->SetOrderable(true);
            $column->SetReplaceLFByBR(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridkommission.branche_beschreibung_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for angaben field
            //
            $column = new TextViewColumn('angaben', 'angaben', 'Angaben', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridkommission.branche_angaben_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $column->SetReplaceLFByBR(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridkommission.branche_notizen_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for name_fr field
            //
            $column = new TextViewColumn('name_fr', 'name_fr', 'Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridkommission.branche_name_fr_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for beschreibung_fr field
            //
            $column = new TextViewColumn('beschreibung_fr', 'beschreibung_fr', 'Beschreibung Fr', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridkommission.branche_beschreibung_fr_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for angaben_fr field
            //
            $column = new TextViewColumn('angaben_fr', 'angaben_fr', 'Angaben Fr', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridkommission.branche_angaben_fr_handler_view', $column);
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
    
        }
    
        protected function doCustomRenderTotal($totalValue, $aggregate, $columnName, &$customText, &$handled)
        {
    
        }
    
        protected function doCustomCompareColumn($columnName, $valueA, $valueB, &$result)
        {
    
        }
    
        protected function doBeforeInsertRecord($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName)
        {
    
        }
    
        protected function doBeforeUpdateRecord($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName)
        {
    
        }
    
        protected function doBeforeDeleteRecord($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName)
        {
    
        }
    
        protected function doAfterInsertRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doAfterUpdateRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
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
    
        protected function doGetCustomUploadFileName($fieldName, $rowData, &$result, &$handled, $originalFileName, $originalFileExtension, $fileSize)
        {
    
        }
    
        protected function doPrepareChart(Chart $chart)
        {
    
        }
    
    }
    
    // OnBeforePageExecute event handler
    
    
    
    class kommissionPage extends Page
    {
        protected function DoBeforeCreate()
        {
            $this->dataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`kommission`');
            $field = new IntegerField('id', null, null, true);
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, true);
            $field = new StringField('abkuerzung');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('abkuerzung_fr');
            $this->dataset->AddField($field, false);
            $field = new StringField('name');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('name_fr');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('rat_id');
            $this->dataset->AddField($field, false);
            $field = new StringField('typ');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('art');
            $this->dataset->AddField($field, false);
            $field = new StringField('beschreibung');
            $this->dataset->AddField($field, false);
            $field = new StringField('beschreibung_fr');
            $this->dataset->AddField($field, false);
            $field = new StringField('sachbereiche');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('sachbereiche_fr');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('anzahl_mitglieder');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('anzahl_nationalraete');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('anzahl_staenderaete');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('mutter_kommission_id');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('zweitrat_kommission_id');
            $this->dataset->AddField($field, false);
            $field = new DateField('von');
            $this->dataset->AddField($field, false);
            $field = new DateField('bis');
            $this->dataset->AddField($field, false);
            $field = new StringField('parlament_url');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('parlament_id');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('parlament_committee_number');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('parlament_subcommittee_number');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('parlament_type_code');
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
            $this->dataset->AddLookupField('rat_id', 'v_rat', new IntegerField('id'), new StringField('abkuerzung_mixed', 'rat_id_abkuerzung_mixed', 'rat_id_abkuerzung_mixed_v_rat'), 'rat_id_abkuerzung_mixed_v_rat');
            $this->dataset->AddLookupField('mutter_kommission_id', 'v_kommission', new IntegerField('id'), new StringField('anzeige_name', 'mutter_kommission_id_anzeige_name', 'mutter_kommission_id_anzeige_name_v_kommission'), 'mutter_kommission_id_anzeige_name_v_kommission');
            $this->dataset->AddLookupField('zweitrat_kommission_id', 'v_kommission', new IntegerField('id'), new StringField('anzeige_name_mixed', 'zweitrat_kommission_id_anzeige_name_mixed', 'zweitrat_kommission_id_anzeige_name_mixed_v_kommission'), 'zweitrat_kommission_id_anzeige_name_mixed_v_kommission');
        }
    
        protected function DoPrepare() {
            globalOnPreparePage($this);
        }
    
        protected function CreatePageNavigator()
        {
            $result = new CompositePageNavigator($this);
            
            $partitionNavigator = new PageNavigator('pnav', $this, $this->dataset);
            $partitionNavigator->SetRowsPerPage(20);
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
                new FilterColumn($this->dataset, 'id', 'id', $this->RenderText('Id')),
                new FilterColumn($this->dataset, 'abkuerzung', 'abkuerzung', $this->RenderText('Abkuerzung')),
                new FilterColumn($this->dataset, 'abkuerzung_fr', 'abkuerzung_fr', $this->RenderText('Abkuerzung Fr')),
                new FilterColumn($this->dataset, 'name', 'name', $this->RenderText('Name')),
                new FilterColumn($this->dataset, 'name_fr', 'name_fr', $this->RenderText('Name Fr')),
                new FilterColumn($this->dataset, 'typ', 'typ', $this->RenderText('Typ')),
                new FilterColumn($this->dataset, 'art', 'art', $this->RenderText('Art')),
                new FilterColumn($this->dataset, 'rat_id', 'rat_id_abkuerzung_mixed', $this->RenderText('Rat Id')),
                new FilterColumn($this->dataset, 'mutter_kommission_id', 'mutter_kommission_id_anzeige_name', $this->RenderText('Mutter Kommission')),
                new FilterColumn($this->dataset, 'beschreibung', 'beschreibung', $this->RenderText('Beschreibung')),
                new FilterColumn($this->dataset, 'beschreibung_fr', 'beschreibung_fr', $this->RenderText('Beschreibung Fr')),
                new FilterColumn($this->dataset, 'sachbereiche', 'sachbereiche', $this->RenderText('Sachbereiche')),
                new FilterColumn($this->dataset, 'sachbereiche_fr', 'sachbereiche_fr', $this->RenderText('Sachbereiche Fr')),
                new FilterColumn($this->dataset, 'anzahl_mitglieder', 'anzahl_mitglieder', $this->RenderText('Anzahl Mitglieder')),
                new FilterColumn($this->dataset, 'anzahl_nationalraete', 'anzahl_nationalraete', $this->RenderText('Anzahl Nationalräte')),
                new FilterColumn($this->dataset, 'anzahl_staenderaete', 'anzahl_staenderaete', $this->RenderText('Anzahl Ständeräte')),
                new FilterColumn($this->dataset, 'zweitrat_kommission_id', 'zweitrat_kommission_id_anzeige_name_mixed', $this->RenderText('Zweitrat Kommission')),
                new FilterColumn($this->dataset, 'von', 'von', $this->RenderText('Von')),
                new FilterColumn($this->dataset, 'bis', 'bis', $this->RenderText('Bis')),
                new FilterColumn($this->dataset, 'parlament_url', 'parlament_url', $this->RenderText('Parlament Url')),
                new FilterColumn($this->dataset, 'parlament_id', 'parlament_id', $this->RenderText('Parlament Id')),
                new FilterColumn($this->dataset, 'parlament_committee_number', 'parlament_committee_number', $this->RenderText('Parlament Committee Number')),
                new FilterColumn($this->dataset, 'parlament_subcommittee_number', 'parlament_subcommittee_number', $this->RenderText('Parlament Subcommittee Number')),
                new FilterColumn($this->dataset, 'parlament_type_code', 'parlament_type_code', $this->RenderText('Parlament Type Code')),
                new FilterColumn($this->dataset, 'notizen', 'notizen', $this->RenderText('Notizen')),
                new FilterColumn($this->dataset, 'eingabe_abgeschlossen_visa', 'eingabe_abgeschlossen_visa', $this->RenderText('Eingabe Abgeschlossen Visa')),
                new FilterColumn($this->dataset, 'eingabe_abgeschlossen_datum', 'eingabe_abgeschlossen_datum', $this->RenderText('Eingabe Abgeschlossen Datum')),
                new FilterColumn($this->dataset, 'kontrolliert_visa', 'kontrolliert_visa', $this->RenderText('Kontrolliert Visa')),
                new FilterColumn($this->dataset, 'kontrolliert_datum', 'kontrolliert_datum', $this->RenderText('Kontrolliert Datum')),
                new FilterColumn($this->dataset, 'freigabe_visa', 'freigabe_visa', $this->RenderText('Freigabe Visa')),
                new FilterColumn($this->dataset, 'freigabe_datum', 'freigabe_datum', $this->RenderText('Freigabe Datum')),
                new FilterColumn($this->dataset, 'created_visa', 'created_visa', $this->RenderText('Created Visa')),
                new FilterColumn($this->dataset, 'created_date', 'created_date', $this->RenderText('Created Date')),
                new FilterColumn($this->dataset, 'updated_visa', 'updated_visa', $this->RenderText('Updated Visa')),
                new FilterColumn($this->dataset, 'updated_date', 'updated_date', $this->RenderText('Updated Date'))
            );
        }
    
        protected function setupQuickFilter(QuickFilter $quickFilter, FixedKeysArray $columns)
        {
            $quickFilter
                ->addColumn($columns['id'])
                ->addColumn($columns['abkuerzung'])
                ->addColumn($columns['abkuerzung_fr'])
                ->addColumn($columns['name'])
                ->addColumn($columns['name_fr'])
                ->addColumn($columns['typ'])
                ->addColumn($columns['art'])
                ->addColumn($columns['mutter_kommission_id'])
                ->addColumn($columns['beschreibung'])
                ->addColumn($columns['beschreibung_fr'])
                ->addColumn($columns['sachbereiche'])
                ->addColumn($columns['sachbereiche_fr'])
                ->addColumn($columns['zweitrat_kommission_id'])
                ->addColumn($columns['parlament_url'])
                ->addColumn($columns['notizen']);
        }
    
        protected function setupColumnFilter(ColumnFilter $columnFilter)
        {
            $columnFilter
                ->setOptionsFor('typ')
                ->setOptionsFor('art')
                ->setOptionsFor('rat_id')
                ->setOptionsFor('mutter_kommission_id')
                ->setOptionsFor('zweitrat_kommission_id')
                ->setOptionsFor('von')
                ->setOptionsFor('bis')
                ->setOptionsFor('eingabe_abgeschlossen_datum')
                ->setOptionsFor('kontrolliert_datum')
                ->setOptionsFor('freigabe_datum')
                ->setOptionsFor('created_date')
                ->setOptionsFor('updated_date');
        }
    
        protected function setupFilterBuilder(FilterBuilder $filterBuilder, FixedKeysArray $columns)
        {
            $main_editor = new TextEdit('id_edit');
            
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
            
            $main_editor = new TextEdit('abkuerzung_edit');
            $main_editor->SetMaxLength(15);
            
            $filterBuilder->addColumn(
                $columns['abkuerzung'],
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
            
            $main_editor = new TextEdit('abkuerzung_fr_edit');
            $main_editor->SetMaxLength(15);
            
            $filterBuilder->addColumn(
                $columns['abkuerzung_fr'],
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
            $main_editor->SetMaxLength(100);
            
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
            
            $main_editor = new TextEdit('name_fr_edit');
            $main_editor->SetMaxLength(100);
            
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
            
            $main_editor = new ComboBox('typ_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $main_editor->addChoice($this->RenderText('kommission'), $this->RenderText('kommission'));
            $main_editor->addChoice($this->RenderText('spezialkommission'), $this->RenderText('Spezialkommission/Delegation im weiteren Sinne'));
            $main_editor->addChoice($this->RenderText('subkommission'), $this->RenderText('Subkommission/Delegation im engeren Sinne'));
            $main_editor->SetAllowNullValue(false);
            
            $multi_value_select_editor = new MultiValueSelect('typ');
            $multi_value_select_editor->setChoices($main_editor->getChoices());
            
            $text_editor = new TextEdit('typ');
            
            $filterBuilder->addColumn(
                $columns['typ'],
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
            
            $main_editor = new ComboBox('art_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $main_editor->addChoice($this->RenderText('legislativkommission'), $this->RenderText('legislativkommission'));
            $main_editor->addChoice($this->RenderText('aufsichtskommission'), $this->RenderText('aufsichtskommission'));
            $main_editor->addChoice($this->RenderText('parlam verwaltungskontrolle'), $this->RenderText('parlam verwaltungskontrolle'));
            $main_editor->addChoice($this->RenderText('weitere kommission'), $this->RenderText('weitere kommission'));
            $main_editor->addChoice($this->RenderText('delegation im weiteren sinne'), $this->RenderText('delegation im weiteren sinne'));
            $main_editor->SetAllowNullValue(false);
            
            $multi_value_select_editor = new MultiValueSelect('art');
            $multi_value_select_editor->setChoices($main_editor->getChoices());
            
            $text_editor = new TextEdit('art');
            
            $filterBuilder->addColumn(
                $columns['art'],
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
            
            $main_editor = new AutocompleteComboBox('rat_id_edit', $this->CreateLinkBuilder());
            $main_editor->setAllowClear(true);
            $main_editor->setMinimumInputLength(0);
            $main_editor->SetAllowNullValue(false);
            $main_editor->SetHandlerName('filter_builder_rat_id_abkuerzung_mixed_search');
            
            $multi_value_select_editor = new RemoteMultiValueSelect('rat_id', $this->CreateLinkBuilder());
            $multi_value_select_editor->SetHandlerName('filter_builder_rat_id_abkuerzung_mixed_search');
            
            $text_editor = new TextEdit('rat_id');
            
            $filterBuilder->addColumn(
                $columns['rat_id'],
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
            
            $main_editor = new AutocompleteComboBox('mutter_kommission_id_edit', $this->CreateLinkBuilder());
            $main_editor->setAllowClear(true);
            $main_editor->setMinimumInputLength(0);
            $main_editor->SetAllowNullValue(false);
            $main_editor->SetHandlerName('filter_builder_mutter_kommission_id_anzeige_name_search');
            
            $multi_value_select_editor = new RemoteMultiValueSelect('mutter_kommission_id', $this->CreateLinkBuilder());
            $multi_value_select_editor->SetHandlerName('filter_builder_mutter_kommission_id_anzeige_name_search');
            
            $text_editor = new TextEdit('mutter_kommission_id');
            
            $filterBuilder->addColumn(
                $columns['mutter_kommission_id'],
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
            
            $main_editor = new TextEdit('beschreibung');
            
            $filterBuilder->addColumn(
                $columns['beschreibung'],
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
            
            $main_editor = new TextEdit('beschreibung_fr');
            
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
            
            $main_editor = new TextEdit('sachbereiche');
            
            $filterBuilder->addColumn(
                $columns['sachbereiche'],
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
            
            $main_editor = new TextEdit('sachbereiche_fr');
            
            $filterBuilder->addColumn(
                $columns['sachbereiche_fr'],
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
            
            $main_editor = new TextEdit('anzahl_mitglieder_edit');
            
            $filterBuilder->addColumn(
                $columns['anzahl_mitglieder'],
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
            
            $main_editor = new AutocompleteComboBox('zweitrat_kommission_id_edit', $this->CreateLinkBuilder());
            $main_editor->setAllowClear(true);
            $main_editor->setMinimumInputLength(0);
            $main_editor->SetAllowNullValue(false);
            $main_editor->SetHandlerName('filter_builder_zweitrat_kommission_id_anzeige_name_mixed_search');
            
            $multi_value_select_editor = new RemoteMultiValueSelect('zweitrat_kommission_id', $this->CreateLinkBuilder());
            $multi_value_select_editor->SetHandlerName('filter_builder_zweitrat_kommission_id_anzeige_name_mixed_search');
            
            $text_editor = new TextEdit('zweitrat_kommission_id');
            
            $filterBuilder->addColumn(
                $columns['zweitrat_kommission_id'],
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
            
            $main_editor = new DateTimeEdit('von_edit', false, 'd.m.Y');
            
            $filterBuilder->addColumn(
                $columns['von'],
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
            
            $main_editor = new DateTimeEdit('bis_edit', false, 'd.m.Y');
            
            $filterBuilder->addColumn(
                $columns['bis'],
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
            
            $main_editor = new TextEdit('parlament_url_edit');
            $main_editor->SetMaxLength(255);
            
            $filterBuilder->addColumn(
                $columns['parlament_url'],
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
            
            $main_editor = new TextEdit('parlament_id_edit');
            
            $filterBuilder->addColumn(
                $columns['parlament_id'],
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
            
            $main_editor = new TextEdit('parlament_committee_number_edit');
            
            $filterBuilder->addColumn(
                $columns['parlament_committee_number'],
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
            
            $main_editor = new TextEdit('parlament_subcommittee_number_edit');
            
            $filterBuilder->addColumn(
                $columns['parlament_subcommittee_number'],
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
            
            $main_editor = new TextEdit('parlament_type_code_edit');
            
            $filterBuilder->addColumn(
                $columns['parlament_type_code'],
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
            
            $main_editor = new TextEdit('notizen');
            
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
            
            $main_editor = new TextEdit('eingabe_abgeschlossen_visa_edit');
            $main_editor->SetMaxLength(10);
            
            $filterBuilder->addColumn(
                $columns['eingabe_abgeschlossen_visa'],
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
            
            $main_editor = new DateTimeEdit('eingabe_abgeschlossen_datum_edit', false, 'd.m.Y H:i:s');
            
            $filterBuilder->addColumn(
                $columns['eingabe_abgeschlossen_datum'],
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
            
            $main_editor = new TextEdit('kontrolliert_visa_edit');
            $main_editor->SetMaxLength(10);
            
            $filterBuilder->addColumn(
                $columns['kontrolliert_visa'],
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
            
            $main_editor = new DateTimeEdit('kontrolliert_datum_edit', false, 'd.m.Y H:i:s');
            
            $filterBuilder->addColumn(
                $columns['kontrolliert_datum'],
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
            
            $main_editor = new TextEdit('freigabe_visa_edit');
            $main_editor->SetMaxLength(10);
            
            $filterBuilder->addColumn(
                $columns['freigabe_visa'],
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
            
            $main_editor = new DateTimeEdit('freigabe_datum_edit', false, 'd.m.Y H:i:s');
            
            $filterBuilder->addColumn(
                $columns['freigabe_datum'],
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
            
            $main_editor = new TextEdit('created_visa_edit');
            $main_editor->SetMaxLength(10);
            
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
            
            $main_editor = new DateTimeEdit('created_date_edit', false, 'd.m.Y H:i:s');
            
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
            $main_editor->SetMaxLength(10);
            
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
            
            $main_editor = new DateTimeEdit('updated_date_edit', false, 'd.m.Y H:i:s');
            
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
        }
    
        protected function AddOperationsColumns(Grid $grid)
        {
            $actions = $grid->getActions();
            $actions->setCaption($this->GetLocalizerCaptions()->GetMessageString('Actions'));
            $actions->setPosition(ActionList::POSITION_LEFT);
            
            if ($this->GetSecurityInfo()->HasViewGrant())
            {
                $operation = new LinkOperation($this->GetLocalizerCaptions()->GetMessageString('View'), OPERATION_VIEW, $this->dataset, $grid);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
            }
            
            if ($this->GetSecurityInfo()->HasEditGrant())
            {
                $operation = new LinkOperation($this->GetLocalizerCaptions()->GetMessageString('Edit'), OPERATION_EDIT, $this->dataset, $grid);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
                $operation->OnShow->AddListener('ShowEditButtonHandler', $this);
            }
            
            if ($this->GetSecurityInfo()->HasDeleteGrant())
            {
                $operation = new LinkOperation($this->GetLocalizerCaptions()->GetMessageString('Delete'), OPERATION_DELETE, $this->dataset, $grid);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
                $operation->OnShow->AddListener('ShowDeleteButtonHandler', $this);
                $operation->SetAdditionalAttribute('data-modal-operation', 'delete');
                $operation->SetAdditionalAttribute('data-delete-handler-name', $this->GetModalGridDeleteHandler());
            }
            
            if ($this->GetSecurityInfo()->HasAddGrant())
            {
                $operation = new LinkOperation($this->GetLocalizerCaptions()->GetMessageString('Copy'), OPERATION_COPY, $this->dataset, $grid);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
            }
        }
    
        protected function AddFieldColumns(Grid $grid, $withDetails = true)
        {
            if (GetCurrentUserGrantForDataSource('kommission.v_in_kommission')->HasViewGrant() && $withDetails)
            {
            //
            // View column for kommission_v_in_kommission detail
            //
            $column = new DetailColumn(array('id'), 'kommission.v_in_kommission', 'kommission_v_in_kommission_handler', $this->dataset, 'Parlamentarier in Kommission');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $grid->AddViewColumn($column);
            }
            
            if (GetCurrentUserGrantForDataSource('kommission.branche')->HasViewGrant() && $withDetails)
            {
            //
            // View column for kommission_branche detail
            //
            $column = new DetailColumn(array('id'), 'kommission.branche', 'kommission_branche_handler', $this->dataset, 'Branche');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $grid->AddViewColumn($column);
            }
            
            //
            // View column for id field
            //
            $column = new TextViewColumn('id', 'id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setHrefTemplate('http://lobbywatch.ch/de/daten/kommission/%id%');
            $column->setTarget('_blank');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('Technischer Schlüssel der Kommission.  Der Link zeigt auf den Eintrag in der Lobbywatch.ch Webseite.'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for abkuerzung field
            //
            $column = new TextViewColumn('abkuerzung', 'abkuerzung', 'Abkuerzung', $this->dataset);
            $column->SetOrderable(true);
            $column->setHrefTemplate('auswertung/anteil.php?option=kommission&id=%id%');
            $column->setTarget('_blank');
            $column->setBold(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('Kürzel der Kommission; Link zeigt den Bearbeitungsstand der Kommissionan an.'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for abkuerzung_fr field
            //
            $column = new TextViewColumn('abkuerzung_fr', 'abkuerzung_fr', 'Abkuerzung Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('Französisches Kürzel der Kommission'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('name', 'name', 'Name', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('kommissionGrid_name_handler_list');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('Ausgeschriebener Name der Kommission'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for name_fr field
            //
            $column = new TextViewColumn('name_fr', 'name_fr', 'Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('kommissionGrid_name_fr_handler_list');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('Ausgeschriebener französischer Name der Kommission'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for typ field
            //
            $column = new TextViewColumn('typ', 'typ', 'Typ', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('Typ einer Kommission (Spezialkommission ist eine Delegation im weiteren Sinne).'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for art field
            //
            $column = new TextViewColumn('art', 'art', 'Art', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('Art der Kommission gemäss Einteilung auf Parlament.ch. Achtung für Delegationen im engeren Sinne (= Subkommissionen) sollte die Art der Mutterkommission gewählt werden, z.B. GPDel ist eine Subkommission der GPK und gehört somit zu den Aufsichtskommissionen.'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for abkuerzung_mixed field
            //
            $column = new TextViewColumn('rat_id', 'rat_id_abkuerzung_mixed', 'Rat Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('Ratszugehörigkeit; Fremdschlüssel des Rates'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for anzeige_name field
            //
            $column = new TextViewColumn('mutter_kommission_id', 'mutter_kommission_id_anzeige_name', 'Mutter Kommission', $this->dataset);
            $column->SetOrderable(true);
            $column->setHrefTemplate('kommission.php?operation=view&pk0=%mutter_kommission_id%');
            $column->setTarget('');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('Zugehörige Kommission von Delegationen im engeren Sinne (=Subkommissionen).  Also die "Oberkommission".'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for beschreibung field
            //
            $column = new TextViewColumn('beschreibung', 'beschreibung', 'Beschreibung', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('kommissionGrid_beschreibung_handler_list');
            $column->SetReplaceLFByBR(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('Beschreibung der Kommission'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for beschreibung_fr field
            //
            $column = new TextViewColumn('beschreibung_fr', 'beschreibung_fr', 'Beschreibung Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('kommissionGrid_beschreibung_fr_handler_list');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('Französische Beschreibung der Kommission'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for sachbereiche field
            //
            $column = new TextViewColumn('sachbereiche', 'sachbereiche', 'Sachbereiche', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('kommissionGrid_sachbereiche_handler_list');
            $column->SetReplaceLFByBR(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('Liste der Sachbereiche der Kommission, abgetrennt durch ";".'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for sachbereiche_fr field
            //
            $column = new TextViewColumn('sachbereiche_fr', 'sachbereiche_fr', 'Sachbereiche Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('kommissionGrid_sachbereiche_fr_handler_list');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('Liste der Sachbereiche der Kommission auf französisch, abgetrennt durch ";".'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for anzahl_mitglieder field
            //
            $column = new TextViewColumn('anzahl_mitglieder', 'anzahl_mitglieder', 'Anzahl Mitglieder', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('Anzahl Kommissionsmitglieder'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for anzeige_name_mixed field
            //
            $column = new TextViewColumn('zweitrat_kommission_id', 'zweitrat_kommission_id_anzeige_name_mixed', 'Zweitrat Kommission', $this->dataset);
            $column->SetOrderable(true);
            $column->setHrefTemplate('kommission.php?operation=view&pk0=%zweitrat_kommission_id%');
            $column->setTarget('');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('Entsprechende Kommission im anderen Rat, Stände- o. Nationalratskommission'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for von field
            //
            $column = new DateTimeViewColumn('von', 'von', 'Von', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y');
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('Beginn der Kommission, leer (NULL) = unbekannt'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for bis field
            //
            $column = new DateTimeViewColumn('bis', 'bis', 'Bis', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y');
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('Ende der Kommission, leer (NULL) = aktuell gültig, nicht leer = historischer Eintrag'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for parlament_url field
            //
            $column = new TextViewColumn('parlament_url', 'parlament_url', 'Parlament Url', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('kommissionGrid_parlament_url_handler_list');
            $column->setHrefTemplate('%parlament_url%');
            $column->setTarget('_blank');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('Link zur Seite auf Parlament.ch'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for parlament_id field
            //
            $column = new TextViewColumn('parlament_id', 'parlament_id', 'Parlament Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('Kommissions-ID von ws.parlament.ch'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for parlament_committee_number field
            //
            $column = new TextViewColumn('parlament_committee_number', 'parlament_committee_number', 'Parlament Committee Number', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('committeeNumber auf ws.parlament.ch'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for parlament_subcommittee_number field
            //
            $column = new TextViewColumn('parlament_subcommittee_number', 'parlament_subcommittee_number', 'Parlament Subcommittee Number', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('subcommitteeNumber auf ws.parlament.ch'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for parlament_type_code field
            //
            $column = new TextViewColumn('parlament_type_code', 'parlament_type_code', 'Parlament Type Code', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('typeCode von ws.parlament.ch'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('kommissionGrid_notizen_handler_list');
            $column->SetReplaceLFByBR(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_visa field
            //
            $column = new TextViewColumn('eingabe_abgeschlossen_visa', 'eingabe_abgeschlossen_visa', 'Eingabe Abgeschlossen Visa', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('Kürzel der Person, welche die Eingabe abgeschlossen hat.'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_datum field
            //
            $column = new DateTimeViewColumn('eingabe_abgeschlossen_datum', 'eingabe_abgeschlossen_datum', 'Eingabe Abgeschlossen Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for kontrolliert_visa field
            //
            $column = new TextViewColumn('kontrolliert_visa', 'kontrolliert_visa', 'Kontrolliert Visa', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('Kürzel der Person, welche die Eingabe kontrolliert hat.'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for kontrolliert_datum field
            //
            $column = new DateTimeViewColumn('kontrolliert_datum', 'kontrolliert_datum', 'Kontrolliert Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for freigabe_visa field
            //
            $column = new TextViewColumn('freigabe_visa', 'freigabe_visa', 'Freigabe Visa', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('Freigabe von (Freigabe = Daten sind fertig)'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for freigabe_datum field
            //
            $column = new DateTimeViewColumn('freigabe_datum', 'freigabe_datum', 'Freigabe Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('Freigabedatum (Freigabe = Daten sind fertig)'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for created_visa field
            //
            $column = new TextViewColumn('created_visa', 'created_visa', 'Created Visa', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('Erstellt von'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for created_date field
            //
            $column = new DateTimeViewColumn('created_date', 'created_date', 'Created Date', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('Erstellt am'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for updated_visa field
            //
            $column = new TextViewColumn('updated_visa', 'updated_visa', 'Updated Visa', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('Abgeändert von'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for updated_date field
            //
            $column = new DateTimeViewColumn('updated_date', 'updated_date', 'Updated Date', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('Abgeändert am'));
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
            $column->setHrefTemplate('http://lobbywatch.ch/de/daten/kommission/%id%');
            $column->setTarget('_blank');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for abkuerzung field
            //
            $column = new TextViewColumn('abkuerzung', 'abkuerzung', 'Abkuerzung', $this->dataset);
            $column->SetOrderable(true);
            $column->setHrefTemplate('auswertung/anteil.php?option=kommission&id=%id%');
            $column->setTarget('_blank');
            $column->setBold(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for abkuerzung_fr field
            //
            $column = new TextViewColumn('abkuerzung_fr', 'abkuerzung_fr', 'Abkuerzung Fr', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('name', 'name', 'Name', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('kommissionGrid_name_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for name_fr field
            //
            $column = new TextViewColumn('name_fr', 'name_fr', 'Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('kommissionGrid_name_fr_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for typ field
            //
            $column = new TextViewColumn('typ', 'typ', 'Typ', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for art field
            //
            $column = new TextViewColumn('art', 'art', 'Art', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for abkuerzung_mixed field
            //
            $column = new TextViewColumn('rat_id', 'rat_id_abkuerzung_mixed', 'Rat Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for anzeige_name field
            //
            $column = new TextViewColumn('mutter_kommission_id', 'mutter_kommission_id_anzeige_name', 'Mutter Kommission', $this->dataset);
            $column->SetOrderable(true);
            $column->setHrefTemplate('kommission.php?operation=view&pk0=%mutter_kommission_id%');
            $column->setTarget('');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for beschreibung field
            //
            $column = new TextViewColumn('beschreibung', 'beschreibung', 'Beschreibung', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('kommissionGrid_beschreibung_handler_view');
            $column->SetReplaceLFByBR(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for beschreibung_fr field
            //
            $column = new TextViewColumn('beschreibung_fr', 'beschreibung_fr', 'Beschreibung Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('kommissionGrid_beschreibung_fr_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for sachbereiche field
            //
            $column = new TextViewColumn('sachbereiche', 'sachbereiche', 'Sachbereiche', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('kommissionGrid_sachbereiche_handler_view');
            $column->SetReplaceLFByBR(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for sachbereiche_fr field
            //
            $column = new TextViewColumn('sachbereiche_fr', 'sachbereiche_fr', 'Sachbereiche Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('kommissionGrid_sachbereiche_fr_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for anzahl_mitglieder field
            //
            $column = new TextViewColumn('anzahl_mitglieder', 'anzahl_mitglieder', 'Anzahl Mitglieder', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for anzeige_name_mixed field
            //
            $column = new TextViewColumn('zweitrat_kommission_id', 'zweitrat_kommission_id_anzeige_name_mixed', 'Zweitrat Kommission', $this->dataset);
            $column->SetOrderable(true);
            $column->setHrefTemplate('kommission.php?operation=view&pk0=%zweitrat_kommission_id%');
            $column->setTarget('');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for von field
            //
            $column = new DateTimeViewColumn('von', 'von', 'Von', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for bis field
            //
            $column = new DateTimeViewColumn('bis', 'bis', 'Bis', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for parlament_url field
            //
            $column = new TextViewColumn('parlament_url', 'parlament_url', 'Parlament Url', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('kommissionGrid_parlament_url_handler_view');
            $column->setHrefTemplate('%parlament_url%');
            $column->setTarget('_blank');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for parlament_id field
            //
            $column = new TextViewColumn('parlament_id', 'parlament_id', 'Parlament Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for parlament_committee_number field
            //
            $column = new TextViewColumn('parlament_committee_number', 'parlament_committee_number', 'Parlament Committee Number', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for parlament_subcommittee_number field
            //
            $column = new TextViewColumn('parlament_subcommittee_number', 'parlament_subcommittee_number', 'Parlament Subcommittee Number', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for parlament_type_code field
            //
            $column = new TextViewColumn('parlament_type_code', 'parlament_type_code', 'Parlament Type Code', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('kommissionGrid_notizen_handler_view');
            $column->SetReplaceLFByBR(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_visa field
            //
            $column = new TextViewColumn('eingabe_abgeschlossen_visa', 'eingabe_abgeschlossen_visa', 'Eingabe Abgeschlossen Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_datum field
            //
            $column = new DateTimeViewColumn('eingabe_abgeschlossen_datum', 'eingabe_abgeschlossen_datum', 'Eingabe Abgeschlossen Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for kontrolliert_visa field
            //
            $column = new TextViewColumn('kontrolliert_visa', 'kontrolliert_visa', 'Kontrolliert Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for kontrolliert_datum field
            //
            $column = new DateTimeViewColumn('kontrolliert_datum', 'kontrolliert_datum', 'Kontrolliert Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for freigabe_visa field
            //
            $column = new TextViewColumn('freigabe_visa', 'freigabe_visa', 'Freigabe Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for freigabe_datum field
            //
            $column = new DateTimeViewColumn('freigabe_datum', 'freigabe_datum', 'Freigabe Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
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
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
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
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
        }
    
        protected function AddEditColumns(Grid $grid)
        {
            //
            // Edit column for abkuerzung field
            //
            $editor = new TextEdit('abkuerzung_edit');
            $editor->SetMaxLength(15);
            $editColumn = new CustomEditColumn('Abkuerzung', 'abkuerzung', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for abkuerzung_fr field
            //
            $editor = new TextEdit('abkuerzung_fr_edit');
            $editor->SetMaxLength(15);
            $editColumn = new CustomEditColumn('Abkuerzung Fr', 'abkuerzung_fr', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for name field
            //
            $editor = new TextEdit('name_edit');
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
            $editor->SetMaxLength(100);
            $editColumn = new CustomEditColumn('Name Fr', 'name_fr', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for typ field
            //
            $editor = new ComboBox('typ_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $editor->addChoice($this->RenderText('kommission'), $this->RenderText('kommission'));
            $editor->addChoice($this->RenderText('spezialkommission'), $this->RenderText('Spezialkommission/Delegation im weiteren Sinne'));
            $editor->addChoice($this->RenderText('subkommission'), $this->RenderText('Subkommission/Delegation im engeren Sinne'));
            $editColumn = new CustomEditColumn('Typ', 'typ', $editor, $this->dataset);
            $editColumn->SetAllowSetToDefault(false); /*afterburner*/ 
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for art field
            //
            $editor = new ComboBox('art_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $editor->addChoice($this->RenderText('legislativkommission'), $this->RenderText('legislativkommission'));
            $editor->addChoice($this->RenderText('aufsichtskommission'), $this->RenderText('aufsichtskommission'));
            $editor->addChoice($this->RenderText('parlam verwaltungskontrolle'), $this->RenderText('parlam verwaltungskontrolle'));
            $editor->addChoice($this->RenderText('weitere kommission'), $this->RenderText('weitere kommission'));
            $editor->addChoice($this->RenderText('delegation im weiteren sinne'), $this->RenderText('delegation im weiteren sinne'));
            $editColumn = new CustomEditColumn('Art', 'art', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for rat_id field
            //
            $editor = new ComboBox('rat_id_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`v_rat`');
            $field = new StringField('anzeige_name');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_fr');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_mixed');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('abkuerzung_mixed');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('id');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
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
            $field = new IntegerField('created_date_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('updated_date_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('eingabe_abgeschlossen_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('kontrolliert_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('freigabe_datum_unix');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('abkuerzung_mixed', GetOrderTypeAsSQL(otAscending));
            $editColumn = new LookUpEditColumn(
                'Rat Id', 
                'rat_id', 
                $editor, 
                $this->dataset, 'id', 'abkuerzung_mixed', $lookupDataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for mutter_kommission_id field
            //
            $editor = new ComboBox('mutter_kommission_id_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
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
            $field = new IntegerField('anzahl_mitglieder');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_nationalraete');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_staenderaete');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('mutter_kommission_id');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('zweitrat_kommission_id');
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
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('updated_date_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('eingabe_abgeschlossen_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('kontrolliert_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('freigabe_datum_unix');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('anzeige_name', GetOrderTypeAsSQL(otAscending));
            $editColumn = new LookUpEditColumn(
                'Mutter Kommission', 
                'mutter_kommission_id', 
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
            $editColumn->SetAllowSetToNull(true);
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
            // Edit column for sachbereiche field
            //
            $editor = new TextAreaEdit('sachbereiche_edit', 50, 8);
            $editColumn = new CustomEditColumn('Sachbereiche', 'sachbereiche', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for sachbereiche_fr field
            //
            $editor = new TextAreaEdit('sachbereiche_fr_edit', 50, 8);
            $editColumn = new CustomEditColumn('Sachbereiche Fr', 'sachbereiche_fr', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for anzahl_mitglieder field
            //
            $editor = new TextEdit('anzahl_mitglieder_edit');
            $editColumn = new CustomEditColumn('Anzahl Mitglieder', 'anzahl_mitglieder', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $validator = new MaxValueValidator(246, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MaxValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new MinValueValidator(0, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MinValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new DigitsValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('DigitsValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for zweitrat_kommission_id field
            //
            $editor = new ComboBox('zweitrat_kommission_id_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
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
            $field = new IntegerField('anzahl_mitglieder');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_nationalraete');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_staenderaete');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('mutter_kommission_id');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('zweitrat_kommission_id');
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
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('updated_date_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('eingabe_abgeschlossen_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('kontrolliert_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('freigabe_datum_unix');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('anzeige_name_mixed', GetOrderTypeAsSQL(otAscending));
            $editColumn = new LookUpEditColumn(
                'Zweitrat Kommission', 
                'zweitrat_kommission_id', 
                $editor, 
                $this->dataset, 'id', 'anzeige_name_mixed', $lookupDataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for von field
            //
            $editor = new DateTimeEdit('von_edit', false, 'd.m.Y');
            $editColumn = new CustomEditColumn('Von', 'von', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for bis field
            //
            $editor = new DateTimeEdit('bis_edit', false, 'd.m.Y');
            $editColumn = new CustomEditColumn('Bis', 'bis', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for parlament_url field
            //
            $editor = new TextEdit('parlament_url_edit');
            $editor->SetMaxLength(255);
            $editColumn = new CustomEditColumn('Parlament Url', 'parlament_url', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $validator = new UrlValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('UrlValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for parlament_id field
            //
            $editor = new TextEdit('parlament_id_edit');
            $editColumn = new CustomEditColumn('Parlament Id', 'parlament_id', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for parlament_committee_number field
            //
            $editor = new TextEdit('parlament_committee_number_edit');
            $editColumn = new CustomEditColumn('Parlament Committee Number', 'parlament_committee_number', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for parlament_subcommittee_number field
            //
            $editor = new TextEdit('parlament_subcommittee_number_edit');
            $editColumn = new CustomEditColumn('Parlament Subcommittee Number', 'parlament_subcommittee_number', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for parlament_type_code field
            //
            $editor = new TextEdit('parlament_type_code_edit');
            $editColumn = new CustomEditColumn('Parlament Type Code', 'parlament_type_code', $editor, $this->dataset);
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
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Eingabe Abgeschlossen Visa', 'eingabe_abgeschlossen_visa', $editor, $this->dataset);
            $editColumn->SetReadOnly(true);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for eingabe_abgeschlossen_datum field
            //
            $editor = new DateTimeEdit('eingabe_abgeschlossen_datum_edit', false, 'd.m.Y H:i:s');
            $editColumn = new CustomEditColumn('Eingabe Abgeschlossen Datum', 'eingabe_abgeschlossen_datum', $editor, $this->dataset);
            $editColumn->SetReadOnly(true);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for kontrolliert_visa field
            //
            $editor = new TextEdit('kontrolliert_visa_edit');
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Kontrolliert Visa', 'kontrolliert_visa', $editor, $this->dataset);
            $editColumn->SetReadOnly(true);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for kontrolliert_datum field
            //
            $editor = new DateTimeEdit('kontrolliert_datum_edit', false, 'd.m.Y H:i:s');
            $editColumn = new CustomEditColumn('Kontrolliert Datum', 'kontrolliert_datum', $editor, $this->dataset);
            $editColumn->SetReadOnly(true);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for freigabe_visa field
            //
            $editor = new TextEdit('freigabe_visa_edit');
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Freigabe Visa', 'freigabe_visa', $editor, $this->dataset);
            $editColumn->SetReadOnly(true);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for freigabe_datum field
            //
            $editor = new DateTimeEdit('freigabe_datum_edit', false, 'd.m.Y H:i:s');
            $editColumn = new CustomEditColumn('Freigabe Datum', 'freigabe_datum', $editor, $this->dataset);
            $editColumn->SetReadOnly(true);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for created_visa field
            //
            $editor = new TextEdit('created_visa_edit');
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Created Visa', 'created_visa', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for created_date field
            //
            $editor = new DateTimeEdit('created_date_edit', false, 'd.m.Y H:i:s');
            $editColumn = new CustomEditColumn('Created Date', 'created_date', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for updated_visa field
            //
            $editor = new TextEdit('updated_visa_edit');
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Updated Visa', 'updated_visa', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for updated_date field
            //
            $editor = new DateTimeEdit('updated_date_edit', false, 'd.m.Y H:i:s');
            $editColumn = new CustomEditColumn('Updated Date', 'updated_date', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
        }
    
        protected function AddInsertColumns(Grid $grid)
        {
            //
            // Edit column for abkuerzung field
            //
            $editor = new TextEdit('abkuerzung_edit');
            $editor->SetMaxLength(15);
            $editColumn = new CustomEditColumn('Abkuerzung', 'abkuerzung', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for abkuerzung_fr field
            //
            $editor = new TextEdit('abkuerzung_fr_edit');
            $editor->SetMaxLength(15);
            $editColumn = new CustomEditColumn('Abkuerzung Fr', 'abkuerzung_fr', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for name field
            //
            $editor = new TextEdit('name_edit');
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
            $editor->SetMaxLength(100);
            $editColumn = new CustomEditColumn('Name Fr', 'name_fr', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for typ field
            //
            $editor = new ComboBox('typ_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $editor->addChoice($this->RenderText('kommission'), $this->RenderText('kommission'));
            $editor->addChoice($this->RenderText('spezialkommission'), $this->RenderText('Spezialkommission/Delegation im weiteren Sinne'));
            $editor->addChoice($this->RenderText('subkommission'), $this->RenderText('Subkommission/Delegation im engeren Sinne'));
            $editColumn = new CustomEditColumn('Typ', 'typ', $editor, $this->dataset);
            $editColumn->SetAllowSetToDefault(false); /*afterburner*/ 
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for art field
            //
            $editor = new ComboBox('art_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $editor->addChoice($this->RenderText('legislativkommission'), $this->RenderText('legislativkommission'));
            $editor->addChoice($this->RenderText('aufsichtskommission'), $this->RenderText('aufsichtskommission'));
            $editor->addChoice($this->RenderText('parlam verwaltungskontrolle'), $this->RenderText('parlam verwaltungskontrolle'));
            $editor->addChoice($this->RenderText('weitere kommission'), $this->RenderText('weitere kommission'));
            $editor->addChoice($this->RenderText('delegation im weiteren sinne'), $this->RenderText('delegation im weiteren sinne'));
            $editColumn = new CustomEditColumn('Art', 'art', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for rat_id field
            //
            $editor = new ComboBox('rat_id_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`v_rat`');
            $field = new StringField('anzeige_name');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_fr');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_mixed');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('abkuerzung_mixed');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('id');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
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
            $field = new IntegerField('created_date_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('updated_date_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('eingabe_abgeschlossen_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('kontrolliert_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('freigabe_datum_unix');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('abkuerzung_mixed', GetOrderTypeAsSQL(otAscending));
            $editColumn = new LookUpEditColumn(
                'Rat Id', 
                'rat_id', 
                $editor, 
                $this->dataset, 'id', 'abkuerzung_mixed', $lookupDataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for mutter_kommission_id field
            //
            $editor = new ComboBox('mutter_kommission_id_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
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
            $field = new IntegerField('anzahl_mitglieder');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_nationalraete');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_staenderaete');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('mutter_kommission_id');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('zweitrat_kommission_id');
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
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('updated_date_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('eingabe_abgeschlossen_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('kontrolliert_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('freigabe_datum_unix');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('anzeige_name', GetOrderTypeAsSQL(otAscending));
            $editColumn = new LookUpEditColumn(
                'Mutter Kommission', 
                'mutter_kommission_id', 
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
            $editColumn->SetAllowSetToNull(true);
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
            // Edit column for sachbereiche field
            //
            $editor = new TextAreaEdit('sachbereiche_edit', 50, 8);
            $editColumn = new CustomEditColumn('Sachbereiche', 'sachbereiche', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for sachbereiche_fr field
            //
            $editor = new TextAreaEdit('sachbereiche_fr_edit', 50, 8);
            $editColumn = new CustomEditColumn('Sachbereiche Fr', 'sachbereiche_fr', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for anzahl_mitglieder field
            //
            $editor = new TextEdit('anzahl_mitglieder_edit');
            $editColumn = new CustomEditColumn('Anzahl Mitglieder', 'anzahl_mitglieder', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $validator = new MaxValueValidator(246, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MaxValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new MinValueValidator(0, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MinValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new DigitsValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('DigitsValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for zweitrat_kommission_id field
            //
            $editor = new ComboBox('zweitrat_kommission_id_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
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
            $field = new IntegerField('anzahl_mitglieder');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_nationalraete');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_staenderaete');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('mutter_kommission_id');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('zweitrat_kommission_id');
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
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('updated_date_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('eingabe_abgeschlossen_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('kontrolliert_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('freigabe_datum_unix');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('anzeige_name_mixed', GetOrderTypeAsSQL(otAscending));
            $editColumn = new LookUpEditColumn(
                'Zweitrat Kommission', 
                'zweitrat_kommission_id', 
                $editor, 
                $this->dataset, 'id', 'anzeige_name_mixed', $lookupDataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for von field
            //
            $editor = new DateTimeEdit('von_edit', false, 'd.m.Y');
            $editColumn = new CustomEditColumn('Von', 'von', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for bis field
            //
            $editor = new DateTimeEdit('bis_edit', false, 'd.m.Y');
            $editColumn = new CustomEditColumn('Bis', 'bis', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for parlament_url field
            //
            $editor = new TextEdit('parlament_url_edit');
            $editor->SetMaxLength(255);
            $editColumn = new CustomEditColumn('Parlament Url', 'parlament_url', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $validator = new UrlValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('UrlValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for parlament_id field
            //
            $editor = new TextEdit('parlament_id_edit');
            $editColumn = new CustomEditColumn('Parlament Id', 'parlament_id', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for parlament_committee_number field
            //
            $editor = new TextEdit('parlament_committee_number_edit');
            $editColumn = new CustomEditColumn('Parlament Committee Number', 'parlament_committee_number', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for parlament_subcommittee_number field
            //
            $editor = new TextEdit('parlament_subcommittee_number_edit');
            $editColumn = new CustomEditColumn('Parlament Subcommittee Number', 'parlament_subcommittee_number', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for parlament_type_code field
            //
            $editor = new TextEdit('parlament_type_code_edit');
            $editColumn = new CustomEditColumn('Parlament Type Code', 'parlament_type_code', $editor, $this->dataset);
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
            $grid->SetShowAddButton(true && $this->GetSecurityInfo()->HasAddGrant());
        }
    
        protected function AddPrintColumns(Grid $grid)
        {
            //
            // View column for id field
            //
            $column = new TextViewColumn('id', 'id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setHrefTemplate('http://lobbywatch.ch/de/daten/kommission/%id%');
            $column->setTarget('_blank');
            $grid->AddPrintColumn($column);
            
            //
            // View column for abkuerzung field
            //
            $column = new TextViewColumn('abkuerzung', 'abkuerzung', 'Abkuerzung', $this->dataset);
            $column->SetOrderable(true);
            $column->setHrefTemplate('auswertung/anteil.php?option=kommission&id=%id%');
            $column->setTarget('_blank');
            $column->setBold(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for abkuerzung_fr field
            //
            $column = new TextViewColumn('abkuerzung_fr', 'abkuerzung_fr', 'Abkuerzung Fr', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('name', 'name', 'Name', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('kommissionGrid_name_handler_print');
            $grid->AddPrintColumn($column);
            
            //
            // View column for name_fr field
            //
            $column = new TextViewColumn('name_fr', 'name_fr', 'Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('kommissionGrid_name_fr_handler_print');
            $grid->AddPrintColumn($column);
            
            //
            // View column for typ field
            //
            $column = new TextViewColumn('typ', 'typ', 'Typ', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for art field
            //
            $column = new TextViewColumn('art', 'art', 'Art', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for abkuerzung_mixed field
            //
            $column = new TextViewColumn('rat_id', 'rat_id_abkuerzung_mixed', 'Rat Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for anzeige_name field
            //
            $column = new TextViewColumn('mutter_kommission_id', 'mutter_kommission_id_anzeige_name', 'Mutter Kommission', $this->dataset);
            $column->SetOrderable(true);
            $column->setHrefTemplate('kommission.php?operation=view&pk0=%mutter_kommission_id%');
            $column->setTarget('');
            $grid->AddPrintColumn($column);
            
            //
            // View column for beschreibung field
            //
            $column = new TextViewColumn('beschreibung', 'beschreibung', 'Beschreibung', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('kommissionGrid_beschreibung_handler_print');
            $column->SetReplaceLFByBR(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for beschreibung_fr field
            //
            $column = new TextViewColumn('beschreibung_fr', 'beschreibung_fr', 'Beschreibung Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('kommissionGrid_beschreibung_fr_handler_print');
            $grid->AddPrintColumn($column);
            
            //
            // View column for sachbereiche field
            //
            $column = new TextViewColumn('sachbereiche', 'sachbereiche', 'Sachbereiche', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('kommissionGrid_sachbereiche_handler_print');
            $column->SetReplaceLFByBR(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for sachbereiche_fr field
            //
            $column = new TextViewColumn('sachbereiche_fr', 'sachbereiche_fr', 'Sachbereiche Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('kommissionGrid_sachbereiche_fr_handler_print');
            $grid->AddPrintColumn($column);
            
            //
            // View column for anzahl_mitglieder field
            //
            $column = new TextViewColumn('anzahl_mitglieder', 'anzahl_mitglieder', 'Anzahl Mitglieder', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for anzeige_name_mixed field
            //
            $column = new TextViewColumn('zweitrat_kommission_id', 'zweitrat_kommission_id_anzeige_name_mixed', 'Zweitrat Kommission', $this->dataset);
            $column->SetOrderable(true);
            $column->setHrefTemplate('kommission.php?operation=view&pk0=%zweitrat_kommission_id%');
            $column->setTarget('');
            $grid->AddPrintColumn($column);
            
            //
            // View column for von field
            //
            $column = new DateTimeViewColumn('von', 'von', 'Von', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y');
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for bis field
            //
            $column = new DateTimeViewColumn('bis', 'bis', 'Bis', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y');
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for parlament_url field
            //
            $column = new TextViewColumn('parlament_url', 'parlament_url', 'Parlament Url', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('kommissionGrid_parlament_url_handler_print');
            $column->setHrefTemplate('%parlament_url%');
            $column->setTarget('_blank');
            $grid->AddPrintColumn($column);
            
            //
            // View column for parlament_id field
            //
            $column = new TextViewColumn('parlament_id', 'parlament_id', 'Parlament Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for parlament_committee_number field
            //
            $column = new TextViewColumn('parlament_committee_number', 'parlament_committee_number', 'Parlament Committee Number', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for parlament_subcommittee_number field
            //
            $column = new TextViewColumn('parlament_subcommittee_number', 'parlament_subcommittee_number', 'Parlament Subcommittee Number', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for parlament_type_code field
            //
            $column = new TextViewColumn('parlament_type_code', 'parlament_type_code', 'Parlament Type Code', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('kommissionGrid_notizen_handler_print');
            $column->SetReplaceLFByBR(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_visa field
            //
            $column = new TextViewColumn('eingabe_abgeschlossen_visa', 'eingabe_abgeschlossen_visa', 'Eingabe Abgeschlossen Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_datum field
            //
            $column = new DateTimeViewColumn('eingabe_abgeschlossen_datum', 'eingabe_abgeschlossen_datum', 'Eingabe Abgeschlossen Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for kontrolliert_visa field
            //
            $column = new TextViewColumn('kontrolliert_visa', 'kontrolliert_visa', 'Kontrolliert Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for kontrolliert_datum field
            //
            $column = new DateTimeViewColumn('kontrolliert_datum', 'kontrolliert_datum', 'Kontrolliert Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for freigabe_visa field
            //
            $column = new TextViewColumn('freigabe_visa', 'freigabe_visa', 'Freigabe Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for freigabe_datum field
            //
            $column = new DateTimeViewColumn('freigabe_datum', 'freigabe_datum', 'Freigabe Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
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
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
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
            $column->SetDateTimeFormat('d.m.Y H:i:s');
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
            $column->setHrefTemplate('http://lobbywatch.ch/de/daten/kommission/%id%');
            $column->setTarget('_blank');
            $grid->AddExportColumn($column);
            
            //
            // View column for abkuerzung field
            //
            $column = new TextViewColumn('abkuerzung', 'abkuerzung', 'Abkuerzung', $this->dataset);
            $column->SetOrderable(true);
            $column->setHrefTemplate('auswertung/anteil.php?option=kommission&id=%id%');
            $column->setTarget('_blank');
            $column->setBold(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for abkuerzung_fr field
            //
            $column = new TextViewColumn('abkuerzung_fr', 'abkuerzung_fr', 'Abkuerzung Fr', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('name', 'name', 'Name', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('kommissionGrid_name_handler_export');
            $grid->AddExportColumn($column);
            
            //
            // View column for name_fr field
            //
            $column = new TextViewColumn('name_fr', 'name_fr', 'Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('kommissionGrid_name_fr_handler_export');
            $grid->AddExportColumn($column);
            
            //
            // View column for typ field
            //
            $column = new TextViewColumn('typ', 'typ', 'Typ', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for art field
            //
            $column = new TextViewColumn('art', 'art', 'Art', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for abkuerzung_mixed field
            //
            $column = new TextViewColumn('rat_id', 'rat_id_abkuerzung_mixed', 'Rat Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for anzeige_name field
            //
            $column = new TextViewColumn('mutter_kommission_id', 'mutter_kommission_id_anzeige_name', 'Mutter Kommission', $this->dataset);
            $column->SetOrderable(true);
            $column->setHrefTemplate('kommission.php?operation=view&pk0=%mutter_kommission_id%');
            $column->setTarget('');
            $grid->AddExportColumn($column);
            
            //
            // View column for beschreibung field
            //
            $column = new TextViewColumn('beschreibung', 'beschreibung', 'Beschreibung', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('kommissionGrid_beschreibung_handler_export');
            $column->SetReplaceLFByBR(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for beschreibung_fr field
            //
            $column = new TextViewColumn('beschreibung_fr', 'beschreibung_fr', 'Beschreibung Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('kommissionGrid_beschreibung_fr_handler_export');
            $grid->AddExportColumn($column);
            
            //
            // View column for sachbereiche field
            //
            $column = new TextViewColumn('sachbereiche', 'sachbereiche', 'Sachbereiche', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('kommissionGrid_sachbereiche_handler_export');
            $column->SetReplaceLFByBR(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for sachbereiche_fr field
            //
            $column = new TextViewColumn('sachbereiche_fr', 'sachbereiche_fr', 'Sachbereiche Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('kommissionGrid_sachbereiche_fr_handler_export');
            $grid->AddExportColumn($column);
            
            //
            // View column for anzahl_mitglieder field
            //
            $column = new TextViewColumn('anzahl_mitglieder', 'anzahl_mitglieder', 'Anzahl Mitglieder', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for anzeige_name_mixed field
            //
            $column = new TextViewColumn('zweitrat_kommission_id', 'zweitrat_kommission_id_anzeige_name_mixed', 'Zweitrat Kommission', $this->dataset);
            $column->SetOrderable(true);
            $column->setHrefTemplate('kommission.php?operation=view&pk0=%zweitrat_kommission_id%');
            $column->setTarget('');
            $grid->AddExportColumn($column);
            
            //
            // View column for von field
            //
            $column = new DateTimeViewColumn('von', 'von', 'Von', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y');
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for bis field
            //
            $column = new DateTimeViewColumn('bis', 'bis', 'Bis', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y');
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for parlament_url field
            //
            $column = new TextViewColumn('parlament_url', 'parlament_url', 'Parlament Url', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('kommissionGrid_parlament_url_handler_export');
            $column->setHrefTemplate('%parlament_url%');
            $column->setTarget('_blank');
            $grid->AddExportColumn($column);
            
            //
            // View column for parlament_id field
            //
            $column = new TextViewColumn('parlament_id', 'parlament_id', 'Parlament Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for parlament_committee_number field
            //
            $column = new TextViewColumn('parlament_committee_number', 'parlament_committee_number', 'Parlament Committee Number', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for parlament_subcommittee_number field
            //
            $column = new TextViewColumn('parlament_subcommittee_number', 'parlament_subcommittee_number', 'Parlament Subcommittee Number', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for parlament_type_code field
            //
            $column = new TextViewColumn('parlament_type_code', 'parlament_type_code', 'Parlament Type Code', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('kommissionGrid_notizen_handler_export');
            $column->SetReplaceLFByBR(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_visa field
            //
            $column = new TextViewColumn('eingabe_abgeschlossen_visa', 'eingabe_abgeschlossen_visa', 'Eingabe Abgeschlossen Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_datum field
            //
            $column = new DateTimeViewColumn('eingabe_abgeschlossen_datum', 'eingabe_abgeschlossen_datum', 'Eingabe Abgeschlossen Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for kontrolliert_visa field
            //
            $column = new TextViewColumn('kontrolliert_visa', 'kontrolliert_visa', 'Kontrolliert Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for kontrolliert_datum field
            //
            $column = new DateTimeViewColumn('kontrolliert_datum', 'kontrolliert_datum', 'Kontrolliert Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for freigabe_visa field
            //
            $column = new TextViewColumn('freigabe_visa', 'freigabe_visa', 'Freigabe Visa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for freigabe_datum field
            //
            $column = new DateTimeViewColumn('freigabe_datum', 'freigabe_datum', 'Freigabe Datum', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
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
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
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
            $column->SetDateTimeFormat('d.m.Y H:i:s');
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
            $column->setHrefTemplate('http://lobbywatch.ch/de/daten/kommission/%id%');
            $column->setTarget('_blank');
            $grid->AddCompareColumn($column);
            
            //
            // View column for abkuerzung field
            //
            $column = new TextViewColumn('abkuerzung', 'abkuerzung', 'Abkuerzung', $this->dataset);
            $column->SetOrderable(true);
            $column->setHrefTemplate('auswertung/anteil.php?option=kommission&id=%id%');
            $column->setTarget('_blank');
            $column->setBold(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for abkuerzung_fr field
            //
            $column = new TextViewColumn('abkuerzung_fr', 'abkuerzung_fr', 'Abkuerzung Fr', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('name', 'name', 'Name', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('kommissionGrid_name_handler_compare');
            $grid->AddCompareColumn($column);
            
            //
            // View column for name_fr field
            //
            $column = new TextViewColumn('name_fr', 'name_fr', 'Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('kommissionGrid_name_fr_handler_compare');
            $grid->AddCompareColumn($column);
            
            //
            // View column for typ field
            //
            $column = new TextViewColumn('typ', 'typ', 'Typ', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for art field
            //
            $column = new TextViewColumn('art', 'art', 'Art', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for abkuerzung_mixed field
            //
            $column = new TextViewColumn('rat_id', 'rat_id_abkuerzung_mixed', 'Rat Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for anzeige_name field
            //
            $column = new TextViewColumn('mutter_kommission_id', 'mutter_kommission_id_anzeige_name', 'Mutter Kommission', $this->dataset);
            $column->SetOrderable(true);
            $column->setHrefTemplate('kommission.php?operation=view&pk0=%mutter_kommission_id%');
            $column->setTarget('');
            $grid->AddCompareColumn($column);
            
            //
            // View column for beschreibung field
            //
            $column = new TextViewColumn('beschreibung', 'beschreibung', 'Beschreibung', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('kommissionGrid_beschreibung_handler_compare');
            $column->SetReplaceLFByBR(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for beschreibung_fr field
            //
            $column = new TextViewColumn('beschreibung_fr', 'beschreibung_fr', 'Beschreibung Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('kommissionGrid_beschreibung_fr_handler_compare');
            $grid->AddCompareColumn($column);
            
            //
            // View column for sachbereiche field
            //
            $column = new TextViewColumn('sachbereiche', 'sachbereiche', 'Sachbereiche', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('kommissionGrid_sachbereiche_handler_compare');
            $column->SetReplaceLFByBR(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for sachbereiche_fr field
            //
            $column = new TextViewColumn('sachbereiche_fr', 'sachbereiche_fr', 'Sachbereiche Fr', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('kommissionGrid_sachbereiche_fr_handler_compare');
            $grid->AddCompareColumn($column);
            
            //
            // View column for anzahl_mitglieder field
            //
            $column = new TextViewColumn('anzahl_mitglieder', 'anzahl_mitglieder', 'Anzahl Mitglieder', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for anzahl_nationalraete field
            //
            $column = new TextViewColumn('anzahl_nationalraete', 'anzahl_nationalraete', 'Anzahl Nationalräte', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for anzahl_staenderaete field
            //
            $column = new TextViewColumn('anzahl_staenderaete', 'anzahl_staenderaete', 'Anzahl Ständeräte', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for anzeige_name_mixed field
            //
            $column = new TextViewColumn('zweitrat_kommission_id', 'zweitrat_kommission_id_anzeige_name_mixed', 'Zweitrat Kommission', $this->dataset);
            $column->SetOrderable(true);
            $column->setHrefTemplate('kommission.php?operation=view&pk0=%zweitrat_kommission_id%');
            $column->setTarget('');
            $grid->AddCompareColumn($column);
            
            //
            // View column for von field
            //
            $column = new DateTimeViewColumn('von', 'von', 'Von', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y');
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for bis field
            //
            $column = new DateTimeViewColumn('bis', 'bis', 'Bis', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y');
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for parlament_url field
            //
            $column = new TextViewColumn('parlament_url', 'parlament_url', 'Parlament Url', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('kommissionGrid_parlament_url_handler_compare');
            $column->setHrefTemplate('%parlament_url%');
            $column->setTarget('_blank');
            $grid->AddCompareColumn($column);
            
            //
            // View column for parlament_id field
            //
            $column = new TextViewColumn('parlament_id', 'parlament_id', 'Parlament Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for parlament_committee_number field
            //
            $column = new TextViewColumn('parlament_committee_number', 'parlament_committee_number', 'Parlament Committee Number', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for parlament_subcommittee_number field
            //
            $column = new TextViewColumn('parlament_subcommittee_number', 'parlament_subcommittee_number', 'Parlament Subcommittee Number', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for parlament_type_code field
            //
            $column = new TextViewColumn('parlament_type_code', 'parlament_type_code', 'Parlament Type Code', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('kommissionGrid_notizen_handler_compare');
            $column->SetReplaceLFByBR(true);
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
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
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
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
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
            $column->SetDateTimeFormat('d.m.Y H:i:s');
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
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
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
            $column->SetDateTimeFormat('d.m.Y H:i:s');
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
    
        function CreateMasterDetailRecordGrid()
        {
            $result = new Grid($this, $this->dataset);
            
            $this->AddFieldColumns($result, false);
            $this->AddPrintColumns($result);
            
            $result->SetAllowDeleteSelected(false);
            $result->SetShowUpdateLink(false);
            $result->SetShowKeyColumnsImagesInHeader(false);
            $result->SetViewMode(ViewMode::TABLE);
            $result->setEnableRuntimeCustomization(false);
            $result->setTableBordered(true);
            $result->setTableCondensed(true);
            
            $this->setupGridColumnGroup($result);
            $this->attachGridEventHandlers($result);
            
            return $result;
        }
        
        function GetCustomClientScript()
        {
            return ;
        }
        
        function GetOnPageLoadedClientScript()
        {
            return ;
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
        
        protected function GetEnableModalGridDelete() { return true; }
    
        protected function CreateGrid()
        {
            $result = new Grid($this, $this->dataset);
            if ($this->GetSecurityInfo()->HasDeleteGrant())
               $result->SetAllowDeleteSelected(true);
            else
               $result->SetAllowDeleteSelected(false);   
            
            ApplyCommonPageSettings($this, $result);
            
            $result->SetUseImagesForActions(true);
            $defaultSortedColumns = array();
            $defaultSortedColumns[] = new SortColumn('updated_date', 'DESC');
            $result->setDefaultOrdering($defaultSortedColumns);
            $result->SetUseFixedHeader(true);
            $result->SetShowLineNumbers(true);
            $result->SetViewMode(ViewMode::TABLE);
            $result->setEnableRuntimeCustomization(true);
            $result->setAllowCompare(true);
            $this->AddCompareHeaderColumns($result);
            $this->AddCompareColumns($result);
            $result->setTableBordered(true);
            $result->setTableCondensed(true);
            
            $result->SetHighlightRowAtHover(false);
            $result->SetWidth('');
            $this->AddOperationsColumns($result);
            $this->AddFieldColumns($result);
            $this->AddSingleRecordViewColumns($result);
            $this->AddEditColumns($result);
            $this->AddInsertColumns($result);
            $this->AddPrintColumns($result);
            $this->AddExportColumns($result);
    
    
            $this->SetViewFormTitle($this->RenderText('Kommission "%abkuerzung%"'));
            $this->SetEditFormTitle($this->RenderText('Edit Kommission "%abkuerzung%"'));
            $this->SetInsertFormTitle($this->RenderText('Add new Kommission'));
            $this->SetShowPageList(true);
            $this->SetShowTopPageNavigator(false);
            $this->SetShowBottomPageNavigator(true);
            $this->setPrintListAvailable(true);
            $this->setPrintListRecordAvailable(true);
            $this->setPrintOneRecordAvailable(true);
            $this->setExportListAvailable(array('excel','word','xml','csv','pdf'));
            $this->setExportListRecordAvailable(array());
            $this->setExportOneRecordAvailable(array('excel','word','xml','csv','pdf'));
            $this->setDescription($this->RenderText('' . $GLOBALS["edit_header_message"] /*afterburner*/  . '
            
            <div class="wiki-table-help">
            <p><a class="wiki external" target="_blank" href="http://www.parlament.ch/D/ORGANE-MITGLIEDER/KOMMISSIONEN/Seiten/default.aspx" rel="_blank external nofollow">Kommissionen</a> und <a class="wiki external" target="_blank" href="http://www.parlament.ch/D/ORGANE-MITGLIEDER/DELEGATIONEN/Seiten/default.aspx" rel="_blank external nofollow">Delegationen</a> des Parlamentes.
            </p>
            
            <div class="clearfix rbox note"><div class="rbox-title"><img src="' . util_data_uri('img/icons/information.png') . '" alt="Hinweis" title="Hinweis" class="icon" height="16" width="16"><span>Hinweis</span></div><div class="rbox-data">
            <ul><li> Delegationen im engeren Sinne (Bsp GPDel - Geschäftsprüfungsdelegation) sind Subkommissionen [Typ=subkommission]. Die zugehörige "Mutterkommission" muss angegeben werden.
            </li><li> Delegationen im weiteren Sinne (Bsp ER - Parlamentarische Versammlung des Europarates) sind Spezialkommissionen [Typ=spezialkommission].
            </li></ul></div></div>
            <p>
            </p>
            <div class="clearfix rbox note"><div class="rbox-title"><img src="' . util_data_uri('img/icons/information.png') . '" alt="Hinweis" title="Hinweis" class="icon" height="16" width="16"><span>Hinweis</span></div><div class="rbox-data">
            <p>Das Feld Sachbereiche enthält eine Aufzählung der Sachbereiche dieser Kommission wie auf parlament.ch angegeben. Die einzelnen Punkte werden durch ";" (ein Strichpunkt) getrennt. Siehe Beispiel <a class="wiki external" target="_blank" href="http://lobbywatch.ch/bearbeitung/kommission.php?operation=view&amp;pk0=1" rel="_blank external nofollow">SGK</a> (<a class="wiki external" target="_blank" href="http://www.parlament.ch/D/ORGANE-MITGLIEDER/KOMMISSIONEN/LEGISLATIVKOMMISSIONEN/KOMMISSIONEN-SGK/Seiten/default.aspx" rel="_blank external nofollow">parlament.ch</a>)
            </p>
            </div></div>
            </div>
            
            ' . $GLOBALS["edit_general_hint"] /*afterburner*/  . ''));
    
            return $result;
        }
     
        protected function doRegisterHandlers() {
            $detailPage = new kommission_v_in_kommissionPage('kommission_v_in_kommission', $this, array('kommission_id'), array('id'), $this->GetForeignKeyFields(), $this->CreateMasterDetailRecordGrid(), $this->dataset, GetCurrentUserGrantForDataSource('kommission.v_in_kommission'), 'UTF-8');
            $detailPage->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource('kommission.v_in_kommission'));
            $detailPage->SetTitle('Parlamentarier in Kommission');
            $detailPage->SetMenuLabel('Parlamentarier in Kommission');
            $detailPage->SetHeader(GetPagesHeader());
            $detailPage->SetFooter(GetPagesFooter());
            $detailPage->SetHttpHandlerName('kommission_v_in_kommission_handler');
            $handler = new PageHTTPHandler('kommission_v_in_kommission_handler', $detailPage);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $detailPage = new kommission_branchePage('kommission_branche', $this, array('kommission_id'), array('id'), $this->GetForeignKeyFields(), $this->CreateMasterDetailRecordGrid(), $this->dataset, GetCurrentUserGrantForDataSource('kommission.branche'), 'UTF-8');
            $detailPage->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource('kommission.branche'));
            $detailPage->SetTitle('Branche');
            $detailPage->SetMenuLabel('Branche');
            $detailPage->SetHeader(GetPagesHeader());
            $detailPage->SetFooter(GetPagesFooter());
            $detailPage->SetHttpHandlerName('kommission_branche_handler');
            $handler = new PageHTTPHandler('kommission_branche_handler', $detailPage);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('name', 'name', 'Name', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'kommissionGrid_name_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for name_fr field
            //
            $column = new TextViewColumn('name_fr', 'name_fr', 'Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'kommissionGrid_name_fr_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for beschreibung field
            //
            $column = new TextViewColumn('beschreibung', 'beschreibung', 'Beschreibung', $this->dataset);
            $column->SetOrderable(true);
            $column->SetReplaceLFByBR(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'kommissionGrid_beschreibung_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for beschreibung_fr field
            //
            $column = new TextViewColumn('beschreibung_fr', 'beschreibung_fr', 'Beschreibung Fr', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'kommissionGrid_beschreibung_fr_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for sachbereiche field
            //
            $column = new TextViewColumn('sachbereiche', 'sachbereiche', 'Sachbereiche', $this->dataset);
            $column->SetOrderable(true);
            $column->SetReplaceLFByBR(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'kommissionGrid_sachbereiche_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for sachbereiche_fr field
            //
            $column = new TextViewColumn('sachbereiche_fr', 'sachbereiche_fr', 'Sachbereiche Fr', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'kommissionGrid_sachbereiche_fr_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for parlament_url field
            //
            $column = new TextViewColumn('parlament_url', 'parlament_url', 'Parlament Url', $this->dataset);
            $column->SetOrderable(true);
            $column->setHrefTemplate('%parlament_url%');
            $column->setTarget('_blank');
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'kommissionGrid_parlament_url_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $column->SetReplaceLFByBR(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'kommissionGrid_notizen_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('name', 'name', 'Name', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'kommissionGrid_name_handler_print', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for name_fr field
            //
            $column = new TextViewColumn('name_fr', 'name_fr', 'Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'kommissionGrid_name_fr_handler_print', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for beschreibung field
            //
            $column = new TextViewColumn('beschreibung', 'beschreibung', 'Beschreibung', $this->dataset);
            $column->SetOrderable(true);
            $column->SetReplaceLFByBR(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'kommissionGrid_beschreibung_handler_print', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for beschreibung_fr field
            //
            $column = new TextViewColumn('beschreibung_fr', 'beschreibung_fr', 'Beschreibung Fr', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'kommissionGrid_beschreibung_fr_handler_print', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for sachbereiche field
            //
            $column = new TextViewColumn('sachbereiche', 'sachbereiche', 'Sachbereiche', $this->dataset);
            $column->SetOrderable(true);
            $column->SetReplaceLFByBR(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'kommissionGrid_sachbereiche_handler_print', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for sachbereiche_fr field
            //
            $column = new TextViewColumn('sachbereiche_fr', 'sachbereiche_fr', 'Sachbereiche Fr', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'kommissionGrid_sachbereiche_fr_handler_print', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for parlament_url field
            //
            $column = new TextViewColumn('parlament_url', 'parlament_url', 'Parlament Url', $this->dataset);
            $column->SetOrderable(true);
            $column->setHrefTemplate('%parlament_url%');
            $column->setTarget('_blank');
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'kommissionGrid_parlament_url_handler_print', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $column->SetReplaceLFByBR(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'kommissionGrid_notizen_handler_print', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('name', 'name', 'Name', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'kommissionGrid_name_handler_compare', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for name_fr field
            //
            $column = new TextViewColumn('name_fr', 'name_fr', 'Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'kommissionGrid_name_fr_handler_compare', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for beschreibung field
            //
            $column = new TextViewColumn('beschreibung', 'beschreibung', 'Beschreibung', $this->dataset);
            $column->SetOrderable(true);
            $column->SetReplaceLFByBR(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'kommissionGrid_beschreibung_handler_compare', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for beschreibung_fr field
            //
            $column = new TextViewColumn('beschreibung_fr', 'beschreibung_fr', 'Beschreibung Fr', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'kommissionGrid_beschreibung_fr_handler_compare', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for sachbereiche field
            //
            $column = new TextViewColumn('sachbereiche', 'sachbereiche', 'Sachbereiche', $this->dataset);
            $column->SetOrderable(true);
            $column->SetReplaceLFByBR(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'kommissionGrid_sachbereiche_handler_compare', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for sachbereiche_fr field
            //
            $column = new TextViewColumn('sachbereiche_fr', 'sachbereiche_fr', 'Sachbereiche Fr', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'kommissionGrid_sachbereiche_fr_handler_compare', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for parlament_url field
            //
            $column = new TextViewColumn('parlament_url', 'parlament_url', 'Parlament Url', $this->dataset);
            $column->SetOrderable(true);
            $column->setHrefTemplate('%parlament_url%');
            $column->setTarget('_blank');
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'kommissionGrid_parlament_url_handler_compare', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $column->SetReplaceLFByBR(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'kommissionGrid_notizen_handler_compare', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`v_rat`');
            $field = new StringField('anzeige_name');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_de');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_fr');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('anzeige_name_mixed');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('abkuerzung_mixed');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('id');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
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
            $field = new IntegerField('created_date_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('updated_date_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('eingabe_abgeschlossen_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('kontrolliert_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('freigabe_datum_unix');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('abkuerzung_mixed', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_rat_id_abkuerzung_mixed_search', 'id', 'abkuerzung_mixed', null);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
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
            $field = new IntegerField('anzahl_mitglieder');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_nationalraete');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_staenderaete');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('mutter_kommission_id');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('zweitrat_kommission_id');
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
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('updated_date_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('eingabe_abgeschlossen_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('kontrolliert_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('freigabe_datum_unix');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('anzeige_name', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_mutter_kommission_id_anzeige_name_search', 'id', 'anzeige_name', null);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
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
            $field = new IntegerField('anzahl_mitglieder');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_nationalraete');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_staenderaete');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('mutter_kommission_id');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('zweitrat_kommission_id');
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
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('updated_date_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('eingabe_abgeschlossen_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('kontrolliert_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('freigabe_datum_unix');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('anzeige_name', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_mutter_kommission_id_anzeige_name_search', 'id', 'anzeige_name', null);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
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
            $field = new IntegerField('anzahl_mitglieder');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_nationalraete');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_staenderaete');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('mutter_kommission_id');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('zweitrat_kommission_id');
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
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('updated_date_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('eingabe_abgeschlossen_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('kontrolliert_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('freigabe_datum_unix');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('anzeige_name', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_mutter_kommission_id_anzeige_name_search', 'id', 'anzeige_name', null);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
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
            $field = new IntegerField('anzahl_mitglieder');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_nationalraete');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_staenderaete');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('mutter_kommission_id');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('zweitrat_kommission_id');
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
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('updated_date_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('eingabe_abgeschlossen_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('kontrolliert_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('freigabe_datum_unix');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('anzeige_name', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_mutter_kommission_id_anzeige_name_search', 'id', 'anzeige_name', null);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
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
            $field = new IntegerField('anzahl_mitglieder');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_nationalraete');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_staenderaete');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('mutter_kommission_id');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('zweitrat_kommission_id');
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
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('updated_date_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('eingabe_abgeschlossen_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('kontrolliert_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('freigabe_datum_unix');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('anzeige_name', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_mutter_kommission_id_anzeige_name_search', 'id', 'anzeige_name', null);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
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
            $field = new IntegerField('anzahl_mitglieder');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_nationalraete');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('anzahl_staenderaete');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('mutter_kommission_id');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('zweitrat_kommission_id');
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
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('updated_date_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('eingabe_abgeschlossen_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('kontrolliert_datum_unix');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('freigabe_datum_unix');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('anzeige_name_mixed', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_zweitrat_kommission_id_anzeige_name_mixed_search', 'id', 'anzeige_name_mixed', null);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('name', 'name', 'Name', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'kommissionGrid_name_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for name_fr field
            //
            $column = new TextViewColumn('name_fr', 'name_fr', 'Name Fr', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'kommissionGrid_name_fr_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for beschreibung field
            //
            $column = new TextViewColumn('beschreibung', 'beschreibung', 'Beschreibung', $this->dataset);
            $column->SetOrderable(true);
            $column->SetReplaceLFByBR(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'kommissionGrid_beschreibung_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for beschreibung_fr field
            //
            $column = new TextViewColumn('beschreibung_fr', 'beschreibung_fr', 'Beschreibung Fr', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'kommissionGrid_beschreibung_fr_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for sachbereiche field
            //
            $column = new TextViewColumn('sachbereiche', 'sachbereiche', 'Sachbereiche', $this->dataset);
            $column->SetOrderable(true);
            $column->SetReplaceLFByBR(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'kommissionGrid_sachbereiche_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for sachbereiche_fr field
            //
            $column = new TextViewColumn('sachbereiche_fr', 'sachbereiche_fr', 'Sachbereiche Fr', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'kommissionGrid_sachbereiche_fr_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for parlament_url field
            //
            $column = new TextViewColumn('parlament_url', 'parlament_url', 'Parlament Url', $this->dataset);
            $column->SetOrderable(true);
            $column->setHrefTemplate('%parlament_url%');
            $column->setTarget('_blank');
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'kommissionGrid_parlament_url_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $column->SetReplaceLFByBR(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'kommissionGrid_notizen_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
        }
       
        protected function doCustomRenderColumn($fieldName, $fieldData, $rowData, &$customText, &$handled)
        { 
            customOnCustomRenderColumn('kommission', $fieldName, $fieldData, $rowData, $customText, $handled);
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
            customDrawRow('kommission', $rowData, $rowCellStyles, $rowStyles);
        }
    
        protected function doCustomRenderTotal($totalValue, $aggregate, $columnName, &$customText, &$handled)
        {
    
        }
    
        protected function doCustomCompareColumn($columnName, $valueA, $valueB, &$result)
        {
    
        }
    
        protected function doBeforeInsertRecord($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName)
        {
    
        }
    
        protected function doBeforeUpdateRecord($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName)
        {
    
        }
    
        protected function doBeforeDeleteRecord($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName)
        {
    
        }
    
        protected function doAfterInsertRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doAfterUpdateRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
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
    
        protected function doGetCustomUploadFileName($fieldName, $rowData, &$result, &$handled, $originalFileName, $originalFileExtension, $fileSize)
        {
    
        }
    
        protected function doPrepareChart(Chart $chart)
        {
    
        }
    
    }

    SetUpUserAuthorization();

    try
    {
        $Page = new kommissionPage("kommission", "kommission.php", GetCurrentUserGrantForDataSource("kommission"), 'UTF-8');
        $Page->SetTitle('Kommission');
        $Page->SetMenuLabel('<span class="entity">Kommission</span>');
        $Page->SetHeader(GetPagesHeader());
        $Page->SetFooter(GetPagesFooter());
        $Page->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource("kommission"));
        GetApplication()->SetCanUserChangeOwnPassword(
            !function_exists('CanUserChangeOwnPassword') || CanUserChangeOwnPassword());
        GetApplication()->SetMainPage($Page);
        before_render($Page); /*afterburner*/ 
        GetApplication()->Run();
    }
    catch(Exception $e)
    {
        ShowErrorPage($e);
    }
