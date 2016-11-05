<?php

include_once dirname(__FILE__) . '/abstract_http_handler.php';

class ModalDeleteHandler extends AbstractHTTPHandler
{
    /**
     * @var Grid
     */
    private $grid;

    /**
     * @param string $name
     * @param Grid $grid
     */
    public function __construct($name, Grid $grid)
    {
        parent::__construct($name);
        $this->grid = $grid;
    }

    public function Render(Renderer $renderer)
    {
        GetApplication()->SetOperation(OPERATION_COMMIT_DELETE);
        $this->grid->SetState(OPERATION_COMMIT_DELETE);
        $this->grid->GetState()->SetUseGetToExtractPrimaryKeys(true);
        $this->grid->ProcessMessages();

        $message = current($this->grid->getMessages());
        $response = array(
            'success' => true,
            'message' => $message ? $message['message'] : null,
            'messageDisplayTime' => $message ? $message['displayTime'] : 0,
        );

        $errorMessage = current($this->grid->getErrorMessages());
        if ($errorMessage) {
            $response['success'] = false;
            $response['message'] = $errorMessage ? $errorMessage['message'] : null;
            $response['messageDisplayTime'] = $errorMessage ? $errorMessage['displayTime'] : 0;
        }

        header('Content-Type: application/json');
        echo SystemUtils::ToJSON($response);
    }
}
