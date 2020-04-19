<?php

require_once dirname(__FILE__) . '/abstract_view_column.php';
include_once dirname(__FILE__) . '/linked_images.php';

abstract class AbstractDatasetFieldViewColumn extends AbstractViewColumn
{
    /** @var string */
    private $dataFieldName;

    /** @var string */
    private $fieldName;

    /** @var Dataset */
    private $dataset;

    /** @var bool */
    private $orderable;

    private $bold = false;
    private $italic = false;
    private $align;
    private $customAttributes;
    private $inlineStyles;
    private $hrefTemplate;
    private $target = '_self';

    /** @var bool */
    private $displayLinkedImagesByClick = false;
    /** @var LinkedImages */
    private $linkedImages = null;
    /** @var bool */
    private $numberOfLinkedImagesToDisplayOnViewForm = 0;
    /** @var string */
    private $lookupRecordModalViewHandlerName;

    public function __construct($fieldName, $dataFieldName, $caption, Dataset $dataset, $orderable = true)
    {
        parent::__construct($caption);
        $this->dataFieldName = $dataFieldName;
        $this->fieldName = $fieldName;
        $this->dataset = $dataset;
        $this->orderable = $orderable;
    }

    public function SetOrderable($value)
    {
        $this->orderable = $value;
    }

    public function GetOrderable()
    {
        return $this->orderable;
    }

    public function GetName()
    {
        return $this->fieldName;
    }

    public function getFieldName()
    {
        return $this->dataFieldName;
    }

    public function getFieldInfo()
    {
        return $this->dataset->getSelectCommand()->getFieldByName(
            $this->fieldName
        );
    }

    public function getDataFieldInfo()
    {
        return $this->dataset->getSelectCommand()->getFieldByName(
            $this->dataFieldName
        );
    }

    /**
     * @return Dataset
     */
    public function GetDataset()
    {
        return $this->dataset;
    }

    public function GetValue()
    {
        return $this->GetDataset()->GetFieldValueByName($this->GetFieldName());
    }

    public function GetFieldType() {
        return $this->GetDataset()->GetFieldByName($this->GetFieldName())->GetEngFieldType();
    }

    public function allowSorting()
    {
        if ($this->GetGrid() != null) {
            return $this->GetOrderable() && $this->GetGrid()->allowSorting();
        } else {
            return $this->GetOrderable();
        }
    }

    public function GetActualKeys()
    {
        $keys = array(
            'Primary' => false,
            'Foreign' => false
        );

        if ($this->GetGrid()->GetShowKeyColumnsImagesInHeader()) {
            if ($this->dataset->IsFieldPrimaryKey($this->getFieldName())) {
                $keys['Primary'] = true;
            }
            if ($this->dataset->IsLookupField($this->getFieldName())) {
                $keys['Foreign'] = true;

                if ($this->dataset->IsLookupFieldNameByDisplayFieldName($this->getFieldName())) {
                    if ($this->dataset->IsFieldPrimaryKey(
                        $this->dataset->IsLookupFieldNameByDisplayFieldName($this->getFieldName())
                    )
                    ) {
                        $keys['Primary'] = true;
                    }
                }
            }
        }

        return $keys;
    }

    public function getSortIndex()
    {
        return $this->GetGrid()->getSortIndexByFieldName($this->getFieldName());
    }

    public function getSortOrderType()
    {
        return $this->GetGrid()->getSortOrderTypeByFieldName($this->getFieldName());
    }

    public function IsDataColumn()
    {
        return true;
    }

    public function setBold($bold)
    {
        $this->bold = $bold;
    }

    public function getBold()
    {
        return $this->bold;
    }

    public function setItalic($italic)
    {
        $this->italic = $italic;

        return $this;
    }

    public function getItalic()
    {
        return $this->italic;
    }

    public function setAlign($align)
    {
        $this->align = $align;

        return $this;
    }

    public function getAlign()
    {
        return $this->align;
    }

