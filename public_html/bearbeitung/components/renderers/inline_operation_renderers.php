<?php

// require_once 'components/renderers/renderer.php';
// require_once 'components/grid/grid.php';
// require_once 'components/utils/system_utils.php';

include_once dirname(__FILE__) . '/' . 'renderer.php';
include_once dirname(__FILE__) . '/' . '../grid/grid.php';
include_once dirname(__FILE__) . '/' . '../utils/system_utils.php';


class InlineEditRenderer extends Renderer
{
    public function RenderDetailPageEdit($detailPage)
    {
        header('Content-Type: application/xml');
        $this->DisplayTemplate('inline_operations/page.tpl',
            array(
                    ),
                array('Grid' => $this->Render($detailPage->GetGrid()))
                );
    }

    function RenderPage(Page $Page)
    {
        header('Content-Type: application/xml');
        $this->DisplayTemplate('inline_operations/page.tpl',
            array(
                    ),
                array('Grid' => $this->Render($Page->GetGrid()))
                );
    }

    function RenderGrid(Grid $grid)
    {
        $columnEditors = array();

        foreach($grid->GetViewColumns() as $column)
        {
            $editor = $column->GetEditOperationEditor();
            if (isset($editor))
            {
                $columnEditors[$column->GetName()]['Html'] = $this->Render($editor, false, true);
                $columnEditors[$column->GetName()]['Script'] = $this->Render($editor, true, false);
            }
        }
        
        $this->DisplayTemplate('inline_operations/grid.tpl',
            array(
                    'encoding' => $grid->GetPage()->GetContentEncoding(),
                    'ColumnEditors' => $columnEditors,
                    'EditorsNameSuffix' => $grid->GetState()->GetNameSuffix()),
                array()
                );
    }
}

class CommitInlineEditRenderer extends Renderer {
    public function RenderDetailPageEdit($detailPage)
    {
        header('Content-Type: application/xml');
        $this->DisplayTemplate('inline_operations/page.tpl',
            array(
                    ),
                array('Grid' => $this->Render($detailPage->GetGrid()))
                );
    }

    function RenderPage(Page $Page)
    {
        header('Content-Type: application/xml');
        $this->DisplayTemplate('inline_operations/page.tpl',
            array(
                    ),
                array('Grid' => $this->Render($Page->GetGrid()))
                );
    }

    function RenderGrid(Grid $grid)
    {
        $columnEditors = array();

        if ($grid->GetErrorMessage() != '')
        {
            foreach($grid->GetViewColumns() as $column)
            {
                $editor = $column->GetEditOperationEditor();
                if (isset($editor))
                {
                    $columnEditors[$column->GetName()]['Html'] = $this->Render($editor, false, true);
                    $columnEditors[$column->GetName()]['Script'] = $this->Render($editor, true, false);
                }
            }

            $this->DisplayTemplate('inline_operations/grid_edit_error_response.tpl',
                array(
                        'ErrorMessage' => $grid->GetErrorMessage(),
                        'ColumnEditors' => $columnEditors,
                        ),
                    array()
                    );
            
            return;
        }

        $dataset = $grid->GetDataset();
        $dataset->Open();

        if ($grid->GetDataset()->Next()) {
            $columnValues = array();

            $rowStyleByColumns = $grid->GetRowStylesByColumn($grid->GetDataset()->GetFieldValues());

            foreach($grid->GetViewColumns() as $column) {

                $columnName = $dataset->IsLookupField($column->GetName()) ?
                    $dataset->IsLookupFieldNameByDisplayFieldName($column->GetName()) :
                    $column->GetName();

                $columnValues[$column->GetName()]['Value'] = $grid->RenderColumn($this, $column);
                $columnValues[$column->GetName()]['Style'] = $rowStyleByColumns[$columnName];

            }

            $this->DisplayTemplate('inline_operations/grid_edit_commit_response.tpl',
                array(
                    'encoding' => $grid->GetPage()->GetContentEncoding(),
                    'ColumnValues' => $columnValues
                ),
                array()
                );
        }
    }

