<?php
function smarty_function_styleoption($params, &$smarty) 
{
    $name = $params['name'];
    $value = $params['value'];
    if (isset($value))
        return "$name: $value; ";
    else
        return '';
}
?>