<?php
    function smarty_function_html_indent($params, &$smarty) 
    {
        $value = $params['value'];
        $text = str_repeat('    ', $value) . $params['text'];
        $result = str_replace("\n", "\n" . str_repeat('    ', $value), $text);
        return str_replace("\n\r", "\n\r" . str_repeat('    ', $value), $result);
    }
?>