    public function setCustomAttributes($customAttributes)
    {
        $this->customAttributes = $customAttributes;

        return $this;
    }

    public function getCustomAttributes()
    {
        return $this->customAttributes;
    }

    public function setInlineStyles($inlineStyles) {
        $this->inlineStyles = $inlineStyles;
    }

    public function getInlineStyles() {
        return $this->inlineStyles;
    }

    public function setHrefTemplate($hrefTemplate)
    {
        $this->hrefTemplate = $hrefTemplate;
    }

    public function setTarget($target)
    {
        $this->target = $target;
    }

    public function getHrefTemplate()
    {
        return $this->hrefTemplate;
    }

    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @param Renderer $renderer
     *
     * @return string
     */
    public function getDisplayValue(Renderer $renderer)
    {
        $handled = false;
        $defaultRenderingResult = $renderer->Render($this);
        $result = $defaultRenderingResult;

        $this->GetGrid()->OnCustomRenderColumn->Fire(array(
            $this->GetFieldName(),
            $this->GetValue(),
            $this->dataset->GetFieldValues(),
            &$result,
            &$handled
        ));

        $result = $handled ? $result : $defaultRenderingResult;

        return $result;
    }

    /**
     * @param Renderer $renderer
     * @return void
     */
    public function Accept($renderer)
    {
        $renderer->RenderDatasetFieldViewColumn($this);
    }

    /**
     * @return array
     */
    public function getViewData()
    {
        return array(
            'Name' => $this->GetName(),
            'FieldName' => $this->GetName(),
            'Caption' => $this->GetCaption(),
            'Classes' => $this->GetGridColumnClass(),
            'Sortable' => $this->allowSorting(),
            'Keys' => $this->GetActualKeys(),
            'Comment' => $this->GetDescription(),
            'Width' => $this->GetFixedWidth(),
            'MinimalVisibility' => $this->getMinimalVisibility(),
            'SortIndex' => $this->getSortIndex(),
            'SortOrderType' => $this->getSortOrderType()
        );
    }

    /**
     * @return bool
     */
    public function getDisplayLinkedImagesByClick()
    {
        return $this->displayLinkedImagesByClick;
    }

    /**
     * @param bool $value
     * @return $this
     */
    public function setDisplayLinkedImagesByClick($value)
    {
        $this->displayLinkedImagesByClick = $value;

        return $this;
    }

    public function setLinkedImages(LinkedImages $linkedImages) {
        $this->linkedImages = $linkedImages;
    }

    public function getLinkedImages() {
        return $this->linkedImages;
    }

    public function getNumberOfLinkedImagesToDisplayOnViewForm() {
        return $this->numberOfLinkedImagesToDisplayOnViewForm;
    }

    public function setNumberOfLinkedImagesToDisplayOnViewForm($value) {
        $this->numberOfLinkedImagesToDisplayOnViewForm = $value;
    }

    public function getLinkedImagesLink()
    {
        $result = $this->GetGrid()->CreateLinkBuilder();
        $result->AddParameter('hname', $this->getName().'_handler');
        AddPrimaryKeyParameters($result, $this->GetDataset()->GetPrimaryKeyValues());

        return $result->GetLink();
    }

    public function getDataNameAttributeValue() {
        return $this->getFieldName().'_'.implode('_', $this->GetDataset()->GetPrimaryKeyValues());
    }

    public function setLookupRecordModalViewHandlerName($value) {
        $this->lookupRecordModalViewHandlerName = $value;
    }

    public function getLookupRecordModalViewLink() {
        if (!empty($this->lookupRecordModalViewHandlerName)) {
            $result = $this->GetGrid()->CreateLinkBuilder();
            $result->AddParameter('hname', $this->lookupRecordModalViewHandlerName);
            $id = $this->GetDataset()->GetFieldValueByName($this->GetName());
            $result->AddParameter('pk0', $id);
            return $result->GetLink();
        } else {
            return null;
        }
    }
}
