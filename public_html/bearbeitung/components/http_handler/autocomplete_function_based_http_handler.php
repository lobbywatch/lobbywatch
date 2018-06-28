<?php

include_once dirname(__FILE__) . '/abstract_http_handler.php';

class AutocompleteFunctionBasedHTTPHandler extends AbstractHTTPHandler
{
    /** @var \Event */
    public $OnGetSuggestions;

    /** @inheritdoc */
    public function __construct($name) {
        parent::__construct($name);
        $this->OnGetSuggestions = new Event();
    }

    public function Render(Renderer $renderer)
    {
        $getWrapper = ArrayWrapper::createGetWrapper();
        $term = trim($getWrapper->getValue('query', ''));

        $suggestions = array();
        $this->OnGetSuggestions->Fire(array($term, &$suggestions));
        $result['suggestions'] = $suggestions;

        header('Content-Type: application/json; charset=utf-8');
        return SystemUtils::ToJSON($result);
    }

}
