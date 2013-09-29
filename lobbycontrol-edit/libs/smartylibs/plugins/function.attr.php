<?php

function smarty_function_attr($params, &$smarty)
{
    $name = $params['name'];
    $value = $params['value'];

    if (!empty($value))
        return sprintf("%s=\"%s\"", $name, $value);
    return '';
}

?>
