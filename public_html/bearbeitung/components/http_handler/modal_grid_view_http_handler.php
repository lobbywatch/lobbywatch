<?php

include_once dirname(__FILE__) . '/abstract_http_handler.php';
include_once dirname(__FILE__) . '/../grid/record_card_view.php';

class ModalGridViewHandler extends AbstractHTTPHandler
{
    /** @var \RecordCardView */
    private $recordCardView;

    public function __construct($name, RecordCardView $recordCardView)
    {
        parent::__construct($name);
        $this->recordCardView = $recordCardView;
    }

    public function Render(Renderer $renderer)
    {
        $this->recordCardView->ProcessMessages();
        echo $renderer->Render($this->recordCardView);
    }
}
