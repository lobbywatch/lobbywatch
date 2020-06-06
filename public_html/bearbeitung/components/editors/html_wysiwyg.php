<?php

include_once dirname(__FILE__) . '/' . 'custom.php';
include_once dirname(__FILE__) . '/' . '../utils/array_wrapper.php';

class HTMLTemplate {
    public $name;
    public $html;

    public function __construct($name, $html) {
        $this->name = $name;
        $this->html = $html;
    }
}

class HtmlWysiwygEditor extends CustomEditor {
    private $value;
    private $columnCount;
    private $rowCount;

    /** @var HTMLTemplate[] */
    private $templates = array();

    public function GetValue() {
        return $this->value;
    }

    public function SetValue($value) {
        $this->value = $value;
    }

    public function SetColumnCount($value) {
        $this->columnCount = $value;
    }

    public function GetColumnCount() {
        return $this->columnCount;
    }

    public function SetRowCount($value) {
        $this->rowCount = $value;
    }

    public function GetRowCount() {
        return $this->rowCount;
    }

    /**
     * @param string $name
     * @param string $html
     *
     * @return $this
     */
    public function addTemplate($name, $html) {
        $this->templates[] = new HTMLTemplate($name, $html);

        return $this;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function removeTemplate($name) {
        foreach ($this->templates as $key => $template) {
            if ($template->name == $name) {
                unset($this->templates[$key]);
            }
        }
        return $this;
    }

    /**
     * @return HTMLTemplate[]
     */
    public function getTemplates() {
        return $this->templates;
    }

    public function extractValueFromArray(ArrayWrapper $arrayWrapper, &$valueChanged) {
        if ($arrayWrapper->IsValueSet($this->GetName())) {
            $valueChanged = true;
            return $arrayWrapper->GetValue($this->GetName());
        } else {
            $valueChanged = false;
            return null;
        }
    }

    /**
     * @return string
     */
    public function getEditorName()
    {
        return 'html_wysiwyg';
    }
}
