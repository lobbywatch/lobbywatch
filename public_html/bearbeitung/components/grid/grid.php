<?php

// require_once 'components/renderers/renderer.php';
// require_once 'components/component.php';
// require_once 'components/editors/editors.php';
// require_once 'components/editors/multilevel_selection.php';
// require_once 'components/filter_builder.php';
// require_once 'components/utils/array_utils.php';

include_once dirname(__FILE__) . '/' . '../renderers/renderer.php';
include_once dirname(__FILE__) . '/' . '../component.php';
include_once dirname(__FILE__) . '/' . '../editors/editors.php';
include_once dirname(__FILE__) . '/' . '../editors/multilevel_selection.php';
include_once dirname(__FILE__) . '/' . '../filter_builder.php';
include_once dirname(__FILE__) . '/' . '../utils/array_utils.php';

include_once dirname(__FILE__) . '/' . 'quick_filter.php';
include_once dirname(__FILE__) . '/' . 'grid_state.php';
include_once dirname(__FILE__) . '/' . 'commit_values_grid_state.php';
include_once dirname(__FILE__) . '/' . 'commit_edited_values_grid_state.php';

define('otAscending', 1);
define('otDescending', 2);

function GetOrderTypeAsSQL($orderType) {
    return $orderType == otAscending ? 'ASC' : 'DESC';
}

$orderTypeCaptions = array(
    otAscending => 'a',
    otDescending => 'd');


class DeleteSelectedGridState extends GridState {
    protected function DoCanChangeData(&$rowValues, &$message) {
        $cancel = false;
        $this->grid->BeforeDeleteRecord->Fire(array($this->GetPage(), &$rowValues, &$cancel, &$message,
            $this->GetDataset()->GetName()));
        return !$cancel;
    }

    protected function DoAfterChangeData($rowValues) {
        $this->grid->AfterDeleteRecord->Fire(array($this->GetPage(), &$rowValues, $this->GetDataset()->GetName()));
    }

    public function ProcessMessages() {
        $primaryKeysArray = array();
        for ($i = 0; $i < GetApplication()->GetPOSTValue('recordCount'); $i++) {
            if (GetApplication()->IsPOSTValueSet('rec' . $i)) {
                // TODO : move GetPrimaryKeyFieldNames function to private
                $primaryKeys = array();
                $primaryKeyNames = $this->grid->GetDataset()->GetPrimaryKeyFieldNames();
                for ($j = 0; $j < count($primaryKeyNames); $j++)
                    $primaryKeys[] = GetApplication()->GetPOSTValue('rec' . $i . '_pk' . $j);
                $primaryKeysArray[] = $primaryKeys;
            }
        }

        $inlineInsertedRecordPrimaryKeyNames = GetApplication()->GetSuperGlobals()->GetPostVariablesIf(
            create_function('$str', 'return StringUtils::StartsWith($str, \'inline_inserted_rec_\') && !StringUtils::Contains($str, \'pk\');')
        );

        foreach ($inlineInsertedRecordPrimaryKeyNames as $name => $value) {
            $primaryKeys = array();
            $primaryKeyNames = $this->grid->GetDataset()->GetPrimaryKeyFieldNames();
            for ($i = 0; $i < count($primaryKeyNames); $i++)
                $primaryKeys[] = GetApplication()->GetSuperGlobals()->GetPostValue($name . '_pk' . $i);
            $primaryKeysArray[] = $primaryKeys;
        }

        foreach ($primaryKeysArray as $primaryKeyValues) {
            $this->grid->GetDataset()->SetSingleRecordState($primaryKeyValues);
            $this->grid->GetDataset()->Open();

            if ($this->grid->GetDataset()->Next()) {
                $message = '';

                $fieldValues = $this->grid->GetDataset()->GetCurrentFieldValues();
                if ($this->CanChangeData($fieldValues, $message)) {
                    try {
                        $this->grid->GetDataset()->Delete();
                        $this->DoAfterChangeData($fieldValues);
                    } catch (Exception $e) {
                        $this->grid->GetDataset()->SetAllRecordsState();
                        $this->ChangeState(OPERATION_VIEWALL);
                        $this->SetGridErrorMessage($e);
                        return;
                    }
                } else {
                    $this->grid->GetDataset()->SetAllRecordsState();
                    $this->ChangeState(OPERATION_VIEWALL);
                    $this->SetGridSimpleErrorMessage($message);
                    return;
                }
            }
            $this->grid->GetDataset()->Close();
        }

        $this->ApplyState(OPERATION_VIEWALL);
    }
}

class ViewAllGridState extends GridState {
    function ProcessMessages() {
        /* $primaryKeyValues = array();
        ExtractPrimaryKeyValues($primaryKeyValues, METHOD_GET);
        if (count($primaryKeyValues) > 0)
            $this->grid->GetDataset()->SetSingleRecordState($primaryKeyValues);
        */
        $orderColumn = $this->grid->GetOrderColumnFieldName();
        $orderType = $this->grid->GetOrderType();
        if (isset($orderType) && isset($orderColumn))
            $this->grid->GetDataset()->SetOrderBy($orderColumn, GetOrderTypeAsSQL($orderType));

        foreach ($this->grid->GetViewColumns() as $column)
            $column->ProcessMessages();
    }
}

class OpenInlineInsertEditorsGridState extends GridState {
    private $nameSuffix;

    function ProcessMessages() {
        $this->nameSuffix = '_inline_' . mt_rand();

        $columns = $this->grid->GetViewColumns();
        foreach ($columns as $column) {
            $inlineEditColumn = $column->GetInsertOperationColumn();
            if (isset($inlineEditColumn)) {
                $editControl = $inlineEditColumn->GetEditControl();
                $editControl->SetName($editControl->GetName() . $this->GetNameSuffix());
                $inlineEditColumn->ProcessMessages();
            }
        }
    }

    public function GetNameSuffix() {
        return $this->nameSuffix;
    }
}

class OpenInlineEditorsGridState extends GridState {
    private $nameSuffix;

    function ProcessMessages() {
        $primaryKeyValues = $this->grid->GetPrimaryKeyValuesFromGet();

        $this->nameSuffix = '_inline_' . mt_rand();

        $this->grid->GetDataset()->SetSingleRecordState($primaryKeyValues);
        $this->grid->GetDataset()->Open();

        if ($this->grid->GetDataset()->Next()) {
            $columns = $this->grid->GetViewColumns();
            foreach ($columns as $column) {
                $inlineEditColumn = $column->GetEditOperationColumn();
                if (isset($inlineEditColumn)) {
                    $editControl = $inlineEditColumn->GetEditControl();
                    $editControl->SetName($editControl->GetName() . $this->GetNameSuffix());
                }
            }
            array_walk($columns, create_function('$column', '$column->ProcessMessages();'));
        }

        //$this->grid->GetDataset()->Close();
    }

    public function GetNameSuffix() {
        return $this->nameSuffix;
    }
}

class EditGridState extends GridState {
    function ProcessMessages() {
        $primaryKeyValues = $this->grid->GetPrimaryKeyValuesFromGet();

        $this->grid->GetDataset()->SetSingleRecordState($primaryKeyValues);
        $this->grid->GetDataset()->Open();

        if ($this->grid->GetDataset()->Next()) {
            $this->CheckRLSEditGrant();
            $columns = $this->grid->GetEditColumns();
            array_walk($columns, create_function('$column', '$column->ProcessMessages();'));
        }
        $this->grid->GetDataset()->Close();
    }
}

class CopyGridState extends GridState {
    function ProcessMessages() {
        $primaryKeyValues = $this->grid->GetPrimaryKeyValuesFromGet();

        $this->grid->GetDataset()->SetSingleRecordState($primaryKeyValues);
        $this->grid->GetDataset()->Open();

        if ($this->grid->GetDataset()->Next())
            foreach ($this->grid->GetInsertColumns() as $column)
                $column->ProcessMessages();
    }
}

class InsertGridState extends GridState {
    function ProcessMessages() {
        foreach ($this->grid->GetInsertColumns() as $column)
            $column->ProcessMessages();
    }
}


class CommitNewValuesGridState extends CommitValuesGridState {
    private $isInline = false;

    public function SetIsInlineOperation($value) {
        $this->isInline = $value;
    }

    protected function DoCanChangeData(&$rowValues, &$message) {
        $cancel = false;
        $this->grid->BeforeInsertRecord->Fire(array($this->GetPage(), &$rowValues, &$cancel, &$message,
            $this->GetDataset()->GetName()));
        return !$cancel;
    }

    protected function DoAfterChangeData($rowValues) {
        $this->grid->AfterInsertRecord->Fire(array($this->GetPage(), $rowValues, $this->GetDataset()->GetName()));
    }

