<?php

class LinkOperation extends BaseRowOperation
{
    /**
     * @var string
     */
    private $additionalAttributes;

    /**
     * @param string $caption
     * @param string $operationName
     * @param Dataset $dataset
     * @param Grid $grid
     */
    function __construct($caption, $operationName, $dataset, $grid)
    {
        parent::__construct($caption, $operationName, $dataset, $grid);
        $this->additionalAttributes = array();
    }

    public function SetAdditionalAttribute($name, $value)
    {
        $this->additionalAttributes[$name] = $value;
    }

    public function GetLink()
    {
        $result = $this->GetGrid()->CreateLinkBuilder();
        $result->AddParameter(OPERATION_PARAMNAME, $this->operationName);
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
                'type' => 'link',
                'name' => $this->GetName(),
                'useImage' => $this->isUseImage(),
                'link' => HtmlUtils::EscapeUrl($this->GetLink()),
                'caption' => $this->GetCaption(),
                'additionalAttributes' => $this->additionalAttributes
            );
        }

        return $result;
    }
}