    protected function ShowHtmlNullValue() 
    {
        return true;
    }
}


class InlineInsertRenderer extends Renderer
{
    function RenderDetailPageEdit($Page)
    {
        header('Content-Type: application/xml');
        $this->DisplayTemplate('inline_operations/page.tpl',
            array(
                    ),
                array('Grid' => $this->Render($Page->GetGrid()))
                );
    }

    function RenderPage(Page $Page)
    {
        header('Content-Type: application/xml');
        $this->DisplayTemplate('inline_operations/page.tpl',
            array(
                    ),
                array('Grid' => $this->Render($Page->GetGrid()))
                );
    }

    function RenderGrid(Grid $grid)
    {
        $columnEditors = array();


        foreach($grid->GetViewColumns() as $column)
        {
            $editor = $column->GetInsertOperationEditor();
            if (isset($editor))
            {
                $columnEditors[$column->GetName()]['Html'] = $this->Render($editor, false, true);
                $columnEditors[$column->GetName()]['Script'] = $this->Render($editor, true, false);
            }
        }
        
        $this->DisplayTemplate('inline_operations/grid.tpl',
            array(
                'encoding' => $grid->GetPage()->GetContentEncoding(),
                'ColumnEditors' => $columnEditors,
                'EditorsNameSuffix' => $grid->GetState()->GetNameSuffix()),
            array()
        );
    }
}

class CommitInlineInsertRenderer extends Renderer
{
    function RenderDetailPageEdit($Page)
    {
        header('Content-Type: application/xml');
        $this->DisplayTemplate('inline_operations/page.tpl',
            array(
                    ),
                array('Grid' => $this->Render($Page->GetGrid()))
                );
    }

    function RenderPage(Page $Page)
    {
        header('Content-Type: application/xml');
        $this->DisplayTemplate('inline_operations/page.tpl',
            array(
                    ),
                array('Grid' => $this->Render($Page->GetGrid()))
                );
    }

    function RenderGrid(Grid $grid)
    {
        $columnEditors = array();
        $dataset = $grid->GetDataset();

        if ($grid->GetErrorMessage() != '')
        {
            foreach($grid->GetViewColumns() as $column)
            {
                $editor = $column->GetInsertOperationEditor();
                if (isset($editor))
                {
                    $columnEditors[$column->GetName()]['Html'] = $this->Render($editor, false, true);
                    $columnEditors[$column->GetName()]['Script'] = $this->Render($editor, true, false);
                }
            }

            $this->DisplayTemplate('inline_operations/grid_edit_error_response.tpl',
                array(
                        'ErrorMessage' => $grid->GetErrorMessage(),
                        'ColumnEditors' => $columnEditors,
                        ),
                    array()
                    );
            
            return;
        }

        $rowStyleByColumns = $grid->GetRowStylesByColumn($grid->GetDataset()->GetFieldValues());
        $columns = array();
        foreach($grid->GetViewColumns() as $column)
        {
            $columnName = $dataset->IsLookupField($column->GetName()) ?
                $dataset->IsLookupFieldNameByDisplayFieldName($column->GetName()) :
                $column->GetName();


            $columns[$column->GetName()]['Value'] = $grid->RenderColumn($this, $column);
            $columns[$column->GetName()]['AfterRowControl'] = $this->Render($column->GetAfterRowControl());
            $columns[$column->GetName()]['Style'] = $rowStyleByColumns[$columnName];

        }

        $primaryKeys = array();
        if ($grid->GetAllowDeleteSelected())
            $primaryKeys = $grid->GetDataset()->GetPrimaryKeyValues();

        $this->DisplayTemplate('inline_operations/grid_insert_commit_response.tpl',
            array(
                'encoding' => $grid->GetPage()->GetContentEncoding(),
                'AllowDeleteSelected' => $grid->GetAllowDeleteSelected(),
                'PrimaryKeys' => $primaryKeys,
                'Columns' => $columns,
                'RecordUID' => Random::GetIntRandom()
            ),
            array()
        );
    }

    protected function ShowHtmlNullValue() 
    {
        return true;
    }
}