    function ProcessMessages() {
        $action = '';
        if (GetApplication()->GetSuperGlobals()->IsPostValueSet('submit1'))
            $action = GetApplication()->GetSuperGlobals()->GetPostValue('submit1');

        $redirect = null;
        $detailToRedirect = null;
        if (GetApplication()->GetSuperGlobals()->IsGetValueSet('details-redirect'))
            $detailToRedirect = GetApplication()->GetSuperGlobals()->GetGetValue('details-redirect');

        $this->grid->GetDataset()->Insert();

        $exceptions = array();
        foreach ($this->grid->GetInsertColumns() as $column) {
            try {
                $column->ProcessMessages();
            } catch (Exception $e) {
                $exceptions[] = $e;
            }
        }
        foreach ($this->grid->GetInsertColumns() as $column) {
            try {
                $column->AfterSetAllDatasetValues();
            } catch (Exception $e) {
                $exceptions[] = $e;
            }
        }


        $message = '';
        $oldFieldValues = $this->GetDataset()->GetCurrentFieldValues();
        $fieldValues = $this->GetDataset()->GetCurrentFieldValues();
        if ($this->CanChangeData($fieldValues, $message)) {
            if (count($exceptions) > 0) {
                $this->ChangeState(OPERATION_INSERT);
                $this->SetGridErrorMessages($exceptions);
                return;
            }
            try {
                $this->WriteChangesToDataset($oldFieldValues, $fieldValues, $this->GetDataset());

                if ($detailToRedirect) {
                    $detail = $this->grid->FindDetail($detailToRedirect);
                    $redirect = $detail->GetSeparateViewLink();
                }

                $this->GetDataset()->Post();
                $fieldValues = ArrayUtils::Merge(
                    $fieldValues,
                    $this->GetDataset()->GetInsertFieldValues()
                );
                $this->DoAfterChangeData($fieldValues);

                $newPrimaryKeyValues = $this->grid->GetDataset()->GetPrimaryKeyValues();
            } catch (Exception $e) {
                $this->ChangeState(OPERATION_INSERT);
                $columns = $this->grid->GetInsertColumns();
                array_walk($columns, create_function('$column', '$column->PrepareEditorControl();'));
                $this->SetGridErrorMessage($e);
                return;
            }
        } else {
            $this->ChangeState(OPERATION_INSERT);
            $this->SetGridSimpleErrorMessage($message);
            $columns = $this->grid->GetInsertColumns();
            array_walk($columns, create_function('$column', '$column->PrepareEditorControl();'));
            return;
        }
        $this->grid->SetGridMessage($message);

        if ($redirect) {
            header('Location: ' . $redirect);
            exit();
        }


        if (!$this->isInline) {
            if ($action == 'saveinsert') {
                $this->ApplyState(OPERATION_INSERT);
                header('Location: ' . $this->grid->GetAddRecordLink());
                exit();
            } else if ($action == 'saveedit') {
                header('Location: ' . $this->grid->GetEditCurrentRecordLink($newPrimaryKeyValues));
                exit();
            } else {
                header('Location: ' . $this->grid->GetReturnUrl());
                exit();
            }
        }

    }

    function SetInternalStateSwitch($primaryKeys) {
        $this->grid->SetInternalStateSwitch($primaryKeys);
    }
}


class CommitInlineInsertedValuesGridState extends CommitValuesGridState {
    protected function DoCanChangeData(&$rowValues, &$message) {
        $cancel = false;
        $this->grid->BeforeInsertRecord->Fire(array($this->GetPage(), &$rowValues, &$cancel, &$message,
            $this->GetDataset()->GetName()));
        return !$cancel;
    }

    protected function DoAfterChangeData($rowValues) {
        $this->grid->AfterInsertRecord->Fire(array($this->GetPage(), $rowValues, $this->GetDataset()->GetName()));
    }

    private function HandleError($message, $decode = true) {
        $this->SetGridSimpleErrorMessage($message, $decode);
        array_walk($this->GetColumns(), create_function('$column', '$column->PrepareEditorControl();'));
    }

    /**
     * @return CustomEditColumn[]
     */
    private function GetColumns() {
        $result = array();

        foreach ($this->grid->GetViewColumns() as $column) {
            $editColumn = $column->GetInsertOperationColumn();
            if (isset($editColumn))
                $result[] = $editColumn;
        }
        return $result;
    }

    public function ProcessMessages() {
        $nameSuffix = ExtractInputValue('namesuffix', METHOD_POST);
        $columns = $this->grid->GetViewColumns();
        foreach ($columns as $column) {
            $inlineEditColumn = $column->GetInsertOperationColumn();
            if (isset($inlineEditColumn)) {
                $editControl = $inlineEditColumn->GetEditControl();
                $editControl->SetName($editControl->GetName() . $nameSuffix);
            }
        }

        $this->GetDataset()->Insert();

        $exceptions = array();

        foreach ($this->GetColumns() as $column) {
            try {
                $column->ProcessMessages();
            } catch (Exception $e) {
                $exceptions[] = $e;
            }
        }

        foreach ($this->GetColumns() as $column) {
            try {
                $column->AfterSetAllDatasetValues();
            } catch (Exception $e) {
                $exceptions[] = $e;
            }
        }


        $message = '';
        $oldFieldValues = $this->GetDataset()->GetCurrentFieldValues();
        $fieldValues = $this->GetDataset()->GetCurrentFieldValues();

        if ($this->CanChangeData($fieldValues, $message)) {
            if (count($exceptions) > 0) {
                $this->HandleError($this->ExceptionsToErrorMessage($exceptions), false);
                return;
            }
            try {
                $this->WriteChangesToDataset($oldFieldValues, $fieldValues, $this->GetDataset());
                $this->GetDataset()->Post();
                $fieldValues = ArrayUtils::Merge(
                    $fieldValues,
                    $this->GetDataset()->GetInsertFieldValues()
                );
                $this->DoAfterChangeData($fieldValues);

                $primaryKeyValues = $this->GetDataset()->GetPrimaryKeyValues();
                $this->GetDataset()->SetSingleRecordState($primaryKeyValues);
                $this->GetDataset()->Open();
                $this->GetDataset()->Next();
            } catch (Exception $e) {
                $this->HandleError($this->ExceptionToErrorMessage($e), false);
                return;
            }
        } else {
            $this->HandleError($message, true);
            return;
        }
        $this->grid->SetGridMessage($message);

    }
}

class CommitInlineEditedValuesGridState extends CommitValuesGridState {
    protected function DoCanChangeData(&$rowValues, &$message) {
        $cancel = false;
        $this->grid->BeforeUpdateRecord->Fire(array($this->GetPage(), &$rowValues, &$cancel, &$message,
            $this->GetDataset()->GetName()));
        return !$cancel;
    }

    protected function DoAfterChangeData($rowValues) {
        $this->grid->AfterUpdateRecord->Fire(array($this->GetPage(), $rowValues, $this->GetDataset()->GetName()));
    }

    private function HandleError($message, $decode = true) {
        $this->SetGridSimpleErrorMessage($message, $decode);
        array_walk($this->GetColumns(), create_function('$column', '$column->PrepareEditorControl();'));
        $this->GetDataset()->Close();
    }

    /**
     * @return CustomEditColumn[]
     */
    private function GetColumns() {
        $result = array();

        foreach ($this->grid->GetViewColumns() as $column) {
            $editColumn = $column->GetEditOperationColumn();
            if (isset($editColumn))
                $result[] = $editColumn;
        }
        return $result;
    }

    public function ProcessMessages() {
        $primaryKeyValues = array();
        ExtractPrimaryKeyValues($primaryKeyValues, METHOD_POST);

        $nameSuffix = ExtractInputValue('namesuffix', METHOD_POST);
        $columns = $this->grid->GetViewColumns();
        foreach ($columns as $column) {
            $inlineEditColumn = $column->GetEditOperationColumn();
            if (isset($inlineEditColumn)) {
                $editControl = $inlineEditColumn->GetEditControl();
                $editControl->SetName($editControl->GetName() . $nameSuffix);
            }
        }

        $this->GetDataset()->SetSingleRecordState($primaryKeyValues);
        $this->GetDataset()->Open();

        if ($this->GetDataset()->Next()) {

            $this->GetDataset()->Edit();

            $exceptions = array();

            foreach ($this->GetColumns() as $column) {
                try {
                    $column->ProcessMessages();
                } catch (Exception $e) {
                    $exceptions[] = $e;
                }
            }
            foreach ($this->GetColumns() as $column) {
                try {
                    $column->AfterSetAllDatasetValues();
                } catch (Exception $e) {
                    $exceptions[] = $e;
                }
            }

            $message = '';
            $oldFieldValues = array_merge($this->GetDataset()->GetFieldValues(), $this->GetDataset()->GetCurrentFieldValues());
            $fieldValues = array_merge($this->GetDataset()->GetFieldValues(), $this->GetDataset()->GetCurrentFieldValues());

            if ($this->CanChangeData($fieldValues, $message)) {
                if (count($exceptions) > 0) {
                    $this->HandleError($this->ExceptionsToErrorMessage($exceptions), false);
                    return;
                }
                try {
                    $this->WriteChangesToDataset($oldFieldValues, $fieldValues, $this->GetDataset());

                    $primaryKeyValues = $this->GetDataset()->GetPrimaryKeyValues();
                    $this->GetDataset()->Post();
                    $this->DoAfterChangeData($fieldValues);
                    $this->GetDataset()->Close();
                    $this->GetDataset()->SetSingleRecordState($primaryKeyValues);
                } catch (Exception $e) {
                    $this->HandleError($this->ExceptionToErrorMessage($e), false);
                    return;
                }
            } else {
                $this->HandleError($message, true);
                return;
            }
            $this->grid->SetGridMessage($message);
            $this->grid->GetDataset()->Close();
            $this->GetDataset()->SetSingleRecordState($primaryKeyValues);
        }
    }
}

