<?php

include_once dirname(__FILE__) . '/' . '../../libs/smartylibs/Smarty.class.php';

interface ITemplateRenderer
{
    public function render($templateName, $params);
}

class SmartyTemplateRenderer implements ITemplateRenderer
{
    /** @var Smarty */
    private $smarty;

    /**
     * @param string $templateDirectory
     * @param string $compileDirectory
     */
    public function __construct($templateDirectory = 'components/templates', $compileDirectory = 'templates_c') {
        $this->smarty = new Smarty();
        $this->smarty->template_dir = $templateDirectory;
        $this->smarty->compile_dir = $compileDirectory;
    }

    /**
     * @param string $templateName
     * @param array $params
     * @return string
     */
    public function render($templateName, $params) {
        foreach($params as $key => &$value) {
            if (is_object($value)) {
                $this->smarty->assign_by_ref($key, $value);
            } else {
                $this->smarty->assign($key, $value);
            }
        }

        return $this->smarty->fetch($templateName);
    }

}

function GetTemplateRenderer() {
    return new SmartyTemplateRenderer();
}
