<?php

require_once 'utils/string_utils.php';

interface IVariableContainer
{
    public function FillVariablesValues(&$values);

    public function FillAvailableVariables(&$variables);
}

class CompositeVariableContainer implements IVariableContainer
{
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

    public function FillAvailableVariables(&$variables)
    {
        $variables = array();
        foreach($this->containers as $variableContainer)
        {
            $containerVariables = array();
            $variableContainer->FillAvailableVariables($containerVariables);
            $variables = array_merge($variables, $containerVariables);
        }
    }
}

class ServerVariablesContainer implements IVariableContainer
{
    /* <IVariableContainer implementation> */
    public function FillVariablesValues(&$values)
    {
        $values = array();
        foreach($_SERVER as $server_key => $server_value)
            $values[$server_key] = $server_value;
    }

    public function FillAvailableVariables(&$variables)
    {
        $variables = array_keys($_SERVER);
    }
    /* </IVariableContainer implementation> */
}

class SystemFunctionsVariablesContainer implements IVariableContainer
{
    /* <IVariableContainer implementation> */
    private $variableFuncs = array(
        'CURRENT_DATETIME'    => 'return date(\'d-m-Y H:i:s\');',
        'CURRENT_DATE'    => 'return date(\'d-m-Y\');',
        'CURRENT_TIME'    => 'return date(\'H:i:s\');',
        'CURRENT_DATETIME_ISO_8601'    => 'return date(\'c\');',
        'CURRENT_DATETIME_RFC_2822'    => 'return date(\'c\');',
        'CURRENT_UNIX_TIMESTAMP'    => 'return date(\'U\');'
        );

    public function FillVariablesValues(&$values)
    {
        $values = array();
        foreach($this->variableFuncs as $name => $code)
        {
            $function = create_function('', $code);
            $values[$name] = $function($this);
        }
    }

    public function FillAvailableVariables(&$variables)
    {
        return array_keys($this->variableFuncs);
    }
    /* </IVariableContainer implementation> */
}

class NullVariableContainer implements IVariableContainer
{
    /* <IVariableContainer implementation> */
    public function FillVariablesValues(&$values)
    {
        $values = array();
    }

    public function FillAvailableVariables(&$variables)
    {
        $variables = array();
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

?>