class DeleteGridState extends GridState {
    public function ProcessMessages() {
        $primaryKeyValues = array();
        ExtractPrimaryKeyValues($primaryKeyValues, METHOD_GET);
        $this->grid->GetDataset()->SetSingleRecordState($primaryKeyValues);
    }
}

class ViewGridState extends GridState {
    public function ProcessMessages() {
        $primaryKeyValues = array();
        ExtractPrimaryKeyValues($primaryKeyValues, METHOD_GET);
        $this->grid->GetDataset()->SetSingleRecordState($primaryKeyValues);
    }
}

class CommitDeleteGridState extends GridState {
    private $useGetToExtractPrimaryKeys = false;

    protected function DoCanChangeData(&$rowValues, &$message) {
        $cancel = false;
        $this->grid->BeforeDeleteRecord->Fire(array($this->GetPage(), &$rowValues, &$cancel, &$message,
            $this->GetDataset()->GetName()));
        return !$cancel;
    }

    protected function DoAfterChangeData($rowValues) {
        $this->grid->AfterDeleteRecord->Fire(array($this->GetPage(), &$rowValues, $this->GetDataset()->GetName()));
    }

    public function ProcessMessages() {
        $primaryKeyValues = array();
        if ($this->useGetToExtractPrimaryKeys)
            ExtractPrimaryKeyValues($primaryKeyValues, METHOD_GET);
        else
            ExtractPrimaryKeyValues($primaryKeyValues, METHOD_POST);

        $this->grid->GetDataset()->SetSingleRecordState($primaryKeyValues);
        $this->grid->GetDataset()->Open();

        if ($this->grid->GetDataset()->Next()) {

            $message = '';
            $fieldValues = $this->grid->GetDataset()->GetCurrentFieldValues();
            if ($this->CanChangeData($fieldValues, $message)) {
                try {
                    $this->grid->GetDataset()->Delete();
                    $this->DoAfterChangeData($fieldValues);
                } catch (Exception $e) {

                    $this->ChangeState(OPERATION_DELETE);
                    $this->SetGridErrorMessage($e);
                    return;
                }
            } else {
                $this->ChangeState(OPERATION_DELETE);
                $this->SetGridSimpleErrorMessage($message);
                return;
            }
            $this->grid->SetGridMessage($message);
        }
        $this->grid->GetDataset()->Close();

        $this->grid->GetDataset()->Open();
        $this->ApplyState(OPERATION_VIEWALL);
    }

    public function SetUseGetToExtractPrimaryKeys($value) {
        $this->useGetToExtractPrimaryKeys = $value;
    }
}

class GridBand {
    private $name;
    private $caption;
    private $columns;
    private $useConsolidatedHeader;

    public function  __construct($name, $caption, $useConsolidatedHeader = false) {
        $this->name = $name;
        $this->caption = $caption;
        $this->useConsolidatedHeader = $useConsolidatedHeader;
        $this->columns = array();
    }

    public function GetUseConsolidatedHeader() {
        return $this->useConsolidatedHeader;
    }

    public function GetColumnCount() {
        return count($this->columns);
    }

    public function GetName() {
        return $this->name;
    }

    public function HasColumns() {
        return $this->GetColumnCount() > 0;
    }

    public function AddColumn($column) {
        $this->columns[] = $column;
    }

    public function AddColumns($columns) {
        foreach ($columns as $column)
            $this->AddColumn($column);
    }

    /**
     * @return CustomViewColumn[]
     */
    public function GetColumns() {
        return $this->columns;
    }

    public function GetCaption() {
        return $this->caption;
    }

    public function SetCaption($value) {
        $this->caption = $value;
    }

    public function GetViewData() {
        $columnsViewData = array();
        foreach ($this->GetColumns() as $column) {
            /** CustomViewColumn $column */
            $columnsViewData[$column->GetName()] = $column->GetViewData();
        }
        return array(
            'Columns' => $columnsViewData,
            'ColumnCount' => $this->GetColumnCount(),
            'Caption' => $this->GetCaption(),
            'ConsolidateHeader' => $this->GetUseConsolidatedHeader()
        );
    }
}

class Grid {
    /** @var string */
    private $name;

    /** @var CustomEditColumn[] */
    private $editColumns;

    /** @var CustomViewColumn[] */
    private $viewColumns;

    /** @var CustomViewColumn[] */
    private $printColumns;

    /** @var CustomEditColumn[] */
    private $insertColumns;

    /** @var CustomViewColumn[] */
    private $exportColumns;

    /** @var CustomViewColumn[] */
    private $singleRecordViewColumns;

    /** @var IDataset */
    private $dataset;

    /** @var GridState */
    private $gridState;

    /** @var Page */
    private $page;

    /** @var bool */
    private $showAddButton;

    /** @var bool */
    private $showInlineAddButton;

    /** @var string */
    private $message;

    /** @var bool */
    private $allowDeleteSelected;
    //
    public $Width;
    public $Margin;

    //
    public $SearchControl;
    public $UseFilter;
    //
    private $orderColumnFieldName;
    private $orderType;
    private $highlightRowAtHover;
    private $errorMessage;
    //
    public $OnDisplayText;
    //
    private $defaultOrderColumnFieldName;
    private $defaultOrderType;
    private $useImagesForActions;
    //
    /** @var GridBand[] */
    private $bands;
    private $defaultBand;
    //
    private $editClientValidationScript;
    private $insertClientValidationScript;

    private $editClientFormLoadedScript;
    private $insertClientFormLoadedScript;
    private $editClientEditorValueChangedScript;
    private $insertClientEditorValueChangedScript;

    private $enabledInlineEditing;
    private $internalName;
    private $showUpdateLink = true;
    private $useFixedHeader;
    private $showLineNumbers;
    private $useModalInserting;
    private $width;

    /** @var FilterBuilderControl */
    private $filterBuilder;

    /** @var QuickFilter */
    private $quickFilter;

    /** @var Aggregate[] */
    private $totals = array();

    /** @var bool */
    private $allowOrdering;

    /** @var bool */
    private $advancedSearchAvailable;

    /** @var bool */
    private $filterRowAvailable;

    /** @var Event */
    public $OnCustomRenderColumn;

    /** @var Event */
    public $OnCustomDrawCell;

    /** @var Event */
    public $BeforeShowRecord;

    /** @var Event */
    public $BeforeUpdateRecord;

    /** @var Event */
    public $BeforeInsertRecord;

    /** @var Event */
    public $BeforeDeleteRecord;

    /** @var Event */
    public $AfterUpdateRecord;

    /** @var Event */
    public $AfterInsertRecord;

    /** @var Event */
    public $AfterDeleteRecord;

    /** @var Event */
    public $OnBeforeDataChange;

    /** @var Event */
    public $OnCustomDrawCell_Simple;

    /** @var Event */
    public $OnCustomRenderTotal;

    /** @var Event */
    public $OnCustomRenderPrintColumn;

    /** @var Event */
    public $OnCustomRenderExportColumn;

    /** @var Event */
    public $OnGetCustomTemplate;

    /** @var DetailColumn[] */
    private $details;

    /** @var bool */
    private $showFilterBuilder;

    function __construct($page, $dataset, $name) {
        $this->page = $page;
        $this->dataset = $dataset;
        $this->internalName = $name;
        //
        $this->editColumns = array();
        $this->viewColumns = array();
        $this->printColumns = array();
        $this->insertColumns = array();
        $this->exportColumns = array();
        $this->singleRecordViewColumns = array();
        $this->details = array();
        //
        $this->SearchControl = new NullComponent('Search');
        $this->UseFilter = false;
        //
        $this->showAddButton = false;
        //
        $this->OnCustomRenderTotal = new Event();
        $this->OnCustomDrawCell = new Event();
        $this->BeforeShowRecord = new Event();

        $this->BeforeUpdateRecord = new Event();
        $this->BeforeInsertRecord = new Event();
        $this->BeforeDeleteRecord = new Event();

        $this->AfterUpdateRecord = new Event();
        $this->AfterInsertRecord = new Event();
        $this->AfterDeleteRecord = new Event();


        $this->OnCustomDrawCell_Simple = new Event();
        $this->OnCustomRenderColumn = new Event();
        $this->OnBeforeDataChange = new Event();
        $this->OnDisplayText = new Event();
        $this->OnCustomRenderPrintColumn = new Event();
        $this->OnCustomRenderExportColumn = new Event();
        $this->OnGetCustomTemplate = new Event();

        //
        $this->SetState(OPERATION_VIEWALL);
        $this->allowDeleteSelected = false;
        $this->highlightRowAtHover = false;

        $this->defaultOrderColumnFieldName = null;
        $this->defaultOrderType = null;

        $this->bands = array();
        $this->defaultBand = new GridBand('defaultBand', 'defaultBand');
        $this->bands[] = $this->defaultBand;
        //
        $this->useImagesForActions = true;
        $this->SetWidth(null);
        $this->SetEditClientValidationScript('');
        $this->SetInsertClientValidationScript('');

        $this->name = 'grid';
        $this->enabledInlineEditing = true;
        $this->useFixedHeader = false;
        $this->showLineNumbers = false;
        $this->useModalInserting = false;
        $this->allowOrdering = true;
        $this->filterBuilder = new FilterBuilderControl($this, $this->GetPage()->GetLocalizerCaptions());
        $this->quickFilter = new QuickFilter(get_class($this->GetPage()), $this->GetPage(), $this->GetDataset());
        $this->advancedSearchAvailable = true;
        $this->filterRowAvailable = true;
        $this->showFilterBuilder = true;
    }

