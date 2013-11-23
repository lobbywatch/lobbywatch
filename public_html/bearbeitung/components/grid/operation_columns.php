<?php

// require_once 'components/grid/columns.php';
// require_once 'components/utils/html_utils.php';

include_once dirname(__FILE__) . '/' . 'columns.php';
include_once dirname(__FILE__) . '/' . '../utils/html_utils.php';

class RowOperationByLinkColumn extends CustomViewColumn
{
    /** @var Dataset */
    private $dataset;
    private $operationName;
    private $imagePath;
    
    #region Events
    public $OnShow;
    #endregion

    private $additionalAttributes;

    /**
     * @param string $caption
     * @param string $operationName
     * @param Dataset $dataset
     * @param string|null $imagePath
     */
    function __construct($caption, $operationName, $dataset, $imagePath = null)
    {
        parent::__construct($caption);
        $this->operationName = $operationName;
        $this->dataset = $dataset;
        //
        $this->OnShow = new Event();
        $this->imagePath = $imagePath;
        $this->additionalAttributes = '';
    }

    public function SetAdditionalAttribute($name, $value)
    {
        $this->additionalAttributes .= " $name=\"$value\"";
    }

    public function GetGridColumnClass() {
        return 'data-operation';
    }

    public function GetName()
    { return $this->operationName; }
    public function GetData()
    { return $this->operationName; }

    public function GetImagePath()
    { return $this->imagePath; }
    public function SetImagePath($value)
    { $this->imagePath = $value; }

    private function GetLinkParametersForPrimaryKey()
    {
        $result = array();
        $keyValues = $this->dataset->GetPrimaryKeyValues();
        for($i = 0; $i < count($keyValues); $i++)
            $result["pk$i"] = $keyValues[$i];
        return $result;
    }

    public function SetGrid($value)
    {
        $this->grid = $value;
    }

    public function GetLink()
    {
        $result = $this->GetGrid()->CreateLinkBuilder();
        $result->AddParameter(OPERATION_PARAMNAME, $this->operationName);
        $result->AddParameters($this->GetLinkParametersForPrimaryKey());
        return $result->GetLink();
    }

    protected function CreateHeaderControl()
    {
        if ($this->GetImagePath() != null) {
            return new CustomHtmlControl('<img src="'.$this->GetImagePath().'" alt="' . $this->GetCaption() . '">');
        }
        else {
            return parent::CreateHeaderControl();
        }
    }

    public function GetFixedWidth()
    {
        return '30px';
    }

    private function GetLinkClass() {
        switch ($this->operationName) {
            case OPERATION_VIEW:
                return 'pg-icon-view-record';
            case OPERATION_EDIT:
                return 'pg-icon-edit-record';
            case OPERATION_DELETE:
                return 'pg-icon-remove-record';
            case OPERATION_COPY:
                return 'pg-icon-copy-record';
        }
        return '';
    }

    public function GetValue()
    {
        $showButton = true;
        $this->OnShow->Fire(array(&$showButton));

        if ($showButton)
        {
            if ($this->GetImagePath() != null)
            {
                return StringUtils::Format(
                    '<a href="%s" title="%s" class="view-record-action"'. $this->additionalAttributes . '>'.
                    '<i class="%s"></i>'.
                    '</a>',
                    HtmlUtils::EscapeUrl($this->GetLink()),
                    $this->GetCaption(),
                    $this->GetLinkClass()
                );
            }
            else
            {
                return StringUtils::Format(
                    '<span><a href="%s" ' . $this->additionalAttributes . '>%s</a></span>', 
                    HtmlUtils::EscapeUrl($this->GetLink()), 
                    $this->GetCaption()
                );
            }
        }
        else
            return '';
    }
}

abstract class ModalRecordOperationsColumn extends CustomViewColumn
{ }

class ModalDialogViewRowColumn extends ModalRecordOperationsColumn
{
    /** @var Dataset */
    private $dataset;
    private $handlerName;
    private $dialogTitle;
    private $imagePath;

    #region Events
    public $OnShow;
    #endregion

    /**
     * @param string $caption
     * @param Dataset $dataset
     * @param string $dialogTitle
     * @param string $handlerName
     */
    function __construct($caption, $dataset, $dialogTitle, $handlerName)
    {
        parent::__construct($caption);
        $this->dataset = $dataset;
        $this->dialogTitle = $dialogTitle;
        $this->handlerName = $handlerName;
        $this->imagePath = null;
        $this->OnShow = new Event();
    }

    public function GetGridColumnClass() {
        return 'data-operation';
    }

    public function GetImagePath() { return $this->imagePath; }
    public function SetImagePath($value) { $this->imagePath = $value; }

    public function GetName()
    {
        return 'ModalView';
    }

    private function GetLinkParametersForPrimaryKey()
    {
        $result = array();
        $keyValues = $this->dataset->GetPrimaryKeyValues();
        for($i = 0; $i < count($keyValues); $i++)
            $result["pk$i"] = $keyValues[$i];
        return $result;
    }

