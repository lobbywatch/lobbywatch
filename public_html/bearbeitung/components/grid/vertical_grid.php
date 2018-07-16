<?php

class VerticalGrid
{
    private $grid;
    private $isCommit = false;
    private $isNested = false;
    private $operation;
    private $response;
    private $superGlobals;

    public function __construct(Grid $grid, $operation, $isNested = false)
    {
        $this->grid = $grid;
        $this->superGlobals = GetApplication()->GetSuperGlobals();
        $this->operation = $operation;
        $this->isNested = $isNested;
    }

    public function ProcessMessages()
    {
        $serverWrapper = ArrayWrapper::createServerWrapper();
        if ($serverWrapper->getValue('REQUEST_METHOD') === 'POST') {
            $this->ProcessCommit($this->operation);
            return;
        }

        GetApplication()->SetOperation($this->operation);
        $this->grid->SetState($this->operation);

        $this->grid->ProcessMessages();
    }

    private function ProcessCommit($operation)
    {
        $this->grid->setPopFlashMessages(false);
        $this->isCommit = true;

        if ($operation == OPERATION_EDIT) {
            GetApplication()->SetOperation(OPERATION_COMMIT_EDIT);
            $this->grid->SetState(OPERATION_COMMIT_EDIT);
        } elseif ($operation == OPERATION_MULTI_EDIT) {
            GetApplication()->SetOperation(OPERATION_COMMIT_MULTI_EDIT);
            $this->grid->SetState(OPERATION_COMMIT_MULTI_EDIT);
        } elseif ($operation == OPERATION_MULTI_UPLOAD) {
            GetApplication()->SetOperation(OPERATION_COMMIT_MULTI_UPLOAD);
            $this->grid->SetState(OPERATION_COMMIT_MULTI_UPLOAD);
        } else {
            GetApplication()->SetOperation(OPERATION_COMMIT_INSERT);
            $this->grid->SetState(OPERATION_COMMIT_INSERT);
        }

        $this->grid->GetPage()->UpdateValuesFromUrl();
        $this->grid->ProcessMessages();

        $message = current($this->grid->getMessages());
        $this->response = array(
            'success' => true,
            'message' => $message ? $message['message'] : null,
            'messageDisplayTime' => $message ? $message['displayTime'] : 0,
        );

        if (count($this->grid->getErrorMessages()) > 0) {
            $errorMessage = current($this->grid->getErrorMessages());
            $this->response['success'] = false;
            $this->response['message'] = $errorMessage ? $errorMessage['message'] : null;
            $this->response['messageDisplayTime'] = $errorMessage ? $errorMessage['displayTime'] : 0;
            return;
        }

        if ($operation == OPERATION_MULTI_EDIT) {
            $this->processMultiEditCommit();
            return;
        }

        $isEdit = ($operation == OPERATION_EDIT);
        $primaryKeys = $isEdit
            ? $this->grid->GetDataset()->GetPrimaryKeyValuesAfterEdit()
            : $this->grid->GetDataset()->GetPrimaryKeyValuesAfterInsert();

        $this->grid->GetDataset()->GetSelectCommand()->ClearAllFilters();
        $this->grid->GetDataset()->SetSingleRecordState($primaryKeys);
        $this->grid->GetDataset()->Open();
        $this->grid->GetDataset()->Next();
        $this->response['record'] = $this->grid->GetDataset()->GetCurrentFieldValues(true);

        $captions = $this->grid->GetPage()->GetLocalizerCaptions();
        $viewRenderer = new ViewRenderer($captions);
        $viewColumns = $this->grid->GetSingleRecordViewColumns();
        $this->response['columns'] = array();
        foreach ($viewColumns as $viewColumn) {
            $this->response['columns'][$viewColumn->GetName()] = $viewColumn->getDisplayValue($viewRenderer);
        }

        $this->grid->GetDataset()->SetSingleRecordState($primaryKeys);
        $this->response['editUrl'] = $this->grid->GetEditCurrentRecordLink($primaryKeys);
        $responseDetails = array();
        foreach ($this->grid->GetDetailLinksViewData() as $detail) {
            $responseDetails[] = $detail['Link'];
        }
        $this->response['details'] = $responseDetails;

        GetApplication()->SetOperation(OPERATION_VIEWALL);
        $this->grid->SetState(OPERATION_VIEWALL);
        $this->grid->ProcessMessages();

        if (ArrayWrapper::createPostWrapper()->getValue('flash_messages', false) && $this->response['message']) {
            $this->grid->addFlashMessage($this->response['message'], $this->response['messageDisplayTime']);
        }

        $viewAllRenderer = new ViewAllRenderer($captions);
        $viewAllRenderer->renderSingleRow = true;
        $this->response['row'] = $viewAllRenderer->Render($this->grid);
        $this->response['primaryKeys'] = $primaryKeys;
    }

    private function processMultiEditCommit()
    {
        $primaryKeysFromPost = ArrayWrapper::createPostWrapper()->getValue('keys', array());
        $primaryFields = $this->grid->GetDataset()->GetPrimaryKeyFieldNames();

        foreach ($primaryKeysFromPost as $primaryKeysValues) {
            $this->grid->GetDataset()->GetSelectCommand()->ClearAllFilters();
            $this->grid->GetDataset()->Close();
            foreach ($primaryKeysValues as $i => $value) {
                $this->grid->GetDataset()->AddFieldFilter($primaryFields[$i], FieldFilter::Equals($value));
            }
            $this->grid->GetDataset()->Open();
            $this->grid->GetDataset()->Next();
            $primaryKeys = $this->grid->GetDataset()->GetPrimaryKeyValues();

            $viewAllRenderer = new ViewAllRenderer($this->grid->GetPage()->GetLocalizerCaptions());
            $viewAllRenderer->renderSingleRow = true;
            $this->response['row'][] = $viewAllRenderer->Render($this->grid);
            $this->response['primaryKeys'][] = SystemUtils::ToJSON($primaryKeys);
        }
    }

    public function Accept(Renderer $renderer)
    {
        $renderer->RenderVerticalGrid($this);
    }

    public function GetGrid()
    {
        return $this->grid;
    }

    public function GetResponse()
    {
        return $this->response;
    }

    public function isCommit()
    {
        return $this->isCommit;
    }

    public function isNested()
    {
        return $this->isNested;
    }

    public function getOperation()
    {
        return $this->operation;
    }

    public function isModal()
    {
        return (bool) ArrayWrapper::createGetWrapper()->getValue('is_modal', false);
    }

    public function isInline()
    {
        return (bool) ArrayWrapper::createGetWrapper()->getValue('is_inline', false);
    }
}