    /**
     * @param string $columnName
     * @return \CustomViewColumn|null
     */
    private function FindViewColumnByName($columnName) {
        $columns = $this->GetViewColumns();
        foreach ($columns as $column) {
            if ($this->GetColumnName($column) == $columnName) {
                return $column;
            }
        }
        return null;
    }

    public function UpdateSearchControls() {
        /** @var AdvancedSearchControl $advancedSearch  */
        $advancedSearch = $this->GetPage()->AdvancedSearchControl;
        if ($advancedSearch != null) {

            foreach ($advancedSearch->GetSearchColumns() as $searchColumn) {
                $columnName = $searchColumn->GetFieldName();
                $column = $this->FindViewColumnByName($columnName);
                /** @var Field $field */
                if ($column)
                    $field = $this->dataset->GetFieldByName($column->GetName());
                else
                    $field = $this->dataset->GetFieldByName($columnName);

                if ($field && $searchColumn) {
                    if ($searchColumn instanceof LookupSearchColumn) {
                        $this->filterBuilder->AddField(
                            $searchColumn,
                            $searchColumn->GetFieldName(),
                            $searchColumn->GetCaption(),
                            $field->GetEngFieldType(),
                            'Typeahead',
                            array(
                                'handler' => $searchColumn->GetHandlerName()
                            ));

                        $searchColumnViewData['Value'] = $searchColumn->GetDisplayValue();
                    } else if ($field instanceof DateTimeField || $field instanceof DateField) {
                        $this->filterBuilder->AddField(
                            $searchColumn,
                            $searchColumn->GetFieldName(),
                            $searchColumn->GetCaption(),
                            $field->GetEngFieldType(), null, array(
                                'fdow' => GetFirstDayOfWeek()
                            ));
                    } else {
                        $this->filterBuilder->AddField(
                            $searchColumn,
                            $searchColumn->GetFieldName(),
                            $searchColumn->GetCaption(),
                            $field->GetEngFieldType(), null, null);
                    }
                }

            }
        }
    }

    public function GetQuickFilter() {
        return $this->quickFilter;
    }

    public function GetFilterBuilder() {
        return $this->filterBuilder;
    }

    public function GetTemplate($mode, $defaultTemplate) {
        $template = '';
        $this->OnGetCustomTemplate->Fire(
            array($mode, &$template)
        );
        return ($template != '') ? $template : $defaultTemplate;
    }

    #region Options

    public function GetUseModalInserting() {
        return $this->useModalInserting;
    }

    public function SetUseModalInserting($value) {
        $this->useModalInserting = $value;
    }

    public function GetShowLineNumbers() {
        return $this->showLineNumbers;
    }

    public function SetShowLineNumbers($showLineNumbers) {
        $this->showLineNumbers = $showLineNumbers;
    }

    public function GetUseFixedHeader() {
        return $this->useFixedHeader;
    }

    public function SetUseFixedHeader($useFixedHeader) {
        $this->useFixedHeader = $useFixedHeader;
    }

    public function GetHighlightRowAtHover() {
        return $this->highlightRowAtHover;
    }

    public function SetHighlightRowAtHover($value) {
        $this->highlightRowAtHover = $value;
    }

    public function GetUseImagesForActions() {
        return $this->useImagesForActions;
    }

    public function SetUseImagesForActions($value) {
        $this->useImagesForActions = $value;
    }

    public function UseAutoWidth() {
        return !isset($this->width);
    }

    public function GetWidth() {
        return $this->width;
    }

    public function SetWidth($value) {
        $this->width = $value;
    }

    public function GetEditClientValidationScript() {
        return $this->editClientValidationScript;
    }

    public function GetInsertClientValidationScript() {
        return $this->insertClientValidationScript;
    }

    public function SetEditClientValidationScript($value) {
        $this->editClientValidationScript = $value;
    }

    public function SetInsertClientValidationScript($value) {
        $this->insertClientValidationScript = $value;
    }

    #endregion

    #region Session variables

    private function SetSessionVariable($name, $value) {
        GetApplication()->SetSessionVariable($this->GetName() . '_' . $name, $value);
    }

    private function UnSetSessionVariable($name) {
        GetApplication()->UnSetSessionVariable($this->GetName() . '_' . $name);
    }

    private function IsSessionVariableSet($name) {
        return GetApplication()->IsSessionVariableSet($this->GetName() . '_' . $name);
    }

    private function GetSessionVariable($name) {
        return GetApplication()->GetSessionVariable($this->GetName() . '_' . $name);
    }

    #endregion

    public function SetErrorMessage($value) {
        $this->errorMessage = $value;
    }

    public function GetErrorMessage() {
        return $this->errorMessage;
    }

    public function SetGridMessage($value) {
        $this->message = $value;
    }

    public function GetGridMessage() {
        return $this->message;
    }

    /**
     * @return Page
     */
    function GetPage() {
        return $this->page;
    }

    /**
     * @return IDataset
     */
    function GetDataset() {
        return $this->dataset;
    }

    function GetSingleRecordViewColumns() {
        return $this->singleRecordViewColumns;
    }

    #region Bands

    public function AddBand($bandName, $caption, $useConsolidatedHeader = false) {
        $result = new GridBand($bandName, $caption, $useConsolidatedHeader);
        $this->bands[] = $result;
        return $result;
    }

    public function AddBandToBegin($bandName, $caption, $useConsolidatedHeader = false) {
        $result = new GridBand($bandName, $caption, $useConsolidatedHeader);
        $this->bands = array_merge(array($result), $this->bands);
        return $result;
    }

    public function GetBandByName($name) {
        foreach ($this->bands as $band)
            if ($band->GetName() == $name)
                return $band;
        return null;
    }

    public function GetDefaultBand() {
        return $this->defaultBand;
    }

    public function GetViewBands() {
        return $this->bands;
    }

    #endregion

    function CreateLinkBuilder() {
        return $this->GetPage()->CreateLinkBuilder();
    }

    /**
     * @param CustomViewColumn $column
     * @return string
     */
    function GetVerticalLineBeforeWidth($column) {
        if (is_subclass_of($column, 'CustomViewColumn'))
            return $column->GetVerticalLine();
        return null;
    }

    function AddVericalLine($style) {
        if (count($this->viewColumns) > 0)
            $this->viewColumns[count($this->viewColumns) - 1]->SetVerticalLine($style);
    }

    function AddSingleRecordViewColumn($column) {
        $this->singleRecordViewColumns[] = $column;
        $this->DoAddColumn($column);
        return $column;
    }

    #region Columns

    /**
     * @param CustomEditColumn|CustomViewColumn $column
     * @return void
     */
    private function DoAddColumn($column) {
        $column->SetGrid($this);
    }

    public function AddDetail($column) {
        $this->details[] = $column;
    }

    public function AddViewColumn($column, $bandName = null) {
        if ($column instanceof DetailColumn) {
            $this->AddDetail($column);
            $this->DoAddColumn($column);
            return $column;
        }

        $this->viewColumns[] = $column;
        $this->DoAddColumn($column);

        $band = $this->GetBandByName($bandName);
        if (!isset($band))
            $band = $this->GetDefaultBand();
        $band->AddColumn($column);

        return $column;
    }

    public function AddEditColumn($column) {
        $this->editColumns[] = $column;
        $this->DoAddColumn($column);
        return $column;
    }

    public function AddPrintColumn($column) {
        $this->printColumns[] = $column;
        $this->DoAddColumn($column);
        return $column;
    }

    public function AddInsertColumn($column) {
        $this->insertColumns[] = $column;
        $this->DoAddColumn($column);
        return $column;
    }

    public function AddExportColumn($column) {
        $this->exportColumns[] = $column;
        $this->DoAddColumn($column);
        return $column;
    }

    /**
     * @return CustomEditColumn[]
     */
    public function GetEditColumns() {
        return $this->editColumns;
    }

    /**
     * @return CustomViewColumn[]
     */
    public function GetViewColumns() {
        return $this->viewColumns;
    }

    /**
     * @return array|CustomViewColumn[]
     */
    public function GetPrintColumns() {
        return $this->printColumns;
    }

    /**
     * @return CustomEditColumn[]
     */
    public function GetInsertColumns() {
        return $this->insertColumns;
    }

    public function GetExportColumns() {
        return $this->exportColumns;
    }

    #endregion