    public function GetLink()
    {
        $result = $this->GetGrid()->CreateLinkBuilder();
        $result->AddParameter(OPERATION_HTTPHANDLER_NAME_PARAMNAME, $this->handlerName);
        $result->AddParameters($this->GetLinkParametersForPrimaryKey());
        return $result->GetLink();
    }

    public function GetFixedWidth()
    {
        return '30px';
    }

    public function GetValue()
    {
        $showButton = true;
        $this->OnShow->Fire(array(&$showButton));

        if ($showButton)
        {
            if ($this->GetImagePath() != null)
            {
                return StringUtils::Format(
                    '<a href="#" title="%s" dialog-title="%s" modal-view="true" content-link="%s">'.
                    '<i class="pg-icon-view-record"></i>'.
                    '</a>',
                    htmlspecialchars($this->dialogTitle),
                    htmlspecialchars($this->dialogTitle),
                    $this->GetLink(),
                    htmlspecialchars($this->dialogTitle)
                );
            }
            else
            {
                return StringUtils::Format(
                        '<a href="#" dialog-title="%s" modal-view="true" content-link="%s">%s</a>',
                            htmlspecialchars($this->dialogTitle),
                            $this->GetLink(),
                            htmlspecialchars($this->dialogTitle)
                        );
            }
        }
        else
            return '';

    }
}

class ModalDialogEditRowColumn extends ModalRecordOperationsColumn
{
    /** @var Dataset */
    private $dataset;
    private $handlerName;
    private $dialogTitle;
    private $imagePath;

    #region Events
    public $OnShow;
    #endregion

    /**
     * @param string $caption
     * @param Dataset $dataset
     * @param string $dialogTitle
     * @param string $handlerName
     */
    function __construct($caption, $dataset, $dialogTitle, $handlerName)
    {
        parent::__construct($caption);
        $this->dataset = $dataset;
        $this->dialogTitle = $dialogTitle;
        $this->handlerName = $handlerName;
        $this->imagePath = null;
        $this->OnShow = new Event();
    }

    public function GetName()
    {
        return 'ModalEdit';
    }

    public function GetGridColumnClass() {
        return 'data-operation';
    }

    public function GetImagePath() { return $this->imagePath; }
    public function SetImagePath($value) { $this->imagePath = $value; }

    private function GetLinkParametersForPrimaryKey()
    {
        $result = array();
        $keyValues = $this->dataset->GetPrimaryKeyValues();
        for($i = 0; $i < count($keyValues); $i++)
            $result["pk$i"] = $keyValues[$i];
        return $result;
    }

    public function GetLink()
    {
        $result = $this->GetGrid()->CreateLinkBuilder();
        $result->AddParameter(OPERATION_HTTPHANDLER_NAME_PARAMNAME, $this->handlerName);
        $result->AddParameter(ModalOperation::Param, ModalOperation::OpenModalEditDialog);
        $result->AddParameters($this->GetLinkParametersForPrimaryKey());
        return $result->GetLink();
    }

    public function GetFixedWidth()
    {
        return '30px';
    }

    public function GetValue()
    {
        $showButton = true;
        $this->OnShow->Fire(array(&$showButton));

        if ($showButton)
        {
            if ($this->GetImagePath() != null)
            {
                return StringUtils::Format(
                    '<a href="#" content-link="%s" title="%s" dialog-title="%s"  class="view-record-action" modal-edit="true">'.
                        '<i class="pg-icon-edit-record"></i>'.
                        '</a>',
                    $this->GetLink(),
                    htmlspecialchars($this->dialogTitle),
                    htmlspecialchars($this->dialogTitle)
                );

            }
            else
            {
                return StringUtils::Format(
                    '<a href="#" content-link="%s" title="%s" dialog-title="%s"  class="view-record-action" modal-edit="true">'.
                        '%s'.
                        '</a>',
                    $this->GetLink(),
                    htmlspecialchars($this->dialogTitle),
                    htmlspecialchars($this->dialogTitle),
                    $this->GetCaption()
                );
            }
        }
        else
            return '';

    }
}

class ModalDialogCopyRowColumn extends ModalRecordOperationsColumn
{
    /** @var Dataset */
    private $dataset;
    private $handlerName;
    private $dialogTitle;
    private $imagePath;

    #region Events
    public $OnShow;
    #endregion

    public function GetGridColumnClass() {
        return 'data-operation';
    }

    /**
     * @param string $caption
     * @param Dataset $dataset
     * @param string $dialogTitle
     * @param string $handlerName
     */
    function __construct($caption, $dataset, $dialogTitle, $handlerName)
    {
        parent::__construct($caption);
        $this->dataset = $dataset;
        $this->dialogTitle = $dialogTitle;
        $this->handlerName = $handlerName;
        $this->imagePath = null;
        $this->OnShow = new Event();
    }

    public function GetImagePath() { return $this->imagePath; }
    public function SetImagePath($value) { $this->imagePath = $value; }

