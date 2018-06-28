<?php

// require_once 'utils/string_utils.php';

include_once dirname(__FILE__) . '/' . 'utils/string_utils.php';


interface IVariableContainer
{
    public function FillVariablesValues(&$values);
}

class NullVariableContainer implements IVariableContainer
{
    /* <IVariableContainer implementation> */
    public function FillVariablesValues(&$values)
    {
        $values = array();
    }
    /* </IVariableContainer implementation> */
}

class SimpleVariableContainer implements IVariableContainer {

    private $variables = array();

    public function FillVariablesValues(&$values)
    {
        $values = $this->variables;
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function addVariable($name, $value) {
        $this->variables[$name] = $value;
    }
}

class CompositeVariableContainer implements IVariableContainer
{
    /** @var IVariableContainer[] */
    private $containers;

    private function AddVariableContainer(IVariableContainer $variableContainer)
    {
        $this->containers[] = $variableContainer;
    }

    public function __construct()
    {
        $this->containers = array();
        //
        $args = func_get_args();
        foreach($args as $variableContainer)
            $this->AddVariableContainer($variableContainer);    
    }

    public function FillVariablesValues(&$values)
    {
        $values = array();
        foreach($this->containers as $variableContainer)
        {
            $containerValues = array();
            $variableContainer->FillVariablesValues($containerValues);
            $values = array_merge($values, $containerValues);
        }
    }
}

class ServerVariablesContainer implements IVariableContainer
{
    /* <IVariableContainer implementation> */
    public function FillVariablesValues(&$values) {
        foreach($_SERVER as $server_key => $server_value)
            $values[$server_key] = $server_value;
    }
    /* </IVariableContainer implementation> */
}

class SystemFunctionsVariablesContainer implements IVariableContainer
{
    /* <IVariableContainer implementation> */
    public function FillVariablesValues(&$values) {
        $values['CURRENT_DATETIME'] = date('d-m-Y H:i:s');
        $values['CURRENT_DATE'] = date('d-m-Y');
        $values['CURRENT_TIME'] = date('H:i:s');
        $values['CURRENT_DATETIME_ISO_8601'] = date('c');
        $values['CURRENT_DATETIME_RFC_2822'] = date('c');
        $values['CURRENT_UNIX_TIMESTAMP'] = date('U');
    }
    /* </IVariableContainer implementation> */
}

class EnvVariablesUtils
{
    public static function EvaluateVariableTemplate(IVariableContainer $variableContainer, $template)
    {
        $result = $template;
        $values = array();
        $variableContainer->FillVariablesValues($values);
        foreach($values as $name => $value)
            $result = StringUtils::ReplaceVariableInTemplate($result, $name, $value);
        return $result;
    }
}
