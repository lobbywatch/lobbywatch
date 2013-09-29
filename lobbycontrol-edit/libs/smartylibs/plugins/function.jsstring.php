<?php

class EscapeMode
{
    const DoubleQuote = 1;
    const SingleQuote = 2;
}

function JSStringLiteral($stringLiteral, $mode = EscapeMode::SingleQuote)
{
    switch ($mode)
    {
        case EscapeMode::DoubleQuote:
            $searches = array( '"', "\n" );
            $replacements = array( '\\"', "\\n\"\n\t+\"" );
            $quote = '"';
            break;
        case EscapeMode::SingleQuote:
            $searches = array( "'", "\n" );
            $replacements = array( "\\'", "\\n'\n\t+'" );
            $quote = "'";
            break;
    }
    return $quote . str_replace( $searches, $replacements, $stringLiteral ) . $quote;
}

function smarty_function_jsstring($params, &$smarty)
{
    return htmlspecialchars(JSStringLiteral($params['value']));
}

?>