    /**
     * @param \Renderer $renderer
     * @return void
     */
    public function Accept(Renderer $renderer) {
        $renderer->RenderGrid($this);
    }

    public function SetState($StateName) {

        switch ($StateName) {
            case OPERATION_VIEW :
                $this->gridState = new ViewGridState($this);
                break;
            case OPERATION_PRINT_ONE :
                $this->gridState = new ViewGridState($this);
                break;
            case OPERATION_EDIT :
                $this->gridState = new EditGridState($this);
                break;
            case OPERATION_VIEWALL :
                $this->gridState = new ViewAllGridState($this);
                break;
            case OPERATION_COMMIT :
                $this->gridState = new CommitEditedValuesGridState($this);
                break;
            case OPERATION_INSERT:
                $this->gridState = new InsertGridState($this);
                break;
            case OPERATION_COPY:
                $this->gridState = new CopyGridState($this);
                break;
            case OPERATION_COMMIT_INSERT:
                $this->gridState = new CommitNewValuesGridState($this);
                break;
            case OPERATION_DELETE:
                $this->gridState = new DeleteGridState($this);
                break;
            case OPERATION_COMMIT_DELETE:
                $this->gridState = new CommitDeleteGridState($this);
                break;
            case OPERATION_DELETE_SELECTED:
                $this->gridState = new DeleteSelectedGridState($this);
                break;
            case OPERATION_AJAX_REQUERT_INLINE_EDIT:
                $this->gridState = new OpenInlineEditorsGridState($this);
                break;
            case OPERATION_AJAX_REQUERT_INLINE_EDIT_COMMIT:
                $this->gridState = new CommitInlineEditedValuesGridState($this);
                break;
            case OPERATION_AJAX_REQUERT_INLINE_INSERT:
                $this->gridState = new OpenInlineInsertEditorsGridState($this);
                break;
            case OPERATION_AJAX_REQUERT_INLINE_INSERT_COMMIT:
                $this->gridState = new CommitInlineInsertedValuesGridState($this);
                break;
        }
    }

    /**
     * @return GridState
     */
    public function GetState() {
        return $this->gridState;
    }

    public function GetEditPageAction() {
        $linkBuilder = $this->CreateLinkBuilder();
        return $linkBuilder->GetLink();
    }

    public function GetOpenInsertModalDialogLink() {
        $linkBuilder = $this->CreateLinkBuilder();
        $linkBuilder->AddParameter(OPERATION_HTTPHANDLER_NAME_PARAMNAME, $this->GetPage()->GetModalGridEditingHandler());
        $linkBuilder->AddParameter(ModalOperation::Param, ModalOperation::OpenModalInsertDialog);
        return $linkBuilder->GetLink();
    }

    public function GetModalInsertPageAction() {
        $linkBuilder = $this->CreateLinkBuilder();
        $linkBuilder->AddParameter(OPERATION_HTTPHANDLER_NAME_PARAMNAME, $this->GetPage()->GetModalGridEditingHandler());
        return $linkBuilder->GetLink();
    }

    public function GetModalEditPageAction() {
        $linkBuilder = $this->CreateLinkBuilder();
        $linkBuilder->AddParameter(OPERATION_HTTPHANDLER_NAME_PARAMNAME, $this->GetPage()->GetModalGridEditingHandler());
        return $linkBuilder->GetLink();
    }

    public function GetReturnUrl() {
        $linkBuilder = $this->CreateLinkBuilder();
        $linkBuilder->AddParameter(OPERATION_PARAMNAME, 'return');
        return $linkBuilder->GetLink();
    }

    #region Ordering

    public function GetOrderType() {
        return $this->orderType;
    }

    public function SetOrderType($value) {
        $this->orderType = $value;
    }

    public function SetDefaultOrdering($fieldName, $orderType) {
        $this->defaultOrderColumnFieldName = $fieldName;
        $this->defaultOrderType = $orderType;
    }

    private function ApplyDefaultOrder() {
        $this->orderColumnFieldName = $this->defaultOrderColumnFieldName;
        $this->orderType = $this->defaultOrderType;
    }

    public function GetOrderColumnFieldName() {
        return $this->orderColumnFieldName;
    }

    public function SetOrderColumnFieldName($value) {
        $this->orderColumnFieldName = $value;
    }

    private function ExtractOrderValues() {
        if (GetApplication()->IsGETValueSet('order')) {
            $orderValue = GetApplication()->GetGETValue('order');
            $this->orderColumnFieldName = substr($orderValue, 1, strlen($orderValue) - 1);
            $this->orderType = $orderValue[0] == 'a' ? otAscending : otDescending;
            $this->SetSessionVariable($this->internalName . '_' . 'orderColumnFieldName', $this->orderColumnFieldName);
            $this->SetSessionVariable($this->internalName . '_' . 'orderType', $this->orderType);
        } elseif (GetOperation() == 'resetorder') {
            $this->UnSetSessionVariable($this->internalName . '_' . 'orderColumnFieldName');
            $this->UnSetSessionVariable($this->internalName . '_' . 'orderType');
            $this->ApplyDefaultOrder();
        } elseif ($this->IsSessionVariableSet($this->internalName . '_' . 'orderColumnFieldName')) {
            $this->orderColumnFieldName = $this->GetSessionVariable($this->internalName . '_' . 'orderColumnFieldName');
            $this->orderType = $this->GetSessionVariable($this->internalName . '_' . 'orderType');
        } else {
            $this->ApplyDefaultOrder();
        }
    }

    #endregion

    #region Buttons

    public function SetShowAddButton($value) {
        $this->showAddButton = $value;
    }

    public function GetShowAddButton() {
        return $this->showAddButton;
    }

    public function SetShowInlineAddButton($value) {
        $this->showInlineAddButton = $value;
    }

    public function GetShowInlineAddButton() {
        return $this->showInlineAddButton;
    }

    function GetPrintRecordLink() {
        $result = $this->CreateLinkBuilder();
        return $result->GetLink();
    }

    function GetInlineEditRequestsAddress() {
        $result = $this->CreateLinkBuilder();
        return $result->GetLink();
    }

    function GetDeleteSelectedLink() {
        $result = $this->CreateLinkBuilder();
        return $result->GetLink();
    }

    public function GetAddRecordLink() {
        $result = $this->CreateLinkBuilder();
        $result->AddParameter(OPERATION_PARAMNAME, OPERATION_INSERT);
        return $result->GetLink();
    }

    function GetUpdateLink() {
        return $this->CreateLinkBuilder()->GetLink();
    }

    function GetShowUpdateLink() {
        return $this->showUpdateLink;
    }

    function SetShowUpdateLink($value) {
        $this->showUpdateLink = $value;
    }

    function SetAllowDeleteSelected($value) {
        $this->allowDeleteSelected = $value;
    }

    function GetAllowDeleteSelected() {
        return $this->allowDeleteSelected;
    }

    #endregion

    function ProcessMessages() {
        $this->ExtractOrderValues();
        $this->SearchControl->ProcessMessages();
        $filterApplied = $this->filterBuilder->ProcessMessages();
        $this->quickFilter->ProcessMessages();
        $this->gridState->ProcessMessages();
        if ($filterApplied) {
            $link = $this->GetPage()->CreateLinkBuilder();
            header('Location: ' . $link->GetLink());
            exit();
        }
    }

    #region Utils

    private $internalStateSwitch = false;
    private $internalStateSwitchPrimaryKeys = array();

    function SetInternalStateSwitch($primaryKeys) {
        $this->internalStateSwitch = true;
        $this->internalStateSwitchPrimaryKeys = $primaryKeys;
    }

    function GetPrimaryKeyValuesFromGet() {
        if ($this->internalStateSwitch) {
            return $this->internalStateSwitchPrimaryKeys;
        } else {
            $primaryKeyValues = array();
            ExtractPrimaryKeyValues($primaryKeyValues, METHOD_GET);
            return $primaryKeyValues;
        }
    }

    #endregion

    public function GetName() {
        return $this->name;
    }

    public function SetName($value) {
        $this->name = $value;
    }

    public function SetEnabledInlineEditing($value) {
        $this->enabledInlineEditing = $value;
    }

    public function GetEnabledInlineEditing() {
        return $this->enabledInlineEditing;
    }

    #region Totals

    public function HasTotals() {
        return count($this->totals) > 0;
    }

    public function SetTotal(CustomViewColumn $column, Aggregate $aggregate) {
        $this->totals[$column->GetName()] = $aggregate;
    }

    /**
     * @param CustomViewColumn $column
     * @return Aggregate
     */
    public function GetAggregateFor(CustomViewColumn $column) {
        return ArrayUtils::GetArrayValueDef($this->totals, $column->GetName());
    }

    public function GetTotalValues() {
        $command = new AggregationValuesQuery(
            $this->GetDataset()->GetSelectCommand(),
            $this->GetDataset()->GetCommandImp()
        );
        foreach ($this->totals as $columnName => $aggregate)
            $command->AddAggregate($columnName, $aggregate, $columnName);

        $result = array();
        $this->GetDataset()->GetConnection()->ExecQueryToArray(
            $command->GetSQL(), $result
        );
        return $result[0];
    }

