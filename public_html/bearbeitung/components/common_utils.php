<?php

// require_once 'utils/string_utils.php';
// require_once 'utils/dataset_utils.php';

include_once dirname(__FILE__) . '/' . 'utils/string_utils.php';
include_once dirname(__FILE__) . '/' . 'utils/dataset_utils.php';

// TODO : move to dataset utils
function FormatDatasetFieldsTemplate($dataset, $template)
{
    return DatasetUtils::FormatDatasetFieldsTemplate($dataset, $template);
}

function ApplyVarablesMapToTemplate($template, $varArray)
{
    return StringUtils::ApplyVarablesMapToTemplate($template, $varArray);
}

/**
 * @param Exception $exception
 * @return string
 */
function FormatExceptionTrace($exception)
{
    return '<pre>'.$exception->getTraceAsString().'</pre>';
}

define('METHOD_POST', 1); // deprectated
define('METHOD_GET', 2); // deprectated

// TODO : deprecated function, use the SuperGlobals
function ExtractInputValue($name, $method = METHOD_GET)
{
    $result = null;
    if ($method == METHOD_GET)
    {
        if (GetApplication()->IsGETValueSet($name))
        {
            $result = GetApplication()->GetGETValue($name);
        }
    }
    elseif ($method == METHOD_POST)
    {
        if (GetApplication()->IsPOSTValueSet($name))
        {
            $result = GetApplication()->GetPOSTValue($name);
        }
    }
    return $result;
}

function ExtractPrimaryKeyValues(&$primaryKeyValues, $method = METHOD_GET)
{
    $paramNumber = 0;
    if ($method == METHOD_GET)
    {
        while(GetApplication()->IsGETValueSet("pk$paramNumber"))
        {
            $primaryKeyValues[] = GetApplication()->GetGETValue("pk$paramNumber");
            $paramNumber++;
        }
    }
    elseif ($method == METHOD_POST)
    {
        while(GetApplication()->IsPOSTValueSet("pk$paramNumber"))
        {
            $primaryKeyValues[] = GetApplication()->GetPOSTValue("pk$paramNumber");
            $paramNumber++;
        }
    }
}

function AddPrimaryKeyParametersToArray(&$targetArray, $primaryKeyValues)
{
    $paramNumber = 0;
    foreach($primaryKeyValues as $primaryKeyValue)
    {
        $targetArray["pk$paramNumber"] = $primaryKeyValue;
        $paramNumber++;
    }
}

/**
 * @param LinkBuilder $linkBuilder
 * @param array $PrimaryKeyValues
 * @return string
 */
function AddPrimaryKeyParameters($linkBuilder, $PrimaryKeyValues)
{
    $KeyValueList = '';
    $KeyValueNumber = 0;
    foreach($PrimaryKeyValues as $PrimaryKeyValue)
    {
        $linkBuilder->AddParameter("pk$KeyValueNumber", $PrimaryKeyValue);
        $KeyValueNumber ++;
    }
    return $KeyValueList;
}

function BuildPrimaryKeyLink($PrimaryKeyValues)
{
    $KeyValueList = '';
    $KeyValueNumber = 0;
    foreach($PrimaryKeyValues as $PrimaryKeyValue)
    {
        AddStr($KeyValueList, "pk$KeyValueNumber=$PrimaryKeyValue", '&');
        $KeyValueNumber ++;
    }
    return $KeyValueList;
}

// TODO : move to StringUtils and add description
function ReplaceFirst($target, $pattern, $newValue)
{
    $result = preg_replace("/(\W|\s)$pattern((\W|\s)|$)/i", "\${1}".'___SM_REMPLACEMENT_STUB___'."\${2}", $target);
    return str_replace('___SM_REMPLACEMENT_STUB___', $newValue, $result);
}

function ConvertTextToEncoding($text, $sourceEncoding, $targetEncoding)
{
    return StringUtils::ConvertTextToEncoding($text, $sourceEncoding, $targetEncoding);
}

// TODO : use StringUtils::AddStr instead
function AddStr(&$AResult, $AString, $ADelimiter = '')
{
    if(isset($AString) && $AString != '')
    {
        if(!($AResult == ''))
            $AResult = $AResult . $ADelimiter;
        $AResult = $AResult . $AString;
    }
}

function Combine($Left, $Right, $Delimiter = ' = ')
{
    return StringUtils::Combine($Left, $Right, $Delimiter);
}
