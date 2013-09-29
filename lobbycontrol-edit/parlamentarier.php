<?php
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
    
    
    
    class parlamentarierPage extends Page
    {
        protected function DoBeforeCreate()
        {
            $this->dataset = new TableDataset(
                new MyConnectionFactory(),
                GetConnectionOptions(),
                '`parlamentarier`');
            $field = new IntegerField('id_parlam', null, null, true);
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, true);
            $field = new StringField('name');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('vorname');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('beruf');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('ratstyp');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('kanton');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('partei');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('parteifunktion');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('im_rat_seit');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('kommission');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('kleinbild');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new IntegerField('sitzplatz');
            $field->SetIsNotNull(true);
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
            $grid->SearchControl = new SimpleSearch('parlamentarierssearch', $this->dataset,
                array('id_parlam', 'name', 'vorname', 'beruf', 'ratstyp', 'kanton', 'partei', 'parteifunktion', 'im_rat_seit', 'kommission', 'kleinbild', 'sitzplatz'),
                array($this->RenderText('Id Parlam'), $this->RenderText('Name'), $this->RenderText('Vorname'), $this->RenderText('Beruf'), $this->RenderText('Ratstyp'), $this->RenderText('Kanton'), $this->RenderText('Partei'), $this->RenderText('Parteifunktion'), $this->RenderText('Im Rat Seit'), $this->RenderText('Kommission'), $this->RenderText('Kleinbild'), $this->RenderText('Sitzplatz')),
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
            $this->AdvancedSearchControl = new AdvancedSearchControl('parlamentarierasearch', $this->dataset, $this->GetLocalizerCaptions(), $this->GetColumnVariableContainer(), $this->CreateLinkBuilder());
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('id_parlam', $this->RenderText('Id Parlam')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('name', $this->RenderText('Name')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('vorname', $this->RenderText('Vorname')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('beruf', $this->RenderText('Beruf')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('ratstyp', $this->RenderText('Ratstyp')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('kanton', $this->RenderText('Kanton')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('partei', $this->RenderText('Partei')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('parteifunktion', $this->RenderText('Parteifunktion')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('im_rat_seit', $this->RenderText('Im Rat Seit')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('kommission', $this->RenderText('Kommission')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('kleinbild', $this->RenderText('Kleinbild')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('sitzplatz', $this->RenderText('Sitzplatz')));
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
            // View column for id_parlam field
            //
            $column = new TextViewColumn('id_parlam', 'Id Parlam', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('name', 'Name', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('name_handler');
            
            /* <inline edit column> */
            //
            // Edit column for name field
            //
            $editor = new TextEdit('name_edit');
            $editor->SetSize(50);
            $editor->SetMaxLength(150);
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
            $editor->SetSize(50);
            $editor->SetMaxLength(150);
            $editColumn = new CustomEditColumn('Name', 'name', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetInsertOperationColumn($editColumn);
            /* </inline insert column> */
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for vorname field
            //
            $column = new TextViewColumn('vorname', 'Vorname', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('vorname_handler');
            
            /* <inline edit column> */
            //
            // Edit column for vorname field
            //
            $editor = new TextEdit('vorname_edit');
            $editor->SetSize(50);
            $editor->SetMaxLength(150);
            $editColumn = new CustomEditColumn('Vorname', 'vorname', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetEditOperationColumn($editColumn);
            /* </inline edit column> */
            
            /* <inline insert column> */
            //
            // Edit column for vorname field
            //
            $editor = new TextEdit('vorname_edit');
            $editor->SetSize(50);
            $editor->SetMaxLength(150);
            $editColumn = new CustomEditColumn('Vorname', 'vorname', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetInsertOperationColumn($editColumn);
            /* </inline insert column> */
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for beruf field
            //
            $column = new TextViewColumn('beruf', 'Beruf', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('beruf_handler');
            
            /* <inline edit column> */
            //
            // Edit column for beruf field
            //
            $editor = new TextEdit('beruf_edit');
            $editor->SetSize(70);
            $editor->SetMaxLength(255);
            $editColumn = new CustomEditColumn('Beruf', 'beruf', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetEditOperationColumn($editColumn);
            /* </inline edit column> */
            
            /* <inline insert column> */
            //
            // Edit column for beruf field
            //
            $editor = new TextEdit('beruf_edit');
            $editor->SetSize(70);
            $editor->SetMaxLength(255);
            $editColumn = new CustomEditColumn('Beruf', 'beruf', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetInsertOperationColumn($editColumn);
            /* </inline insert column> */
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for ratstyp field
            //
            $column = new TextViewColumn('ratstyp', 'Ratstyp', $this->dataset);
            $column->SetOrderable(true);
            
            /* <inline edit column> */
            //
            // Edit column for ratstyp field
            //
            $editor = new ComboBox('ratstyp_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $editor->AddValue('NR', $this->RenderText('NR'));
            $editor->AddValue('SR', $this->RenderText('SR'));
            $editColumn = new CustomEditColumn('Ratstyp', 'ratstyp', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetEditOperationColumn($editColumn);
            /* </inline edit column> */
            
            /* <inline insert column> */
            //
            // Edit column for ratstyp field
            //
            $editor = new ComboBox('ratstyp_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $editor->AddValue('NR', $this->RenderText('NR'));
            $editor->AddValue('SR', $this->RenderText('SR'));
            $editColumn = new CustomEditColumn('Ratstyp', 'ratstyp', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetInsertOperationColumn($editColumn);
            /* </inline insert column> */
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for kanton field
            //
            $column = new TextViewColumn('kanton', 'Kanton', $this->dataset);
            $column->SetOrderable(true);
            
            /* <inline edit column> */
            //
            // Edit column for kanton field
            //
            $editor = new TextEdit('kanton_edit');
            $editor->SetSize(2);
            $editor->SetMaxLength(2);
            $editColumn = new CustomEditColumn('Kanton', 'kanton', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetEditOperationColumn($editColumn);
            /* </inline edit column> */
            
            /* <inline insert column> */
            //
            // Edit column for kanton field
            //
            $editor = new TextEdit('kanton_edit');
            $editor->SetSize(2);
            $editor->SetMaxLength(2);
            $editColumn = new CustomEditColumn('Kanton', 'kanton', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetInsertOperationColumn($editColumn);
            /* </inline insert column> */
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for partei field
            //
            $column = new TextViewColumn('partei', 'Partei', $this->dataset);
            $column->SetOrderable(true);
            
            /* <inline edit column> */
            //
            // Edit column for partei field
            //
            $editor = new TextEdit('partei_edit');
            $editor->SetSize(20);
            $editor->SetMaxLength(20);
            $editColumn = new CustomEditColumn('Partei', 'partei', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetEditOperationColumn($editColumn);
            /* </inline edit column> */
            
            /* <inline insert column> */
            //
            // Edit column for partei field
            //
            $editor = new TextEdit('partei_edit');
            $editor->SetSize(20);
            $editor->SetMaxLength(20);
            $editColumn = new CustomEditColumn('Partei', 'partei', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetInsertOperationColumn($editColumn);
            /* </inline insert column> */
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for parteifunktion field
            //
            $column = new TextViewColumn('parteifunktion', 'Parteifunktion', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('parteifunktion_handler');
            
            /* <inline edit column> */
            //
            // Edit column for parteifunktion field
            //
            $editor = new TextEdit('parteifunktion_edit');
            $editor->SetSize(70);
            $editor->SetMaxLength(255);
            $editColumn = new CustomEditColumn('Parteifunktion', 'parteifunktion', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetEditOperationColumn($editColumn);
            /* </inline edit column> */
            
            /* <inline insert column> */
            //
            // Edit column for parteifunktion field
            //
            $editor = new TextEdit('parteifunktion_edit');
            $editor->SetSize(70);
            $editor->SetMaxLength(255);
            $editColumn = new CustomEditColumn('Parteifunktion', 'parteifunktion', $editor, $this->dataset);
            $editColumn->SetAllowSetToDefault(true);
            $editColumn->SetInsertDefaultValue($this->RenderText('Mitglied'));
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetInsertOperationColumn($editColumn);
            /* </inline insert column> */
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for im_rat_seit field
            //
            $column = new TextViewColumn('im_rat_seit', 'Im Rat Seit', $this->dataset);
            $column->SetOrderable(true);
            
            /* <inline edit column> */
            //
            // Edit column for im_rat_seit field
            //
            $editor = new TextEdit('im_rat_seit_edit');
            $editor->SetSize(4);
            $editor->SetMaxLength(4);
            $editColumn = new CustomEditColumn('Im Rat Seit', 'im_rat_seit', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetEditOperationColumn($editColumn);
            /* </inline edit column> */
            
            /* <inline insert column> */
            //
            // Edit column for im_rat_seit field
            //
            $editor = new TextEdit('im_rat_seit_edit');
            $editor->SetSize(4);
            $editor->SetMaxLength(4);
            $editColumn = new CustomEditColumn('Im Rat Seit', 'im_rat_seit', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetInsertOperationColumn($editColumn);
            /* </inline insert column> */
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for kommission field
            //
            $column = new TextViewColumn('kommission', 'Kommission', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('kommission_handler');
            
            /* <inline edit column> */
            //
            // Edit column for kommission field
            //
            $editor = new TextEdit('kommission_edit');
            $editor->SetSize(70);
            $editor->SetMaxLength(255);
            $editColumn = new CustomEditColumn('Kommission', 'kommission', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetEditOperationColumn($editColumn);
            /* </inline edit column> */
            
            /* <inline insert column> */
            //
            // Edit column for kommission field
            //
            $editor = new TextEdit('kommission_edit');
            $editor->SetSize(70);
            $editor->SetMaxLength(255);
            $editColumn = new CustomEditColumn('Kommission', 'kommission', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetInsertOperationColumn($editColumn);
            /* </inline insert column> */
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for kleinbild field
            //
            $column = new TextViewColumn('kleinbild', 'Kleinbild', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('kleinbild_handler');
            
            /* <inline edit column> */
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
            $column->SetEditOperationColumn($editColumn);
            /* </inline edit column> */
            
            /* <inline insert column> */
            //
            // Edit column for kleinbild field
            //
            $editor = new TextEdit('kleinbild_edit');
            $editor->SetSize(80);
            $editor->SetMaxLength(80);
            $editColumn = new CustomEditColumn('Kleinbild', 'kleinbild', $editor, $this->dataset);
            $editColumn->SetInsertDefaultValue($this->RenderText('leer.png'));
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetInsertOperationColumn($editColumn);
            /* </inline insert column> */
            $column->SetDescription($this->RenderText('Dateiname Bild (44x62 px), muss auf Server vorhanden sein oder leer.png'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for sitzplatz field
            //
            $column = new TextViewColumn('sitzplatz', 'Sitzplatz', $this->dataset);
            $column->SetOrderable(true);
            
            /* <inline edit column> */
            //
            // Edit column for sitzplatz field
            //
            $editor = new TextEdit('sitzplatz_edit');
            $editColumn = new CustomEditColumn('Sitzplatz', 'sitzplatz', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetEditOperationColumn($editColumn);
            /* </inline edit column> */
            
            /* <inline insert column> */
            //
            // Edit column for sitzplatz field
            //
            $editor = new TextEdit('sitzplatz_edit');
            $editColumn = new CustomEditColumn('Sitzplatz', 'sitzplatz', $editor, $this->dataset);
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
            // View column for id_parlam field
            //
            $column = new TextViewColumn('id_parlam', 'Id Parlam', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('name', 'Name', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('name_handler');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for vorname field
            //
            $column = new TextViewColumn('vorname', 'Vorname', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('vorname_handler');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for beruf field
            //
            $column = new TextViewColumn('beruf', 'Beruf', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('beruf_handler');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for ratstyp field
            //
            $column = new TextViewColumn('ratstyp', 'Ratstyp', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for kanton field
            //
            $column = new TextViewColumn('kanton', 'Kanton', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for partei field
            //
            $column = new TextViewColumn('partei', 'Partei', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for parteifunktion field
            //
            $column = new TextViewColumn('parteifunktion', 'Parteifunktion', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('parteifunktion_handler');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for im_rat_seit field
            //
            $column = new TextViewColumn('im_rat_seit', 'Im Rat Seit', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for kommission field
            //
            $column = new TextViewColumn('kommission', 'Kommission', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('kommission_handler');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for kleinbild field
            //
            $column = new TextViewColumn('kleinbild', 'Kleinbild', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('kleinbild_handler');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for sitzplatz field
            //
            $column = new TextViewColumn('sitzplatz', 'Sitzplatz', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
        }
    
        protected function AddEditColumns(Grid $grid)
        {
            //
            // Edit column for name field
            //
            $editor = new TextEdit('name_edit');
            $editor->SetSize(50);
            $editor->SetMaxLength(150);
            $editColumn = new CustomEditColumn('Name', 'name', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for vorname field
            //
            $editor = new TextEdit('vorname_edit');
            $editor->SetSize(50);
            $editor->SetMaxLength(150);
            $editColumn = new CustomEditColumn('Vorname', 'vorname', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for beruf field
            //
            $editor = new TextEdit('beruf_edit');
            $editor->SetSize(70);
            $editor->SetMaxLength(255);
            $editColumn = new CustomEditColumn('Beruf', 'beruf', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for ratstyp field
            //
            $editor = new ComboBox('ratstyp_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $editor->AddValue('NR', $this->RenderText('NR'));
            $editor->AddValue('SR', $this->RenderText('SR'));
            $editColumn = new CustomEditColumn('Ratstyp', 'ratstyp', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for kanton field
            //
            $editor = new TextEdit('kanton_edit');
            $editor->SetSize(2);
            $editor->SetMaxLength(2);
            $editColumn = new CustomEditColumn('Kanton', 'kanton', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for partei field
            //
            $editor = new TextEdit('partei_edit');
            $editor->SetSize(20);
            $editor->SetMaxLength(20);
            $editColumn = new CustomEditColumn('Partei', 'partei', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for parteifunktion field
            //
            $editor = new TextEdit('parteifunktion_edit');
            $editor->SetSize(70);
            $editor->SetMaxLength(255);
            $editColumn = new CustomEditColumn('Parteifunktion', 'parteifunktion', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for im_rat_seit field
            //
            $editor = new TextEdit('im_rat_seit_edit');
            $editor->SetSize(4);
            $editor->SetMaxLength(4);
            $editColumn = new CustomEditColumn('Im Rat Seit', 'im_rat_seit', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for kommission field
            //
            $editor = new TextEdit('kommission_edit');
            $editor->SetSize(70);
            $editor->SetMaxLength(255);
            $editColumn = new CustomEditColumn('Kommission', 'kommission', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
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
        }
    
        protected function AddInsertColumns(Grid $grid)
        {
            //
            // Edit column for name field
            //
            $editor = new TextEdit('name_edit');
            $editor->SetSize(50);
            $editor->SetMaxLength(150);
            $editColumn = new CustomEditColumn('Name', 'name', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for vorname field
            //
            $editor = new TextEdit('vorname_edit');
            $editor->SetSize(50);
            $editor->SetMaxLength(150);
            $editColumn = new CustomEditColumn('Vorname', 'vorname', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for beruf field
            //
            $editor = new TextEdit('beruf_edit');
            $editor->SetSize(70);
            $editor->SetMaxLength(255);
            $editColumn = new CustomEditColumn('Beruf', 'beruf', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for ratstyp field
            //
            $editor = new ComboBox('ratstyp_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $editor->AddValue('NR', $this->RenderText('NR'));
            $editor->AddValue('SR', $this->RenderText('SR'));
            $editColumn = new CustomEditColumn('Ratstyp', 'ratstyp', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for kanton field
            //
            $editor = new TextEdit('kanton_edit');
            $editor->SetSize(2);
            $editor->SetMaxLength(2);
            $editColumn = new CustomEditColumn('Kanton', 'kanton', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for partei field
            //
            $editor = new TextEdit('partei_edit');
            $editor->SetSize(20);
            $editor->SetMaxLength(20);
            $editColumn = new CustomEditColumn('Partei', 'partei', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for parteifunktion field
            //
            $editor = new TextEdit('parteifunktion_edit');
            $editor->SetSize(70);
            $editor->SetMaxLength(255);
            $editColumn = new CustomEditColumn('Parteifunktion', 'parteifunktion', $editor, $this->dataset);
            $editColumn->SetAllowSetToDefault(true);
            $editColumn->SetInsertDefaultValue($this->RenderText('Mitglied'));
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for im_rat_seit field
            //
            $editor = new TextEdit('im_rat_seit_edit');
            $editor->SetSize(4);
            $editor->SetMaxLength(4);
            $editColumn = new CustomEditColumn('Im Rat Seit', 'im_rat_seit', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for kommission field
            //
            $editor = new TextEdit('kommission_edit');
            $editor->SetSize(70);
            $editor->SetMaxLength(255);
            $editColumn = new CustomEditColumn('Kommission', 'kommission', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for kleinbild field
            //
            $editor = new TextEdit('kleinbild_edit');
            $editor->SetSize(80);
            $editor->SetMaxLength(80);
            $editColumn = new CustomEditColumn('Kleinbild', 'kleinbild', $editor, $this->dataset);
            $editColumn->SetInsertDefaultValue($this->RenderText('leer.png'));
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
            // View column for id_parlam field
            //
            $column = new TextViewColumn('id_parlam', 'Id Parlam', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('name', 'Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for vorname field
            //
            $column = new TextViewColumn('vorname', 'Vorname', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for beruf field
            //
            $column = new TextViewColumn('beruf', 'Beruf', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for ratstyp field
            //
            $column = new TextViewColumn('ratstyp', 'Ratstyp', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for kanton field
            //
            $column = new TextViewColumn('kanton', 'Kanton', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for partei field
            //
            $column = new TextViewColumn('partei', 'Partei', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for parteifunktion field
            //
            $column = new TextViewColumn('parteifunktion', 'Parteifunktion', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for im_rat_seit field
            //
            $column = new TextViewColumn('im_rat_seit', 'Im Rat Seit', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for kommission field
            //
            $column = new TextViewColumn('kommission', 'Kommission', $this->dataset);
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
        }
    
        protected function AddExportColumns(Grid $grid)
        {
            //
            // View column for id_parlam field
            //
            $column = new TextViewColumn('id_parlam', 'Id Parlam', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('name', 'Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for vorname field
            //
            $column = new TextViewColumn('vorname', 'Vorname', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for beruf field
            //
            $column = new TextViewColumn('beruf', 'Beruf', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for ratstyp field
            //
            $column = new TextViewColumn('ratstyp', 'Ratstyp', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for kanton field
            //
            $column = new TextViewColumn('kanton', 'Kanton', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for partei field
            //
            $column = new TextViewColumn('partei', 'Partei', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for parteifunktion field
            //
            $column = new TextViewColumn('parteifunktion', 'Parteifunktion', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for im_rat_seit field
            //
            $column = new TextViewColumn('im_rat_seit', 'Im Rat Seit', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for kommission field
            //
            $column = new TextViewColumn('kommission', 'Kommission', $this->dataset);
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
        public function GetModalGridEditingHandler() { return 'parlamentarier_inline_edit'; }
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
        
        public function GetModalGridDeleteHandler() { return 'parlamentarier_modal_delete'; }
        protected function GetEnableModalGridDelete() { return true; }
        
        public function GetModalGridCopyHandler() { return 'parlamentarier_inline_edit'; }
        protected function GetEnableModalGridCopy() { return true; }
    
        protected function CreateGrid()
        {
            $result = new Grid($this, $this->dataset, 'parlamentarierGrid');
            if ($this->GetSecurityInfo()->HasDeleteGrant())
               $result->SetAllowDeleteSelected(true);
            else
               $result->SetAllowDeleteSelected(false);   
            
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
            $this->SetExportToExcelAvailable(false);
            $this->SetExportToWordAvailable(false);
            $this->SetExportToXmlAvailable(false);
            $this->SetExportToCsvAvailable(false);
            $this->SetExportToPdfAvailable(false);
            $this->SetPrinterFriendlyAvailable(false);
            $this->SetSimpleSearchAvailable(true);
            $this->SetAdvancedSearchAvailable(false);
            $this->SetFilterRowAvailable(false);
            $this->SetVisualEffectsEnabled(false);
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
            
            /* <inline edit column> */
            //
            // Edit column for name field
            //
            $editor = new TextEdit('name_edit');
            $editor->SetSize(50);
            $editor->SetMaxLength(150);
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
            $editor->SetSize(50);
            $editor->SetMaxLength(150);
            $editColumn = new CustomEditColumn('Name', 'name', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetInsertOperationColumn($editColumn);
            /* </inline insert column> */
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'name_handler', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for vorname field
            //
            $column = new TextViewColumn('vorname', 'Vorname', $this->dataset);
            $column->SetOrderable(true);
            
            /* <inline edit column> */
            //
            // Edit column for vorname field
            //
            $editor = new TextEdit('vorname_edit');
            $editor->SetSize(50);
            $editor->SetMaxLength(150);
            $editColumn = new CustomEditColumn('Vorname', 'vorname', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetEditOperationColumn($editColumn);
            /* </inline edit column> */
            
            /* <inline insert column> */
            //
            // Edit column for vorname field
            //
            $editor = new TextEdit('vorname_edit');
            $editor->SetSize(50);
            $editor->SetMaxLength(150);
            $editColumn = new CustomEditColumn('Vorname', 'vorname', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetInsertOperationColumn($editColumn);
            /* </inline insert column> */
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'vorname_handler', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for beruf field
            //
            $column = new TextViewColumn('beruf', 'Beruf', $this->dataset);
            $column->SetOrderable(true);
            
            /* <inline edit column> */
            //
            // Edit column for beruf field
            //
            $editor = new TextEdit('beruf_edit');
            $editor->SetSize(70);
            $editor->SetMaxLength(255);
            $editColumn = new CustomEditColumn('Beruf', 'beruf', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetEditOperationColumn($editColumn);
            /* </inline edit column> */
            
            /* <inline insert column> */
            //
            // Edit column for beruf field
            //
            $editor = new TextEdit('beruf_edit');
            $editor->SetSize(70);
            $editor->SetMaxLength(255);
            $editColumn = new CustomEditColumn('Beruf', 'beruf', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetInsertOperationColumn($editColumn);
            /* </inline insert column> */
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'beruf_handler', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for parteifunktion field
            //
            $column = new TextViewColumn('parteifunktion', 'Parteifunktion', $this->dataset);
            $column->SetOrderable(true);
            
            /* <inline edit column> */
            //
            // Edit column for parteifunktion field
            //
            $editor = new TextEdit('parteifunktion_edit');
            $editor->SetSize(70);
            $editor->SetMaxLength(255);
            $editColumn = new CustomEditColumn('Parteifunktion', 'parteifunktion', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetEditOperationColumn($editColumn);
            /* </inline edit column> */
            
            /* <inline insert column> */
            //
            // Edit column for parteifunktion field
            //
            $editor = new TextEdit('parteifunktion_edit');
            $editor->SetSize(70);
            $editor->SetMaxLength(255);
            $editColumn = new CustomEditColumn('Parteifunktion', 'parteifunktion', $editor, $this->dataset);
            $editColumn->SetAllowSetToDefault(true);
            $editColumn->SetInsertDefaultValue($this->RenderText('Mitglied'));
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetInsertOperationColumn($editColumn);
            /* </inline insert column> */
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'parteifunktion_handler', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for kommission field
            //
            $column = new TextViewColumn('kommission', 'Kommission', $this->dataset);
            $column->SetOrderable(true);
            
            /* <inline edit column> */
            //
            // Edit column for kommission field
            //
            $editor = new TextEdit('kommission_edit');
            $editor->SetSize(70);
            $editor->SetMaxLength(255);
            $editColumn = new CustomEditColumn('Kommission', 'kommission', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetEditOperationColumn($editColumn);
            /* </inline edit column> */
            
            /* <inline insert column> */
            //
            // Edit column for kommission field
            //
            $editor = new TextEdit('kommission_edit');
            $editor->SetSize(70);
            $editor->SetMaxLength(255);
            $editColumn = new CustomEditColumn('Kommission', 'kommission', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetInsertOperationColumn($editColumn);
            /* </inline insert column> */
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'kommission_handler', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for kleinbild field
            //
            $column = new TextViewColumn('kleinbild', 'Kleinbild', $this->dataset);
            $column->SetOrderable(true);
            
            /* <inline edit column> */
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
            $column->SetEditOperationColumn($editColumn);
            /* </inline edit column> */
            
            /* <inline insert column> */
            //
            // Edit column for kleinbild field
            //
            $editor = new TextEdit('kleinbild_edit');
            $editor->SetSize(80);
            $editor->SetMaxLength(80);
            $editColumn = new CustomEditColumn('Kleinbild', 'kleinbild', $editor, $this->dataset);
            $editColumn->SetInsertDefaultValue($this->RenderText('leer.png'));
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetInsertOperationColumn($editColumn);
            /* </inline insert column> */
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'kleinbild_handler', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for name field
            //
            $column = new TextViewColumn('name', 'Name', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'name_handler', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for vorname field
            //
            $column = new TextViewColumn('vorname', 'Vorname', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'vorname_handler', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for beruf field
            //
            $column = new TextViewColumn('beruf', 'Beruf', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'beruf_handler', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for parteifunktion field
            //
            $column = new TextViewColumn('parteifunktion', 'Parteifunktion', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'parteifunktion_handler', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for kommission field
            //
            $column = new TextViewColumn('kommission', 'Kommission', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'kommission_handler', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for kleinbild field
            //
            $column = new TextViewColumn('kleinbild', 'Kleinbild', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'kleinbild_handler', $column);
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
        $Page = new parlamentarierPage("parlamentarier.php", "parlamentarier", GetCurrentUserGrantForDataSource("parlamentarier"), 'UTF-8');
        $Page->SetShortCaption('Parlamentarier');
        $Page->SetHeader(GetPagesHeader());
        $Page->SetFooter(GetPagesFooter());
        $Page->SetCaption('Parlamentarier');
        $Page->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource("parlamentarier"));
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
	
