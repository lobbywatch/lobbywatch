<?php

class TextViewColumn extends AbstractDatasetFieldViewColumn
{
    private $maxLength;
    private $replaceLFByBR;
    private $escapeHTMLSpecialChars;

    public function __construct($fieldName, $datasetFieldName, $caption, $dataset, $orderable = true)
    {
        parent::__construct($fieldName, $datasetFieldName, $caption, $dataset, $orderable);
        
        $this->maxLength = null;
        $this->replaceLFByBR = false;
        $this->escapeHTMLSpecialChars = false;
    }

    /**
     * @param Renderer $renderer
     * @return void
     */
    public function Accept($renderer)
    {
        $renderer->RenderTextViewColumn($this);
    }

    #region Column options

    public function SetMaxLength($value)
    {
        $this->maxLength = $value;
    }

    public function GetMaxLength()
    {
        return $this->maxLength;
    }

    public function SetReplaceLFByBR($value)
    {
        $this->replaceLFByBR = $value;
    }

    public function GetReplaceLFByBR()
    {
        return $this->replaceLFByBR;
    }

    public function SetEscapeHTMLSpecialChars($value)
    {
        $this->escapeHTMLSpecialChars = $value;
    }

    public function GetEscapeHTMLSpecialChars()
    {
        return $this->escapeHTMLSpecialChars;
    }

    #endregion
}
