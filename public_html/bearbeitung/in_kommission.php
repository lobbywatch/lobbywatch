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
    
    
    
    class in_kommissionPage extends Page
    {
        protected function DoBeforeCreate()
        {
            $this->SetTitle('In Kommission');
            $this->SetMenuLabel('<span class="relation">In Kommission</span>');
    
            $this->dataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`in_kommission`');
            $this->dataset->addFields(
                array(
                    new IntegerField('id', true, true, true),
                    new IntegerField('parlamentarier_id', true),
                    new IntegerField('kommission_id', true),
                    new StringField('funktion', true),
                    new IntegerField('parlament_committee_function'),
                    new StringField('parlament_committee_function_name'),
                    new DateField('von'),
                    new DateField('bis'),
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
                    new DateTimeField('updated_date', true)
                )
            );
            $this->dataset->AddLookupField('parlamentarier_id', 'v_parlamentarier_simple', new IntegerField('id'), new StringField('anzeige_name', false, false, false, false, 'parlamentarier_id_anzeige_name', 'parlamentarier_id_anzeige_name_v_parlamentarier_simple'), 'parlamentarier_id_anzeige_name_v_parlamentarier_simple');
            $this->dataset->AddLookupField('kommission_id', 'v_kommission', new IntegerField('id'), new StringField('anzeige_name_mixed', false, false, false, false, 'kommission_id_anzeige_name_mixed', 'kommission_id_anzeige_name_mixed_v_kommission'), 'kommission_id_anzeige_name_mixed_v_kommission');
        }
    
        protected function DoPrepare() {
            globalOnPreparePage($this);
        }
    
        protected function CreatePageNavigator()
        {
            $result = new CompositePageNavigator($this);
            
            $partitionNavigator = new PageNavigator('pnav', $this, $this->dataset);
            $partitionNavigator->SetRowsPerPage(12);
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
                new FilterColumn($this->dataset, 'kommission_id', 'kommission_id_anzeige_name_mixed', 'Kommission'),
                new FilterColumn($this->dataset, 'funktion', 'funktion', 'Funktion'),
                new FilterColumn($this->dataset, 'von', 'von', 'Von'),
                new FilterColumn($this->dataset, 'bis', 'bis', 'Bis'),
                new FilterColumn($this->dataset, 'parlament_committee_function', 'parlament_committee_function', 'Parlament Committee Function'),
                new FilterColumn($this->dataset, 'parlament_committee_function_name', 'parlament_committee_function_name', 'Parlament Committee Function Name'),
                new FilterColumn($this->dataset, 'notizen', 'notizen', 'Notizen'),
                new FilterColumn($this->dataset, 'eingabe_abgeschlossen_visa', 'eingabe_abgeschlossen_visa', 'Eingabe Abgeschlossen Visa'),
                new FilterColumn($this->dataset, 'eingabe_abgeschlossen_datum', 'eingabe_abgeschlossen_datum', 'Eingabe Abgeschlossen Datum'),
                new FilterColumn($this->dataset, 'kontrolliert_visa', 'kontrolliert_visa', 'Kontrolliert Visa'),
                new FilterColumn($this->dataset, 'kontrolliert_datum', 'kontrolliert_datum', 'Kontrolliert Datum'),
                new FilterColumn($this->dataset, 'freigabe_visa', 'freigabe_visa', 'Freigabe Visa'),
                new FilterColumn($this->dataset, 'freigabe_datum', 'freigabe_datum', 'Freigabe Datum'),
                new FilterColumn($this->dataset, 'created_visa', 'created_visa', 'Created Visa'),
                new FilterColumn($this->dataset, 'created_date', 'created_date', 'Created Date'),
                new FilterColumn($this->dataset, 'updated_visa', 'updated_visa', 'Updated Visa'),
                new FilterColumn($this->dataset, 'updated_date', 'updated_date', 'Updated Date')
            );
        }
    
        protected function setupQuickFilter(QuickFilter $quickFilter, FixedKeysArray $columns)
        {
            $quickFilter
                ->addColumn($columns['id'])
                ->addColumn($columns['parlamentarier_id'])
                ->addColumn($columns['kommission_id'])
                ->addColumn($columns['parlament_committee_function_name'])
                ->addColumn($columns['notizen']);
        }
    
        protected function setupColumnFilter(ColumnFilter $columnFilter)
        {
            $columnFilter
                ->setOptionsFor('id')
                ->setOptionsFor('parlamentarier_id')
                ->setOptionsFor('kommission_id')
                ->setOptionsFor('funktion')
                ->setOptionsFor('von')
                ->setOptionsFor('bis')
                ->setOptionsFor('eingabe_abgeschlossen_visa')
                ->setOptionsFor('eingabe_abgeschlossen_datum')
                ->setOptionsFor('kontrolliert_visa')
                ->setOptionsFor('kontrolliert_datum')
                ->setOptionsFor('freigabe_visa')
                ->setOptionsFor('freigabe_datum')
                ->setOptionsFor('created_visa')
                ->setOptionsFor('created_date')
                ->setOptionsFor('updated_visa')
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
            
            $main_editor = new DynamicCombobox('parlamentarier_id_edit', $this->CreateLinkBuilder());
            $main_editor->setAllowClear(true);
            $main_editor->setMinimumInputLength(0);
            $main_editor->SetAllowNullValue(false);
            $main_editor->SetHandlerName('filter_builder_in_kommission_parlamentarier_id_search');
            
            $multi_value_select_editor = new RemoteMultiValueSelect('parlamentarier_id', $this->CreateLinkBuilder());
            $multi_value_select_editor->SetHandlerName('filter_builder_in_kommission_parlamentarier_id_search');
            
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
            
            $main_editor = new DynamicCombobox('kommission_id_edit', $this->CreateLinkBuilder());
            $main_editor->setAllowClear(true);
            $main_editor->setMinimumInputLength(0);
            $main_editor->SetAllowNullValue(false);
            $main_editor->SetHandlerName('filter_builder_in_kommission_kommission_id_search');
            
            $multi_value_select_editor = new RemoteMultiValueSelect('kommission_id', $this->CreateLinkBuilder());
            $multi_value_select_editor->SetHandlerName('filter_builder_in_kommission_kommission_id_search');
            
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
            
            $main_editor = new ComboBox('funktion_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $main_editor->addChoice('praesident', 'PräsidentIn / Président(e)');
            $main_editor->addChoice('co-praesident', 'Co-PräsidentIn / Co-président(e)');
            $main_editor->addChoice('vizepraesident', 'VizepräsidentIn / Vice-président(e)');
            $main_editor->addChoice('mitglied', 'Mitglied / Membre');
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
            //
            // View column for id field
            //
            $column = new TextViewColumn('id', 'id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('Technischer Schlüssel einer Kommissionszugehörigkeit');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for anzeige_name field
            //
            $column = new TextViewColumn('parlamentarier_id', 'parlamentarier_id_anzeige_name', 'Parlamentarier', $this->dataset);
            $column->SetOrderable(true);
            $column->setHrefTemplate('parlamentarier.php?operation=view&pk0=%parlamentarier_id%');
            $column->setTarget('_self');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('Fremdschlüssel des Parlamentariers');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for anzeige_name_mixed field
            //
            $column = new TextViewColumn('kommission_id', 'kommission_id_anzeige_name_mixed', 'Kommission', $this->dataset);
            $column->SetOrderable(true);
            $column->setHrefTemplate('kommission.php?operation=view&pk0=%kommission_id%');
            $column->setTarget('_self');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('Fremdschlüssel der Kommission');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for funktion field
            //
            $column = new TextViewColumn('funktion', 'funktion', 'Funktion', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('Funktion des Parlamentariers in der Kommission');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for von field
            //
            $column = new DateTimeViewColumn('von', 'von', 'Von', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('Beginn der Kommissionszugehörigkeit, leer (NULL) = unbekannt');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for bis field
            //
            $column = new DateTimeViewColumn('bis', 'bis', 'Bis', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('Ende der Kommissionszugehörigkeit, leer (NULL) = aktuell gültig, nicht leer = historischer Eintrag');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for parlament_committee_function field
            //
            $column = new TextViewColumn('parlament_committee_function', 'parlament_committee_function', 'Parlament Committee Function', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('committeeFunction von ws.parlament.ch');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for parlament_committee_function_name field
            //
            $column = new TextViewColumn('parlament_committee_function_name', 'parlament_committee_function_name', 'Parlament Committee Function Name', $this->dataset);
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
            $column->SetMaxLength(75);
            $column->SetReplaceLFByBR(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_visa field
            //
            $column = new TextViewColumn('eingabe_abgeschlossen_visa', 'eingabe_abgeschlossen_visa', 'Eingabe Abgeschlossen Visa', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('Kürzel der Person, welche die Eingabe abgeschlossen hat.');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for eingabe_abgeschlossen_datum field
            //
            $column = new DateTimeViewColumn('eingabe_abgeschlossen_datum', 'eingabe_abgeschlossen_datum', 'Eingabe Abgeschlossen Datum', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for kontrolliert_visa field
            //
            $column = new TextViewColumn('kontrolliert_visa', 'kontrolliert_visa', 'Kontrolliert Visa', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('Kürzel der Person, welche die Eingabe kontrolliert hat.');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for kontrolliert_datum field
            //
            $column = new DateTimeViewColumn('kontrolliert_datum', 'kontrolliert_datum', 'Kontrolliert Datum', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for freigabe_visa field
            //
            $column = new TextViewColumn('freigabe_visa', 'freigabe_visa', 'Freigabe Visa', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('Freigabe von (Freigabe = Daten sind fertig)');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for freigabe_datum field
            //
            $column = new DateTimeViewColumn('freigabe_datum', 'freigabe_datum', 'Freigabe Datum', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('Freigabedatum (Freigabe = Daten sind fertig)');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for created_visa field
            //
            $column = new TextViewColumn('created_visa', 'created_visa', 'Created Visa', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('Erstellt von');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for created_date field
            //
            $column = new DateTimeViewColumn('created_date', 'created_date', 'Created Date', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('Erstellt am');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for updated_visa field
            //
            $column = new TextViewColumn('updated_visa', 'updated_visa', 'Updated Visa', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('Abgeändert von');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for updated_date field
            //
            $column = new DateTimeViewColumn('updated_date', 'updated_date', 'Updated Date', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('Abgeändert am');
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
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for anzeige_name_mixed field
            //
            $column = new TextViewColumn('kommission_id', 'kommission_id_anzeige_name_mixed', 'Kommission', $this->dataset);
            $column->SetOrderable(true);
            $column->setHrefTemplate('kommission.php?operation=view&pk0=%kommission_id%');
            $column->setTarget('_self');
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
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for bis field
            //
            $column = new DateTimeViewColumn('bis', 'bis', 'Bis', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for parlament_committee_function field
            //
            $column = new TextViewColumn('parlament_committee_function', 'parlament_committee_function', 'Parlament Committee Function', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for parlament_committee_function_name field
            //
            $column = new TextViewColumn('parlament_committee_function_name', 'parlament_committee_function_name', 'Parlament Committee Function Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
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
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
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
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
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
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
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
        }
    
        protected function AddEditColumns(Grid $grid)
        {
            //
            // Edit column for parlamentarier_id field
            //
            $editor = new DynamicCombobox('parlamentarier_id_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
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
            $editColumn = new DynamicLookupEditColumn('Parlamentarier', 'parlamentarier_id', 'parlamentarier_id_anzeige_name', 'edit_in_kommission_parlamentarier_id_search', $editor, $this->dataset, $lookupDataset, 'id', 'anzeige_name', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
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
            $lookupDataset->addFields(
                array(
                    new StringField('anzeige_name'),
                    new StringField('anzeige_name_de'),
                    new StringField('anzeige_name_fr'),
                    new StringField('anzeige_name_mixed'),
                    new IntegerField('id', true),
                    new StringField('abkuerzung', true),
                    new StringField('abkuerzung_fr'),
                    new StringField('name', true),
                    new StringField('name_fr'),
                    new IntegerField('rat_id'),
                    new StringField('typ', true),
                    new StringField('art'),
                    new StringField('beschreibung'),
                    new StringField('beschreibung_fr'),
                    new StringField('sachbereiche', true),
                    new StringField('sachbereiche_fr'),
                    new IntegerField('anzahl_mitglieder'),
                    new IntegerField('anzahl_nationalraete'),
                    new IntegerField('anzahl_staenderaete'),
                    new IntegerField('mutter_kommission_id'),
                    new IntegerField('zweitrat_kommission_id'),
                    new DateField('von'),
                    new DateField('bis'),
                    new StringField('parlament_url'),
                    new IntegerField('parlament_id'),
                    new IntegerField('parlament_committee_number'),
                    new IntegerField('parlament_subcommittee_number'),
                    new IntegerField('parlament_type_code'),
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
                    new StringField('abkuerzung_de', true),
                    new StringField('beschreibung_de'),
                    new StringField('sachbereiche_de', true),
                    new IntegerField('published', true),
                    new IntegerField('created_date_unix', true),
                    new IntegerField('updated_date_unix', true),
                    new IntegerField('eingabe_abgeschlossen_datum_unix'),
                    new IntegerField('kontrolliert_datum_unix'),
                    new IntegerField('freigabe_datum_unix')
                )
            );
            $lookupDataset->setOrderByField('anzeige_name_mixed', 'ASC');
            $editColumn = new LookUpEditColumn(
                'Kommission', 
                'kommission_id', 
                $editor, 
                $this->dataset, 'id', 'anzeige_name_mixed', $lookupDataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for funktion field
            //
            $editor = new ComboBox('funktion_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $editor->addChoice('praesident', 'PräsidentIn / Président(e)');
            $editor->addChoice('co-praesident', 'Co-PräsidentIn / Co-président(e)');
            $editor->addChoice('vizepraesident', 'VizepräsidentIn / Vice-président(e)');
            $editor->addChoice('mitglied', 'Mitglied / Membre');
            $editColumn = new CustomEditColumn('Funktion', 'funktion', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
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
            // Edit column for parlament_committee_function field
            //
            $editor = new TextEdit('parlament_committee_function_edit');
            $editColumn = new CustomEditColumn('Parlament Committee Function', 'parlament_committee_function', $editor, $this->dataset);
            $editColumn->SetReadOnly(true);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for parlament_committee_function_name field
            //
            $editor = new TextEdit('parlament_committee_function_name_edit');
            $editor->SetMaxLength(40);
            $editColumn = new CustomEditColumn('Parlament Committee Function Name', 'parlament_committee_function_name', $editor, $this->dataset);
            $editColumn->SetReadOnly(true);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for notizen field
            //
            $editor = new TextAreaEdit('notizen_edit', 50, 8);
            $editor->setPlaceholder(getNotizenPlaceholder()); // Afterburned
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
            $editColumn->SetReadOnly(true);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for created_date field
            //
            $editor = new DateTimeEdit('created_date_edit', false, 'd.m.Y H:i:s');
            $editColumn = new CustomEditColumn('Created Date', 'created_date', $editor, $this->dataset);
            $editColumn->SetReadOnly(true);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for updated_visa field
            //
            $editor = new TextEdit('updated_visa_edit');
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Updated Visa', 'updated_visa', $editor, $this->dataset);
            $editColumn->SetReadOnly(true);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for updated_date field
            //
            $editor = new DateTimeEdit('updated_date_edit', false, 'd.m.Y H:i:s');
            $editColumn = new CustomEditColumn('Updated Date', 'updated_date', $editor, $this->dataset);
            $editColumn->SetReadOnly(true);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
        }
    
        protected function AddMultiEditColumns(Grid $grid)
        {
            //
            // Edit column for parlamentarier_id field
            //
            $editor = new DynamicCombobox('parlamentarier_id_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
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
            $editColumn = new DynamicLookupEditColumn('Parlamentarier', 'parlamentarier_id', 'parlamentarier_id_anzeige_name', 'multi_edit_in_kommission_parlamentarier_id_search', $editor, $this->dataset, $lookupDataset, 'id', 'anzeige_name', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for kommission_id field
            //
            $editor = new ComboBox('kommission_id_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`v_kommission`');
            $lookupDataset->addFields(
                array(
                    new StringField('anzeige_name'),
                    new StringField('anzeige_name_de'),
                    new StringField('anzeige_name_fr'),
                    new StringField('anzeige_name_mixed'),
                    new IntegerField('id', true),
                    new StringField('abkuerzung', true),
                    new StringField('abkuerzung_fr'),
                    new StringField('name', true),
                    new StringField('name_fr'),
                    new IntegerField('rat_id'),
                    new StringField('typ', true),
                    new StringField('art'),
                    new StringField('beschreibung'),
                    new StringField('beschreibung_fr'),
                    new StringField('sachbereiche', true),
                    new StringField('sachbereiche_fr'),
                    new IntegerField('anzahl_mitglieder'),
                    new IntegerField('anzahl_nationalraete'),
                    new IntegerField('anzahl_staenderaete'),
                    new IntegerField('mutter_kommission_id'),
                    new IntegerField('zweitrat_kommission_id'),
                    new DateField('von'),
                    new DateField('bis'),
                    new StringField('parlament_url'),
                    new IntegerField('parlament_id'),
                    new IntegerField('parlament_committee_number'),
                    new IntegerField('parlament_subcommittee_number'),
                    new IntegerField('parlament_type_code'),
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
                    new StringField('abkuerzung_de', true),
                    new StringField('beschreibung_de'),
                    new StringField('sachbereiche_de', true),
                    new IntegerField('published', true),
                    new IntegerField('created_date_unix', true),
                    new IntegerField('updated_date_unix', true),
                    new IntegerField('eingabe_abgeschlossen_datum_unix'),
                    new IntegerField('kontrolliert_datum_unix'),
                    new IntegerField('freigabe_datum_unix')
                )
            );
            $lookupDataset->setOrderByField('anzeige_name_mixed', 'ASC');
            $editColumn = new LookUpEditColumn(
                'Kommission', 
                'kommission_id', 
                $editor, 
                $this->dataset, 'id', 'anzeige_name_mixed', $lookupDataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for funktion field
            //
            $editor = new ComboBox('funktion_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $editor->addChoice('praesident', 'PräsidentIn / Président(e)');
            $editor->addChoice('co-praesident', 'Co-PräsidentIn / Co-président(e)');
            $editor->addChoice('vizepraesident', 'VizepräsidentIn / Vice-président(e)');
            $editor->addChoice('mitglied', 'Mitglied / Membre');
            $editColumn = new CustomEditColumn('Funktion', 'funktion', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for von field
            //
            $editor = new DateTimeEdit('von_edit', false, 'd.m.Y');
            $editColumn = new CustomEditColumn('Von', 'von', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for bis field
            //
            $editor = new DateTimeEdit('bis_edit', false, 'd.m.Y');
            $editColumn = new CustomEditColumn('Bis', 'bis', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for parlament_committee_function field
            //
            $editor = new TextEdit('parlament_committee_function_edit');
            $editColumn = new CustomEditColumn('Parlament Committee Function', 'parlament_committee_function', $editor, $this->dataset);
            $editColumn->SetReadOnly(true);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for parlament_committee_function_name field
            //
            $editor = new TextEdit('parlament_committee_function_name_edit');
            $editor->SetMaxLength(40);
            $editColumn = new CustomEditColumn('Parlament Committee Function Name', 'parlament_committee_function_name', $editor, $this->dataset);
            $editColumn->SetReadOnly(true);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for notizen field
            //
            $editor = new TextAreaEdit('notizen_edit', 50, 8);
            $editor->setPlaceholder(getNotizenPlaceholder()); // Afterburned
            $editColumn = new CustomEditColumn('Notizen', 'notizen', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for eingabe_abgeschlossen_visa field
            //
            $editor = new TextEdit('eingabe_abgeschlossen_visa_edit');
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Eingabe Abgeschlossen Visa', 'eingabe_abgeschlossen_visa', $editor, $this->dataset);
            $editColumn->SetReadOnly(true);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for eingabe_abgeschlossen_datum field
            //
            $editor = new DateTimeEdit('eingabe_abgeschlossen_datum_edit', false, 'd.m.Y H:i:s');
            $editColumn = new CustomEditColumn('Eingabe Abgeschlossen Datum', 'eingabe_abgeschlossen_datum', $editor, $this->dataset);
            $editColumn->SetReadOnly(true);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for kontrolliert_visa field
            //
            $editor = new TextEdit('kontrolliert_visa_edit');
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Kontrolliert Visa', 'kontrolliert_visa', $editor, $this->dataset);
            $editColumn->SetReadOnly(true);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for kontrolliert_datum field
            //
            $editor = new DateTimeEdit('kontrolliert_datum_edit', false, 'd.m.Y H:i:s');
            $editColumn = new CustomEditColumn('Kontrolliert Datum', 'kontrolliert_datum', $editor, $this->dataset);
            $editColumn->SetReadOnly(true);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for freigabe_visa field
            //
            $editor = new TextEdit('freigabe_visa_edit');
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Freigabe Visa', 'freigabe_visa', $editor, $this->dataset);
            $editColumn->SetReadOnly(true);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for freigabe_datum field
            //
            $editor = new DateTimeEdit('freigabe_datum_edit', false, 'd.m.Y H:i:s');
            $editColumn = new CustomEditColumn('Freigabe Datum', 'freigabe_datum', $editor, $this->dataset);
            $editColumn->SetReadOnly(true);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for created_visa field
            //
            $editor = new TextEdit('created_visa_edit');
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Created Visa', 'created_visa', $editor, $this->dataset);
            $editColumn->SetReadOnly(true);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for created_date field
            //
            $editor = new DateTimeEdit('created_date_edit', false, 'd.m.Y H:i:s');
            $editColumn = new CustomEditColumn('Created Date', 'created_date', $editor, $this->dataset);
            $editColumn->SetReadOnly(true);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for updated_visa field
            //
            $editor = new TextEdit('updated_visa_edit');
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Updated Visa', 'updated_visa', $editor, $this->dataset);
            $editColumn->SetReadOnly(true);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for updated_date field
            //
            $editor = new DateTimeEdit('updated_date_edit', false, 'd.m.Y H:i:s');
            $editColumn = new CustomEditColumn('Updated Date', 'updated_date', $editor, $this->dataset);
            $editColumn->SetReadOnly(true);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
        }
    
        protected function AddInsertColumns(Grid $grid)
        {
            //
            // Edit column for parlamentarier_id field
            //
            $editor = new DynamicCombobox('parlamentarier_id_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
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
            $editColumn = new DynamicLookupEditColumn('Parlamentarier', 'parlamentarier_id', 'parlamentarier_id_anzeige_name', 'insert_in_kommission_parlamentarier_id_search', $editor, $this->dataset, $lookupDataset, 'id', 'anzeige_name', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
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
            $lookupDataset->addFields(
                array(
                    new StringField('anzeige_name'),
                    new StringField('anzeige_name_de'),
                    new StringField('anzeige_name_fr'),
                    new StringField('anzeige_name_mixed'),
                    new IntegerField('id', true),
                    new StringField('abkuerzung', true),
                    new StringField('abkuerzung_fr'),
                    new StringField('name', true),
                    new StringField('name_fr'),
                    new IntegerField('rat_id'),
                    new StringField('typ', true),
                    new StringField('art'),
                    new StringField('beschreibung'),
                    new StringField('beschreibung_fr'),
                    new StringField('sachbereiche', true),
                    new StringField('sachbereiche_fr'),
                    new IntegerField('anzahl_mitglieder'),
                    new IntegerField('anzahl_nationalraete'),
                    new IntegerField('anzahl_staenderaete'),
                    new IntegerField('mutter_kommission_id'),
                    new IntegerField('zweitrat_kommission_id'),
                    new DateField('von'),
                    new DateField('bis'),
                    new StringField('parlament_url'),
                    new IntegerField('parlament_id'),
                    new IntegerField('parlament_committee_number'),
                    new IntegerField('parlament_subcommittee_number'),
                    new IntegerField('parlament_type_code'),
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
                    new StringField('abkuerzung_de', true),
                    new StringField('beschreibung_de'),
                    new StringField('sachbereiche_de', true),
                    new IntegerField('published', true),
                    new IntegerField('created_date_unix', true),
                    new IntegerField('updated_date_unix', true),
                    new IntegerField('eingabe_abgeschlossen_datum_unix'),
                    new IntegerField('kontrolliert_datum_unix'),
                    new IntegerField('freigabe_datum_unix')
                )
            );
            $lookupDataset->setOrderByField('anzeige_name_mixed', 'ASC');
            $editColumn = new LookUpEditColumn(
                'Kommission', 
                'kommission_id', 
                $editor, 
                $this->dataset, 'id', 'anzeige_name_mixed', $lookupDataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for funktion field
            //
            $editor = new ComboBox('funktion_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $editor->addChoice('praesident', 'PräsidentIn / Président(e)');
            $editor->addChoice('co-praesident', 'Co-PräsidentIn / Co-président(e)');
            $editor->addChoice('vizepraesident', 'VizepräsidentIn / Vice-président(e)');
            $editor->addChoice('mitglied', 'Mitglied / Membre');
            $editColumn = new CustomEditColumn('Funktion', 'funktion', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
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
            // Edit column for parlament_committee_function field
            //
            $editor = new TextEdit('parlament_committee_function_edit');
            $editColumn = new CustomEditColumn('Parlament Committee Function', 'parlament_committee_function', $editor, $this->dataset);
            $editColumn->SetReadOnly(true);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for parlament_committee_function_name field
            //
            $editor = new TextEdit('parlament_committee_function_name_edit');
            $editor->SetMaxLength(40);
            $editColumn = new CustomEditColumn('Parlament Committee Function Name', 'parlament_committee_function_name', $editor, $this->dataset);
            $editColumn->SetReadOnly(true);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for notizen field
            //
            $editor = new TextAreaEdit('notizen_edit', 50, 8);
            $editor->setPlaceholder(getNotizenPlaceholder()); // Afterburned
            $editColumn = new CustomEditColumn('Notizen', 'notizen', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for created_visa field
            //
            $editor = new TextEdit('created_visa_edit');
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Created Visa', 'created_visa', $editor, $this->dataset);
            $editColumn->SetReadOnly(true);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for created_date field
            //
            $editor = new DateTimeEdit('created_date_edit', false, 'd.m.Y H:i:s');
            $editColumn = new CustomEditColumn('Created Date', 'created_date', $editor, $this->dataset);
            $editColumn->SetReadOnly(true);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for updated_visa field
            //
            $editor = new TextEdit('updated_visa_edit');
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Updated Visa', 'updated_visa', $editor, $this->dataset);
            $editColumn->SetReadOnly(true);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for updated_date field
            //
            $editor = new DateTimeEdit('updated_date_edit', false, 'd.m.Y H:i:s');
            $editColumn = new CustomEditColumn('Updated Date', 'updated_date', $editor, $this->dataset);
            $editColumn->SetReadOnly(true);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            $grid->SetShowAddButton(true && $this->GetSecurityInfo()->HasAddGrant());
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
            $column->setHrefTemplate('parlamentarier.php?operation=view&pk0=%parlamentarier_id%');
            $column->setTarget('_self');
            $grid->AddPrintColumn($column);
            
            //
            // View column for anzeige_name_mixed field
            //
            $column = new TextViewColumn('kommission_id', 'kommission_id_anzeige_name_mixed', 'Kommission', $this->dataset);
            $column->SetOrderable(true);
            $column->setHrefTemplate('kommission.php?operation=view&pk0=%kommission_id%');
            $column->setTarget('_self');
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
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y');
            $grid->AddPrintColumn($column);
            
            //
            // View column for bis field
            //
            $column = new DateTimeViewColumn('bis', 'bis', 'Bis', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y');
            $grid->AddPrintColumn($column);
            
            //
            // View column for parlament_committee_function field
            //
            $column = new TextViewColumn('parlament_committee_function', 'parlament_committee_function', 'Parlament Committee Function', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for parlament_committee_function_name field
            //
            $column = new TextViewColumn('parlament_committee_function_name', 'parlament_committee_function_name', 'Parlament Committee Function Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
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
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
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
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
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
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
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
            $grid->AddExportColumn($column);
            
            //
            // View column for anzeige_name_mixed field
            //
            $column = new TextViewColumn('kommission_id', 'kommission_id_anzeige_name_mixed', 'Kommission', $this->dataset);
            $column->SetOrderable(true);
            $column->setHrefTemplate('kommission.php?operation=view&pk0=%kommission_id%');
            $column->setTarget('_self');
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
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y');
            $grid->AddExportColumn($column);
            
            //
            // View column for bis field
            //
            $column = new DateTimeViewColumn('bis', 'bis', 'Bis', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y');
            $grid->AddExportColumn($column);
            
            //
            // View column for parlament_committee_function field
            //
            $column = new TextViewColumn('parlament_committee_function', 'parlament_committee_function', 'Parlament Committee Function', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for parlament_committee_function_name field
            //
            $column = new TextViewColumn('parlament_committee_function_name', 'parlament_committee_function_name', 'Parlament Committee Function Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
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
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
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
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
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
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
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
            $grid->AddCompareColumn($column);
            
            //
            // View column for anzeige_name_mixed field
            //
            $column = new TextViewColumn('kommission_id', 'kommission_id_anzeige_name_mixed', 'Kommission', $this->dataset);
            $column->SetOrderable(true);
            $column->setHrefTemplate('kommission.php?operation=view&pk0=%kommission_id%');
            $column->setTarget('_self');
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
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y');
            $grid->AddCompareColumn($column);
            
            //
            // View column for bis field
            //
            $column = new DateTimeViewColumn('bis', 'bis', 'Bis', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('d.m.Y');
            $grid->AddCompareColumn($column);
            
            //
            // View column for parlament_committee_function field
            //
            $column = new TextViewColumn('parlament_committee_function', 'parlament_committee_function', 'Parlament Committee Function', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for parlament_committee_function_name field
            //
            $column = new TextViewColumn('parlament_committee_function_name', 'parlament_committee_function_name', 'Parlament Committee Function Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for notizen field
            //
            $column = new TextViewColumn('notizen', 'notizen', 'Notizen', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
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
            $result->setMultiEditAllowed($this->GetSecurityInfo()->HasEditGrant() && true);
            $result->setIncludeAllFieldsForMultiEditByDefault(false);
            $result->setTableBordered(true);
            $result->setTableCondensed(true);
            
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
    
    
            $this->SetViewFormTitle('"%parlamentarier_id%" in Kommission "%kommission_id%"');
            $this->SetEditFormTitle('Edit "%parlamentarier_id%" in Kommission "%kommission_id%"');
            $this->SetInsertFormTitle('Add new In Kommission');
            $this->SetShowPageList(true);
            $this->SetShowTopPageNavigator(false);
            $this->SetShowBottomPageNavigator(true);
            $this->setPrintListAvailable(true);
            $this->setPrintListRecordAvailable(false);
            $this->setPrintOneRecordAvailable(true);
            $this->setAllowPrintSelectedRecords(true);
            $this->setExportListAvailable(array('pdf', 'excel', 'word', 'xml', 'csv'));
            $this->setExportSelectedRecordsAvailable(array('pdf', 'excel', 'word', 'xml', 'csv'));
            $this->setExportListRecordAvailable(array());
            $this->setExportOneRecordAvailable(array('pdf', 'excel', 'word', 'xml', 'csv'));
            $this->setDescription('' . $GLOBALS["edit_header_message"] /*afterburner*/  . '
            
            <div class="wiki-table-help">
            <p>WIRD AUTOMATISCH EINGELESEN! Falls veraltete Daten vorhanden sind, bitte den Admin Roland Kurmann benachrichtigen.</p>
            <p>Diese Tabelle ordnet einem <a class="wiki external" target="_blank" href="http://www.parlament.ch/D/ORGANE-MITGLIEDER/Seiten/default.aspx" rel="_blank external nofollow">Parlamentarier</a> seine parlamentarischen <a class="wiki external" target="_blank" href="http://www.parlament.ch/D/ORGANE-MITGLIEDER/KOMMISSIONEN/Seiten/default.aspx" rel="_blank external nofollow">Kommissions</a>- und <a class="wiki external" target="_blank" href="http://www.parlament.ch/D/ORGANE-MITGLIEDER/DELEGATIONEN/Seiten/default.aspx" rel="_blank external nofollow">Delegations</a>mitgliedschaften zu.
            </p>
            </div>
            
            ' . $GLOBALS["edit_general_hint"] /*afterburner*/  . '');
            $this->setShowFormErrorsOnTop(true);
    
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
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'insert_in_kommission_parlamentarier_id_search', 'id', 'anzeige_name', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
            
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
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_in_kommission_parlamentarier_id_search', 'id', 'anzeige_name', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`v_kommission`');
            $lookupDataset->addFields(
                array(
                    new StringField('anzeige_name'),
                    new StringField('anzeige_name_de'),
                    new StringField('anzeige_name_fr'),
                    new StringField('anzeige_name_mixed'),
                    new IntegerField('id', true),
                    new StringField('abkuerzung', true),
                    new StringField('abkuerzung_fr'),
                    new StringField('name', true),
                    new StringField('name_fr'),
                    new IntegerField('rat_id'),
                    new StringField('typ', true),
                    new StringField('art'),
                    new StringField('beschreibung'),
                    new StringField('beschreibung_fr'),
                    new StringField('sachbereiche', true),
                    new StringField('sachbereiche_fr'),
                    new IntegerField('anzahl_mitglieder'),
                    new IntegerField('anzahl_nationalraete'),
                    new IntegerField('anzahl_staenderaete'),
                    new IntegerField('mutter_kommission_id'),
                    new IntegerField('zweitrat_kommission_id'),
                    new DateField('von'),
                    new DateField('bis'),
                    new StringField('parlament_url'),
                    new IntegerField('parlament_id'),
                    new IntegerField('parlament_committee_number'),
                    new IntegerField('parlament_subcommittee_number'),
                    new IntegerField('parlament_type_code'),
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
                    new StringField('abkuerzung_de', true),
                    new StringField('beschreibung_de'),
                    new StringField('sachbereiche_de', true),
                    new IntegerField('published', true),
                    new IntegerField('created_date_unix', true),
                    new IntegerField('updated_date_unix', true),
                    new IntegerField('eingabe_abgeschlossen_datum_unix'),
                    new IntegerField('kontrolliert_datum_unix'),
                    new IntegerField('freigabe_datum_unix')
                )
            );
            $lookupDataset->setOrderByField('anzeige_name_mixed', 'ASC');
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_in_kommission_kommission_id_search', 'id', 'anzeige_name_mixed', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
            
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
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'edit_in_kommission_parlamentarier_id_search', 'id', 'anzeige_name', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
            
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
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'multi_edit_in_kommission_parlamentarier_id_search', 'id', 'anzeige_name', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
        }
       
        protected function doCustomRenderColumn($fieldName, $fieldData, $rowData, &$customText, &$handled)
        { 
            customOnCustomRenderColumn('in_kommission', $fieldName, $fieldData, $rowData, $customText, $handled);
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
            customDrawRow('in_kommission', $rowData, $rowCellStyles, $rowStyles, $rowClasses, $cellClasses);
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
            check_bis_date($page, $rowData, $cancel, $message, $tableName);
        }
    
        protected function doBeforeUpdateRecord($page, $oldRowData, &$rowData, $tableName, &$cancel, &$message, &$messageDisplayTime)
        {
            check_bis_date($page, $rowData, $cancel, $message, $tableName);
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
        $Page = new in_kommissionPage("in_kommission", "in_kommission.php", GetCurrentUserPermissionsForPage("in_kommission"), 'UTF-8');
        $Page->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource("in_kommission"));
        GetApplication()->SetMainPage($Page);
        before_render($Page); /*afterburner*/ 
        GetApplication()->Run();
    }
    catch(Exception $e)
    {
        ShowErrorPage($e);
    }
