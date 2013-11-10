<?php

class ModalOperation
{
    const Param = 'mo';
    const OpenModalEditDialog = 'e';
    const OpenModalInsertDialog = 'i';
    const OpenModalCopyDialog = 'c';
}

class VerticalGridState
{
    const DisplayGrid = 0;
    const JSONResponse = 1;
    const DisplayInsertGrid = 2;
    const DisplayCopyGrid = 3;
}

class VerticalGrid
{
    private $grid;
    private $superGlobals;
    private $state;
    private $response;

    public function __construct(Grid $grid)
    {
        $this->grid = $grid;
        $this->superGlobals = GetApplication()->GetSuperGlobals();
        $this->state = VerticalGridState::DisplayGrid;
        $this->response = null;
    }

    public function Accept(Renderer $renderer)
    {
        $renderer->RenderVerticalGrid($this);
    }

    public function GetState()
    {
        return $this->state;
    }

    public function GetResponse()
    {
        return $this->response;
    }

    public function ProcessMessages()
    {
        if ($this->superGlobals->GetPostValueDef('edit_operation') == 'commit')
        {
            $this->state = VerticalGridState::JSONResponse;
            GetApplication()->SetOperation(OPERATION_COMMIT);
            $this->grid->SetState(OPERATION_COMMIT);
            $this->grid->GetState()->SetIsInlineOperation(true);

            $this->grid->ProcessMessages();


            if ($this->grid->GetErrorMessage() != '')
            {
                $this->response['type'] = 'error';
                $this->response['error_message'] = $this->grid->GetErrorMessage();
            }
            else
            {
                $this->response['type'] = 'ok';

                $this->grid->GetDataset()->SetSingleRecordState(
                    $this->grid->GetDataset()->GetPrimaryKeyValuesAfterEdit());

                GetApplication()->SetOperation(OPERATION_VIEWALL);
                $this->grid->SetState(OPERATION_VIEWALL);
                $this->grid->ProcessMessages();
                $viewAllRenderer = new ViewAllRenderer($this->grid->GetPage()->GetLocalizerCaptions());
                $viewAllRenderer->renderSingleRow = true;
                $this->response['row'] = $viewAllRenderer->Render($this->grid);
            }
        }
        else if ($this->superGlobals->GetPostValueDef('edit_operation') == 'commit_insert')
        {
            $this->state = VerticalGridState::JSONResponse;
            GetApplication()->SetOperation(OPERATION_COMMIT_INSERT);
            $this->grid->SetState(OPERATION_COMMIT_INSERT);
            $this->grid->GetState()->SetIsInlineOperation(true);
            $this->grid->GetPage()->UpdateValuesFromUrl();
            $this->grid->ProcessMessages();

            if ($this->grid->GetErrorMessage() != '')
            {
                $this->response['type'] = 'error';
                $this->response['error_message'] = $this->grid->GetErrorMessage();
            }
            else
            {
                $this->response['type'] = 'ok';

                $this->grid->GetDataset()->SetSingleRecordState(
                    $this->grid->GetDataset()->GetPrimaryKeyValuesAfterInsert());

                GetApplication()->SetOperation(OPERATION_VIEWALL);
                $this->grid->SetState(OPERATION_VIEWALL);
                $this->grid->ProcessMessages();
                $viewAllRenderer = new ViewAllRenderer($this->grid->GetPage()->GetLocalizerCaptions());
                $viewAllRenderer->renderSingleRow = true;
                $this->response['row'] = $viewAllRenderer->Render($this->grid);
            }
        }
        else
        {
            if ($this->superGlobals->GetGetValueDef(ModalOperation::Param) == ModalOperation::OpenModalEditDialog)
            {
                GetApplication()->SetOperation(OPERATION_EDIT);
                $this->grid->SetState(OPERATION_EDIT);
            }
            else if ($this->superGlobals->GetGetValueDef(ModalOperation::Param) == ModalOperation::OpenModalInsertDialog)
            {
                $this->state = VerticalGridState::DisplayInsertGrid;
                GetApplication()->SetOperation(OPERATION_INSERT);
                $this->grid->SetState(OPERATION_INSERT);
            }
            else if ($this->superGlobals->GetGetValueDef(ModalOperation::Param) == ModalOperation::OpenModalCopyDialog)
            {
                $this->state = VerticalGridState::DisplayCopyGrid;
                GetApplication()->SetOperation(OPERATION_COPY);
                $this->grid->SetState(OPERATION_COPY);
            }
             
            $this->grid->ProcessMessages();
        }
    }

    public function GetGrid() { return $this->grid; }
}
