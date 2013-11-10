<?php

function smarty_compiler_while($tag_arg, &$smarty)
{
    return 'while(' . $tag_arg . ') { ';
}
?>
