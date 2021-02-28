<?php
function smarty_function_to_json($params)
{
    $result = SystemUtils::ToJSON($params['value']);

    if (isset($params['escape'])) {
        return htmlspecialchars($result, ENT_QUOTES);
    }

    return $result;
}
