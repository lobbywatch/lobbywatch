<?php

class TextViewColumn extends AbstractDatasetFieldViewColumn
{
    private $fieldName;
    private $maxLength;
    private $replaceLFByBR;
    private $escapeHTMLSpecialChars;
    private $fullTextWindowHandlerName;

    public function __construct($fieldName, $datasetFieldName, $caption, $dataset, $orderable = true)
    {
        parent::__construct($fieldName, $datasetFieldName, $caption, $dataset, $orderable);
        $this->fieldName = $fieldName;
        $this->maxLength = null;
        $this->replaceLFByBR = false;
        $this->escapeHTMLSpecialChars = false;
        $this->fullTextWindowHandlerName = null;
    }

    public function GetMoreLink()
    {
        $result = $this->GetGrid()->CreateLinkBuilder();
        if ($this->GetFullTextWindowHandlerName() != null) {
            $result->AddParameter('hname', $this->GetFullTextWindowHandlerName());
        } else {
            $result->AddParameter('hname', $this->GetFieldName().'_handler');
        }

        AddPrimaryKeyParameters($result, $this->GetDataset()->GetPrimaryKeyValues());

        return $result->GetLink();
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

    public function SetFullTextWindowHandlerName($value)
    {
        $this->fullTextWindowHandlerName = $value;
    }

    public function GetFullTextWindowHandlerName()
    {
        return $this->fullTextWindowHandlerName;
    }

    #endregion
}
