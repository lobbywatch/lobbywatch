<?php

function smarty_function_jsbool($params, &$smarty)
{
    $value = $params['value'];

    return $value ? 'true' : 'false';
}

?>
