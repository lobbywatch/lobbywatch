<?php

function smarty_function_parity($params, Smarty &$smarty)
{
    return
        $smarty->_foreach[$params['name']]['iteration'] % 2 == 1 ? 'odd' : 'even';
}

?>
