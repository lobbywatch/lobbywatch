<?php
set_include_path('../..' . PATH_SEPARATOR . get_include_path());
include_once 'components/captions.php';

header('Content-Type: application/javascript');

$captions = GetCaptions('UTF-8');

/**
 * @param Captions $captions
 * @param string $code
 * @internal param string $value
 * @param bool $suppressComma
 * @return void
 */
function BuildLocalizationString($captions, $code, $suppressComma = false)
{
    echo sprintf('"%s": "%s"', $code, $captions->GetMessageString($code));
    if (!$suppressComma)
        echo ',';
}

?>
define(function(require, exports) {

exports.resource = {<?php

BuildLocalizationString($captions, 'Cancel');
BuildLocalizationString($captions, 'Commit');
BuildLocalizationString($captions, 'ErrorsDuringUpdateProcess');
BuildLocalizationString($captions, 'PasswordChanged');

BuildLocalizationString($captions, 'Equals');
BuildLocalizationString($captions, 'DoesNotEquals');
BuildLocalizationString($captions, 'IsLessThan');
BuildLocalizationString($captions, 'IsLessThanOrEqualsTo');
BuildLocalizationString($captions, 'IsGreaterThan');
BuildLocalizationString($captions, 'IsGreaterThanOrEqualsTo');
BuildLocalizationString($captions, 'Like');
BuildLocalizationString($captions, 'IsBlank');
BuildLocalizationString($captions, 'IsNotBlank');
BuildLocalizationString($captions, 'IsLike');
BuildLocalizationString($captions, 'IsNotLike');

BuildLocalizationString($captions, 'Contains');
BuildLocalizationString($captions, 'DoesNotContain');
BuildLocalizationString($captions, 'BeginsWith');
BuildLocalizationString($captions, 'EndsWith');

BuildLocalizationString($captions, 'And');
BuildLocalizationString($captions, 'Or');
BuildLocalizationString($captions, 'Xor');

BuildLocalizationString($captions, 'Loading');
BuildLocalizationString($captions, 'FilterBuilder');
BuildLocalizationString($captions, 'DeleteSelectedRecordsQuestion');

BuildLocalizationString($captions, 'AddGroup');
BuildLocalizationString($captions, 'AndCondition');
BuildLocalizationString($captions, 'RemoveFromFilter');
BuildLocalizationString($captions, 'DeleteRecordQuestion');

BuildLocalizationString($captions, 'FilterOperatorEquals');
BuildLocalizationString($captions, 'FilterOperatorDoesNotEqual');
BuildLocalizationString($captions, 'FilterOperatorIsGreaterThan');
BuildLocalizationString($captions, 'FilterOperatorIsGreaterThanOrEqualTo');
BuildLocalizationString($captions, 'FilterOperatorIsLessThan');
BuildLocalizationString($captions, 'FilterOperatorIsLessThanOrEqualTo');
BuildLocalizationString($captions, 'FilterOperatorIsBetween');
BuildLocalizationString($captions, 'FilterOperatorIsNotBetween');
BuildLocalizationString($captions, 'FilterOperatorContains');
BuildLocalizationString($captions, 'FilterOperatorDoesNotContain');
BuildLocalizationString($captions, 'FilterOperatorBeginsWith');
BuildLocalizationString($captions, 'FilterOperatorEndsWith');
BuildLocalizationString($captions, 'FilterOperatorIsLike');
BuildLocalizationString($captions, 'FilterOperatorIsNotLike');



BuildLocalizationString($captions, 'SaveAndInsert');
BuildLocalizationString($captions, 'SaveAndBackToList');
BuildLocalizationString($captions, 'SaveAndEdit');
BuildLocalizationString($captions, 'Save', true);


?>};

});