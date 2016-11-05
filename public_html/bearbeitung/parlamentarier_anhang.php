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

    
    
    
    // OnBeforePageExecute event handler
    
    
    
    class parlamentarier_anhangPage extends Page
    {
        protected function DoBeforeCreate()
        {
            $this->dataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`parlamentarier_anhang`');
            $field = new IntegerField('id', null, null, true);
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, true);
            $field = new IntegerField('parlamentarier_id');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('datei');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('dateiname');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('dateierweiterung');
            $this->dataset->AddField($field, false);
            $field = new StringField('dateiname_voll');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('mime_type');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('encoding');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('beschreibung');
            $field->SetIsNotNull(true);
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
            $this->dataset->AddLookupField('parlamentarier_id', 'v_parlamentarier_simple', new IntegerField('id'), new StringField('anzeige_name', 'parlamentarier_id_anzeige_name', 'parlamentarier_id_anzeige_name_v_parlamentarier_simple'), 'parlamentarier_id_anzeige_name_v_parlamentarier_simple');
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
                new FilterColumn($this->dataset, 'datei', 'datei', $this->RenderText('Datei')),
                new FilterColumn($this->dataset, 'dateierweiterung', 'dateierweiterung', $this->RenderText('Dateierweiterung')),
                new FilterColumn($this->dataset, 'dateiname_voll', 'dateiname_voll', $this->RenderText('Dateiname')),
                new FilterColumn($this->dataset, 'mime_type', 'mime_type', $this->RenderText('Mime Type')),
                new FilterColumn($this->dataset, 'encoding', 'encoding', $this->RenderText('Encoding')),
                new FilterColumn($this->dataset, 'beschreibung', 'beschreibung', $this->RenderText('Beschreibung')),
                new FilterColumn($this->dataset, 'created_visa', 'created_visa', $this->RenderText('Created Visa')),
                new FilterColumn($this->dataset, 'created_date', 'created_date', $this->RenderText('Created Date')),
                new FilterColumn($this->dataset, 'updated_visa', 'updated_visa', $this->RenderText('Updated Visa')),
                new FilterColumn($this->dataset, 'updated_date', 'updated_date', $this->RenderText('Updated Date')),
                new FilterColumn($this->dataset, 'dateiname', 'dateiname', $this->RenderText('Dateiname')),
                new FilterColumn($this->dataset, 'freigabe_visa', 'freigabe_visa', $this->RenderText('Freigabe Visa')),
                new FilterColumn($this->dataset, 'freigabe_datum', 'freigabe_datum', $this->RenderText('Freigabe Datum'))
            );
        }
    
        protected function setupQuickFilter(QuickFilter $quickFilter, FixedKeysArray $columns)
        {
            $quickFilter
                ->addColumn($columns['id'])
                ->addColumn($columns['parlamentarier_id'])
                ->addColumn($columns['datei'])
                ->addColumn($columns['dateierweiterung'])
                ->addColumn($columns['dateiname_voll'])
                ->addColumn($columns['mime_type'])
                ->addColumn($columns['encoding'])
                ->addColumn($columns['beschreibung'])
                ->addColumn($columns['created_visa'])
                ->addColumn($columns['created_date'])
                ->addColumn($columns['updated_visa'])
                ->addColumn($columns['updated_date']);
        }
    
        protected function setupColumnFilter(ColumnFilter $columnFilter)
        {
            $columnFilter
                ->setOptionsFor('parlamentarier_id')
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
            
            $main_editor = new TextEdit('datei');
            
            $filterBuilder->addColumn(
                $columns['datei'],
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
            
            $main_editor = new TextEdit('dateierweiterung_edit');
            $main_editor->SetMaxLength(15);
            
            $filterBuilder->addColumn(
                $columns['dateierweiterung'],
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
            
            $main_editor = new TextEdit('dateiname_voll');
            
            $filterBuilder->addColumn(
                $columns['dateiname_voll'],
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
            
            $main_editor = new TextEdit('mime_type_edit');
            $main_editor->SetMaxLength(100);
            
            $filterBuilder->addColumn(
                $columns['mime_type'],
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
            
            $main_editor = new TextEdit('encoding_edit');
            $main_editor->SetMaxLength(20);
            
            $filterBuilder->addColumn(
                $columns['encoding'],
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
        }
    
        protected function AddFieldColumns(Grid $grid, $withDetails = true)
        {
            //
            // View column for id field
            //
            $column = new TextViewColumn('id', 'id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('Technischer Schlüssel des Parlamentarieranhangs'));
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
            $column->SetDescription($this->RenderText('Fremdschlüssel eines Parlamentariers'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for datei field
            //
            $column = new DownloadDataColumn('datei', 'datei', 'Datei', $this->dataset, $this->GetLocalizerCaptions()->GetMessageString('Download'));
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('Datei'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for dateierweiterung field
            //
            $column = new TextViewColumn('dateierweiterung', 'dateierweiterung', 'Dateierweiterung', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('Erweiterung der Datei'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for dateiname_voll field
            //
            $column = new TextViewColumn('dateiname_voll', 'dateiname_voll', 'Dateiname', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('parlamentarier_anhangGrid_dateiname_voll_handler_list');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('Dateiname inkl. Erweiterung'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for mime_type field
            //
            $column = new TextViewColumn('mime_type', 'mime_type', 'Mime Type', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('parlamentarier_anhangGrid_mime_type_handler_list');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('MIME Type der Datei'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for encoding field
            //
            $column = new TextViewColumn('encoding', 'encoding', 'Encoding', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('Encoding der Datei'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for beschreibung field
            //
            $column = new TextViewColumn('beschreibung', 'beschreibung', 'Beschreibung', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('parlamentarier_anhangGrid_beschreibung_handler_list');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('Beschreibung des Anhangs'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for created_visa field
            //
            $column = new TextViewColumn('created_visa', 'created_visa', 'Created Visa', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('Datensatz erstellt von'));
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
            $column->SetDescription($this->RenderText('Abgäendert von'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for updated_date field
            //
            $column = new DateTimeViewColumn('updated_date', 'updated_date', 'Updated Date', $this->dataset);
            $column->SetDateTimeFormat('d.m.Y H:i:s');
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('Abgäendert am'));
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
            // View column for datei field
            //
            $column = new DownloadDataColumn('datei', 'datei', 'Datei', $this->dataset, $this->GetLocalizerCaptions()->GetMessageString('Download'));
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for dateierweiterung field
            //
            $column = new TextViewColumn('dateierweiterung', 'dateierweiterung', 'Dateierweiterung', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for dateiname_voll field
            //
            $column = new TextViewColumn('dateiname_voll', 'dateiname_voll', 'Dateiname', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('parlamentarier_anhangGrid_dateiname_voll_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for mime_type field
            //
            $column = new TextViewColumn('mime_type', 'mime_type', 'Mime Type', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('parlamentarier_anhangGrid_mime_type_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for encoding field
            //
            $column = new TextViewColumn('encoding', 'encoding', 'Encoding', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for beschreibung field
            //
            $column = new TextViewColumn('beschreibung', 'beschreibung', 'Beschreibung', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('parlamentarier_anhangGrid_beschreibung_handler_view');
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
            // Edit column for dateierweiterung field
            //
            $editor = new TextEdit('dateierweiterung_edit');
            $editor->SetMaxLength(15);
            $editColumn = new CustomEditColumn('Dateierweiterung', 'dateierweiterung', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for dateiname_voll field
            //
            $editor = new TextAreaEdit('dateiname_voll_edit', 50, 8);
            $editColumn = new CustomEditColumn('Dateiname', 'dateiname_voll', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for mime_type field
            //
            $editor = new TextEdit('mime_type_edit');
            $editor->SetMaxLength(100);
            $editColumn = new CustomEditColumn('Mime Type', 'mime_type', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for encoding field
            //
            $editor = new TextEdit('encoding_edit');
            $editor->SetMaxLength(20);
            $editColumn = new CustomEditColumn('Encoding', 'encoding', $editor, $this->dataset);
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
            $editor = new DateTimeEdit('created_date_edit', false, 'Y-m-d H:i:s');
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
            $editor = new DateTimeEdit('updated_date_edit', false, 'Y-m-d H:i:s');
            $editColumn = new CustomEditColumn('Updated Date', 'updated_date', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
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
            // Edit column for datei field
            //
            $editor = new ImageUploader('datei_edit');
            $editor->SetShowImage(false);
            $editColumn = new UploadFileToFolderColumn('Datei', 'datei', $editor, $this->dataset, false, false, '' . $GLOBALS["private_files_dir"] /*afterburner*/  . '/parlamentarier_anhang/%parlamentarier_id%', '%original_file_name%', $this->OnGetCustomUploadFilename, false);
            $editColumn->SetReplaceUploadedFileIfExist(true);
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
            $grid->AddPrintColumn($column);
            
            //
            // View column for datei field
            //
            $column = new DownloadDataColumn('datei', 'datei', 'Datei', $this->dataset, $this->GetLocalizerCaptions()->GetMessageString('Download'));
            $grid->AddPrintColumn($column);
            
            //
            // View column for dateierweiterung field
            //
            $column = new TextViewColumn('dateierweiterung', 'dateierweiterung', 'Dateierweiterung', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for dateiname_voll field
            //
            $column = new TextViewColumn('dateiname_voll', 'dateiname_voll', 'Dateiname Voll', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('parlamentarier_anhangGrid_dateiname_voll_handler_print');
            $grid->AddPrintColumn($column);
            
            //
            // View column for mime_type field
            //
            $column = new TextViewColumn('mime_type', 'mime_type', 'Mime Type', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('parlamentarier_anhangGrid_mime_type_handler_print');
            $grid->AddPrintColumn($column);
            
            //
            // View column for encoding field
            //
            $column = new TextViewColumn('encoding', 'encoding', 'Encoding', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for beschreibung field
            //
            $column = new TextViewColumn('beschreibung', 'beschreibung', 'Beschreibung', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('parlamentarier_anhangGrid_beschreibung_handler_print');
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
            // View column for datei field
            //
            $column = new DownloadDataColumn('datei', 'datei', 'Datei', $this->dataset, $this->GetLocalizerCaptions()->GetMessageString('Download'));
            $grid->AddExportColumn($column);
            
            //
            // View column for dateierweiterung field
            //
            $column = new TextViewColumn('dateierweiterung', 'dateierweiterung', 'Dateierweiterung', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for dateiname_voll field
            //
            $column = new TextViewColumn('dateiname_voll', 'dateiname_voll', 'Dateiname Voll', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('parlamentarier_anhangGrid_dateiname_voll_handler_export');
            $grid->AddExportColumn($column);
            
            //
            // View column for mime_type field
            //
            $column = new TextViewColumn('mime_type', 'mime_type', 'Mime Type', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('parlamentarier_anhangGrid_mime_type_handler_export');
            $grid->AddExportColumn($column);
            
            //
            // View column for encoding field
            //
            $column = new TextViewColumn('encoding', 'encoding', 'Encoding', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for beschreibung field
            //
            $column = new TextViewColumn('beschreibung', 'beschreibung', 'Beschreibung', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('parlamentarier_anhangGrid_beschreibung_handler_export');
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
            // View column for datei field
            //
            $column = new DownloadDataColumn('datei', 'datei', 'Datei', $this->dataset, $this->GetLocalizerCaptions()->GetMessageString('Download'));
            $grid->AddCompareColumn($column);
            
            //
            // View column for dateierweiterung field
            //
            $column = new TextViewColumn('dateierweiterung', 'dateierweiterung', 'Dateierweiterung', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for dateiname_voll field
            //
            $column = new TextViewColumn('dateiname_voll', 'dateiname_voll', 'Dateiname', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('parlamentarier_anhangGrid_dateiname_voll_handler_compare');
            $grid->AddCompareColumn($column);
            
            //
            // View column for mime_type field
            //
            $column = new TextViewColumn('mime_type', 'mime_type', 'Mime Type', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('parlamentarier_anhangGrid_mime_type_handler_compare');
            $grid->AddCompareColumn($column);
            
            //
            // View column for encoding field
            //
            $column = new TextViewColumn('encoding', 'encoding', 'Encoding', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for beschreibung field
            //
            $column = new TextViewColumn('beschreibung', 'beschreibung', 'Beschreibung', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('parlamentarier_anhangGrid_beschreibung_handler_compare');
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
            // View column for dateiname field
            //
            $column = new TextViewColumn('dateiname', 'dateiname', 'Dateiname', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('parlamentarier_anhangGrid_dateiname_handler_compare');
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
            $handler = new PrivateFileDownloadHTTPHandler($this->dataset, 'datei', 'datei_handler', '%mime_type%', '%datei%', true);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for dateiname_voll field
            //
            $column = new TextViewColumn('dateiname_voll', 'dateiname_voll', 'Dateiname', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'parlamentarier_anhangGrid_dateiname_voll_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for mime_type field
            //
            $column = new TextViewColumn('mime_type', 'mime_type', 'Mime Type', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'parlamentarier_anhangGrid_mime_type_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for beschreibung field
            //
            $column = new TextViewColumn('beschreibung', 'beschreibung', 'Beschreibung', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'parlamentarier_anhangGrid_beschreibung_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $handler = new PrivateFileDownloadHTTPHandler($this->dataset, 'datei', 'datei_handler', '%mime_type%', '%datei%', true);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for dateiname_voll field
            //
            $column = new TextViewColumn('dateiname_voll', 'dateiname_voll', 'Dateiname Voll', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'parlamentarier_anhangGrid_dateiname_voll_handler_print', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for mime_type field
            //
            $column = new TextViewColumn('mime_type', 'mime_type', 'Mime Type', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'parlamentarier_anhangGrid_mime_type_handler_print', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for beschreibung field
            //
            $column = new TextViewColumn('beschreibung', 'beschreibung', 'Beschreibung', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'parlamentarier_anhangGrid_beschreibung_handler_print', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $handler = new PrivateFileDownloadHTTPHandler($this->dataset, 'datei', 'datei_handler', '%mime_type%', '%datei%', true);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for dateiname_voll field
            //
            $column = new TextViewColumn('dateiname_voll', 'dateiname_voll', 'Dateiname', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'parlamentarier_anhangGrid_dateiname_voll_handler_compare', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for mime_type field
            //
            $column = new TextViewColumn('mime_type', 'mime_type', 'Mime Type', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'parlamentarier_anhangGrid_mime_type_handler_compare', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for beschreibung field
            //
            $column = new TextViewColumn('beschreibung', 'beschreibung', 'Beschreibung', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'parlamentarier_anhangGrid_beschreibung_handler_compare', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for dateiname field
            //
            $column = new TextViewColumn('dateiname', 'dateiname', 'Dateiname', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'parlamentarier_anhangGrid_dateiname_handler_compare', $column);
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
            
            $handler = new PrivateFileDownloadHTTPHandler($this->dataset, 'datei', 'datei_handler', '%mime_type%', '%datei%', true);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for dateiname_voll field
            //
            $column = new TextViewColumn('dateiname_voll', 'dateiname_voll', 'Dateiname', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'parlamentarier_anhangGrid_dateiname_voll_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for mime_type field
            //
            $column = new TextViewColumn('mime_type', 'mime_type', 'Mime Type', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'parlamentarier_anhangGrid_mime_type_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for beschreibung field
            //
            $column = new TextViewColumn('beschreibung', 'beschreibung', 'Beschreibung', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'parlamentarier_anhangGrid_beschreibung_handler_view', $column);
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
            datei_anhang_insert($page, $rowData, $cancel, $message, $tableName);
        }
    
        protected function doBeforeUpdateRecord($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName)
        {
    
        }
    
        protected function doBeforeDeleteRecord($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName)
        {
            datei_anhang_delete($page, $rowData, $cancel, $message, $tableName);
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
        $Page = new parlamentarier_anhangPage("parlamentarier_anhang", "parlamentarier_anhang.php", GetCurrentUserGrantForDataSource("parlamentarier_anhang"), 'UTF-8');
        $Page->SetTitle('Parlamentarier Anhang');
        $Page->SetMenuLabel('Parlamentarier Anhang');
        $Page->SetHeader(GetPagesHeader());
        $Page->SetFooter(GetPagesFooter());
        $Page->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource("parlamentarier_anhang"));
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
