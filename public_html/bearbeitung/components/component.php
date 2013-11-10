<?php

require_once 'utils/string_utils.php';

class Component
{
    /** @var string */
    private $name;

    /** @var boolean */
    private $allowNullValue;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
        $this->allowNullValue = true;
    }

    /**
     * @return string
     */
    function GetName() { return $this->name; }

    /**
     * @param string $value
     * @return void
     */
    function SetName($value) { $this->name = $value; }

    public function ProcessMessages()
    { }

    public function CanSetupNullValues()
    { return false; }

    protected function DoSetAllowNullValue($value)
    { }
    public function SetAllowNullValue($value)
    {
        if ($this->allowNullValue != $value)
        {
            $this->allowNullValue = $value;
            $this->DoSetAllowNullValue($value);
        }
    }
    public function GetAllowNullValue()
    { return $this->allowNullValue; }

    public function GetSetToNullFromPost()
    { return false; }
}

class NullComponent extends Component
{
    public function Accept(Renderer $Renderer)
    {
        $Renderer->RenderComponent($this);
    }
    
    public function ExtractsValueFromPost()
    {
        return '';   
    }
}

class TextBox extends Component
{
    private $caption;

    public function GetCaption()
    {
        return $this->caption;
    }

    public function SetCaption($caption)
    {
        $this->caption = $caption;
    }

    public function __construct($name, $caption)
    {
        Component::__construct($name);
        $this->caption = $caption;
    }

    public function Accept(Renderer $Renderer)
    {
        $Renderer->RenderTextBox($this);
    }
}

class Image extends Component
{
    private $source;

    public function GetSource()
    {
        return $this->source;
    }

    public function SetSource($source)
    {
        $this->source = $source;
    }

    public function Accept(Renderer $Renderer)
    {
        $Renderer->RenderImage($this);
    }

    public function __construct($source)
    {
        $this->source = $source;
    }
}

class CustomHtmlControl extends Component
{
    private $html;

    public function GetHtml()
    {
        return $this->html;
    }

    public function SetHtml($html)
    {
        $this->html = $html;
    }

    public function __construct($html)
    {
        $this->html = $html;
    }

    public function Accept(Renderer $renderer)
    {
        $renderer->RenderCustomHtmlControl($this);
    }

}

class HyperLink extends Component
{
    private $innerText; 
    private $afterLinkText; 
    private $link;
    
    public function __construct($name, $innerText, $link = '#')
    {
        parent::__construct($name);
        $this->innerText = $innerText;  
        $this->link = $link;            
    }            
    
    public function GetAfterLinkText() { return $this->afterLinkText; } 
    public function SetAfterLinkText($value) { $this->afterLinkText = $value; }
    
    public function GetInnerText() { return $this->innerText; } 
    public function SetInnerText($value) { $this->innerText = $value; }
    
    public function GetLink() { return $this->link; }
    public function SetLink($value) { $this->link = $value; }
    
    public function Accept(Renderer $renderer)
    {
        $renderer->RenderHyperLink($this);
    }        
}

class HintedTextBox extends Component
{
    private $innerText;
    private $afterLinkText;
    private $hint;

    public function __construct($name, $innerText)
    {
        parent::__construct($name);
        $this->innerText = $innerText;
        $this->hint = '';
    }

    public function GetHint()
    { return $this->hint; }
    public function SetHint($value)
    { $this->hint = $value; }

    public function GetAfterLinkText() { return $this->afterLinkText; }
    public function SetAfterLinkText($value) { $this->afterLinkText = $value; }

    public function GetInnerText() { return $this->innerText; }
    public function SetInnerText($value) { $this->innerText = $value; }

    public function Accept(Renderer $renderer)
    {
        $renderer->RenderHintedTextBox($this);
    }
}

?>