    public function GetAllowOrdering() {
        return $this->allowOrdering;
    }

    public function SetAllowOrdering($value) {
        $this->allowOrdering = $value;
    }

    public function GetEditClientFormLoadedScript() {
        return $this->editClientFormLoadedScript;
    }

    public function SetEditClientFormLoadedScript($editClientFormLoadedScript) {
        $this->editClientFormLoadedScript = $editClientFormLoadedScript;
    }

    public function GetInsertClientFormLoadedScript() {
        return $this->insertClientFormLoadedScript;
    }

    public function SetInsertClientFormLoadedScript($insertClientFormLoadedScript) {
        $this->insertClientFormLoadedScript = $insertClientFormLoadedScript;
    }

    public function GetEditClientEditorValueChangedScript() {
        return $this->editClientEditorValueChangedScript;
    }

    public function SetEditClientEditorValueChangedScript($editClientEditorValueChangedScript) {
        $this->editClientEditorValueChangedScript = $editClientEditorValueChangedScript;
    }

    public function GetInsertClientEditorValueChangedScript() {
        return $this->insertClientEditorValueChangedScript;
    }

    public function SetInsertClientEditorValueChangedScript($insertClientEditorValueChangedScript) {
        $this->insertClientEditorValueChangedScript = $insertClientEditorValueChangedScript;
    }

    #endregion

    public function GetId() {
        return $this->internalName;
    }

    public function SetId($value) {
        $this->internalName = $value;
    }

    public function GetHiddenValues() {
        return $this->GetPage()->GetHiddenGetParameters();
    }

    public function GetHasDetails() {
        return count($this->details) > 0;
    }

    private function IsShowCurrentRecord() {
        $show = true;
        $this->BeforeShowRecord->Fire(array(&$show));
        return $show;
    }

    private function GetColumnName(CustomViewColumn $column) {
        $dataset = $this->GetDataset();
        return $dataset->IsLookupField($column->GetName()) ?
            $dataset->IsLookupFieldNameByDisplayFieldName($column->GetName()) :
            $column->GetName();
    }

    public function RenderColumn(Renderer $renderer, CustomViewColumn $column) {
        $handled = false;
        $rowValues = $this->GetDataset()->GetFieldValues();
        $result = '';
        $this->OnCustomRenderColumn->Fire(array(
            $this->GetColumnName($column),
            $column->GetData(),
            $rowValues, &$result, &$handled));
        $result = $handled ? $result : $renderer->Render($column);
        return $result;
    }

    private function GetStylesForColumn(Grid $grid, $rowData) {
        $cellFontColor = array();
        $cellFontSize = array();
        $cellBgColor = array();
        $cellItalicAttr = array();
        $cellBoldAttr = array();

        $grid->OnCustomDrawCell_Simple->Fire(array($rowData, &$cellFontColor, &$cellFontSize, &$cellBgColor, &$cellItalicAttr, &$cellBoldAttr));

        $result = array();
        $fieldNames = array_unique(array_merge(
            array_keys($cellFontColor),
            array_keys($cellFontSize),
            array_keys($cellBgColor),
            array_keys($cellItalicAttr),
            array_keys($cellBoldAttr)));

        $fieldStylesBuilder = new StyleBuilder();
        foreach ($fieldNames as $fieldName) {
            $fieldStylesBuilder->Clear();
            if (array_key_exists($fieldName, $cellFontColor))
                $fieldStylesBuilder->Add('color', $cellFontColor[$fieldName]);
            if (array_key_exists($fieldName, $cellFontSize))
                $fieldStylesBuilder->Add('font-size', $cellFontSize[$fieldName]);
            if (array_key_exists($fieldName, $cellBgColor))
                $fieldStylesBuilder->Add('background-color', $cellBgColor[$fieldName]);
            if (array_key_exists($fieldName, $cellItalicAttr))
                $fieldStylesBuilder->Add('font-style',
                    $cellItalicAttr[$fieldName] ? 'italic' : 'normal');
            if (array_key_exists($fieldName, $cellBoldAttr)) {
                $fieldStylesBuilder->Add('font-weight',
                    $cellBoldAttr[$fieldName] ? 'bold' : 'normal');
            }
            $result[$fieldName] = $fieldStylesBuilder->GetStyleString();
        }

        return $result;
    }

    public function GetRowStylesByColumn($rowValues) {
        $result = array();
        $cellCssStyles = array();
        $rowCssStyle = '';
        $this->OnCustomDrawCell->Fire(array($rowValues, &$cellCssStyles, &$rowCssStyle));
        $cellCssStyles_Simple = $this->GetStylesForColumn($this, $rowValues);
        $cellCssStyles = array_merge($cellCssStyles_Simple, $cellCssStyles);

        foreach ($this->GetViewBands() as $band) {
            foreach ($band->GetColumns() as $column) {
                $columnName = $this->GetColumnName($column);

                if (array_key_exists($columnName, $cellCssStyles)) {
                    $styleBuilder = new StyleBuilder();
                    $styleBuilder->AddStyleString($rowCssStyle);
                    $styleBuilder->AddStyleString($cellCssStyles[$columnName]);
                    $result[$columnName] = $styleBuilder->GetStyleString();
                } else
                    $result[$columnName] = $rowCssStyle;
            }
        }
        return $result;
    }

    private function GetRowStyles($rowValues) {
        $cellCssStyles = '';
        $rowCssStyle = '';
        $this->OnCustomDrawCell->Fire(array($rowValues, &$cellCssStyles, &$rowCssStyle));

        return $rowCssStyle;
    }

    private function GetRowsViewData(Renderer $renderer) {
        $result = array();
        $dataset = $this->GetDataset();

        $dataset->Open();
        $lineNumber = $this->GetStartLineNumber();
        while ($dataset->Next()) {
            if (!$this->IsShowCurrentRecord())
                continue;

            $rowViewData = array();
            $rowStyle = $this->GetRowStyles($this->GetDataset()->GetFieldValues());
            $rowStyleByColumns = $this->GetRowStylesByColumn($this->GetDataset()->GetFieldValues());

            foreach ($this->GetViewBands() as $band) {
                foreach ($band->GetColumns() as $column) {

                    $columnName = $dataset->IsLookupField($column->GetName()) ?
                        $dataset->IsLookupFieldNameByDisplayFieldName($column->GetName()) :
                        $column->GetName();

                    $columnRenderResult = $this->RenderColumn($renderer, $column);

                    $rowViewData[$columnName] = array(
                        'ColumnName' => $column->GetName(),
                        'Data' => $columnRenderResult,
                        'FieldName' => $columnName,
                        'Classes' => $column->GetGridColumnClass(),
                        'Style' => $rowStyleByColumns[$columnName]
                    );
                }
            }

            $detailsViewData = array();
            foreach ($this->details as $detail) {
                $detailsViewData[] = $detail->GetViewData();
            }

            $result[] = array(
                'DataCells' => $rowViewData,
                'LineNumber' => $lineNumber,
                'PrimaryKeys' => $dataset->GetPrimaryKeyValues(),
                'Style' => $rowStyle,
                'Details' => array(
                    'Items' => $detailsViewData,
                    'JSON' => htmlspecialchars(SystemUtils::ToJSON($detailsViewData))
                )
            );
            $lineNumber++;
        }
        //$dataset->Close();
        return $result;
    }

    private function GetTotalDataForColumn(CustomViewColumn $column, $totalValues) {

        if (isset($totalValues[$column->GetName()])) {
            $aggregate = $this->GetAggregateFor($column)->AsString();

            $totalValue = $totalValues[$column->GetName()];
            if (is_numeric($totalValue))
                $totalValue = number_format((double)$totalValue, 2);

            $result = StringUtils::Format('%s = %s', $aggregate, $totalValue);

            $customTotalValue = '';
            $handled = false;
            $this->OnCustomRenderTotal->Fire(array($totalValue, $aggregate, $column->GetName(), &$customTotalValue, &$handled));
            if ($handled)
                $result = $customTotalValue;

            return $result;
        }
        return '';
    }

    private function GetTotalsViewData() {
        if ($this->HasTotals()) {
            $result = array();
            $totalValues = $this->GetTotalValues();
            foreach ($this->GetViewBands() as $band) {
                foreach ($band->GetColumns() as $column) {
                    $result[] = array(
                        'Value' => $this->GetTotalDataForColumn($column, $totalValues)
                    );
                }
            }
            return $result;
        }
        return null;
    }

    private function GetStartLineNumber() {
        $startLineNumber = 1;
        $paginationControl = $this->GetPage()->GetPaginationControl();
        if (isset($paginationControl)) {
            $startLineNumber =
                ($paginationControl->CurrentPageNumber() - 1) * ($paginationControl->GetRowsPerPage()) + 1;
        }
        return $startLineNumber;
    }

    private function GetAdditionalAttributes() {
        $result = new AttributesBuilder();
        if ($this->GetShowLineNumbers()) {
            $result->AddAttrValue('start-line-number', $this->GetStartLineNumber());
        }
        $result->AddAttrValue('data-delete-selected-action', $this->GetDeleteSelectedLink(), true);
        $result->AddAttrValue('data-grid-quick-filter-value', $this->GetQuickFilter()->GetValue(), true);
        return $result->GetAsString();
    }