    private function GetLinkParametersForPrimaryKey()
    {
        $result = array();
        $keyValues = $this->dataset->GetPrimaryKeyValues();
        for($i = 0; $i < count($keyValues); $i++)
            $result["pk$i"] = $keyValues[$i];
        return $result;
    }

    public function GetName()
    {
        return 'ModalCopy';
    }

    public function GetLink()
    {
        $result = $this->GetGrid()->CreateLinkBuilder();
        $result->AddParameter(OPERATION_HTTPHANDLER_NAME_PARAMNAME, $this->handlerName);
        $result->AddParameter(ModalOperation::Param, ModalOperation::OpenModalCopyDialog);
        $result->AddParameters($this->GetLinkParametersForPrimaryKey());
        return $result->GetLink();
    }

    public function GetFixedWidth()
    {
        return '30px';
    }

    public function GetValue()
    {
        $showButton = true;
        $this->OnShow->Fire(array(&$showButton));

        if ($showButton)
        {
            if ($this->GetImagePath() != null)
            {
                return
                    StringUtils::Format(
                        '<a href="#" title="%s" dialog-title="%s" modal-copy="true" content-link="%s">'.
                        '<i class="pg-icon-copy-record"></i>'.
                        '</a>',
                        htmlspecialchars($this->dialogTitle),
                        htmlspecialchars($this->dialogTitle),
                        $this->GetLink()
                    );
            }
            else
            {
                return StringUtils::Format(
                        '<a href="#" dialog-title="%s" modal-copy="true" content-link="%s">%s</a>',
                            htmlspecialchars($this->dialogTitle),
                            $this->GetLink(),
                            htmlspecialchars($this->dialogTitle)
                        );
            }
        }
        else
            return '';

    }    
}


class InlineEditRowColumn extends CustomViewColumn
{
    /** @var Dataset */
    private $dataset;
    private $cancelButtonText;
    private $commitButtonText;
    private $editButtonText;
    private $useImages;
    public $OnShow;

    public function GetGridColumnClass() {
        return 'data-operation';
    }

    /**
     * @param string $caption
     * @param Dataset $dataset
     * @param string $editButtonText
     * @param string $cancelButtonText
     * @param string $commitButtonText
     * @param bool $useImages
     */
    function __construct($caption, $dataset, $editButtonText, $cancelButtonText, $commitButtonText, $useImages = true)
    {
        parent::__construct($caption);
        $this->dataset = $dataset;
        $this->editButtonText = $editButtonText;
        $this->cancelButtonText = $cancelButtonText;
        $this->commitButtonText = $commitButtonText;
        $this->useImages = $useImages;
        $this->OnShow = new Event();
    }

    public function GetName()
    { 
        return 'InlineEdit';
    }
    
    public function GetData()
    { 
        return 'InlineEdit';
    }

    public function SetGrid($value)
    {
        $this->grid = $value;
    }

    public function GetFixedWidth()
    {
        return '30px';
    }

    public function GetValue()
    {
        $showButton = true;
        $this->OnShow->Fire(array(&$showButton));

        if ($showButton)
        {
            if ($this->useImages)
                AddStr($result, '<span class="inline_edit_controls default-fade-in fade-out-on-hover" style="white-space: nowrap;">');
            else
                AddStr($result, '<span class="inline_edit_controls" style="white-space: nowrap;">');

            if ($this->useImages)
            {
                AddStr($result, '<a href="#" class="inline_edit_init" title="' . $this->editButtonText . '">' .
                    //'<img src="images/inline_edit.png" title="' . $this->editButtonText . '">'
                    '<i class="pg-icon-edit-record"></i>'
                    . '</a>');
                AddStr($result, '<a href="#" style="display: none;" class="inline_edit_cancel" title="' . $this->cancelButtonText . '">' . '<i class="pg-icon-inline-edit-cancel" title="' . $this->cancelButtonText . '"></i>' . '</a>');
                AddStr($result, '<a href="#" style="display: none;" class="inline_edit_commit" title="' . $this->commitButtonText . '">' . '<i class="pg-icon-inline-edit-commit" title="' . $this->commitButtonText . '"></i>' . '</a>');
            }
            else
            {
                AddStr($result, '<a href="#" class="inline_edit_init" title="' . $this->editButtonText . '">'  . $this->editButtonText . '</a>');        
                AddStr($result, '<a style="margin-right: 5px;" href="#" class="inline_edit_cancel" title="' . $this->cancelButtonText . '">' . $this->cancelButtonText . '</a>');
                AddStr($result, '<a href="#" class="inline_edit_commit" title="' . $this->commitButtonText . '">' . $this->commitButtonText . '</a>');
            }

            $keyValues = $this->dataset->GetPrimaryKeyValues();
            for($i = 0; $i < count($keyValues); $i++)
                // AddStr($result, sprintf('<input type="hidden" name="pk%d" value="%s"></input>', $i, $keyValues[$i]));
                AddStr($result, sprintf('<input type="hidden" name="pk%d" value="%s"/>', $i, $keyValues[$i]));

            AddStr($result, '</span>');

            return $result;
        }
        else
            return '';
    }
}
