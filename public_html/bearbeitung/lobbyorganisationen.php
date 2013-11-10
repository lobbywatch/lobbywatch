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
    
    
    
    class lobbyorganisationenPage extends Page
    {
        protected function DoBeforeCreate()
        {
            $this->dataset = new TableDataset(
                new MyConnectionFactory(),
                GetConnectionOptions(),
                '`lobbyorganisationen`');
            $field = new IntegerField('id_lobbyorg', null, null, true);
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, true);
            $field = new StringField('lobbyname');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('lobbydescription');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('lobbyorgtyp');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('weblink');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new IntegerField('id_lobbytyp');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('id_lobbygroup');
            $this->dataset->AddField($field, false);
            $field = new StringField('vernehmlassung');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('parlam_verbindung');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $this->dataset->AddLookupField('id_lobbytyp', 'lobbytypen', new IntegerField('id_lobbytyp', null, null, true), new StringField('lt_kategorie', 'id_lobbytyp_lt_kategorie', 'id_lobbytyp_lt_kategorie_lobbytypen'), 'id_lobbytyp_lt_kategorie_lobbytypen');
            $this->dataset->AddLookupField('id_lobbygroup', 'lobbygruppen', new IntegerField('id_lobbygroup', null, null, true), new StringField('lg_bezeichnung', 'id_lobbygroup_lg_bezeichnung', 'id_lobbygroup_lg_bezeichnung_lobbygruppen'), 'id_lobbygroup_lg_bezeichnung_lobbygruppen');
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
            if (GetCurrentUserGrantForDataSource('interessenbindungen')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('Interessenbindungen'), 'interessenbindungen.php', $this->RenderText('Interessenbindungen'), $currentPageCaption == $this->RenderText('Interessenbindungen')));
            if (GetCurrentUserGrantForDataSource('zugangsberechtigungen')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('Zugangsberechtigungen'), 'zugangsberechtigungen.php', $this->RenderText('Zugangsberechtigungen'), $currentPageCaption == $this->RenderText('Zugangsberechtigungen')));
            if (GetCurrentUserGrantForDataSource('parlamentarier')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('Parlamentarier'), 'parlamentarier.php', $this->RenderText('Parlamentarier'), $currentPageCaption == $this->RenderText('Parlamentarier')));
            if (GetCurrentUserGrantForDataSource('lobbyorganisationen')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('Lobbyorganisationen'), 'lobbyorganisationen.php', $this->RenderText('Lobbyorganisationen'), $currentPageCaption == $this->RenderText('Lobbyorganisationen')));
            if (GetCurrentUserGrantForDataSource('lobbygruppen')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('Lobbygruppen'), 'lobbygruppen.php', $this->RenderText('Lobbygruppen'), $currentPageCaption == $this->RenderText('Lobbygruppen')));
            if (GetCurrentUserGrantForDataSource('lobbytypen')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('Lobbytypen'), 'lobbytypen.php', $this->RenderText('Lobbytypen'), $currentPageCaption == $this->RenderText('Lobbytypen')));
            if (GetCurrentUserGrantForDataSource('kommissionen')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('Kommissionen'), 'kommissionen.php', $this->RenderText('Kommissionen'), $currentPageCaption == $this->RenderText('Kommissionen')));
            
            if ( HasAdminPage() && GetApplication()->HasAdminGrantForCurrentUser() )
              $result->AddPage(new PageLink($this->GetLocalizerCaptions()->GetMessageString('AdminPage'), 'phpgen_admin.php', $this->GetLocalizerCaptions()->GetMessageString('AdminPage'), false, true));
            return $result;
        }
    
        protected function CreateRssGenerator()
        {
            return null;
        }
    
        protected function CreateGridSearchControl(Grid $grid)
        {
            $grid->UseFilter = true;
            $grid->SearchControl = new SimpleSearch('lobbyorganisationenssearch', $this->dataset,
                array('id_lobbyorg', 'lobbyname', 'lobbydescription', 'lobbyorgtyp', 'weblink', 'id_lobbytyp_lt_kategorie', 'id_lobbygroup_lg_bezeichnung', 'vernehmlassung', 'parlam_verbindung'),
                array($this->RenderText('Id Lobbyorg'), $this->RenderText('Lobbyname'), $this->RenderText('Lobbydescription'), $this->RenderText('Lobbyorgtyp'), $this->RenderText('Weblink'), $this->RenderText('Id Lobbytyp'), $this->RenderText('Id Lobbygroup'), $this->RenderText('Vernehmlassung'), $this->RenderText('Parlam Verbindung')),
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
            $this->AdvancedSearchControl = new AdvancedSearchControl('lobbyorganisationenasearch', $this->dataset, $this->GetLocalizerCaptions(), $this->GetColumnVariableContainer(), $this->CreateLinkBuilder());
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('id_lobbyorg', $this->RenderText('Id Lobbyorg')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('lobbyname', $this->RenderText('Lobbyname')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('lobbydescription', $this->RenderText('Lobbydescription')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('lobbyorgtyp', $this->RenderText('Lobbyorgtyp')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('weblink', $this->RenderText('Weblink')));
            
            $lookupDataset = new TableDataset(
                new MyConnectionFactory(),
                GetConnectionOptions(),
                '`lobbytypen`');
            $field = new IntegerField('id_lobbytyp', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('lt_kategorie');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('lt_description');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('factsheet');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('lt_kommission');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateLookupSearchInput('id_lobbytyp', $this->RenderText('Id Lobbytyp'), $lookupDataset, 'id_lobbytyp', 'lt_kategorie', false));
            
            $lookupDataset = new TableDataset(
                new MyConnectionFactory(),
                GetConnectionOptions(),
                '`lobbygruppen`');
            $field = new IntegerField('id_lobbygroup', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('lg_bezeichnung');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('lg_description');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('id_lobbytyp');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateLookupSearchInput('id_lobbygroup', $this->RenderText('Id Lobbygroup'), $lookupDataset, 'id_lobbygroup', 'lg_bezeichnung', false));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('vernehmlassung', $this->RenderText('Vernehmlassung')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('parlam_verbindung', $this->RenderText('Parlam Verbindung')));
        }
    
        protected function AddOperationsColumns(Grid $grid)
        {
            $actionsBandName = 'actions';
            $grid->AddBandToBegin($actionsBandName, $this->GetLocalizerCaptions()->GetMessageString('Actions'), true);
            if ($this->GetSecurityInfo()->HasViewGrant())
            {
                $column = new RowOperationByLinkColumn($this->GetLocalizerCaptions()->GetMessageString('View'), OPERATION_VIEW, $this->dataset);
                $grid->AddViewColumn($column, $actionsBandName);
            }
            if ($this->GetSecurityInfo()->HasEditGrant())
            {
                $column = new ModalDialogEditRowColumn(
                    $this->GetLocalizerCaptions()->GetMessageString('Edit'), $this->dataset,
                    $this->GetLocalizerCaptions()->GetMessageString('Edit'),
                    $this->GetModalGridEditingHandler());
                $grid->AddViewColumn($column, $actionsBandName);
                $column->OnShow->AddListener('ShowEditButtonHandler', $this);
            }
            if ($this->GetSecurityInfo()->HasDeleteGrant())
            {
                $column = new RowOperationByLinkColumn($this->GetLocalizerCaptions()->GetMessageString('Delete'), OPERATION_DELETE, $this->dataset);
                $grid->AddViewColumn($column, $actionsBandName);
                $column->OnShow->AddListener('ShowDeleteButtonHandler', $this);
            $column->SetAdditionalAttribute("data-modal-delete", "true");
            $column->SetAdditionalAttribute("data-delete-handler-name", $this->GetModalGridDeleteHandler());
            }
            if ($this->GetSecurityInfo()->HasAddGrant())
            {
                $column = new ModalDialogCopyRowColumn(
                    $this->GetLocalizerCaptions()->GetMessageString('Copy'), $this->dataset,
                    $this->GetLocalizerCaptions()->GetMessageString('Copy'),
                    $this->GetModalGridCopyHandler());
                $grid->AddViewColumn($column, $actionsBandName);
            }
        }
    
        protected function AddFieldColumns(Grid $grid)
        {
            //
            // View column for id_lobbyorg field
            //
            $column = new TextViewColumn('id_lobbyorg', 'Id Lobbyorg', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for lobbyname field
            //
            $column = new TextViewColumn('lobbyname', 'Lobbyname', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('lobbyname_handler');
            
            /* <inline edit column> */
            //
            // Edit column for lobbyname field
            //
            $editor = new TextAreaEdit('lobbyname_edit', 50, 8);
            $editColumn = new CustomEditColumn('Lobbyname', 'lobbyname', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetEditOperationColumn($editColumn);
            /* </inline edit column> */
            
            /* <inline insert column> */
            //
            // Edit column for lobbyname field
            //
            $editor = new TextAreaEdit('lobbyname_edit', 50, 8);
            $editColumn = new CustomEditColumn('Lobbyname', 'lobbyname', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetInsertOperationColumn($editColumn);
            /* </inline insert column> */
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for lobbydescription field
            //
            $column = new TextViewColumn('lobbydescription', 'Lobbydescription', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('lobbydescription_handler');
            
            /* <inline edit column> */
            //
            // Edit column for lobbydescription field
            //
            $editor = new TextAreaEdit('lobbydescription_edit', 50, 8);
            $editColumn = new CustomEditColumn('Lobbydescription', 'lobbydescription', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetEditOperationColumn($editColumn);
            /* </inline edit column> */
            
            /* <inline insert column> */
            //
            // Edit column for lobbydescription field
            //
            $editor = new TextAreaEdit('lobbydescription_edit', 50, 8);
            $editColumn = new CustomEditColumn('Lobbydescription', 'lobbydescription', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetInsertOperationColumn($editColumn);
            /* </inline insert column> */
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for lobbyorgtyp field
            //
            $column = new TextViewColumn('lobbyorgtyp', 'Lobbyorgtyp', $this->dataset);
            $column->SetOrderable(true);
            
            /* <inline edit column> */
            //
            // Edit column for lobbyorgtyp field
            //
            $editor = new CheckBoxGroup('lobbyorgtyp_edit');
            $editor->SetDisplayMode(CheckBoxGroup::StackedMode);
            $editor->AddValue('EinzelOrganisation', $this->RenderText('EinzelOrganisation'));
            $editor->AddValue('DachOrganisation', $this->RenderText('DachOrganisation'));
            $editor->AddValue('MitgliedsOrganisation', $this->RenderText('MitgliedsOrganisation'));
            $editor->AddValue('LeistungsErbringer', $this->RenderText('LeistungsErbringer'));
            $editor->AddValue('dezidierteLobby', $this->RenderText('dezidierteLobby'));
            $editColumn = new CustomEditColumn('Lobbyorgtyp', 'lobbyorgtyp', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetEditOperationColumn($editColumn);
            /* </inline edit column> */
            
            /* <inline insert column> */
            //
            // Edit column for lobbyorgtyp field
            //
            $editor = new CheckBoxGroup('lobbyorgtyp_edit');
            $editor->SetDisplayMode(CheckBoxGroup::StackedMode);
            $editor->AddValue('EinzelOrganisation', $this->RenderText('EinzelOrganisation'));
            $editor->AddValue('DachOrganisation', $this->RenderText('DachOrganisation'));
            $editor->AddValue('MitgliedsOrganisation', $this->RenderText('MitgliedsOrganisation'));
            $editor->AddValue('LeistungsErbringer', $this->RenderText('LeistungsErbringer'));
            $editor->AddValue('dezidierteLobby', $this->RenderText('dezidierteLobby'));
            $editColumn = new CustomEditColumn('Lobbyorgtyp', 'lobbyorgtyp', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetInsertOperationColumn($editColumn);
            /* </inline insert column> */
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for weblink field
            //
            $column = new TextViewColumn('weblink', 'Weblink', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('weblink_handler');
            
            /* <inline edit column> */
            //
            // Edit column for weblink field
            //
            $editor = new TextAreaEdit('weblink_edit', 50, 8);
            $editColumn = new CustomEditColumn('Weblink', 'weblink', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetEditOperationColumn($editColumn);
            /* </inline edit column> */
            
            /* <inline insert column> */
            //
            // Edit column for weblink field
            //
            $editor = new TextAreaEdit('weblink_edit', 50, 8);
            $editColumn = new CustomEditColumn('Weblink', 'weblink', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetInsertOperationColumn($editColumn);
            /* </inline insert column> */
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for lt_kategorie field
            //
            $column = new TextViewColumn('id_lobbytyp_lt_kategorie', 'Id Lobbytyp', $this->dataset);
            $column->SetOrderable(true);
            
            /* <inline edit column> */
            //
            // Edit column for id_lobbytyp field
            //
            $editor = new ComboBox('id_lobbytyp_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                new MyConnectionFactory(),
                GetConnectionOptions(),
                '`lobbytypen`');
            $field = new IntegerField('id_lobbytyp', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('lt_kategorie');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('lt_description');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('factsheet');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('lt_kommission');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $lookupDataset->SetOrderBy('lt_kategorie', GetOrderTypeAsSQL(otAscending));
            $editColumn = new LookUpEditColumn(
                'Id Lobbytyp', 
                'id_lobbytyp', 
                $editor, 
                $this->dataset, 'id_lobbytyp', 'lt_kategorie', $lookupDataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetEditOperationColumn($editColumn);
            /* </inline edit column> */
            
            /* <inline insert column> */
            //
            // Edit column for id_lobbytyp field
            //
            $editor = new ComboBox('id_lobbytyp_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                new MyConnectionFactory(),
                GetConnectionOptions(),
                '`lobbytypen`');
            $field = new IntegerField('id_lobbytyp', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('lt_kategorie');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('lt_description');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('factsheet');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('lt_kommission');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $lookupDataset->SetOrderBy('lt_kategorie', GetOrderTypeAsSQL(otAscending));
            $editColumn = new LookUpEditColumn(
                'Id Lobbytyp', 
                'id_lobbytyp', 
                $editor, 
                $this->dataset, 'id_lobbytyp', 'lt_kategorie', $lookupDataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetInsertOperationColumn($editColumn);
            /* </inline insert column> */
            $column->SetDescription($this->RenderText('FK lobbytyp'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for lg_bezeichnung field
            //
            $column = new TextViewColumn('id_lobbygroup_lg_bezeichnung', 'Id Lobbygroup', $this->dataset);
            $column->SetOrderable(true);
            
            /* <inline edit column> */
            //
            // Edit column for id_lobbygroup field
            //
            $editor = new ComboBox('id_lobbygroup_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                new MyConnectionFactory(),
                GetConnectionOptions(),
                '`lobbygruppen`');
            $field = new IntegerField('id_lobbygroup', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('lg_bezeichnung');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('lg_description');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('id_lobbytyp');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $lookupDataset->SetOrderBy('lg_bezeichnung', GetOrderTypeAsSQL(otAscending));
            $editColumn = new LookUpEditColumn(
                'Id Lobbygroup', 
                'id_lobbygroup', 
                $editor, 
                $this->dataset, 'id_lobbygroup', 'lg_bezeichnung', $lookupDataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetEditOperationColumn($editColumn);
            /* </inline edit column> */
            
            /* <inline insert column> */
            //
            // Edit column for id_lobbygroup field
            //
            $editor = new ComboBox('id_lobbygroup_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                new MyConnectionFactory(),
                GetConnectionOptions(),
                '`lobbygruppen`');
            $field = new IntegerField('id_lobbygroup', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('lg_bezeichnung');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('lg_description');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('id_lobbytyp');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $lookupDataset->SetOrderBy('lg_bezeichnung', GetOrderTypeAsSQL(otAscending));
            $editColumn = new LookUpEditColumn(
                'Id Lobbygroup', 
                'id_lobbygroup', 
                $editor, 
                $this->dataset, 'id_lobbygroup', 'lg_bezeichnung', $lookupDataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetInsertOperationColumn($editColumn);
            /* </inline insert column> */
            $column->SetDescription($this->RenderText('FK von lobbygruppen'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for vernehmlassung field
            //
            $column = new TextViewColumn('vernehmlassung', 'Vernehmlassung', $this->dataset);
            $column->SetOrderable(true);
            
            /* <inline edit column> */
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
            $column->SetEditOperationColumn($editColumn);
            /* </inline edit column> */
            
            /* <inline insert column> */
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
            $column->SetInsertOperationColumn($editColumn);
            /* </inline insert column> */
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for parlam_verbindung field
            //
            $column = new TextViewColumn('parlam_verbindung', 'Parlam Verbindung', $this->dataset);
            $column->SetOrderable(true);
            
            /* <inline edit column> */
            //
            // Edit column for parlam_verbindung field
            //
            $editor = new CheckBoxGroup('parlam_verbindung_edit');
            $editor->SetDisplayMode(CheckBoxGroup::StackedMode);
            $editor->AddValue('einzel', $this->RenderText('einzel'));
            $editor->AddValue('mehrere', $this->RenderText('mehrere'));
            $editor->AddValue('mitglied', $this->RenderText('mitglied'));
            $editor->AddValue('exekutiv', $this->RenderText('exekutiv'));
            $editor->AddValue('kommission', $this->RenderText('kommission'));
            $editColumn = new CustomEditColumn('Parlam Verbindung', 'parlam_verbindung', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetEditOperationColumn($editColumn);
            /* </inline edit column> */
            
            /* <inline insert column> */
            //
            // Edit column for parlam_verbindung field
            //
            $editor = new CheckBoxGroup('parlam_verbindung_edit');
            $editor->SetDisplayMode(CheckBoxGroup::StackedMode);
            $editor->AddValue('einzel', $this->RenderText('einzel'));
            $editor->AddValue('mehrere', $this->RenderText('mehrere'));
            $editor->AddValue('mitglied', $this->RenderText('mitglied'));
            $editor->AddValue('exekutiv', $this->RenderText('exekutiv'));
            $editor->AddValue('kommission', $this->RenderText('kommission'));
            $editColumn = new CustomEditColumn('Parlam Verbindung', 'parlam_verbindung', $editor, $this->dataset);
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
            // View column for id_lobbyorg field
            //
            $column = new TextViewColumn('id_lobbyorg', 'Id Lobbyorg', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for lobbyname field
            //
            $column = new TextViewColumn('lobbyname', 'Lobbyname', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('lobbyname_handler');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for lobbydescription field
            //
            $column = new TextViewColumn('lobbydescription', 'Lobbydescription', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('lobbydescription_handler');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for lobbyorgtyp field
            //
            $column = new TextViewColumn('lobbyorgtyp', 'Lobbyorgtyp', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for weblink field
            //
            $column = new TextViewColumn('weblink', 'Weblink', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('weblink_handler');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for lt_kategorie field
            //
            $column = new TextViewColumn('id_lobbytyp_lt_kategorie', 'Id Lobbytyp', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for lg_bezeichnung field
            //
            $column = new TextViewColumn('id_lobbygroup_lg_bezeichnung', 'Id Lobbygroup', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for vernehmlassung field
            //
            $column = new TextViewColumn('vernehmlassung', 'Vernehmlassung', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for parlam_verbindung field
            //
            $column = new TextViewColumn('parlam_verbindung', 'Parlam Verbindung', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
        }
    
        protected function AddEditColumns(Grid $grid)
        {
            //
            // Edit column for lobbyname field
            //
            $editor = new TextAreaEdit('lobbyname_edit', 50, 8);
            $editColumn = new CustomEditColumn('Lobbyname', 'lobbyname', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for lobbydescription field
            //
            $editor = new TextAreaEdit('lobbydescription_edit', 50, 8);
            $editColumn = new CustomEditColumn('Lobbydescription', 'lobbydescription', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for lobbyorgtyp field
            //
            $editor = new CheckBoxGroup('lobbyorgtyp_edit');
            $editor->SetDisplayMode(CheckBoxGroup::StackedMode);
            $editor->AddValue('EinzelOrganisation', $this->RenderText('EinzelOrganisation'));
            $editor->AddValue('DachOrganisation', $this->RenderText('DachOrganisation'));
            $editor->AddValue('MitgliedsOrganisation', $this->RenderText('MitgliedsOrganisation'));
            $editor->AddValue('LeistungsErbringer', $this->RenderText('LeistungsErbringer'));
            $editor->AddValue('dezidierteLobby', $this->RenderText('dezidierteLobby'));
            $editColumn = new CustomEditColumn('Lobbyorgtyp', 'lobbyorgtyp', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for weblink field
            //
            $editor = new TextAreaEdit('weblink_edit', 50, 8);
            $editColumn = new CustomEditColumn('Weblink', 'weblink', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for id_lobbytyp field
            //
            $editor = new ComboBox('id_lobbytyp_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                new MyConnectionFactory(),
                GetConnectionOptions(),
                '`lobbytypen`');
            $field = new IntegerField('id_lobbytyp', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('lt_kategorie');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('lt_description');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('factsheet');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('lt_kommission');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $lookupDataset->SetOrderBy('lt_kategorie', GetOrderTypeAsSQL(otAscending));
            $editColumn = new LookUpEditColumn(
                'Id Lobbytyp', 
                'id_lobbytyp', 
                $editor, 
                $this->dataset, 'id_lobbytyp', 'lt_kategorie', $lookupDataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for id_lobbygroup field
            //
            $editor = new ComboBox('id_lobbygroup_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                new MyConnectionFactory(),
                GetConnectionOptions(),
                '`lobbygruppen`');
            $field = new IntegerField('id_lobbygroup', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('lg_bezeichnung');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('lg_description');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('id_lobbytyp');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $lookupDataset->SetOrderBy('lg_bezeichnung', GetOrderTypeAsSQL(otAscending));
            $editColumn = new LookUpEditColumn(
                'Id Lobbygroup', 
                'id_lobbygroup', 
                $editor, 
                $this->dataset, 'id_lobbygroup', 'lg_bezeichnung', $lookupDataset);
            $editColumn->SetAllowSetToNull(true);
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
            // Edit column for parlam_verbindung field
            //
            $editor = new CheckBoxGroup('parlam_verbindung_edit');
            $editor->SetDisplayMode(CheckBoxGroup::StackedMode);
            $editor->AddValue('einzel', $this->RenderText('einzel'));
            $editor->AddValue('mehrere', $this->RenderText('mehrere'));
            $editor->AddValue('mitglied', $this->RenderText('mitglied'));
            $editor->AddValue('exekutiv', $this->RenderText('exekutiv'));
            $editor->AddValue('kommission', $this->RenderText('kommission'));
            $editColumn = new CustomEditColumn('Parlam Verbindung', 'parlam_verbindung', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
        }
    
        protected function AddInsertColumns(Grid $grid)
        {
            //
            // Edit column for lobbyname field
            //
            $editor = new TextAreaEdit('lobbyname_edit', 50, 8);
            $editColumn = new CustomEditColumn('Lobbyname', 'lobbyname', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for lobbydescription field
            //
            $editor = new TextAreaEdit('lobbydescription_edit', 50, 8);
            $editColumn = new CustomEditColumn('Lobbydescription', 'lobbydescription', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for lobbyorgtyp field
            //
            $editor = new CheckBoxGroup('lobbyorgtyp_edit');
            $editor->SetDisplayMode(CheckBoxGroup::StackedMode);
            $editor->AddValue('EinzelOrganisation', $this->RenderText('EinzelOrganisation'));
            $editor->AddValue('DachOrganisation', $this->RenderText('DachOrganisation'));
            $editor->AddValue('MitgliedsOrganisation', $this->RenderText('MitgliedsOrganisation'));
            $editor->AddValue('LeistungsErbringer', $this->RenderText('LeistungsErbringer'));
            $editor->AddValue('dezidierteLobby', $this->RenderText('dezidierteLobby'));
            $editColumn = new CustomEditColumn('Lobbyorgtyp', 'lobbyorgtyp', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for weblink field
            //
            $editor = new TextAreaEdit('weblink_edit', 50, 8);
            $editColumn = new CustomEditColumn('Weblink', 'weblink', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for id_lobbytyp field
            //
            $editor = new ComboBox('id_lobbytyp_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                new MyConnectionFactory(),
                GetConnectionOptions(),
                '`lobbytypen`');
            $field = new IntegerField('id_lobbytyp', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('lt_kategorie');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('lt_description');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('factsheet');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('lt_kommission');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $lookupDataset->SetOrderBy('lt_kategorie', GetOrderTypeAsSQL(otAscending));
            $editColumn = new LookUpEditColumn(
                'Id Lobbytyp', 
                'id_lobbytyp', 
                $editor, 
                $this->dataset, 'id_lobbytyp', 'lt_kategorie', $lookupDataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for id_lobbygroup field
            //
            $editor = new ComboBox('id_lobbygroup_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                new MyConnectionFactory(),
                GetConnectionOptions(),
                '`lobbygruppen`');
            $field = new IntegerField('id_lobbygroup', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('lg_bezeichnung');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('lg_description');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('id_lobbytyp');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $lookupDataset->SetOrderBy('lg_bezeichnung', GetOrderTypeAsSQL(otAscending));
            $editColumn = new LookUpEditColumn(
                'Id Lobbygroup', 
                'id_lobbygroup', 
                $editor, 
                $this->dataset, 'id_lobbygroup', 'lg_bezeichnung', $lookupDataset);
            $editColumn->SetAllowSetToNull(true);
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
            // Edit column for parlam_verbindung field
            //
            $editor = new CheckBoxGroup('parlam_verbindung_edit');
            $editor->SetDisplayMode(CheckBoxGroup::StackedMode);
            $editor->AddValue('einzel', $this->RenderText('einzel'));
            $editor->AddValue('mehrere', $this->RenderText('mehrere'));
            $editor->AddValue('mitglied', $this->RenderText('mitglied'));
            $editor->AddValue('exekutiv', $this->RenderText('exekutiv'));
            $editor->AddValue('kommission', $this->RenderText('kommission'));
            $editColumn = new CustomEditColumn('Parlam Verbindung', 'parlam_verbindung', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
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
            // View column for id_lobbyorg field
            //
            $column = new TextViewColumn('id_lobbyorg', 'Id Lobbyorg', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for lobbyname field
            //
            $column = new TextViewColumn('lobbyname', 'Lobbyname', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for lobbydescription field
            //
            $column = new TextViewColumn('lobbydescription', 'Lobbydescription', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for lobbyorgtyp field
            //
            $column = new TextViewColumn('lobbyorgtyp', 'Lobbyorgtyp', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for weblink field
            //
            $column = new TextViewColumn('weblink', 'Weblink', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for lt_kategorie field
            //
            $column = new TextViewColumn('id_lobbytyp_lt_kategorie', 'Id Lobbytyp', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for lg_bezeichnung field
            //
            $column = new TextViewColumn('id_lobbygroup_lg_bezeichnung', 'Id Lobbygroup', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for vernehmlassung field
            //
            $column = new TextViewColumn('vernehmlassung', 'Vernehmlassung', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for parlam_verbindung field
            //
            $column = new TextViewColumn('parlam_verbindung', 'Parlam Verbindung', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
        }
    
        protected function AddExportColumns(Grid $grid)
        {
            //
            // View column for id_lobbyorg field
            //
            $column = new TextViewColumn('id_lobbyorg', 'Id Lobbyorg', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for lobbyname field
            //
            $column = new TextViewColumn('lobbyname', 'Lobbyname', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for lobbydescription field
            //
            $column = new TextViewColumn('lobbydescription', 'Lobbydescription', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for lobbyorgtyp field
            //
            $column = new TextViewColumn('lobbyorgtyp', 'Lobbyorgtyp', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for weblink field
            //
            $column = new TextViewColumn('weblink', 'Weblink', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for lt_kategorie field
            //
            $column = new TextViewColumn('id_lobbytyp_lt_kategorie', 'Id Lobbytyp', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for lg_bezeichnung field
            //
            $column = new TextViewColumn('id_lobbygroup_lg_bezeichnung', 'Id Lobbygroup', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for vernehmlassung field
            //
            $column = new TextViewColumn('vernehmlassung', 'Vernehmlassung', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for parlam_verbindung field
            //
            $column = new TextViewColumn('parlam_verbindung', 'Parlam Verbindung', $this->dataset);
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
        public function GetModalGridEditingHandler() { return 'lobbyorganisationen_inline_edit'; }
        protected function GetEnableModalGridEditing() { return true; }
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
        
        public function GetModalGridDeleteHandler() { return 'lobbyorganisationen_modal_delete'; }
        protected function GetEnableModalGridDelete() { return true; }
        
        public function GetModalGridCopyHandler() { return 'lobbyorganisationen_inline_edit'; }
        protected function GetEnableModalGridCopy() { return true; }
    
        protected function CreateGrid()
        {
            $result = new Grid($this, $this->dataset, 'lobbyorganisationenGrid');
            if ($this->GetSecurityInfo()->HasDeleteGrant())
               $result->SetAllowDeleteSelected(true);
            else
               $result->SetAllowDeleteSelected(true);   
            
            ApplyCommonPageSettings($this, $result);
            
            $result->SetUseImagesForActions(false);
            $result->SetUseFixedHeader(false);
            
            $result->SetShowLineNumbers(false);
            $result->SetUseModalInserting(true);
            
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
            // View column for lobbyname field
            //
            $column = new TextViewColumn('lobbyname', 'Lobbyname', $this->dataset);
            $column->SetOrderable(true);
            
            /* <inline edit column> */
            //
            // Edit column for lobbyname field
            //
            $editor = new TextAreaEdit('lobbyname_edit', 50, 8);
            $editColumn = new CustomEditColumn('Lobbyname', 'lobbyname', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetEditOperationColumn($editColumn);
            /* </inline edit column> */
            
            /* <inline insert column> */
            //
            // Edit column for lobbyname field
            //
            $editor = new TextAreaEdit('lobbyname_edit', 50, 8);
            $editColumn = new CustomEditColumn('Lobbyname', 'lobbyname', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetInsertOperationColumn($editColumn);
            /* </inline insert column> */
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'lobbyname_handler', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for lobbydescription field
            //
            $column = new TextViewColumn('lobbydescription', 'Lobbydescription', $this->dataset);
            $column->SetOrderable(true);
            
            /* <inline edit column> */
            //
            // Edit column for lobbydescription field
            //
            $editor = new TextAreaEdit('lobbydescription_edit', 50, 8);
            $editColumn = new CustomEditColumn('Lobbydescription', 'lobbydescription', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetEditOperationColumn($editColumn);
            /* </inline edit column> */
            
            /* <inline insert column> */
            //
            // Edit column for lobbydescription field
            //
            $editor = new TextAreaEdit('lobbydescription_edit', 50, 8);
            $editColumn = new CustomEditColumn('Lobbydescription', 'lobbydescription', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetInsertOperationColumn($editColumn);
            /* </inline insert column> */
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'lobbydescription_handler', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for weblink field
            //
            $column = new TextViewColumn('weblink', 'Weblink', $this->dataset);
            $column->SetOrderable(true);
            
            /* <inline edit column> */
            //
            // Edit column for weblink field
            //
            $editor = new TextAreaEdit('weblink_edit', 50, 8);
            $editColumn = new CustomEditColumn('Weblink', 'weblink', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetEditOperationColumn($editColumn);
            /* </inline edit column> */
            
            /* <inline insert column> */
            //
            // Edit column for weblink field
            //
            $editor = new TextAreaEdit('weblink_edit', 50, 8);
            $editColumn = new CustomEditColumn('Weblink', 'weblink', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetInsertOperationColumn($editColumn);
            /* </inline insert column> */
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'weblink_handler', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for lobbyname field
            //
            $column = new TextViewColumn('lobbyname', 'Lobbyname', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'lobbyname_handler', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for lobbydescription field
            //
            $column = new TextViewColumn('lobbydescription', 'Lobbydescription', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'lobbydescription_handler', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for weblink field
            //
            $column = new TextViewColumn('weblink', 'Weblink', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'weblink_handler', $column);
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

    SetUpUserAuthorization(GetApplication());

    try
    {
        $Page = new lobbyorganisationenPage("lobbyorganisationen.php", "lobbyorganisationen", GetCurrentUserGrantForDataSource("lobbyorganisationen"), 'UTF-8');
        $Page->SetShortCaption('Lobbyorganisationen');
        $Page->SetHeader(GetPagesHeader());
        $Page->SetFooter(GetPagesFooter());
        $Page->SetCaption('Lobbyorganisationen');
        $Page->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource("lobbyorganisationen"));
        GetApplication()->SetEnableLessRunTimeCompile(GetEnableLessFilesRunTimeCompilation());
        GetApplication()->SetCanUserChangeOwnPassword(
            !function_exists('CanUserChangeOwnPassword') || CanUserChangeOwnPassword());
        GetApplication()->SetMainPage($Page);
        GetApplication()->Run();
    }
    catch(Exception $e)
    {
        ShowErrorPage($e->getMessage());
    }
	