    public function GetFilterRowViewData() {
        $result = array();
        $result['Columns'] = array();

        $advancedSearch = $this->GetPage()->AdvancedSearchControl;

        $bands = $this->GetViewBands();
        $isActionButtonPositionLeft = $bands[0]->GetName() == 'actions';

        if ($advancedSearch && $this->GetFilterRowAvailable()) {
            foreach ($this->GetViewBands() as $band) {
                foreach ($band->GetColumns() as $column) {
                    $searchColumnViewData = null;

                    $columnName = $this->GetDataset()->IsLookupField($column->GetName()) ?
                        $this->GetDataset()->IsLookupFieldNameByDisplayFieldName($column->GetName()) :
                        $column->GetName();

                    $searchColumn = null;
                    if ($column instanceof CustomDatasetFieldViewColumn ||
                        $column instanceof CustomFormatValueViewColumnDecorator ||
                        $column instanceof ImageViewColumn)
                    {
                        $searchColumn = $advancedSearch->FindSearchColumnByName($columnName);
                    }

                    if ($searchColumn) {

                        $searchColumnViewData = array();

                        $searchColumnViewData['FieldName'] = $columnName;
                        $searchColumnViewData['Value'] = $searchColumn->GetValue();

                        $searchColumnViewData['Attributes'] = '';
                        if ($column instanceof DateTimeViewColumn) {
                            $searchColumnViewData['Attributes'] =
                                'data-calendar="true" ' .
                                    'data-picker-first-day-of-week="' . GetFirstDayOfWeek() . '" ';
                        } else if ($searchColumn instanceof LookupSearchColumn) {
                            $searchColumnViewData['Attributes'] =
                                'data-pg-typeahead="true" ' .
                                    'data-pg-typeahead-handler="' . $searchColumn->GetHandlerName() . '" ' .
                                    'data-post-value="' . $searchColumn->GetValue() . '"';

                            $searchColumnViewData['Value'] = $searchColumn->GetDisplayValue();
                        }


                        if ($searchColumn->IsFilterActive()) {
                            $searchColumnViewData['CurrentOperator'] =
                                SearchFilterOperator::GetOperatorByName($searchColumn->GetFilterIndex())
                                    ->GetViewData($this->GetPage()->GetLocalizerCaptions());
                        } else {
                            $defaultFilter = 'CONTAINS';
                            $availableFilterTypes = array_keys($searchColumn->GetAvailableFilterTypes());
                            if (count($availableFilterTypes) > 0) {
                                $defaultFilter = $availableFilterTypes[0];
                            }
                            $searchColumnViewData['CurrentOperator'] =
                                SearchFilterOperator::GetOperatorByName($defaultFilter)
                                    ->GetViewData($this->GetPage()->GetLocalizerCaptions());
                        }
                        $searchColumnViewData['Operators'] = $searchColumn->GetAvailableFilterTypesViewData();
                    }
                    $result['Columns'][$column->GetName()] = $searchColumnViewData;
                }
            }

            $result['TimerInterval'] = $advancedSearch->getTimerInterval();


            $tempArray = array();
            $resetButtonPlacementColumnName = null;
            foreach ($this->GetViewBands() as $band) {
                foreach ($band->GetColumns() as $column) {
                    $searchColumnViewData = null;

                    $columnName = $this->GetDataset()->IsLookupField($column->GetName()) ?
                        $this->GetDataset()->IsLookupFieldNameByDisplayFieldName($column->GetName()) :
                        $column->GetName();

                    $searchColumn = null;
                    if ($column instanceof CustomDatasetFieldViewColumn || $column instanceof CustomFormatValueViewColumnDecorator || $column instanceof ImageViewColumn) {
                        $searchColumn = $advancedSearch->FindSearchColumnByName($columnName);
                    }

                    if ($searchColumn) {
                        $tempArray[] = array('Name' => $columnName, 'IsPlacement' => false);
                    } else {
                        $tempArray[] = array('Name' => $columnName, 'IsPlacement' => true);
                    }
                }
            }

            $resetButtonPlacementFound = false;
            for ($i = 0; $i < count($tempArray); $i++) {
                if (!$tempArray[$i]['IsPlacement'] && $i > 0) {
                    $result['Columns'][$tempArray[$i - 1]['Name']]['ResetButtonPlacement'] = true;
                    $result['Columns'][$tempArray[$i - 1]['Name']]['ResetButtonAlignment'] = $isActionButtonPositionLeft ? 'right' : 'left';

                    $resetButtonPlacementFound = true;
                    break;
                } else if (!$tempArray[$i]['IsPlacement']) {
                    break;
                }
            }

            if (!$resetButtonPlacementFound) {
                for ($i = count($tempArray) - 1; $i >= 0; $i--) {
                    if (!($tempArray[$i]['IsPlacement']) && $i < (count($tempArray) - 1)) {
                        $result['Columns'][$tempArray[$i + 1]['Name']]['ResetButtonPlacement'] = true;
                        $result['Columns'][$tempArray[$i + 1]['Name']]['ResetButtonAlignment'] = $isActionButtonPositionLeft ? 'right' : 'left';
                        $resetButtonPlacementFound = true;
                        break;
                    } else if (!$tempArray[$i]['IsPlacement']) {
                        break;
                    }
                }
            }

        }
        return $result;
    }

    public function GetAdvancedSearchAvailable() {
        return $this->advancedSearchAvailable;
    }

    function SetFilterRowAvailable($value) {
        $this->filterRowAvailable = $value;
    }

    function GetFilterRowAvailable() {
        return $this->filterRowAvailable;
    }


    public function SetAdvancedSearchAvailable($value) {
        $this->advancedSearchAvailable = $value;
    }

    private function GetGridStyles() {
        $result = new StyleBuilder();


        if ($this->GetWidth()) {
            $result->Add('width', $this->GetWidth());
        }

        return $result->GetStyleString();
    }

    private function RequestFilterFromUser() {
        return
            $this->GetPage()->OpenAdvancedSearchByDefault() &&
            (
                ($this->GetPage()->AdvancedSearchControl && !$this->GetPage()->AdvancedSearchControl->HasCondition()) &&
                ($this->GetFilterBuilder() && $this->GetFilterBuilder()->IsEmpty())
            );
    }

    private function GetHiddenValuesJson() {
        return SystemUtils::ToJSON($this->GetHiddenValues());
    }

    public function GetViewData(Renderer $renderer) {
        $bandsViewData = array();
        foreach ($this->GetViewBands() as $band) {
            $bandsViewData[] = $band->GetViewData();
        }

        $rows = array();
        $emptyGridMessage = $this->GetPage()->GetLocalizerCaptions()->GetMessageString('NoDataToDisplay');
        if ($this->RequestFilterFromUser()) {
            $emptyGridMessage = $this->GetPage()->GetLocalizerCaptions()->GetMessageString('CreateFilterConditionFirst');
        } else {
            $rows = $this->GetRowsViewData($renderer);
        }

        return array(
            'Id' => $this->GetId(),
            'Styles' => $this->GetGridStyles(),
            'Classes' => $this->GetGridClasses(),
            'Attributes' => $this->GetAdditionalAttributes(),

            'HiddenValuesJson' => $this->GetHiddenValuesJson(),

            'EmptyGridMessage' => $emptyGridMessage,

            // Filter builder
            'FilterBuilder' => $this->GetShowFilterBuilder() ?
                $this->GetFilterBuilder()->GetViewData() :
                null,

            // Filter row
            'FilterRow' => $this->GetFilterRowViewData(),
            'AllowFilterRow' =>
            (($this->GetPage()->AdvancedSearchControl != null) ? true : false) &&
                $this->GetPage()->GetFilterRowAvailable(),

            // Quick filter
            'QuickFilter' => $this->GetQuickFilter()->GetViewData(),
            'AllowQuickFilter' => $this->GetPage()->GetSimpleSearchAvailable() && $this->UseFilter,

            // Action panel
            'ActionsPanelAvailable' =>
            ($this->GetPage()->GetSimpleSearchAvailable() && $this->UseFilter) ||
                ($this->GetShowAddButton()) ||
                ($this->GetShowInlineAddButton()) ||
                ($this->GetAllowDeleteSelected()) ||
                ($this->GetShowUpdateLink()),

            'Links' => array(
                'ModalInsertDialog' => $this->GetOpenInsertModalDialogLink(),
                'InlineEditRequest' => $this->GetInlineEditRequestsAddress(),
                'SimpleAddNewRow' => $this->GetAddRecordLink(),
                'Refresh' => $this->GetUpdateLink()
            ),

            'ActionsPanel' => array(
                'InlineAdd' => $this->GetShowInlineAddButton(),
                'AddNewButton' => $this->GetShowAddButton() ? ($this->GetUseModalInserting() ? 'modal' : 'simple') : null,
                'RefreshButton' => $this->GetShowUpdateLink(),
                'DeleteSelectedButton' => $this->GetAllowDeleteSelected()
            ),

            'ColumnCount' => count($this->GetViewColumns()) +
                ($this->GetAllowDeleteSelected() ? 1 : 0) +
                ($this->GetShowLineNumbers() ? 1 : 0) +
                ($this->GetHasDetails() ? 1 : 0),
            'Bands' => $bandsViewData,

            'HasDetails' => $this->GetHasDetails(),
            'UseInlineEdit' => $this->GetEnabledInlineEditing(),
            'HighlightRowAtHover' => $this->GetHighlightRowAtHover(),

            'AllowDeleteSelected' => $this->GetAllowDeleteSelected(),

            'ShowLineNumbers' => $this->GetShowLineNumbers(),

            'Rows' => $rows,
            'Totals' => $this->GetTotalsViewData(),

            'GridMessage' => $this->GetGridMessage() == '' ? null : $this->GetGridMessage(),
            'ErrorMessage' => $this->GetErrorMessage() == '' ? null : $this->GetErrorMessage()
        );
    }

