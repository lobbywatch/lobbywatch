<?php

class AjaxOperation extends BaseRowOperation
{
    const MODAL = 'modal';
    const INLINE = 'inline';

    /**
     * @var string
     */
    private $handlerName;

    /**
     * @var string
     */
    private $dialogTitle;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    protected $operationName;

    /**
     * @param string $operationName
     * @param string $caption
     * @param string $dialogTitle
     * @param Dataset $dataset
     * @param string $handlerName
     * @param null|Grid $grid
     * @param null|string $type
     */
    function __construct($operationName, $caption, $dialogTitle, $dataset, $handlerName, $grid = null, $type = null)
    {
        parent::__construct($caption, $handlerName, $dataset, $grid);
        $this->operationName = $operationName;
        $this->dialogTitle = $dialogTitle;
        $this->handlerName = $handlerName;
        $this->type = is_null($type) ? self::MODAL : self::INLINE;
    }

    public function GetLink()
    {
        $result = $this->GetGrid()->CreateLinkBuilder();
        $result->AddParameter(OPERATION_HTTPHANDLER_NAME_PARAMNAME, $this->handlerName);
        $result->AddParameters($this->GetLinkParametersForPrimaryKey());

        return $result->GetLink();
    }

    public function GetValue()
    {
        $showButton = true;
        $this->OnShow->Fire(array(&$showButton));

        $result = false;

        if ($showButton) {
            $result = array(
                'type' => $this->type,
                'name' => $this->operationName,
                'useImage' => $this->isUseImage(),
                'link' => HtmlUtils::EscapeUrl($this->GetLink()),
                'caption' => $this->GetCaption(),
                'dialogTitle' => htmlspecialchars($this->dialogTitle)
            );
        }

        return $result;

    }
}
