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
    
    
    
    class v_parlamentarier_authorisierungs_emailPage extends Page
    {
        protected function DoBeforeCreate()
        {
            $this->dataset = new TableDataset(
                new MyPDOConnectionFactory(),
                GetConnectionOptions(),
                '`v_parlamentarier_authorisierungs_email`');
            $field = new IntegerField('id');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, true);
            $field = new StringField('parlamentarier_name');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('email');
            $this->dataset->AddField($field, false);
            $field = new StringField('email_text_html');
            $this->dataset->AddField($field, false);
            $field = new StringField('email_text_for_url');
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
            if (GetCurrentUserGrantForDataSource('organisation')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('<span class="entity important-entity">Organisation</span>'), 'organisation.php', $this->RenderText('Organisation'), $currentPageCaption == $this->RenderText('<span class="entity important-entity">Organisation</span>')));
            if (GetCurrentUserGrantForDataSource('parlamentarier')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('<span class="entity important-entity">Parlamentarier</span>'), 'parlamentarier.php', $this->RenderText('Parlamentarier'), $currentPageCaption == $this->RenderText('<span class="entity important-entity">Parlamentarier</span>')));
            if (GetCurrentUserGrantForDataSource('zutrittsberechtigung')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('<span class="entity">Zutrittsberechtigter</span>'), 'zutrittsberechtigung.php', $this->RenderText('Zutrittsberechtigter'), $currentPageCaption == $this->RenderText('<span class="entity">Zutrittsberechtigter</span>')));
            if (GetCurrentUserGrantForDataSource('interessenbindung')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('<span class="relation">Interessenbindung</span>'), 'interessenbindung.php', $this->RenderText('Interessenbindung'), $currentPageCaption == $this->RenderText('<span class="relation">Interessenbindung</span>')));
            if (GetCurrentUserGrantForDataSource('mandat')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('<span class="relation">Mandat</span>'), 'mandat.php', $this->RenderText('Mandat'), $currentPageCaption == $this->RenderText('<span class="relation">Mandat</span>')));
            if (GetCurrentUserGrantForDataSource('in_kommission')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('<span class="relation">In Kommission</span>'), 'in_kommission.php', $this->RenderText('In Kommission'), $currentPageCaption == $this->RenderText('<span class="relation">In Kommission</span>')));
            if (GetCurrentUserGrantForDataSource('organisation_beziehung')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('<span class="relation">Organisation Beziehung</span>'), 'organisation_beziehung.php', $this->RenderText('Organisation Beziehung'), $currentPageCaption == $this->RenderText('<span class="relation">Organisation Beziehung</span>')));
            if (GetCurrentUserGrantForDataSource('branche')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('<span class="entity">Branche</span>'), 'branche.php', $this->RenderText('Branche'), $currentPageCaption == $this->RenderText('<span class="entity">Branche</span>')));
            if (GetCurrentUserGrantForDataSource('interessengruppe')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('<span class="entity">Lobbygruppe</span>'), 'interessengruppe.php', $this->RenderText('Lobbygruppe'), $currentPageCaption == $this->RenderText('<span class="entity">Lobbygruppe</span>')));
            if (GetCurrentUserGrantForDataSource('kommission')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('<span class="entity">Kommission</span>'), 'kommission.php', $this->RenderText('Kommission'), $currentPageCaption == $this->RenderText('<span class="entity">Kommission</span>')));
            if (GetCurrentUserGrantForDataSource('partei')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('<span class="entity">Partei</span>'), 'partei.php', $this->RenderText('Partei'), $currentPageCaption == $this->RenderText('<span class="entity">Partei</span>')));
            if (GetCurrentUserGrantForDataSource('fraktion')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('<span class="entity">Fraktion</span>'), 'fraktion.php', $this->RenderText('Fraktion'), $currentPageCaption == $this->RenderText('<span class="entity">Fraktion</span>')));
            if (GetCurrentUserGrantForDataSource('kanton')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('<span class="entity">Kanton</span>'), 'kanton.php', $this->RenderText('Kanton'), $currentPageCaption == $this->RenderText('<span class="entity">Kanton</span>')));
            if (GetCurrentUserGrantForDataSource('settings')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('<span class="settings">Settings</span>'), 'settings.php', $this->RenderText('Settings'), $currentPageCaption == $this->RenderText('<span class="settings">Settings</span>')));
            if (GetCurrentUserGrantForDataSource('settings_category')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('<span class="settings">Settings Category</span>'), 'settings_category.php', $this->RenderText('Settings Category'), $currentPageCaption == $this->RenderText('<span class="settings">Settings Category</span>')));
            if (GetCurrentUserGrantForDataSource('user')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('<span class="settings">User</span>'), 'user.php', $this->RenderText('User'), $currentPageCaption == $this->RenderText('<span class="settings">User</span>')));
            if (GetCurrentUserGrantForDataSource('q_unvollstaendige_parlamentarier')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('<span class="view">Unvollständige Parlamentarier</span>'), 'q_unvollstaendige_parlamentarier.php', $this->RenderText('Unvollständige Parlamentarier'), $currentPageCaption == $this->RenderText('<span class="view">Unvollständige Parlamentarier</span>')));
            if (GetCurrentUserGrantForDataSource('q_unvollstaendige_zutrittsberechtigte')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('<span class="view">Unvollständige Zutrittsberechtigte</span>'), 'q_unvollstaendige_zutrittsberechtigte.php', $this->RenderText('Unvollständige Zutrittsberechtigte'), $currentPageCaption == $this->RenderText('<span class="view">Unvollständige Zutrittsberechtigte</span>')));
            if (GetCurrentUserGrantForDataSource('q_unvollstaendige_organisationen')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('<span class="view">Unvollständige Organisationen</span>'), 'q_unvollstaendige_organisationen.php', $this->RenderText('Unvollständige Organisationen'), $currentPageCaption == $this->RenderText('<span class="view">Unvollständige Organisationen</span>')));
            if (GetCurrentUserGrantForDataSource('q_last_updated_tables')->HasViewGrant())
                $result->AddPage(new PageLink($this->RenderText('<span class="view">Tabellenstand</span>'), 'tabellenstand.php', $this->RenderText('Tabellenstand'), $currentPageCaption == $this->RenderText('<span class="view">Tabellenstand</span>')));
            
            if ( HasAdminPage() && GetApplication()->HasAdminGrantForCurrentUser() )
              $result->AddPage(new PageLink($this->GetLocalizerCaptions()->GetMessageString('AdminPage'), 'phpgen_admin.php', $this->GetLocalizerCaptions()->GetMessageString('AdminPage'), false, true));

            add_more_navigation_links($result); // Afterburned
            return $result;
        }
    
        protected function CreateRssGenerator() {
            return setupRSS($this, $this->dataset);
        }
    
        protected function CreateGridSearchControl(Grid $grid)
        {
            $grid->UseFilter = true;
            $grid->SearchControl = new SimpleSearch('v_parlamentarier_authorisierungs_emailssearch', $this->dataset,
                array('id', 'parlamentarier_name', 'email', 'email_text_html'),
                array($this->RenderText('Id'), $this->RenderText('Parlamentarier Name'), $this->RenderText('Email'), $this->RenderText('Email Text Html')),
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
            $this->AdvancedSearchControl = new AdvancedSearchControl('v_parlamentarier_authorisierungs_emailasearch', $this->dataset, $this->GetLocalizerCaptions(), $this->GetColumnVariableContainer(), $this->CreateLinkBuilder());
            $this->AdvancedSearchControl->setTimerInterval(1000);
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('id', $this->RenderText('Id')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('parlamentarier_name', $this->RenderText('Parlamentarier Name')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('email', $this->RenderText('Email')));
            $this->AdvancedSearchControl->AddSearchColumn($this->AdvancedSearchControl->CreateStringSearchInput('email_text_html', $this->RenderText('Email Text Html')));
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
        }
    
        protected function AddFieldColumns(Grid $grid)
        {
            //
            // View column for id field
            //
            $column = new TextViewColumn('id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            
            /* <inline edit column> */
            //
            // Edit column for id field
            //
            $editor = new TextEdit('id_edit');
            $editColumn = new CustomEditColumn('Id', 'id', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetEditOperationColumn($editColumn);
            /* </inline edit column> */
            
            /* <inline insert column> */
            //
            // Edit column for id field
            //
            $editor = new TextEdit('id_edit');
            $editColumn = new CustomEditColumn('Id', 'id', $editor, $this->dataset);
            $editColumn->SetAllowSetToDefault(false);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetInsertOperationColumn($editColumn);
            /* </inline insert column> */
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for parlamentarier_name field
            //
            $column = new TextViewColumn('parlamentarier_name', 'Parlamentarier Name', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('v_parlamentarier_authorisierungs_emailGrid_parlamentarier_name_handler_list');
            
            /* <inline edit column> */
            //
            // Edit column for parlamentarier_name field
            //
            $editor = new TextAreaEdit('parlamentarier_name_edit', 50, 8);
            $editColumn = new CustomEditColumn('Parlamentarier Name', 'parlamentarier_name', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetEditOperationColumn($editColumn);
            /* </inline edit column> */
            
            /* <inline insert column> */
            //
            // Edit column for parlamentarier_name field
            //
            $editor = new TextAreaEdit('parlamentarier_name_edit', 50, 8);
            $editColumn = new CustomEditColumn('Parlamentarier Name', 'parlamentarier_name', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetInsertOperationColumn($editColumn);
            /* </inline insert column> */
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'parlamentarier.php?operation=edit&pk0=%id%' , '_blank');
            $column = new DivTagViewColumnDecorator($column);
            $column->Bold = true;
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for email field
            //
            $column = new TextViewColumn('email', 'Email', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('v_parlamentarier_authorisierungs_emailGrid_email_handler_list');
            
            /* <inline edit column> */
            //
            // Edit column for email field
            //
            $editor = new TextEdit('email_edit');
            $editor->SetSize(100);
            $editor->SetMaxLength(100);
            $editColumn = new CustomEditColumn('Email', 'email', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetEditOperationColumn($editColumn);
            /* </inline edit column> */
            
            /* <inline insert column> */
            //
            // Edit column for email field
            //
            $editor = new TextEdit('email_edit');
            $editor->SetSize(100);
            $editor->SetMaxLength(100);
            $editColumn = new CustomEditColumn('Email', 'email', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetInsertOperationColumn($editColumn);
            /* </inline insert column> */
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'mailto:%email%?subject=Interessenbinungen&body=%email_text_for_url%&bcc=info@lobbywatch.ch' , '_blank');
            $column->SetDescription($this->RenderText('E-Mail-Adresse. Ein Klick auf den Text öffnet das E-Mail-Programm mit einem unformtierten E-Mail. Ein formatiertes E-Mail kann vom Feld "Email Text HTML" kopiert werden. Bei diesem Feld auf "mehr" klicken.'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for email_text_html field
            //
            $column = new TextViewColumn('email_text_html', 'Email Text Html', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('v_parlamentarier_authorisierungs_emailGrid_email_text_html_handler_list');
            
            /* <inline edit column> */
            //
            // Edit column for email_text_html field
            //
            $editor = new TextAreaEdit('email_text_html_edit', 50, 8);
            $editColumn = new CustomEditColumn('Email Text Html', 'email_text_html', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetEditOperationColumn($editColumn);
            /* </inline edit column> */
            
            /* <inline insert column> */
            //
            // Edit column for email_text_html field
            //
            $editor = new TextAreaEdit('email_text_html_edit', 50, 8);
            $editColumn = new CustomEditColumn('Email Text Html', 'email_text_html', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetInsertOperationColumn($editColumn);
            /* </inline insert column> */
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'mailto:%email%?subject=Interessenbinungen&body=%email_text_for_url%&bcc=info@lobbywatch.ch' , '_blank');
            $column->SetDescription($this->RenderText('Formatiertes E-Mail zum Kopieren. Klick auf "mehr" zeigt das formatierte E-Mail. Ein Klick auf den Text öffnet das E-Mail-Programm mit einem unformtierten E-Mail.'));
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
            // View column for parlamentarier_name field
            //
            $column = new TextViewColumn('parlamentarier_name', 'Parlamentarier Name', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('v_parlamentarier_authorisierungs_emailGrid_parlamentarier_name_handler_view');
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'parlamentarier.php?operation=edit&pk0=%id%' , '_blank');
            $column = new DivTagViewColumnDecorator($column);
            $column->Bold = true;
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for email field
            //
            $column = new TextViewColumn('email', 'Email', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('v_parlamentarier_authorisierungs_emailGrid_email_handler_view');
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'mailto:%email%?subject=Interessenbinungen&body=%email_text_for_url%&bcc=info@lobbywatch.ch' , '_blank');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for email_text_html field
            //
            $column = new TextViewColumn('email_text_html', 'Email Text Html', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('v_parlamentarier_authorisierungs_emailGrid_email_text_html_handler_view');
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'mailto:%email%?subject=Interessenbinungen&body=%email_text_for_url%&bcc=info@lobbywatch.ch' , '_blank');
            $grid->AddSingleRecordViewColumn($column);
        }
    
        protected function AddEditColumns(Grid $grid)
        {
            //
            // Edit column for id field
            //
            $editor = new TextEdit('id_edit');
            $editColumn = new CustomEditColumn('Id', 'id', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for parlamentarier_name field
            //
            $editor = new TextAreaEdit('parlamentarier_name_edit', 50, 8);
            $editColumn = new CustomEditColumn('Parlamentarier Name', 'parlamentarier_name', $editor, $this->dataset);
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
            // Edit column for email_text_html field
            //
            $editor = new TextAreaEdit('email_text_html_edit', 50, 8);
            $editColumn = new CustomEditColumn('Email Text Html', 'email_text_html', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
        }
    
        protected function AddInsertColumns(Grid $grid)
        {
            //
            // Edit column for id field
            //
            $editor = new TextEdit('id_edit');
            $editColumn = new CustomEditColumn('Id', 'id', $editor, $this->dataset);
            $editColumn->SetAllowSetToDefault(false);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for parlamentarier_name field
            //
            $editor = new TextAreaEdit('parlamentarier_name_edit', 50, 8);
            $editColumn = new CustomEditColumn('Parlamentarier Name', 'parlamentarier_name', $editor, $this->dataset);
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
            // Edit column for email_text_html field
            //
            $editor = new TextAreaEdit('email_text_html_edit', 50, 8);
            $editColumn = new CustomEditColumn('Email Text Html', 'email_text_html', $editor, $this->dataset);
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
            // View column for parlamentarier_name field
            //
            $column = new TextViewColumn('parlamentarier_name', 'Parlamentarier Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for email field
            //
            $column = new TextViewColumn('email', 'Email', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for email_text_html field
            //
            $column = new TextViewColumn('email_text_html', 'Email Text Html', $this->dataset);
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
            // View column for parlamentarier_name field
            //
            $column = new TextViewColumn('parlamentarier_name', 'Parlamentarier Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for email field
            //
            $column = new TextViewColumn('email', 'Email', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for email_text_html field
            //
            $column = new TextViewColumn('email_text_html', 'Email Text Html', $this->dataset);
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
        public function v_parlamentarier_authorisierungs_emailGrid_OnGetCustomTemplate($part, $mode, &$result, &$params)
        {
        if ($part == PagePart::VerticalGrid && $mode == PageMode::Edit) {
          $result = 'edit/grid.tpl';
        } else if ($part == PagePart::VerticalGrid && $mode == PageMode::Insert) {
          $result = 'insert/grid.tpl';
        } else if ($part == PagePart::RecordCard && $mode == PageMode::View) {
          $result = 'view/grid.tpl';
        } else if ($part == PagePart::Grid && $mode == PageMode::ViewAll) {
          $result = 'list/grid.tpl';
        } else if ($part == PagePart::PageList) {
          $result = 'page_list.tpl';
        }
        }
    
        protected function CreateGrid()
        {
            $result = new Grid($this, $this->dataset, 'v_parlamentarier_authorisierungs_emailGrid');
            if ($this->GetSecurityInfo()->HasDeleteGrant())
               $result->SetAllowDeleteSelected(false);
            else
               $result->SetAllowDeleteSelected(false);   
            
            ApplyCommonPageSettings($this, $result);
            
            $result->SetUseImagesForActions(true);
            $result->SetDefaultOrdering('parlamentarier_name', otAscending);
            
            $result->SetUseFixedHeader(false);
            
            $result->SetShowLineNumbers(false);
            
            $result->SetHighlightRowAtHover(false);
            $result->SetWidth('');
            $this->OnGetCustomTemplate->AddListener('v_parlamentarier_authorisierungs_emailGrid' . '_OnGetCustomTemplate', $this);
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
            // View column for parlamentarier_name field
            //
            $column = new TextViewColumn('parlamentarier_name', 'Parlamentarier Name', $this->dataset);
            $column->SetOrderable(true);
            
            /* <inline edit column> */
            //
            // Edit column for parlamentarier_name field
            //
            $editor = new TextAreaEdit('parlamentarier_name_edit', 50, 8);
            $editColumn = new CustomEditColumn('Parlamentarier Name', 'parlamentarier_name', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetEditOperationColumn($editColumn);
            /* </inline edit column> */
            
            /* <inline insert column> */
            //
            // Edit column for parlamentarier_name field
            //
            $editor = new TextAreaEdit('parlamentarier_name_edit', 50, 8);
            $editColumn = new CustomEditColumn('Parlamentarier Name', 'parlamentarier_name', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetInsertOperationColumn($editColumn);
            /* </inline insert column> */
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'parlamentarier.php?operation=edit&pk0=%id%' , '_blank');
            $column = new DivTagViewColumnDecorator($column);
            $column->Bold = true;
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'v_parlamentarier_authorisierungs_emailGrid_parlamentarier_name_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for email field
            //
            $column = new TextViewColumn('email', 'Email', $this->dataset);
            $column->SetOrderable(true);
            
            /* <inline edit column> */
            //
            // Edit column for email field
            //
            $editor = new TextEdit('email_edit');
            $editor->SetSize(100);
            $editor->SetMaxLength(100);
            $editColumn = new CustomEditColumn('Email', 'email', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetEditOperationColumn($editColumn);
            /* </inline edit column> */
            
            /* <inline insert column> */
            //
            // Edit column for email field
            //
            $editor = new TextEdit('email_edit');
            $editor->SetSize(100);
            $editor->SetMaxLength(100);
            $editColumn = new CustomEditColumn('Email', 'email', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetInsertOperationColumn($editColumn);
            /* </inline insert column> */
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'mailto:%email%?subject=Interessenbinungen&body=%email_text_for_url%&bcc=info@lobbywatch.ch' , '_blank');
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'v_parlamentarier_authorisierungs_emailGrid_email_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for email_text_html field
            //
            $column = new TextViewColumn('email_text_html', 'Email Text Html', $this->dataset);
            $column->SetOrderable(true);
            
            /* <inline edit column> */
            //
            // Edit column for email_text_html field
            //
            $editor = new TextAreaEdit('email_text_html_edit', 50, 8);
            $editColumn = new CustomEditColumn('Email Text Html', 'email_text_html', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetEditOperationColumn($editColumn);
            /* </inline edit column> */
            
            /* <inline insert column> */
            //
            // Edit column for email_text_html field
            //
            $editor = new TextAreaEdit('email_text_html_edit', 50, 8);
            $editColumn = new CustomEditColumn('Email Text Html', 'email_text_html', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $column->SetInsertOperationColumn($editColumn);
            /* </inline insert column> */
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'mailto:%email%?subject=Interessenbinungen&body=%email_text_for_url%&bcc=info@lobbywatch.ch' , '_blank');
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'v_parlamentarier_authorisierungs_emailGrid_email_text_html_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);//
            // View column for parlamentarier_name field
            //
            $column = new TextViewColumn('parlamentarier_name', 'Parlamentarier Name', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'parlamentarier.php?operation=edit&pk0=%id%' , '_blank');
            $column = new DivTagViewColumnDecorator($column);
            $column->Bold = true;
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'v_parlamentarier_authorisierungs_emailGrid_parlamentarier_name_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for email field
            //
            $column = new TextViewColumn('email', 'Email', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'mailto:%email%?subject=Interessenbinungen&body=%email_text_for_url%&bcc=info@lobbywatch.ch' , '_blank');
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'v_parlamentarier_authorisierungs_emailGrid_email_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            //
            // View column for email_text_html field
            //
            $column = new TextViewColumn('email_text_html', 'Email Text Html', $this->dataset);
            $column->SetOrderable(true);
            $column = new ExtendedHyperLinkColumnDecorator($column, $this->dataset, 'mailto:%email%?subject=Interessenbinungen&body=%email_text_for_url%&bcc=info@lobbywatch.ch' , '_blank');
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'v_parlamentarier_authorisierungs_emailGrid_email_text_html_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            return $result;
        }
        
        public function OpenAdvancedSearchByDefault()
        {
            return false;
        }
    
        protected function DoGetGridHeader()
        {
            return '<div class="wiki-table-help">
    <p>E-Mail-Vorlagen für Autorisierungs-E-Mails an Parlamentarier.
    </p>
    
    <p>Zur Autorisierung, siehe <a class="wiki" href="/wiki/tiki-index.php?page=Autorisierung" rel="_blank ">Wiki Autorisierung</a>.
    </p>
    
    <p>Via E-Mail-Link können im E-Mail-Programm nur unformatierte E-Mail geladen werden. Formatierte E-Mails können von der Spalte "Email Text HTML" kopiert werden. Dort auf "mehr" klicken und den Text kopieren.
    </p>
    </div>';
        }
    }

    SetUpUserAuthorization(GetApplication());

    try
    {
        $Page = new v_parlamentarier_authorisierungs_emailPage("v_parlamentarier_authorisierungs_email.php", "v_parlamentarier_authorisierungs_email", GetCurrentUserGrantForDataSource("v_parlamentarier_authorisierungs_email"), 'UTF-8');
        $Page->SetShortCaption('<span class="view">Parlamentarier Email</span>');
        $Page->SetHeader(GetPagesHeader());
        $Page->SetFooter(GetPagesFooter());
        $Page->SetCaption('Parlamentarier Email');
        $Page->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource("v_parlamentarier_authorisierungs_email"));
        GetApplication()->SetEnableLessRunTimeCompile(GetEnableLessFilesRunTimeCompilation());
        GetApplication()->SetCanUserChangeOwnPassword(
            !function_exists('CanUserChangeOwnPassword') || CanUserChangeOwnPassword());
        GetApplication()->SetMainPage($Page);
        before_render($Page);
        GetApplication()->Run();
    }
    catch(Exception $e)
    {
        ShowErrorPage($e->getMessage());
    }