    private function GetEditColumnViewData(Renderer $renderer) {
        $columnViewDatas = array();
        foreach ($this->GetEditColumns() as $column) {
            $columnViewDatas[$column->GetFieldName()] = array(
                'FieldName' => $column->GetFieldName(),
                'Value' => $column->GetValue(),
                'Editor' => $renderer->Render($column),
                'Caption' => $column->GetCaption(),
                'Required' => $column->DisplayAsRequired(),
                'UseSetNull' => $column->GetShowSetToNullCheckBox(),
                'UseSetDefault' => $column->GetAllowSetToDefault(),
                'IsValueNull' => $column->IsValueNull(),
                'IsValueSetToDefault' => $column->IsValueSetToDefault(),
                'SetNullCheckBoxName' => $column->GetFieldName() . '_null',
                'SetDefaultCheckBoxName' => $column->GetFieldName() . '_def'
            );
        }
        return $columnViewDatas;
    }

    public function GetInsertColumnViewData(Renderer $renderer) {
        $columnViewDatas = array();
        foreach ($this->GetInsertColumns() as $column) {
            $columnViewDatas[$column->GetFieldName()] = array(
                'FieldName' => $column->GetFieldName(),
                'Editor' => $renderer->Render($column),
                'Caption' => $column->GetCaption(),
                'Required' => $column->DisplayAsRequired(),
                'UseSetNull' => $column->GetShowSetToNullCheckBox(),
                'UseSetDefault' => $column->GetAllowSetToDefault(),
                'IsValueNull' => $column->IsValueNull(),
                'IsValueSetToDefault' => $column->IsValueSetToDefault(),
                'SetNullCheckBoxName' => $column->GetFieldName() . '_null',
                'SetDefaultCheckBoxName' => $column->GetFieldName() . '_def'
            );
        }
        return $columnViewDatas;
    }

    public function GetInsertViewData(Renderer $renderer) {

        $detailViewData = array();
        foreach ($this->details as $detail) {
            $linkBuilder = $this->CreateLinkBuilder();
            $detail->DecorateLinkForPostMasterRecord($linkBuilder);

            $detailViewData[] = array(
                'Link' => $linkBuilder->GetLink(),
                'Caption' => $detail->GetCaption()
            );
        }

        return array(
            'OnLoadScript' => $this->GetInsertClientFormLoadedScript(),
            'Details' => $detailViewData,
            'ErrorMessage' => $this->GetErrorMessage(),
            'FormAction' => $this->GetEditPageAction(),
            'Title' => $this->GetPage()->GetShortCaption(),
            'Columns' => $this->GetInsertColumnViewData($renderer),
            'CancelUrl' => $this->GetReturnUrl(),
            'ClientValidationScript' => $this->GetInsertClientValidationScript()
        );
    }

    public function GetModalInsertViewData(Renderer $renderer) {
        $result = $this->GetInsertViewData($renderer);
        $result['FormAction'] = $this->GetModalInsertPageAction();
        return $result;
    }

    public function GetEditViewData(Renderer $renderer) {
        $detailViewData = array();
        foreach ($this->details as $detail) {
            $linkBuilder = $this->CreateLinkBuilder();
            $detail->DecorateLinkForPostMasterRecord($linkBuilder);

            $detailViewData[] = array(
                'Link' => $linkBuilder->GetLink(),
                'Caption' => $detail->GetCaption()
            );
        }

        return array(
            'OnLoadScript' => $this->GetEditClientFormLoadedScript(),
            'Details' => $detailViewData,
            'Title' => $this->GetPage()->GetShortCaption(),
            'FormAction' => $this->GetEditPageAction(),
            'ErrorMessage' => $this->GetErrorMessage(),
            'CancelUrl' => $this->GetReturnUrl(),
            'Columns' => $this->GetEditColumnViewData($renderer)
        );
    }

    public function GetModalEditViewData(Renderer $renderer) {
        $result = $this->GetEditViewData($renderer);
        $result['FormAction'] = $this->GetModalEditPageAction();
        return $result;
    }


    public function GetViewSingleRowColumnViewData(Renderer $renderer) {
        $Row = array();

        $rowValues = $this->GetDataset()->GetFieldValues();
        foreach ($this->GetSingleRecordViewColumns() as $Column) {
            $columnName = $this->GetDataset()->IsLookupField($Column->GetName()) ?
                $this->GetDataset()->IsLookupFieldNameByDisplayFieldName($Column->GetName()) :
                $Column->GetName();

            $columnRenderResult = '';
            $customRenderColumnHandled = false;

            $this->OnCustomRenderColumn->Fire(array(
                $columnName,
                $Column->GetData(),
                $rowValues,
                &$columnRenderResult, &$customRenderColumnHandled
            ));

            $columnRenderResult = $customRenderColumnHandled ?
                $columnRenderResult :
                $renderer->Render($Column);

            $Row[$columnName] = array(
                'Caption' => $Column->GetCaption(),
                'DisplayValue' => $columnRenderResult
            );
        }
        return $Row;
    }

    public function GetViewSingleRowViewData(Renderer $renderer) {

        $detailViewData = array();
        $this->GetDataset()->Open();
        $linkBuilder = null;
        if ($this->GetDataset()->Next()) {
            $linkBuilder = $this->CreateLinkBuilder();
            $linkBuilder->AddParameter(OPERATION_PARAMNAME, OPERATION_PRINT_ONE);

            $keyValues = $this->GetDataset()->GetPrimaryKeyValues();
            for ($i = 0; $i < count($keyValues); $i++)
                $linkBuilder->AddParameter("pk$i", $keyValues[$i]);

            $primaryKeyMap = $this->GetDataset()->GetPrimaryKeyValuesMap();

            foreach ($this->details as $detail) {

                $detailViewData[] = array(
                    'Link' => $detail->GetSeparateViewLink(),
                    'Caption' => $detail->GetCaption()
                );
            }

            return array(
                'Details' => $detailViewData,
                'CancelUrl' => $this->GetReturnUrl(),
                'PrintOneRecord' => $this->GetPage()->GetPrinterFriendlyAvailable(),
                'PrintRecordLink' => $linkBuilder->GetLink(),
                'Title' => $this->GetPage()->GetShortCaption(),
                'PrimaryKeyMap' => $primaryKeyMap,
                'Row' => $this->GetViewSingleRowColumnViewData($renderer)
            );

        } else {
            RaiseError('Cannot retrieve single record. Check the primary key fields.');
            return null;
        }
    }

    public function SetShowFilterBuilder($value) {
        return $this->showFilterBuilder = $value;
    }

    public function GetShowFilterBuilder() {
        return $this->showFilterBuilder && $this->GetAdvancedSearchAvailable() && $this->GetPage()->GetAdvancedSearchAvailable();
    }

    public function GetDetailLinksViewData() {
        $result = array();
        foreach ($this->details as $detail) {
            $result[] = array(
                'Caption' => $detail->GetCaption(),
                'Link' => $detail->GetSeparateViewLink(),
                'Name' => $detail->GetSeparatePageHandlerName(),
            );
        }
        return $result;
    }

    public function FindDetail($detailEditHandlerName) {
        foreach ($this->details as $detail) {
            if ($detail->GetSeparatePageHandlerName() == $detailEditHandlerName)
                return $detail;
        }
        return null;
    }

    private function GetLinkParametersForPrimaryKey($primaryKeyValues) {
        $result = array();
        $keyValues = $primaryKeyValues;
        for ($i = 0; $i < count($keyValues); $i++)
            $result["pk$i"] = $keyValues[$i];
        return $result;
    }

    public function GetEditCurrentRecordLink($primaryKeyValues) {
        $result = $this->CreateLinkBuilder();
        $result->AddParameter(OPERATION_PARAMNAME, OPERATION_EDIT);
        $result->AddParameters($this->GetLinkParametersForPrimaryKey($primaryKeyValues));
        return $result->GetLink();
    }

    private function GetGridClasses() {
        $result = '';

        StringUtils::AddStr($result, 'stripped', ' ');

        if ($this->GetHighlightRowAtHover()) {
            StringUtils::AddStr($result, 'row-hover-highlight', ' ');
        }

        if ($this->GetUseFixedHeader()) {
            StringUtils::AddStr($result, 'fixed-header', ' ');
        }

        return $result;
    }
}
