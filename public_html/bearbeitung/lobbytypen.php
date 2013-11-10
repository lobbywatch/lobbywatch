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
    
    
    
    class lobbytypenPage extends Page
    {
        protected function DoBeforeCreate()
        {
            $this->dataset = new TableDataset(
                new MyConnectionFactory(),
                GetConnectionOptions(),
                '`lobbytypen`');
            $field = new IntegerField('id_lobbytyp', null, null, true);
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, true);
            $field = new StringField('lt_kategorie');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('lt_description');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('factsheet');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('lt_kommission');
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
            $grid->SearchControl = new SimpleSearch('lobbytypenssearch', $this->dataset,
                array('id_lobbytyp', 'lt_kategorie', 'lt_description', 'factsheet', 'lt_kommission'),
                array($this->RenderText('Id Lobbytyp'), $this->RenderText('Lt Kategorie'), $this->RenderText('Lt Description'), $this->RenderText('Factsheet'), $this->RenderText('Lt Kommission')),
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
            $this->AdvancedSearchControl = new AdvancedSearchControl('lobbytypenasearch', $this->dataset, $this->GetLocalizerCaptions(), $this->GetColumnVariableContainer(), $this->CreateLinkBuilder());
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('id_lobbytyp', $this->RenderText('Id Lobbytyp')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('lt_kategorie', $this->RenderText('Lt Kategorie')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('lt_description', $this->RenderText('Lt Description')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('factsheet', $this->RenderText('Factsheet')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('lt_kommission', $this->RenderText('Lt Kommission')));
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
            // View column for id_lobbytyp field
            //
            $column = new TextViewColumn('id_lobbytyp', 'Id Lobbytyp', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for lt_kategorie field
            //
            $column = new TextViewColumn('lt_kategorie', 'Lt Kategorie', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('lt_kategorie_handler');
            
            /* <inline edit column> */
            //
            // Edit column for lt_kategorie field
            //
            $editor = new TextAreaEdit('lt_kategorie_edit', 50, 8);
            $editColumn = new CustomEditColumn('Lt Kategorie', 'lt_kategorie', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetEditOperationColumn($editColumn);
            /* </inline edit column> */
            
            /* <inline insert column> */
            //
            // Edit column for lt_kategorie field
            //
            $editor = new TextAreaEdit('lt_kategorie_edit', 50, 8);
            $editColumn = new CustomEditColumn('Lt Kategorie', 'lt_kategorie', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetInsertOperationColumn($editColumn);
            /* </inline insert column> */
            $column->SetDescription($this->RenderText('Energielobby, Gesundheitslobby etc'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for lt_description field
            //
            $column = new TextViewColumn('lt_description', 'Lt Description', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('lt_description_handler');
            
            /* <inline edit column> */
            //
            // Edit column for lt_description field
            //
            $editor = new TextAreaEdit('lt_description_edit', 50, 8);
            $editColumn = new CustomEditColumn('Lt Description', 'lt_description', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetEditOperationColumn($editColumn);
            /* </inline edit column> */
            
            /* <inline insert column> */
            //
            // Edit column for lt_description field
            //
            $editor = new TextAreaEdit('lt_description_edit', 50, 8);
            $editColumn = new CustomEditColumn('Lt Description', 'lt_description', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetInsertOperationColumn($editColumn);
            /* </inline insert column> */
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for factsheet field
            //
            $column = new TextViewColumn('factsheet', 'Factsheet', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('factsheet_handler');
            
            /* <inline edit column> */
            //
            // Edit column for factsheet field
            //
            $editor = new TextAreaEdit('factsheet_edit', 50, 8);
            $editColumn = new CustomEditColumn('Factsheet', 'factsheet', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetEditOperationColumn($editColumn);
            /* </inline edit column> */
            
            /* <inline insert column> */
            //
            // Edit column for factsheet field
            //
            $editor = new TextAreaEdit('factsheet_edit', 50, 8);
            $editColumn = new CustomEditColumn('Factsheet', 'factsheet', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetInsertOperationColumn($editColumn);
            /* </inline insert column> */
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for lt_kommission field
            //
            $column = new TextViewColumn('lt_kommission', 'Lt Kommission', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('lt_kommission_handler');
            
            /* <inline edit column> */
            //
            // Edit column for lt_kommission field
            //
            $editor = new TextAreaEdit('lt_kommission_edit', 50, 8);
            $editColumn = new CustomEditColumn('Lt Kommission', 'lt_kommission', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetEditOperationColumn($editColumn);
            /* </inline edit column> */
            
            /* <inline insert column> */
            //
            // Edit column for lt_kommission field
            //
            $editor = new TextAreaEdit('lt_kommission_edit', 50, 8);
            $editColumn = new CustomEditColumn('Lt Kommission', 'lt_kommission', $editor, $this->dataset);
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
            // View column for id_lobbytyp field
            //
            $column = new TextViewColumn('id_lobbytyp', 'Id Lobbytyp', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for lt_kategorie field
            //
            $column = new TextViewColumn('lt_kategorie', 'Lt Kategorie', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('lt_kategorie_handler');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for lt_description field
            //
            $column = new TextViewColumn('lt_description', 'Lt Description', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('lt_description_handler');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for factsheet field
            //
            $column = new TextViewColumn('factsheet', 'Factsheet', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('factsheet_handler');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for lt_kommission field
            //
            $column = new TextViewColumn('lt_kommission', 'Lt Kommission', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('lt_kommission_handler');
            $grid->AddSingleRecordViewColumn($column);
        }
    
        protected function AddEditColumns(Grid $grid)
        {
            //
            // Edit column for lt_kategorie field
            //
            $editor = new TextAreaEdit('lt_kategorie_edit', 50, 8);
            $editColumn = new CustomEditColumn('Lt Kategorie', 'lt_kategorie', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for lt_description field
            //
            $editor = new TextAreaEdit('lt_description_edit', 50, 8);
            $editColumn = new CustomEditColumn('Lt Description', 'lt_description', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for factsheet field
            //
            $editor = new TextAreaEdit('factsheet_edit', 50, 8);
            $editColumn = new CustomEditColumn('Factsheet', 'factsheet', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for lt_kommission field
            //
            $editor = new TextAreaEdit('lt_kommission_edit', 50, 8);
            $editColumn = new CustomEditColumn('Lt Kommission', 'lt_kommission', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
        }
    
        protected function AddInsertColumns(Grid $grid)
        {
            //
            // Edit column for lt_kategorie field
            //
            $editor = new TextAreaEdit('lt_kategorie_edit', 50, 8);
            $editColumn = new CustomEditColumn('Lt Kategorie', 'lt_kategorie', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for lt_description field
            //
            $editor = new TextAreaEdit('lt_description_edit', 50, 8);
            $editColumn = new CustomEditColumn('Lt Description', 'lt_description', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for factsheet field
            //
            $editor = new TextAreaEdit('factsheet_edit', 50, 8);
            $editColumn = new CustomEditColumn('Factsheet', 'factsheet', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for lt_kommission field
            //
            $editor = new TextAreaEdit('lt_kommission_edit', 50, 8);
            $editColumn = new CustomEditColumn('Lt Kommission', 'lt_kommission', $editor, $this->dataset);
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
            // View column for id_lobbytyp field
            //
            $column = new TextViewColumn('id_lobbytyp', 'Id Lobbytyp', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for lt_kategorie field
            //
            $column = new TextViewColumn('lt_kategorie', 'Lt Kategorie', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for lt_description field
            //
            $column = new TextViewColumn('lt_description', 'Lt Description', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for factsheet field
            //
            $column = new TextViewColumn('factsheet', 'Factsheet', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for lt_kommission field
            //
            $column = new TextViewColumn('lt_kommission', 'Lt Kommission', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
        }
    
        protected function AddExportColumns(Grid $grid)
        {
            //
            // View column for id_lobbytyp field
            //
            $column = new TextViewColumn('id_lobbytyp', 'Id Lobbytyp', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for lt_kategorie field
            //
            $column = new TextViewColumn('lt_kategorie', 'Lt Kategorie', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for lt_description field
            //
            $column = new TextViewColumn('lt_description', 'Lt Description', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for factsheet field
            //
            $column = new TextViewColumn('factsheet', 'Factsheet', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for lt_kommission field
            //
            $column = new TextViewColumn('lt_kommission', 'Lt Kommission', $this->dataset);
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
        public function GetModalGridEditingHandler() { return 'lobbytypen_inline_edit'; }
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
        
        public function GetModalGridDeleteHandler() { return 'lobbytypen_modal_delete'; }
        protected function GetEnableModalGridDelete() { return true; }
        
        public function GetModalGridCopyHandler() { return 'lobbytypen_inline_edit'; }
        protected function GetEnableModalGridCopy() { return true; }
    
        protected function CreateGrid()
        {
            $result = new Grid($this, $this->dataset, 'lobbytypenGrid');
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
            // View column for lt_kategorie field
            //
            $column = new TextViewColumn('lt_kategorie', 'Lt Kategorie', $this->dataset);
            $column->SetOrderable(true);
            
            /* <inline edit column> */
            //
            // Edit column for lt_kategorie field
            //
            $editor = new TextAreaEdit('lt_kategorie_edit', 50, 8);
            $editColumn = new CustomEditColumn('Lt Kategorie', 'lt_kategorie', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetEditOperationColumn($editColumn);
            /* </inline edit column> */
            
            /* <inline insert column> */
            //
            // Edit column for lt_kategorie field
            //
            $editor = new TextAreaEdit('lt_kategorie_edit', 50, 8);
            $editColumn = new CustomEditColumn('Lt Kategorie', 'lt_kategorie', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetInsertOperationColumn($editColumn);
            /* </inline insert column> */
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'lt_kategorie_handler', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for lt_description field
            //
            $column = new TextViewColumn('lt_description', 'Lt Description', $this->dataset);
            $column->SetOrderable(true);
            
            /* <inline edit column> */
            //
            // Edit column for lt_description field
            //
            $editor = new TextAreaEdit('lt_description_edit', 50, 8);
            $editColumn = new CustomEditColumn('Lt Description', 'lt_description', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetEditOperationColumn($editColumn);
            /* </inline edit column> */
            
            /* <inline insert column> */
            //
            // Edit column for lt_description field
            //
            $editor = new TextAreaEdit('lt_description_edit', 50, 8);
            $editColumn = new CustomEditColumn('Lt Description', 'lt_description', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetInsertOperationColumn($editColumn);
            /* </inline insert column> */
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'lt_description_handler', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for factsheet field
            //
            $column = new TextViewColumn('factsheet', 'Factsheet', $this->dataset);
            $column->SetOrderable(true);
            
            /* <inline edit column> */
            //
            // Edit column for factsheet field
            //
            $editor = new TextAreaEdit('factsheet_edit', 50, 8);
            $editColumn = new CustomEditColumn('Factsheet', 'factsheet', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetEditOperationColumn($editColumn);
            /* </inline edit column> */
            
            /* <inline insert column> */
            //
            // Edit column for factsheet field
            //
            $editor = new TextAreaEdit('factsheet_edit', 50, 8);
            $editColumn = new CustomEditColumn('Factsheet', 'factsheet', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetInsertOperationColumn($editColumn);
            /* </inline insert column> */
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'factsheet_handler', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for lt_kommission field
            //
            $column = new TextViewColumn('lt_kommission', 'Lt Kommission', $this->dataset);
            $column->SetOrderable(true);
            
            /* <inline edit column> */
            //
            // Edit column for lt_kommission field
            //
            $editor = new TextAreaEdit('lt_kommission_edit', 50, 8);
            $editColumn = new CustomEditColumn('Lt Kommission', 'lt_kommission', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetEditOperationColumn($editColumn);
            /* </inline edit column> */
            
            /* <inline insert column> */
            //
            // Edit column for lt_kommission field
            //
            $editor = new TextAreaEdit('lt_kommission_edit', 50, 8);
            $editColumn = new CustomEditColumn('Lt Kommission', 'lt_kommission', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetInsertOperationColumn($editColumn);
            /* </inline insert column> */
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'lt_kommission_handler', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for lt_kategorie field
            //
            $column = new TextViewColumn('lt_kategorie', 'Lt Kategorie', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'lt_kategorie_handler', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for lt_description field
            //
            $column = new TextViewColumn('lt_description', 'Lt Description', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'lt_description_handler', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for factsheet field
            //
            $column = new TextViewColumn('factsheet', 'Factsheet', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'factsheet_handler', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for lt_kommission field
            //
            $column = new TextViewColumn('lt_kommission', 'Lt Kommission', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'lt_kommission_handler', $column);
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
        $Page = new lobbytypenPage("lobbytypen.php", "lobbytypen", GetCurrentUserGrantForDataSource("lobbytypen"), 'UTF-8');
        $Page->SetShortCaption('Lobbytypen');
        $Page->SetHeader(GetPagesHeader());
        $Page->SetFooter(GetPagesFooter());
        $Page->SetCaption('Lobbytypen');
        $Page->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource("lobbytypen"));
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
	


