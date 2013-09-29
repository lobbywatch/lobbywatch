<?php

function smarty_block_smart_strip($params, $content, &$smarty) 
{
    if ($content) 
    {
        $hasTrallingLineBreak = false;
        if (count($content) > 0)
            if ($content[count($content)] = "\n")
                $hasTrallingLineBreak = true;
        $result = $content;
        $result = preg_replace('!>\s+!', '>', $result);
        $result = preg_replace('!\s+?<!', '<', $result);
        $result = preg_replace('!\s+?>!', '>', $result);
        $result = preg_replace('!\s+!', ' ', $result);
        if ($hasTrallingLineBreak)
            $result .= "\n";
        return $result;
    }
}

?>
